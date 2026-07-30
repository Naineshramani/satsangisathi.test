<?php

namespace App\Imports;

use App\Models\Astrology;
use App\Models\Career;
use App\Models\Education;
use App\Models\Family;
use App\Models\Lifestyle;
use App\Models\Member;
use App\Models\OnBehalf;
use App\Models\Package;
use App\Models\PhysicalAttribute;
use App\Models\SatsangDetail;
use App\Models\SpiritualBackground;
use App\Models\User;
use App\Services\BulkImportLookupResolver;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Bulk-creates member profiles from an admin-uploaded spreadsheet, covering
 * every profile section a member normally fills in themselves (except
 * Partner Expectation, deferred). Each row is processed independently in
 * its own DB transaction, so one bad row can never affect any other --
 * failures are collected with a row number and reason instead of being
 * silently swallowed (see the old app/Models/MembersImport.php this
 * replaces, which caught and discarded every exception).
 */
class MembersBulkImport implements ToCollection, WithHeadingRow
{
    private BulkImportLookupResolver $resolver;
    private array $failures = [];
    private int $successCount = 0;
    private array $seenPhones = [];
    private array $seenEmails = [];
    private string $codePrefix;
    private int $codeCounter;

    /** Satsang Details columns -> [satsang_options category, model attribute] */
    private const SATSANG_FIELDS = [
        'follower_of_sect'         => ['sect', 'follower_of_sect_id'],
        'nitya_pooja_daily'        => ['nitya_pooja', 'nitya_pooja_daily_id'],
        'wear_kanthi'              => ['kanthi', 'wear_kanthi_id'],
        'eat_onion_garlic'         => ['onion_garlic', 'eat_onion_garlic_id'],
        'perform_aarti_ghar_sabha' => ['aarti', 'perform_aarti_id'],
        'observe_sampradaya_fasts' => ['fasts', 'observe_fasts_id'],
        'temple_visit_frequency'   => ['temple_frequency', 'temple_visit_frequency_id'],
    ];

    private const EMPLOYMENT_TYPES = [
        'job'           => 'job',
        'business'      => 'business',
        'self-employed' => 'self_employed',
        'self employed' => 'self_employed',
        'not working'   => 'not_working',
    ];

    public function __construct()
    {
        $this->resolver   = new BulkImportLookupResolver();
        $this->codePrefix = get_setting('member_code_prifix') . date('Ym');
        $this->codeCounter = (int) (DB::table('users')->max('id') ?? 0);
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Heading row is spreadsheet row 1, so the first data row is row 2.
            $rowNumber = $index + 2;

            if ($row->filter(fn ($v) => trim((string) $v) !== '')->isEmpty()) {
                continue; // skip fully blank rows
            }

            try {
                $this->importRow($row);
                $this->successCount++;
            } catch (\Throwable $e) {
                $this->failures[] = [
                    'row'    => $rowNumber,
                    'reason' => $e->getMessage(),
                    'data'   => $row->toArray(),
                ];
            }
        }
    }

    public function failures(): array
    {
        return $this->failures;
    }

    public function successCount(): int
    {
        return $this->successCount;
    }

    private function importRow(Collection $row): void
    {
        $get = fn ($key) => trim((string) ($row[$key] ?? ''));

        // ---- Required account fields ----
        $phone    = $this->normalizePhone($get('candidate_mobile_no'));
        $email    = $get('email_address') ?: null;
        $password = $get('password');
        $firstName = $get('first_name');
        $lastName  = $get('last_name');
        $genderRaw = $get('gender');
        $dobRaw    = $get('date_of_birth');
        $maritalStatusName = $get('marital_status');

        if ($phone === '') {
            throw new \RuntimeException('Candidate Mobile No is required');
        }
        if (strlen($password) < 8) {
            throw new \RuntimeException('Password is required and must be at least 8 characters');
        }
        if ($firstName === '') {
            throw new \RuntimeException('First Name is required');
        }
        if ($lastName === '') {
            throw new \RuntimeException('Last Name is required');
        }
        if ($maritalStatusName === '') {
            throw new \RuntimeException('Marital Status is required');
        }

        $gender = $this->resolveGender($genderRaw);
        if (!$gender) {
            throw new \RuntimeException("Gender '{$genderRaw}' not recognized -- use Male or Female");
        }

        $birthday = $this->parseDate($dobRaw);
        if (!$birthday) {
            throw new \RuntimeException("Date Of Birth '{$dobRaw}' is not a valid date");
        }

        $maritalStatusId = $this->resolver->maritalStatus($maritalStatusName);
        if (!$maritalStatusId) {
            throw new \RuntimeException("Marital Status '{$maritalStatusName}' not found -- check the reference list");
        }

        if (isset($this->seenPhones[$phone]) || User::withTrashed()->where('phone', $phone)->exists()) {
            throw new \RuntimeException("Phone '{$phone}' already exists");
        }
        if ($email && (isset($this->seenEmails[$email]) || User::withTrashed()->where('email', $email)->exists())) {
            throw new \RuntimeException("Email '{$email}' already exists");
        }

        $onBehalfName = $get('on_behalf');
        $onBehalfId = null;
        if (OnBehalf::count() > 0) {
            if ($onBehalfName === '') {
                throw new \RuntimeException('On Behalf is required');
            }
            $onBehalfId = $this->resolver->onBehalf($onBehalfName);
            if (!$onBehalfId) {
                throw new \RuntimeException("On Behalf '{$onBehalfName}' not found -- check the reference list");
            }
        }

        $packageName = $get('package');
        if ($packageName !== '') {
            $packageId = $this->resolver->package($packageName);
            if (!$packageId) {
                throw new \RuntimeException("Package '{$packageName}' not found -- check the reference list");
            }
            $package = Package::find($packageId);
        } else {
            $package = Package::where('price', 0)->first();
            if (!$package) {
                throw new \RuntimeException('Package is required -- no default free package is configured');
            }
        }

        DB::transaction(function () use (
            $row, $get, $phone, $email, $password, $firstName, $lastName,
            $gender, $birthday, $maritalStatusId, $onBehalfId, $package
        ) {
            $this->codeCounter++;
            $code = $this->codePrefix . $this->codeCounter;

            $approved = get_setting('member_verification') == 1 ? 0 : 1;

            $user = new User;
            $user->user_type = 'member';
            $user->code = $code;
            $user->first_name = $firstName;
            $user->last_name = $lastName;
            $user->email = $email;
            $user->email_verified_at = now();
            $user->password = Hash::make($password);
            $user->phone = $phone;
            $user->membership = $package->price > 0 ? 2 : 1;
            $user->approved = $approved;
            $user->approved_at = $approved == 1 ? now() : null;
            $user->save();

            $this->seenPhones[$phone] = true;
            if ($email) {
                $this->seenEmails[$email] = true;
            }

            $member = new Member;
            $member->user_id = $user->id;
            $member->gender = $gender;
            $member->birthday = $birthday;
            $member->on_behalves_id = $onBehalfId;
            $member->marital_status_id = $maritalStatusId;

            $childrenRaw = $get('number_of_children');
            if (in_array($maritalStatusId, [2, 3]) && $childrenRaw !== '' && is_numeric($childrenRaw)) {
                $member->children = (int) $childrenRaw;
            }

            $member->current_package_id = $package->id;
            $member->remaining_interest = $package->express_interest;
            $member->remaining_contact_view = $package->contact;
            $member->remaining_photo_gallery = $package->photo_gallery;
            $member->auto_profile_match = $package->auto_profile_match;
            $member->auto_horoscope_profile_match = $package->auto_horoscope_profile_match;
            $member->package_validity = date('Y-m-d', strtotime($package->validity . ' days'));

            $motherTongueName = $get('mother_tongue');
            if ($motherTongueName !== '') {
                $mtId = $this->resolver->memberLanguage($motherTongueName);
                if (!$mtId) {
                    throw new \RuntimeException("Mother Tongue '{$motherTongueName}' not found -- check the reference list");
                }
                $member->mothere_tongue = $mtId;
            }

            $knownLanguagesRaw = $get('known_languages');
            if ($knownLanguagesRaw !== '') {
                $ids = $this->resolver->knownLanguages($knownLanguagesRaw);
                if ($ids !== null) {
                    if (in_array(false, $ids, true)) {
                        throw new \RuntimeException("Known Languages '{$knownLanguagesRaw}' contains a name not found -- check the reference list");
                    }
                    $member->known_languages = json_encode($ids);
                }
            }

            $introduction = $get('introduction');
            if ($introduction !== '') {
                $member->introduction = $introduction;
            }

            $member->save();

            $this->importPhysicalAttributes($get, $user->id);
            $this->importAddress($get, $user->id, 'present', 'present_address_country', 'present_address_state', 'present_address_city');
            $this->importAddress($get, $user->id, 'permanent', 'permanent_address_country', 'permanent_address_state', 'permanent_address_city');
            $this->importEducation($get, $user->id);
            $this->importCareer($get, $user->id);
            $this->importSpiritualBackground($get, $user->id);
            $this->importSatsangDetails($get, $user->id, $gender);
            $this->importLifestyle($get, $user->id);
            $this->importAstrology($get, $user->id, $birthday);
            $this->importFamily($get, $user->id);
        });
    }

    private function importPhysicalAttributes(callable $get, int $userId): void
    {
        $heightFt   = $get('height_ft');
        $heightIn   = $get('height_inch');
        $weight     = $get('weight');
        $disability = $get('disability_if_any');

        if ($heightFt === '' && $weight === '' && $disability === '') {
            return;
        }

        $physical = new PhysicalAttribute;
        $physical->user_id = $userId;
        if ($heightFt !== '' && is_numeric($heightFt)) {
            $physical->height = $heightFt . '.' . ($heightIn !== '' && is_numeric($heightIn) ? $heightIn : '0');
        }
        if ($weight !== '' && is_numeric($weight)) {
            $physical->weight = $weight;
        }
        if ($disability !== '') {
            $physical->disability = $disability;
        }
        $physical->save();
    }

    private function importAddress(callable $get, int $userId, string $type, string $countryKey, string $stateKey, string $cityKey): void
    {
        $countryName = $get($countryKey);
        $stateName   = $get($stateKey);
        $cityName    = $get($cityKey);

        if ($countryName === '' && $stateName === '' && $cityName === '') {
            return;
        }

        $countryId = $this->resolver->country($countryName);
        if (!$countryId) {
            $label = $countryName !== '' ? $countryName : 'India';
            throw new \RuntimeException("{$type} address country '{$label}' not found");
        }

        $stateId = null;
        if ($stateName !== '') {
            $stateId = $this->resolver->state($stateName, $countryId);
            if (!$stateId) {
                throw new \RuntimeException("{$type} address state '{$stateName}' not found for the given country");
            }
        }

        $cityId = null;
        if ($cityName !== '') {
            if (!$stateId) {
                throw new \RuntimeException("{$type} address city '{$cityName}' given without a matching state");
            }
            $cityId = $this->resolver->city($cityName, $stateId);
            if (!$cityId) {
                throw new \RuntimeException("{$type} address city '{$cityName}' not found for the given state");
            }
        }

        $address = new \App\Models\Address;
        $address->user_id = $userId;
        $address->type = $type;
        $address->country_id = $countryId;
        $address->state_id = $stateId;
        $address->city_id = $cityId;
        $address->save();
    }

    private function importEducation(callable $get, int $userId): void
    {
        $degree = $get('degree');
        if ($degree === '') {
            return;
        }

        $education = new Education;
        $education->user_id = $userId;
        $education->degree = $degree;
        $spec = $get('specialization');
        $education->specialization = $spec !== '' ? $spec : null;
        $inst = $get('institution');
        $education->institution = $inst !== '' ? $inst : null;
        $education->save();
    }

    private function importCareer(callable $get, int $userId): void
    {
        $typeRaw = $get('type');
        if ($typeRaw === '') {
            return;
        }

        $type = self::EMPLOYMENT_TYPES[strtolower($typeRaw)] ?? null;
        if (!$type) {
            throw new \RuntimeException("Career Type '{$typeRaw}' not recognized -- use Job, Business, Self-Employed, or Not Working");
        }

        $roleNature = $get('role_nature');
        $company    = $get('company_business');

        $career = new Career;
        $career->user_id = $userId;
        $career->employment_type = $type;
        $career->designation = in_array($type, ['job', 'self_employed']) && $roleNature !== '' ? $roleNature : null;
        $career->nature_of_business = $type === 'business' && $roleNature !== '' ? $roleNature : null;
        $career->company = $company !== '' ? $company : null;
        $career->currency = $get('currency') ?: 'INR';

        $monthly = $get('monthly_income');
        $yearly  = $get('yearly_income');
        $career->start = $monthly !== '' && is_numeric($monthly) ? $monthly : null;
        $career->end   = $yearly !== '' && is_numeric($yearly) ? $yearly : null;

        $career->save();
    }

    private function importSpiritualBackground(callable $get, int $userId): void
    {
        $casteName = $get('caste');
        if ($casteName === '') {
            return;
        }

        $casteId = $this->resolver->caste($casteName);
        if (!$casteId) {
            throw new \RuntimeException("Caste '{$casteName}' not found -- check the reference list");
        }

        $spiritual = new SpiritualBackground;
        $spiritual->user_id = $userId;
        $spiritual->religion_id = 1; // Hindu -- the only religion configured in this system
        $spiritual->caste_id = $casteId;
        $spiritual->save();
    }

    private function importSatsangDetails(callable $get, int $userId, int $gender): void
    {
        $nameOfMandal = $get('name_of_the_mandal');
        $volunteer    = $get('volunteer_activities');
        $defineYourself = $get('define_yourself_as_satsangi');

        $resolved = [];
        $anyValue = $nameOfMandal !== '' || $volunteer !== '' || $defineYourself !== '';

        foreach (self::SATSANG_FIELDS as $column => [$category, $attribute]) {
            $value = $get($column);
            if ($value === '') {
                continue;
            }
            $anyValue = true;
            $optionId = $this->resolver->satsangOption($category, $value);
            if ($optionId === false) {
                $label = str_replace('_', ' ', $column);
                throw new \RuntimeException("Satsang field '{$label}' value '{$value}' not found -- check the reference list");
            }
            $resolved[$attribute] = $optionId;
        }

        if (!$anyValue) {
            return;
        }

        $detail = new SatsangDetail;
        $detail->user_id = $userId;
        foreach ($resolved as $attribute => $optionId) {
            $detail->{$attribute} = $optionId;
        }
        $detail->name_of_mandal = $nameOfMandal !== '' ? $nameOfMandal : null;
        $detail->volunteer_activities = $volunteer !== '' ? $volunteer : null;
        $detail->define_yourself_satsangi = $defineYourself !== '' ? $defineYourself : null;
        $detail->wear_kanthi_tilak_id = null; // legacy column, superseded by wear_kanthi_id
        $detail->save();
    }

    private function importLifestyle(callable $get, int $userId): void
    {
        $diet = $get('diet');
        $drink = strtolower($get('drink'));
        $smoke = strtolower($get('smoke'));
        $livingWith = $get('living_with');

        if ($diet === '' && $drink === '' && $smoke === '' && $livingWith === '') {
            return;
        }

        $lifestyle = new Lifestyle;
        $lifestyle->user_id = $userId;
        $lifestyle->diet = $diet !== '' ? $diet : null;
        $lifestyle->drink = in_array($drink, ['yes', 'no']) ? $drink : null;
        $lifestyle->smoke = in_array($smoke, ['yes', 'no']) ? $smoke : null;
        $lifestyle->living_with = $livingWith !== '' ? $livingWith : null;
        $lifestyle->save();
    }

    private function importAstrology(callable $get, int $userId, string $birthday): void
    {
        $timeOfBirth = $get('time_of_birth');
        $cityOfBirth = $get('city_of_birth');
        $moonSign    = $get('moon_sign');

        if ($timeOfBirth === '' && $cityOfBirth === '' && $moonSign === '') {
            return;
        }

        $astrology = new Astrology;
        $astrology->user_id = $userId;
        $astrology->time_of_birth = $timeOfBirth !== '' ? $timeOfBirth : null;
        $astrology->city_of_birth = $cityOfBirth !== '' ? $cityOfBirth : null;
        $astrology->moon_sign = $moonSign !== '' ? $moonSign : null;
        $astrology->sun_sign = Astrology::calculateSunSign($birthday);
        $astrology->save();
    }

    private function importFamily(callable $get, int $userId): void
    {
        $father = $get('father');
        $fatherOccupation = $get('father_occupation');
        $mother = $get('mother');
        $motherOccupation = $get('mother_occupation');

        if ($father === '' && $fatherOccupation === '' && $mother === '' && $motherOccupation === '') {
            return;
        }

        $family = new Family;
        $family->user_id = $userId;
        $family->father = $father !== '' ? $father : null;
        $family->father_occupation = $fatherOccupation !== '' ? $fatherOccupation : null;
        $family->mother = $mother !== '' ? $mother : null;
        $family->mother_occupation = $motherOccupation !== '' ? $motherOccupation : null;
        $family->save();
    }

    private function resolveGender(string $value): ?int
    {
        return match (strtolower($value)) {
            'male', '1' => 1,
            'female', '2' => 2,
            default => null,
        };
    }

    private function parseDate(string $value): ?string
    {
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizePhone(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }
        return '+' . preg_replace('/\D+/', '', $value);
    }
}

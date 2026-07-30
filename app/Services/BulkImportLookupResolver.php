<?php

namespace App\Services;

use App\Models\Caste;
use App\Models\City;
use App\Models\Country;
use App\Models\MaritalStatus;
use App\Models\MemberLanguage;
use App\Models\OnBehalf;
use App\Models\Package;
use App\Models\SatsangOption;
use App\Models\State;
use Illuminate\Support\Collection;

/**
 * Resolves human-readable names (as typed into the bulk-import spreadsheet)
 * to the ids the profile tables actually store.
 *
 * Small lookup tables (On Behalf, Package, Marital Status, Member Language,
 * Caste, Satsang Options) are preloaded once per import run and matched
 * case-insensitively/trimmed. Country/State/City are far too large to
 * preload (State has ~4k rows, City ~48k) so those are resolved with a
 * direct, scoped, case-insensitive query per lookup instead.
 */
class BulkImportLookupResolver
{
    private Collection $onBehalves;
    private Collection $packages;
    private Collection $maritalStatuses;
    private Collection $memberLanguages;
    private Collection $castes;
    private Collection $satsangOptions;

    public function __construct()
    {
        $this->onBehalves     = $this->nameMap(OnBehalf::all());
        $this->packages       = $this->nameMap(Package::all());
        $this->maritalStatuses = $this->nameMap(MaritalStatus::all());
        $this->memberLanguages = $this->nameMap(MemberLanguage::all());
        $this->castes          = $this->nameMap(Caste::all());

        $this->satsangOptions = SatsangOption::all()
            ->groupBy('category')
            ->map(fn ($group) => $this->nameMap($group));
    }

    private function nameMap(Collection $rows): Collection
    {
        return $rows->mapWithKeys(fn ($row) => [$this->normalize($row->name) => $row->id]);
    }

    private function normalize(?string $value): string
    {
        return strtolower(trim((string) $value));
    }

    public function onBehalf(?string $name): ?int
    {
        $name = trim((string) $name);
        return $name === '' ? null : ($this->onBehalves[$this->normalize($name)] ?? null);
    }

    public function package(?string $name): ?int
    {
        $name = trim((string) $name);
        return $name === '' ? null : ($this->packages[$this->normalize($name)] ?? null);
    }

    public function maritalStatus(?string $name): ?int
    {
        $name = trim((string) $name);
        return $name === '' ? null : ($this->maritalStatuses[$this->normalize($name)] ?? null);
    }

    public function memberLanguage(?string $name): ?int
    {
        $name = trim((string) $name);
        return $name === '' ? null : ($this->memberLanguages[$this->normalize($name)] ?? null);
    }

    public function caste(?string $name): ?int
    {
        $name = trim((string) $name);
        return $name === '' ? null : ($this->castes[$this->normalize($name)] ?? null);
    }

    /**
     * @return int|false null if blank (nothing to resolve), false if the
     *   category/name pair genuinely doesn't match anything.
     */
    public function satsangOption(string $category, ?string $name): int|false|null
    {
        $name = trim((string) $name);
        if ($name === '') {
            return null;
        }
        $group = $this->satsangOptions->get($category);
        if (!$group) {
            return false;
        }
        return $group[$this->normalize($name)] ?? false;
    }

    /**
     * Comma-separated language names -> array of member_languages ids.
     * Returns null if input is blank, or an array with a false entry
     * standing in for any name that couldn't be matched (caller reports it).
     */
    public function knownLanguages(?string $commaSeparated): ?array
    {
        $commaSeparated = trim((string) $commaSeparated);
        if ($commaSeparated === '') {
            return null;
        }

        return collect(explode(',', $commaSeparated))
            ->map(fn ($name) => trim($name))
            ->filter(fn ($name) => $name !== '')
            ->map(fn ($name) => $this->memberLanguages[$this->normalize($name)] ?? false)
            ->values()
            ->all();
    }

    public function country(?string $name): ?int
    {
        $name = trim((string) $name);
        if ($name === '') {
            $name = 'India';
        }
        return Country::whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->orWhereRaw('LOWER(code) = ?', [strtolower($name)])
            ->value('id');
    }

    public function state(?string $name, ?int $countryId): ?int
    {
        $name = trim((string) $name);
        if ($name === '' || !$countryId) {
            return null;
        }
        return State::where('country_id', $countryId)
            ->whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->value('id');
    }

    public function city(?string $name, ?int $stateId): ?int
    {
        $name = trim((string) $name);
        if ($name === '' || !$stateId) {
            return null;
        }
        return City::where('state_id', $stateId)
            ->whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->value('id');
    }
}

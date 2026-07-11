<!DOCTYPE html>
<html>
<head>
<style>
    @page {
        margin: 22mm 16mm 26mm 16mm;
        background-color: #fdf6e3;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'DejaVuSans', sans-serif;
        color: #4a2e00;
        font-size: 10pt;
        line-height: 1.5;
    }
    .site-header {
        position: fixed;
        top: -18mm;
        left: 0;
        right: 0;
        height: 14mm;
    }
    .site-header .brand {
        font-size: 15pt;
        font-weight: bold;
        color: #a6741a;
    }
    .site-header .tagline {
        font-size: 8pt;
        color: #8b0000;
    }
    .site-footer {
        position: fixed;
        bottom: -22mm;
        left: -16mm;
        right: -16mm;
        height: 18mm;
        background: #8b0000;
        color: #f5e6c8;
        text-align: center;
        padding-top: 5mm;
        font-size: 9pt;
    }
    .site-footer .site-name {
        font-weight: bold;
        font-size: 11pt;
        color: #ffd77a;
    }
    .page-frame {
        border: 2px solid #c9a24b;
        padding: 3mm;
    }
    .page-frame-inner {
        border: 1px solid #c9a24b;
        padding: 6mm;
    }
    h2.section-title {
        background: #f2e2b6;
        color: #7a3e00;
        border-left: 4px solid #8b0000;
        padding: 2mm 4mm;
        font-size: 11pt;
        margin: 5mm 0 2mm 0;
    }
    table.details { width: 100%; border-collapse: collapse; margin-bottom: 2mm; }
    table.details td { padding: 1.2mm 2mm; vertical-align: top; font-size: 9.5pt; }
    table.details td.label { width: 34%; color: #7a3e00; font-weight: bold; }
    table.details td.label2 { width: 16%; color: #7a3e00; font-weight: bold; }
    .identity-box { width: 100%; margin-bottom: 3mm; }
    .identity-box .photo-cell { width: 32mm; vertical-align: top; }
    .identity-box .photo-cell img {
        width: 30mm; height: 36mm; object-fit: cover; border: 2px solid #c9a24b;
    }
    .identity-box .name-cell { vertical-align: top; padding-left: 5mm; }
    .identity-box .name-cell .name { font-size: 16pt; font-weight: bold; color: #7a3e00; }
    .identity-box .name-cell .code { font-size: 9pt; color: #8b0000; margin-bottom: 2mm; }
    .paragraph { font-size: 9.5pt; text-align: justify; padding: 1mm 2mm; }
</style>
</head>
<body>

<div class="site-header">
    <table style="width:100%;">
        <tr>
            <td style="width:60%;">
                <div class="brand">{{ get_setting('site_name') ?: 'Satsangi Sathi' }}</div>
                <div class="tagline">{{ translate('For Followers of Lord Swaminarayan') }}</div>
            </td>
            <td style="width:40%;text-align:right;">
                @if (get_setting('header_logo'))
                    <img src="{{ uploaded_asset(get_setting('header_logo')) }}" style="height:16mm;">
                @endif
            </td>
        </tr>
    </table>
</div>

<div class="site-footer">
    <div class="site-name">{{ get_setting('site_name') ?: 'Satsangi Sathi' }}</div>
    <div>{{ preg_replace('#^https?://#', '', rtrim(env('APP_URL'), '/')) }}
        @if (get_setting('footer_email')) &nbsp;|&nbsp; {{ get_setting('footer_email') }} @endif
        @if (get_setting('footer_address')) &nbsp;|&nbsp; {{ get_setting('footer_address') }} @endif
    </div>
</div>

<div class="page-frame">
<div class="page-frame-inner">

    @php
        $m = $user->member;
        $age = !empty($m->birthday) ? \Carbon\Carbon::parse($m->birthday)->age : null;
        $present_address = $user->addresses->where('type', 'present')->first();
        $permanent_address = $user->addresses->where('type', 'permanent')->first();
        $native_address = $user->addresses->where('type', 'native')->first();
        $satsang = $user->satsang_details;
        $satsang_opt = function ($id) {
            return $id ? optional(\App\Models\SatsangOption::find($id))->name : '-';
        };
    @endphp

    <table class="identity-box">
        <tr>
            <td class="photo-cell">
                @if (show_profile_picture($user) && $user->photo)
                    <img src="{{ uploaded_asset($user->photo) }}">
                @else
                    <img src="{{ static_asset($m->gender == 2 ? 'assets/img/female-avatar-place.png' : 'assets/img/avatar-place.png') }}">
                @endif
            </td>
            <td class="name-cell">
                <div class="name">{{ $user->first_name }} {{ $user->last_name }}</div>
                <div class="code">{{ translate('Member ID') }}: {{ $user->code }}</div>
                <table class="details" style="margin:0;">
                    <tr>
                        <td class="label2">{{ translate('Age') }}</td>
                        <td>{{ $age ?? '-' }} {{ translate('yrs') }}</td>
                        <td class="label2">{{ translate('Gender') }}</td>
                        <td>{{ $m->gender == 1 ? translate('Male') : ($m->gender == 2 ? translate('Female') : '-') }}</td>
                    </tr>
                    <tr>
                        <td class="label2">{{ translate('Marital Status') }}</td>
                        <td>{{ optional($m->marital_status)->name ?? '-' }}</td>
                        <td class="label2">{{ translate('Height') }}</td>
                        <td>{{ optional($user->physical_attributes)->height ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @if (!empty($m->introduction))
        <h2 class="section-title">{{ translate('About') }}</h2>
        <div class="paragraph">{{ $m->introduction }}</div>
    @endif

    <h2 class="section-title">{{ translate('Basic Information') }}</h2>
    <table class="details">
        <tr>
            <td class="label">{{ translate('Date of Birth') }}</td>
            <td>{{ !empty($m->birthday) ? \Carbon\Carbon::parse($m->birthday)->format('d M Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">{{ translate('Mother Tongue') }}</td>
            <td>{{ optional($m->mothereTongue)->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">{{ translate('No. of Children') }}</td>
            <td>{{ $m->children ?? '-' }}</td>
        </tr>
    </table>

    <h2 class="section-title">{{ translate('Satsang Details') }}</h2>
    <table class="details">
        <tr>
            <td class="label">{{ translate('Follower of (Sect)') }}</td>
            <td>{{ optional($satsang)->follower_of_sect_id ? $satsang_opt($satsang->follower_of_sect_id) : '-' }}</td>
        </tr>
        <tr>
            <td class="label">{{ translate('Name of the Mandal') }}</td>
            <td>{{ optional($satsang)->name_of_mandal ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">{{ translate('Nitya Pooja Daily') }}</td>
            <td>{{ optional($satsang)->nitya_pooja_daily_id ? $satsang_opt($satsang->nitya_pooja_daily_id) : '-' }}</td>
        </tr>
        <tr>
            <td class="label">{{ translate('Wear Kanthi') }}</td>
            <td>{{ optional($satsang)->wear_kanthi_id ? $satsang_opt($satsang->wear_kanthi_id) : '-' }}</td>
        </tr>
        @if ($m->gender == 1)
            <tr>
                <td class="label">{{ translate('Wear Tilak Chandlo') }}</td>
                <td>{{ optional($satsang)->wear_tilak_chandlo_id ? $satsang_opt($satsang->wear_tilak_chandlo_id) : '-' }}</td>
            </tr>
        @endif
        <tr>
            <td class="label">{{ translate('Eat Onion / Garlic') }}</td>
            <td>{{ optional($satsang)->eat_onion_garlic_id ? $satsang_opt($satsang->eat_onion_garlic_id) : '-' }}</td>
        </tr>
        <tr>
            <td class="label">{{ translate('Perform Aarti / Ghar Sabha') }}</td>
            <td>{{ optional($satsang)->perform_aarti_id ? $satsang_opt($satsang->perform_aarti_id) : '-' }}</td>
        </tr>
        <tr>
            <td class="label">{{ translate('Observes Sampradaya Fasts') }}</td>
            <td>{{ optional($satsang)->observe_fasts_id ? $satsang_opt($satsang->observe_fasts_id) : '-' }}</td>
        </tr>
        <tr>
            <td class="label">{{ translate('Temple Visit Frequency') }}</td>
            <td>{{ optional($satsang)->temple_visit_frequency_id ? $satsang_opt($satsang->temple_visit_frequency_id) : '-' }}</td>
        </tr>
        <tr>
            <td class="label">{{ translate('Volunteer Activities') }}</td>
            <td>{{ optional($satsang)->volunteer_activities ?? '-' }}</td>
        </tr>
    </table>
    @if (optional($satsang)->define_yourself_satsangi)
        <div class="paragraph">{{ $satsang->define_yourself_satsangi }}</div>
    @endif

    <h2 class="section-title">{{ translate('Spiritual & Social Background') }}</h2>
    <table class="details">
        <tr>
            <td class="label">{{ translate('Caste') }}</td>
            <td>{{ optional($user->spiritual_backgrounds)->caste->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">{{ translate('Family Value') }}</td>
            <td>{{ optional($user->spiritual_backgrounds)->family_value->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">{{ translate('Family Status') }}</td>
            <td>{{ optional($user->spiritual_backgrounds)->family_status->name ?? '-' }}</td>
        </tr>
    </table>

    <h2 class="section-title">{{ translate('Education') }}</h2>
    <table class="details">
        <tr>
            <td class="label2">{{ translate('Degree') }}</td>
            <td class="label2">{{ translate('Specialization') }}</td>
            <td class="label2">{{ translate('Institution') }}</td>
            <td class="label2">{{ translate('Years') }}</td>
        </tr>
        @forelse ($user->education as $edu)
            <tr>
                <td>{{ $edu->degree }}</td>
                <td>{{ $edu->specialization }}</td>
                <td>{{ $edu->institution }}</td>
                <td>{{ $edu->start }} - {{ $edu->end ?: translate('Present') }}</td>
            </tr>
        @empty
            <tr><td colspan="4">-</td></tr>
        @endforelse
    </table>

    <h2 class="section-title">{{ translate('Career') }}</h2>
    <table class="details">
        @forelse ($user->career as $career)
            <tr>
                <td class="label">{{ $career->designation ?: ucfirst(str_replace('_', ' ', $career->employment_type)) }}</td>
                <td>{{ $career->company }} @if($career->present) ({{ translate('Current') }}) @endif</td>
            </tr>
        @empty
            <tr><td colspan="2">-</td></tr>
        @endforelse
    </table>

    <h2 class="section-title">{{ translate('Physical Attributes') }}</h2>
    <table class="details">
        <tr>
            <td class="label2">{{ translate('Height') }}</td>
            <td>{{ optional($user->physical_attributes)->height ?? '-' }}</td>
            <td class="label2">{{ translate('Weight') }}</td>
            <td>{{ optional($user->physical_attributes)->weight ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label2">{{ translate('Complexion') }}</td>
            <td>{{ optional($user->physical_attributes)->complexion ?? '-' }}</td>
            <td class="label2">{{ translate('Body Type') }}</td>
            <td>{{ optional($user->physical_attributes)->body_type ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label2">{{ translate('Blood Group') }}</td>
            <td>{{ optional($user->physical_attributes)->blood_group ?? '-' }}</td>
            <td class="label2">{{ translate('Disability') }}</td>
            <td>{{ optional($user->physical_attributes)->disability ?? '-' }}</td>
        </tr>
    </table>

    <h2 class="section-title">{{ translate('Lifestyle') }}</h2>
    <table class="details">
        <tr>
            <td class="label2">{{ translate('Diet') }}</td>
            <td>{{ optional($user->lifestyles)->diet ?? '-' }}</td>
            <td class="label2">{{ translate('Living With') }}</td>
            <td>{{ optional($user->lifestyles)->living_with ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label2">{{ translate('Smoke') }}</td>
            <td>{{ optional($user->lifestyles)->smoke ?? '-' }}</td>
            <td class="label2">{{ translate('Drink') }}</td>
            <td>{{ optional($user->lifestyles)->drink ?? '-' }}</td>
        </tr>
    </table>

    <h2 class="section-title">{{ translate('Astronomic Information') }}</h2>
    <table class="details">
        <tr>
            <td class="label2">{{ translate('Sun Sign') }}</td>
            <td>{{ optional($user->astrologies)->sun_sign ?? '-' }}</td>
            <td class="label2">{{ translate('Moon Sign') }}</td>
            <td>{{ optional($user->astrologies)->moon_sign ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label2">{{ translate('Time of Birth') }}</td>
            <td>{{ optional($user->astrologies)->time_of_birth ?? '-' }}</td>
            <td class="label2">{{ translate('City of Birth') }}</td>
            <td>{{ optional($user->astrologies)->city_of_birth ?? '-' }}</td>
        </tr>
    </table>

    <h2 class="section-title">{{ translate('Family Information') }}</h2>
    <table class="details">
        <tr>
            <td class="label2">{{ translate('Father') }}</td>
            <td>{{ optional($user->families)->father ?? '-' }}</td>
            <td class="label2">{{ translate('Father Occupation') }}</td>
            <td>{{ optional($user->families)->father_occupation ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label2">{{ translate('Mother') }}</td>
            <td>{{ optional($user->families)->mother ?? '-' }}</td>
            <td class="label2">{{ translate('Mother Occupation') }}</td>
            <td>{{ optional($user->families)->mother_occupation ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label2">{{ translate('No. of Brothers') }}</td>
            <td>{{ optional($user->families)->no_of_brothers ?? '-' }}</td>
            <td class="label2">{{ translate('No. of Sisters') }}</td>
            <td>{{ optional($user->families)->no_of_sisters ?? '-' }}</td>
        </tr>
    </table>
    @if (optional($user->families)->about_parents)
        <div class="paragraph"><strong>{{ translate('About Parents') }}:</strong> {{ $user->families->about_parents }}</div>
    @endif
    @if (optional($user->families)->about_siblings)
        <div class="paragraph"><strong>{{ translate('About Siblings') }}:</strong> {{ $user->families->about_siblings }}</div>
    @endif

    <h2 class="section-title">{{ translate('Hobbies & Interests') }}</h2>
    <table class="details">
        <tr>
            <td class="label2">{{ translate('Hobbies') }}</td>
            <td>{{ optional($user->hobbies)->hobbies ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label2">{{ translate('Interests') }}</td>
            <td>{{ optional($user->hobbies)->interests ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label2">{{ translate('Music') }}</td>
            <td>{{ optional($user->hobbies)->music ?? '-' }}</td>
        </tr>
    </table>

    <h2 class="section-title">{{ translate('Personal Attitude & Behavior') }}</h2>
    <table class="details">
        <tr>
            <td class="label2">{{ translate('Affection') }}</td>
            <td>{{ optional($user->attitude)->affection ?? '-' }}</td>
            <td class="label2">{{ translate('Humor') }}</td>
            <td>{{ optional($user->attitude)->humor ?? '-' }}</td>
        </tr>
    </table>

    <h2 class="section-title">{{ translate('Residency Information') }}</h2>
    <table class="details">
        <tr>
            <td class="label2">{{ translate('Birth Country') }}</td>
            <td>{{ optional(\App\Models\Country::find(optional($user->recidency)->birth_country_id))->name ?? '-' }}</td>
            <td class="label2">{{ translate('Residency Country') }}</td>
            <td>{{ optional(\App\Models\Country::find(optional($user->recidency)->recidency_country_id))->name ?? '-' }}</td>
        </tr>
    </table>

    <h2 class="section-title">{{ translate('Address') }}</h2>
    <table class="details">
        <tr>
            <td class="label">{{ translate('Present Address') }}</td>
            <td>{{ implode(', ', array_filter([optional($present_address)->city->name ?? null, optional($present_address)->state->name ?? null, optional($present_address)->country->name ?? null])) ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">{{ translate('Permanent Address') }}</td>
            <td>{{ implode(', ', array_filter([optional($permanent_address)->city->name ?? null, optional($permanent_address)->country->name ?? null])) ?: '-' }}</td>
        </tr>
        @if ($native_address)
            <tr>
                <td class="label">{{ translate('Native Village') }}</td>
                <td>{{ implode(', ', array_filter([$native_address->native_village, optional($native_address)->city->name ?? null])) ?: '-' }}</td>
            </tr>
        @endif
    </table>

    <h2 class="section-title">{{ translate('Contact Details') }}</h2>
    <table class="details">
        <tr>
            <td class="label2">{{ translate('Mobile') }}</td>
            <td>{{ $user->phone ?? '-' }}</td>
            <td class="label2">{{ translate('Email') }}</td>
            <td>{{ $user->email ?? '-' }}</td>
        </tr>
    </table>

    @if ($user->partner_expectations)
        <h2 class="section-title">{{ translate('Partner Expectation') }}</h2>
        <table class="details">
            <tr>
                <td class="label2">{{ translate('Age / Height') }}</td>
                <td>{{ $user->partner_expectations->height ?? '-' }}</td>
                <td class="label2">{{ translate('Marital Status') }}</td>
                <td>{{ optional($user->partner_expectations->marital_status)->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label2">{{ translate('Education') }}</td>
                <td>{{ $user->partner_expectations->education ?? '-' }}</td>
                <td class="label2">{{ translate('Profession') }}</td>
                <td>{{ $user->partner_expectations->profession ?? '-' }}</td>
            </tr>
        </table>
        @if ($user->partner_expectations->general)
            <div class="paragraph">{{ $user->partner_expectations->general }}</div>
        @endif
    @endif

</div>
</div>

</body>
</html>

<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:member_report']);
    }

    public function index()
    {
        $report = $this->buildReport([]);
        return view('admin.member_report.index', $report);
    }

    public function data(Request $request)
    {
        $filters = $this->filtersFromRequest($request);
        return response()->json($this->buildReport($filters));
    }

    private function filtersFromRequest(Request $request)
    {
        return [
            'gender'      => (array) $request->input('gender', []),
            'marital'     => (array) $request->input('marital', []),
            'sect'        => (array) $request->input('sect', []),
            'caste'       => (array) $request->input('caste', []),
            'city'        => (array) $request->input('city', []),
            'state'       => (array) $request->input('state', []),
            'country'     => (array) $request->input('country', []),
            'education'   => (array) $request->input('education', []),
            'profession'  => (array) $request->input('profession', []),
            'age_min'     => $request->input('age_min') !== null && $request->input('age_min') !== '' ? (int) $request->input('age_min') : null,
            'age_max'     => $request->input('age_max') !== null && $request->input('age_max') !== '' ? (int) $request->input('age_max') : null,
        ];
    }

    private function buildReport(array $filters)
    {
        $filters = array_merge([
            'gender' => [], 'marital' => [], 'sect' => [], 'caste' => [], 'city' => [],
            'state' => [], 'country' => [], 'education' => [], 'profession' => [],
            'age_min' => null, 'age_max' => null,
        ], $filters);

        $rows = $this->baseQuery($filters)
            ->select([
                'u.id',
                'u.code',
                'u.first_name',
                'u.last_name',
                'u.phone',
                'u.last_login_at',
                'm.gender',
                'm.birthday',
                'ms.name as marital_status',
                'so.name as sect',
                'c.name as caste',
                'ci.name as city',
                'st.name as state',
                'co.name as country',
            ])
            ->addSelect([
                'education' => Education::select('degree')
                    ->whereColumn('education.user_id', 'u.id')
                    ->whereNull('education.deleted_at')
                    ->orderByDesc('is_highest_degree')
                    ->limit(1),
                'profession' => Career::select('designation')
                    ->whereColumn('careers.user_id', 'u.id')
                    ->whereNull('careers.deleted_at')
                    ->orderByDesc('present')
                    ->limit(1),
            ])
            ->distinct()
            ->orderByDesc('u.id')
            ->get()
            ->map(function ($row) {
                $row->name = trim($row->first_name . ' ' . $row->last_name);
                $row->age = !empty($row->birthday) ? \Carbon\Carbon::parse($row->birthday)->age : null;
                $row->gender_label = $row->gender == 1 ? 'Male' : ($row->gender == 2 ? 'Female' : null);
                $row->last_login = $row->last_login_at
                    ? \Carbon\Carbon::parse($row->last_login_at)->format('d M Y, h:i A')
                    : 'Never';
                return $row;
            });

        $slicerDimensions = ['gender', 'marital', 'sect', 'caste', 'city', 'state', 'country', 'education', 'profession'];
        $options = [];
        foreach ($slicerDimensions as $dimension) {
            $options[$dimension] = $this->optionsFor($dimension, $filters);
        }

        return [
            'rows' => $rows,
            'options' => $options,
            'filters' => $filters,
            'count' => $rows->count(),
        ];
    }

    /**
     * Base joined query with all filters applied except the ones in $except.
     */
    private function baseQuery(array $filters, ?string $except = null)
    {
        $query = DB::table('users as u')
            ->join('members as m', 'm.user_id', '=', 'u.id')
            ->leftJoin('marital_statuses as ms', 'ms.id', '=', 'm.marital_status_id')
            ->leftJoin('satsang_details as sd', function ($j) {
                $j->on('sd.user_id', '=', 'u.id')->whereNull('sd.deleted_at');
            })
            ->leftJoin('satsang_options as so', 'so.id', '=', 'sd.follower_of_sect_id')
            ->leftJoin('spiritual_backgrounds as sb', function ($j) {
                $j->on('sb.user_id', '=', 'u.id')->whereNull('sb.deleted_at');
            })
            ->leftJoin('castes as c', 'c.id', '=', 'sb.caste_id')
            ->leftJoin('addresses as a', function ($j) {
                $j->on('a.user_id', '=', 'u.id')->where('a.type', 'present')->whereNull('a.deleted_at');
            })
            ->leftJoin('cities as ci', 'ci.id', '=', 'a.city_id')
            ->leftJoin('states as st', 'st.id', '=', 'a.state_id')
            ->leftJoin('countries as co', 'co.id', '=', 'a.country_id')
            ->where('u.user_type', 'member');

        if ($except !== 'gender' && !empty($filters['gender'])) {
            $query->whereIn('m.gender', $filters['gender']);
        }
        if ($except !== 'marital' && !empty($filters['marital'])) {
            $query->whereIn('m.marital_status_id', $filters['marital']);
        }
        if ($except !== 'sect' && !empty($filters['sect'])) {
            $query->whereIn('sd.follower_of_sect_id', $filters['sect']);
        }
        if ($except !== 'caste' && !empty($filters['caste'])) {
            $query->whereIn('sb.caste_id', $filters['caste']);
        }
        if ($except !== 'city' && !empty($filters['city'])) {
            $query->whereIn('a.city_id', $filters['city']);
        }
        if ($except !== 'state' && !empty($filters['state'])) {
            $query->whereIn('a.state_id', $filters['state']);
        }
        if ($except !== 'country' && !empty($filters['country'])) {
            $query->whereIn('a.country_id', $filters['country']);
        }
        if ($except !== 'education' && !empty($filters['education'])) {
            $query->whereIn('u.id', function ($sub) use ($filters) {
                $sub->select('user_id')->from('education')
                    ->whereNull('deleted_at')
                    ->whereIn('degree', $filters['education']);
            });
        }
        if ($except !== 'profession' && !empty($filters['profession'])) {
            $query->whereIn('u.id', function ($sub) use ($filters) {
                $sub->select('user_id')->from('careers')
                    ->whereNull('deleted_at')
                    ->whereIn('designation', $filters['profession']);
            });
        }

        // Age band is a range input, not a categorical slicer -- always applied, never excluded.
        if (!empty($filters['age_min'])) {
            $query->whereRaw('TIMESTAMPDIFF(YEAR, m.birthday, CURDATE()) >= ?', [$filters['age_min']]);
        }
        if (!empty($filters['age_max'])) {
            $query->whereRaw('TIMESTAMPDIFF(YEAR, m.birthday, CURDATE()) <= ?', [$filters['age_max']]);
        }

        return $query;
    }

    private function optionsFor(string $dimension, array $filters)
    {
        $query = $this->baseQuery($filters, $dimension);

        switch ($dimension) {
            case 'gender':
                return $query->select('m.gender as value')
                    ->whereNotNull('m.gender')->distinct()->pluck('value')
                    ->map(fn ($g) => ['value' => $g, 'label' => $g == 1 ? 'Male' : 'Female'])
                    ->values();

            case 'marital':
                return $query->select('ms.id as value', 'ms.name as label')
                    ->whereNotNull('ms.id')->distinct()->get();

            case 'sect':
                return $query->select('so.id as value', 'so.name as label')
                    ->whereNotNull('so.id')->distinct()->get();

            case 'caste':
                return $query->select('c.id as value', 'c.name as label')
                    ->whereNotNull('c.id')->distinct()->get();

            case 'city':
                return $query->select('ci.id as value', 'ci.name as label')
                    ->whereNotNull('ci.id')->distinct()->get();

            case 'state':
                return $query->select('st.id as value', 'st.name as label')
                    ->whereNotNull('st.id')->distinct()->get();

            case 'country':
                return $query->select('co.id as value', 'co.name as label')
                    ->whereNotNull('co.id')->distinct()->get();

            case 'education':
                $userIds = $query->select('u.id')->distinct()->pluck('id');
                return Education::whereIn('user_id', $userIds)
                    ->whereNull('deleted_at')
                    ->whereNotNull('degree')
                    ->distinct()
                    ->pluck('degree')
                    ->map(fn ($d) => ['value' => $d, 'label' => $d])
                    ->values();

            case 'profession':
                $userIds = $query->select('u.id')->distinct()->pluck('id');
                return Career::whereIn('user_id', $userIds)
                    ->whereNull('deleted_at')
                    ->whereNotNull('designation')
                    ->distinct()
                    ->pluck('designation')
                    ->map(fn ($d) => ['value' => $d, 'label' => $d])
                    ->values();
        }

        return [];
    }
}

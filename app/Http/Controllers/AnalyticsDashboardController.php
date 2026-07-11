<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Chat;
use App\Models\ChatThread;
use App\Models\ExpressInterest;
use App\Models\Package;
use App\Models\PackagePayment;
use App\Models\Shortlist;
use App\Models\SpiritualBackground;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:analytics_dashboard']);
    }

    public function index()
    {
        $members = User::where('user_type', 'member');

        $kpi = [
            'total_members'      => (clone $members)->count(),
            'new_this_month'     => (clone $members)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            'premium_members'    => (clone $members)->where('membership', 2)->count(),
            'free_members'       => (clone $members)->where('membership', 1)->count(),
            'pending_approval'   => (clone $members)->where('approved', 0)->count(),
            'blocked_members'    => (clone $members)->where('blocked', 1)->count(),
            'email_verified'     => (clone $members)->whereNotNull('email_verified_at')->count(),
            'deactivated'        => (clone $members)->where('deactivated', 1)->count(),
        ];

        $interests_sent = ExpressInterest::count();
        $interests_accepted = ExpressInterest::where('status', 1)->count();

        $activity = [
            'interests_sent'     => $interests_sent,
            'interests_accepted' => $interests_accepted,
            'accept_rate'        => $interests_sent > 0 ? round(($interests_accepted / $interests_sent) * 100, 1) : 0,
            'total_shortlists'   => Shortlist::count(),
            'active_chat_threads' => ChatThread::where('active', 1)->count(),
            'total_chats'        => Chat::count(),
        ];

        $revenue = [
            'all_time' => PackagePayment::where('payment_status', 'Paid')->sum('amount'),
            'this_month' => PackagePayment::where('payment_status', 'Paid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
        ];

        // Last 12 months labels + member registration counts + revenue
        $months = [];
        $registration_trend = [];
        $revenue_trend = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $registration_trend[] = (clone $members)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            $revenue_trend[] = (float) PackagePayment::where('payment_status', 'Paid')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');
        }

        $gender = [
            'male'   => (clone $members)->whereHas('member', fn ($q) => $q->where('gender', 1))->count(),
            'female' => (clone $members)->whereHas('member', fn ($q) => $q->where('gender', 2))->count(),
        ];

        $package_distribution = Package::all()->map(function ($package) {
            return [
                'name'  => $package->name,
                'count' => \App\Models\Member::where('current_package_id', $package->id)->count(),
            ];
        })->filter(fn ($p) => $p['count'] > 0)->values();

        $no_package_count = \App\Models\Member::whereNull('current_package_id')->count();

        $top_castes = SpiritualBackground::select('caste_id', DB::raw('count(*) as total'))
            ->whereNotNull('caste_id')
            ->groupBy('caste_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                return [
                    'name'  => optional(\App\Models\Caste::find($row->caste_id))->name ?? 'Unknown',
                    'count' => $row->total,
                ];
            });

        return view('admin.analytics_dashboard.index', compact(
            'kpi', 'activity', 'revenue', 'months', 'registration_trend', 'revenue_trend',
            'gender', 'package_distribution', 'no_package_count', 'top_castes'
        ));
    }
}

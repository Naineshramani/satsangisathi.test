<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Career;
use App\Models\ChatThread;
use App\Models\Education;
use App\Models\ExpressInterest;
use App\Models\PackagePayment;
use App\Models\Shortlist;
use App\Models\User;
use Illuminate\Http\Request;

class SupportLookupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:support_lookup']);
    }

    public function index(Request $request)
    {
        $mobile = trim((string) $request->get('mobile', ''));
        $member = null;
        $data = [];

        if ($mobile !== '') {
            $digits = preg_replace('/\D+/', '', $mobile);

            $member = User::where('user_type', 'member')
                ->where(function ($q) use ($mobile, $digits) {
                    $q->where('phone', 'like', '%' . $mobile . '%');
                    if ($digits !== '' && $digits !== $mobile) {
                        $q->orWhere('phone', 'like', '%' . $digits . '%');
                    }
                    if ($digits !== '') {
                        $q->orWhere('father_mobile', 'like', '%' . $digits . '%')
                            ->orWhere('mother_mobile', 'like', '%' . $digits . '%');
                    }
                })
                ->first();

            if ($member) {
                $data = $this->buildOverview($member);
            }
        }

        return view('admin.support_lookup.index', compact('mobile', 'member', 'data'));
    }

    private function buildOverview(User $member)
    {
        $present_address = Address::where('user_id', $member->id)->where('type', 'present')->first();
        $permanent_address = Address::where('user_id', $member->id)->where('type', 'permanent')->first();
        $educations = Education::where('user_id', $member->id)->orderBy('is_highest_degree', 'desc')->get();
        $careers = Career::where('user_id', $member->id)->orderBy('present', 'desc')->get();

        $interests_sent = ExpressInterest::where('interested_by', $member->id)
            ->latest()
            ->get()
            ->map(function ($i) {
                $i->target = User::withTrashed()->find($i->user_id);
                return $i;
            });

        $interests_received = ExpressInterest::where('user_id', $member->id)
            ->latest()
            ->get()
            ->map(function ($i) {
                $i->sender = User::withTrashed()->find($i->interested_by);
                return $i;
            });

        $shortlisted_by_them = Shortlist::where('shortlisted_by', $member->id)
            ->latest()
            ->get()
            ->map(function ($s) {
                $s->target = User::withTrashed()->find($s->user_id);
                return $s;
            });

        $shortlisted_them = Shortlist::where('user_id', $member->id)
            ->latest()
            ->get()
            ->map(function ($s) {
                $s->by = User::withTrashed()->find($s->shortlisted_by);
                return $s;
            });

        $chat_threads = ChatThread::where('sender_user_id', $member->id)
            ->orWhere('receiver_user_id', $member->id)
            ->latest()
            ->get()
            ->map(function ($t) use ($member) {
                $other_id = $t->sender_user_id == $member->id ? $t->receiver_user_id : $t->sender_user_id;
                $t->other = User::withTrashed()->find($other_id);
                $t->last_chat = $t->chats()->latest()->first();
                return $t;
            });

        $package_payments = PackagePayment::where('user_id', $member->id)
            ->with('package')
            ->latest()
            ->get();

        return compact(
            'present_address',
            'permanent_address',
            'educations',
            'careers',
            'interests_sent',
            'interests_received',
            'shortlisted_by_them',
            'shortlisted_them',
            'chat_threads',
            'package_payments'
        );
    }
}

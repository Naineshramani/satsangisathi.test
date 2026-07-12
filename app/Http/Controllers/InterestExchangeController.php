<?php

namespace App\Http\Controllers;

use App\Models\ExpressInterest;
use App\Models\User;
use Illuminate\Http\Request;

class InterestExchangeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:interest_exchange_report']);
    }

    public function index(Request $request)
    {
        $query = ExpressInterest::with(['user', 'sender'])->orderBy('created_at', 'desc');

        $date_range = $request->get('date_range');
        if ($date_range) {
            $parts = explode(' / ', $date_range);
            if (count($parts) == 2) {
                $query->whereDate('created_at', '>=', trim($parts[0]))
                    ->whereDate('created_at', '<=', trim($parts[1]));
            }
        }

        $sender_ids = array_filter((array) $request->get('sender_id', []));
        if ($sender_ids) {
            $query->whereIn('interested_by', $sender_ids);
        }

        $receiver_ids = array_filter((array) $request->get('receiver_id', []));
        if ($receiver_ids) {
            $query->whereIn('user_id', $receiver_ids);
        }

        $status = $request->get('status', '');
        if ($status !== '' && $status !== null) {
            $query->where('status', $status);
        }

        $interests = $query->paginate(20)->appends($request->query());

        $sender_options = User::whereIn('id', function ($q) {
            $q->select('interested_by')->from('express_interests')->distinct();
        })->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'code']);

        $receiver_options = User::whereIn('id', function ($q) {
            $q->select('user_id')->from('express_interests')->distinct();
        })->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'code']);

        return view('admin.interest_exchange.index', compact(
            'interests',
            'sender_options',
            'receiver_options',
            'sender_ids',
            'receiver_ids',
            'status',
            'date_range'
        ));
    }
}

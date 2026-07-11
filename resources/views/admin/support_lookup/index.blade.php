@extends('admin.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Support Lookup') }}</h1>
                <p class="mb-0 opacity-60">{{ translate('Search by the member\'s registered mobile number to pull up everything needed to assist them on a call.') }}</p>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('support_lookup.index') }}" method="GET">
                <div class="form-group row mb-0">
                    <div class="col-md-6">
                        <input type="text" name="mobile" value="{{ $mobile }}" class="form-control"
                            placeholder="{{ translate('Enter registered mobile number') }}" autofocus>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="las la-search mr-1"></i>{{ translate('Search') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if ($mobile !== '' && !$member)
        <div class="alert alert-warning">
            {{ translate('No member found with mobile number') }} "{{ $mobile }}".
        </div>
    @endif

    @if ($member)
        @php
            $m = $member->member;
            $age = !empty($m->birthday) ? \Carbon\Carbon::parse($m->birthday)->age : null;
        @endphp

        {{-- Ready reckoner summary --}}
        <div class="card mb-4">
            <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
                <h5 class="mb-0 h6">{{ translate('Member Summary') }}</h5>
                <a href="{{ route('members.show', encrypt($member->id)) }}" target="_blank" class="btn btn-sm btn-light">
                    {{ translate('Open Full Profile') }}
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-center mb-3">
                        <span class="avatar avatar-lg">
                            @if (!uploaded_asset($member->photo))
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}">
                            @else
                                <img src="{{ uploaded_asset($member->photo) }}">
                            @endif
                        </span>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-4 mb-2"><strong>{{ translate('Name') }}:</strong> {{ $member->first_name }} {{ $member->last_name }}</div>
                            <div class="col-md-4 mb-2"><strong>{{ translate('Member ID') }}:</strong> {{ $member->code }}</div>
                            <div class="col-md-4 mb-2"><strong>{{ translate('Age') }}:</strong> {{ $age ?? '-' }}</div>

                            <div class="col-md-4 mb-2"><strong>{{ translate('Mobile') }}:</strong> {{ $member->phone ?? '-' }}</div>
                            <div class="col-md-4 mb-2"><strong>{{ translate('Father\'s Mobile') }}:</strong> {{ $member->father_mobile ?? '-' }}</div>
                            <div class="col-md-4 mb-2"><strong>{{ translate('Mother\'s Mobile') }}:</strong> {{ $member->mother_mobile ?? '-' }}</div>

                            <div class="col-md-4 mb-2"><strong>{{ translate('Email') }}:</strong> {{ $member->email ?? '-' }}</div>
                            <div class="col-md-4 mb-2">
                                <strong>{{ translate('Email Verified') }}:</strong>
                                @if ($member->email_verified_at)
                                    <span class="badge badge-inline badge-success">{{ translate('Yes') }}</span>
                                @else
                                    <span class="badge badge-inline badge-danger">{{ translate('No') }}</span>
                                @endif
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>{{ translate('Approved') }}:</strong>
                                @if ($member->approved == 1)
                                    <span class="badge badge-inline badge-success">{{ translate('Yes') }}</span>
                                @else
                                    <span class="badge badge-inline badge-danger">{{ translate('No') }}</span>
                                @endif
                            </div>

                            <div class="col-md-4 mb-2">
                                <strong>{{ translate('Blocked') }}:</strong>
                                @if ($member->blocked == 1)
                                    <span class="badge badge-inline badge-danger">{{ translate('Yes') }}</span>
                                @else
                                    <span class="badge badge-inline badge-success">{{ translate('No') }}</span>
                                @endif
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>{{ translate('Account Deactivated') }}:</strong>
                                @if ($member->deactivated == 1)
                                    <span class="badge badge-inline badge-danger">{{ translate('Yes') }}</span>
                                @else
                                    <span class="badge badge-inline badge-success">{{ translate('No') }}</span>
                                @endif
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>{{ translate('Membership') }}:</strong>
                                {{ $member->membership == 2 ? translate('Premium') : translate('Free') }}
                            </div>

                            <div class="col-md-4 mb-2"><strong>{{ translate('Package') }}:</strong> {{ optional(\App\Models\Package::find($m->current_package_id ?? null))->name ?? '-' }}</div>
                            <div class="col-md-4 mb-2"><strong>{{ translate('Package Validity') }}:</strong> {{ $m->package_validity ?? '-' }}</div>
                            <div class="col-md-4 mb-2"><strong>{{ translate('Gender') }}:</strong> {{ $m->gender == 1 ? translate('Male') : ($m->gender == 2 ? translate('Female') : '-') }}</div>

                            <div class="col-md-4 mb-2"><strong>{{ translate('Marital Status') }}:</strong> {{ optional($m->marital_status)->name ?? '-' }}</div>
                            <div class="col-md-4 mb-2"><strong>{{ translate('Sect') }}:</strong> {{ optional(optional($member->satsang_details)->followerOfSect)->name ?? '-' }}</div>
                            <div class="col-md-4 mb-2"><strong>{{ translate('Caste') }}:</strong> {{ optional($member->spiritual_backgrounds)->caste->name ?? '-' }}</div>

                            <div class="col-md-4 mb-2">
                                <strong>{{ translate('Present City-Country') }}:</strong>
                                {{ implode(' - ', array_filter([optional(optional($data['present_address'] ?? null)->city)->name, optional(optional($data['present_address'] ?? null)->country)->name])) ?: '-' }}
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>{{ translate('Education') }}:</strong>
                                {{ optional(($data['educations'] ?? collect())->first())->degree ?? '-' }}
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>{{ translate('Profession') }}:</strong>
                                {{ optional(($data['careers'] ?? collect())->first())->designation ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Interests Sent --}}
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0 h6">{{ translate('Interest Sent') }} ({{ $data['interests_sent']->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ translate('To') }}</th>
                                <th>{{ translate('Member ID') }}</th>
                                <th>{{ translate('Mobile') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['interests_sent'] as $i)
                                <tr>
                                    <td>{{ optional($i->target)->first_name }} {{ optional($i->target)->last_name }}</td>
                                    <td>{{ optional($i->target)->code }}</td>
                                    <td>{{ optional($i->target)->phone }}</td>
                                    <td>
                                        @if ($i->status == 1)
                                            <span class="badge badge-inline badge-success">{{ translate('Accepted') }}</span>
                                        @else
                                            <span class="badge badge-inline badge-info">{{ translate('Pending') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $i->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">{{ translate('No interests sent') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Interests Received --}}
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0 h6">{{ translate('Interest Received') }} ({{ $data['interests_received']->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ translate('From') }}</th>
                                <th>{{ translate('Member ID') }}</th>
                                <th>{{ translate('Mobile') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['interests_received'] as $i)
                                <tr>
                                    <td>{{ optional($i->sender)->first_name }} {{ optional($i->sender)->last_name }}</td>
                                    <td>{{ optional($i->sender)->code }}</td>
                                    <td>{{ optional($i->sender)->phone }}</td>
                                    <td>
                                        @if ($i->status == 1)
                                            <span class="badge badge-inline badge-success">{{ translate('Accepted') }}</span>
                                        @else
                                            <span class="badge badge-inline badge-info">{{ translate('Pending') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $i->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">{{ translate('No interests received') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Shortlists --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0 h6">{{ translate('Shortlisted by Them') }} ({{ $data['shortlisted_by_them']->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead><tr><th>{{ translate('Name') }}</th><th>{{ translate('Mobile') }}</th></tr></thead>
                                <tbody>
                                    @forelse ($data['shortlisted_by_them'] as $s)
                                        <tr>
                                            <td>{{ optional($s->target)->first_name }} {{ optional($s->target)->last_name }}</td>
                                            <td>{{ optional($s->target)->phone }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="text-center">{{ translate('None') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0 h6">{{ translate('Shortlisted Them') }} ({{ $data['shortlisted_them']->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead><tr><th>{{ translate('Name') }}</th><th>{{ translate('Mobile') }}</th></tr></thead>
                                <tbody>
                                    @forelse ($data['shortlisted_them'] as $s)
                                        <tr>
                                            <td>{{ optional($s->by)->first_name }} {{ optional($s->by)->last_name }}</td>
                                            <td>{{ optional($s->by)->phone }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="text-center">{{ translate('None') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chats --}}
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0 h6">{{ translate('Chats') }} ({{ $data['chat_threads']->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ translate('With') }}</th>
                                <th>{{ translate('Mobile') }}</th>
                                <th>{{ translate('Last Message') }}</th>
                                <th>{{ translate('Last Activity') }}</th>
                                <th>{{ translate('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['chat_threads'] as $t)
                                <tr>
                                    <td>{{ optional($t->other)->first_name }} {{ optional($t->other)->last_name }}</td>
                                    <td>{{ optional($t->other)->phone }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit(optional($t->last_chat)->message, 60) ?: ($t->last_chat && $t->last_chat->attachment ? translate('Attachment') : '-') }}</td>
                                    <td>{{ optional($t->last_chat)->created_at?->format('d M Y, h:i A') ?? '-' }}</td>
                                    <td>
                                        @if ($t->active == 1)
                                            <span class="badge badge-inline badge-success">{{ translate('Active') }}</span>
                                        @else
                                            <span class="badge badge-inline badge-danger">{{ translate('Blocked') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">{{ translate('No chats') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Package Payment History --}}
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0 h6">{{ translate('Package Payment History') }} ({{ $data['package_payments']->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ translate('Package') }}</th>
                                <th>{{ translate('Amount') }}</th>
                                <th>{{ translate('Method') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data['package_payments'] as $p)
                                <tr>
                                    <td>{{ optional($p->package)->name ?? '-' }}</td>
                                    <td>{{ single_price($p->amount) }}</td>
                                    <td>{{ $p->payment_method }}</td>
                                    <td>{{ ucfirst($p->payment_status) }}</td>
                                    <td>{{ $p->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">{{ translate('No package purchases') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection

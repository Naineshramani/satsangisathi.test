@extends('admin.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Interest Exchange') }}</h1>
                <p class="mb-0 opacity-60">{{ translate('All interest requests across the platform, latest first, with WhatsApp biodata sharing.') }}</p>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <form action="{{ route('interest_exchange.index') }}" method="GET">
            <div class="card-header row gutters-5">
                <div class="col-md-3">
                    <select class="form-control form-control-sm aiz-selectpicker" name="sender_id[]" multiple data-live-search="true" title="{{ translate('Sender') }}">
                        @foreach ($sender_options as $u)
                            <option value="{{ $u->id }}" @if (in_array($u->id, $sender_ids)) selected @endif>
                                {{ $u->first_name }} {{ $u->last_name }} ({{ $u->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control form-control-sm aiz-selectpicker" name="receiver_id[]" multiple data-live-search="true" title="{{ translate('Receiver') }}">
                        @foreach ($receiver_options as $u)
                            <option value="{{ $u->id }}" @if (in_array($u->id, $receiver_ids)) selected @endif>
                                {{ $u->first_name }} {{ $u->last_name }} ({{ $u->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control form-control-sm aiz-selectpicker" name="status" title="{{ translate('Status') }}">
                        <option value="">{{ translate('All Statuses') }}</option>
                        <option value="0" @if ($status === '0') selected @endif>{{ translate('Pending') }}</option>
                        <option value="1" @if ($status === '1') selected @endif>{{ translate('Accepted') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm aiz-date-range" name="date_range" value="{{ $date_range }}" placeholder="{{ translate('Daterange') }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-sm btn-primary" type="submit">
                        <i class="las la-filter mr-1"></i>{{ translate('Filter') }}
                    </button>
                    <a href="{{ route('interest_exchange.index') }}" class="btn btn-sm btn-light">
                        {{ translate('Reset') }}
                    </a>
                </div>
            </div>
        </form>
        <div class="card-body">
            <div class="mb-3 opacity-60">
                {{ translate('Showing') }} {{ $interests->count() }} {{ translate('of') }} {{ $interests->total() }} {{ translate('interest requests') }}
            </div>
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th data-breakpoints="lg">{{ translate('Date') }}</th>
                            <th>{{ translate('Sender') }}</th>
                            <th>{{ translate('Receiver') }}</th>
                            <th>{{ translate('Status') }}</th>
                            <th class="text-right">{{ translate('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($interests as $interest)
                            @php
                                $sender_full_name = trim(optional($interest->sender)->first_name . ' ' . optional($interest->sender)->last_name);
                                $receiver_full_name = trim(optional($interest->user)->first_name . ' ' . optional($interest->user)->last_name);
                                $receiver_phone_digits = preg_replace('/\D+/', '', optional($interest->user)->phone ?? '');

                                $wa_link = null;
                                if ($interest->sender && $receiver_phone_digits) {
                                    $short_link = \App\Models\ShortLink::findOrCreateFor('biodata', $interest->interested_by, 30);
                                    $biodata_link = route('member.short_biodata_pdf', $short_link->token);
                                    $wa_message = "Jai Swaminarayan " . $receiver_full_name . ", you got interest request from " . $sender_full_name . ". Please find biodata at " . $biodata_link . ". Login to your account on www.satsangisathi.in to Accept or reject";
                                    $wa_link = 'https://wa.me/' . $receiver_phone_digits . '?text=' . urlencode($wa_message);
                                }
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration + ($interests->currentPage() - 1) * $interests->perPage() }}</td>
                                <td>{{ $interest->created_at->format('d M Y, h:i A') }}</td>
                                <td>
                                    {{ $sender_full_name ?: '-' }}
                                    @if ($interest->sender)
                                        <div class="opacity-60">{{ $interest->sender->code }}</div>
                                    @endif
                                </td>
                                <td>
                                    {{ $receiver_full_name ?: '-' }}
                                    @if ($interest->user)
                                        <div class="opacity-60">{{ $interest->user->code }} @if ($interest->user->phone) &bull; {{ $interest->user->phone }} @endif</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($interest->status == 1)
                                        <span class="badge badge-inline badge-success">{{ translate('Accepted') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-info">{{ translate('Pending') }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if ($wa_link)
                                        <a href="{{ $wa_link }}" target="_blank" class="btn btn-sm btn-success" title="{{ translate('Send sender\'s biodata to receiver via WhatsApp') }}">
                                            <i class="lab la-whatsapp mr-1"></i>{{ translate('Send Biodata') }}
                                        </a>
                                    @else
                                        <span class="opacity-60">{{ translate('No phone on file') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ translate('No interest requests found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="aiz-pagination mt-4">
                {{ $interests->links() }}
            </div>
        </div>
    </div>
@endsection

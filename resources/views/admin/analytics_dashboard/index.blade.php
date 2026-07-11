@extends('admin.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Analytics Dashboard') }}</h1>
                <p class="mb-0 opacity-60">{{ translate('Live KPIs and activity summary across the platform.') }}</p>
            </div>
        </div>
    </div>

    {{-- Headline KPIs --}}
    <div class="row gutters-10">
        <div class="col-xl-3 col-md-6">
            <div class="bg-grad-2 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-90"><span class="fs-12 d-block">{{ translate('Total') }}</span>{{ translate('Members') }}</div>
                    <div class="h3 fw-700 mb-0">{{ $kpi['total_members'] }}</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#c95792" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path></svg>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-90"><span class="fs-12 d-block">{{ translate('New') }}</span>{{ translate('This Month') }}</div>
                    <div class="h3 fw-700 mb-0">{{ $kpi['new_this_month'] }}</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#a084ca" fill-opacity="1" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path></svg>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="bg-grad-1 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-90"><span class="fs-12 d-block">{{ translate('Premium') }}</span>{{ translate('Members') }}</div>
                    <div class="h3 fw-700 mb-0">{{ $kpi['premium_members'] }}</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#a3d1c6" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path></svg>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="bg-grad-4 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="opacity-90"><span class="fs-12 d-block">{{ translate('Pending') }}</span>{{ translate('Approval') }}</div>
                    <div class="h3 fw-700 mb-0">{{ $kpi['pending_approval'] }}</div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#ffa955" d="M0,128L34.3,112C68.6,96,137,64,206,96C274.3,128,343,224,411,250.7C480,277,549,235,617,213.3C685.7,192,754,192,823,181.3C891.4,171,960,149,1029,117.3C1097.1,85,1166,43,1234,58.7C1302.9,75,1371,149,1406,186.7L1440,224L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"></path></svg>
            </div>
        </div>
    </div>

    {{-- Secondary status KPIs --}}
    <div class="row gutters-10">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card bg-white h-100 px-3 py-3 rounded shadow-sm mb-4">
                <span class="opacity-50 fs-12 mb-1">{{ translate('Free Members') }}</span>
                <div class="h4 mb-0">{{ $kpi['free_members'] }}</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card bg-white h-100 px-3 py-3 rounded shadow-sm mb-4">
                <span class="opacity-50 fs-12 mb-1">{{ translate('Blocked') }}</span>
                <div class="h4 mb-0">{{ $kpi['blocked_members'] }}</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card bg-white h-100 px-3 py-3 rounded shadow-sm mb-4">
                <span class="opacity-50 fs-12 mb-1">{{ translate('Email Verified') }}</span>
                <div class="h4 mb-0">{{ $kpi['email_verified'] }}</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card bg-white h-100 px-3 py-3 rounded shadow-sm mb-4">
                <span class="opacity-50 fs-12 mb-1">{{ translate('Deactivated') }}</span>
                <div class="h4 mb-0">{{ $kpi['deactivated'] }}</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card bg-white h-100 px-3 py-3 rounded shadow-sm mb-4">
                <span class="opacity-50 fs-12 mb-1">{{ translate('Active Chats') }}</span>
                <div class="h4 mb-0">{{ $activity['active_chat_threads'] }}</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card bg-white h-100 px-3 py-3 rounded shadow-sm mb-4">
                <span class="opacity-50 fs-12 mb-1">{{ translate('Shortlists') }}</span>
                <div class="h4 mb-0">{{ $activity['total_shortlists'] }}</div>
            </div>
        </div>
    </div>

    {{-- Interest & Revenue KPIs --}}
    <div class="row gutters-10">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card bg-white h-100 px-3 py-3 rounded shadow-sm mb-4">
                <span class="opacity-50 fs-12 mb-1">{{ translate('Interests Sent') }}</span>
                <div class="h4 mb-0">{{ $activity['interests_sent'] }}</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card bg-white h-100 px-3 py-3 rounded shadow-sm mb-4">
                <span class="opacity-50 fs-12 mb-1">{{ translate('Interests Accepted') }}</span>
                <div class="h4 mb-0">{{ $activity['interests_accepted'] }} <span class="fs-12 opacity-60">({{ $activity['accept_rate'] }}%)</span></div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4">
            <div class="card bg-white h-100 px-3 py-3 rounded shadow-sm mb-4">
                <span class="opacity-50 fs-12 mb-1">{{ translate('Total Revenue (All Time)') }}</span>
                <div class="h4 mb-0">{{ single_price($revenue['all_time']) }}</div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4">
            <div class="card bg-white h-100 px-3 py-3 rounded shadow-sm mb-4">
                <span class="opacity-50 fs-12 mb-1">{{ translate('This Month Revenue') }}</span>
                <div class="h4 mb-0">{{ single_price($revenue['this_month']) }}</div>
            </div>
        </div>
    </div>

    {{-- Trend charts --}}
    <div class="row gutters-10">
        <div class="col-xl-6">
            <div class="card shadow-sm">
                <div class="card-header"><h6 class="mb-0 fs-14">{{ translate('Member Registrations (Last 12 Months)') }}</h6></div>
                <div class="card-body">
                    <canvas id="chart-registrations" class="w-100" height="280"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card shadow-sm">
                <div class="card-header"><h6 class="mb-0 fs-14">{{ translate('Revenue (Last 12 Months)') }}</h6></div>
                <div class="card-body">
                    <canvas id="chart-revenue" class="w-100" height="280"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Breakdown charts --}}
    <div class="row gutters-10">
        <div class="col-xl-4 col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h6 class="mb-0 fs-14">{{ translate('Gender Split') }}</h6></div>
                <div class="card-body">
                    <canvas id="chart-gender" class="w-100" height="260"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h6 class="mb-0 fs-14">{{ translate('Package Distribution') }}</h6></div>
                <div class="card-body">
                    <canvas id="chart-package" class="w-100" height="260"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h6 class="mb-0 fs-14">{{ translate('Top 5 Castes') }}</h6></div>
                <div class="card-body">
                    <canvas id="chart-castes" class="w-100" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
    AIZ.plugins.chart('#chart-registrations', {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: '{{ translate("Registrations") }}',
                data: {!! json_encode($registration_trend) !!},
                backgroundColor: 'rgba(55, 125, 255, 0)',
                borderColor: '#377dff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            legend: { display: false },
            elements: { point: { radius: 2 } }
        }
    });

    AIZ.plugins.chart('#chart-revenue', {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: '{{ translate("Revenue") }}',
                data: {!! json_encode($revenue_trend) !!},
                backgroundColor: 'rgba(52, 191, 163, 0)',
                borderColor: '#34bfa3',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            legend: { display: false },
            elements: { point: { radius: 2 } }
        }
    });

    AIZ.plugins.chart('#chart-gender', {
        type: 'doughnut',
        data: {
            labels: ['{{ translate("Male") }}', '{{ translate("Female") }}'],
            datasets: [{
                data: [{{ $gender['male'] }}, {{ $gender['female'] }}],
                backgroundColor: ['#5d78ff', '#fd3995']
            }]
        },
        options: {
            cutoutPercentage: 65,
            legend: { position: 'bottom' }
        }
    });

    AIZ.plugins.chart('#chart-package', {
        type: 'doughnut',
        data: {
            labels: [
                @foreach ($package_distribution as $p)
                    '{{ $p['name'] }}',
                @endforeach
                @if($no_package_count > 0) '{{ translate("No Package") }}' @endif
            ],
            datasets: [{
                data: [
                    @foreach ($package_distribution as $p)
                        {{ $p['count'] }},
                    @endforeach
                    @if($no_package_count > 0) {{ $no_package_count }} @endif
                ],
                backgroundColor: ['#fd3995', '#34bfa3', '#5d78ff', '#ffa955', '#a084ca', '#c95792']
            }]
        },
        options: {
            cutoutPercentage: 65,
            legend: { position: 'bottom' }
        }
    });

    AIZ.plugins.chart('#chart-castes', {
        type: 'bar',
        data: {
            labels: [
                @foreach ($top_castes as $c)
                    '{{ $c['name'] }}',
                @endforeach
            ],
            datasets: [{
                label: '{{ translate("Members") }}',
                data: [
                    @foreach ($top_castes as $c)
                        {{ $c['count'] }},
                    @endforeach
                ],
                backgroundColor: '#377dff'
            }]
        },
        options: {
            responsive: true,
            legend: { display: false }
        }
    });
</script>
@endsection

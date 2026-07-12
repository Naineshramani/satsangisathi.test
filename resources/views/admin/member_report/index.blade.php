@extends('admin.layouts.app')

@section('content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3">{{ translate('Member Report') }}</h1>
                <p class="mb-0 opacity-60">{{ translate('Use the slicers below to filter -- selecting a value in one slicer narrows down the options in the others.') }}</p>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row gutters-10">
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                    <label class="form-label fs-12 opacity-60">{{ translate('Gender') }}</label>
                    <select id="filter_gender" multiple class="form-control aiz-selectpicker" data-live-search="false">
                        @foreach ($options['gender'] as $opt)
                            <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                    <label class="form-label fs-12 opacity-60">{{ translate('Age Band') }}</label>
                    <div class="d-flex align-items-center">
                        <input type="number" id="filter_age_min" min="0" max="120" class="form-control" placeholder="{{ translate('Min') }}">
                        <span class="mx-2 opacity-60">-</span>
                        <input type="number" id="filter_age_max" min="0" max="120" class="form-control" placeholder="{{ translate('Max') }}">
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                    <label class="form-label fs-12 opacity-60">{{ translate('Marital Status') }}</label>
                    <select id="filter_marital" multiple class="form-control aiz-selectpicker" data-live-search="true">
                        @foreach ($options['marital'] as $opt)
                            <option value="{{ $opt->value }}">{{ $opt->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                    <label class="form-label fs-12 opacity-60">{{ translate('Follower of (Sect)') }}</label>
                    <select id="filter_sect" multiple class="form-control aiz-selectpicker" data-live-search="true">
                        @foreach ($options['sect'] as $opt)
                            <option value="{{ $opt->value }}">{{ $opt->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                    <label class="form-label fs-12 opacity-60">{{ translate('Caste') }}</label>
                    <select id="filter_caste" multiple class="form-control aiz-selectpicker" data-live-search="true">
                        @foreach ($options['caste'] as $opt)
                            <option value="{{ $opt->value }}">{{ $opt->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                    <label class="form-label fs-12 opacity-60">{{ translate('Present City') }}</label>
                    <select id="filter_city" multiple class="form-control aiz-selectpicker" data-live-search="true">
                        @foreach ($options['city'] as $opt)
                            <option value="{{ $opt->value }}">{{ $opt->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                    <label class="form-label fs-12 opacity-60">{{ translate('Present State') }}</label>
                    <select id="filter_state" multiple class="form-control aiz-selectpicker" data-live-search="true">
                        @foreach ($options['state'] as $opt)
                            <option value="{{ $opt->value }}">{{ $opt->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                    <label class="form-label fs-12 opacity-60">{{ translate('Present Country') }}</label>
                    <select id="filter_country" multiple class="form-control aiz-selectpicker" data-live-search="true">
                        @foreach ($options['country'] as $opt)
                            <option value="{{ $opt->value }}">{{ $opt->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                    <label class="form-label fs-12 opacity-60">{{ translate('Education') }}</label>
                    <select id="filter_education" multiple class="form-control aiz-selectpicker" data-live-search="true">
                        @foreach ($options['education'] as $opt)
                            <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                    <label class="form-label fs-12 opacity-60">{{ translate('Profession') }}</label>
                    <select id="filter_profession" multiple class="form-control aiz-selectpicker" data-live-search="true">
                        @foreach ($options['profession'] as $opt)
                            <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-4 col-6 mb-3 d-flex align-items-end">
                    <button type="button" id="reset_filters" class="btn btn-light btn-block">
                        <i class="las la-redo mr-1"></i>{{ translate('Reset Filters') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0 h6">{{ translate('Members') }} (<span id="result_count">{{ $count }}</span>)</h5>
            <span id="report_loading" class="d-none"><i class="las la-spinner la-spin"></i> {{ translate('Loading...') }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('Member ID') }}</th>
                            <th>{{ translate('Name') }}</th>
                            <th>{{ translate('Gender') }}</th>
                            <th>{{ translate('Age') }}</th>
                            <th>{{ translate('Marital Status') }}</th>
                            <th>{{ translate('Follower of (Sect)') }}</th>
                            <th>{{ translate('Caste') }}</th>
                            <th>{{ translate('Present City') }}</th>
                            <th>{{ translate('Present State') }}</th>
                            <th>{{ translate('Present Country') }}</th>
                            <th>{{ translate('Education') }}</th>
                            <th>{{ translate('Profession') }}</th>
                            <th>{{ translate('Mobile No') }}</th>
                            <th>{{ translate('Last Login') }}</th>
                        </tr>
                    </thead>
                    <tbody id="report_table_body">
                        @include('admin.member_report._rows', ['rows' => $rows])
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
    var slicerIds = ['gender', 'marital', 'sect', 'caste', 'city', 'state', 'country', 'education', 'profession'];

    function collectFilters() {
        var payload = {};
        slicerIds.forEach(function (key) {
            payload[key] = $('#filter_' + key).val() || [];
        });
        payload.age_min = $('#filter_age_min').val();
        payload.age_max = $('#filter_age_max').val();
        return payload;
    }

    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return $('<div>').text(str).html();
    }

    function renderRows(rows) {
        if (!rows.length) {
            return '<tr><td colspan="14" class="text-center">{{ translate("No members match the selected filters") }}</td></tr>';
        }
        var html = '';
        rows.forEach(function (r) {
            html += '<tr>' +
                '<td>' + escapeHtml(r.code) + '</td>' +
                '<td>' + escapeHtml(r.name) + '</td>' +
                '<td>' + escapeHtml(r.gender_label) + '</td>' +
                '<td>' + escapeHtml(r.age) + '</td>' +
                '<td>' + escapeHtml(r.marital_status) + '</td>' +
                '<td>' + escapeHtml(r.sect) + '</td>' +
                '<td>' + escapeHtml(r.caste) + '</td>' +
                '<td>' + escapeHtml(r.city) + '</td>' +
                '<td>' + escapeHtml(r.state) + '</td>' +
                '<td>' + escapeHtml(r.country) + '</td>' +
                '<td>' + escapeHtml(r.education) + '</td>' +
                '<td>' + escapeHtml(r.profession) + '</td>' +
                '<td>' + escapeHtml(r.phone) + '</td>' +
                '<td>' + escapeHtml(r.last_login) + '</td>' +
                '</tr>';
        });
        return html;
    }

    function rebuildSlicer(key, opts, currentSelection) {
        var $select = $('#filter_' + key);
        var currentSet = {};
        (currentSelection || []).forEach(function (v) { currentSet[v] = true; });

        $select.empty();
        opts.forEach(function (opt) {
            var selected = currentSet[opt.value] ? ' selected' : '';
            $select.append('<option value="' + escapeHtml(opt.value) + '"' + selected + '>' + escapeHtml(opt.label) + '</option>');
        });
        AIZ.plugins.bootstrapSelect('refresh');
    }

    function refreshReport() {
        var filters = collectFilters();
        $('#report_loading').removeClass('d-none');

        $.ajax({
            url: '{{ route("member_report.data") }}',
            method: 'POST',
            data: $.extend({ _token: '{{ csrf_token() }}' }, filters),
            success: function (res) {
                $('#report_table_body').html(renderRows(res.rows));
                $('#result_count').text(res.count);

                slicerIds.forEach(function (key) {
                    rebuildSlicer(key, res.options[key], filters[key]);
                });
            },
            complete: function () {
                $('#report_loading').addClass('d-none');
            }
        });
    }

    $(document).ready(function () {
        slicerIds.forEach(function (key) {
            $('#filter_' + key).on('changed.bs.select', refreshReport);
        });

        var ageDebounce;
        $('#filter_age_min, #filter_age_max').on('input', function () {
            clearTimeout(ageDebounce);
            ageDebounce = setTimeout(refreshReport, 400);
        });

        $('#reset_filters').on('click', function () {
            slicerIds.forEach(function (key) {
                $('#filter_' + key).selectpicker('deselectAll');
            });
            $('#filter_age_min, #filter_age_max').val('');
            refreshReport();
        });
    });
</script>
@endsection

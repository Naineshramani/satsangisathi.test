<div class="card" id="sec-satsang">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Satsang Details') }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('satsang_details.update', $member->id) }}" method="POST">
            <input name="_method" type="hidden" value="PATCH">
            @csrf

            <div class="form-group row">
                <div class="col-md-6">
                    <label>{{ translate('Follower of (Sect)') }}</label>
                    <select class="form-control aiz-selectpicker" name="follower_of_sect_id" data-live-search="true">
                        <option value="">{{ translate('Select One') }}</option>
                        @foreach ($satsang_sects as $opt)
                            <option value="{{ $opt->id }}" {{ ($member->satsang_details->follower_of_sect_id ?? '') == $opt->id ? 'selected' : '' }}>
                                {{ $opt->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>{{ translate('Name of the Mandal') }}</label>
                    <input type="text" name="name_of_mandal"
                           value="{{ $member->satsang_details->name_of_mandal ?? '' }}"
                           class="form-control" placeholder="{{ translate('Name of the Mandal') }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label>{{ translate('Do you perform Nitya Pooja Daily?') }}</label>
                    <select class="form-control aiz-selectpicker" name="nitya_pooja_daily_id">
                        <option value="">{{ translate('Select One') }}</option>
                        @foreach ($satsang_nitya_pooja as $opt)
                            <option value="{{ $opt->id }}" {{ ($member->satsang_details->nitya_pooja_daily_id ?? '') == $opt->id ? 'selected' : '' }}>
                                {{ $opt->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>{{ translate('Do you wear Kanthi?') }}</label>
                    <select class="form-control aiz-selectpicker" name="wear_kanthi_id">
                        <option value="">{{ translate('Select One') }}</option>
                        @foreach ($satsang_kanthi as $opt)
                            <option value="{{ $opt->id }}" {{ ($member->satsang_details->wear_kanthi_id ?? '') == $opt->id ? 'selected' : '' }}>
                                {{ $opt->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            @php $gender = $member->member->gender ?? null; @endphp
            <div class="form-group row" id="tilak_chandlo_row" @if($gender != 1) style="display:none;" @endif>
                <div class="col-md-6">
                    <label>{{ translate('Do you wear Tilak Chandlo?') }}</label>
                    <select class="form-control aiz-selectpicker" name="wear_tilak_chandlo_id" id="wear_tilak_chandlo_select">
                        <option value="">{{ translate('Select One') }}</option>
                        @foreach ($satsang_tilak_chandlo as $opt)
                            <option value="{{ $opt->id }}" {{ ($member->satsang_details->wear_tilak_chandlo_id ?? '') == $opt->id ? 'selected' : '' }}>
                                {{ $opt->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label>{{ translate('Do you Eat Onion / Garlic?') }}</label>
                    <select class="form-control aiz-selectpicker" name="eat_onion_garlic_id">
                        <option value="">{{ translate('Select One') }}</option>
                        @foreach ($satsang_onion_garlic as $opt)
                            <option value="{{ $opt->id }}" {{ ($member->satsang_details->eat_onion_garlic_id ?? '') == $opt->id ? 'selected' : '' }}>
                                {{ $opt->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>{{ translate('Do you Perform Aarti, Evening Ghar Sabha etc?') }}</label>
                    <select class="form-control aiz-selectpicker" name="perform_aarti_id">
                        <option value="">{{ translate('Select One') }}</option>
                        @foreach ($satsang_aarti as $opt)
                            <option value="{{ $opt->id }}" {{ ($member->satsang_details->perform_aarti_id ?? '') == $opt->id ? 'selected' : '' }}>
                                {{ $opt->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label>{{ translate('Do you observe all fasts prescribed in Sampradaya?') }}</label>
                    <select class="form-control aiz-selectpicker" name="observe_fasts_id">
                        <option value="">{{ translate('Select One') }}</option>
                        @foreach ($satsang_fasts as $opt)
                            <option value="{{ $opt->id }}" {{ ($member->satsang_details->observe_fasts_id ?? '') == $opt->id ? 'selected' : '' }}>
                                {{ $opt->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>{{ translate('Frequency of Temple Visits') }}</label>
                    <select class="form-control aiz-selectpicker" name="temple_visit_frequency_id">
                        <option value="">{{ translate('Select One') }}</option>
                        @foreach ($satsang_temple_frequency as $opt)
                            <option value="{{ $opt->id }}" {{ ($member->satsang_details->temple_visit_frequency_id ?? '') == $opt->id ? 'selected' : '' }}>
                                {{ $opt->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label>{{ translate('Any Volunteer Activities') }}</label>
                    <input type="text" name="volunteer_activities"
                           value="{{ $member->satsang_details->volunteer_activities ?? '' }}"
                           class="form-control" placeholder="{{ translate('e.g. Bal Sabha, Youth Wing') }}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12">
                    <label>{{ translate('Define Yourself as Satsangi') }}</label>
                    <textarea name="define_yourself_satsangi" class="form-control" rows="4"
                              placeholder="{{ translate('Describe your satsang lifestyle and values...') }}">{{ $member->satsang_details->define_yourself_satsangi ?? '' }}</textarea>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary btn-sm">{{ translate('Update') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function syncTilakRow(genderVal) {
        var row = document.getElementById('tilak_chandlo_row');
        if (!row) return;
        if (String(genderVal) === '1') {
            row.style.display = '';
        } else {
            row.style.display = 'none';
            var sel = document.getElementById('wear_tilak_chandlo_select');
            if (sel) { sel.value = ''; AIZ.plugins.bootstrapSelect('refresh'); }
        }
    }

    var genderInput = document.querySelector('input[name="gender"]');
    if (genderInput) syncTilakRow(genderInput.value);

    var genderSelect = document.querySelector('select[name="gender"]');
    if (genderSelect) {
        genderSelect.addEventListener('change', function () { syncTilakRow(this.value); });
        $(genderSelect).on('changed.bs.select', function () { syncTilakRow(this.value); });
    }
});
</script>

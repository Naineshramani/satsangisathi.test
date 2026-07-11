<div class="card" id="sec-address">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Address Information')}}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('address.update', $member->id) }}" method="POST">
            <input name="_method" type="hidden" value="PATCH">
            @csrf
            <input type="hidden" name="address_type" value="both">

            {{-- Present Address --}}
            <span class="addr-section-header">{{translate('Present Address')}}</span>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="present_country_id">{{translate('Country')}}</label>
                    <select class="form-control aiz-selectpicker" name="present_country_id" id="present_country_id" data-selected="{{ $present_country_id }}" data-live-search="true" required>
                        <option value="">{{translate('Select One')}}</option>
                        @php
                            $india = \App\Models\Country::where('status',1)->where('id',101)->first();
                            $other_countries = \App\Models\Country::where('status',1)->where('id','!=',101)->orderBy('name')->get();
                        @endphp
                        @if($india)
                            <option value="{{$india->id}}">{{$india->name}}</option>
                        @endif
                        @foreach ($other_countries as $country)
                            <option value="{{$country->id}}">{{$country->name}}</option>
                        @endforeach
                    </select>
                    @error('present_country_id')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="present_state_id">{{translate('State')}}</label>
                    <select class="form-control aiz-selectpicker" name="present_state_id" id="present_state_id" data-live-search="true" required>
                    </select>
                    @error('present_state_id')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="present_city_id">{{translate('City')}}</label>
                    <select class="form-control aiz-selectpicker" name="present_city_id" id="present_city_id" data-live-search="true" required>
                    </select>
                </div>
            </div>
            <input type="hidden" name="present_postal_code" value="{{ $present_postal_code ?? '' }}">

            {{-- Permanent Address --}}
            <span class="addr-section-header mt-3">{{translate('Permanent Address')}}</span>
            <div class="mb-3">
                <label class="aiz-checkbox">
                    <input type="checkbox" id="same_as_present">
                    <span class="opacity-60">{{translate('Permanent address is same as present address')}}</span>
                    <span class="aiz-square-check"></span>
                </label>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="permanent_country_id">{{translate('Country')}}</label>
                    <select class="form-control aiz-selectpicker" name="permanent_country_id" id="permanent_country_id" data-selected="{{ $permanent_country_id }}" data-live-search="true" required>
                        <option value="">{{translate('Select One')}}</option>
                        @if($india)
                            <option value="{{$india->id}}">{{$india->name}}</option>
                        @endif
                        @foreach ($other_countries as $country)
                            <option value="{{$country->id}}">{{$country->name}}</option>
                        @endforeach
                    </select>
                    @error('permanent_country_id')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="permanent_state_id">{{translate('State')}}</label>
                    <select class="form-control aiz-selectpicker" name="permanent_state_id" id="permanent_state_id" data-live-search="true" required>
                    </select>
                    @error('permanent_state_id')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="permanent_city_id">{{translate('City')}}</label>
                    <select class="form-control aiz-selectpicker" name="permanent_city_id" id="permanent_city_id" data-live-search="true" required>
                    </select>
                </div>
            </div>
            <input type="hidden" name="permanent_postal_code" value="{{ $permanent_postal_code ?? '' }}">

            {{-- Native Details --}}
            <span class="addr-section-header mt-3">{{translate('Native Details')}}</span>
            <div class="mb-3">
                <label class="aiz-checkbox">
                    <input type="checkbox" id="native_same_as_present">
                    <span class="opacity-60">{{translate('Native address is same as present address')}}</span>
                    <span class="aiz-square-check"></span>
                </label>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="native_country_id">{{translate('Country')}}</label>
                    <select class="form-control aiz-selectpicker" name="native_country_id" id="native_country_id" data-selected="{{ $native_country_id }}" data-live-search="true">
                        <option value="">{{translate('Select One')}}</option>
                        @if($india)
                            <option value="{{$india->id}}">{{$india->name}}</option>
                        @endif
                        @foreach ($other_countries as $country)
                            <option value="{{$country->id}}">{{$country->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="native_state_id">{{translate('State')}}</label>
                    <select class="form-control aiz-selectpicker" name="native_state_id" id="native_state_id" data-live-search="true">
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="native_city_id">{{translate('District / City')}}</label>
                    <select class="form-control aiz-selectpicker" name="native_city_id" id="native_city_id" data-live-search="true">
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="native_village">{{translate('Village / Town / Area')}}</label>
                    <input type="text" name="native_village" id="native_village" class="form-control" value="{{ $native_village ?? '' }}" placeholder="{{ translate('Enter village, town or area name') }}">
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Permanent same as present
    var cbPerm = document.getElementById('same_as_present');
    cbPerm.addEventListener('change', function () {
        if (!this.checked) return;
        copyAddress('present', 'permanent', function(){});
    });
    $('#permanent_country_id, #permanent_state_id, #permanent_city_id').on('change', function () {
        cbPerm.checked = false;
    });

    // Native same as present
    var cbNative = document.getElementById('native_same_as_present');
    cbNative.addEventListener('change', function () {
        if (!this.checked) return;
        copyAddress('present', 'native', function(){
            // also copy village? leave blank for native
        });
    });
    $('#native_country_id, #native_state_id, #native_city_id').on('change', function () {
        cbNative.checked = false;
    });

    function copyAddress(src, dest, cb) {
        var srcCountry = $('#' + src + '_country_id').val();
        $('#' + dest + '_country_id').val(srcCountry).trigger('change');
        AIZ.plugins.bootstrapSelect('refresh');
        setTimeout(function () {
            var srcState = $('#' + src + '_state_id').val();
            $('#' + dest + '_state_id').val(srcState).trigger('change');
            AIZ.plugins.bootstrapSelect('refresh');
            setTimeout(function () {
                var srcCity = $('#' + src + '_city_id').val();
                $('#' + dest + '_city_id').val(srcCity);
                AIZ.plugins.bootstrapSelect('refresh');
                if (cb) cb();
            }, 600);
        }, 600);
    }
});
</script>

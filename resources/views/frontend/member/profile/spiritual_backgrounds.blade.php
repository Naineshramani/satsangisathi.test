<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Spiritual & Social Background')}}</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('spiritual_backgrounds.update', $member->id) }}" method="POST">
          <input name="_method" type="hidden" value="PATCH">
          @csrf
          <input type="hidden" name="address_type" value="present">

          {{-- Religion fixed to Hindu --}}
          <input type="hidden" id="member_religion_id" name="member_religion_id" value="1">

          {{-- Ethnicity & community_value hidden — not needed --}}
          <input type="hidden" name="ethnicity" value="{{ $member->spiritual_backgrounds->ethnicity ?? '' }}">
          <input type="hidden" name="community_value" value="">

          <div class="form-group row">
              <div class="col-md-3">
                  <label for="member_caste_id">{{translate('Caste')}}</label>
                  <select class="form-control aiz-selectpicker" name="member_caste_id" id="member_caste_id" data-live-search="true">
                      <option value="">{{translate('Select One')}}</option>
                  </select>
                  @error('member_caste_id')
                      <small class="form-text text-danger">{{ $message }}</small>
                  @enderror
              </div>
              <div class="col-md-3">
                  <label for="personal_value">{{translate('Personal Value')}}</label>
                  <input type="text" name="personal_value" value="{{$member->spiritual_backgrounds->personal_value ?? "" }}" class="form-control" placeholder="{{translate('Personal Value')}}">
              </div>
              <div class="col-md-3">
                  <label for="family_value_id">{{translate('Family Value')}}</label>
                  <select class="form-control aiz-selectpicker" name="family_value_id" data-selected="{{ $member->spiritual_backgrounds->family_value_id ?? '' }}" data-live-search="true">
                      <option value="">{{translate('Select One')}}</option>
                      @foreach ($family_values as $family_value)
                          <option value="{{$family_value->id}}"> {{ $family_value->name }}</option>
                      @endforeach
                  </select>
              </div>
              <div class="col-md-3">
                  <label for="family_status_id">{{translate('Family Status')}}</label>
                  <select class="form-control aiz-selectpicker" name="family_status_id" data-live-search="true">
                      <option value="">{{translate('Select One')}}</option>
                      @foreach ($family_statuses as $fs)
                          <option value="{{ $fs->id }}" {{ ($member->spiritual_backgrounds->family_status_id ?? '') == $fs->id ? 'selected' : '' }}>
                              {{ $fs->name }}
                          </option>
                      @endforeach
                  </select>
              </div>
          </div>

          <div class="text-right">
              <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
          </div>
      </form>
    </div>
</div>


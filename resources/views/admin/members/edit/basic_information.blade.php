<div class="card-header bg-dark text-white">
    <h5 class="mb-0 h6">{{translate('Basic Information')}}</h5>
</div>
<div class="card-body">

    <form action="{{ route('member.basic_info_update', $member->id) }}#basic_information" method="POST">
        @csrf
        <div class="form-group row">
            <div class="col-md-6">
                <label for="first_name" >{{translate('First Name')}}
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="first_name" value="{{ $member->first_name }}" class="form-control" placeholder="{{translate('First Name')}}" required>
                @error('first_name')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="first_name" >{{translate('Last Name')}}
                    <span class="text-danger">*</span>
                </label>
                <input type="text" name="last_name" value="{{ $member->last_name }}" class="form-control" placeholder="{{translate('Last Name')}}" required>
                @error('last_name')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6">
                <label for="first_name" >{{translate('Gender')}}
                    <span class="text-danger">*</span>
                </label>
                <select id="gender" class="form-control aiz-selectpicker" name="gender" required>
                    <option startdate="{{ get_max_date_male() }}" value="1" @if($member->member->gender ==  1) selected @endif >{{translate('Male')}}</option>
                    <option startdate="{{ get_max_date_female() }}" value="2" @if($member->member->gender ==  2) selected @endif >{{translate('Female')}}</option>
                    @error('gender')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </select>
            </div>
            <div class="col-md-6">
                <label for="first_name" >{{translate('Date Of Birth')}}
                    <span class="text-danger">*</span>
                </label>
                <input id="date_of_birth" type="text" class="aiz-date-range form-control" 
                value="@if(!empty($member->member->birthday)) 
                {{date('Y-m-d', strtotime($member->member->birthday))}}
                 @endif" name="date_of_birth"  placeholder="Select Date" 
                 data-single="true" data-show-dropdown="true" 
                 data-max-date="{{ get_max_date_male() }}" autocomplete="off" required>
                @error('date_of_birth')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <input type="hidden" name="email" value="{{ $member->email }}">
            <div class="col-md-6">
                <label for="email" >{{translate('Email')}}</label>
                <input type="email" name="email" value="{{ $member->email }}" class="form-control" placeholder="{{translate('Email')}}" disabled>
                @error('email')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="first_name" >{{translate('Phone Number')}}</label>
                <input type="text" name="phone" value="{{ $member->phone }}" class="form-control" placeholder="{{translate('Phone')}}">
                @error('phone')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label for="first_name" >{{translate('On Behalf')}}
                    <span class="text-danger">{{ $on_behalves->isNotEmpty() ? '*' : '' }}</span>
                </label>
                <select class="form-control aiz-selectpicker" name="on_behalf" data-live-search="true" {{ $on_behalves->isNotEmpty() ? 'required' : '' }}>
                    @foreach ($on_behalves as $on_behalf)
                        <option value="{{$on_behalf->id}}" @if($member->member->on_behalves_id == $on_behalf->id) selected @endif>{{$on_behalf->name}}</option>
                    @endforeach
                </select>
                @error('on_behalf')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="first_name" >{{translate('Annual Salary')}}</label>
                <select class="form-control aiz-selectpicker" name="annual_salary_range" data-live-search="true">
                    <option value="">{{ translate('None') }}</option>
                    @foreach ($annual_salary_ranges as $annual_salary_range)
                        <option value="{{$annual_salary_range->id}}" @if($member->member->annual_salary_range_id == $annual_salary_range->id) selected @endif>{{ single_price($annual_salary_range->min_salary) }} - {{ single_price($annual_salary_range->max_salary) }}</option>
                    @endforeach
                </select>
                @error('annual_salary_range')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        @php
            $showChildren = in_array($member->member->marital_status_id, [2, 3]);
            $childrenCount = intval($member->member->children ?? 0);
        @endphp
        <div class="form-group row">
            <div class="col-md-6">
                <label for="first_name" >{{translate('Marital Status')}}
                    <span class="text-danger">*</span>
                </label>
                <select class="form-control aiz-selectpicker" id="marital_status_select" name="marital_status" data-live-search="true" required>
                    @foreach ($marital_statuses as $marital_status)
                        <option value="{{$marital_status->id}}" @if($member->member->marital_status_id == $marital_status->id) selected @endif>{{$marital_status->name}}</option>
                    @endforeach
                </select>
                @error('marital_status')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6" id="children_field" @if(!$showChildren) style="display:none;" @endif>
                <label for="first_name" >{{translate('Number Of Children')}}</label>
                <input type="number" name="children" id="children_input" value="{{ $member->member->children }}" class="form-control" placeholder="{{translate('Number Of Children')}}" min="0">
            </div>
        </div>
        <div class="form-group row mt-3" id="children_details_field" @if(!$showChildren || $childrenCount <= 0) style="display:none;" @endif>
            <div class="col-md-12">
                <label>{{translate('Details About Children')}}</label>
                <textarea name="children_details" class="form-control" rows="3" placeholder="{{translate('e.g. age, gender, living with you, etc.')}}">{{ $member->member->children_details }}</textarea>
            </div>
        </div>
        <script>
            (function () {
                var maritalSelect = document.getElementById('marital_status_select');
                var childrenField = document.getElementById('children_field');
                var childrenInput = document.getElementById('children_input');
                var childrenDetailsField = document.getElementById('children_details_field');

                function toggleChildrenDetails() {
                    var count = parseInt(childrenInput.value) || 0;
                    childrenDetailsField.style.display = (count > 0) ? '' : 'none';
                    if (count <= 0) childrenDetailsField.querySelector('textarea').value = '';
                }

                function toggleChildren() {
                    var val = maritalSelect.value;
                    if (val == '2' || val == '3') {
                        childrenField.style.display = '';
                    } else {
                        childrenField.style.display = 'none';
                        childrenDetailsField.style.display = 'none';
                        childrenInput.value = '';
                        childrenDetailsField.querySelector('textarea').value = '';
                    }
                    toggleChildrenDetails();
                }

                childrenInput.addEventListener('input', toggleChildrenDetails);
                $(maritalSelect).on('changed.bs.select change', toggleChildren);
                toggleChildrenDetails();
            })();
        </script>
        <div class="form-group row">
            <div class="col-md-12">
              <label class="" for="signinSrEmail">{{translate('Photo')}}</small></label>
                <div class="input-group" data-toggle="aizuploader" data-type="image">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="photo" class="selected-files" value="{{ $member->photo}}">
                </div>
                <div class="file-preview box sm">
                </div>
            </div>
        </div>
        <div class="text-right">
            <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
        </div>
    </form>
</div>

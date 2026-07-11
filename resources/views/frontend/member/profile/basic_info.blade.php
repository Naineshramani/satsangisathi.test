<div class="card" id="sec-basic-info">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Basic Information')}}</h5>
    </div>
    <div class="card-body">

        <form action="{{ route('member.basic_info_update', $member->id) }}" method="POST">
            @csrf
            {{-- Hidden locked fields — values passed as-is, not editable --}}
            <input type="hidden" name="first_name" value="{{ $member->first_name }}">
            <input type="hidden" name="last_name" value="{{ $member->last_name }}">
            <input type="hidden" name="gender" value="{{ $member->member->gender }}">
            <input type="hidden" name="date_of_birth" value="@if(!empty($member->member->birthday)){{ date('Y-m-d', strtotime($member->member->birthday)) }}@endif">
            <input type="hidden" name="phone" value="{{ $member->phone }}">

            <div class="form-group row">
                <div class="col-md-6">
                    <label>{{translate('First Name')}}</label>
                    <div class="form-control bg-light d-flex align-items-center justify-content-between" style="color:#888;">
                        <span>{{ $member->first_name }}</span><i class="las la-lock ml-2"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>{{translate('Last Name')}}</label>
                    <div class="form-control bg-light d-flex align-items-center justify-content-between" style="color:#888;">
                        <span>{{ $member->last_name }}</span><i class="las la-lock ml-2"></i>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-4">
                    <label>{{translate('Gender')}}</label>
                    <div class="form-control bg-light d-flex align-items-center justify-content-between" style="color:#888;">
                        <span>{{ $member->member->gender == 1 ? translate('Male') : translate('Female') }}</span><i class="las la-lock ml-2"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <label>{{translate('Date Of Birth')}}</label>
                    <div class="form-control bg-light d-flex align-items-center justify-content-between" style="color:#888;">
                        <span>{{ !empty($member->member->birthday) ? date('d M Y', strtotime($member->member->birthday)) : '—' }}</span><i class="las la-lock ml-2"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <label>{{translate('On Behalf')}}
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
            </div>

            @php
                $showChildren = in_array($member->member->marital_status_id, [2, 3]);
            @endphp
            <div class="form-group row">
                <div class="col-md-6">
                    <label>{{translate('Marital Status')}}
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-control aiz-selectpicker" id="marital_status_select" name="marital_status" title="{{ translate('Select Marital Status') }}" data-live-search="true" required>
                        @foreach ($marital_statuses as $marital_status)
                            <option value="{{$marital_status->id}}" @if($member->member->marital_status_id == $marital_status->id) selected @endif>{{$marital_status->name}}</option>
                        @endforeach
                    </select>
                    @error('marital_status')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6" id="children_field" @if(!$showChildren) style="display:none;" @endif>
                    <label>{{translate('Number Of Children')}}</label>
                    <input type="number" name="children" id="children_input" value="{{ $member->member->children }}" class="form-control" placeholder="{{translate('Number Of Children')}}" min="0">
                </div>
            </div>
            @php $childrenCount = intval($member->member->children ?? 0); @endphp
            <div class="form-group row mt-3" id="children_details_field" @if(!$showChildren || $childrenCount <= 0) style="display:none;" @endif>
                <div class="col-md-12">
                    <label>{{translate('Details About Children')}}</label>
                    <textarea name="children_details" class="form-control" rows="3" placeholder="{{translate('e.g. age, gender, living with you, etc.')}}">{{ $member->member->children_details }}</textarea>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
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
                });
            </script>

            <div class="form-group row">
                <div class="col-md-6">
                    <label>{{translate('Height')}} <small>({{ translate('In Feet') }})</small></label>
                    <input type="text" name="height" value="{{ $member->physical_attributes->height ?? '' }}" class="form-control" placeholder="{{translate('e.g. 5.10, 5.6')}}">
                </div>
                <div class="col-md-6">
                    <label>{{translate('Weight')}} <small>({{ translate('In Kg') }})</small></label>
                    <input type="number" name="weight" step="any" value="{{ $member->physical_attributes->weight ?? '' }}" class="form-control" placeholder="{{translate('Weight')}}">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label>{{translate('Disability (If Any)')}}</label>
                    <input type="text" name="disability" value="{{ $member->physical_attributes->disability ?? '' }}" class="form-control" placeholder="{{translate('Disability (If Any)')}}">
                </div>
                <div class="col-md-6">
                    <label for="photo" >{{translate('Photo')}} <small>(800x800)</small>
                        @if(auth()->user()->photo != null && auth()->user()->photo_approved == 0)
                        <small class="text-danger">({{ translate('Pending for Admin Approval.') }})</small>
                        @elseif(auth()->user()->photo != null && auth()->user()->photo_approved == 1)
                            <small class="text-danger">({{ translate('Approved.') }})</small>
                        @endif</label>
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="photo" class="selected-files" value="{{ $member->photo }}">
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
</div>

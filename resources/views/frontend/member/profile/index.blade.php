@extends('frontend.layouts.member_panel')
@section('panel_content')
    <!-- Contact Details -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Contact Details') }}</h5>
        </div>
        <div class="card-body">
            @php
                $fatherMobile = Auth::user()->father_mobile ?? '';
                $motherMobile = Auth::user()->mother_mobile ?? '';
                // Split stored E.164 number into country code + number for display
                $fatherCC  = '91'; $fatherNum = '';
                if ($fatherMobile && str_starts_with($fatherMobile, '+')) {
                    preg_match('/^\+(\d{1,3})(\d+)$/', $fatherMobile, $fm);
                    $fatherCC = $fm[1] ?? '91'; $fatherNum = $fm[2] ?? '';
                } elseif ($fatherMobile) { $fatherNum = ltrim($fatherMobile, '+'); }
                $motherCC  = '91'; $motherNum = '';
                if ($motherMobile && str_starts_with($motherMobile, '+')) {
                    preg_match('/^\+(\d{1,3})(\d+)$/', $motherMobile, $mm);
                    $motherCC = $mm[1] ?? '91'; $motherNum = $mm[2] ?? '';
                } elseif ($motherMobile) { $motherNum = ltrim($motherMobile, '+'); }
                $primaryContact = Auth::user()->primary_contact ?? 'candidate';
            @endphp
            <form action="{{ route('member.contact_details_update') }}" method="POST">
                @csrf
                {{-- Locked fields --}}
                <div class="form-group row">
                    <div class="col-md-6">
                        <label>{{ translate('Candidate Mobile No') }}</label>
                        <div class="form-control bg-light d-flex align-items-center justify-content-between" style="color:#888;">
                            <span>{{ Auth::user()->phone ?? '—' }}</span><i class="las la-lock ml-2"></i>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>{{ translate('Email Address') }}</label>
                        <div class="form-control bg-light d-flex align-items-center justify-content-between" style="color:#888;">
                            <span>{{ Auth::user()->email ?? '—' }}</span><i class="las la-lock ml-2"></i>
                        </div>
                    </div>
                </div>

                {{-- Father's Mobile --}}
                <div class="form-group row">
                    <div class="col-md-6">
                        <label>{{ translate("Father's Mobile No") }}</label>
                        <input type="tel" id="father_phone_input" class="form-control" name="father_mobile" value="{{ $fatherNum }}" placeholder="{{ translate('Father Mobile Number') }}" autocomplete="off">
                        <input type="hidden" name="father_country_code" id="father_country_code" value="{{ $fatherCC }}">
                    </div>
                    <div class="col-md-6">
                        <label>{{ translate("Mother's Mobile No") }}</label>
                        <input type="tel" id="mother_phone_input" class="form-control" name="mother_mobile" value="{{ $motherNum }}" placeholder="{{ translate('Mother Mobile Number') }}" autocomplete="off">
                        <input type="hidden" name="mother_country_code" id="mother_country_code" value="{{ $motherCC }}">
                    </div>
                </div>

                {{-- Primary Contact --}}
                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="fw-600">{{ translate('Primary Contact Number') }}</label>
                        <div class="d-flex flex-wrap" style="gap:16px;">
                            <label class="aiz-checkbox">
                                <input type="radio" name="primary_contact" value="candidate" {{ $primaryContact == 'candidate' ? 'checked' : '' }}>
                                <span class="opacity-60">{{ translate('Candidate No') }} <small class="text-muted">({{ Auth::user()->phone ?? '—' }})</small></span>
                                <span class="aiz-rounded-check"></span>
                            </label>
                            <label class="aiz-checkbox">
                                <input type="radio" name="primary_contact" value="father" {{ $primaryContact == 'father' ? 'checked' : '' }}>
                                <span class="opacity-60" id="primary_father_label">{{ translate("Father's No") }} <small class="text-muted">({{ $fatherMobile ?: '—' }})</small></span>
                                <span class="aiz-rounded-check"></span>
                            </label>
                            <label class="aiz-checkbox">
                                <input type="radio" name="primary_contact" value="mother" {{ $primaryContact == 'mother' ? 'checked' : '' }}>
                                <span class="opacity-60" id="primary_mother_label">{{ translate("Mother's No") }} <small class="text-muted">({{ $motherMobile ?: '—' }})</small></span>
                                <span class="aiz-rounded-check"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary btn-sm">{{ translate('Update') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Introduction -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Introduction')}}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('member.introduction.update', $member->member->id) }}" method="POST">
                @csrf
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">{{translate('Introduction')}}</label>
                    <div class="col-md-10">
                        <textarea type="text" name="introduction" class="form-control" rows="4" placeholder="{{translate('Introduction')}}" required>{{ $member->member->introduction }}</textarea>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Documents -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Documents') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('member.documents_update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Biodata -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-600">{{ translate('Biodata') }} <small class="text-muted fw-400">(PDF / DOC / Image, max 5MB)</small></label>

                        @if (Auth::user()->biodata_file)
                            @php $ext = strtolower(pathinfo(Auth::user()->biodata_file, PATHINFO_EXTENSION)); @endphp
                            <div class="mb-2 p-2 border rounded d-flex align-items-center" style="gap:10px;background:#fafafa;">
                                @if (in_array($ext, ['jpg','jpeg','png']))
                                    <img src="{{ static_asset(Auth::user()->biodata_file) }}" style="height:48px;width:48px;object-fit:cover;border-radius:4px;">
                                @else
                                    <i class="las la-file-pdf text-danger" style="font-size:2rem;"></i>
                                @endif
                                <div style="flex:1;min-width:0;">
                                    <div class="text-truncate" style="font-size:0.82rem;">{{ basename(Auth::user()->biodata_file) }}</div>
                                    <small class="text-muted">{{ translate('Currently uploaded') }}</small>
                                </div>
                                <a href="{{ static_asset(Auth::user()->biodata_file) }}" target="_blank" class="btn btn-xs btn-outline-secondary" style="white-space:nowrap;">
                                    <i class="las la-external-link-alt"></i>
                                </a>
                            </div>
                        @endif

                        <input type="file" class="form-control @error('biodata_file') is-invalid @enderror" name="biodata_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        @if (Auth::user()->biodata_file)
                            <small class="text-muted">{{ translate('Upload a new file to replace the existing one') }}</small>
                        @endif
                        @error('biodata_file')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- ID Proof -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-600">{{ translate('ID Proof') }} <small class="text-muted fw-400">(PDF / Image, max 5MB)</small></label>

                        @if (Auth::user()->id_proof_file)
                            @php $idExt = strtolower(pathinfo(Auth::user()->id_proof_file, PATHINFO_EXTENSION)); @endphp
                            <div class="mb-2 p-2 border rounded d-flex align-items-center" style="gap:10px;background:#fafafa;">
                                @if (in_array($idExt, ['jpg','jpeg','png']))
                                    <img src="{{ static_asset(Auth::user()->id_proof_file) }}" style="height:48px;width:48px;object-fit:cover;border-radius:4px;">
                                @else
                                    <i class="las la-id-card text-primary" style="font-size:2rem;"></i>
                                @endif
                                <div style="flex:1;min-width:0;">
                                    <div class="text-truncate" style="font-size:0.82rem;">{{ basename(Auth::user()->id_proof_file) }}</div>
                                    <small class="text-muted">{{ translate('Currently uploaded') }}</small>
                                </div>
                                <a href="{{ static_asset(Auth::user()->id_proof_file) }}" target="_blank" class="btn btn-xs btn-outline-secondary" style="white-space:nowrap;">
                                    <i class="las la-external-link-alt"></i>
                                </a>
                            </div>
                        @endif

                        <input type="file" class="form-control @error('id_proof_file') is-invalid @enderror" name="id_proof_file" accept=".pdf,.jpg,.jpeg,.png">
                        @if (Auth::user()->id_proof_file)
                            <small class="text-muted">{{ translate('Upload a new file to replace the existing one') }}</small>
                        @endif
                        @error('id_proof_file')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-primary btn-sm">{{ translate('Update Documents') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Basic Information -->
    @include('frontend.member.profile.basic_info')

    <!-- Address Information (Present + Permanent + Native merged) -->
    @php
        $present_address      = \App\Models\Address::where('type','present')->where('user_id',$member->id)->first();
        $present_country_id   = $present_address->country_id ?? "";
        $present_state_id     = $present_address->state_id ?? "";
        $present_city_id      = $present_address->city_id ?? "";
        $present_postal_code  = $present_address->postal_code ?? "";
        $permanent_address    = \App\Models\Address::where('type','permanent')->where('user_id',$member->id)->first();
        $permanent_country_id = $permanent_address->country_id ?? "";
        $permanent_state_id   = $permanent_address->state_id ?? "";
        $permanent_city_id    = $permanent_address->city_id ?? "";
        $permanent_postal_code= $permanent_address->postal_code ?? "";
        $native_address       = \App\Models\Address::where('type','native')->where('user_id',$member->id)->first();
        $native_country_id    = $native_address->country_id ?? "";
        $native_state_id      = $native_address->state_id ?? "";
        $native_city_id       = $native_address->city_id ?? "";
        $native_village       = $native_address->native_village ?? "";
    @endphp
    @if(get_setting('member_present_address_section') == 'on' || get_setting('member_permanent_address_section') == 'on')
      @include('frontend.member.profile.present_address')
    @endif

    <!-- Education -->
    @if(get_setting('member_education_section') == 'on')
      @include('frontend.member.profile.education.index')
    @endif

    <!-- Career -->
    @if(get_setting('member_career_section') == 'on')
      @include('frontend.member.profile.career.index')
    @endif

    <!-- Physical Attributes -->
    @if(get_setting('member_physical_attributes_section') == 'on')
      @include('frontend.member.profile.physical_attributes')
    @endif

    <!-- Language -->
    @if(get_setting('member_language_section') == 'on')
      @include('frontend.member.profile.language')
    @endif

    <!-- Hobbies  -->
    @if(get_setting('member_hobbies_and_interests_section') == 'on')
      @include('frontend.member.profile.hobbies_interest')
    @endif

    <!-- Personal Attitude & Behavior -->
    @if(get_setting('member_personal_attitude_and_behavior_section') == 'on')
      @include('frontend.member.profile.attitudes_behavior')
    @endif

    <!-- Residency Information -->
    @if(get_setting('member_residency_information_section') == 'on')
      @include('frontend.member.profile.residency_information')
    @endif

    <!-- Spiritual & Social Background -->
    @php
        $member_religion_id   =  $member->spiritual_backgrounds->religion_id ?? "";
        $member_caste_id      =  $member->spiritual_backgrounds->caste_id ?? "";
        $member_sub_caste_id  =  $member->spiritual_backgrounds->sub_caste_id ?? "";
    @endphp
    @if(get_setting('member_spiritual_and_social_background_section') == 'on')
      @include('frontend.member.profile.spiritual_backgrounds')
    @endif

    <!-- Satsang Details -->
    @include('frontend.member.profile.satsang_details')

    <!-- Life Style -->
    @if(get_setting('member_life_style_section') == 'on')
      @include('frontend.member.profile.lifestyle')
    @endif

    <!-- Astronomic Information  -->
    @if(get_setting('member_astronomic_information_section') == 'on')
      @include('frontend.member.profile.astronomic_information')
    @endif

    {{-- Permanent address merged into Address Information section above --}}

    <!-- Family Information -->
    @if(get_setting('member_family_information_section') == 'on')
      @include('frontend.member.profile.family_information')
    @endif

    <!-- Partner Expectation -->
    @php
        $partner_religion_id   = $member->partner_expectations->religion_id ?? "";
        $partner_caste_id      = $member->partner_expectations->caste_id ?? "";
        $partner_sub_caste_id  = $member->partner_expectations->sub_caste_id ?? "";
        $partner_country_id    = $member->partner_expectations->preferred_country_id ?? "";
        $partner_state_id      = $member->partner_expectations->preferred_state_id ?? "";
    @endphp
    @if(get_setting('member_partner_expectation_section') == 'on')
      @include('frontend.member.profile.partner_expectation')
    @endif

    @if(get_setting('additional_profile_section') == 'on')
      @include('frontend.member.profile.additional_attributes')
    @endif

    @if (session('resent'))
    <div class="alert alert-success mt-4" role="alert">
        <i class="las la-check-circle mr-1"></i>{{ translate('A verification email has been sent to your email address.') }}
    </div>
    @endif

    @if(get_setting('email_verification') == 1 && Auth::user()->email_verified_at == null)
    <div class="card mt-4" style="border: 2px solid #E8A800;">
        <div class="card-body d-flex align-items-center justify-content-between flex-wrap" style="gap:12px;">
            <div>
                <h5 class="mb-1 fw-700" style="color:#E8A800;">
                    <i class="las la-exclamation-circle mr-1"></i>{{ translate('Account Not Verified') }}
                </h5>
                <p class="mb-0 opacity-70">{{ translate('Verify your email to unlock all features — messaging, interests, shortlists, and more.') }}</p>
            </div>
            <a href="{{ route('verification.resend') }}" class="btn btn-primary fw-600">
                <i class="las la-envelope mr-1"></i>{{ translate('Verify Now') }}
            </a>
        </div>
    </div>
    @endif

    @if(Auth::user()->approved == 0)
    <div class="card mt-4" style="border: 2px solid #E8A800;">
        <div class="card-body">
            <h5 class="mb-1 fw-700" style="color:#E8A800;">
                <i class="las la-exclamation-circle mr-1"></i>{{ translate('Account Pending Approval') }}
            </h5>
            <p class="mb-0 opacity-70">{{ translate('Your account is awaiting admin approval. Messaging, interests, shortlists, and profile viewers will be available once approved.') }}</p>
        </div>
    </div>
    @endif

@endsection

@section('modal')
    @include('modals.create_edit_modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">

    // Father & Mother phone intlTelInput
    function initContactIti(inputId, ccId, dialCode) {
        var el = document.getElementById(inputId);
        if (!el) return;
        var iti = intlTelInput(el, {
            initialCountry: 'in',
            separateDialCode: true,
            utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}",
            onlyCountries: @php echo json_encode(\App\Models\Country::where('status', 1)->pluck('code')->toArray()) @endphp,
        });
        // Set saved country by dial code
        if (dialCode) {
            iti.setNumber('+' + dialCode + el.value);
            document.getElementById(ccId).value = iti.getSelectedCountryData().dialCode;
        }
        el.addEventListener('countrychange', function () {
            document.getElementById(ccId).value = iti.getSelectedCountryData().dialCode;
        });
        // On form submit strip the dial code prefix from the visible input
        el.closest('form').addEventListener('submit', function () {
            document.getElementById(ccId).value = iti.getSelectedCountryData().dialCode;
            el.value = iti.getNumber().replace('+' + iti.getSelectedCountryData().dialCode, '').trim();
        });
    }

    $(document).ready(function(){
        initContactIti('father_phone_input', 'father_country_code', '{{ $fatherCC }}');
        initContactIti('mother_phone_input', 'mother_country_code', '{{ $motherCC }}');
        get_states_by_country_for_present_address();
        get_cities_by_state_for_present_address();
        get_states_by_country_for_permanent_address();
        get_cities_by_state_for_permanent_address();
        get_states_by_country_for_native_address();
        get_cities_by_state_for_native_address();
        get_castes_by_religion_for_member();
        get_sub_castes_by_caste_for_member();
        get_castes_by_religion_for_partner();
        get_sub_castes_by_caste_for_partner();
        get_states_by_country_for_partner();
    });

    // For Present address
    function get_states_by_country_for_present_address(){
        var present_country_id = $('#present_country_id').val();
            $.post('{{ route('states.get_state_by_country') }}',{_token:'{{ csrf_token() }}', country_id:present_country_id}, function(data){
                $('#present_state_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#present_state_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#present_state_id > option").each(function() {
                    if(this.value == '{{$present_state_id}}'){
                        $("#present_state_id").val(this.value).change();
                    }
                });

                AIZ.plugins.bootstrapSelect('refresh');

                get_cities_by_state_for_present_address();
            });
        }

    function get_cities_by_state_for_present_address(){
		var present_state_id = $('#present_state_id').val();
    		$.post('{{ route('cities.get_cities_by_state') }}',{_token:'{{ csrf_token() }}', state_id:present_state_id}, function(data){
    		    $('#present_city_id').html(null);
    		    for (var i = 0; i < data.length; i++) {
    		        $('#present_city_id').append($('<option>', {
    		            value: data[i].id,
    		            text: data[i].name
    		        }));
    		    }
    		    $("#present_city_id > option").each(function() {
    		        if(this.value == '{{$present_city_id}}'){
    		            $("#present_city_id").val(this.value).change();
    		        }
    		    });

    		    AIZ.plugins.bootstrapSelect('refresh');
    		});
    	}

    $('#present_country_id').on('change', function() {
  	    get_states_by_country_for_present_address();
  	});

    $('#present_state_id').on('change', function() {
  	    get_cities_by_state_for_present_address();
  	});

    // For permanent address
    function get_states_by_country_for_permanent_address(){
        var permanent_country_id = $('#permanent_country_id').val();
            $.post('{{ route('states.get_state_by_country') }}',{_token:'{{ csrf_token() }}', country_id:permanent_country_id}, function(data){
                $('#permanent_state_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#permanent_state_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#permanent_state_id > option").each(function() {
                    if(this.value == '{{$permanent_state_id}}'){
                        $("#permanent_state_id").val(this.value).change();
                    }
                });

                AIZ.plugins.bootstrapSelect('refresh');

                get_cities_by_state_for_permanent_address();
            });
    }

    function get_cities_by_state_for_permanent_address(){
        var permanent_state_id = $('#permanent_state_id').val();
            $.post('{{ route('cities.get_cities_by_state') }}',{_token:'{{ csrf_token() }}', state_id:permanent_state_id}, function(data){
                $('#permanent_city_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#permanent_city_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#permanent_city_id > option").each(function() {
                    if(this.value == '{{$permanent_city_id}}'){
                        $("#permanent_city_id").val(this.value).change();
                    }
                });

                AIZ.plugins.bootstrapSelect('refresh');
            });
    }

    $('#permanent_country_id').on('change', function() {
        get_states_by_country_for_permanent_address();
    });

    $('#permanent_state_id').on('change', function() {
        get_cities_by_state_for_permanent_address();
    });

    // For native address
    function get_states_by_country_for_native_address(){
        var native_country_id = $('#native_country_id').val();
        $.post('{{ route('states.get_state_by_country') }}',{_token:'{{ csrf_token() }}', country_id:native_country_id}, function(data){
            $('#native_state_id').html(null);
            for (var i = 0; i < data.length; i++) {
                $('#native_state_id').append($('<option>', { value: data[i].id, text: data[i].name }));
            }
            $("#native_state_id > option").each(function() {
                if(this.value == '{{$native_state_id}}'){
                    $("#native_state_id").val(this.value).change();
                }
            });
            AIZ.plugins.bootstrapSelect('refresh');
            get_cities_by_state_for_native_address();
        });
    }

    function get_cities_by_state_for_native_address(){
        var native_state_id = $('#native_state_id').val();
        $.post('{{ route('cities.get_cities_by_state') }}',{_token:'{{ csrf_token() }}', state_id:native_state_id}, function(data){
            $('#native_city_id').html(null);
            for (var i = 0; i < data.length; i++) {
                $('#native_city_id').append($('<option>', { value: data[i].id, text: data[i].name }));
            }
            $("#native_city_id > option").each(function() {
                if(this.value == '{{$native_city_id}}'){
                    $("#native_city_id").val(this.value).change();
                }
            });
            AIZ.plugins.bootstrapSelect('refresh');
        });
    }

    $('#native_country_id').on('change', function() { get_states_by_country_for_native_address(); });
    $('#native_state_id').on('change', function() { get_cities_by_state_for_native_address(); });

    // get castes and subcastes For member
    function get_castes_by_religion_for_member(){
        var member_religion_id = $('#member_religion_id').val();
            $.post('{{ route('castes.get_caste_by_religion') }}',{_token:'{{ csrf_token() }}', religion_id:member_religion_id}, function(data){
                $('#member_caste_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#member_caste_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#member_caste_id > option").each(function() {
                    if(this.value == '{{$member_caste_id}}'){
                        $("#member_caste_id").val(this.value).change();
                    }
                });
                AIZ.plugins.bootstrapSelect('refresh');

                get_sub_castes_by_caste_for_member();
            });
        }

    function get_sub_castes_by_caste_for_member(){
        var member_caste_id = $('#member_caste_id').val();
            $.post('{{ route('sub_castes.get_sub_castes_by_religion') }}',{_token:'{{ csrf_token() }}', caste_id:member_caste_id}, function(data){
                $('#member_sub_caste_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#member_sub_caste_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#member_sub_caste_id > option").each(function() {
                    if(this.value == '{{$member_sub_caste_id}}'){
                        $("#member_sub_caste_id").val(this.value).change();
                    }
                });
                AIZ.plugins.bootstrapSelect('refresh');
            });
        }

    $('#member_religion_id').on('change', function() {
        get_castes_by_religion_for_member();
    });

    // Load castes on page load (religion is fixed to Hindu)
    get_castes_by_religion_for_member();

    $('#member_caste_id').on('change', function() {
        get_sub_castes_by_caste_for_member();
    });

    // get castes and subcastes For partner
    function get_castes_by_religion_for_partner(){
        var partner_religion_id = $('#partner_religion_id').val();
            $.post('{{ route('castes.get_caste_by_religion') }}',{_token:'{{ csrf_token() }}', religion_id:partner_religion_id}, function(data){
                $('#partner_caste_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#partner_caste_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#partner_caste_id > option").each(function() {
                    if(this.value == '{{$partner_caste_id}}'){
                        $("#partner_caste_id").val(this.value).change();
                    }
                });
                AIZ.plugins.bootstrapSelect('refresh');

                get_sub_castes_by_caste_for_partner();
            });
        }

    function get_sub_castes_by_caste_for_partner(){
        var partner_caste_id = $('#partner_caste_id').val();
            $.post('{{ route('sub_castes.get_sub_castes_by_religion') }}',{_token:'{{ csrf_token() }}', caste_id:partner_caste_id}, function(data){
                $('#partner_sub_caste_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#partner_sub_caste_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#partner_sub_caste_id > option").each(function() {
                    if(this.value == '{{$partner_sub_caste_id}}'){
                        $("#partner_sub_caste_id").val(this.value).change();
                    }
                });
                AIZ.plugins.bootstrapSelect('refresh');
            });
        }

    $('#partner_religion_id').on('change', function() {
        get_castes_by_religion_for_partner();
    });

    $('#partner_caste_id').on('change', function() {
        get_sub_castes_by_caste_for_partner();
    });

    // For partner address
    function get_states_by_country_for_partner(){
        var partner_country_id = $('#partner_country_id').val();
            $.post('{{ route('states.get_state_by_country') }}',{_token:'{{ csrf_token() }}', country_id:partner_country_id}, function(data){
                $('#partner_state_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#partner_state_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#partner_state_id > option").each(function() {
                    if(this.value == '{{$partner_state_id}}'){
                        $("#partner_state_id").val(this.value).change();
                    }
                });

                AIZ.plugins.bootstrapSelect('refresh');
            });
    }

    $('#partner_country_id').on('change', function() {
        get_states_by_country_for_partner();
    });

    //  education Add edit , status change
    function education_add_modal(id){
       $.post('{{ route('education.create') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
           $('.create_edit_modal_content').html(data);
           $('.create_edit_modal').modal('show');
       });
    }

    function education_edit_modal(id){
        $.post('{{ route('education.edit') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
            $('.create_edit_modal_content').html(data);
            $('.create_edit_modal').modal('show');
        });
    }

    function update_education_present_status(el) {
        if (el.checked) {
            var status = 1;
        } else {
            var status = 0;
        }
        $.post('{{ route('education.update_education_present_status') }}', {
            _token: '{{ csrf_token() }}',
            id: el.value,
            status: status
        }, function (data) {
            if (data == 1) {
                location.reload();
            } else {
                AIZ.plugins.notify('danger', 'Something went wrong');
            }
        });
    }

    function update_highest_degree(el) {
        if (el.checked) {
            var status = 1;
        } else {
            var status = 0;
        }
        $.post('{{ route('education.update_highest_degree') }}', {
            _token: '{{ csrf_token() }}',
            id: el.value,
            status: status
        }, function(data) {
            if (data == 1) {
                AIZ.plugins.notify('success', 'Data updated successfully');
                location.reload();
            } else {
                AIZ.plugins.notify('danger', 'Something went wrong');
            }
        });
    }


    //  Career Add edit , status change
    function career_add_modal(id){
       $.post('{{ route('career.create') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
           $('.create_edit_modal_content').html(data);
           $('.create_edit_modal').modal('show');
       });
    }

    function career_edit_modal(id){
        $.post('{{ route('career.edit') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
            $('.create_edit_modal_content').html(data);
            $('.create_edit_modal').modal('show');
        });
    }

    function update_career_present_status(el) {
        if (el.checked) {
            var status = 1;
        } else {
            var status = 0;
        }
        $.post('{{ route('career.update_career_present_status') }}', {
            _token: '{{ csrf_token() }}',
            id: el.value,
            status: status
        }, function (data) {
            if (data == 1) {
                location.reload();
            } else {
                AIZ.plugins.notify('danger', 'Something went wrong');
            }
        });
    }

    $('.new-email-verification').on('click', function() {
        $(this).find('.loading').removeClass('d-none');
        $(this).find('.default').addClass('d-none');
        var email = $("input[name=email]").val();

        $.post('{{ route('user.new.verify') }}', {_token:'{{ csrf_token() }}', email: email}, function(data){
            data = JSON.parse(data);
            $('.default').removeClass('d-none');
            $('.loading').addClass('d-none');
            if(data.status == 2)
                AIZ.plugins.notify('warning', data.message);
            else if(data.status == 1)
                AIZ.plugins.notify('success', data.message);
            else
                AIZ.plugins.notify('danger', data.message);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('gender');
        const dobInput = document.getElementById("date_of_birth"); 
        function initDatepicker(maxDate) {
            let existingDate = dobInput.value;
            if ($(dobInput).data('daterangepicker')) {
                $(dobInput).data('daterangepicker').remove();
            }
            $(dobInput).daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                maxDate: maxDate,
                startDate: existingDate ? existingDate : maxDate, 
                autoUpdateInput: true,
                locale: {
                    format: 'YYYY-MM-DD'
                },
            });
        }
        initDatepicker(select.options[select.selectedIndex].getAttribute('startdate'));
        select.addEventListener('change', function() {
            const maxDate = this.options[this.selectedIndex].getAttribute('startdate');
            initDatepicker(maxDate);
        });
    });

    // Scroll back to section after form submit
    (function () {
        var savedSection = sessionStorage.getItem('profileSection');
        if (savedSection) {
            sessionStorage.removeItem('profileSection');
            var el = document.getElementById(savedSection);
            if (el) {
                setTimeout(function () {
                    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 200);
            }
        }

        document.querySelectorAll('[id^="sec-"] form').forEach(function (form) {
            form.addEventListener('submit', function () {
                var card = this.closest('[id^="sec-"]');
                if (card) sessionStorage.setItem('profileSection', card.id);
            });
        });
    })();
</script>
@endsection

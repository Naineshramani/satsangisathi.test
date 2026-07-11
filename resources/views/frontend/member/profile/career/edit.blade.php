<form action="{{ route('career_update', $career->id) }}#career" method="POST" id="career_edit_form_{{ $career->id }}">
    @csrf
    <input name="_method" type="hidden" value="PATCH">

    {{-- Hidden real fields read by controller --}}
    <input type="hidden" name="designation"        id="h_designation_{{ $career->id }}">
    <input type="hidden" name="company"            id="h_company_{{ $career->id }}">
    <input type="hidden" name="nature_of_business" id="h_nature_{{ $career->id }}">

    <div class="modal-header">
        <h5 class="modal-title h6">{{translate('Edit Career Info')}}</h5>
        <button type="button" class="close" data-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        @php
            $empType = $career->employment_type ?? 'job';
            $currencies = [['code'=>'INR','symbol'=>'₹','name'=>'Indian Rupee'],['code'=>'USD','symbol'=>'$','name'=>'US Dollar'],['code'=>'GBP','symbol'=>'£','name'=>'Pound Sterling'],['code'=>'EUR','symbol'=>'€','name'=>'Euro'],['code'=>'AED','symbol'=>'AED','name'=>'UAE Dirham'],['code'=>'CAD','symbol'=>'C$','name'=>'Canadian Dollar'],['code'=>'AUD','symbol'=>'A$','name'=>'Australian Dollar'],['code'=>'SGD','symbol'=>'S$','name'=>'Singapore Dollar'],['code'=>'QAR','symbol'=>'QAR','name'=>'Qatari Riyal'],['code'=>'SAR','symbol'=>'SAR','name'=>'Saudi Riyal'],['code'=>'MYR','symbol'=>'RM','name'=>'Malaysian Ringgit']];
            $savedSymbol = collect($currencies)->firstWhere('code', $career->currency)['symbol'] ?? '₹';
            $eid = $career->id;
        @endphp

        {{-- Employment Type --}}
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Type')}}</label>
            <div class="col-md-9 d-flex flex-wrap" style="gap:12px; padding-top:7px;">
                <label class="aiz-checkbox mb-0">
                    <input type="radio" name="employment_type" value="job" @if($empType==='job') checked @endif onchange="careerTypeChange(this,'_e{{ $eid }}')">
                    <span class="opacity-60 mr-1">{{translate('Job')}}</span>
                    <span class="aiz-rounded-check"></span>
                </label>
                <label class="aiz-checkbox mb-0">
                    <input type="radio" name="employment_type" value="business" @if($empType==='business') checked @endif onchange="careerTypeChange(this,'_e{{ $eid }}')">
                    <span class="opacity-60 mr-1">{{translate('Business')}}</span>
                    <span class="aiz-rounded-check"></span>
                </label>
                <label class="aiz-checkbox mb-0">
                    <input type="radio" name="employment_type" value="self_employed" @if($empType==='self_employed') checked @endif onchange="careerTypeChange(this,'_e{{ $eid }}')">
                    <span class="opacity-60 mr-1">{{translate('Self-Employed')}}</span>
                    <span class="aiz-rounded-check"></span>
                </label>
                <label class="aiz-checkbox mb-0">
                    <input type="radio" name="employment_type" value="not_working" @if($empType==='not_working') checked @endif onchange="careerTypeChange(this,'_e{{ $eid }}')">
                    <span class="opacity-60 mr-1">{{translate('Not Working')}}</span>
                    <span class="aiz-rounded-check"></span>
                </label>
            </div>
        </div>

        {{-- Job fields --}}
        <div id="job_fields_e{{ $eid }}" @if($empType !== 'job') style="display:none;" @endif>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Designation')}}</label>
                <div class="col-md-9">
                    <input type="text" id="vis_designation_{{ $eid }}" class="form-control" value="{{ $empType === 'job' ? $career->designation : '' }}" placeholder="{{translate('e.g. Software Engineer, Manager')}}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Company Name')}}</label>
                <div class="col-md-9">
                    <input type="text" id="vis_company_{{ $eid }}" class="form-control" value="{{ $empType === 'job' ? $career->company : '' }}" placeholder="{{translate('Company name')}}">
                </div>
            </div>
        </div>

        {{-- Business fields --}}
        <div id="business_fields_e{{ $eid }}" @if($empType !== 'business') style="display:none;" @endif>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Nature of Business')}}</label>
                <div class="col-md-9">
                    <input type="text" id="vis_nature_{{ $eid }}" class="form-control" value="{{ $career->nature_of_business }}" placeholder="{{translate('e.g. Retail, Manufacturing, IT Services')}}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Business Name')}}</label>
                <div class="col-md-9">
                    <input type="text" id="vis_company_biz_{{ $eid }}" class="form-control" value="{{ $empType === 'business' ? $career->company : '' }}" placeholder="{{translate('Business / firm name')}}">
                </div>
            </div>
        </div>

        {{-- Self-Employed fields --}}
        <div id="self_fields_e{{ $eid }}" @if($empType !== 'self_employed') style="display:none;" @endif>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Nature of Work')}}</label>
                <div class="col-md-9">
                    <input type="text" id="vis_designation_self_{{ $eid }}" class="form-control" value="{{ $empType === 'self_employed' ? $career->designation : '' }}" placeholder="{{translate('e.g. Freelance Developer, Consultant')}}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Trading / Firm Name')}}</label>
                <div class="col-md-9">
                    <input type="text" id="vis_company_self_{{ $eid }}" class="form-control" value="{{ $empType === 'self_employed' ? $career->company : '' }}" placeholder="{{translate('Optional')}}">
                </div>
            </div>
        </div>

        {{-- Common income fields (hidden for Not Working) --}}
        <div id="income_fields_e{{ $eid }}" @if($empType === 'not_working') style="display:none;" @endif>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Currency')}}</label>
                <div class="col-md-9">
                    <select class="form-control aiz-selectpicker" name="currency" id="currency_edit_{{ $eid }}">
                        @foreach($currencies as $c)
                            <option value="{{ $c['code'] }}" data-symbol="{{ $c['symbol'] }}" @if(($career->currency ?? 'INR') == $c['code']) selected @endif>{{ $c['code'] }} – {{ $c['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Monthly Income')}}</label>
                <div class="col-md-9">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text currency-symbol-edit-{{ $eid }}">{{ $savedSymbol }}</span></div>
                        <input type="number" name="career_start" value="{{ $career->start }}" class="form-control" placeholder="{{translate('Monthly Income')}}">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Yearly Income')}}</label>
                <div class="col-md-9">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text currency-symbol-edit-{{ $eid }}">{{ $savedSymbol }}</span></div>
                        <input type="number" name="career_end" value="{{ $career->end }}" class="form-control" placeholder="{{ translate('Yearly Income') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Close')}}</button>
        <button type="submit" class="btn btn-primary">{{translate('Update Career Info')}}</button>
    </div>
</form>

<script>
(function() {
    var eid = {{ $eid }};

    $('#currency_edit_' + eid).on('changed.bs.select change', function() {
        var sym = $(this).find(':selected').data('symbol') || $(this).val();
        $('.currency-symbol-edit-' + eid).text(sym);
    });

    document.querySelector('#income_fields_e' + eid + ' [name="career_start"]').addEventListener('input', function() {
        var monthly = parseFloat(this.value) || 0;
        document.querySelector('#income_fields_e' + eid + ' [name="career_end"]').value = monthly ? monthly * 12 : '';
    });

    document.getElementById('career_edit_form_' + eid).addEventListener('submit', function() {
        var type = this.querySelector('[name="employment_type"]:checked').value;
        if (type === 'job') {
            document.getElementById('h_designation_' + eid).value = document.getElementById('vis_designation_' + eid).value;
            document.getElementById('h_company_' + eid).value     = document.getElementById('vis_company_' + eid).value;
            document.getElementById('h_nature_' + eid).value      = '';
        } else if (type === 'business') {
            document.getElementById('h_designation_' + eid).value = '';
            document.getElementById('h_company_' + eid).value     = document.getElementById('vis_company_biz_' + eid).value;
            document.getElementById('h_nature_' + eid).value      = document.getElementById('vis_nature_' + eid).value;
        } else if (type === 'self_employed') {
            document.getElementById('h_designation_' + eid).value = document.getElementById('vis_designation_self_' + eid).value;
            document.getElementById('h_company_' + eid).value     = document.getElementById('vis_company_self_' + eid).value;
            document.getElementById('h_nature_' + eid).value      = '';
        } else {
            document.getElementById('h_designation_' + eid).value = '';
            document.getElementById('h_company_' + eid).value     = '';
            document.getElementById('h_nature_' + eid).value      = '';
        }
    });
})();
</script>

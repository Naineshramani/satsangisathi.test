<form action="{{ route('career_store') }}#career" method="POST" id="career_create_form">
    @csrf

    {{-- Hidden real fields read by controller --}}
    <input type="hidden" name="designation"        id="h_designation_c">
    <input type="hidden" name="company"            id="h_company_c">
    <input type="hidden" name="nature_of_business" id="h_nature_c">

    <div class="modal-header">
        <h5 class="modal-title h6">{{translate('Add New Career Info')}}</h5>
        <button type="button" class="close" data-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="user_id" value="{{ $member_id }}">

        {{-- Employment Type --}}
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Type')}}</label>
            <div class="col-md-9 d-flex flex-wrap" style="gap:12px; padding-top:7px;">
                <label class="aiz-checkbox mb-0">
                    <input type="radio" name="employment_type" value="job" checked onchange="careerTypeChange(this,'_c')">
                    <span class="opacity-60 mr-1">{{translate('Job')}}</span>
                    <span class="aiz-rounded-check"></span>
                </label>
                <label class="aiz-checkbox mb-0">
                    <input type="radio" name="employment_type" value="business" onchange="careerTypeChange(this,'_c')">
                    <span class="opacity-60 mr-1">{{translate('Business')}}</span>
                    <span class="aiz-rounded-check"></span>
                </label>
                <label class="aiz-checkbox mb-0">
                    <input type="radio" name="employment_type" value="self_employed" onchange="careerTypeChange(this,'_c')">
                    <span class="opacity-60 mr-1">{{translate('Self-Employed')}}</span>
                    <span class="aiz-rounded-check"></span>
                </label>
                <label class="aiz-checkbox mb-0">
                    <input type="radio" name="employment_type" value="not_working" onchange="careerTypeChange(this,'_c')">
                    <span class="opacity-60 mr-1">{{translate('Not Working')}}</span>
                    <span class="aiz-rounded-check"></span>
                </label>
            </div>
        </div>

        {{-- Job fields --}}
        <div id="job_fields_c">
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Designation')}}</label>
                <div class="col-md-9">
                    <input type="text" id="vis_designation_c" class="form-control" placeholder="{{translate('e.g. Software Engineer, Manager')}}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Company Name')}}</label>
                <div class="col-md-9">
                    <input type="text" id="vis_company_c" class="form-control" placeholder="{{translate('Company name')}}">
                </div>
            </div>
        </div>

        {{-- Business fields --}}
        <div id="business_fields_c" style="display:none;">
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Nature of Business')}}</label>
                <div class="col-md-9">
                    <input type="text" id="vis_nature_c" class="form-control" placeholder="{{translate('e.g. Retail, Manufacturing, IT Services')}}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Business Name')}}</label>
                <div class="col-md-9">
                    <input type="text" id="vis_company_biz_c" class="form-control" placeholder="{{translate('Business / firm name')}}">
                </div>
            </div>
        </div>

        {{-- Self-Employed fields --}}
        <div id="self_fields_c" style="display:none;">
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Nature of Work')}}</label>
                <div class="col-md-9">
                    <input type="text" id="vis_designation_self_c" class="form-control" placeholder="{{translate('e.g. Freelance Developer, Consultant')}}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Trading / Firm Name')}}</label>
                <div class="col-md-9">
                    <input type="text" id="vis_company_self_c" class="form-control" placeholder="{{translate('Optional')}}">
                </div>
            </div>
        </div>

        {{-- Common income fields (hidden for Not Working) --}}
        @php $currencies = [['code'=>'INR','symbol'=>'₹','name'=>'Indian Rupee'],['code'=>'USD','symbol'=>'$','name'=>'US Dollar'],['code'=>'GBP','symbol'=>'£','name'=>'Pound Sterling'],['code'=>'EUR','symbol'=>'€','name'=>'Euro'],['code'=>'AED','symbol'=>'AED','name'=>'UAE Dirham'],['code'=>'CAD','symbol'=>'C$','name'=>'Canadian Dollar'],['code'=>'AUD','symbol'=>'A$','name'=>'Australian Dollar'],['code'=>'SGD','symbol'=>'S$','name'=>'Singapore Dollar'],['code'=>'QAR','symbol'=>'QAR','name'=>'Qatari Riyal'],['code'=>'SAR','symbol'=>'SAR','name'=>'Saudi Riyal'],['code'=>'MYR','symbol'=>'RM','name'=>'Malaysian Ringgit']]; @endphp
        <div id="income_fields_c">
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Currency')}}</label>
                <div class="col-md-9">
                    <select class="form-control aiz-selectpicker" name="currency" id="currency_create">
                        @foreach($currencies as $c)
                            <option value="{{ $c['code'] }}" data-symbol="{{ $c['symbol'] }}">{{ $c['code'] }} – {{ $c['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Monthly Income')}}</label>
                <div class="col-md-9">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text currency-symbol-create">₹</span></div>
                        <input type="number" name="career_start" class="form-control" placeholder="{{translate('Monthly Income')}}">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">{{translate('Yearly Income')}}</label>
                <div class="col-md-9">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text currency-symbol-create">₹</span></div>
                        <input type="number" name="career_end" class="form-control" placeholder="{{ translate('Yearly Income') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Close')}}</button>
        <button type="submit" class="btn btn-primary">{{translate('Add Career Info')}}</button>
    </div>
</form>

<script>
function careerTypeChange(el, suffix) {
    var s = suffix;
    var type = el.value;
    document.getElementById('job_fields' + s).style.display      = (type === 'job')           ? '' : 'none';
    document.getElementById('business_fields' + s).style.display = (type === 'business')      ? '' : 'none';
    document.getElementById('self_fields' + s).style.display     = (type === 'self_employed') ? '' : 'none';
    document.getElementById('income_fields' + s).style.display   = (type === 'not_working')   ? 'none' : '';
}

document.getElementById('career_create_form').addEventListener('submit', function() {
    var type = this.querySelector('[name="employment_type"]:checked').value;
    if (type === 'job') {
        document.getElementById('h_designation_c').value = document.getElementById('vis_designation_c').value;
        document.getElementById('h_company_c').value     = document.getElementById('vis_company_c').value;
        document.getElementById('h_nature_c').value      = '';
    } else if (type === 'business') {
        document.getElementById('h_designation_c').value = '';
        document.getElementById('h_company_c').value     = document.getElementById('vis_company_biz_c').value;
        document.getElementById('h_nature_c').value      = document.getElementById('vis_nature_c').value;
    } else if (type === 'self_employed') {
        document.getElementById('h_designation_c').value = document.getElementById('vis_designation_self_c').value;
        document.getElementById('h_company_c').value     = document.getElementById('vis_company_self_c').value;
        document.getElementById('h_nature_c').value      = '';
    } else {
        document.getElementById('h_designation_c').value = '';
        document.getElementById('h_company_c').value     = '';
        document.getElementById('h_nature_c').value      = '';
    }
});

$('#currency_create').on('changed.bs.select change', function() {
    var sym = $(this).find(':selected').data('symbol') || $(this).val();
    $('.currency-symbol-create').text(sym);
});

document.querySelector('#income_fields_c [name="career_start"]').addEventListener('input', function() {
    var monthly = parseFloat(this.value) || 0;
    document.querySelector('#income_fields_c [name="career_end"]').value = monthly ? monthly * 12 : '';
});
</script>

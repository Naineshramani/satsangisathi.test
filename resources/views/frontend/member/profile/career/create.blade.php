<form action="{{ route('career_store') }}#career" method="POST">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title h6">{{translate('Add New Career Info')}}</h5>
        <button type="button" class="close" data-dismiss="modal">
        </button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="user_id" value="{{ $member_id }}">
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Designation')}}</label>
            <div class="col-md-9">
                <input type="text" name="designation" class="form-control" placeholder="{{translate('designation')}}" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Company')}}</label>
            <div class="col-md-9">
                <input type="text" name="company"  placeholder="{{ translate('company') }}" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Currency')}}</label>
            <div class="col-md-9">
                <select class="form-control aiz-selectpicker" name="currency" id="currency_create">
                    @php $currencies = [['code'=>'INR','symbol'=>'₹','name'=>'Indian Rupee'],['code'=>'USD','symbol'=>'$','name'=>'US Dollar'],['code'=>'GBP','symbol'=>'£','name'=>'Pound Sterling'],['code'=>'EUR','symbol'=>'€','name'=>'Euro'],['code'=>'AED','symbol'=>'AED','name'=>'UAE Dirham'],['code'=>'CAD','symbol'=>'C$','name'=>'Canadian Dollar'],['code'=>'AUD','symbol'=>'A$','name'=>'Australian Dollar'],['code'=>'SGD','symbol'=>'S$','name'=>'Singapore Dollar'],['code'=>'QAR','symbol'=>'QAR','name'=>'Qatari Riyal'],['code'=>'SAR','symbol'=>'SAR','name'=>'Saudi Riyal'],['code'=>'MYR','symbol'=>'RM','name'=>'Malaysian Ringgit']]; @endphp
                    @foreach($currencies as $c)
                        <option value="{{ $c['code'] }}" data-symbol="{{ $c['symbol'] }}">{{ $c['code'] }} – {{ $c['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Monthly Earning')}}</label>
            <div class="col-md-9">
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text currency-symbol-create">₹</span></div>
                    <input type="number" name="career_start" class="form-control" placeholder="{{translate('Monthly Earning')}}">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Yearly Earning')}}</label>
            <div class="col-md-9">
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text currency-symbol-create">₹</span></div>
                    <input type="number" name="career_end" placeholder="{{ translate('Yearly Earning') }}" class="form-control">
                </div>
            </div>
        </div>
        <script>
            $('#currency_create').on('changed.bs.select change', function() {
                var sym = $(this).find(':selected').data('symbol') || $(this).val();
                $('.currency-symbol-create').text(sym);
            });
        </script>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Close')}}</button>
        <button type="submit" class="btn btn-primary">{{translate('Add New Career Info')}}</button>
    </div>
</form>

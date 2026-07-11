<div class="card" id="sec-lifestyle">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Lifestyle')}}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('lifestyles.update', $member->id) }}" method="POST">
            <input name="_method" type="hidden" value="PATCH">
            @csrf
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="diet">{{translate('Diet')}}</label>
                    @php $user_diet = !empty($member->lifestyles->diet) ? $member->lifestyles->diet : ""; @endphp
                    <select class="form-control aiz-selectpicker" name="diet" required>
                        <option value="">{{ translate('Select One') }}</option>
                        <option value="Swaminarayan Food (No Onion No Garlic)" @if($user_diet == 'Swaminarayan Food (No Onion No Garlic)') selected @endif>{{ translate('Swaminarayan Food (No Onion No Garlic)') }}</option>
                        <option value="Vegetarian" @if($user_diet == 'Vegetarian') selected @endif>{{ translate('Vegetarian') }}</option>
                        <option value="Eggetarian" @if($user_diet == 'Eggetarian') selected @endif>{{ translate('Eggetarian') }}</option>
                        <option value="Non Vegetarian" @if($user_diet == 'Non Vegetarian') selected @endif>{{ translate('Non Vegetarian') }}</option>
                        @error('diet')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="drink">{{translate('Drink')}}</label>
                    @php $user_drink = !empty($member->lifestyles->drink) ? $member->lifestyles->drink : ""; @endphp
                    <select class="form-control aiz-selectpicker" name="drink" required>
                        <option value="yes" @if($user_drink ==  'yes') selected @endif >{{translate('Yes')}}</option>
                        <option value="no" @if($user_drink ==  'no') selected @endif >{{translate('No')}}</option>
                        @error('drink')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="smoke">{{translate('Smoke')}}</label>
                    @php $user_smoke = !empty($member->lifestyles->smoke) ? $member->lifestyles->smoke : ""; @endphp
                    <select class="form-control aiz-selectpicker" name="smoke" required>
                        <option value="yes" @if($user_smoke ==  'yes') selected @endif >{{translate('Yes')}}</option>
                        <option value="no" @if($user_smoke ==  'no') selected @endif >{{translate('No')}}</option>
                        @error('smoke')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="living_with">{{translate('Living With')}}</label>
                    @php $user_living_with = $member->lifestyles->living_with ?? ''; @endphp
                    <select class="form-control aiz-selectpicker" name="living_with" required>
                        <option value="">{{ translate('Select One') }}</option>
                        <option value="Alone" @if($user_living_with == 'Alone') selected @endif>{{ translate('Alone') }}</option>
                        <option value="With Parents" @if($user_living_with == 'With Parents') selected @endif>{{ translate('With Parents') }}</option>
                        <option value="In Joint Family (With Siblings / Grand Parents)" @if($user_living_with == 'In Joint Family (With Siblings / Grand Parents)') selected @endif>{{ translate('In Joint Family (With Siblings / Grand Parents)') }}</option>
                    </select>
                    @error('living_with')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
            </div>
        </form>
    </div>
</div>

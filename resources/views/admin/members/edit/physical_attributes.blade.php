<div class="card-header bg-dark text-white">
    <h5 class="mb-0 h6">{{translate('Physical Attributes')}}</h5>
</div>
<div class="card-body">
    <form action="{{ route('physical-attribute.update', $member->id) }}#physical_attributes" method="POST">
        <input name="_method" type="hidden" value="PATCH">
        @csrf
        @php
            $heightParts = explode('.', $member->physical_attributes->height ?? '');
            $heightFeet = $heightParts[0] ?? '';
            $heightInches = $heightParts[1] ?? '';
        @endphp
        <div class="form-group row">
            <div class="col-md-6">
                <label for="height_feet">{{translate('Height')}}</label>
                <input type="hidden" name="height" id="height_combined" value="{{ $member->physical_attributes->height ?? '' }}">
                <div class="row">
                    <div class="col-6">
                        <select class="form-control" id="height_feet" required>
                            <option value="">{{ translate('Feet') }}</option>
                            @for ($ft = 3; $ft <= 8; $ft++)
                                <option value="{{ $ft }}" @if((string) $heightFeet === (string) $ft) selected @endif>{{ $ft }} {{ translate('ft') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-6">
                        <select class="form-control" id="height_inches" required>
                            <option value="">{{ translate('Inches') }}</option>
                            @for ($in = 0; $in <= 11; $in++)
                                <option value="{{ $in }}" @if((string) $heightInches === (string) $in) selected @endif>{{ $in }} {{ translate('in') }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                @error('height')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="weight">{{translate('Weight')}}</label>
                <input type="text" name="weight" value="{{ $member->physical_attributes->weight ?? "" }}" placeholder="{{ translate('Weight') }}" step="any" class="form-control" required>
                @error('weight')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var heightFeet = document.getElementById('height_feet');
                var heightInches = document.getElementById('height_inches');
                var heightCombined = document.getElementById('height_combined');

                function updateHeightCombined() {
                    heightCombined.value = (heightFeet.value !== '' && heightInches.value !== '')
                        ? heightFeet.value + '.' + heightInches.value
                        : '';
                }

                heightFeet.addEventListener('change', updateHeightCombined);
                heightInches.addEventListener('change', updateHeightCombined);
            });
        </script>

        <div class="form-group row">
            <div class="col-md-6">
                <label for="eye_color">{{translate('Eye color')}}</label>
                <input type="text" name="eye_color" value="{{ $member->physical_attributes->eye_color ?? "" }}" class="form-control" placeholder="{{translate('Eye Color')}}" required>
                @error('eye_color')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="hair_color">{{translate('Hair Color')}}</label>
                <input type="text" name="hair_color" value="{{ $member->physical_attributes->hair_color ?? "" }}" placeholder="{{ translate('Hair Color') }}" class="form-control" required>
                @error('hair_color')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6">
                <label for="complexion">{{translate('Complexion')}}</label>
                <input type="text" name="complexion" value="{{ $member->physical_attributes->complexion ?? "" }}" class="form-control" placeholder="{{translate('Complexion')}}" required>
                @error('complexion')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="blood_group">{{translate('Blood Group')}}</label>
                <input type="text" name="blood_group" value="{{ $member->physical_attributes->blood_group ?? "" }}" placeholder="{{ translate('Blood Group') }}" class="form-control" required>
                @error('blood_group')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6">
                <label for="body_type">{{translate('Body Type')}}</label>
                <input type="text" name="body_type" value="{{ $member->physical_attributes->body_type ?? "" }}" class="form-control" placeholder="{{translate('Body Type')}}" required>
                @error('body_type')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="body_art">{{translate('Body Art')}}</label>
                <input type="text" name="body_art" value="{{ $member->physical_attributes->body_art ?? "" }}" placeholder="{{ translate('Body Art') }}" class="form-control" required>
                @error('body_art')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label for="disability">{{translate('Disability')}}</label>
                <input type="text" name="disability" value="{{ $member->physical_attributes->disability ?? "" }}" class="form-control" placeholder="{{translate('Disability')}}">
                @error('disability')
                    <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-primary btn-sm">{{translate('Update')}}</button>
        </div>
    </form>
</div>

<form action="{{ route('education_update', $education->id) }}#education" method="POST">

    @csrf
    <div class="modal-header">
        <h5 class="modal-title h6">{{translate('Edit Education Info')}}</h5>
        <button type="button" class="close" data-dismiss="modal">
        </button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Degree')}}</label>
            <div class="col-md-9">
                <select name="degree" class="form-control aiz-selectpicker" data-live-search="true" required>
                    <option value="">{{translate('Select Degree')}}</option>
                    @foreach(\App\Models\DegreeLevel::orderBy('sort_order')->orderBy('name')->get() as $dl)
                        <option value="{{ $dl->name }}" @if($education->degree === $dl->name) selected @endif>{{ $dl->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Specialization')}}</label>
            <div class="col-md-9">
                <input type="text" name="specialization" value="{{ $education->specialization }}" class="form-control" placeholder="{{ translate('e.g. Computer Science, Finance, Cardiology') }}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Institution')}}</label>
            <div class="col-md-9">
                <input type="text" name="institution" value="{{$education->institution}}"  placeholder="{{ translate('Institution') }}" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Start')}}</label>
            <div class="col-md-9">
                <input type="number" name="education_start" value="{{$education->start}}" class="form-control" placeholder="{{translate('Start')}}">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('End')}}</label>
            <div class="col-md-9">
                <input type="number" name="education_end" value="{{$education->end}}"  placeholder="{{ translate('End') }}" class="form-control" >
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Close')}}</button>
        <button type="submit" class="btn btn-primary">{{translate('Update Education Info')}}</button>
    </div>
</form>

<form action="{{ route('degree-levels.update', $degree_level->id) }}" method="POST">
    <input name="_method" type="hidden" value="PATCH">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title h6">{{translate('Edit Degree')}}</h5>
        <button type="button" class="close" data-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Degree Name')}}</label>
            <div class="col-md-9">
                <input type="text" name="name" value="{{ $degree_level->name }}" class="form-control" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">{{translate('Sort Order')}}</label>
            <div class="col-md-9">
                <input type="number" name="sort_order" value="{{ $degree_level->sort_order }}" class="form-control">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">{{translate('Close')}}</button>
        <button type="submit" class="btn btn-sm btn-primary">{{translate('Update')}}</button>
    </div>
</form>

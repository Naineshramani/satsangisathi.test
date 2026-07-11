@extends('admin.layouts.app')
@section('content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('Education Degrees')}}</h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('All Degree Levels') }}</h5>
                </div>
                <div class="col-md-4">
                    <form id="sort_degree_levels" action="" method="GET">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{translate('Degree Name')}}</th>
                            <th>{{translate('Order')}}</th>
                            <th class="text-right" width="20%">{{translate('Options')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($degree_levels as $key => $degree)
                            <tr>
                                <td>{{ ($key+1) + ($degree_levels->currentPage() - 1)*$degree_levels->perPage() }}</td>
                                <td>{{ $degree->name }}</td>
                                <td>{{ $degree->sort_order }}</td>
                                <td class="text-right">
                                    <a href="javascript:void(0);" onclick="degree_level_modal('{{ route('degree-levels.edit', encrypt($degree->id)) }}')" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{ translate('Edit') }}">
                                        <i class="las la-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" data-href="{{ route('degree-levels.destroy', $degree->id) }}" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" title="{{ translate('Delete') }}">
                                        <i class="las la-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $degree_levels->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Add New Degree')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('degree-levels.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label>{{translate('Degree Name')}}</label>
                        <input type="text" name="name" class="form-control" placeholder="{{ translate('e.g. B.Tech, MBA, Ph.D.') }}" required>
                        @error('name')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label>{{translate('Sort Order')}}</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                    <div class="form-group mb-3 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
    @include('modals.create_edit_modal')
    @include('modals.delete_modal')
@endsection
@section('script')
<script>
    function degree_level_modal(url) {
        $.get(url, function(data) {
            $('.create_edit_modal_content').html(data);
            $('.create_edit_modal').modal('show');
        });
    }
</script>
@endsection

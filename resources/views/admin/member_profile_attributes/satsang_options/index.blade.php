@extends('admin.layouts.app')
@section('content')
<div class="aiz-titlebar mt-2 mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Satsang Options') }}</h1>
        </div>
    </div>
</div>

{{-- Category Tabs --}}
<ul class="nav nav-tabs mb-4">
    @foreach($categories as $key => $label)
    <li class="nav-item">
        <a class="nav-link {{ $category == $key ? 'active' : '' }}"
           href="{{ route('satsang-options.index', ['category' => $key]) }}">
            {{ translate($label) }}
        </a>
    </li>
    @endforeach
</ul>

<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left">
                    <h5 class="mb-md-0 h6">{{ translate('All Options') }} — {{ translate($categories[$category]) }}</h5>
                </div>
                <div class="col-md-4">
                    <form id="sort_options" action="" method="GET">
                        <input type="hidden" name="category" value="{{ $category }}">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" name="search"
                                   value="{{ $sort_search }}" placeholder="{{ translate('Type name & Enter') }}">
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Name') }}</th>
                            <th class="text-right" width="20%">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($options as $key => $option)
                        <tr>
                            <td>{{ ($key + 1) + ($options->currentPage() - 1) * $options->perPage() }}</td>
                            <td>{{ $option->name }}</td>
                            <td class="text-right">
                                <a href="javascript:void(0);"
                                   onclick="satsang_option_modal('{{ route('satsang-options.edit', encrypt($option->id)) }}')"
                                   class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="javascript:void(0);"
                                   data-href="{{ route('satsang-options.destroy', $option->id) }}"
                                   class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                   title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $options->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Add New Option') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('satsang-options.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="category" value="{{ $category }}">
                    <div class="form-group mb-3">
                        <label>{{ translate('Name') }}</label>
                        <input type="text" name="name" placeholder="{{ translate('Option Name') }}"
                               class="form-control" required>
                        @error('name')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group mb-3 text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Save Option') }}</button>
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
    function satsang_option_modal(url) {
        $.get(url, function(data) {
            $('.create_edit_modal_content').html(data);
            $('.create_edit_modal').modal('show');
        });
    }
</script>
@endsection

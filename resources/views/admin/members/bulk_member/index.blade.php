@extends('admin.layouts.app')
@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Member Bulk Upload')}}</h5>
        </div>
        <div class="card-body">
            <div class="alert" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                <strong>{{ translate('Step 1')}}:</strong>
                <p>{{translate('1. Download the skeleton file below -- one row per member, covering Basic Info, Address, Education, Career, Language, Caste, Satsang Details, Lifestyle, Astronomic Info, and Family Info.')}}</p>
                <p>{{translate('2. Download the reference list below to see the exact spelling expected for Marital Status, Mother Tongue/Known Languages, Caste, Degree, and every Satsang Details field.')}}</p>
                <p>{{translate('3. On Behalf and Package are matched by name too -- use the dedicated PDFs below for those. Present/Permanent Address Country, State, and City are matched directly against the system\'s location list, no reference sheet needed (leave Country blank to default to India).')}}</p>
                <p>{{translate('4. Once filled in, upload the sheet below. Column order does not matter -- only the column headers.')}}</p>
            </div>
            <br>
            <div class="">
                <a href="{{ static_asset('download/member_bulk_demo.xlsx') }}" download><button class="btn btn-primary">{{ translate('Download Skeleton File')}}</button></a>
                <a href="{{ route('pdf.reference_lists') }}"><button class="btn btn-primary">{{translate('Download Reference Lists')}}</button></a>
                <a href="{{ route('pdf.on_behalf') }}"><button class="btn btn-primary">{{translate('Download On Behalf')}}</button></a>
                <a href="{{ route('pdf.package') }}"><button class="btn btn-primary">{{translate('Download Package')}}</button></a>
            </div>
            <br>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6"><strong>{{translate('Upload Member File')}}</strong></h5>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('bulk_member_upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-sm-9">
                        <div class="custom-file">
    						<label class="custom-file-label">
    							<input type="file" name="bulk_file" class="custom-file-input" required>
    							<span class="custom-file-name">{{ translate('Choose File')}}</span>
    						</label>
    					</div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">{{translate('Upload File')}}</button>
                </div>
            </form>

            @if (session('bulk_import_failure_batch'))
                <div class="mt-3">
                    <a href="{{ route('bulk_member.download_failures', session('bulk_import_failure_batch')) }}">
                        <button class="btn btn-danger">{{ translate('Download Error Report') }}</button>
                    </a>
                </div>
            @endif
        </div>
    </div>

@endsection

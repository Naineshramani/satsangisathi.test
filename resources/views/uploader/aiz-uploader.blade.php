<div class="modal fade" id="aizUploaderModal" data-backdrop="static" role="dialog" aria-hidden="true" >
	<div class="modal-dialog modal-adaptive" role="document">
		<div class="modal-content h-100">
			<div class="modal-header pb-2 bg-light">
				<h6 class="mb-0 font-weight-medium">{{ translate('Select or Upload File') }}</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"></span>
				</button>
			</div>
			<div class="modal-body d-flex flex-column p-0">

				{{-- Hidden file input —  triggered by the upload tile in the grid --}}
				<input type="file" id="aiz-file-input" multiple style="display:none">

				{{-- Filter row --}}
				<div class="aiz-uploader-filter px-3 pt-2 pb-2 border-bottom">
					<div class="row align-items-center gutters-5 gutters-md-10 position-relative">
						<div class="col-xl-2 col-md-3 col-5">
							<select class="form-control form-control-xs aiz-selectpicker" name="aiz-uploader-sort">
								<option value="newest" selected>{{ translate('Sort by newest') }}</option>
								<option value="oldest">{{ translate('Sort by oldest') }}</option>
								<option value="smallest">{{ translate('Sort by smallest') }}</option>
								<option value="largest">{{ translate('Sort by largest') }}</option>
							</select>
						</div>
						<div class="col-md-3 col-5">
							<div class="custom-control custom-radio">
								<input type="checkbox" class="custom-control-input" name="aiz-show-selected" id="aiz-show-selected">
								<label class="custom-control-label" for="aiz-show-selected">
									{{ translate('Selected Only') }}
								</label>
							</div>
						</div>
						<div class="col-md-4 col-xl-3 ml-auto mr-0 col-2 position-static">
							<div class="aiz-uploader-search text-right">
								<input type="text" class="form-control form-control-xs" name="aiz-uploader-search" placeholder="Search your files">
								<i class="search-icon d-md-none"><span></span></i>
							</div>
						</div>
					</div>
				</div>

				{{-- File grid (upload tile injected here by JS as first item) --}}
				<div class="aiz-uploader-all clearfix c-scrollbar-light flex-grow-1">
					<div class="align-items-center d-flex h-100 justify-content-center w-100">
						<div class="text-center">
							<h3>{{ translate('No files found') }}</h3>
						</div>
					</div>
				</div>

			</div>
			<div class="modal-footer justify-content-between bg-light">
				<div class="flex-grow-1 overflow-hidden d-flex">
					<div class="">
						<div class="aiz-uploader-selected">{{ translate('0 File selected') }}</div>
						<button type="button" class="btn-link btn btn-sm p-0 aiz-uploader-selected-clear">{{ translate('Clear') }}</button>
					</div>
					<div class="mb-0 ml-3">
						<button type="button" class="btn btn-sm btn-primary" id="uploader_prev_btn">{{ translate('Prev') }}</button>
						<button type="button" class="btn btn-sm btn-primary" id="uploader_next_btn">{{ translate('Next') }}</button>
					</div>
				</div>
				<button type="button" class="btn btn-sm btn-primary" data-toggle="aizUploaderAddSelected">{{ translate('Add Files') }}</button>
			</div>
		</div>
	</div>
</div>

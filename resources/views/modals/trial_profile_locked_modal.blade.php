<div class="modal fade trial_profile_locked_modal" id="trial-profile-locked-modal">
    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom">
        <div class="modal-content trial_profile_locked_modal_content">
            <div class="modal-body text-center">
                <h4 class="modal-title h6 mb-3">{{translate('Profile Locked')}}</h4>
                <p class="fs-14">{{translate("You can view this member's full profile once they have expressed interest in you. Activate a package to view all profiles right away.")}}</p>
                <hr>
                <a href="{{ route('interest_requests') }}" class="btn btn-primary mt-2">{{translate('See Who Sent Me Interest')}}</a>
                <a href="{{ route('packages') }}" class="btn btn-outline-primary mt-2">{{translate('Purchase Package')}}</a>
                <button type="button" class="btn btn-danger mt-2" data-dismiss="modal">{{translate('Close')}}</button>
            </div>
        </div>
    </div>
</div>

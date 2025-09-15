<div class="alert alert-important alert-danger alert-dismissible" role="alert">
    <div class="d-flex">
        <div>
            {{ __('Your email verification is not completed. Please check your email and activate your account. If you did not receive an email, please') }}
            <a class="text-white"
                href="{{ route('business-admin.resend.email.verification') }}"><u>{{ __('click here.') }}</u></a>
        </div>
    </div>
    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
</div>

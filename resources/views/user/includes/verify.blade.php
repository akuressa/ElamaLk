<div class="bg-red-500 text-white p-2 rounded-lg relative" role="alert">
    <div class="flex items-center">
        <div>
            {{ __('Your email verification is not completed. Please check your email and activate your account. If you did not receive an email, please') }}
            <a class="underline text-white hover:text-gray-200"
                href="{{ route('user.resend.email.verification') }}">{{ __('click here.') }}</a>
        </div>
    </div>
    <button class="absolute top-2 right-2 text-white hover:text-gray-200 h-5 w-5" aria-label="close"
        onclick="this.parentElement.remove()">
        &times;
    </button>
</div>

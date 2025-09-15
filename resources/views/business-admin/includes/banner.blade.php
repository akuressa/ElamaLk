@php
    use App\Models\Business;
    $business_id = request()->route()->parameter('business_id');
    $business_name = Business::where('business_id', $business_id)->first()->business_name;

@endphp
<header class="navbar navbar-expand-md navbar-light bg-dark d-print-none">
    <div class="container-xl d-flex align-items-center justify-content-between">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand d-none-navbar-horizontal pe-0 pe-md-3 text-white m-0">
            {{ __('You are viewing') }} {{ __($business_name) }} {{ __('Business Dashboard') }}
        </h1>
        <div class="navbar-nav flex-row order-md-last d-flex align-items-center">
            <!-- Exit button -->
            <div class="col-auto ms-auto d-print-none">
                <a type="button" href="{{ route('business.businesses.index') }}"
                    class="badge h-5 px-4 bg-danger d-flex align-items-center fw-bold" style="font-size: 1.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-logout">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                        <path d="M9 12h12l-3 -3" />
                        <path d="M18 15l3 -3" />
                    </svg>
                    {{ __('Exit') }}
                </a>
            </div>
        </div>
    </div>
</header>

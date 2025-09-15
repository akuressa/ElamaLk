@php
    use App\Models\Business;
    $user = Auth::user();
    $business_id = request()->route()->parameter('business_id');
    $business_details = Business::where('business_id', $business_id)->first();
@endphp

<header class="navbar navbar-expand-md navbar-light d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="{{ route('business-admin.dashboard.index', ['business_id' => $business_id]) }}">
                <img src="{{ asset($business_details->business_logo_url) }}" width="200" height="50"
                    alt="{{ $business_details->business_name }}" class="navbar-brand-image custom-logo">
            </a>
        </h1>

        <div class="navbar-nav flex-row order-md-last">
            {{-- Languages --}}
            @if (count(config('app.languages')) > 1)
                <div class="nav-item dropdown mx-2">
                    <div class="lang">
                        <select type="text" class="form-select" placeholder="{{ __('Select a language') }}"
                            id="chooseLang" value="">
                            @foreach (config('app.languages') as $langLocale => $langName)
                                <option value="{{ $langLocale }}"
                                    {{ app()->getLocale() == $langLocale ? 'selected' : '' }}>
                                    <strong>{{ $langName }}</strong>
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            {{-- Profile --}}
            @if ($user->role == 3)
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                        aria-label="Open user menu">
                        <span class="avatar avatar-md">
                            <img src="{{ asset($user->profile_image == null ? asset($setting->favicon) : asset($user->profile_image)) }}" alt="{{ $user->name }}">
                        </span>
                        <div class="d-none d-xl-block ps-2">
                            <div>{{ $user->name }}</div>
                            <div class="mt-1 small text-muted">{{ __('Business Admin') }}</div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <a href="{{ route('business-admin.index.account', ['business_id' => $business_id]) }}"
                            class="dropdown-item">{{ __('Profile & account') }}</a>
                        {{-- Light / Dark Mode --}}
                        <a href="{{ route('business-admin.change.theme', 'dark') }}"
                            class="dropdown-item hide-theme-dark" data-bs-placement="bottom">
                            {{ __('Enable dark mode') }}
                        </a>
                        <a href="{{ route('business-admin.change.theme', 'light') }}"
                            class="dropdown-item hide-theme-light" data-bs-placement="bottom">
                            {{ __('Enable light mode') }}
                        </a>
                        <a href="{{ route('logout') }}" class="dropdown-item"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                        <form class="logout" id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</header>

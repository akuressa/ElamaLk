@php
    // Settings
    use App\Models\Setting;
    $setting = Setting::where('status', 1)->first();
@endphp

<header class="navbar navbar-expand-md navbar-light d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="{{ route('admin.dashboard.index') }}">
                <img src="{{ asset($setting->site_logo) }}" width="200" height="50" alt="{{ config('app.name') }}"
                    class="navbar-brand-image custom-logo">
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
                                    <strong>{{ $langName }}</strong></option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            {{-- Profile --}}
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    <span class="avatar">
                        <img src="{{ Auth::user()->profile_image == null ? asset('images/profile.png') : asset(Auth::user()->profile_image) }}"
                            alt="{{ Auth::user()->name }}">
                    </span>
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ __(Auth::user()->name) }}</div>
                        <div class="mt-1 small text-muted">{{ __('Administrator') }}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="{{ route('admin.index.account') }}"
                        class="dropdown-item">{{ __('Profile & Account') }}</a>
                    {{-- Light / Dark Mode --}}
                    <a href="{{ route('admin.change.theme', 'dark') }}" class="dropdown-item hide-theme-dark"
                        data-bs-placement="bottom">
                        {{ __('Enable dark mode') }}
                    </a>
                    <a href="{{ route('admin.change.theme', 'light') }}" class="dropdown-item hide-theme-light"
                        data-bs-placement="bottom">
                        {{ __('Enable light mode') }}
                    </a>
                    <a href="{{ route('logout') }}" class="dropdown-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                    <form class="logout" id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>

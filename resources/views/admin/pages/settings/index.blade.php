@extends('admin.layouts.app')

{{-- Custom CSS --}}
@section('custom-css')
    {{-- Tiny MCE --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.0.1/tinymce.min.js"
        integrity="sha512-KGtsnWohFUg0oksKq7p7eDgA1Rw2nBfqhGJn463/rGhtUY825dBqGexj8eP04LwfnsSW6dNAHAlOqKJKquHsnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="container-xl">
            <!-- Page title -->
            <div class="page-header d-print-none">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="page-pretitle">
                            {{ __('Overview') }}
                        </div>
                        <h2 class="page-title mb-2">
                            {{ __('Settings') }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                {{-- Failed --}}
                @if (Session::has('failed'))
                    <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('failed') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                {{-- Success --}}
                @if (Session::has('success'))
                    <div class="alert alert-important alert-success alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('success') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                {{-- Settings --}}
                <div class="card">
                    <div class="card-body">
                        <div class="accordion" id="accordion-example">
                            {{-- General Configuration Settings --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-1">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-1" aria-expanded="false">
                                        <h2>{{ __('General Configuration Settings') }}</h2>
                                    </button>
                                </h2>
                                <div id="collapse-1" class="accordion-collapse collapse"
                                    data-bs-parent="#accordion-example">
                                    <div class="accordion-body pt-0">
                                        <form action="{{ route('admin.change.general.settings') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                {{-- Default Business Country --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="show_website">{{ __('Default Country') }}</label>
                                                        <select name="default_country" id="default_country"
                                                            class="form-control form-select" required>
                                                            <option value="" disabled selected>--
                                                                {{ __('Select Country') }} --</option>
                                                            @foreach ($countries as $country)
                                                                <option value="{{ $country->id }}"
                                                                    {{ $config[34]->config_value == $country->id ? 'selected' : '' }}>
                                                                    {{ $country->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Timezone --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="timezone">{{ __('Timezone') }}</label>
                                                        <select name="timezone" id="timezone"
                                                            class="form-control form-select" required>
                                                            @foreach (timezone_identifiers_list() as $timezone)
                                                                <option value="{{ $timezone }}"
                                                                    {{ $config[2]->config_value == $timezone ? ' selected' : '' }}>
                                                                    {{ __($timezone) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Currency --}}
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="currency">{{ __('Currency') }}</label>
                                                        <select class="tomselected form-select" name="currency"
                                                            id="currency" required>
                                                            @foreach ($currencies as $currency)
                                                                <option value="{{ $currency->iso_code }}"
                                                                    {{ $config[1]->config_value == $currency->iso_code ? ' selected' : '' }}>
                                                                    {{ $currency->name }} ({{ $currency->symbol }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Default Plan Term Settings --}}
                                            <div class="row">
                                                <div class="col-md-4 col-xl-4">
                                                    <h2 class="page-title my-3">
                                                        {{ __('Default Plan Term Settings') }}
                                                    </h2>
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="term">{{ __('Default Plan Term') }}</label>
                                                        <select name="term" id="term"
                                                            class="form-control form-select" required>
                                                            <option value="monthly"
                                                                {{ $config[8]->config_value == 'monthly' ? 'selected' : '' }}>
                                                                {{ __('Monthly') }}</option>
                                                            <option value="yearly"
                                                                {{ $config[8]->config_value == 'yearly' ? 'selected' : '' }}>
                                                                {{ __('Yearly') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Cookie Consent Settings --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <h2 class="page-title my-3">
                                                        {{ __('Cookie Consent Settings') }}
                                                    </h2>
                                                    <div class="mb-3">
                                                        <label class="form-label required"
                                                            for="cookie">{{ __('Cookie Consent Settings') }}</label>
                                                        <select name="cookie" id="cookie"
                                                            class="form-control form-select" required>
                                                            <option value="true"
                                                                {{ env('COOKIE_CONSENT_ENABLED') == 'true' ? ' selected' : '' }}>
                                                                {{ __('Enable') }}</option>
                                                            <option value="false"
                                                                {{ env('COOKIE_CONSENT_ENABLED') == '' ? ' selected' : '' }}>
                                                                {{ __('Disable') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Default Image Upload Limit --}}
                                                <div class="col-md-4 col-xl-4 mb-2">
                                                    <h2 class="page-title my-3">
                                                        {{ __('Default Image Upload Limit') }}
                                                    </h2>
                                                    <div class="mb-3">
                                                        <label class="form-label" for="image_limit">{{ __('Size') }}
                                                        </label>
                                                        <input type="number" class="form-control" name="image_limit"
                                                            value="{{ $settings->image_limit['SIZE_LIMIT'] }}"
                                                            placeholder="{{ __('Size') }}" min="1024" />
                                                    </div>
                                                </div>

                                                {{-- WhatsApp Chat Settings --}}
                                                <div class="row">
                                                    <h2 class="page-title my-3">
                                                        {{ __('WhatsApp Chat Button Settings') }}
                                                    </h2>
                                                    <div class="col-xl-4 col-12">
                                                        <div class="mb-3">
                                                            <label class="form-label required"
                                                                for="show_whatsapp_chatbot">{{ __('Want to display whatsapp chat button on website?') }}</label>
                                                            <select name="show_whatsapp_chatbot"
                                                                id="show_whatsapp_chatbot" class="form-control" required>
                                                                <option value="1"
                                                                    {{ $config[49]->config_value == '1' ? ' selected' : '' }}>
                                                                    {{ __('Yes') }}</option>
                                                                <option value="0"
                                                                    {{ $config[49]->config_value == '0' ? ' selected' : '' }}>
                                                                    {{ __('No') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- WhatsApp Number --}}
                                                    <div class="col-xl-4 col-12">
                                                        <div class="mb-3">
                                                            <label
                                                                class="form-label required">{{ __('WhatsApp Number') }}</label>
                                                            <input type="tel" class="form-control"
                                                                name="whatsapp_chatbot_mobile_number"
                                                                value="{{ $config[50]->config_value }}"
                                                                placeholder="{{ __('WhatsApp Number') }}"
                                                                oninput="javascript: if (this.value.length > 20) this.value = this.value.slice(0, 20); this.value = this.value.replace(/[^0-9]/g, '');" />
                                                            <small>{{ __('Note : With Country code (without +)') }}</small>
                                                        </div>
                                                    </div>

                                                    {{-- Initial Chat Message --}}
                                                    <div class="col-xl-4 col-12">
                                                        <div class="mb-3">
                                                            <label
                                                                class="form-label required">{{ __('Initial Chat Message') }}</label>
                                                            <textarea class="form-control" name="whatsapp_chatbot_message" id="whatsapp_chatbot_message" cols="30"
                                                                rows="2" placeholder="{{ __('Initial Chat Message') }}" required>{{ $config[51]->config_value }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Tawk Chat Settings --}}
                                                <div class="col-md-6 col-xl-6">
                                                    <h2 class="page-title my-3">
                                                        {{ __('Tawk Chat Settings') }}
                                                    </h2>
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('Tawk Chat URL (s1.src)') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">
                                                                {{ __('https://embed.tawk.to/') }}
                                                            </span>
                                                            <input type="text" class="form-control"
                                                                name="tawk_chat_bot_key"
                                                                value="{{ $settings->tawk_chat_key }}" maxlength="120"
                                                                placeholder="{{ __('Tawk Chat Key') }}"
                                                                autocomplete="off" />
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Update button --}}
                                                <div class="text-end">
                                                    <div class="d-flex">
                                                        <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                            {{ __('Update') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Website Configuration Settings --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-2" aria-expanded="false">
                                        <h2>{{ __('Website Configuration Settings') }}</h2>
                                    </button>
                                </h2>
                                <div id="collapse-2" class="accordion-collapse collapse"
                                    data-bs-parent="#accordion-example">
                                    <div class="accordion-body pt-0">
                                        <form action="{{ route('admin.change.website.settings') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                {{-- Theme Colors --}}
                                                <div class="col-md-12 col-xl-12">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label required">{{ __('Theme Colors') }}</label>
                                                        <div class="row g-2">
                                                            <div class="col-auto">
                                                                <label class="form-colorinput">
                                                                    <input name="app_theme" type="radio" value="blue"
                                                                        class="form-colorinput-input"
                                                                        {{ $config[11]->config_value == 'blue' ? 'checked' : '' }} />
                                                                    <span class="form-colorinput-color bg-blue"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-auto">
                                                                <label class="form-colorinput form-colorinput-light">
                                                                    <input name="app_theme" type="radio" value="indigo"
                                                                        class="form-colorinput-input"
                                                                        {{ $config[11]->config_value == 'indigo' ? 'checked' : '' }} />
                                                                    <span class="form-colorinput-color bg-indigo"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-auto">
                                                                <label class="form-colorinput">
                                                                    <input name="app_theme" type="radio" value="green"
                                                                        class="form-colorinput-input"
                                                                        {{ $config[11]->config_value == 'green' ? 'checked' : '' }} />
                                                                    <span class="form-colorinput-color bg-green"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-auto">
                                                                <label class="form-colorinput">
                                                                    <input name="app_theme" type="radio" value="yellow"
                                                                        class="form-colorinput-input"
                                                                        {{ $config[11]->config_value == 'yellow' ? 'checked' : '' }} />
                                                                    <span class="form-colorinput-color bg-yellow"></span>
                                                                </label>
                                                            </div>

                                                            <div class="col-auto">
                                                                <label class="form-colorinput">
                                                                    <input name="app_theme" type="radio" value="red"
                                                                        class="form-colorinput-input"
                                                                        {{ $config[11]->config_value == 'red' ? 'checked' : '' }} />
                                                                    <span class="form-colorinput-color bg-red"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-auto">
                                                                <label class="form-colorinput">
                                                                    <input name="app_theme" type="radio" value="purple"
                                                                        class="form-colorinput-input"
                                                                        {{ $config[11]->config_value == 'purple' ? 'checked' : '' }} />
                                                                    <span class="form-colorinput-color bg-purple"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-auto">
                                                                <label class="form-colorinput">
                                                                    <input name="app_theme" type="radio" value="pink"
                                                                        class="form-colorinput-input"
                                                                        {{ $config[11]->config_value == 'pink' ? 'checked' : '' }} />
                                                                    <span class="form-colorinput-color bg-pink"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-auto">
                                                                <label class="form-colorinput form-colorinput-light">
                                                                    <input name="app_theme" type="radio" value="gray"
                                                                        class="form-colorinput-input"
                                                                        {{ $config[11]->config_value == 'gray' ? 'checked' : '' }} />
                                                                    <span class="form-colorinput-color bg-muted"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Site Name --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label required">{{ __('Site Name') }}</label>
                                                        <input type="text" class="form-control" name="site_name"
                                                            value="{{ $settings->site_name }}" maxlength="120"
                                                            placeholder="{{ __('Site Name') }}" required />
                                                    </div>
                                                </div>

                                                {{-- Website Logo --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Website Logo') }} <span
                                                                class="text-muted">
                                                                ({{ __('Recommended size : 200 x 90') }})</span></div>
                                                        <input type="file" class="form-control" name="site_logo"
                                                            placeholder="{{ __('Website Logo') }}"
                                                            accept=".png,.jpg,.jpeg,.gif,.svg" />
                                                    </div>
                                                </div>

                                                {{-- Favicon --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Favicon') }} <span
                                                                class="text-muted">
                                                                ({{ __('Recommended size : 200 x 200') }})</span></div>
                                                        <input type="file" class="form-control" name="favi_icon"
                                                            placeholder="{{ __('Favicon') }}"
                                                            accept=".png,.jpg,.jpeg,.gif,.svg" />
                                                    </div>
                                                </div>

                                                {{-- Update button --}}
                                                <div class="text-end">
                                                    <div class="d-flex">
                                                        <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                            {{ __('Update') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Payment Method Configuration Settings --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-3" aria-expanded="false">
                                        <h2>{{ __('Payment Methods Configuration Settings') }}</h2>
                                    </button>
                                </h2>
                                <div id="collapse-3" class="accordion-collapse collapse"
                                    data-bs-parent="#accordion-example">
                                    <div class="accordion-body pt-0">
                                        <form action="{{ route('admin.change.payments.settings') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">

                                                {{-- Paypal Settings --}}
                                                <h2 class="page-title my-3">
                                                    {{ __('Paypal Settings') }}
                                                </h2>
                                                {{-- Mode --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label required">{{ __('Mode') }}</label>
                                                        <select type="text" class="form-select"
                                                            placeholder="Select a payment mode" id="paypal_mode"
                                                            name="paypal_mode" required>
                                                            <option value="sandbox"
                                                                {{ $config[3]->config_value == 'sandbox' ? 'selected' : '' }}>
                                                                {{ __('Sandbox') }}</option>
                                                            <option value="live"
                                                                {{ $config[3]->config_value == 'live' ? 'selected' : '' }}>
                                                                {{ __('Live') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Client Key --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label required">{{ __('Client Key') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="paypal_client_key"
                                                            value="{{ $config[4]->config_value }}" maxlength="120"
                                                            placeholder="{{ __('Client Key') }}" required />
                                                    </div>
                                                </div>

                                                {{-- Secret --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" required>{{ __('Secret') }}</label>
                                                        <input type="text" class="form-control" name="paypal_secret"
                                                            value="{{ $config[5]->config_value }}" maxlength="120"
                                                            placeholder="{{ __('Secret') }}" required />
                                                    </div>
                                                </div>

                                                {{-- Razorpay Settings --}}
                                                <h2 class="page-title my-3">
                                                    {{ __('Razorpay Settings') }}
                                                </h2>
                                                {{-- Client Key --}}
                                                <div class="col-md-6 col-xl-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required">{{ __('Client Key') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="razorpay_client_key"
                                                            value="{{ $config[6]->config_value }}" maxlength="120"
                                                            placeholder="{{ __('Client Key') }}" required />
                                                    </div>
                                                </div>

                                                {{-- Secret --}}
                                                <div class="col-md-6 col-xl-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required">{{ __('Secret') }}</label>
                                                        <input type="text" class="form-control" name="razorpay_secret"
                                                            value="{{ $config[7]->config_value }}" maxlength="120"
                                                            placeholder="{{ __('Secret') }}" required />
                                                    </div>
                                                </div>

                                                {{-- PhonePe Settings --}}
                                                <h2 class="page-title my-3">
                                                    {{ __('PhonePe Settings') }}
                                                </h2>
                                                {{-- Client Key --}}
                                                <div class="col-xl-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required">{{ __('Merchant ID') }}</label>
                                                        <input type="text" class="form-control" name="merchantId"
                                                            value="{{ $config[53]->config_value }}"
                                                            placeholder="{{ __('Merchant ID') }}" required />
                                                    </div>
                                                </div>

                                                {{-- Salt Key --}}
                                                <div class="col-xl-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required">{{ __('Salt Key') }}</label>
                                                        <input type="text" class="form-control" name="saltKey"
                                                            value="{{ $config[54]->config_value }}"
                                                            placeholder="{{ __('Salt Key') }}" required />
                                                    </div>
                                                </div>

                                                {{-- Stripe Settings --}}
                                                <h2 class="page-title my-3">
                                                    {{ __('Stripe Settings') }}
                                                </h2>
                                                {{-- Publishable Key --}}
                                                <div class="col-md-6 col-xl-6">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label required">{{ __('Publishable Key') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="stripe_publishable_key" maxlength="120"
                                                            value="{{ $config[9]->config_value }}"
                                                            placeholder="{{ __('Publishable Key') }}" required />
                                                    </div>
                                                </div>

                                                {{-- Secret --}}
                                                <div class="col-md-6 col-xl-6">
                                                    <div class="mb-3">
                                                        <label class="form-label required">{{ __('Secret Key') }}</label>
                                                        <input type="text" class="form-control" name="stripe_secret"
                                                            value="{{ $config[10]->config_value }}" maxlength="120"
                                                            placeholder="{{ __('Secret Key') }}" required />
                                                    </div>
                                                </div>

                                                {{-- Paystack Settings --}}
                                                <h2 class="page-title my-3">
                                                    {{ __('Paystack Settings') }}
                                                </h2>
                                                {{-- Publishable Key --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label required">{{ __('Public Key') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="paystack_public_key" maxlength="120"
                                                            value="{{ $config[37]->config_value }}"
                                                            placeholder="{{ __('Public Key') }}" required />
                                                    </div>
                                                </div>

                                                {{-- Secret --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label required">{{ __('Secret Key') }}</label>
                                                        <input type="text" class="form-control" name="paystack_secret"
                                                            value="{{ $config[38]->config_value }}" maxlength="120"
                                                            placeholder="{{ __('Secret Key') }}" required />
                                                    </div>
                                                </div>

                                                {{-- Merchant Email --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label required">{{ __('Merchant Email') }}</label>
                                                        <input type="text" class="form-control" name="merchant_email"
                                                            value="{{ $config[40]->config_value }}" maxlength="120"
                                                            placeholder="{{ __('Merchant Email') }}" required />
                                                    </div>
                                                </div>

                                                {{-- Mollie Settings --}}
                                                <h2 class="page-title my-3">
                                                    {{ __('Mollie Settings') }}
                                                </h2>
                                                {{-- Key --}}
                                                <div class="col-md-8 col-xl-8">
                                                    <div class="mb-3">
                                                        <label class="form-label required">{{ __('Key') }}</label>
                                                        <input type="text" class="form-control" name="mollie_key"
                                                            maxlength="120" value="{{ $config[41]->config_value }}"
                                                            placeholder="{{ __('Key') }}" required />
                                                    </div>
                                                </div>

                                                {{-- Offline (Bank Transfer) Settings --}}
                                                <div class="col-md-12 col-xl-12 mt-3">
                                                    <h2 class="page-title my-3">
                                                        {{ __('Offline (Bank Transfer) Settings') }}
                                                    </h2>
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label required">{{ __('Offline (Bank Transfer) Details') }}</label>
                                                        <textarea class="form-control" name="bank_transfer" id="bank_transfer" rows="3" maxlength="250"
                                                            placeholder="{{ __('Offline (Bank Transfer) Details') }}" required>{{ $config[31]->config_value }}</textarea>
                                                    </div>
                                                </div>

                                                {{-- Update button --}}
                                                <div class="text-end">
                                                    <div class="d-flex">
                                                        <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                            {{ __('Update') }}
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Google Configuration Settings --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-4">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-4" aria-expanded="false">
                                        <h2>{{ __('Google Configuration Settings') }}</h2>
                                    </button>
                                </h2>
                                <div id="collapse-4" class="accordion-collapse collapse"
                                    data-bs-parent="#accordion-example">
                                    <div class="accordion-body pt-0">
                                        <form action="{{ route('admin.change.google.settings') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">

                                                {{-- Google reCAPTCHA Settings --}}
                                                <h2 class="page-title my-3">
                                                    {{ __('Google reCAPTCHA Configuration Settings') }}
                                                </h2>
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('reCAPTCHA Enable') }}</div>
                                                        <select class="form-select" placeholder="Select a reCAPTCHA"
                                                            id="recaptcha_enable" name="recaptcha_enable">
                                                            <option value="on"
                                                                {{ $settings->recaptcha_configuration['RECAPTCHA_ENABLE'] == 'on' ? 'selected' : '' }}>
                                                                {{ __('On') }}</option>
                                                            <option value="off"
                                                                {{ $settings->recaptcha_configuration['RECAPTCHA_ENABLE'] == 'off' ? 'selected' : '' }}>
                                                                {{ __('Off') }}</option>
                                                        </select>
                                                    </div>
                                                    <span>{{ __('If you did not get a reCAPTCHA (v2 Checkbox), create a') }}
                                                        <a href="https://www.google.com/recaptcha/about/"
                                                            target="_blank">{{ __('reCAPTCHA') }}</a> </span>
                                                </div>

                                                {{-- reCAPTCHA Site Key --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('reCAPTCHA Site Key') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="recaptcha_site_key" maxlength="120"
                                                            value="{{ $settings->recaptcha_configuration['RECAPTCHA_SITE_KEY'] }}"
                                                            placeholder="{{ __('reCAPTCHA Site Key') }}" />
                                                    </div>
                                                </div>

                                                {{-- reCAPTCHA Secret Key --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('reCAPTCHA Secret Key') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="recaptcha_secret_key" maxlength="120"
                                                            value="{{ $settings->recaptcha_configuration['RECAPTCHA_SECRET_KEY'] }}"
                                                            placeholder="{{ __('reCAPTCHA Secret Key') }}" />
                                                    </div>
                                                </div>

                                                {{-- Google OAuth Settings --}}
                                                <h2 class="page-title my-4">
                                                    {{ __('Google OAuth Configuration Settings') }}
                                                </h2>
                                                {{-- Google Auth Enable --}}
                                                <div class="col-md-3 col-xl-3">
                                                    <div class="mb-3">
                                                        <div class="form-label">{{ __('Google Auth Enable') }}</div>
                                                        <select class="form-select" placeholder="Select a reCAPTCHA"
                                                            id="google_auth_enable" name="google_auth_enable">
                                                            <option value="on"
                                                                {{ $settings->google_configuration['GOOGLE_ENABLE'] == 'on' ? 'selected' : '' }}>
                                                                {{ __('On') }}</option>
                                                            <option value="off"
                                                                {{ $settings->google_configuration['GOOGLE_ENABLE'] == 'off' ? 'selected' : '' }}>
                                                                {{ __('Off') }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Google Client ID --}}
                                                <div class="col-md-3 col-xl-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('Google Client ID') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="google_client_id" maxlength="120"
                                                            value="{{ $settings->google_configuration['GOOGLE_CLIENT_ID'] }}"
                                                            placeholder="{{ __('Google CLIENT ID') }}" />
                                                    </div>
                                                </div>

                                                {{-- Google Client Secret --}}
                                                <div class="col-md-3 col-xl-3">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('Google Client Secret') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="google_client_secret" maxlength="120"
                                                            value="{{ $settings->google_configuration['GOOGLE_CLIENT_SECRET'] }}"
                                                            placeholder="{{ __('Google CLIENT Secret') }}" />
                                                    </div>
                                                </div>

                                                {{-- Google Redirect --}}
                                                <div class="col-md-3 col-xl-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('Google Redirect') }}</label>
                                                        <input type="text" class="form-control" name="google_redirect"
                                                            maxlength="120"
                                                            value="{{ $settings->google_configuration['GOOGLE_REDIRECT'] }}"
                                                            placeholder="{{ __('Google Redirect') }}" />
                                                    </div>
                                                </div>
                                                <span>{{ __('If you did not get a google OAuth Client ID & Secret Key, follow a') }}
                                                    <a href="https://support.google.com/cloud/answer/6158849?hl=en#zippy=%2Cuser-consent%2Cpublic-and-internal-applications%2Cauthorized-domains/"
                                                        target="_blank">{{ __(' steps') }}</a> </span>

                                                {{-- Google Analytics ID --}}
                                                <div class="col-md-6 col-xl-6 mt-3">
                                                    <h2 class="page-title my-3">
                                                        {{ __('Google Analytics Configuration Settings') }}
                                                    </h2>
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label required">{{ __('Google Analytics ID') }}</label>
                                                        <input type="text" class="form-control"
                                                            name="google_analytics_id"
                                                            value="{{ $settings->analytics_id }}" maxlength="120"
                                                            placeholder="{{ __('Google Analytics ID') }}" required />
                                                    </div>
                                                    <span>{{ __('If you did not get a google analytics id, create a') }}
                                                        <a href="https://analytics.google.com/analytics/web/"
                                                            target="_blank">{{ __('new analytics id.') }}</a> </span>
                                                </div>

                                                {{-- Google Tag ID --}}
                                                <div class="col-md-6 col-xl-6 mt-3">
                                                    <h2 class="page-title my-3">
                                                        {{ __('Google Tag Manager Settings') }}
                                                    </h2>
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('Google Tag ID') }}</label>
                                                        <input type="text" class="form-control" name="google_tag"
                                                            value="{{ $settings->google_tag }}" maxlength="120"
                                                            placeholder="{{ __('Google Tag ID') }}" />
                                                    </div>
                                                    <span>{{ __('If you did not get a google tag id, create a') }} <a
                                                            href="https://support.google.com/analytics/answer/9539598?hl=en"
                                                            rel="nofollow" target="_blank">{{ __('new tag id.') }}</a>
                                                    </span>
                                                </div>

                                                {{-- Update button --}}
                                                <div class="text-end">
                                                    <div class="d-flex">
                                                        <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                            {{ __('Update') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Email Configuration Settings --}}
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-5">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-5" aria-expanded="false">
                                        <h2>{{ __('Email Configuration Settings') }}</h2>
                                    </button>
                                </h2>
                                <div id="collapse-5" class="accordion-collapse collapse"
                                    data-bs-parent="#accordion-example">
                                    <div class="accordion-body pt-0">
                                        <form action="{{ route('admin.change.email.settings') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                {{-- Sender Name --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('Sender Name') }}</label>
                                                        <input type="text" class="form-control" name="mail_sender"
                                                            maxlength="120"
                                                            value="{{ $settings->email_configuration['name'] }}"
                                                            placeholder="{{ __('Sender Name') }}" />
                                                    </div>
                                                </div>

                                                {{-- Sender Email Address --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('Sender Email Address') }}</label>
                                                        <input type="text" class="form-control" name="mail_address"
                                                            maxlength="120"
                                                            value="{{ $settings->email_configuration['address'] }}"
                                                            placeholder="{{ __('Sender Email Address') }}" />
                                                    </div>
                                                </div>

                                                {{-- Mailer Driver --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('Mailer Driver') }}</label>
                                                        <input type="text" class="form-control" name="mail_driver"
                                                            maxlength="120"
                                                            value="{{ $settings->email_configuration['driver'] }}"
                                                            placeholder="{{ __('Mailer Driver') }}" />
                                                    </div>
                                                </div>

                                                {{-- Mailer Host --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('Mailer Host') }}</label>
                                                        <input type="text" class="form-control" name="mail_host"
                                                            maxlength="120"
                                                            value="{{ $settings->email_configuration['host'] }}"
                                                            placeholder="{{ __('Mailer Host') }}" />
                                                    </div>
                                                </div>

                                                {{-- Mailer Port --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('Mailer Port') }}</label>
                                                        <input type="number" class="form-control" name="mail_port"
                                                            value="{{ $settings->email_configuration['port'] }}"
                                                            placeholder="{{ __('Mailer Port') }}" />
                                                    </div>
                                                </div>

                                                {{-- Mailer Encryption --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('Mailer Encryption') }}</label>
                                                        <input type="text" class="form-control" name="mail_encryption"
                                                            maxlength="120"
                                                            value="{{ $settings->email_configuration['encryption'] }}"
                                                            placeholder="{{ __('Mailer Encryption') }}" />
                                                    </div>
                                                </div>

                                                {{-- Mailer Username --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('Mailer Username') }}</label>
                                                        <input type="text" class="form-control" name="mail_username"
                                                            maxlength="120"
                                                            value="{{ $settings->email_configuration['username'] }}"
                                                            placeholder="{{ __('Mailer Username') }}" />
                                                    </div>
                                                </div>

                                                {{-- Mailer Password --}}
                                                <div class="col-md-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">{{ __('Mailer Password') }}</label>
                                                        <input type="password" class="form-control" name="mail_password"
                                                            maxlength="120"
                                                            value="{{ $settings->email_configuration['password'] }}"
                                                            placeholder="{{ __('Mailer Password') }}" />
                                                    </div>
                                                </div>

                                                {{-- Test Mail --}}
                                                <div class="col-md-4 col-xl-4 mt-3">
                                                    <div class="mb-3">
                                                        <label class="form-label"></label>
                                                        <a href="{{ route('admin.test.email') }}"
                                                            class="btn btn-primary">
                                                            {{ __('Test Mail') }}
                                                        </a>
                                                    </div>
                                                </div>

                                                {{-- User Email Verification Syetem --}}
                                                <div class="row">
                                                    <h2 class="page-title my-3">
                                                        {{ __('User Email Verification Syetem') }}
                                                    </h2>
                                                    <div class="col-xl-4 col-12">
                                                        <div class="mb-3">
                                                            <label class="form-label required"
                                                                for="disable_user_email_verification">{{ __('User Email Verification') }}</label>
                                                            <select name="disable_user_email_verification"
                                                                id="disable_user_email_verification" class="form-control"
                                                                required>
                                                                <option value="1"
                                                                    {{ $config[52]->config_value == '1' ? ' selected' : '' }}>
                                                                    {{ __('Yes') }}</option>
                                                                <option value="0"
                                                                    {{ $config[52]->config_value == '0' ? ' selected' : '' }}>
                                                                    {{ __('No') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Update button --}}
                                                <div class="text-end">
                                                    <div class="d-flex">
                                                        <button type="submit" class="btn btn-primary btn-md ms-auto">
                                                            {{ __('Update') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('admin.includes.footer')
    </div>

    {{-- Custom JS --}}
@section('custom-js')
    {{-- Tom Select --}}
    <script src="{{ asset('assets/js/tom-select.base.min.js') }}"></script>
    <script>
        // Validate port
        function validatePort(input) {
            "use strict";

            const maxLength = 5; // Set your desired max length
            if (input.value.length > maxLength) {
                input.value = input.value.slice(0, maxLength);
            }
        }
    </script>
    {{-- Tiny MCE --}}
    <script>
        tinymce.init({
            selector: 'textarea#bank_transfer',
            plugins: 'code preview importcss searchreplace autolink autosave save directionality visualblocks visualchars link table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
            menubar: 'file edit view insert format tools table help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | pagebreak | link',
            toolbar_sticky: true,
            height: 300,
            menubar: false,
            statusbar: false,
            autosave_interval: '30s',
            autosave_prefix: '{path}{query}-{id}-',
            autosave_restore_when_empty: false,
            autosave_retention: '2m',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
        });
    </script>
    <script>
        // Array of element IDs
        var elementIds = ['default_country', 'timezone', 'currency', 'term', 'cookie', 'show_whatsapp_chatbot',
            'paypal_mode',
            'aws_enable', 'recaptcha_enable', 'google_auth_enable', 'mail_encryption', 'disable_user_email_verification'
        ];

        // Loop through each element ID
        elementIds.forEach(function(id) {
            // Check if the element exists
            var el = document.getElementById(id);
            if (el) {
                // Apply TomSelect to the element
                new TomSelect(el, {
                    copyClassesToDropdown: false,
                    dropdownClass: 'dropdown-menu ts-dropdown',
                    optionClass: 'dropdown-item',
                    maxOptions: null,
                    controlInput: '<input>',
                    render: {
                        item: function(data, escape) {
                            if (data.customProperties) {
                                return '<div><span class="dropdown-item-indicator">' + data
                                    .customProperties + '</span>' + escape(data.text) + '</div>';
                            }
                            return '<div>' + escape(data.text) + '</div>';
                        },
                        option: function(data, escape) {
                            if (data.customProperties) {
                                return '<div><span class="dropdown-item-indicator">' + data
                                    .customProperties + '</span>' + escape(data.text) + '</div>';
                            }
                            return '<div>' + escape(data.text) + '</div>';
                        },
                    },
                });
            }
        });
    </script>
@endsection
@endsection

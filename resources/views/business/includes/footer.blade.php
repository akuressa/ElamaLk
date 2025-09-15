{{-- Datas --}}
@php
    use App\Models\Configuration;

    $config = Configuration::get();
@endphp

<footer class="footer footer-transparent d-print-none">
    <div class="container">
        <div class="row text-center align-items-center flex-row-reverse">
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                    <li class="list-inline-item">
                        {{ __('Copyright') }} &copy; <span id="year"></span>
                        <a href="#" class="link-secondary">{{ config('app.name') }} -
                            <strong>{{ __('v') }}{{ $config[33]->config_value }}</strong></a>.
                        {{ __('All rights reserved.') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

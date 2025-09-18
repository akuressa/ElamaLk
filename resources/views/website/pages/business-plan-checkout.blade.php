@extends('business.layouts.app')

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
                    <h2 class="page-title">
                        {{ __('Business Plan Checkout') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xl mt-3">
        <div class="row">
            {{-- Left Column: Plan Summary --}}
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">{{ __('Business Plan') }}</h3>
                        <div class="card-table table-responsive">
                            <table class="table table-vcenter">
                                <thead>
                                    <tr>
                                        <th class="w-1">{{ __('Description') }}</th>
                                        <th class="w-1">{{ __('Price') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div>
                                                {{ __($businessPlan->plan_name) }} - {{ $businessPlan->duration_months }} {{ __('Months') }}
                                            </div>
                                        </td>
                                        <td class="text-bold">
                                            {{ $currency->symbol }} {{ number_format($plan_price, 2) }}
                                        </td>
                                    </tr>
    
                                    <tr>
                                        <td class="h3 text-bold">{{ __('Total Payable') }}</td>
                                        <td class="w-1 text-bold h3">
                                            {{ $currency->symbol }} {{ number_format($total, 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Billing Details & Payment --}}
            <div class="col-lg-8">
                {{-- Failed --}}
                @if (Session::has('error'))
                    <div class="alert alert-important alert-danger alert-dismissible mb-2" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('error') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                {{-- Success --}}
                @if (Session::has('success'))
                    <div class="alert alert-important alert-success alert-dismissible mb-2" role="alert">
                        <div class="d-flex">
                            <div>
                                {{ Session::get('success') }}
                            </div>
                        </div>
                        <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif

                <form action="{{ route('business.plan.payment', ['business_id' => $business->business_id, 'business_plan_id' => $businessPlan->business_plan_id]) }}" method="POST">
                    @csrf
                    
                    <div class="col-lg-12 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="row">
                                        <h3 class="card-title text-muted mb-3">{{ __('Billing Details') }}</h3>
                                        
                                        {{-- Name --}}
                                        <div class="col-md-4 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Name') }}</label>
                                                <input type="text" class="form-control" name="billing_name"
                                                    placeholder="{{ __('Name') }}"
                                                    value="{{ $billing_details['billing_name'] ?? Auth::user()->name }}"
                                                    required />
                                            </div>
                                        </div>
                                        
                                        {{-- Email --}}
                                        <div class="col-md-4 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Email') }}</label>
                                                <input type="email" class="form-control" name="billing_email"
                                                    placeholder="{{ __('Email') }}"
                                                    value="{{ $billing_details['billing_email'] ?? Auth::user()->email }}"
                                                    required />
                                            </div>
                                        </div>
                                        
                                        {{-- Phone --}}
                                        <div class="col-md-4 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Phone') }}</label>
                                                <input type="tel" class="form-control" name="billing_phone"
                                                    placeholder="{{ __('Phone') }}"
                                                    value="{{ $billing_details['billing_phone'] ?? Auth::user()->phone }}"
                                                    required />
                                            </div>
                                        </div>
                                        
                                        {{-- Address --}}
                                        <div class="col-md-4 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Billing Address') }}</label>
                                                <textarea class="form-control" name="billing_address" id="billing_address" cols="10" rows="3"
                                                    placeholder="{{ __('Billing Address') }}" required>{{ $billing_details['billing_address'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        
                                        {{-- City --}}
                                        <div class="col-md-4 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Billing City') }}</label>
                                                <input type="text" class="form-control" name="billing_city"
                                                    value="{{ $billing_details['billing_city'] ?? '' }}"
                                                    placeholder="{{ __('Billing City') }}" required />
                                            </div>
                                        </div>
                                        
                                        {{-- State / Province --}}
                                        <div class="col-md-4 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Billing State/Province') }}</label>
                                                <input type="text" class="form-control" name="billing_state"
                                                    value="{{ $billing_details['billing_state'] ?? '' }}"
                                                    placeholder="{{ __('Billing State/Province') }}"
                                                    required />
                                            </div>
                                        </div>
                                        
                                        {{-- Zip code --}}
                                        <div class="col-md-4 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Billing Zip Code') }}</label>
                                                <input type="text" class="form-control"
                                                    name="billing_zipcode"
                                                    value="{{ $billing_details['billing_zipcode'] ?? '' }}"
                                                    placeholder="{{ __('Billing Zip Code') }}" required />
                                            </div>
                                        </div>
                                        
                                        {{-- Country --}}
                                        <div class="col-md-4 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label required">{{ __('Billing Country') }}</label>
                                                <select class="form-control" name="billing_country" required>
                                                    <option value="">{{ __('Select Country') }}</option>
                                                    <option value="US" {{ ($billing_details['billing_country'] ?? '') == 'US' ? 'selected' : '' }}>United States</option>
                                                    <option value="CA" {{ ($billing_details['billing_country'] ?? '') == 'CA' ? 'selected' : '' }}>Canada</option>
                                                    <option value="GB" {{ ($billing_details['billing_country'] ?? '') == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                                    <option value="AU" {{ ($billing_details['billing_country'] ?? '') == 'AU' ? 'selected' : '' }}>Australia</option>
                                                    <option value="DE" {{ ($billing_details['billing_country'] ?? '') == 'DE' ? 'selected' : '' }}>Germany</option>
                                                    <option value="FR" {{ ($billing_details['billing_country'] ?? '') == 'FR' ? 'selected' : '' }}>France</option>
                                                    <option value="IT" {{ ($billing_details['billing_country'] ?? '') == 'IT' ? 'selected' : '' }}>Italy</option>
                                                    <option value="ES" {{ ($billing_details['billing_country'] ?? '') == 'ES' ? 'selected' : '' }}>Spain</option>
                                                    <option value="NL" {{ ($billing_details['billing_country'] ?? '') == 'NL' ? 'selected' : '' }}>Netherlands</option>
                                                    <option value="BE" {{ ($billing_details['billing_country'] ?? '') == 'BE' ? 'selected' : '' }}>Belgium</option>
                                                    <option value="CH" {{ ($billing_details['billing_country'] ?? '') == 'CH' ? 'selected' : '' }}>Switzerland</option>
                                                    <option value="AT" {{ ($billing_details['billing_country'] ?? '') == 'AT' ? 'selected' : '' }}>Austria</option>
                                                    <option value="SE" {{ ($billing_details['billing_country'] ?? '') == 'SE' ? 'selected' : '' }}>Sweden</option>
                                                    <option value="NO" {{ ($billing_details['billing_country'] ?? '') == 'NO' ? 'selected' : '' }}>Norway</option>
                                                    <option value="DK" {{ ($billing_details['billing_country'] ?? '') == 'DK' ? 'selected' : '' }}>Denmark</option>
                                                    <option value="FI" {{ ($billing_details['billing_country'] ?? '') == 'FI' ? 'selected' : '' }}>Finland</option>
                                                    <option value="IE" {{ ($billing_details['billing_country'] ?? '') == 'IE' ? 'selected' : '' }}>Ireland</option>
                                                    <option value="PT" {{ ($billing_details['billing_country'] ?? '') == 'PT' ? 'selected' : '' }}>Portugal</option>
                                                    <option value="LU" {{ ($billing_details['billing_country'] ?? '') == 'LU' ? 'selected' : '' }}>Luxembourg</option>
                                                    <option value="MT" {{ ($billing_details['billing_country'] ?? '') == 'MT' ? 'selected' : '' }}>Malta</option>
                                                    <option value="CY" {{ ($billing_details['billing_country'] ?? '') == 'CY' ? 'selected' : '' }}>Cyprus</option>
                                                    <option value="EE" {{ ($billing_details['billing_country'] ?? '') == 'EE' ? 'selected' : '' }}>Estonia</option>
                                                    <option value="LV" {{ ($billing_details['billing_country'] ?? '') == 'LV' ? 'selected' : '' }}>Latvia</option>
                                                    <option value="LT" {{ ($billing_details['billing_country'] ?? '') == 'LT' ? 'selected' : '' }}>Lithuania</option>
                                                    <option value="SI" {{ ($billing_details['billing_country'] ?? '') == 'SI' ? 'selected' : '' }}>Slovenia</option>
                                                    <option value="SK" {{ ($billing_details['billing_country'] ?? '') == 'SK' ? 'selected' : '' }}>Slovakia</option>
                                                    <option value="CZ" {{ ($billing_details['billing_country'] ?? '') == 'CZ' ? 'selected' : '' }}>Czech Republic</option>
                                                    <option value="HU" {{ ($billing_details['billing_country'] ?? '') == 'HU' ? 'selected' : '' }}>Hungary</option>
                                                    <option value="PL" {{ ($billing_details['billing_country'] ?? '') == 'PL' ? 'selected' : '' }}>Poland</option>
                                                    <option value="RO" {{ ($billing_details['billing_country'] ?? '') == 'RO' ? 'selected' : '' }}>Romania</option>
                                                    <option value="BG" {{ ($billing_details['billing_country'] ?? '') == 'BG' ? 'selected' : '' }}>Bulgaria</option>
                                                    <option value="HR" {{ ($billing_details['billing_country'] ?? '') == 'HR' ? 'selected' : '' }}>Croatia</option>
                                                    <option value="GR" {{ ($billing_details['billing_country'] ?? '') == 'GR' ? 'selected' : '' }}>Greece</option>
                                                    <option value="JP" {{ ($billing_details['billing_country'] ?? '') == 'JP' ? 'selected' : '' }}>Japan</option>
                                                    <option value="KR" {{ ($billing_details['billing_country'] ?? '') == 'KR' ? 'selected' : '' }}>South Korea</option>
                                                    <option value="CN" {{ ($billing_details['billing_country'] ?? '') == 'CN' ? 'selected' : '' }}>China</option>
                                                    <option value="IN" {{ ($billing_details['billing_country'] ?? '') == 'IN' ? 'selected' : '' }}>India</option>
                                                    <option value="SG" {{ ($billing_details['billing_country'] ?? '') == 'SG' ? 'selected' : '' }}>Singapore</option>
                                                    <option value="HK" {{ ($billing_details['billing_country'] ?? '') == 'HK' ? 'selected' : '' }}>Hong Kong</option>
                                                    <option value="TW" {{ ($billing_details['billing_country'] ?? '') == 'TW' ? 'selected' : '' }}>Taiwan</option>
                                                    <option value="TH" {{ ($billing_details['billing_country'] ?? '') == 'TH' ? 'selected' : '' }}>Thailand</option>
                                                    <option value="MY" {{ ($billing_details['billing_country'] ?? '') == 'MY' ? 'selected' : '' }}>Malaysia</option>
                                                    <option value="ID" {{ ($billing_details['billing_country'] ?? '') == 'ID' ? 'selected' : '' }}>Indonesia</option>
                                                    <option value="PH" {{ ($billing_details['billing_country'] ?? '') == 'PH' ? 'selected' : '' }}>Philippines</option>
                                                    <option value="VN" {{ ($billing_details['billing_country'] ?? '') == 'VN' ? 'selected' : '' }}>Vietnam</option>
                                                    <option value="BR" {{ ($billing_details['billing_country'] ?? '') == 'BR' ? 'selected' : '' }}>Brazil</option>
                                                    <option value="MX" {{ ($billing_details['billing_country'] ?? '') == 'MX' ? 'selected' : '' }}>Mexico</option>
                                                    <option value="AR" {{ ($billing_details['billing_country'] ?? '') == 'AR' ? 'selected' : '' }}>Argentina</option>
                                                    <option value="CL" {{ ($billing_details['billing_country'] ?? '') == 'CL' ? 'selected' : '' }}>Chile</option>
                                                    <option value="CO" {{ ($billing_details['billing_country'] ?? '') == 'CO' ? 'selected' : '' }}>Colombia</option>
                                                    <option value="PE" {{ ($billing_details['billing_country'] ?? '') == 'PE' ? 'selected' : '' }}>Peru</option>
                                                    <option value="ZA" {{ ($billing_details['billing_country'] ?? '') == 'ZA' ? 'selected' : '' }}>South Africa</option>
                                                    <option value="EG" {{ ($billing_details['billing_country'] ?? '') == 'EG' ? 'selected' : '' }}>Egypt</option>
                                                    <option value="NG" {{ ($billing_details['billing_country'] ?? '') == 'NG' ? 'selected' : '' }}>Nigeria</option>
                                                    <option value="KE" {{ ($billing_details['billing_country'] ?? '') == 'KE' ? 'selected' : '' }}>Kenya</option>
                                                    <option value="MA" {{ ($billing_details['billing_country'] ?? '') == 'MA' ? 'selected' : '' }}>Morocco</option>
                                                    <option value="TN" {{ ($billing_details['billing_country'] ?? '') == 'TN' ? 'selected' : '' }}>Tunisia</option>
                                                    <option value="DZ" {{ ($billing_details['billing_country'] ?? '') == 'DZ' ? 'selected' : '' }}>Algeria</option>
                                                    <option value="LY" {{ ($billing_details['billing_country'] ?? '') == 'LY' ? 'selected' : '' }}>Libya</option>
                                                    <option value="SD" {{ ($billing_details['billing_country'] ?? '') == 'SD' ? 'selected' : '' }}>Sudan</option>
                                                    <option value="ET" {{ ($billing_details['billing_country'] ?? '') == 'ET' ? 'selected' : '' }}>Ethiopia</option>
                                                    <option value="GH" {{ ($billing_details['billing_country'] ?? '') == 'GH' ? 'selected' : '' }}>Ghana</option>
                                                    <option value="UG" {{ ($billing_details['billing_country'] ?? '') == 'UG' ? 'selected' : '' }}>Uganda</option>
                                                    <option value="TZ" {{ ($billing_details['billing_country'] ?? '') == 'TZ' ? 'selected' : '' }}>Tanzania</option>
                                                    <option value="RW" {{ ($billing_details['billing_country'] ?? '') == 'RW' ? 'selected' : '' }}>Rwanda</option>
                                                    <option value="BI" {{ ($billing_details['billing_country'] ?? '') == 'BI' ? 'selected' : '' }}>Burundi</option>
                                                    <option value="DJ" {{ ($billing_details['billing_country'] ?? '') == 'DJ' ? 'selected' : '' }}>Djibouti</option>
                                                    <option value="SO" {{ ($billing_details['billing_country'] ?? '') == 'SO' ? 'selected' : '' }}>Somalia</option>
                                                    <option value="ER" {{ ($billing_details['billing_country'] ?? '') == 'ER' ? 'selected' : '' }}>Eritrea</option>
                                                    <option value="SS" {{ ($billing_details['billing_country'] ?? '') == 'SS' ? 'selected' : '' }}>South Sudan</option>
                                                    <option value="CF" {{ ($billing_details['billing_country'] ?? '') == 'CF' ? 'selected' : '' }}>Central African Republic</option>
                                                    <option value="TD" {{ ($billing_details['billing_country'] ?? '') == 'TD' ? 'selected' : '' }}>Chad</option>
                                                    <option value="NE" {{ ($billing_details['billing_country'] ?? '') == 'NE' ? 'selected' : '' }}>Niger</option>
                                                    <option value="ML" {{ ($billing_details['billing_country'] ?? '') == 'ML' ? 'selected' : '' }}>Mali</option>
                                                    <option value="BF" {{ ($billing_details['billing_country'] ?? '') == 'BF' ? 'selected' : '' }}>Burkina Faso</option>
                                                    <option value="CI" {{ ($billing_details['billing_country'] ?? '') == 'CI' ? 'selected' : '' }}>Côte d'Ivoire</option>
                                                    <option value="GN" {{ ($billing_details['billing_country'] ?? '') == 'GN' ? 'selected' : '' }}>Guinea</option>
                                                    <option value="GW" {{ ($billing_details['billing_country'] ?? '') == 'GW' ? 'selected' : '' }}>Guinea-Bissau</option>
                                                    <option value="GM" {{ ($billing_details['billing_country'] ?? '') == 'GM' ? 'selected' : '' }}>Gambia</option>
                                                    <option value="SN" {{ ($billing_details['billing_country'] ?? '') == 'SN' ? 'selected' : '' }}>Senegal</option>
                                                    <option value="MR" {{ ($billing_details['billing_country'] ?? '') == 'MR' ? 'selected' : '' }}>Mauritania</option>
                                                    <option value="CV" {{ ($billing_details['billing_country'] ?? '') == 'CV' ? 'selected' : '' }}>Cape Verde</option>
                                                    <option value="ST" {{ ($billing_details['billing_country'] ?? '') == 'ST' ? 'selected' : '' }}>São Tomé and Príncipe</option>
                                                    <option value="GQ" {{ ($billing_details['billing_country'] ?? '') == 'GQ' ? 'selected' : '' }}>Equatorial Guinea</option>
                                                    <option value="GA" {{ ($billing_details['billing_country'] ?? '') == 'GA' ? 'selected' : '' }}>Gabon</option>
                                                    <option value="CG" {{ ($billing_details['billing_country'] ?? '') == 'CG' ? 'selected' : '' }}>Republic of the Congo</option>
                                                    <option value="CD" {{ ($billing_details['billing_country'] ?? '') == 'CD' ? 'selected' : '' }}>Democratic Republic of the Congo</option>
                                                    <option value="AO" {{ ($billing_details['billing_country'] ?? '') == 'AO' ? 'selected' : '' }}>Angola</option>
                                                    <option value="ZM" {{ ($billing_details['billing_country'] ?? '') == 'ZM' ? 'selected' : '' }}>Zambia</option>
                                                    <option value="ZW" {{ ($billing_details['billing_country'] ?? '') == 'ZW' ? 'selected' : '' }}>Zimbabwe</option>
                                                    <option value="BW" {{ ($billing_details['billing_country'] ?? '') == 'BW' ? 'selected' : '' }}>Botswana</option>
                                                    <option value="NA" {{ ($billing_details['billing_country'] ?? '') == 'NA' ? 'selected' : '' }}>Namibia</option>
                                                    <option value="SZ" {{ ($billing_details['billing_country'] ?? '') == 'SZ' ? 'selected' : '' }}>Eswatini</option>
                                                    <option value="LS" {{ ($billing_details['billing_country'] ?? '') == 'LS' ? 'selected' : '' }}>Lesotho</option>
                                                    <option value="MW" {{ ($billing_details['billing_country'] ?? '') == 'MW' ? 'selected' : '' }}>Malawi</option>
                                                    <option value="MZ" {{ ($billing_details['billing_country'] ?? '') == 'MZ' ? 'selected' : '' }}>Mozambique</option>
                                                    <option value="MG" {{ ($billing_details['billing_country'] ?? '') == 'MG' ? 'selected' : '' }}>Madagascar</option>
                                                    <option value="MU" {{ ($billing_details['billing_country'] ?? '') == 'MU' ? 'selected' : '' }}>Mauritius</option>
                                                    <option value="SC" {{ ($billing_details['billing_country'] ?? '') == 'SC' ? 'selected' : '' }}>Seychelles</option>
                                                    <option value="KM" {{ ($billing_details['billing_country'] ?? '') == 'KM' ? 'selected' : '' }}>Comoros</option>
                                                    <option value="YT" {{ ($billing_details['billing_country'] ?? '') == 'YT' ? 'selected' : '' }}>Mayotte</option>
                                                    <option value="RE" {{ ($billing_details['billing_country'] ?? '') == 'RE' ? 'selected' : '' }}>Réunion</option>
                                                    <option value="SH" {{ ($billing_details['billing_country'] ?? '') == 'SH' ? 'selected' : '' }}>Saint Helena</option>
                                                    <option value="AC" {{ ($billing_details['billing_country'] ?? '') == 'AC' ? 'selected' : '' }}>Ascension Island</option>
                                                    <option value="TA" {{ ($billing_details['billing_country'] ?? '') == 'TA' ? 'selected' : '' }}>Tristan da Cunha</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        {{-- Tax Number --}}
                                        <div class="col-md-4 col-xl-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('Tax Number') }}</label>
                                                <input type="text" class="form-control" name="vat_number"
                                                    value="{{ $billing_details['vat_number'] ?? '' }}"
                                                    placeholder="{{ __('Tax Number') }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Methods --}}
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                {{-- Payment Methods --}}
                                <h3 class="card-title text-muted">{{ __('Payment Methods') }}</h3>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <div class="row">
                                                @foreach ($gateways as $gateway)
                                                    <div class="col-lg-4 mb-3">
                                                        <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                                                            <label class="form-selectgroup-item flex-fill">
                                                                <input type="radio"
                                                                    name="payment_gateway_id"
                                                                    value="{{ $gateway->payment_gateway_id }}"
                                                                    class="form-selectgroup-input">
                                                                <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                                    <div class="me-3">
                                                                        <span class="form-selectgroup-check"></span>
                                                                    </div>
                                                                    <span class="avatar me-3"
                                                                        style="background-image: url({{ asset($gateway->payment_gateway_logo_url) }})"></span>
                                                                    <div>
                                                                        <div class="font-weight-medium h4">
                                                                            {{ __($gateway->payment_gateway_name) }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <input type="submit" value="{{ __('Continue for payment') }}"
                                                class="btn btn-primary">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
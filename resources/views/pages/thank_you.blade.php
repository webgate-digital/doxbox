@extends('layout', ['ogTitle' => $translations['cart.thank_you']['text']])

@section('content')
    <!-- Page title -->
    @php
        $breadcrumbs = [];
        $breadcrumbs[] = [
            'url' => url()->current(),
            'title' => $translations['cart.thank_you']['text'],
        ];
    @endphp
    @include('components.breadcrumbs')

    <section class="section section--thank-you">
        <div class="container">
            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-8">
                <div>
                    <h1 class="text-heading-xs mb-10">
                        {{ $translations['cart.thank_you']['text'] }}
                    </h1>
                    <p>
                        {{ $translations['Objednávka č.']['text'] }}
                        <strong>{{ $order['code'] ?? '-' }}</strong>
                        {{ $translations['bola úspešne vytvorená']['text'] }}.
                    </p>
                    <p>
                        {!! str_replace('%email%', $order['customer']['email'], $translations['cart.thank_you.info']['text']) !!}
                    </p>
                    <h3 class="text-subheading-xl my-4">
                        {{ $translations['Čo bude nasledovať?']['text'] }}
                    </h3>
                    <p>
                        {!! $translations['cart.thank_you.whats_next.pickup']['text'] !!}
                    </p>
                </div>
                <div>
                    <div class="p-6 bg-gray-5 md:mt-4 rounded-lg">
                        <div class="flex gap-6 items-center">
                            <svg width="36" height="36" viewBox="0 0 36 36" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M33 9C33 7.34315 31.6569 6 30 6H6C4.34315 6 3 7.34315 3 9V27C3 28.6569 4.34315 30 6 30H30C31.6569 30 33 28.6569 33 27V9ZM15 24.75C15 25.1642 14.6642 25.5 14.25 25.5H9.75C9.33579 25.5 9 25.1642 9 24.75V23.25C9 22.8358 9.33579 22.5 9.75 22.5H14.25C14.6642 22.5 15 22.8358 15 23.25V24.75ZM30 17.25C30 17.6642 29.6642 18 29.25 18H6.75C6.33579 18 6 17.6642 6 17.25V14.25C6 13.8358 6.33579 13.5 6.75 13.5H29.25C29.6642 13.5 30 13.8358 30 14.25V17.25Z"
                                    fill="#131416" />
                            </svg>
                            <span class="text-subheading-l">{{ $translations['Platba']['text'] }}</span>
                        </div>
                        <div class="text-body-m mt-6">
                            @if($order['payment']['uuid'] === 'SK_CARD_PAYPAL_0_99999')
                                {!! $translations['cart.thank_you.payment.paypal']['text'] !!}
                            @elseif($order['payment']['uuid'] === 'SK_CARD_GPWEBPAY_0_99999')
                                {!! $translations['cart.thank_you.payment.gpwebpay']['text'] !!}
                            @elseif($order['payment']['uuid'] === 'SK_CASH_0_20000')
                                {!! $translations['cart.thank_you.payment.cash']['text'] !!}
                            @elseif($order['payment']['uuid'] === 'SK_COD_10_10')
                                {!! $translations['cart.thank_you.payment.cod']['text'] !!}
                            @endif
                        </div>
                    </div>
                    <div class="p-6 bg-gray-5 rounded-lg mt-4 md:mt-10">
                        <div class="flex gap-6 items-center">
                            <svg width="36" height="36" viewBox="0 0 36 36" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M28.905 10.905L28.095 10.095C27.4061 9.40534 26.4748 9.01237 25.5 9H10.5C9.50446 8.99656 8.54872 9.39074 7.84503 10.095L7.03503 10.905C6.36733 11.6035 5.99635 12.5337 6.00003 13.5V31.5C6.00003 32.3284 6.6716 33 7.50003 33H9.00003C9.82845 33 10.5 32.3284 10.5 31.5V30H25.5V31.5C25.5 32.3284 26.1716 33 27 33H28.5C29.3285 33 30 32.3284 30 31.5V13.5C29.9877 12.5252 29.5947 11.594 28.905 10.905ZM13.5 25.5C13.5 26.3284 12.8285 27 12 27H10.5C9.6716 27 9.00003 26.3284 9.00003 25.5V24C9.00003 23.1716 9.6716 22.5 10.5 22.5H12C12.8285 22.5 13.5 23.1716 13.5 24V25.5ZM27 25.5C27 26.3284 26.3285 27 25.5 27H24C23.1716 27 22.5 26.3284 22.5 25.5V24C22.5 23.1716 23.1716 22.5 24 22.5H25.5C26.3285 22.5 27 23.1716 27 24V25.5ZM27 18H9.00003V13.5H27V18ZM25.5 4.5C25.5 3.67157 24.8285 3 24 3H12C11.1716 3 10.5 3.67157 10.5 4.5V6H25.5V4.5Z"
                                    fill="#131416" />
                            </svg>

                            <span class="text-subheading-l">{{ $translations['Doručenie']['text'] }}</span>
                        </div>
                        <div class="text-body-m mt-6">
                            @if($order['shipping']['uuid'] === 'SK_COURIER_123KURIER_0_99999')
                                @php
                                    $isDifferentShipping = $order['customer']['delivery_street'] !== null;
                                    if($isDifferentShipping) {
                                        $address = "{$order['customer']['delivery_street']} {$order['customer']['delivery_house_number']}, {$order['customer']['delivery_city']}, {$order['customer']['delivery_zip']}";
                                    } else {
                                        $address = "{$order['customer']['street']} {$order['customer']['house_number']}, {$order['customer']['city']}, {$order['customer']['zip']}";
                                    }
                                @endphp
                                {!! str_replace('%address%', $address, $translations['cart.thank_you.shipping.courier']['text']) !!}
                            @elseif($order['shipping']['uuid'] === 'SK_PACKETA_0_10000')
                                {!! str_replace('%address%', $order['packeta-selector-branch-name'], $translations['cart.thank_you.shipping.packeta']['text']) !!}
                            @elseif($order['shipping']['uuid'] === 'SK_PERSONAL_0_99999')
                                {!! $translations['cart.thank_you.shipping.pickup']['text'] !!}
                            @endif
                        </div>
                    </div>
                    <div class="p-6 bg-gray-5 rounded-lg mt-4 md:mt-10">
                        <div class="flex gap-6 items-center">
                            <svg width="36" height="36" viewBox="0 0 36 36" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.5026 10.5C10.5026 6.35786 13.8615 3 18.0049 3C22.1483 3 25.5072 6.35786 25.5072 10.5C25.5072 14.6421 22.1483 18 18.0049 18C13.8615 18 10.5026 14.6421 10.5026 10.5ZM32.8444 30.825L30.0085 25.14C28.7366 22.6024 26.1407 21.0001 23.3015 21H12.7083C9.8691 21.0001 7.27321 22.6024 6.00125 25.14L3.16539 30.825C2.93137 31.2893 2.95451 31.8416 3.22656 32.2847C3.4986 32.7278 3.98075 32.9984 4.5008 33H31.509C32.029 32.9984 32.5112 32.7278 32.7832 32.2847C33.0553 31.8416 33.0784 31.2893 32.8444 30.825Z"
                                    fill="#131416" />
                            </svg>

                            <span
                                class="text-subheading-l">{{ $translations['Sú vaše kontaktné údaje spravne?']['text'] }}</span>
                        </div>
                        <div class="text-body-m mt-6">
                            {!! $translations['cart.thank_you.wrong_info']['text'] !!}
                        </div>
                        <div class="flex items-center gap-6 mt-4">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17 21C15.3463 20.9986 13.7183 20.5899 12.26 19.81L11.81 19.56C8.70075 17.8883 6.15169 15.3393 4.48 12.23L4.23 11.78C3.42982 10.3134 3.00713 8.67072 3 7.00003V6.33003C2.99958 5.79698 3.21196 5.28582 3.59 4.91003L5.28 3.22003C5.44413 3.05462 5.67581 2.9749 5.90696 3.00428C6.13811 3.03367 6.34248 3.16882 6.46 3.37003L8.71 7.23003C8.93753 7.62291 8.87183 8.11978 8.55 8.44003L6.66 10.33C6.50304 10.4853 6.46647 10.7251 6.57 10.92L6.92 11.58C8.17704 13.9085 10.0893 15.8172 12.42 17.07L13.08 17.43C13.275 17.5336 13.5148 17.497 13.67 17.34L15.56 15.45C15.8802 15.1282 16.3771 15.0625 16.77 15.29L20.63 17.54C20.8312 17.6575 20.9664 17.8619 20.9957 18.0931C21.0251 18.3242 20.9454 18.5559 20.78 18.72L19.09 20.41C18.7142 20.7881 18.203 21.0004 17.67 21H17Z"
                                    fill="#131416" />
                            </svg>
                            {{ $catalogSettings['phone']['value'] }}
                        </div>
                        <div class="flex items-center gap-6 mt-4">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20 4H4C2.89543 4 2 4.89543 2 6V18C2 19.1046 2.89543 20 4 20H20C21.1046 20 22 19.1046 22 18V6C22 4.89543 21.1046 4 20 4ZM20 11L13.65 15.45C12.659 16.1427 11.341 16.1427 10.35 15.45L4 11V8.9L11.35 14.05C11.7409 14.3213 12.2591 14.3213 12.65 14.05L20 8.9V11Z"
                                    fill="#131416" />
                            </svg>
                            {{ $catalogSettings['email']['value'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section--thank-you container">
        <div class="divider"></div>
    </div>

    <section class="section section--cart">
        <div class="container">
            <h2 class="text-heading-2xs">Váš nákup</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-20 mt-10">
                <table class="table-auto md:col-span-2">
                    <thead>
                        <tr>
                            <th>{{ $translations['Objednávka č.']['text'] }} {{ $order['code'] ?? '-' }}</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{--
                            <tr>
                                <td>
                                    <a class="text-primary" href="#">Extrémne odolná Loptička Wunderball -skákacia
                                        hračka</a>
                                    <small>Farba: Žltá</small>
                                    <small>Veľkosť: Medium</small>
                                </td>
                                <td>
                                    1ks
                                </td>
                                <td>
                                    28,90 €
                                </td>
                            </tr>
                        --}}
                        @foreach ($order['items'] ?? [] as $item)
                            <tr>
                                <td>
                                    <a class="text-primary" href="#">
                                        {{ $item['name'] }}
                                    </a>
                                    {{--
                                    @foreach ($item['options'] as $option)
                                        <small>{{ $option['name'] }}: {{ $option['value'] }}</small>
                                    @endforeach
                                    --}}
                                </td>
                                <td>
                                    {{ $item['quantity'] }} {{ $item['quantity_type'] }}
                                </td>
                                <td>
                                    {{ $item['vat_amount_formatted'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        {{-- <tr>
                            <td>{{ $translations['Doručenie']['text'] }}</td>
                            <td></td>
                            <td>{{ $order['shipping']['price_formatted'] }}</td>
                        </tr> --}}
                        <tr>
                            <td>{{ $translations['Spôsob platby']['text'] }}</td>
                            <td></td>
                            <td>
                                <div>{{ $order['payment']['price_formatted'] }}</div>
                                <div>{{ $order['payment']['name'] }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>{{ $translations['Celková cena']['text'] }}</td>
                            <td></td>
                            <td>{{ $order['vat_amount_formatted'] }}</td>
                        </tr>
                    </tfoot>
                </table>

                <table class="table-auto">
                    <thead>
                        <tr>
                            <th>{{ $translations['Dodacia adresa']['text'] }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $translations['Meno']['text'] }}</td>
                            <td>{{ $order['customer']['delivery_name'] ?? $order['customer']['name'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>{{ $translations['Ulica']['text'] }}</td>
                            <td>{{ $order['customer']['delivery_street'] ?? $order['customer']['street'] ?? '-' }} {{ $order['customer']['delivery_house_number'] ?? $order['customer']['house_number'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>{{ $translations['PSČ']['text'] }}</td>
                            <td>{{ $order['customer']['delivery_zip'] ?? $order['customer']['zip'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>{{ $translations['Mesto']['text'] }}</td>
                            <td>{{ $order['customer']['delivery_city'] ?? $order['customer']['city'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>{{ $translations['Telefón']['text'] }}</td>
                            <td>{{ $order['customer']['phone'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>{{ $translations['Email']['text'] }}</td>
                            <td>{{ $order['customer']['email'] ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table-auto">
                    <thead>
                        <tr>
                            <th>{{ $translations['Fakturačná adresa']['text'] }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($order['customer']['company_name']))
                            <tr>
                                <td>{{ $translations['Názov firmy']['text'] }}</td>
                                <td>{{ $order['customer']['company_name'] ?? '-' }}</td>
                            </tr>
                        @endif
                        @if(isset($order['customer']['company_id']))
                            <tr>
                                <td>{{ $translations['IČO']['text'] }}</td>
                                <td>{{ $order['customer']['company_id'] ?? '-' }}</td>
                            </tr>
                        @endif
                        @if(isset($order['customer']['company_tax_id']))
                            <tr>
                                <td>{{ $translations['DIČ']['text'] }}</td>
                                <td>{{ $order['customer']['company_tax_id'] ?? '-' }}</td>
                            </tr>
                        @endif
                        @if(isset($order['customer']['company_vat_id']))
                            <tr>
                                <td>{{ $translations['IČ DPH']['text'] }}</td>
                                <td>{{ $order['customer']['company_vat_id'] ?? '-' }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td>{{ $translations['Meno']['text'] }}</td>
                            <td>{{ $order['customer']['company_name'] ?? $order['customer']['name'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>{{ $translations['Ulica']['text'] }}</td>
                            <td>{{ $order['customer']['company_address'] ?? ($order['customer']['street'] . " " . $order['customer']['house_number']) ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>{{ $translations['PSČ']['text'] }}</td>
                            <td>{{ $order['customer']['company_zip'] ?? $order['customer']['zip'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>{{ $translations['Mesto']['text'] }}</td>
                            <td>{{ $order['customer']['company_city'] ?? $order['customer']['city'] ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @include('components.newsletter-section')
@endsection

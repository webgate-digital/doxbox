@extends('layout')

@section('content')

    @php
        $breadcrumbs = [];
        $breadcrumbs[] = [
            'url' => route(locale() . '.customer.profile'),
            'title' => $translations['customer.title']['text']
        ];
        $breadcrumbs[] = [
            'url' => route(locale() . '.orders.index'),
            'title' => $translations['orders.title']['text']
        ];
         $breadcrumbs[] = [
            'url' => url()->current(),
            'title' => $translations['order.title']['text'] . ' | ' . $order['code']
        ];
    @endphp
    @include('components.breadcrumbs')

    <section class="section section--thank-you">
        <div class="container">
            <h1 class="text-heading-xs">
                #{{ $order['code'] }}
            </h1>
        </div>
        <div class="container mt-10 mb-10">
            <div class="divider"></div>
        </div>
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
                <div class="p-6 bg-gray-5 rounded-lg">
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
                <div class="p-6 bg-gray-5 rounded-lg">
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
                            {!! str_replace('%address%', $order['packeta-selector-branch-name'] ?? "", $translations['cart.thank_you.shipping.packeta']['text']) !!}
                        @elseif($order['shipping']['uuid'] === 'SK_PERSONAL_0_99999')
                            {!! $translations['cart.thank_you.shipping.pickup']['text'] !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

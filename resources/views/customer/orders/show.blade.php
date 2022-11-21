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

    <section class="section min-h-screen">
        <div class="container">

            {{--            Page title--}}

            <div class="flex flex-wrap items-center justify-between">
                <div>
                    <h1 class="h1">
                        {{ $translations['order.title']['text'] }}
                    </h1>
                </div>
            </div>

            {{--            END Page title--}}

            <hr class="border-grey mb-16 mt-8">

            <div class="flex flex-wrap -mx-8">
                <div class="w-full lg:w-1/2 px-8">
                    <p>
                        {{ $translations['orders.code']['text'] }}:
                        <b>{{ $order['code'] }}</b>
                    </p>
                    <p>
                        {{ $translations['orders.status']['text'] }}:
                        <b>{{ $order['status'] }}</b>
                    </p>
                    <p>
                        {{ $translations['orders.shipping_type']['text'] }}:
                        <b>{{ $order['shipping']['name'] }}</b>&nbsp;<b>{{ $order['shipping']['price_formatted'] }}</b>
                    </p>
                    <p>
                        {{ $translations['orders.payment_type']['text'] }}:
                        <b>{{ $order['payment']['name'] }}</b>&nbsp;<b>{{ $order['payment']['price_formatted'] }}</b>
                    </p>
                    <p>
                        {{$translations['orders.total_price']['text'] }}:
                        <b>{{ $order['vat_amount_formatted'] }}</b>
                    </p>
                </div>
            </div>

            <hr class="border-grey mb-16 mt-8">

            <div class="flex flex-wrap -mx-8">
                <div class="w-full px-8">
                    <div>
                        <h3 class="h3">
                            {{ $translations['orders.items_title']['text'] }}
                        </h3>
                    </div>
                    <div>
                        @foreach($order['items'] as $orderItem)
                            <div class="grid grid-cols-6 border-b border-primary py-4 text-center">
                                <div class="col-span-4 text-left">
                                    {{ $orderItem['name'] }}
                                </div>
                                <div>
                                    {{ $orderItem['quantity'] }} {{ $orderItem['quantity_type'] }}
                                </div>
                                <div class="text-right">
                                    {{ $orderItem['vat_amount_formatted'] }}
                                </div>
                            </div>
                        @endforeach
                        <div class="flex justify-between mt-4">
                            <div>
                                {{ $translations['orders.total_price']['text'] }}
                            </div>
                            <div>
                                {{ $order['vat_amount_formatted'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
@endsection

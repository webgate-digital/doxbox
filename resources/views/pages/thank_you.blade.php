@extends('layout', ['ogTitle' => $translations['cart.thank_you']['text']])

@section('content')

    <!-- Page title -->
    @php
        $breadcrumbs = [];
        $breadcrumbs[] = [
        'url' => url()->current(),
        'title' => $translations['cart.thank_you']['text']
        ];
    @endphp
    @include('components.breadcrumbs')

    <section class="section">
        <div class="container">
            <h1 class="h1">
                {{$translations['cart.thank_you']['text']}} {{$order['code']}}
            </h1>
            @foreach($order['items'] as $item)
                <div class="flex flex-wrap flex-row items-center border-grey border-b mb-8 pb-8 text-small">
                    <div class="w-1/2">
                        @if($item['type'] === 'OPTION')
                            <small>{{$item['quantity']}} x {{$item['name']}}</small>
                        @else
                            {{$item['name']}}
                        @endif
                    </div>
                    <div class="w-1/4 flex flex-col items-end mt-4 lg:mt-0">
                        @if($item['type'] !== 'OPTION')
                            {{$item['quantity']}} {{$item['quantity_type']}}.
                        @endif
                    </div>
                    <div class="w-1/4 flex flex-col items-end mt-4 lg:mt-0">
                        <b>@if($item['type'] !== 'OPTION')
                                {{$item['vat_amount_formatted']}}
                            @endif</b>
                    </div>
                </div>
            @endforeach
            @if($order['voucher_used'])
            <div class="flex flex-wrap flex-row items-center border-grey border-b mb-8 pb-8 text-small">
                <div class="w-1/2">
                    {{$order['voucher_code']}}
                </div>
                <div class="w-1/2 flex flex-col items-end">
                    <b>- {{$order['voucher_amount_formatted']}}</b>
                </div>
            </div>
            @endif
            <div class="flex flex-wrap flex-row items-center border-grey border-b mb-8 pb-8 text-small">
                <div class="w-1/2">
                    {{$order['shipping']['name']}}
                </div>
                <div class="w-1/2 flex flex-col items-end">

                </div>
            </div>
            <div class="flex flex-wrap flex-row items-center border-grey border-b mb-8 pb-8 text-small">
                <div class="w-1/2">
                    {{$order['payment']['name']}}
                </div>
                <div class="w-1/2 flex flex-col items-end">

                </div>
            </div>
            @if(!$order['apply_vat_rate'])
                <div class="flex flex-wrap flex-row items-center border-grey border-b mb-8 pb-8 text-small">
                    <div class="w-1/2">
                        {{$translations['cart.total']['text']}}
                    </div>
                    <div class="w-1/2 flex flex-col items-end">
                        <b class="text-h3">{{$order['vat_amount_formatted']}}</b>
                    </div>
                </div>
            @else
                <div class="flex flex-wrap flex-row items-center border-grey border-b mb-8 pb-8 text-small">
                    <div class="w-1/2">
                        {{$translations['cart.total_without_vat']['text']}}
                    </div>
                    <div class="w-1/2 flex flex-col items-end">
                        <b>{!! \Illuminate\Support\Str::replaceFirst(' ', '&nbsp;', $order['amount_formatted']) !!}</b>
                    </div>
                </div>
                <div class="flex flex-wrap flex-row items-center border-grey border-b mb-8 pb-8 text-small">
                    <div class="w-1/2">
                        {{$translations['cart.vat']['text']}}
                    </div>
                    <div class="w-1/2 flex flex-col items-end">
                        <b>{!! \Illuminate\Support\Str::replaceFirst(' ', '&nbsp;', $order['vat_rate_formatted']) !!}</b>
                    </div>
                </div>
                <div class="flex flex-wrap flex-row items-center border-grey border-b mb-8 pb-8 text-small">
                    <div class="w-1/2">
                        {{$translations['cart.total']['text']}}
                    </div>
                    <div class="w-1/2 flex flex-col items-end">
                        <b class="text-h3">{{$order['vat_amount_formatted']}}</b>
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection

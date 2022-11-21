@extends('layout')

@section('content')

    @php
        $breadcrumbs = [];
        $breadcrumbs[] = [
            'url' => route(locale() . '.customer.profile'),
            'title' => $translations['customer.title']['text']
        ];
         $breadcrumbs[] = [
            'url' => url()->current(),
            'title' => $translations['orders.title']['text']
        ];
    @endphp
    @include('components.breadcrumbs')

    <section class="section min-h-screen">
        <div class="container">

            {{--            Page title--}}

            <div class="flex flex-wrap items-center justify-between">
                <div>
                    <h1 class="h1">
                        {{ $translations['orders.title']['text'] }}
                    </h1>
                </div>
            </div>

            {{--            END Page title--}}

            <hr class="border-grey mb-16 mt-8">

            <div class="flex flex-wrap -mx-8">
                <div class="w-full px-8">
                    @if(count($orders))
                        <div>
                            <div class="hidden sm:grid grid-cols-3 border-b border-primary py-8 text-center">
                                <div class="text-left">
                                    {{ $translations['orders.code']['text'] }}
                                </div>
                                <div>
                                    {{ $translations['orders.total_price']['text'] }}
                                </div>
                                <div class="text-right">
                                    {{ $translations['orders.status']['text'] }}
                                </div>
                            </div>
                            @foreach($orders as $order)
                                <a href="{{ route(locale() . '.orders.show', ['orderUuid' => $order['uuid'], 'token' => $order['token']]) }}" class="grid grid-cols-3 border-b border-primary py-4 text-center hover:bg-primary hover:text-white px-2">
                                    <div class="text-left">
                                        {{ $order['code'] }}
                                    </div>
                                    <div>
                                        {{ $order['vat_amount_formatted'] }}
                                    </div>
                                    <div class="text-right">
                                        {{ $order['status'] }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>


        </div>
    </section>
@endsection

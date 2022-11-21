@extends('layout')

@section('content')

    @php
        $breadcrumbs = [];
        $breadcrumbs[] = [
        'url' => url()->current(),
        'title' => $translations['customer.title']['text']
        ];
    @endphp
    @include('components.breadcrumbs')

    <section class="section">
        <div class="container">

            {{--            Page title--}}

            <div class="flex flex-wrap items-center justify-between">
                <div class="flex align-baseline">
                    <h1 class="h1">
                        {{$translations['customer.title']['text']}}
                    </h1>
                </div>
                <div>
                    <form action="{{route(locale() . '.logout')}}" method="post" class="mb-16">
                        @csrf
                        <button type="submit" class="button">{{$translations['auth.logout.cta']['text']}}</button>
                    </form>
                    <div>
                        <a href="{{ route(locale() . '.orders.index') }}" class="block p-4 text-center border border-secondary text-secondary">{{ $translations['orders.my_orders.title']['text'] }}</a>
                    </div>
                </div>
            </div>

            {{--            END Page title--}}

            <div class="flex items-start">
                @if(isset($me['gravatar_url']))
                    <img src="{{$me['gravatar_url']}}" alt="{{$me['name']}}" class="w-[80px] rounded-full mr-8">
                @endif
                <div>
                    <h2 class="h2 !mb-0">{{$me['name']}}</h2>
                    <p>{{$me['email']}}, {{$me['phone']}}</p>
                </div>
            </div>

            <hr class="border-grey mb-16 mt-8">

            <div class="flex flex-wrap -mx-8">
                <div class="w-full lg:w-1/2 px-8">
                    <h3 class="h3">
                        {{$translations['customer.billing_address.title']['text']}}
                    </h3>
                    @if($me['company_name'] && $me['company_id'] && $me['company_tax_id'] && $me['company_address'] && $me['company_city'] && $me['company_zip'] && $me['company_country'])
                        <p><b>{{$me['company_name']}}</b></p>
                        <p>
                            {{$me['company_address']}}
                            <br>
                            {{$me['company_city']}}, {{$me['company_zip']}}
                            <br>
                            {{$me['company_state']}} {{$me['company_country']}}
                        </p>
                        <p>
                            {{$translations['general.company_id']['text']}}: {{$me['company_id']}}
                            <br>
                            {{$translations['general.company_tax_id']['text']}}: {{$me['company_tax_id']}}
                            @if($me['company_vat_id'])
                                <br>
                                {{$translations['general.company_vat_id']['text']}}: {{$me['company_vat_id']}}
                            @endif
                        </p>
                    @else
                        <p><b>{{$me['name']}}</b></p>
                        <p>
                            {{$me['street']}} {{$me['house_number']}}
                            <br>
                            {{$me['city']}}, {{$me['zip']}}
                            <br>
                            {{$me['state']}} {{$me['country']}}
                        </p>
                    @endif
                </div>
                <div class="w-full lg:w-1/2 px-8">
                    <h3 class="h3">
                        {{$translations['customer.shipping_address.title']['text']}}
                    </h3>
                    @if($me['shipping_name'] && $me['shipping_street'] && $me['shipping_house_number'] && $me['shipping_city'] && $me['shipping_zip'] && $me['shipping_country'])
                        <p><b>{{$me['shipping_name']}}</b></p>
                        <p>
                            {{$me['shipping_street']}} {{$me['shipping_house_number']}}
                            <br>
                            {{$me['shipping_city']}}, {{$me['shipping_zip']}}
                            <br>
                            {{$me['shipping_state']}} {{$me['shipping_country']}}
                        </p>
                    @else
                        <p><b>{{$me['name']}}</b></p>
                        <p>
                            {{$me['street']}} {{$me['house_number']}}
                            <br>
                            {{$me['city']}}, {{$me['zip']}}
                            <br>
                            {{$me['state']}} {{$me['country']}}
                        </p>
                    @endif
                </div>
            </div>


        </div>
    </section>
@endsection

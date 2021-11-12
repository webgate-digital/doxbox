@extends('layout', ['ogTitle' => $translations['contact.title']['text'], 'robots' => 'index,follow'])

@section('content')
    <!-- Page title -->
    @php
        $breadcrumbs = [];
        $breadcrumbs[] = [
        'url' => url()->current(),
        'title' => $translations['contact.title']['text']
        ];
    @endphp
    @include('components.breadcrumbs')
    <!-- end: Page title -->

    <section class="section">
        <div class="container">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full lg:w-1/2 px-4">
                    <h1 class="h1">
                        {{$translations['contact.title']['text']}}
                    </h1>
                    <p>
                        <b>{{$translations['contact.address_title']['text']}}:</b>
                    </p>
                    <p>
                        {!! $catalogSettings['address']['value'] !!}
                        <a href="mailto:{{$catalogSettings['email']['value']}}" class="flex hover:underline" ><img
                                src="{{asset('images/icons/email_black_24dp.svg')}}"
                                alt="{{$catalogSettings['email']['value']}}" width="15" class="mr-4"
                            > {{$catalogSettings['email']['value']}}</a>
                        <a href="tel:{{$catalogSettings['phone']['value']}}" class="flex hover:underline"><img
                                src="{{asset('images/icons/call_black_24dp.svg')}}"
                                alt="{{$catalogSettings['phone']['value']}}" width="15" class="mr-4"
                            > {{$catalogSettings['phone']['value']}}</a>
                    </p>
                    <p>
                        <b>{{$translations['contact.billing_address_title']['text']}}:</b>
                    </p>
                    <p>
                        <b>{{$supplierSettings['name']['value']}}</b><br>
                        {{$supplierSettings['address']['value']}}<br>
                        {{$supplierSettings['zip']['value']}} {{$supplierSettings['city']['value']}}<br>
                        {{$translations['general.company_id']['text']}}: {{$supplierSettings['id']['value']}}<br>
                        {{$translations['general.company_tax_id']['text']}}: {{$supplierSettings['tax_id']['value']}}
                        <br>
                        {{$translations['general.company_vat_id']['text']}}: {{$supplierSettings['vat_id']['value']}}

                    </p>
                </div>
                <div class="w-full lg:w-1/2 px-4">
                    {!! $catalogSettings['map']['value'] !!}
                </div>
            </div>
        </div>
    </section>
@endsection

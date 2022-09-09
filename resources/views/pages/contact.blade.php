@extends('layout', ['ogTitle' => $translations['contact.title']['text'], 'robots' => 'index,follow'])

@section('content')
    <!-- Breadrumbs -->
    @php
        $breadcrumbs = [];
        $breadcrumbs[] = [
            'url' => url()->current(),
            'title' => $translations['contact.title']['text'],
        ];
    @endphp
    @include('components.breadcrumbs')
    <!-- end: Breadrumbs -->

    <section class="section section--contact">
        <div class="container">
            <div class="text-center">
                <h1 class="text-heading-xs mb-4">
                    {{ $translations['contact.title']['text'] }}
                </h1>
                <h2 class="text-subheading-xl">
                    {{ $translations['contact.subtitle']['text'] }}
                </h2>
            </div>
            <div class="lg:flex mt-32 items-center">
                <div class="lg:w-1/2">
                    <img src="/images/kontakt.jpeg" alt="Kontakt" class="drop-shadow-2xl">
                </div>
                <div class="lg:ml-[10%] flex-grow">
                    <h5 class="text-subheading-m">
                        {{ $translations['Zákaznícky servis']['text'] }}
                    </h5>
                    <p>
                        Email:
                        <a href="mailto:{{ $catalogSettings['email']['value'] }}" class="underline text-primary">
                            {{ $catalogSettings['email']['value'] }}
                        </a>
                        <br />
                        Tel:
                        <a href="tel:{{ $catalogSettings['phone']['value'] }}" class="underline text-primary">
                            {{ $catalogSettings['phone']['value'] }}
                        </a>
                    </p>

                    <h5 class="text-subheading-m mt-16">
                        {{ $translations['Predajňa']['text'] }}
                    </h5>
                    <p class="whitespace-pre-line">{{ $translations['contact.payment_options_text']['text'] }}</p>
                        {!! $translations['contact.opening_hours']['text'] !!}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="section section--contact bg-gray-5">
        <div class="container lg:flex">
            <div class="lg:w-1/2 flex-shrink-0">
                <h2 class="text-subheading-xl mb-4">
                    {{ $translations['Odpovede na najčastejšie otázky zákazníkov DOXBOX']['text'] }}
                </h2>
                <div class="mt-16">
                    @foreach ($faqItems as $item)
                        <h4 class="text-body-l text-gray-60 cursor-pointer inline-block accordion-trigger pb-2 @if (!$loop->first) mt-8 @endif" data-accordion-content-id="accordion-content-{{ $loop->index }}">
                            {{ $item['question'] }}
                        </h4>
                        <div id="accordion-content-{{ $loop->index }}" class="accordion-content">
                            <div class="accordion-content__inner pt-8">
                                {!! $item['answer'] !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="lg:ml-[10%]">
                <h2 class="text-subheading-xl">
                    {{ $translations['Firemné informácie']['text'] }}
                </h2>
                <h3 class="text-subheading-m mt-16">
                    {{ $translations['Fakturačná a korešpondenčná adresa']['text'] }}
                </h3>
                <p class="whitespace-pre-line">{{$supplierSettings['name']['value']}}
                    {{$supplierSettings['address']['value']}}
                    {{$supplierSettings['zip']['value']}}, {{$supplierSettings['city']['value']}}
                    {{$supplierSettings['country']['value']}}

                    IČO: {{$supplierSettings['id']['value']}} IČ, DPH: {{$supplierSettings['tax_id']['value']}}
                </p>
                <h3 class="text-subheading-m mt-16">
                    {{ $translations['Bankové spojenie']['text'] }}:
                </h3>
                <p class="whitespace-pre-line">{{$translations['Číslo účtu']['text']}}:
                    {{$translations['bank.account_number']['text']}}
                    IBAN: {{$translations['bank.iban']['text']}}
                    BIC/SWIFT: {{$translations['bank.swift']['text']}}
                    {{$translations['V prípade platby vašej objednávky uveďte ako variabilný symbol (VS) vaše číslo objednávky.']['text']}}
                </p>
            </div>
        </div>
    </section>
@endsection

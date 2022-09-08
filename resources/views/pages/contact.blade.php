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
                        Zákaznícky servis
                    </h5>
                    <p>
                        Email:
                        <a href="mailto:{{ $catalogSettings['email']['value'] }}" class="underline text-primary">
                            {{ $catalogSettings['email']['value'] }}
                        </a>
                        <br/>
                        Tel:
                        <a href="tel:{{ $catalogSettings['phone']['value'] }}">
                            {{ $catalogSettings['phone']['value'] }}
                        </a>
                    </p>

                    <h5 class="text-subheading-m mt-16">
                        Predajňa
                    </h5>
                    <p class="whitespace-pre-line">Platba: kartou, v hotovosti
                        Otváracie hodiny:

                        Po-Pi: od 10:00 do 18:00
                        So: od 10:00 do 16:00
                        Ne: zatvorené

                        Od 27.12. do 30.12. bude predajňa otvorená od 10:00 do 14:00.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="section section--contact bg-gray-5">
        <div class="container lg:flex">
            <div class="lg:w-1/2 flex-shrink-0">
                <h2 class="text-subheading-xl mb-4">
                    Odpovede na najčastejšie otázky zákazníkov DOXBOX
                </h2>
                <div>
                    <div>
                        <h4 class="text-body-xl text-gray-60 cursor-pointer">
                            Ako zabezpečiť bezpečnosť pri nákupe na internete?
                        </h4>
                        <div>
                            <h5 class="text-subheading-m">
                                Eshop
                            </h5>
                            <p>
                                Online nákup platobnou kartou, cez Paypal alebo si tovar môžete poslať nadobierku a zaplatiť tak pri prevzatí balíku od kuriéra. Zákazníci z Česka pri dobierke platia v českých korunách.
                            </p>
                            
                            <h5 class="text-subheading-m">
                                Predajňa v Bratislava
                            </h5>
                            <p>
                                V našej predajni môžete platiť platobnou kartou alebo v hotovosti.
                            </p>
                        </div>
                    </div>
                    @foreach ($faqItems as $item)
                        
                    @endforeach
                </div>
            </div>
            <div class="lg:ml-[10%]">
                <h2 class="text-subheading-xl">
                    Firemné informácie
                </h2>
                <h3 class="text-subheading-m mt-16">
                    Fakturačná a korešpondenčná adresa
                </h3>
                <p class="whitespace-pre-line">4 Laby s.r.o.
                    Vrakunská 39
                    821 06, Bratislava
                    Slovenská Republika
                    
                    IČO: 52192181 IČ, DPH: SK2120959918
                </p>
                <h3 class="text-subheading-m mt-16">
                    Bankové spojenie:
                </h3>
                <p class="whitespace-pre-line">Číslo účtu:
                    5154762908 / 0900
                    IBAN: SK29 0900 0000 0051 5476 2908
                    BIC/SWIFT: GIBASKBX
                    V prípade platby vašej objednávky uveďte ako variabilný symbol (VS) vaše číslo objednávky.
                </p>
            </div>
        </div>
    </section>
@endsection

@extends('layout', ['ogTitle' => $translations['contact.title']['text'], 'robots' => 'index,follow'])

@section('content')
    <!-- Breadrumbs -->
    @php
        $breadcrumbs = [];
        $breadcrumbs[] = [
        'url' => url()->current(),
        'title' => $translations['contact.title']['text']
        ];
    @endphp
    @include('components.breadcrumbs')
    <!-- end: Breadrumbs -->

    <section class="section">
        <div class="container">
            <div class="text-center">
                <h1 class="text-heading-xs mb-4">
                    {{$translations['contact.title']['text']}}
                </h1>
                <h2 class="text-subheading-xl">
                    {{$translations['contact.subtitle']['text']}}
                </h2>
            </div>
            <div class="lg:flex mt-32">
                <div>
                    <img src="/images/kontakt.jpeg" alt="Kontakt" class="w-full drop-shadow-2xl">
                </div>
                <div class="ml-[10%] flex-grow">
                    <h5 class="text-subheading-m mb-8">
                        Zákaznícky servis
                    </h5>
                    <p>
                        Email:
                        <a href="mailto:{{$catalogSettings['email']['value']}}" class="underline text-primary">
                            {{$catalogSettings['email']['value']}}
                        </a><br/>
                        Tel:
                        <a href="tel:{{$catalogSettings['phone']['value']}}">
                            {{$catalogSettings['phone']['value']}}
                        </a>
                    </p>

                    <h5 class="text-subheading-m mb-8 mt-16">
                        Predajňa
                    </h5>
                    <p>
                        Platba: kartou, v hotovosti<br/>
                        <br/>
                        Otváracie hodiny:<br/>
                        Po-Pi: od 10:00 do 18:00<br/>
                        So: od 10:00 do 16:00<br/>
                        Ne: zatvorené<br/>
                        <br/>
                        Od 27.12. do 30.12. bude predajňa otvorená od 10:00 do 14:00.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
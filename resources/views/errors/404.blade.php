@extends('layout', ['robots' => 'index,follow'])

@section('content')
    <main class="flex flex-col align-center items-center h-full">
        <h2 class="text-heading-2xl mb-8 lg:mb-0">
            {{$translations['404.title']['text']}}
        </h2>
        <div class="text-heading-2xs">
            {{$translations['404.subtitle']['text']}}
        </div>

        <a href="{{route(locale() . '.homepage')}}" class="button button--primary rounded-lg button--inline mt-6 lg:mt-10">
            {{$translations['404.go_to_homepage']['text']}}
        </a>
    </main>
@endsection

@section('css')
    <style>
        html .content {display:flex;flex-flow:column;align-items:center;justify-content:center}
    </style>
@endsection
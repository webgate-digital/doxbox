@extends('layout')

@section('content')
    @php
        $breadcrumbs = [];
        $breadcrumbs[] = [
            'url' => route(locale() . '.customer.profile'),
            'title' => $translations['customer.title']['text'],
        ];
        $breadcrumbs[] = [
            'url' => url()->current(),
            'title' => $translations['orders.title']['text'],
        ];
    @endphp
    @include('components.breadcrumbs')

    <section class="py-8 lg:p-20 bg-gray-5">
        <div class="container">
            <div class="flex items-center gap-8">
                <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="64" height="64" rx="32" fill="#D9D9D9" />
                    <path
                        d="M23.4974 23.5C23.4974 18.8056 27.3042 15 32 15C36.6958 15 40.5026 18.8056 40.5026 23.5C40.5026 28.1944 36.6958 32 32 32C27.3042 32 23.4974 28.1944 23.4974 23.5ZM48.8181 46.535L45.6041 40.092C44.1626 37.2161 41.2206 35.4001 38.0028 35.4H25.9972C22.7794 35.4001 19.8374 37.2161 18.3959 40.092L15.1819 46.535C14.9167 47.0612 14.9429 47.6871 15.2512 48.1893C15.5595 48.6915 16.106 48.9982 16.6954 49H47.3046C47.894 48.9982 48.4405 48.6915 48.7488 48.1893C49.0571 47.6871 49.0833 47.0612 48.8181 46.535Z"
                        fill="black" />
                </svg>
                <div>
                    <h2 class="text-heading-2xs">
                        {{ $me['name'] }}
                    </h2>
                    <form action="{{ route(locale() . '.logout') }}" method="post">
                        @csrf
                        <button type="submit" class="text-primary underline">
                            {{ $translations['auth.logout.cta']['text'] }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section class="py-8 lg:p-20">
        <div class="container">
            <div class="grid gap-2" style="grid-template-columns: 1fr 3fr;">
                <div>
                    <ul>
                        <li class="{{ request()->routeIs(locale() . '.orders.index') ? 'font-bold' : '' }}">Moje objednavky</li>
                    </ul>
                </div>
                <div>
                    <div class="text-subheading-m">
                        Moje objednávky
                    </div>
                    <table class="table-auto md:col-span-2 mt-8">
                        <thead>
                            <tr>
                                <th>
                                    Objednávka číslo
                                </th>
                                <th style="text-align:center">
                                    Dátum vytvorenia
                                </th>
                                <th style="text-align:center">
                                    Stav
                                </th>
                                <th style="text-align:right">
                                    Suma
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>
                                        <a
                                            href="{{ route(locale() . '.orders.show', ['orderUuid' => $order['uuid'], 'token' => $order['token']]) }}"
                                            class="text-primary underline"
                                        >
                                            {{ $order['code'] }}
                                        </a>
                                    </td>
                                    <td style="text-align:center">
                                        -
                                    </td>
                                    <td style="text-align:center">
                                        {{ $order['status'] }}
                                    </td>
                                    <td>
                                        {{ $order['vat_amount_formatted'] }}
                                    </td>
                                </tr>
                            @endforeach
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

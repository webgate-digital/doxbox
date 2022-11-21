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
            'title' => 'B2B'
            ];
    @endphp

    @if($errors->any() || request()->get('error', null))
        <div class="alert alert--danger">
            {{$translations['general.form_validation_error']['text']}}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert--danger">
            {{session('error')}}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert--success">
            {{session('success')}}
        </div>
    @endif

    @include('components.breadcrumbs')

    <section class="section min-h-screen">
        <div class="container">

            {{--            Page title--}}

            <div class="flex flex-wrap items-center justify-between">
                <h1 class="h1">
                    {{ $translations['general.b2b.title']['text'] }}
                </h1>
            </div>

            {{--            END Page title--}}


            <hr class="border-grey mb-16 mt-8">

            <div class="flex flex-wrap -mx-8">
                <div class="w-full lg:w-1/2 lg:mx-auto px-8">
                    @if(! $me['pending_b2b_approval'])
                        <form action="{{ route(locale() . '.b2b.store') }}" method="POST">
                            @csrf
                            <div class="form--group">
                                <label class="form--label"
                                       for="company_name">{{ $translations['general.company_name']['text'] }}  *</label>
                                <input type="text" name="company_name" id="company_name" class="form--input" value="{{old('company_name')}}">
                                @error('company_name')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="form--group">
                                    <label class="form--label"
                                           for="company_id">{{ $translations['general.company_id']['text'] }} *</label>
                                    <input type="text" name="company_id" id="company_id" class="form--input" value="{{old('company_id')}}">
                                    @error('company_id')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form--group">
                                    <label class="form--label"
                                           for="company_tax_id">{{ $translations['general.company_tax_id']['text'] }} *</label>
                                    <input type="text" name="company_tax_id" id="company_tax_id" class="form--input" value="{{old('company_tax_id')}}">
                                    @error('company_tax_id')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form--group">
                                    <label class="form--label"
                                           for="company_vat_id">{{ $translations['general.company_vat_id']['text'] }}</label>
                                    <input type="text" name="company_vat_id" id="company_vat_id" class="form--input" value="{{old('company_vat_id')}}">
                                    @error('company_vat_id')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="form--group">
                                    <label class="form--label"
                                           for="company_address">{{ $translations['general.address']['text'] }} *</label>
                                    <input type="text" name="company_address" id="company_address" class="form--input" value="{{old('company_address')}}">
                                    @error('company_address')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form--group">
                                    <label class="form--label"
                                           for="company_city">{{ $translations['general.city']['text'] }} *</label>
                                    <input type="text" name="company_city" id="company_city" class="form--input" value="{{old('company_city')}}">
                                    @error('company_city')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form--group">
                                    <label class="form--label"
                                           for="company_zip">{{ $translations['general.zip']['text'] }} *</label>
                                    <input type="text" name="company_zip" id="company_zip" class="form--input" value="{{old('company_zip')}}">
                                    @error('company_zip')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <div class="form--group">
                                    <label class="form--label"
                                           for="company_country">{{ $translations['general.country']['text'] }} *</label>
                                    <input type="text" name="company_country" id="company_country" class="form--input" value="{{old('company_country')}}">
                                    @error('company_country')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="button">{{ $translations['general.send_apply']['text'] }}</button>
                            </div>
                        </form>
                    @else()
                        <div class="bg-primary p-16 text-white text-center">
                            {{ $translations['general.your_request_has_been_sent']['text'] }}
                        </div>
                    @endif
                </div>
            </div>


        </div>
    </section>
@endsection

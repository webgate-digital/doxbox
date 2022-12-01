@extends('layout')

@section('content')

    @if(session('error'))
        <div class="alert alert--danger">
            {{session('error')}}
        </div>
    @endif

    <section class="section">
        <div class="container container--narrow">

            {{--            Page title--}}

            <h1 class="h1">
                {{$translations['auth.registration.title']['text']}}
            </h1>

            {{--            END Page title--}}

            {{--            register form--}}

            <form action="{{route(locale().'.register.post')}}" method="post">
                @csrf
                <div class="form--group">
                    <label class="form--label"
                           for="name">{{$translations['customer.form.name']['text']}} *</label>
                    <input type="text" name="name" id="name" class="form--input" value="{{old('name')}}">
                    @error('name')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form--group mt-4">
                    <label class="form--label"
                           for="email">E-mail *</label>
                    <input type="text" name="email" id="email" class="form--input" value="{{old('email')}}">
                    @error('email')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form--group mt-4">
                    <label class="form--label"
                           for="password">{{$translations['customer.form.password']['text']}} *</label>
                    <input type="password" name="password" id="password" class="form--input">
                    @error('password')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form--group mt-4">
                    <label class="form--label"
                           for="password_confirmation">{{$translations['customer.form.password_confirmation']['text']}} *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form--input">
                    @error('password_confirmation')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <button type="submit" class="button button--primary rounded-lg mt-4">{{$translations['auth.registration.cta']['text']}}</button>
            </form>
            <div class="mt-8">
                <div class="text-h4">
                    <a href="{{ route(locale() . '.login') }}" class="text-secondary">{{$translations['auth.login.cta']['text']}}</a>
                </div>
            </div>

            {{--            END register form--}}

        </div>
    </section>
@endsection

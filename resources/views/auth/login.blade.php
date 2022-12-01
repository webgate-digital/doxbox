@extends('layout')

@section('content')

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

    <section class="section">
        <div class="container container--narrow">

            {{--            Page title--}}

            <h1 class="h1">
                {{$translations['auth.login.title']['text']}}
            </h1>

            {{--            END Page title--}}

            {{--            Login form--}}

            <form action="{{route(locale().'.login.post')}}" method="post">
                @csrf
                <div class="form--group">
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
                <button type="submit" class="button button--primary rounded-lg mt-4">{{$translations['auth.login.cta']['text']}}</button>
            </form>
            <div class="mt-8 flex justify-between">
                <div class="text-h4">
                     <a href="{{ route(locale() . '.register') }}" class="text-secondary">{{$translations['auth.registration.cta']['text']}}</a>
                </div>
                <div class="text-h4">
                    <a href="{{ route(locale() . '.forgotten_password') }}" class="text-secondary">{{$translations['auth.forgot_your_password.cta']['text']}}</a>
                </div>
            </div>

            {{--            END Login form--}}

        </div>
    </section>
@endsection

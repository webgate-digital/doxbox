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
        <div class="container">

            {{--            Page title--}}

            <h1 class="h1">
                {{ $translations['auth.forgotten_password']['text'] }} 2/2
            </h1>

            {{--            END Page title--}}

            {{--            Password reset form--}}

            <form action="{{route(locale().'.request.password.reset')}}" method="post">
                @csrf
                <div class="form--group">
                    <label class="form--label"
                           for="email">E-mail *</label>
                    <input type="text" name="email" id="email" class="form--input" value="{{old('email', request()->get('email'))}}">
                    @error('email')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form--group mt-4">
                    <label class="form--label"
                           for="password">{{ $translations['customer.form.password']['text'] }} *</label>
                    <input type="password" name="password" id="password" class="form--input">
                    @error('password')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form--group mt-4">
                    <label class="form--label"
                           for="password_confirmation">{{ $translations['customer.form.password_confirmation']['text'] }} *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form--input">
                    @error('password_confirmation')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <input type="hidden" name="token" value="{{ request()->get('token') }}">
                <button type="submit" class="button button--primary rounded-lg mt-4">
                    {{ $translations['auth.reset_password.cta']['text'] }}
                </button>
            </form>

            {{--            END  Password reset form--}}

        </div>
    </section>
@endsection

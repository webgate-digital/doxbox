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
                {{ $translations['auth.forgotten_password']['text'] }} 1/2
            </h1>

            {{--            END Page title--}}

            {{--            Password reset form--}}

            <form action="{{route(locale().'.forgotten_password.post')}}" method="post">
                @csrf
                <div class="form--group">
                    <label class="form--label"
                           for="email">E-mail *</label>
                    <input type="text" name="email" id="email" class="form--input" value="{{old('email')}}">
                    @error('email')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <button type="submit" class="button button--primary rounded-lg mt-4">{{ $translations['auth.request_password_recovery.cta']['text'] }}</button>
            </form>

            {{--            END  Password reset form--}}

        </div>
    </section>
@endsection

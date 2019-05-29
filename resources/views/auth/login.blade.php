@extends('layouts.app')

@section('content')
    <div class="login-container">

        <form class="login" method="POST" action="{{ route('login') }}">
            @csrf

            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                   name="email" required autocomplete="email" autofocus placeholder="ایمیل">

            @error('email')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
            <input id="password" type="password" class="" name="password" required
                   autocomplete="current-password" placeholder="رمز عبور">

            @error('password')
            <span class="" role="">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror


            {{--<div class="form-check">--}}
            {{--<input class="" type="checkbox" name="remember"--}}
            {{--id="remember" {{ old('remember') ? 'checked' : '' }}>--}}

            {{--<label class="" for="remember">--}}
            {{--من رو --}}
            {{--</label>--}}
            {{--</div>--}}


            <button type="submit">
                ورود
            </button>

            {{--FOR PASSWORD RESET--}}
            {{--@if (Route::has('password.request'))--}}
            {{--<a class="btn btn-link" href="{{ route('password.request') }}">--}}
            {{--{{ __('Forgot Your Password?') }}--}}
            {{--</a>--}}
            {{--@endif--}}
        </form>

    </div>
@endsection



{{--Default Laravel codes for this page--}}
{{--<div class="card-body">--}}
{{--<form method="POST" action="{{ route('login') }}">--}}
{{--@csrf--}}
{{--<div class="form-group row">--}}
{{--<label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>--}}

{{--<div class="col-md-6">--}}
{{--<input id="email" type="email" class="form-control @error('email') is-invalid @enderror"--}}
{{--name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>--}}

{{--@error('email')--}}
{{--<span class="invalid-feedback" role="alert">--}}
{{--<strong>{{ $message }}</strong>--}}
{{--</span>--}}
{{--@enderror--}}
{{--</div>--}}
{{--</div>--}}

{{--<div class="form-group row">--}}
{{--<label for="password" class="">{{ __('Password') }}</label>--}}

{{--<div class="">--}}
{{--<input id="password" type="password" class="" name="password" required--}}
{{--autocomplete="current-password">--}}

{{--@error('password')--}}
{{--<span class="" role="">--}}
{{--<strong>{{ $message }}</strong>--}}
{{--</span>--}}
{{--@enderror--}}
{{--</div>--}}
{{--</div>--}}

{{--<div class="form-check">--}}
{{--<input class="" type="checkbox" name="remember"--}}
{{--id="remember" {{ old('remember') ? 'checked' : '' }}>--}}

{{--<label class="" for="remember">--}}
{{--{{ __('Remember Me') }}--}}
{{--</label>--}}
{{--</div>--}}


{{--<button type="submit" class="btn btn-primary">--}}
{{--{{ __('Login') }}--}}
{{--</button>--}}

{{--@if (Route::has('password.request'))--}}
{{--<a class="btn btn-link" href="{{ route('password.request') }}">--}}
{{--{{ __('Forgot Your Password?') }}--}}
{{--</a>--}}
{{--@endif--}}
{{--</form>--}}

{{--</div>--}}
{{--Default Laravel codes for this page--}}
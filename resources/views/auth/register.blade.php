@extends('layouts.app')

@section('content')
    <div class="login-container">

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                   value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="نام">

            @error('name')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror


            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="ایمیل">

            @error('email')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror

            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="new-password" placeholder="رمز">

            @error('password')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror


            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                   required autocomplete="new-password" placeholder="تایید رمز">


            <button type="submit" class="btn btn-primary">
                ثبت
            </button>
        </form>
    </div>
@endsection

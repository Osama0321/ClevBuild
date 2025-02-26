@extends('layout.app', ['sidebar' => false, 'topbar' => false, 'body_class' => 'authPage'])
@section('content')
<section class="loginFormSection">
	<div class="d-lg-flex half">
	   	<div class="bg order-1 order-md-1">
	    	<img src="{{ asset('images/bg_1.jpg') }}">
	   	</div>
	    <div class="contents order-1 order-md-1">
	    	<div class="container">
		        <div class="row align-items-center justify-content-center">
                    <div class="col-md-12 loginFormCol">
                        <div class="loginLogo">
                            <img src="{{ asset('images/dashlyght-logo-300x75.png') }}">
                        </div>

                        <div class="card">
                            <div class="card-header">{{ __('Reset Password') }}</div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('password.updates') }}">
                                    @csrf

                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="row mb-3">
                                        <label for="email"  class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" style="pointer-events: none;     background-color: #ffffff;" readonly type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                        </div>
                                    </div>

                                    <div class="row mb-0">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Reset Password') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
@endsection

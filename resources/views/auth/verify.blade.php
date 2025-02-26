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
                    <div class="col-md-6 loginFormCol">
                        <div class="loginLogo">
                            <img src="{{ asset('images/dashlyght-logo-300x75.png') }}">
                        </div>

                        <div class="card-header" style="margin-top: 15px;">{{ __('Verify Your Email Address') }}</div>

                        <div class="card-body">
                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    {{ __('A fresh verification link has been sent to your email address.') }}
                                </div>
                            @endif

                            {{ __('Before proceeding, please check your email for a verification link.') }}
                            {{ __('If you did not receive the email') }},
                            <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                            </form>


                        </div>
                    </div>
                </div>
	      	</div>
	    </div>
	</div>
</section>
@endsection
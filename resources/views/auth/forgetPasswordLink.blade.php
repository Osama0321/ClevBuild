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
		            	<p class="mb-4 mt-5">Welcome back! Please login to your account.</p>

		            	@if(session('msg'))
	                  <div class="alert alert-{{session('msg_type')}}">
	                  	{{session('msg')}}                                            
	                  </div> 
	                @endif
	                @error('authenticate')
	                	<div class="alert alert-danger">
	                  	{{ $message }}
	                  </div>
                  @enderror

		            	<form action="{{ route('reset.password.post') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group row">
                        <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                        <div class="col-md-6">
                            <input type="text" id="email_address"  class="form-control" name="email" required autofocus>
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                        <div class="col-md-6">
                            <input type="password" id="password" class="form-control" name="password" required autofocus>
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                        <div class="col-md-6">
                            <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required autofocus>
                            @if ($errors->has('password_confirmation'))
                                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Reset Password
                        </button>
                    </div>
                      </form>
		          	</div>
		        </div>
	      	</div>
	    </div>
	</div>
</section>
@endsection
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
		        	<div class="col-md-10 loginFormCol">
		            	<div class="loginLogo">
		            		<img src="{{ asset('images/dashlyght-logo-300x75.png') }}">
		            	</div>
		            	<p class="mb-4 mt-5">Hello there! Reset your account.</p>

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

		            	<form action="{{ route('password.email.post') }}" method="POST">
                  	@csrf
                    <div class="form-group row">
                    <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                      <div class="col-md-6">
                        <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                        @if ($errors->has('email'))
                        	<span class="text-danger">{{ $errors->first('email') }}</span>
                      	@endif
                      </div>
                    </div>
                    <div class="text-center mt-4">
                      <button type="submit" class="btnStyle" style="height:44px; margin-left: 80px;">
                          Reset
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
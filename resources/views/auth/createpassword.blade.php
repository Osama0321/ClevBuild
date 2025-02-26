
@extends('layouts.admin.app', ['title' => 'Create Password'])
<style>
  .layout-fixed .main-sidebar{
    width: 20%;
  }

  .loginFormSection{
    width: 80%;
    float:right;
  }

  @media (max-width:767px){
    .loginFormSection{
        width: 100%;
    }
  }
</style>
@section('content')
<section class="loginFormSection">
	<div class="d-lg-flex half" style="display:block !important">
	   	<div class="bg order-1 order-md-1">
	    	<!-- <img src="{{ asset('images/bg_1.jpg') }}"> -->
	   	</div>
	    <div class="contents order-1 order-md-1">
	    	<div class="container">
		        <div class="row align-items-center justify-content-center">
                    <div class="col-md-12 loginFormCol">
                        <div class="loginLogo">
                            <!-- <img src="{{ asset('images/dashlyght-logo-300x75.png') }}"> -->
                        </div>
                        <div class="card mt-4">
                        <div class="card-header">Create Password</div>
                        <div class="card-body">
        
                            <form action="{{ route('reset.password.post') }}" method="POST">
                                @csrf
                                
        
                                <div class="form-group row">
                                    <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                    <div class="col-md-6">
                                        <input type="text" style="pointer-events: none;     background-color: #ffffff;" readonly id="email_address" class="form-control" name="email" value="{{$email}}" readonly required autofocus>
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
  
                                <div class="col-md-6 offset-md-4" style="margin-top: 15px;">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
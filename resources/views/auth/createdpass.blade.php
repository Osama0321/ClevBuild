@extends('layouts.admin.app', ['title' => 'Add Manager'])

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
                        <div class="card-header">Created Password</div>
                        <div class="card-body">
                            <h5>Password SuccessFully Created. Please <a href="{{ env('APP_URL') }}">Login!</a></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
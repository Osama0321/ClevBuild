@extends('layout.app', ['sidebar' => false, 'topbar' => false, 'body_class' => 'authPage'])
@section('content')
<section class="registerFormSection">
    <div class="d-lg-flex half">
        <div class="bg order-1 order-md-1">
            <img src="{{ asset('images/bg_1.jpg') }}">
        </div>
        <div class="contents order-1 order-md-1">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-6 registerFormCol">
                        <div class="loginLogo">
                            <img src="{{ asset('images/dashlyght-logo-300x75.png') }}">
                        </div>
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
                        <div class="generalInfoStep">
                            <p class="mb-4 mt-5 text-muted">Create an Account</p>

                            <form>
                                @csrf
                                <input type="hidden" class="form-control @error('first_name') is-invalid @enderror" name="action" value="generalInfo" required>
                                <div class="form-group groupSet">
                                    <div>
                                        <input id="first_name" type="text" placeholder="First Name" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required>

                                        <span role="alert">
                                            <p class="first_name text-danger"></p>
                                        </span>

                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group groupSet">
                                    <div>
                                        <input id="last_name" type="text" placeholder="Last Name" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required>

                                        <span role="alert">
                                            <p class="last_name text-danger"></p>
                                        </span>

                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group groupSet">
                                    <div>
                                        <input id="email" type="email" placeholder="Email Address" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>

                                        <span role="alert">
                                            <p class="email text-danger"></p>
                                        </span>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group groupSet">
                                    <div>
                                        <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required>

                                        <span role="alert">
                                            <p class="password text-danger"></p>
                                        </span>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group groupSet">
                                    <div>
                                        <input id="phone_no" type="text" placeholder="Phone no" class="form-control @error('phone_no') is-invalid @enderror" name="phone_no" value="{{ old('phone_no') }}" required>

                                        <span role="alert">
                                            <p class="phone_no text-danger"></p>
                                        </span>

                                        @error('phone_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group groupSet">
                                    <div>
                                        <input id="dealership" type="text" placeholder="Dealership" class="form-control @error('dealership') is-invalid @enderror" name="dealership" value="{{ old('dealership') }}" required>

                                        <span role="alert">
                                            <p class="dealership text-danger"></p>
                                        </span>

                                        @error('dealership')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group groupSet">
                                    <label class="control control--checkbox mb-0"><span class="caption">{{ __('I agree to the terms and conditions.') }}</span>
                                        <input type="checkbox" value="1" id="terms_condition" name="terms_condition">
                                        <div class="control__indicator"></div>
                                    </label>

                                    <span role="alert">
                                        <p class="terms_condition text-danger"></p>
                                    </span>

                                    @error('terms_condition')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="paymentStepBtn">
                                    <a href="javascript:void(0)" id="generalSubmit" class="btnStyle">Next Step</a>
                                </div>
                            </form>
                        </div>

                        <div class="PaymentStep">
                            <form method="POST" action="{{ route('register') }}">
                                <p class="mb-4 mt-5">Billing Information</p>
                                <div class="bilingText">
                                    <span>You will be billed $99 per month plus any applicable taxes.</span>
                                    <p>Only $99 per month per dealership Unlimited Manager and Sales Accounts Cancel anytime.</p>
                                </div>
                                <div class="stripFormMain">
                                    <span>Pay with your credit card via Stripe.</span>
                                    <div class="stripLogos">
                                        <img src="{{asset('images/Credit-Card-Icons.png')}}">
                                    </div>
                                    <div class="stripForm">
                                        <div class="form-group">
                                            <label for="cardNumber">Card Number*</label>
                                            <input type="text" class="form-control" placeholder="1234 1234 1234 1234" id="cardNumber">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="expiryDate">Expiry Date*</label>
                                                    <input type="text" class="form-control" placeholder="MM / YY" id="expiryDate">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="cardCode">Card Code (CVC)*</label>
                                                    <input type="text" class="form-control" placeholder="CVC" id="cardCode">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex mb-3 align-items-center rememberForgot">
                                            <label class="control control--checkbox mb-0"><span class="caption">I agree to the terms and conditions.</span>
                                                <input type="checkbox">
                                                <div class="control__indicator"></div>
                                            </label>
                                        </div>
                                        <a href="javascript:void(0)" class="btnStyle finishingStepBtn">Next Step</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="finishingStep">
                            <form method="POST" action="{{ route('register') }}">
                                <p class="mb-4 mt-5">Dealership Information</p>
                                <div class="form-group groupSet">
                                    <label for="role_id" class="col-md-4 col-form-label text-md-end">{{ __('Role') }}</label>

                                    <div>
                                        <select name="role_id" class="form-control">
                                            <option value="1">Role 1</option>
                                            <option value="2">Role 2</option>
                                        </select>

                                        @error('role_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group groupSet">
                                    <label for="profile_picture" class="col-md-4 col-form-label text-md-end">{{ __('Profile Picture') }}</label>

                                    <div>
                                        <div class="form-group">
                                            <div class="fileUpload" id="fileOpne">
                                                <span>Upload Logo</span>
                                                <div class="tooltip">
                                                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                                                    <span class="tooltiptext">Upload your dealerships logo to be used throughout Dashlyght. We’ll add your brand to emails and use it as the default picture for your team members until they upload their own.</span>
                                                </div>
                                                <input type="file" name="logo" id="logo" class="fileUploadInput">
                                            </div>
                                            <img class="imageToDisplay">
                                        </div>

                                        @error('profile_picture')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group groupSet">
                                    <label for="user_type_id" class="col-md-4 col-form-label text-md-end">{{ __('User Type') }}</label>

                                    <select class="form-control" name="user_type_id">
                                        <option disabled selected>--Please Select--</option>
                                        <option value="1">Signup</option>
                                        <option value="2">Created</option>
                                    </select>

                                    @error('user_type_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group groupSet">
                                    <label for="created_by_id" class="col-md-4 col-form-label text-md-end">{{ __('Created By') }}</label>

                                    <div>
                                        <input id="created_by_id" type="text" class="form-control @error('created_by_id') is-invalid @enderror" name="created_by_id" value="{{ old('created_by_id') }}" required>

                                        @error('created_by_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-md-6">
                                        <button type="submit" class="btnStyle registerBtn">
                                            {{ __('Register') }}
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script type="text/javascript">

         // $(".paymentPrevBtn").click(function(){
         //    $(".generalInfoStep").hide();
         //    $(".finishingStep").hide();
         //    $(".PaymentStep").fadeIn(1000);
         //  });

         // $(".generalPrevBtn").click(function(){
         //    $(".finishingStep").hide();
         //    $(".PaymentStep").hide();
         //    $(".generalInfoStep").fadeIn(1000);
         //  });

         //  $(".finishingStepBtn").click(function(){
         //    $(".generalInfoStep").hide();
         //    $(".PaymentStep").hide();
         //    $(".finishingStep").fadeIn(1000);
         //  });

            var readURL = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('.imageToDisplay').attr('src', e.target.result);
                    }
            
                    reader.readAsDataURL(input.files[0]);
                }
            }
            

            $(".fileUploadInput").on('change', function(){
                readURL(this);
            });
            
            $(".fileUpload").on('click', function() {
               $(".fileUploadInput").click();
            });
                     
        $('#generalSubmit').click(function(){

            let first_name      = $('#first_name').val();
            let last_name       = $('#last_name').val();
            let email           = $('#email').val();
            let password        = $('#password').val();
            let phone_no        = $('#phone_no').val();
            let dealership      = $('#dealership').val();
            let terms_condition = $('#terms_condition').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
              url: "{{ route('register') }}",
              type:"POST",
              data:{
                "_token": "{{ csrf_token() }}",
                action     : "generalInfo",
                first_name : first_name,
                last_name  : last_name,
                email      : email,
                password   : password,
                phone_no   : phone_no,
                dealership : dealership,
                terms_condition : terms_condition,
              },

              success:function(response){
                    if (response.status == "success") {
                        window.location.href = "http://localhost:8000/"+response.url;
                    }
                },
                error:function(response){
                    var errors = response.responseJSON.errors;
                    if (errors.first_name) {
                        $('.first_name').html(errors.first_name);
                    }
                    if (errors.last_name) {
                        $('.last_name').html(errors.last_name);
                    }
                    if (errors.email) {
                        $('.email').html(errors.email);
                    }
                    if (errors.password) {
                        $('.password').html(errors.password);
                    }
                    if (errors.phone_no) {
                        $('.phone_no').html(errors.phone_no);
                    }
                    if (errors.dealership) {
                        $('.dealership').html(errors.dealership);
                    }
                    if (errors.terms_condition) {
                        $('.terms_condition').html(errors.terms_condition);
                    }
                }
            });
        });
    </script>
</section>
@endsection
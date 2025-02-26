@extends('layouts.admin.app', ['title' => 'Add Company'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush
<style>
    label.required::after {
        content: " *";
        color: #ff0000;
    }
    /* Overlay styles */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 9999;
    }
    /* Loader styles */
    .loader-container {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        z-index: 10000;
        display: none;
    }
</style>
@section('content')
<section>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Company</h3>
                    </div>
                    <form id="form" action="{{route('companies.update', ['company' => $company->id] )}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" value="{{$company->id}}">
                        <input type="hidden" name="old_email" value="{{$company->email}}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="First Name" class="required">First Name</label>
                                        <input field_type="text" error_val="The first name field is required." type="text" name="first_name" class="form-control form_validation" id="FirstName" placeholder="Enter First Name" value="{{ (old('first_name')) ? old('first_name') : $company->first_name }}">
                                        <div class="text-danger">@error('first_name') {{$message}} @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Email address" class="required">Email address</label>
                                        <input field_type="email" type="email" name="email" class="form-control form_validation" id="EmailAddress" placeholder="Enter email" value="{{ (old('email')) ? old('email') : $company->email }}">
                                        <div class="text-danger">@error('email') {{$message}} @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Password">Reset Password</label>
                                        <input type="password" name="password" class="form-control" id="Password" placeholder="Enter Password">
                                        @error('pass')
                                            <div class="text-danger">
                                                {{$message}}                                            
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Last Name">Last Name</label>
                                        <input type="text" name="last_name" class="form-control" id="LastName" placeholder="Enter Last Name" value="{{ (old('last_name')) ? old('last_name') : $company->last_name }}">
                                        @error('last_name')
                                            <div class="text-danger">
                                                {{$message}}                                            
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="Phone No">Phone No.</label>
                                        <input type="text" name="phone_no" class="form-control" id="PhoneNo" placeholder="Enter Phone" value="{{ (old('phone_no')) ? old('phone_no') : $company->phone_no }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="Address">Address</label>
                                        <textarea class="form-control" name="address" id="Address" placeholder="Enter Address" rows="1">{{ (old('address')) ? old('address') : $company->address }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-footer" style="text-align:center; background: none;">
                        <button class="btn btn-primary submit">Submit</button>
                        <a href="{{ route('companies') }}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Loader and Overlay -->
    <div class="overlay" id="overlay"></div>
    <div class="loader-container" id="loader">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('Admin/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
@endpush
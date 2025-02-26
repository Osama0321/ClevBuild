@extends('layouts.admin.app', ['title' => 'Add Follower'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    .has-error{
        color: #ff0000;
    }
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
@endpush

@section('content')
<section>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-end">
                    <a href="{{ url()->previous() }}" class="btn btn-default mt-2 mb-2">
                        <i class="fas fa-arrow-left fa-xs"></i> Back
                    </a>
                </div>
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Add Followers</h3>
                    </div>
                    <form id="form" action="{{route('followers.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="First Name" class="required">First Name</label>
                                        <input field_type="text" error_val="The first name field is required." type="text" name="first_name" class="form-control form_validation" id="FirstName" placeholder="Enter First Name" value="{{ old('first_name') }}">
                                        <div class="text-danger">@error('first_name')The first name field is required. @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Email address" class="required">Email address</label>
                                        <input field_type="email" type="email" name="email" class="form-control form_validation" id="EmailAddress" placeholder="Enter email" value="{{ old('email') }}">
                                        <div class="text-danger">@error('email') {{$message}} @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Address">Address</label>
                                        <textarea class="form-control" name="address" id="Address" placeholder="Enter Address" rows="1">{{ old('address') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Last Name">Last Name</label>
                                        <input type="text" name="last_name" class="form-control" id="LastName" placeholder="Enter Last Name" value="{{ old('last_name') }}">
                                        @error('last_name')
                                            <div class="text-danger">
                                                {{$message}}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="Phone No">Phone No.</label>
                                        <input field_type="text" type="text" name="phone_no" class="form-control" id="PhoneNo" placeholder="Enter Phone" value="{{ old('phone_no') }}" oninput="validatePhoneNumber(this)">
                                    </div>
                                    @if(Auth::user()->isAn('admin'))
                                        <div class="form-group">
                                            <label for="company" class="required" >Company</label>
                                            <select id="companyId" error_val="Please select a company. Company is required." field_type="select" name="company" class="select2 companies form-control form_validation" style="width: 100%;">
                                            <option value="">Select Company</option>
                                            @foreach($companies as $key => $value)
                                                <option {{ old('company') == $value->id  || (Auth::user()->id == $value->id) ?  'selected':'' }} value="{{ $value->id }}">{{$value->first_name}} {{$value->last_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('company') {{$message}} @endif</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-footer" style="text-align:center; background: none;">
                        <button class="btn btn-primary submit">Submit</button>
                        <a href="{{ route('managers') }}" class="btn btn-default">Cancel</a>
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
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('Admin/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
@endpush

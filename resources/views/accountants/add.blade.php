@extends('layouts.admin.app', ['title' => 'Add Accountant'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
<section>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
            <div class="card card-primary">
<div class="card-header">
<h3 class="card-title">Add Accountant</h3>
</div>

<form action="{{route('accountants.store')}}" method="POST" autocomplete="off">
    @csrf
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="First Name">First Name</label>
                    <input type="text" name="first_name" class="form-control" id="FirstName" placeholder="Enter First Name" value="{{ old('first_name') }}">
                    @error('first_name')
                        <div class="text-danger">
                            {{$message}}                                            
                        </div>
                    @endif
                </div>

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
                    <label for="Email address">Email address</label>
                    <input type="email" name="email" class="form-control" id="EmailAddress" placeholder="Enter email" value="{{ old('email') }}">
                    @error('email')
                        <div class="text-danger">
                            {{$message}}                                            
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="Address">Address</label>
                    <textarea class="form-control" name="address" id="Address" placeholder="Enter Address">{{ old('address') }}</textarea>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="country">Country</label>
                    <select id="countryId" name="country" class="select2 countries form-control" style="width: 100%;">
                        <option value="">Select Country</option>
                        @foreach($countries as $key => $value)
                            <option {{ old('country') == $value->country_id ?  'selected':'' }} value="{{ $value->country_id }}">{{$value->country_name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="City">City</label>
                    <select id="CityId" name="city" class="select2 cities form-control">
                        <option value="">Select City</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Phone No">Phone No.</label>
                    <input type="text" name="phone_no" class="form-control" id="PhoneNo" placeholder="Enter Phone" value="{{ old('phone_no') }}">
                </div>


               
            </div>
        </div>

        <div class="card-footer" style="text-align:center; background: none;">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('accountants') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('Admin/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
@endpush
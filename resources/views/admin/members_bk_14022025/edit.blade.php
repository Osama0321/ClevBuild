@extends('layouts.admin.app', ['title' => 'Edit Member'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    label.required::after {
        content: " *";
        color: #ff0000;
    }
    /* Overlay styles */
    .card-body>.loading-img, .card-body>.overlay {
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
    .card-body>.loader-container {
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
            <div class="card card-primary">
        <div class="card-header">
        <h3 class="card-title">Edit Member</h3>
    </div>

<form action="{{route('members.update', ['member' => $member->id] )}}" method="POST" autocomplete="off">
    @csrf
    <input type="hidden" name="id" value="{{$member->id}}">
    <input type="hidden" name="old_email" value="{{$member->email}}">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="First Name">First Name</label>
                    <input type="text" name="first_name" class="form-control" id="FirstName" placeholder="Enter First Name" value="{{ (old('first_name')) ? old('first_name') : $member->first_name }}">
                    @error('first_name')
                        <div class="text-danger">
                            {{$message}}                                            
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="Email address">Email address</label>
                    <input type="email" name="email" class="form-control" id="EmailAddress" placeholder="Enter email" value="{{ (old('email')) ? old('email') : $member->email }}">
                    @error('email')
                        <div class="text-danger">
                            {{$message}}                                            
                        </div>
                    @endif
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
                @if(Auth::user()->id == 1)
                    <div class="form-group">
                        <label for="company">Company</label>
                        <select id="companyId" error_val="Please select a company. Company is required." field_type="select" name="company" class="select2 companies form-control" style="width: 100%;">
                            <option value="">Select Company</option>
                            @foreach($companies as $key => $value)
                                <option {{($value->id == old('company', ($member->parent_id ? ($member->company ? $member->company->id : ($member->manager ? ($member->manager->company ? $member->manager->company->id : '' ) : '')) : ''))) ? 'selected' : ''}} value="{{ $value->id }}">{{$value->first_name}} {{$value->last_name}}</option>
                            @endforeach
                        </select>
                        <div class="text-danger">@error('company') {{$message}} @endif</div>
                    </div>
                @endif    
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="Last Name">Last Name</label>
                    <input type="text" name="last_name" class="form-control" id="LastName" placeholder="Enter Last Name" value="{{ (old('last_name')) ? old('last_name') : $member->last_name }}">
                    @error('last_name')
                        <div class="text-danger">
                            {{$message}}                                            
                        </div>
                    @endif
                </div>

                <div class="form-group  d-none">
                    <label for="country">Country</label>
                    <select id="countryId" name="country" class="select2 countries form-control">
                        <option value="">Select Country</option>
                        @foreach($countries as $key => $value)
                            <option {{ $value->country_id == old('country', $member->country) ? 'selected':''}} value="{{ $value->country_id }}">{{ $value->country_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group  d-none">
                    <label for="City">City</label>
                    <select id="City" name="city" class="select2 cities form-control">
                        <option value="">Select City</option>
                        @foreach($countries[($member->country)-1]->cities as $key => $value)
                            <option {{ $value->city_id == old('city', $member->city) ? 'selected':'' }} value="{{ $value->city_id }}">{{ $value->city_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="Phone No">Phone No.</label>
                    <input type="text" name="phone_no" class="form-control" id="PhoneNo" placeholder="Enter Phone" value="{{ (old('phone_no')) ? old('phone_no') : $member->phone_no }}">
                </div>
                <div class="form-group">
                    <label for="Address">Address</label>
                    <textarea class="form-control" name="address" id="Address" placeholder="Enter Address" rows="1">{{ (old('address')) ? old('address') : $member->address }}</textarea>
                </div>
                <div class="form-group">
                    <label for="manager">Manager</label>
                    <select id="managerId" error_val="Please select a manager. Manager is required." field_type="select" name="manager" class="select2 managers form-control" style="width: 100%;">
                        <option value="">Select Manager</option>
                        @if($member->parent_id)
                            @if($member->company)
                                    @if($member->company->managers)
                                        @foreach($member->company->managers as $key => $value)
                                            <option {{($value->id == old('manager', $member->parent_id)) ? 'selected' : ''}} value="{{ $value->id }}">{{$value->first_name}} {{$value->last_name}}</option>
                                        @endforeach
                                    @endif  
                                @endphp
                            @else
                                @if($member->manager)
                                    @if($member->manager->company)
                                        @if($member->manager->company->managers)
                                            @foreach($member->manager->company->managers as $key => $value)
                                            <option {{($value->id == old('manager', $member->parent_id)) ? 'selected' : ''}} value="{{ $value->id }}">{{$value->first_name}} {{$value->last_name}}</option>
                                            @endforeach
                                        @endif
                                    @endif
                                @endif
                            @endif
                        @endif
                    </select>
                    <div class="text-danger">@error('manager') {{$message}} @endif</div>
                </div>
            </div>
        </div>
        <div class="card-footer" style="text-align:center; background: none;">
            <button type="submit" class="btn btn-primary submit">Submit</button>
            <a href="{{ route('members') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
	<!-- Loader and Overlay -->
	<div class="overlay" id="overlay"></div>
	<div class="loader-container" id="loader">
		<div class="spinner-border text-primary" role="status">
			<span class="sr-only">Loading...</span>
		</div>
	</div>	
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
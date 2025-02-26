@extends('layouts.admin.app', ['title' => 'Add Manager'])

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
                        <h3 class="card-title">Edit Manager</h3>
                    </div>

                    <form id="form" action="{{route('managers.update', ['manager' => $manager->id] )}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" value="{{$manager->id}}">
                        <input type="hidden" name="old_email" value="{{$manager->email}}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="First Name">First Name</label>
                                        <input field_type="text" error_val="The first name field is required." type="text" name="first_name" class="form-control form_validation" id="FirstName" placeholder="Enter First Name" value="{{ (old('first_name')) ? old('first_name') : $manager->first_name }}">
                                        <div class="text-danger">@error('first_name')The first name field is required. @endif</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="Email address">Email address</label>
                                        <input field_type="email" type="email" name="email" class="form-control form_validation" id="EmailAddress" placeholder="Enter email" value="{{ (old('email')) ? old('email') : $manager->email }}">
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
                                    @if(Auth::user()->id == 1)
                                        <div class="form-group">
                                            <label for="company">Company</label>
                                            <select id="companyId" error_val="Please select a company. Company is required." field_type="select" name="company" class="select2 companies form-control" style="width: 100%;">
                                                <option value="">Select Company</option>
                                                @foreach($companies as $key => $value)
                                                    <option {{($value->id == old('company', $manager->parent_id)) ? 'selected' : ''}} value="{{ $value->id }}">{{$value->first_name}} {{$value->last_name}}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger">@error('company') {{$message}} @endif</div>
                                        </div>
                                    @endif    
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group d-none">
                                        <label for="country">Country</label>
                                        <select id="countryId" error_val="Please select a country. Country is required." field_type="select" name="country" class="select2 countries form-control form_validation">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $key => $value)
                                                <option {{($value->country_id == old('country', $manager->country)) ? 'selected' : ''}} value="{{ $value->country_id }}">{{ $value->country_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('country')Please select a country. Country is required. @endif</div>
                                    </div>

                                    <div class="form-group d-none">
                                        <label for="City">City</label>
                                        <select id="CityId" error_val="Please select a city. City is required." field_type="select" name="city" class="select2 cities form-control form_validation">
                                            <option value="">Select City</option>
                                            @foreach($countries[(old('country', $manager->country))-1]->cities as $key => $value)
                                                <option {{($value->city_id == old('city', $manager->city)) ? 'selected' : ''}} value="{{ $value->city_id }}">{{ $value->city_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('city')Please select a city. City is required. @endif</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="Last Name">Last Name</label>
                                        <input type="text" name="last_name" class="form-control" id="LastName" placeholder="Enter Last Name" value="{{ (old('last_name')) ? old('last_name') : $manager->last_name }}">
                                        @error('last_name')
                                            <div class="text-danger">
                                                {{$message}}                                            
                                            </div>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        <label for="Phone No">Phone No.</label>
                                        <input type="text" name="phone_no" class="form-control" id="PhoneNo" placeholder="Enter Phone" value="{{ (old('phone_no')) ? old('phone_no') : $manager->phone_no }}">
                                    </div>


                                    <div class="form-group">
                                        <label for="Address">Address</label>
                                        <textarea class="form-control" name="address" id="Address" placeholder="Enter Address" rows="1">{{ (old('address')) ? old('address') : $manager->address }}</textarea>
                                    </div>
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
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('Admin/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
@endpush
@extends('layouts.admin.app', ['title' => 'Edit Accountant'])

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
        <h3 class="card-title">Edit Accountant</h3>
    </div>

<form action="{{route('accountants.update', ['accountant' => $accountant->id] )}}" method="POST" autocomplete="off">
    @csrf
    <input type="hidden" name="id" value="{{$accountant->id}}">
    <input type="hidden" name="old_email" value="{{$accountant->email}}">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="First Name">First Name</label>
                    <input type="text" name="first_name" class="form-control" id="FirstName" placeholder="Enter First Name" value="{{ (old('first_name')) ? old('first_name') : $accountant->first_name }}">
                    @error('first_name')
                        <div class="text-danger">
                            {{$message}}                                            
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="Last Name">Last Name</label>
                    <input type="text" name="last_name" class="form-control" id="LastName" placeholder="Enter Last Name" value="{{ (old('last_name')) ? old('last_name') : $accountant->last_name }}">
                    @error('last_name')
                        <div class="text-danger">
                            {{$message}}                                            
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="Email address">Email address</label>
                    <input type="email" name="email" class="form-control" id="EmailAddress" placeholder="Enter email" value="{{ (old('email')) ? old('email') : $accountant->email }}">
                    @error('email')
                        <div class="text-danger">
                            {{$message}}                                            
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="Password">Password</label>
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
                    <label for="country">Country</label>
                    <select id="countryId" name="country" class="select2 countries form-control">
                        <option value="">Select Country</option>
                        @foreach($countries as $key => $value)
                            <option {{ $value->country_id == $accountant->country ? 'selected':''}} value="{{ $value->country_id }}">{{ $value->country_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="City">City</label>
                    <select id="City" name="city" class="select2 cities form-control">
                        <option value="">Select City</option>
                        @foreach($countries[($accountant->country)-1]->cities as $key => $value)
                            <option {{ $value->city_id == $accountant->city ? 'selected':'' }} value="{{ $value->city_id }}">{{ $value->city_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="Phone No">Phone No.</label>
                    <input type="text" name="phone_no" class="form-control" id="PhoneNo" placeholder="Enter Phone" value="{{ (old('phone_no')) ? old('phone_no') : $accountant->phone_no }}">
                </div>


                <div class="form-group">
                    <label for="Address">Address</label>
                    <textarea class="form-control" name="address" id="Address" placeholder="Enter Address">{{ (old('address')) ? old('address') : $accountant->address }}</textarea>
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
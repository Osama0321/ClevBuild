@extends('layouts.admin.app', ['title' => 'Add Member'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    label.required::after {
        content: " *";
        color: #ff0000;
    }
    /* Overlay styles */
    .card>.loading-img, .card>.overlay {
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
    .card>.loader-container {
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
                        <h3 class="card-title">Add Member</h3>
                    </div>

                    <form id="form" action="{{route('members.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="First Name">First Name</label>
                                        <input field_type="text" error_val="The first name field is required." type="text" name="first_name" class="form-control form_validation" id="FirstName" placeholder="Enter First Name" value="{{ old('first_name') }}">
                                        <div class="text-danger">@error('first_name')The first name field is required. @endif</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="Email address">Email address</label>
                                        <input field_type="email" type="email" name="email" class="form-control form_validation" id="EmailAddress" placeholder="Enter email" value="{{ old('email') }}">
                                        <div class="text-danger">@error('email') {{$message}} @endif</div>
                                    </div>
                                    <div class="form-group {{ Auth::user()->user_type == 6 ? 'd-none' : ''}}">
                                        <label for="company">Company</label>
                                        <select id="companyId" error_val="Please select a company. Company is required." field_type="select" name="company" class="select2 companies form-control" style="width: 100%;">
                                            <option value="">Select Company</option>
                                            @foreach($companies as $key => $value)
                                                <option {{ old('company') == $value->id  || (Auth::user()->id == $value->id) ?  'selected':'' }} value="{{ $value->id }}">{{$value->first_name}} {{$value->last_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('company') {{$message}} @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Address">Address</label>
                                        <textarea class="form-control" name="address" id="Address" placeholder="Enter Address" rows="1">{{ old('address') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
								<input type="hidden" name="country" value="1" />
								<input type="hidden" name="city" value="122795" />
                                    <!--div class="form-group">
                                        <label for="country">Country</label>
                                        <select id="countryId" error_val="Please select a country. Country is required." field_type="select" name="country" class="select2 countries form-control form_validation" style="width: 100%;">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $key => $value)
                                                <option {{ old('country') == $value->country_id ?  'selected':'' }} value="{{ $value->country_id }}">{{$value->country_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('country') {{$message}} @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="City">City</label>
                                        <select error_val="Please select a city. City is required." field_type="select" id="CityId" name="city" class="select2 cities form-control form_validation">
                                            <option value="">Select City</option>
                                        </select>
                                        <div class="text-danger">@error('city') {{$message}} @endif</div>
                                    </div-->

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
                                        <input type="text" name="phone_no" class="form-control" id="PhoneNo" placeholder="Enter Phone" value="{{ old('phone_no') }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="manager">Manager</label>
                                        <select id="managerId" error_val="Please select a manager. Manager is required." field_type="select" name="manager" class="select2 managers form-control" style="width: 100%;">
                                            <option value="">Select Manager</option>
                                            @foreach($managers as $key => $value)
                                                <option {{ old('manager') == $value->id  || (Auth::user()->id == $value->id) ?  'selected':'' }} value="{{ $value->id }}">{{$value->first_name}} {{$value->last_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('manager') {{$message}} @endif</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-footer" style="text-align:center; background: none;">
                        <button class="btn btn-primary submit">Submit</button>
                        <a href="{{ route('members') }}" class="btn btn-default">Cancel</a>
                    </div>
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
<script type="text/javascript">
$(document).ready( function () {
    let company_id = $('#companyId').val(); // Get pre-selected company ID

    if (company_id && company_id !== '0') {
        fetchManagers(company_id);
    }

	function fetchManagers(company_id) {
		let managerDropdown = $('#managerId');
		
		if (company_id === '' || company_id === '0') {
			managerDropdown.empty().append('<option value="">Please wait...</option>');
			return;
		}

		let options = "<option value=''>Select Manager</option>";

		$.ajax({
			url: "/managers/get-by-company-id",
			type: 'GET',
			data: { 'company_id': company_id },
			success: function(response) {
				managerDropdown.empty();
				if (response.length) {
					$(response).each(function(key, value) {
						options += `<option value="${value.id}">${value.first_name} ${value.last_name}</option>`;
					});
				}
			},
			complete: function() {
				managerDropdown.html(options);
				managerDropdown.select2({
					placeholder: "Select Manager",
					allowClear: true
				});
			}
		});
	}
 });
</script>
@endpush
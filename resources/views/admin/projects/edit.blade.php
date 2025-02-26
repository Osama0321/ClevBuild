@extends('layouts.admin.app', ['title' => 'Add Manager'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
<section>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Update Project</h3>
                    </div>

                    <form id="form" action="{{route('projects.update', ['projects' => $projects->project_id ])}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Project Name">Project Name</label>
                                        <input type="text" field_type="text" error_val="The project name field is required." name="project_name" class="form-control form_validation" id="projectname" placeholder="Enter Project Name" value="{{ (old('project_name')) ? old('project_name') : $projects->project_name }}">
                                        <div class="text-danger">@error('project_name') {{$message}} @endif</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="Category">Category</label>
                                        <select id="category" field_type="select" error_val="Please select a category. Category is required." name="category_id" class="select2 categories form-control form_validation">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $key => $category)
                                                <option {{($category->category_id == old('category_id', $projects->category_id)) ? 'selected' : ''}} value="{{ $category->category_id }}">{{$category->category_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('category_id') {{$message}} @endif</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <select id="countryId" error_val="Please select a country. Country is required." field_type="select" name="country_id" class="select2 countries form-control form_validation">
                                            <option value="">Select Country</option>
                                            @foreach($countries as $key => $country)
                                                <option {{($country->country_id == old('country_id', $projects->country_id)) ? 'selected' : ''}} value="{{ $country->country_id }}">{{$country->country_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('country_id') {{$message}} @endif</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="Address">Address</label>
                                        <textarea class="form-control" name="address" id="Address" placeholder="Enter Address">{{ (old('address')) ? old('address') : $projects->address }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="manager">Manager</label>
                                        <select field_type="select" error_val="Please select a Manager. Manager is required." id="manager_id" name="manager_id" class="select2 countries form-control form_validation" style="width: 100%;">
                                            <option value="">Select Manager</option>
                                            @foreach($managers as $key => $value)
                                                <option {{($value->id == old('manager_id', $projects->manager_id)) ? 'selected' : ''}} value="{{ $value->id }}">{{$value->first_name}} {{$value->last_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('manager_id') {{$message}} @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select id="project_status_id" name="project_status_id" class="select2 countries form-control" style="width: 100%;">
                                            @foreach($project_statuses as $key => $status)
                                                <option {{($status->project_status_id == old('project_status_id', $projects->project_status_id)) ? 'selected' : ''}} value="{{ $status->project_status_id }}">{{$status->project_status_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="City">City</label>
                                        <select id="CityId" error_val="Please select a city. City is required." field_type="select" name="city_id" class="select2 cities form-control form_validation">
                                            <option value="">Select City</option>
                                            @foreach($countries[($projects->country_id)-1]->cities as $key => $value)
                                                <option {{($value->city_id == old('city_id', $projects->city_id)) ? 'selected' : ''}} value="{{ $value->city_id }}">{{ $value->city_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('city_id') {{$message}} @endif</div>
                                    </div>
                                </div>
                            </div>

                           
                        </div>
                    </form>
                    <div class="card-footer" style="text-align:center; background: none;">
                        <button class="btn btn-primary submit">Update</button>
                        <a href="{{ route('projects') }}" class="btn btn-default">Cancel</a>
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
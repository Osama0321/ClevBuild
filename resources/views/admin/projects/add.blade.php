@extends('layouts.admin.app', ['title' => 'Add Project'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
@endpush

@section('content')
<section>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-end">
                    <a href="{{ url()->previous() }}" class="btn btn-default mt-2 mb-2">
                        <i class="fas fa-arrow-left fa-xs"></i> Back
                    </a>
                </div>
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Create Project</h3>
                    </div>
                    <form id="form" action="{{route('projects.store')}}" method="POST" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
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
                                    <div class="form-group">
                                        <label for="Project Name" class="required">Project Name</label>
                                        <input type="text" field_type="text" name="project_name" error_val="The project name field is required." class="form-control form_validation" id="projectname" placeholder="Enter Project Name" value="{{ old('project_name') }}">
                                        <div class="text-danger">@error('project_name') {{$message}}  @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Address">Address</label>
                                        <textarea class="form-control" name="address" id="Address" placeholder="Enter Address" rows="1">{{ old('address') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="start_date" class="required">Start Date</label>
                                        <input type="date" field_type="date" name="start_date" error_val="The start date field is required." class="form-control form_validation" id="start-date" placeholder="DD-MM-YYYY" value="{{ old('start_date') }}">
                                        <div class="text-danger">@error('start_date') {{ $message }} @enderror</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="manager" class="required">Manager</label>
                                        <select field_type="select" error_val="Please select a manager. Manager is required." id="managerId" name="manager_id" class="select2 form-control form_validation" style="width: 100%;">
                                            <option value="">Select Manager</option>
                                            @foreach($managers as $key => $value)
                                                <option {{ old('manager_id') == $value->id ?  'selected':'' }} value="{{ $value->id }}">{{$value->first_name}} {{$value->last_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('manager_id') {{$message}} @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Category" class="required">Category</label>
                                        <select id="category" field_type="select" error_val="Please select a category. Category is required." name="category_id" class="select2 categories form-control form_validation">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $key => $category)
                                                <option {{ old('category_id') == $category->category_id ?  'selected':'' }} value="{{ $category->category_id }}">{{$category->category_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('category_id')  {{$message}} @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select id="project_status_id" name="project_status_id" class="select2 form-control" style="width: 100%;">
                                            @foreach($project_statuses as $key => $status)
                                                <option {{ old('project_status_id') == $status->project_status_id ?  'selected':'' }} value="{{ $status->project_status_id }}">{{$status->project_status_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('project_status_id') {{$message}} @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="end_date" class="required">Planned End Date</label>
                                        <input type="date" field_type="date" name="end_date" error_val="The end date field is required." class="form-control form_validation" id="end-date" placeholder="DD-MM-YYYY" value="{{ old('end_date') }}">
                                        <div class="text-danger">@error('end_date') {{ $message }} @enderror</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-footer" style="text-align:center; background: none;">
                        <button class="btn btn-primary submit">Submit</button>
                        <a href="{{ route('projects') }}" class="btn btn-default">Cancel</a>
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
<script src="{{ asset('js/filemanger.js')}}"></script>
<script>
    let today = new Date().toISOString().split('T')[0];
    document.getElementById("start-date").setAttribute("min", today);
    document.getElementById("end-date").setAttribute("min", today);
</script>
@endpush
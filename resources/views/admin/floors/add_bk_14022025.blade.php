@extends('layouts.admin.app', ['title' => 'Add Floor'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
    label.required::after {
        content: " *";
        color: #ff0000;
    }
    h3.required::after {
        content: " *";
        color: #ff0000;
        font-size: smaller;
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
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Create Floor</h3>
                    </div>
                    <form id="form" action="{{route('floors.store')}}" method="POST" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project" class="required">Project</label>
                                        <select id="project" field_type="select" error_val="Please select a project. Project is required." name="project_id" class="select2 projects form-control form_validation">
                                            <option value="">Select Project</option>
                                            @foreach($projects as $key => $project)
                                                <option {{ old('project_id') == $project->project_id ?  'selected':'' }} value="{{ $project->project_id }}">{{$project->project_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('category_id') {{$message}} @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="floor_name" class="required">Floor Name</label>
                                        <input type="text" field_type="text" name="floor_name" error_val="The floor name field is required." class="form-control form_validation" id="floor_name" placeholder="Enter Floor Name" value="{{ old('floor_name') }}">
                                        <div class="text-danger">@error('floor_name') {{$message}}  @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Category" class="required">Category</label>
                                        <select id="category" field_type="select" error_val="Please select a category. Category is required." name="category_id" class="select2 categories form-control form_validation">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $key => $category)
                                                <option {{ old('category_id') == $category->category_id ?  'selected':'' }} value="{{ $category->category_id }}">{{$category->category_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('category_id') {{$message}} @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Address">Address</label>
                                        <textarea class="form-control" name="address" id="Address" placeholder="Enter Address">{{ old('address') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Members" class="required">Members</label>
                                        <select name="member_id" error_val="Please select a member. Member is required." field_type="select" class="form_validation select2 cities form-control">
                                            <option value="">Select Member</option>
                                            @foreach($members as $member)
                                                <option {{ old('member_id') == $member->id ?  'selected':'' }} value="{{ $member->id }}">{{ $member->first_name . ' ' . $member->last_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('member_id') {{$message}} @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="status" class="required">Status</label>
                                        <select id="floor_status_id" name="floor_status_id" class="select2 countries form-control" style="width: 100%;">
                                            @foreach($statuses as $key => $status)
                                                <option {{ old('floor_status_id') == $status->status_id ?  'selected':'' }} value="{{ $status->status_id }}">{{$status->status_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('floor_status_id') {{$message}} @endif</div>
                                    </div>
                                    <div class="row" id="preview_task_images" style="margin-top: 10px;" >
                                        <div class="col-md-12 col-12">
                                            <h3 class="required">Upload Plans</h3>
                                        </div>
                                        <div class="col-md-2 col-12">
                                            <input type="file" name="file" id="file-input" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-footer" style="text-align:center; background: none;">
                        <button class="btn btn-primary submit">Save & Continue</button>
                        <a href="{{ route('floors') }}" class="btn btn-default">Cancel</a>
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
@endpush
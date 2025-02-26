@extends('layouts.admin.app', ['title' => 'Add Manager'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

<style>
.image-preview {
	margin-bottom: 30px;
	position: relative;
	display: inline-block;
  }
  
  .preview-image {
	width: 80px;
	height: 80px;
  }

  .remove-icon {
    position: absolute;
    top: -10px;
    right: -15px;
    color: #fff;
    cursor: pointer;
    z-index: 1; /* Ensure it's above other content */
    color: red;
    font-size: 20px;
    cursor: pointer;
    width: 30px;
    height: 30px;
    text-align: center;
    border-radius: 50%;
    background: beige;
}
  

  .gal{
    height: 125px;
    width: 125px;
    border: dashed 2px #ccc;
    border-radius: 4px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #ccc;
    cursor: pointer;
    font-size: 25px;                                                  
  }

  .image-preview {
    position: relative;
}

.image-container {
    position: relative;
    display: inline-block;
}

.view-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    font-size:25px;
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    padding: 5px;
    cursor: pointer;
    opacity: 0; /* Hide initially */
    transition: opacity 0.3s ease;
}

.image-container:hover .view-icon {
    opacity: 1; /* Show on hover */
}

.modal-content {
    width: 800px !important;
}

.modal-body {
    text-align: center;
}

#modalImage {
    max-width: 100%;
    max-height: 100%;
}
    </style>

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
                        <h3 class="card-title">Create Task</h3>
                    </div>
                    <form id="form" action="{{route('tasks.store')}}" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" name="floor_id" class="form-control" value="{{ $floor->floor_id }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="First Name">Task Name</label>
                                        <input field_type="text" error_val="The task name field is required." type="text" name="task_name" class="form-control form_validation" id="FirstName" placeholder="Enter Task Name" value="{{ old('task_name') }}">
                                        <div class="text-danger">@error('task_name') {{$message}} @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="task_type">Task Type</label>
                                        <select field_type="select" id="taskType" error_val="Please select a task type. Task type is required." name="task_type" class="select2 task_type form-control form_validation">
                                            <option value="">Select Task Type</option>
                                            @foreach($task_types as $key => $task_type)
                                                <option value="{{ $task_type->task_type_id }}">{{ $task_type->task_type_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('status_id') {{$message}} @endif</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="priority">Priority</label>
                                        <select field_type="select" id="priority" error_val="Please select a priority. Priority is required." name="priority_id" class="select2 priorities form-control form_validation">
                                            <option value="">Select Priorities</option>
                                            @foreach($priorities as $key => $priority)
                                                <option {{ old('priority') == $priority->priority_id ?  'selected':'' }} value="{{ $priority->priority_id }}">{{$priority->priority_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('priority_id') {{$message}} @endif</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select field_type="select" id="status" error_val="Please select a status. Status is required." name="status" class="select2 status form-control form_validation">
                                            <option value="">Select Status</option>
                                            <!-- @foreach($statuses as $key => $status)
                                                <option value="{{ $status->status_id }}">{{$status->status_name}}</option>
                                            @endforeach -->
                                        </select>
                                        <div class="text-danger">@error('status_id') {{$message}} @endif</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="Discription">Discription</label>
                                        <textarea class="form-control" style="resize:none" rows="10" name="description" id="discription" placeholder="Enter Discription">{{ old('discription') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="preview_task_plan" style="margin-top: 10px;" >
                                <div class="col-md-12 col-12">
                                    <h3>Upload Plan</h3>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group" data-preview="preview_task_plan" style="
                                        height: 150px;
                                        width: 150px;
                                        border: dashed 2px #ccc;
                                        border-radius: 4px;
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        color: #ccc;
                                        cursor: pointer;
                                        font-size: 25px;
                                        ">
                                        <i class="nav-icon fas fa-solid fa-upload" style="font-size: 60px;"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="preview_task_images" style="margin-top: 10px;" >
                                <div class="col-md-12 col-12">
                                    <h3>Upload Images</h3>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group gal" data-preview="preview_task_images" style="
                                        height: 150px;
                                        width: 150px;
                                        border: dashed 2px #ccc;
                                        border-radius: 4px;
                                        display: flex;
                                        justify-content: center;
                                        align-items: center;
                                        color: #ccc;
                                        cursor: pointer;
                                        font-size: 25px;
                                        ">
                                        <i class="nav-icon fas fa-solid fa-upload" style="font-size: 60px;"></i>
                                    </div>
                                </div>
                            </div>

                            
                        </div>
                    </form>
                    <div class="card-footer" style="text-align:center; background: none;">
                        <button class="btn btn-primary submit">Submit</button>
                        <a href="{{ route('tasks') }}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</section>
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" id="modalImage" class="img-fluid" />
            </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('Admin/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/filemanger.js')}}"></script>
<script>

var task_image_counter = 0;
var route_prefix = "{{route('unisharp.lfm.show')}}";
$(document).ready(function () {
    $('.gal').gallery('image', {prefix: route_prefix});
});

function showImageInModal(imageUrl) {
    // Set the image source in the modal
    $('#modalImage').attr('src', imageUrl);

    // Show the modal
    $('#imageModal').modal('show');
}
</script>
@endpush
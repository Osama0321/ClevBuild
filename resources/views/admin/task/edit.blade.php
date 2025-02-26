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

                    <form action="{{route('tasks.update', ['task' => $task->task_id ])}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Member Name">Project Name : </label> {{ $projects->project_name != '' ? $projects->project_name : '' }}
                                        <input type="hidden" name="project_id" class="form-control" id="project_id" value="{{ $projects->project_id }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Member Name">Member Name : </label> {{ $projects->member != '' ? $projects->member->first_name . ' ' . $projects->member->last_name:'' }}
                                        <input type="hidden" name="member_id" class="form-control" id="member_id" value="{{ $projects->member->id }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Member Name">Task Name : </label> {{ $task->task_name != '' ? $task->task_name : '' }}
                                        <input type="hidden" name="task_id" class="form-control" id="task_id" value="{{ $task->task_id }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php 
                                            $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                            $barcode = $generator->getBarcode($task->task_name, $generator::TYPE_CODE_128);
                                        ?>
                                        <img src="data:image/png;base64, {{ base64_encode($barcode) }}" />
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Status">Status</label>
                                        <select name="project_status_id" error_val="Please select a Status. Status is required." field_type="select" class="select2 status form-control form_validation">
                                            <!-- <option value="">Select Status</option> -->
                                            @foreach($project_statuses as $project_status)
                                                <option {{($project_status->project_status_id == old('project_status_id', $task->project_status_id)) ? 'selected' : ''}} value="{{ $project_status->project_status_id }}">{{ $project_status->project_status_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('project_status_id')Please select a Status. Status is required. @endif</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Priorities">Priorities</label>
                                        <select name="priority_id" error_val="Please select a Priorities. Priorities is required." field_type="select" class="select2 status form-control form_validation">
                                            <!-- <option value="">Select Priorities</option> -->
                                            @foreach($priorities as $priority)
                                                <option {{($priority->priority_id == old('priority_id', $task->priority_id)) ? 'selected' : ''}} value="{{ $priority->priority_id }}">{{ $priority->priority_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('priority_id')Please select a Priorities. Priorities is required. @endif</div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer" style="text-align:center; background: none;">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('projects') }}" class="btn btn-default">Cancel</a>
                            </div>
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
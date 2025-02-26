@extends('layouts.admin.app', ['title' => 'Add Task Amount'])

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
<h3 class="card-title">Add Task Amount</h3>
</div>

<form action="{{route('addtaskamount.store')}}" method="POST" autocomplete="off">
    @csrf
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Projects</label>
                    <div class="select2-purple">
                        <select class="select2" name="Project_id" id="project_id_task_amount" data-placeholder="Select Project" data-dropdown-css-class="select2-purple" style="width: 100%;">
                            <option value="">Select Projects</option>
                            @foreach($Projects as $Project)
                                <option value="{{ $Project->project_id }}">{{ $Project->project_name }}</option>
                            @endforeach
                        </select>
                        @error('Projects')
                        <div class="text-danger">
                            Please select a Project. Project is required                                       
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!--Task -grid- for invoice amount  -->
        <div class="row">
            <table class="table table-border">
                <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Task Amount</th>
                    </tr>
                </thead>
                <tbody id="tasks_amount_details">
                </tbody>
            </table>
        </div>
        <!--Task -grid- for invoice amount  -->

        <div class="card-footer" style="text-align:center; background: none;">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('addtaskamount') }}" class="btn btn-default">Cancel</a>
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
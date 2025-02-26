@extends('layouts.admin.app', ['title' => 'Add Invoice'])

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
<h3 class="card-title">Add Invoice</h3>
</div>

<form action="{{route('invoices.store')}}" method="POST" autocomplete="off">
    @csrf
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Projects</label>
                    <div class="select2-purple">
                        <select class="select2" name="Projects[]" id="project_id" data-placeholder="Select Project" data-dropdown-css-class="select2-purple" style="width: 100%;">
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
            <div class="col-md-6">
                <div class="form-group">
                    <label>Followers</label>
                    <div class="select2-purple">
                        <select class="select2" id="follower_id" name="followers[]" multiple="multiple" data-placeholder="Select Followers" data-dropdown-css-class="select2-purple" style="width: 100%;">
                            <option value="">Select followers</option>
                        </select>
                        @error('followers')
                        <div class="text-danger">
                            Please select a follower. Follower is required                                       
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
                <tbody id="tasks_details">
                </tbody>
            </table>
        </div>
        <!--Task -grid- for invoice amount  -->

        <div class="card-footer" style="text-align:center; background: none;">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('invoices') }}" class="btn btn-default">Cancel</a>
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
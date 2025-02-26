@extends('layouts.admin.app', ['title' => 'All Tasks'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')
<style>
    .wrapper aside.main-sidebar {
    box-shadow: none !important;
}
    .content-header .card-header {
    display: flex;
    align-items: center;
    border: 0;
    padding: 0 0 27px;
}

.card-header-btn {
    display: flex;
    align-items: center;
    gap: 10px;
}

a.btn.btn-primary {
    font-size: 16px;
    font-weight: 500;
    line-height: 24px;
    text-align: center;
    padding: 7px 18px;
    border-radius: 8px;
    background: rgba(0, 85, 230, 1);
    border-color: rgba(0, 85, 230, 1);
    width: auto;
    margin: 0;
}

.content-header .card-header h3.card-title {
    flex: 1;
    font-size: 22px;
    font-weight: 600;
    line-height: 33px;
    text-align: left;
}

section .content-wrapper {
    background: none;
}

.content-header .card {
    box-shadow: none;
}

.content-header .card-body {
    padding: 0;
}

div.dataTables_wrapper table {
    width: 100% !important;
}
</style>
<section>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="card">
                    @include('message')
                    <div class="card-header">
                        <h3 class="card-title">Task</h3>
                        <div class="card-header-btn">
                            <!-- <a href="{{ route('tasks.add', ['floor_id' => $floor_id]) }}" class="btn btn-block btn-primary">Add New Task</a> -->
                            <a href="{{ route('cadeditorNew', ['floor_id' => $floor_id]) }}" class="btn btn-block btn-primary">View In Cadviewer</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="floor_id" id="floor_id" value="{{ $floor_id }}">
                        <table id="task" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Task Name</th>
                                    <th>Barcode</th>
                                    <th>Floor</th>
                                    <th>Member</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Created By</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('Admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('Admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('Admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('Admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('Admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('Admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

<script src="{{ asset('Admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('Admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('Admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>


	<script type="text/javascript">
		$(document).ready( function () {
            var floor_id = $('#floor_id').val();

		    $.ajaxSetup({
		      headers: {
		          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		      }
		    });
		 
		    $('#task').DataTable({
		         processing: true,
		         serverSide: true,
		         ajax: {
                    url: "{{ route('tasks.get') }}?floor_id=" + floor_id,
		            type: 'GET',
		         },
		         columns: [
		                { data: 'task_name', name: 'task_name' },
                        { data: 'barcode', name: 'barcode' },
		                { data: 'floor_name', name: 'floor_name' },
                        { data: 'member_name', name: 'member_name' },
                        { data: 'priority_name', name: 'priority_name' },
                        { data: 'status_name', name: 'status_name' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'created_by_name', name: 'created_by_name' },
                        // { data: 'action', name: 'action' },
		            ],
		        order: [[0, 'desc']]
		    });


            $(document).off("click",".remove_task").on("click",".remove_task",function(e)
            {
                let item = $(this);
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure? You want to remove ',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(item).parents('form').submit();
                    }
                })
            });
		  
		});
	</script>
@endpush
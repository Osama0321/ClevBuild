@extends('layouts.admin.app', ['title' => 'All Floors'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
  table.dataTable > thead > tr > th {
    vertical-align: top;
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Floors</h3>
                    </div>
                    <div class="card-body">
                        @include('message')
                        @can('addFloors')
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <a href="{{route('floors.add')}}" class="btn btn-block btn-primary">Add New Floors</a>
                            </div>
                        </div>
                        @endcan
                        <table id="floors" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Floor Name</th>
                                    <th>Project Name</th>
                                    <th>Category Name</th>
                                    <th>Country Name</th>
                                    <th>City Name</th>
                                    <th>Address</th>
                                    <th>Created Date</th>
                                    <th>Created By</th>
                                    <th>Action</th>
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
		    $.ajaxSetup({
		        headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        }
		    });
		 
		    $('#floors').DataTable({
		         processing: true,
		         serverSide: true,
		         ajax: {
		          url: "{{ route('floors.get') }}",
		          type: 'GET',
		         },
		         columns: [
		                { 
                            data: 'floor_name', 
                            name: 'floor_name',
                            render: function(data, type, row, meta) {
                                // Format the floor name as a hyperlink
                                return '<a href="{{ route("tasks") }}?floor_id=' + row.floor_id + '">' + data + '</a>';
                            }
                        },
                        { 
                            data: 'project', 
                            name: 'project',
                            render: function(data) {
                                return data.project_name;
                            }
                        },
                        { 
                            data: 'category', 
                            name: 'category',
                            render: function(data) {
                                return data.category_name;
                            }
                        },
                        { 
                            data: 'country', 
                            name: 'country',
                            render: function(data) {
                                return data.country_name;
                            }
                        },
                        { 
                            data: 'city', 
                            name: 'city',
                            render: function(data) {
                                return data.city_name;
                            }
                        },
                        { data: 'address', name: 'address' },
                        { data: 'created_at', name: 'created_at' },
                        { 
                            data: 'created_by',
                            name: 'created_by',
                            render: function(data) {
                                return data.first_name+' '+data.last_name;
                            }
                        },
                        { data: 'action', name: 'action' },
		            ],
		        order: [[0, 'desc']]
		    });


            $(document).off("click",".remove").on("click",".remove",function(e)
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

<style>
    .btn-actions {
    display: flex;
    gap: 5px;
}

#allTaskModal .modal-dialog .modal-content .modal-body {
    max-height: 400px;
    overflow-y: scroll;
}
</style>
@endpush
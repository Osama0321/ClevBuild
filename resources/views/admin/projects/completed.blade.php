@extends('layouts.admin.app', ['title' => 'All Products'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
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
                        <h3 class="card-title">Completed Projects</h3>
                    </div>
                    <div class="card-body">
                        <table id="projects" class="table table-bordered table-striped">
                            <thead>
                                <tr>
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
		 
		    $('#projects').DataTable({
		         processing: true,
		         serverSide: true,
		         ajax: {
		          url: "{{ route('completed-projects.get') }}",
		          type: 'GET',
		         },
		         columns: [
		                  { data: 'project_name', name: 'project_name' },
                          { data: 'category_name', name: 'category_name' },
                          { data: 'country_name', name: 'country_name' },
		                  { data: 'city_name', name: 'city_name' },
		                  { data: 'address', name: 'address' },
		                  { data: 'created_at', name: 'created_at' },
		                  { data: 'created_by_name', name: 'created_by_name' },
		                  { data: 'action', name: 'action' },
		               ],
		        order: [[0, 'desc']]
		    });


            $(document).off("click",".remove_project").on("click",".remove_project",function(e)
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
@extends('layouts.admin.app', ['title' => 'All Accountants'])

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
                        <h3 class="card-title">Accountants</h3>
                    </div>
                    <div class="card-body">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <a href="{{route('accountants.add')}}" class="btn btn-block btn-primary">Add New Accountant</a>
                            </div>
                        </div>
                        <table id="accountants" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
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
		 
		    $('#accountants').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('accountants.get') }}",
                    type: 'GET',
                },
                columns: [
                        { data: 'first_name', name: 'first_name' },
                        { data: 'last_name', name: 'last_name' },
                        { data: 'email', name: 'email' },
                        { data: 'action', name: 'action' },
                    ],
                order: [[0, 'desc']]
		    });


            $(document).off("click",".remove_manager").on("click",".remove_manager",function(e)
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
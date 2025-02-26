@extends('layouts.admin.app', ['title' => 'All Products'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Managers</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if(Auth::user()->isAn('admin'))
                                <div class="col-md-6">               
                                    <div class="form-group">
                                        <select id="companyId" error_val="Please select a company. Company is required." field_type="select" name="company" class="select2 companies form-control" style="width: 100%;">
                                            <option value="">Select Company</option>
                                            @foreach($companies as $key => $value)
                                                <option {{ old('company') == $value->id ?  'selected':'' }} value="{{ $value->id }}">{{$value->first_name}} {{$value->last_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger">@error('company') {{$message}} @endif</div>
                                    </div>
                                </div>
                                <div class="col-md-1">               
                                    <div class="form-group">
                                        <button class="btn btn-block btn-primary btn-search"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            @endif    
                            @can('addManagers')
                                <div class="col-md-3 {{ Auth::user()->user_type == 1 ? 'offset-2' : 'offset-9' }}">               
                                    <div class="form-group">
                                        <a href="{{route('managers.add')}}" class="btn btn-block btn-primary">Add New Manager</a>
                                    </div>
                                </div> 
                            @endcan       
                        </div>
                        <table id="managers" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Created Date</th>
                                    @if(Auth::user()->isAn('admin'))
                                        <th>Company</th>
                                    @endif
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center">
                                    <td colspan="8">....</td>
                                </tr>
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

            $('#companyId').select2({
                placeholder: "Select Company",
                allowClear: true
            });

            var userType = @json(Auth::user()->user_type);

            var columns = [
                { data: 'first_name', name: 'first_name' },
                { data: 'last_name', name: 'last_name' },
                { data: 'email', name: 'email' },
                { data: 'created_at', name: 'created_at' },
                { data: 'created_by', name: 'created_by', orderable: false, searchable: true},
                { data: 'action', name: 'action', orderable: false },
            ];

            if (userType === 1) {
                columns.splice(4, 0, { data: 'company', name: 'company', orderable: false, searchable: true});
            }

		    var table = $('#managers').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('managers.get') }}",
                    type: 'GET',
                    data: function(d) {
                        d.company_id = $('#companyId').val();
                    }
                },
                columns: columns,
                "order": [],
		    });

            $(document).on("click",".btn-search",function(e){
                table.ajax.reload();
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

@push('scripts')
<script src="{{ asset('Admin/plugins/select2/js/select2.full.min.js') }}"></script>
@endpush
@extends('layouts.admin.app', ['title' => 'All Companies'])

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
                        <h3 class="card-title">Companies</h3>
                    </div>
                        
                    <div class="card-body">
                        <div class="row">                            
                            @can('addCompanies')
                                <div class="col-md-4 offset-8">               
                                    <div class="form-group">
                                        <a href="{{route('companies.add')}}" class="btn btn-block btn-primary">Add New Company</a>
                                    </div>
                                </div> 
                            @endcan       
                        </div>
                        <table id="companies" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Created Date</th>
                                    <th>Created By</th>
                                    <th>Updated By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center">
                                    <td colspan="7">--</td>
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
            
            $('#companyId').select2({
                placeholder: "Select Company",
                allowClear: true
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            var table = $('#companies').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('companies.get') }}",
                    type: 'GET',
                    data: function(d) {
                        d.company_id = $('#companyId').val();
                    }
                },
                columns: [
                    { data: 'first_name', name: 'first_name' },
                    { data: 'last_name', name: 'last_name' },
                    { data: 'email', name: 'email' },
                    { data: 'created_at', name: 'created_at'},
                    { data: 'created_by_name', name: 'created_by_name',orderable: false, searchable: true },
                    { data: 'updated_by_name', name: 'updated_by_name',orderable: false, searchable: true },
                    { data: 'action', name: 'action',orderable: false, searchable: false },
                ],
                "order": [],
            });

            $('#companyId').change(function() {
                table.ajax.reload();
            });



            $(document).off("click",".remove_company").on("click",".remove_company",function(e)
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

    <script src="{{ asset('Admin/plugins/select2/js/select2.full.min.js') }}"></script>
@endpush
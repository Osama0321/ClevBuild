@extends('layouts.admin.app', ['title' => 'All Products'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    #members > thead > tr > th {
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
                        <h3 class="card-title">Members</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if(Auth::user()->user_type == 1)
                                <div class="col-md-4">               
                                    <div class="form-group">
                                        <select id="companyId" field_type="select" name="company" class="select2 companies form-control" style="width: 100%;">
                                            <option value="">Select Company</option>
                                            @foreach($companies as $key => $value)
                                                <option value="{{ $value->id }}">{{$value->first_name}} {{$value->last_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select id="managerId" field_type="select" name="manager" class="select2 managers form-control" style="width: 100%;">
                                        <option value="">Select Manager</option>
                                        @foreach($managers as $key => $value)
                                            <option value="{{ $value->id }}">{{$value->first_name}} {{$value->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">               
                                <div class="form-group">
                                    <button class="btn btn-block btn-primary btn-search"><i class="fas fa-search"></i></button>
                                </div>
                            </div> 
                            @can('addMembers')
                                <div class="col-md-3 {{ Auth::user()->user_type == 6 ? 'offset-4' : '' }} ">               
                                    <div class="form-group">
                                        <a href="{{route('members.add')}}" class="btn btn-block btn-primary">Add New Member</a>
                                    </div>
                                </div> 
                            @endcan
                        </div>
                        <table id="members" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <!--th>Country</th>
                                    <th>City</th-->
                                    <th>Company</th>
                                    <th>Manager</th>
                                    <th>Created By</th>
                                    <th>Updated By</th>
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
<script src="{{ asset('Admin/plugins/select2/js/select2.full.min.js') }}"></script>
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
    
    $('#managerId').select2({
        placeholder: "Select Manager",
        allowClear: true
    });
    
    var table = $('#members').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('members.get') }}",
            type: 'GET',
            data: function(d) {
                d.company_id = $('#companyId').val();
                d.manager_id = $('#managerId').val();
            }
        },
        columns: [
                { data: 'first_name', name: 'first_name' },
                { data: 'last_name', name: 'last_name' },
                { data: 'email', name: 'email' },
                // { data: 'country', name: 'country' },
                // { data: 'city', name: 'city' },
                { data: 'company', name: 'company' },
                { data: 'manager', name: 'manager' },
                { data: 'created_by', name: 'created_by' },
                { data: 'updated_by', name: 'updated_by' },
                { data: 'action', name: 'action' },
            ],
    order: [[0, 'desc']]
    });

    $('#companyId').change(function() {
        let company_id = $(this).val();
        if(company_id == '' || company_id == '0'){
            $('#managerId').empty();
        } else {
            let options = "<option value=''>Select Manager</option>";
            $.ajax({
                url: "{{ route('managers.getByComapnyId') }}",
                type: 'GET',
                data:{'company_id':company_id},
                success:function(response){
                    if(response.length){
                        $(response).each(function(key,value){
                            options += `<option value="${value.id}">${value.first_name} ${value.last_name}</option>`;
                        });
                    } 
                },
                complete:function(){
                    $('#managerId').html(options);
                    $('#managerId').select2({
                        placeholder: "Select Manger",
                        allowClear: true
                    });
                }

            })
        }
    });

    $(document).on("click",".btn-search",function(e){
        reloadMembersTable();
    });
    
    function reloadMembersTable(){
        table.ajax.reload();
    }

    $(document).off("click",".remove_member").on("click",".remove_member",function(e)
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
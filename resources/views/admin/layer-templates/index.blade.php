@extends('layouts.admin.app', ['title' => 'Layer Listing'])

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
                        <h3 class="card-title">Layer Templates</h3>
                    </div>
                        
                    <div class="card-body">
                        <div class="row">                            
                            @can('addLayerTemplates')
                                <div class="col-md-4 offset-8">               
                                    <div class="form-group">
                                        <a href="{{route('layer-templates.add')}}" class="btn btn-block btn-primary">Add Layer Template</a>
                                    </div>
                                </div> 
                            @endcan       
                        </div>
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
                        </div>
                        <table id="layer-templates" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Template Name</th>
                                    <th>Created Date</th>
                                    <th>Created By</th>
                                    <!-- <th>Updated By</th> -->
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

            $.ajaxSetup({
		        headers: {
		            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		        }
		    });
		 
            var table = $('#layer-templates').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('layer-templates.get') }}",
                    type: 'GET',
                    data: function(d) {
                        d.company_id = $('#companyId').val();
                    }
                },
                columns: [
                    { data: 'template_name', name: 'template_name' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'created_by', name: 'created_by', orderable: false, searchable: true },
                    // { data: 'updated_by', name: 'updated_by' },
                    { data: 'action', name: 'action',orderable: false, searchable: true },
                ],
                "order": [],
            });

            $('#companyId').change(function() {
                table.ajax.reload();
            });
		});
	</script>
@endpush

@push('scripts')
<script src="{{ asset('Admin/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#companyId').select2({
            placeholder: "Select Company",
            allowClear: true
        });        
    });
</script>
@endpush
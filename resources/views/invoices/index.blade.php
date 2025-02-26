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
                        <h3 class="card-title">Invoices</h3>
                    </div>
                    <div class="card-body">
                        @can('addInvoices')
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <a href="{{route('invoices.add')}}" class="btn btn-block btn-primary">Create New Invoice</a>
                            </div>
                        </div>
                        @endcan
                        <table id="invoices" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Invoice ID</th>
                                    <th>Amount</th>
                                    <th>Created By</th>
                                    <th>Created at</th>
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
    
    $('#invoices').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('invoices.get') }}",
            type: 'GET',
        },
        columns: [
                { data: 'invoice_id',   name: 'invoice_id' },
                { data: 'amount',       name: 'amount' },
                { data: 'created_by',   name: 'created_by' },
                { data: 'created_at',   name: 'created_at' },
                { data: 'action',       name: 'action' },
            ],
        order: [[0, 'desc']]
    });
});
</script>
@endpush
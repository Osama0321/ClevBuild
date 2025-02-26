@extends('layouts.admin.app', ['title' => 'Layer Template'])

@push('styles')

<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

<link rel="stylesheet" href="{{ asset('Admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    /* Overlay styles */
    .card .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: block;
        z-index: 9999;
    }

    /* Loader styles */
    .loader-container {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        z-index: 10000;
        display: block;
    }

    input, select {
        pointer-events: none;
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
                        <h2 class="text-center mb-4">Layer Template</h2>
                    </div>
                        
                    <div class="card-body">
                        <!-- Layer templates table -->
                        <div id="layer-templates" class="d-none">
                        <!-- <div id="layer-templates"> -->
                            <table class="table table-bordered">
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label for="template_name" class="required" >Template Name</label>
                                            <input type="text" name="template_name" value="{{ $layerTemplate->template_name }}" id="template-name" class="form-control"/>
                                        </div>    
                                    </td>
                                    @if(Auth::user()->isAn('admin'))
                                        <td width="50%">
                                            <div class="form-group">
                                                <label for="company" class="required" >Company</label>
                                                <select id="company-id"  field_type="select" name="company" class="select2 companies form-control" style="width: 100%;">
                                                    @foreach($companies as $key => $value)
                                                        @if((Auth::user()->id == $value->id)  || $layerTemplate->user_id == $value->id)
                                                            <option value="{{ $value->id }}">{{$value->first_name}} {{$value->last_name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                    @endif    
                                </tr>
                            </table>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Layer Name</th>
                                        <th>Pipe</th>
                                        <th>Head</th>
                                        <th>Lock</th>
                                        <th>Hide</th>
                                    </tr>
                                </thead>
                                <tbody id="layer-rows">
                                </tbody>
                            </table>
                        </div>
                        <!-- Loader and Overlay -->
                        <div class="overlay" id="overlay"></div>
                        <div class="loader-container" id="loader">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
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

    let layerData = [];
    let layerTemplate = {!! json_encode($layerTemplate) !!} ?? [];
   
    if(layerTemplate){
        layerData = layerTemplate.template_layers;
    }
    
    function renderLayers() {

        document.querySelector("#layer-rows").innerHTML = '';
        const tableBody = document.getElementById('layer-rows');

        if (layerData) {
            // If layerData is a string, parse it
            if (typeof layerData === 'string') {
                layerData = JSON.parse(layerData);
            }
        }

        // Only add rows that are not already rendered
        layerData.forEach((layer, index) => {
            const row = document.createElement('tr');
            row.id = `layer-row-${index}`;
            row.innerHTML = `
                <td>
                    ${index + 1}
                </td>
                <td>
                    <input type="text" name="data[${index}][layer_name]" value="${layer.layer_name}" class="form-control">
                </td>
                <td>
                    <input type="checkbox" name="data[${index}][pipe]" value="pipe" class="checkbox-pipe" ${layer.type === 'pipe' ? 'checked' : ''}>
                </td>
                <td>
                    <input type="checkbox" name="data[${index}][head]" value="head" class="checkbox-head" ${layer.type === 'head' ? 'checked' : ''}>
                </td>
                <td>
                    <input type="checkbox" name="data[${index}][lock]" ${layer.lock ? 'checked' : ''}>
                </td>
                <td>
                    <input type="checkbox" name="data[${index}][hide]" ${layer.hide ? 'checked' : ''}>
                </td>
            `;
            tableBody.appendChild(row);
        });

        if (layerData.length > 0) {
            document.getElementById('layer-templates').classList.remove('d-none');
        }

        document.querySelector(".overlay").style.display = "none";
        document.querySelector(".loader-container").style.display = "none";
    }
    renderLayers();

</script>
@endpush

@push('scripts')
<script src="{{ asset('Admin/plugins/select2/js/select2.full.min.js') }}"></script>
@endpush
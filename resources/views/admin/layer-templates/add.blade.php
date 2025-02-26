@extends('layouts.admin.app', ['title' => 'Layer Template'])

@push('styles')

<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

<link rel="stylesheet" href="{{ asset('Admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    label.required::after {
        content: " *";
        color: #ff0000;
    }
    .has-error{
        color: #ff0000;
    }
    /* Overlay styles */
    .card .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
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
        display: none;
    }

    .modal-body .card {
        box-shadow: none;
        margin: 0;
        height: 100%;
    }

    .card .card-table {
        overflow: auto;
        height: 100%; 
    }

    .card-table table tr th {
        padding: 7px 10px;
        border-bottom: 1px solid lightgrey;
        font-size: 15px;
        text-align: center;
    }

    .card-table table tr th:first-child {
        text-align: left;
    }

    div#layer-management-modal {
        background: #00000066;
        height:100%;
    }

    div#layer-management-modal .modal-dialog {
        max-width: 850px;
        margin: 70px auto;
        height: calc(100% - 20%);
    }

    .card-table table {
        width: 100%;
    }

    div#layer-management-modal .modal-dialog .modal-content {
        box-shadow: 0 0 1px rgba(0,0,0,.125),0 1px 3px rgba(0,0,0,.2);
        height: 100%;
    }

    .modal-content .modal-body {
        height: calc(100% - 120px);
    }

    .modal-content .modal-header {
        padding: .75rem 1.25rem;
        align-items: center;
        border: 0;
    }

    h4.modal-title i {
        color: #2563eb;
        padding-right: 8px;
    }

    .modal-content .modal-header h4.modal-title {
        line-height: normal;
        font-size: 1.1rem;
        font-weight: 400;
        color: #000;
    }

    .modal-content .modal-header button.close {
        padding: 0;
        opacity: 1;
        margin: 0;
    }

    .modal-content .modal-header button.close:hover {
        opacity: 1;
    }

    .card-table table tr 
    td {
        padding: 7px 10px;
        vertical-align: middle;
        text-align: left;
    }

    .card-table table tr td .form-group {
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-group input.form-check {
        width: 15px;
        height: 15px;
    }

    .card-table table tr td input {
        width: 100%;
        border: 0;
    }

    .card-table table tr td input:focus {
        outline: none;
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
                <div class="d-flex justify-content-end">
                    <a href="{{ url()->previous() }}" class="btn btn-default mt-2 mb-2">
                        <i class="fas fa-arrow-left fa-xs"></i> Back
                    </a>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center mb-4">Layer Template</h2>
                        <!-- Add/Import options -->
                        <div class="text-center mb-3">
                            <button id="add-layer-btn" class="btn btn-primary">Add New Layer</button>
                            <button id="import-dwg-btn" class="btn btn-secondary">Import from DWG</button>
                        </div>
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
                                            <label for="set-as-default" style="float:inline-end">
                                                <input type="checkbox" name="set_as_default" id="set-as-default"> Set As Default
                                            </label>
                                            <input type="text" name="template_name" id="template-name" class="form-control"/>
                                        </div>    
                                    </td>
                                    @if(Auth::user()->isAn('admin'))
                                        <td width="50%">
                                            <div class="form-group">
                                                <label for="company" class="required" >Company</label>
                                                <select id="company-id"  field_type="select" name="company" class="select2 companies form-control" style="width: 100%;">
                                                    <option value="">Select Company</option>
                                                    @foreach($companies as $key => $value)
                                                        <option {{ (Auth::user()->id == $value->id) ?  'selected':'' }} value="{{ $value->id }}">{{$value->first_name}} {{$value->last_name}}</option>
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
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="layer-rows">
                                    <!-- Dynamic rows will be added here -->
                                </tbody>
                            </table>
                            <button id="submit-btn" class="btn btn-success">Submit</button>
                        </div>

                        <!-- Import DWG file section -->
                        <div id="import-dwg-section" class="d-none mt-4">
                            <form id="import-dwg-form">
                                <div class="form-group">
                                    <label for="dwg-file">Upload DWG File:</label>
                                    <input type="file" class="form-control" id="file-input" accept=".dwg">
                                </div>
                                <button type="submit" class="btn btn-primary" id="upload-and-process-btn">Upload and Process</button>
                            </form>
                        </div>

                        <!-- Loader and Overlay -->
                        <div class="overlay" id="overlay"></div>
                        <div class="loader-container" id="loader">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <!-- <div class="modal" id="layer-management-modal" aria-hidden="false" style="display: block;"> -->
                        <div class="modal" id="layer-management-modal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title"><i class="fa fa-layer-group"></i>Layer Management</h4>
                                        <button type="button" class="close" id="modal-close-btn" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card">
                                            <div class="card-table">
                                                <form id="form-layer-management">
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th>Layer Name</th>
                                                                <th>Pipe</th>
                                                                <th>Head</th>
                                                                <th>Lock</th>
                                                                <th>Hide</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </form>    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" id="merge-layers-btn">Merge Layers</button>
                                        <button type="submit" class="btn btn-primary" id="replace-layers-btn">Replace Layers</button>
                                        <button class="btn btn-default" id="cancel-btn">Cancel</button>
                                    </div>
                                </div>
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

    // let layerData = {!! json_encode($layersListing) !!} ?? [];
    let layerData = [];
    let filename = '';
    let message = '';
    
    function renderLayers() {

        const fileInput = document.querySelector("#file-input");
        fileInput.value = "";

        document.querySelector("#layer-rows").innerHTML = '';
        const tableBody = document.getElementById('layer-rows');
        
        // if (layerData) {
        //     // If layerData is a string, parse it
        //     if (typeof layerData === 'string') {
        //         layerData = JSON.parse(layerData);
        //     }
        // }

        // Only add rows that are not already rendered
        layerData.forEach((layer, index) => {
            // if (!document.getElementById(`layer-row-${index}`)) {
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
                    <td>
                        <button class="btn btn-danger btn-sm remove-row-btn">Remove</button>
                    </td>
                `;
                tableBody.appendChild(row);
            // }
        });

        if (layerData.length > 0) {
            document.getElementById('layer-templates').classList.remove('d-none');
        }

    }

    function resetLayerTable() {
        document.getElementById('layer-rows').innerHTML = '';
        renderLayers();
    }

    document.getElementById('add-layer-btn').addEventListener('click', function() {

        let isValid = validateInputs();

        const tableRows = document.querySelectorAll('#layer-rows tr');
        layerData = Array.from(tableRows).map(row => {
            return {
                layer_name: row.children[1].querySelector('input').value,
                type: row.children[2].querySelector('input').checked ? 'pipe' : (row.children[3].querySelector('input').checked ? 'head'  : ''),
                lock: row.children[4].querySelector('input').checked,
                hide: row.children[5].querySelector('input').checked
            };
        });

        if (isValid) {
            layerData.push({ layer_name: '', type: '', lock: false, hide: false });
            renderLayers();
        }
    });

    function validateInputs(){
        let inputs = document.querySelectorAll('input[name^="data"][name$="[layer_name]"]');

        let isValid = true; // Flag to check validation
        inputs.forEach(input => {
            // Remove existing error message before validating
            let existingError = input.nextElementSibling;
            if (existingError && existingError.classList.contains("has-error")) {
                existingError.remove();
            }

            // Validate if input is empty
            if (input.value.trim() === "") {
                input.insertAdjacentHTML("afterend", '<span class="has-error" style="color:red;">This field is required.</span>');
                isValid = false;
            }
        });

        return isValid;
    }

    document.getElementById('layer-rows').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row-btn')) {
            const row = e.target.closest('tr');
            const index = Array.from(row.parentElement.children).indexOf(row);
            layerData.splice(index, 1);
            row.remove();
        }

        if (event.target.classList.contains("checkbox-pipe")) {
            const pipeCheckbox = event.target;
            const index = pipeCheckbox.name.match(/\d+/)[0]; // Extract the index from the name
            const headCheckbox = document.querySelector(`input[name="data[${index}][head]"]`);
            
            if (pipeCheckbox.checked) {
                headCheckbox.checked = false;
            }
        }

        if (event.target.classList.contains("checkbox-head")) {
            const headCheckbox = event.target;
            const index = headCheckbox.name.match(/\d+/)[0]; // Extract the index from the name
            const pipeCheckbox = document.querySelector(`input[name="data[${index}][pipe]"]`);
            if (headCheckbox.checked) {
                pipeCheckbox.checked = false;
            }
        }
    });

    document.getElementById('import-dwg-btn').addEventListener('click', function() {

        let import_dwg_section = document.getElementById('import-dwg-section');
        import_dwg_section.classList.toggle('d-none');
        if(import_dwg_section){
            import_dwg_section.scrollIntoView({ behavior: "smooth", block: "center" });
        }

    });

    document.getElementById("upload-and-process-btn").addEventListener("click", function (e) {
        
        const errorElement = document.querySelectorAll(".has-error");
        if (errorElement) {
            errorElement.forEach(error => error.remove());
        }

        e.preventDefault();
        let formData = new FormData();
        let fileInput = document.querySelector("#file-input");
        let file = fileInput.files[0];
        let message = "";
        let isValid = true;

        if (!file) {
            fileInput.insertAdjacentHTML("afterend", '<span class="has-error">Select File.</span>');
            isValid = false;
        }
        
        if(isValid){
            formData.append("file", file);
            formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute("content"));

            // Show overlay and loader before sending request
            document.querySelector(".overlay").style.display = "block";
            document.querySelector(".loader-container").style.display = "block";

            // Send the AJAX request using fetch
            fetch("{{ route('layer-templates.uploadAndProcess') }}", {
                method: "POST",
                body: formData,
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    let layers = "";
                    let rowCount = document.querySelectorAll('#layer-rows tr').length;
                    data.layers.forEach((layer) => {
                        layers += `
                            <tr>
                                <td>
                                    <input type="text" name="data[${rowCount}][layer_name]" value="${layer.layer_name}">
                                </td>
                                <td>
                                    <input type="checkbox" name="data[${rowCount}][pipe]" data-index="${rowCount}" class="checkbox-pipe"/>
                                </td>
                                <td>
                                    <input type="checkbox" name="data[${rowCount}][head]" data-index="${rowCount}" class="checkbox-head"/>
                                </td>
                                <td>
                                    <input type="checkbox" name="data[${rowCount}][lock]"/>
                                </td>
                                <td>
                                    <input type="checkbox" name="data[${rowCount}][hide]"/>
                                </td>
                            </tr>`;
                        rowCount++;    
                    });

                    document.querySelector("#layer-management-modal tbody").innerHTML = layers;
                    document.querySelector("#layer-management-modal").style.display = "block";

                    if(document.querySelectorAll('#layer-rows tr').length == 0){
                        document.querySelector("#layer-management-modal .modal-footer").innerHTML = 
                        `   <button type="submit" class="btn btn-primary" id="add-layers-btn">Add</button>
                            <button class="btn btn-default" id="cancel-btn">Cancel</button>
                        `;
                    } else {
                        document.querySelector("#layer-management-modal .modal-footer").innerHTML = 
                        `
                            <button type="submit" class="btn btn-primary" id="merge-layers-btn">Merge Layers</button>
                            <button type="submit" class="btn btn-primary" id="replace-layers-btn">Replace Layers</button>
                            <button class="btn btn-default" id="cancel-btn">Cancel</button>
                        `;
                    }
                    
                    renderLayers();

                } else if (data.error) {
                    // message = `
                    //     <div class="alert alert-danger alert-block">
                    //         <button type="button" class="close" data-dismiss="alert">×</button>
                    //         <strong>${data.error}</strong>
                    //     </div>`;
                    
                    let error = '';
                    if (data.error) {
                        Object.keys(data.error).forEach(function (key) {
                            data.error[key].forEach(function (errorMsg) {
                                error += `${errorMsg}<br>`;
                            });
                        });
                    }
                    fileInput.insertAdjacentHTML("afterend", `<span class="has-error">${error}</span>`);    
                }
            })
            .catch((error) => {
                message = `
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Something went wrong.</strong>
                    </div>`;
            })
            .finally(() => {
                // Hide overlay and loader
                document.querySelector(".overlay").style.display = "none";
                document.querySelector(".loader-container").style.display = "none";

                // Prepend the message and remove it after 5 seconds
                const cardBody = document.querySelector(".card-body");
                if (message !== '') {
                    cardBody.insertAdjacentHTML("afterbegin", message);

                    setTimeout(() => {
                        const alert = cardBody.querySelector(".alert");
                        if (alert) alert.remove();
                    }, 10000);
                }
            });
        }
    });

    document.getElementById('layer-management-modal').addEventListener('click', function (e) {
        // Ensure the clicked element is a checkbox
        if (e.target.tagName !== 'INPUT' || e.target.type !== 'checkbox') return;

        if (e.target.classList.contains("checkbox-pipe")) {
            const pipeCheckbox = e.target;
            const index = pipeCheckbox.name.match(/\d+/)[0]; // Extract the index from the name
            const headCheckbox = document.querySelector(`input[name="data[${index}][head]"]`);

            if (pipeCheckbox.checked) {
                headCheckbox.checked = false;
            }
        }

        if (e.target.classList.contains("checkbox-head")) {
            const headCheckbox = e.target;
            const index = headCheckbox.name.match(/\d+/)[0]; // Extract the index from the name
            const pipeCheckbox = document.querySelector(`input[name="data[${index}][pipe]"]`);

            if (headCheckbox.checked) {
                pipeCheckbox.checked = false;
            }
        }
        
    });
    
    document.getElementById('modal-close-btn').addEventListener('click', function (e) {
        
        Swal.fire({
            title: 'Are you sure you want to close?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, close it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {    
                document.getElementById('layer-management-modal').style.display = 'none';
            }
        });
    });
    
    document.getElementById('submit-btn').addEventListener('click', function() {

        const errorElement = document.querySelectorAll(".has-error");
        if (errorElement) {
            errorElement.forEach(error => error.remove());
        }

        let isValid = true;

        let templateName = document.getElementById('template-name');
        let setAsDefault = document.querySelector('input[name="set_as_default"]').checked;

        if (templateName.value.trim() === "") {
            templateName.insertAdjacentHTML("afterend", '<span class="has-error">Template name is required.</span>');
            isValid = false;
        }
        
        let company= document.getElementById('company-id');
        if (company) {
            if (company.value.trim() === '') {
                company.nextElementSibling.insertAdjacentHTML("afterend", '<span class="has-error">Select Company.</span>');
                isValid = false;
            }
        } else if(!validateInputs()) {
            isValid = false;
        } 

        if(!isValid){
            const errorElement = document.querySelector(".has-error");
            if (errorElement) {
                errorElement.scrollIntoView({ behavior: "smooth", block: "center" });
            }
        } else {

            if(document.querySelectorAll('#layer-rows tr').length == 0){
                Swal.fire({
                    title: "Template layers cannot be empty!",
                    icon: "error",
                    confirmButtonText: "Ok"
                });
                return false;
            }
           
            const alertElement = document.querySelector(".alert");
            if (alertElement) {
                alertElement.remove();
            }
            document.getElementById('layer-management-modal').style.display = 'none';
        
            const tableRows = document.querySelectorAll('#layer-rows tr');
            
            layerData = Array.from(tableRows).map(row => {
                return {
                    layer_name: row.children[1].querySelector('input').value,
                    type: row.children[2].querySelector('input').checked ? 'pipe' : (row.children[3].querySelector('input').checked ? 'head' : ''),
                    lock: row.children[4].querySelector('input').checked,
                    hide: row.children[5].querySelector('input').checked
                };
            });
            
            layerData = JSON.stringify(layerData);
            
            let formData = new FormData();

            // formData.append("file_name", file_name);
            if(company){
                formData.append("company_id", company.value.trim());
            }
            formData.append("template_name", templateName.value.trim());
            formData.append("set_as_default",setAsDefault);
            formData.append("layer_data", layerData);
            formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute("content"));

            // Show overlay and loader before sending request
            document.querySelector(".overlay").style.display = "block";
            document.querySelector(".loader-container").style.display = "block";

            // Send the AJAX request using fetch
            fetch("{{ route('layer-templates.store') }}", {
                method: "POST",
                body: formData,
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    message = ` <div class="alert alert-success alert-block text-center">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>${data.message}</strong>
                                </div>`;

                    document.querySelector("tbody#layer-rows").innerHTML = '';
                    
                    layerData = [];
                    
                    $('input').val('');
                    $('select').val('').change();
                    $('input[type="checkbox"]').prop('checked', false);

                    document.getElementById('layer-templates').classList.add('d-none');
                    document.getElementById('import-dwg-section').classList.add('d-none');          

                } else if (data.error) {
                    let errors = '';
                    for (let key in data.error) {
                        if (data.error.hasOwnProperty(key)) {
                            errors += data.error[key] + "\n";
                        }
                    }
                    Swal.fire({
                        title: "Validation Errors",
                        html: Object.values(data.error).join('<br>'),
                        icon: "error",
                        confirmButtonText: "OK"
                    });

                    message = `
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>${errors}</strong>
                        </div>`;
                }
            })
            .catch((error) => {
                message = `
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Something went wrong.</strong>
                    </div>`;
            })
            .finally(() => {
                // Hide overlay and loader
                document.querySelector(".overlay").style.display = "none";
                document.querySelector(".loader-container").style.display = "none";

                // Prepend the message and remove it after 5 seconds
                const cardBody = document.querySelector(".card-header h2");
                if (message !== '') {
                    cardBody.insertAdjacentHTML("afterend", message);

                    setTimeout(() => {
                        const alert = document.querySelector(".card-header").querySelector(".alert");
                        if (alert) alert.remove();
                    }, 10000);
                }
            });
        }
    });
    
    document.body.addEventListener("click", function (e) {
        if (e.target && e.target.id === "replace-layers-btn" || e.target.id === "add-layers-btn") {

            let btn_text = e.target.innerText.toLowerCase();
            btn_text = btn_text.replace('layers', '');

            Swal.fire({
                title: 'Are you sure you want to '+btn_text+' the layers?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, '+btn_text+' it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    let updatedLayerData = [];
                    layerData = [];

                    const rows = document.querySelectorAll("#layer-management-modal tbody tr");
                    
                    rows.forEach((row, index) => {
                        
                        const layerName = row.cells[0].querySelector(`input[name^="data"]`)?.value || '';
                        const pipeChecked = row.cells[1].querySelector('input[type="checkbox"]')?.checked || false;
                        const headChecked = row.cells[2].querySelector('input[type="checkbox"]')?.checked || false;
                        const lockChecked = row.cells[3].querySelector('input[type="checkbox"]')?.checked || false;
                        const hideChecked = row.cells[4].querySelector('input[type="checkbox"]')?.checked || false;

                        const type = pipeChecked ? 'pipe' : headChecked ? 'head' : '';

                        updatedLayerData.push({
                            layer_name: layerName,
                            type: type,
                            lock: lockChecked,
                            hide: hideChecked,
                        });
                    });
                    
                    layerData = updatedLayerData; 

                    document.querySelector("#layer-management-modal tbody").innerHTML = '';
                    document.querySelector("#layer-management-modal").style.display = "none";

                    message = ` <div class="alert alert-success alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>Layers have been replaced.</strong>
                                </div>`;

                    renderLayers();
                }
            });

        } else if (e.target && e.target.id === "merge-layers-btn"){

            Swal.fire({
                title: 'Are you sure you want to merge the layers?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, merge it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    const rows = document.querySelectorAll("#layer-management-modal tbody tr");
                    rows.forEach((row, index) => {

                        const layerName = row.cells[0].querySelector(`input[name^="data"]`)?.value || '';
                        const pipeChecked = row.cells[1].querySelector('input[type="checkbox"]')?.checked || false;
                        const headChecked = row.cells[2].querySelector('input[type="checkbox"]')?.checked || false;
                        const lockChecked = row.cells[3].querySelector('input[type="checkbox"]')?.checked || false;
                        const hideChecked = row.cells[4].querySelector('input[type="checkbox"]')?.checked || false;

                        const type = pipeChecked ? 'pipe' : headChecked ? 'head' : '';

                        layerData.push({
                            layer_name: layerName,
                            type: type,
                            lock: lockChecked,
                            hide: hideChecked,
                        });
                    });

                    document.querySelector("#layer-management-modal tbody").innerHTML = '';
                    document.querySelector("#layer-management-modal").style.display = "none";
                    
                    message = ` <div class="alert alert-success alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>Layers have been merged.</strong>
                                </div>`;
                    renderLayers();
                }
            });

        } else if (e.target && e.target.id === "cancel-btn") {

            const confirmation = window.confirm("Are you sure you want to cancel?");
            if (confirmation) {
                document.getElementById('layer-management-modal').style.display = 'none';
            }

        }
    });

</script>
@endpush

@push('scripts')
<script src="{{ asset('Admin/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $(".select2").select2();        
    });
</script>
<script src="{{ asset('Admin/plugins/sweetalert2/sweetalert2.js') }}"></script>
@endpush
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Leaflet Geoman with Image Background</title>
<link rel="stylesheet" href="css/leaflet.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/geoman.css') }}">
<!-- // To go in full screen mode -->
<link rel="stylesheet" href="{{ asset('css/fullscreen.css') }}">
<link rel="stylesheet" href="{{ asset('css/leaflet-draw.css') }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/editor.css') }}">
</head>
<body>

<div id="map"></div>
<div id="color-picker">
    <input type="color" id="color-input" style="position: absolute;
    margin-top: -139px;">
</div>

<!-- Bootstrap modal for editing text -->
<div class="modal fade" id="editTextModal" tabindex="-1" aria-labelledby="editTextModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editTextModalLabel">Edit Text</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- <input type="text" class="form-control" id="markerTextInput"> -->
        <textarea class="form-control" id="markerTextInput" rows="3"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveTextBtn">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Markup details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Created by</b> <a class="float-right" id="created_by"></a>
                    </li>
                    <li class="list-group-item">
                        <b>Created on</b> <a class="float-right" id="created_on"></a>
                    </li>
                    <li class="list-group-item">
                        <b>Last modified by</b> <a class="float-right" id="modified_by"></a>
                    </li>
                    <li class="list-group-item">
                        <b>Last modified on</b> <a class="float-right" id="modified_on"></a>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveTextBtn">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div id="contextMenuOptions" style="position: absolute; display: none; z-index: 999999; background: #fff;">
  <a class="dropdown-item" id="editDetailsBtn" href="#">Edit Details</a>
  <a class="deleteBtn">Delete Markup</a>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="{{ asset('js/editor/leaflet.js') }}"></script>
<script src="{{ asset('js/editor/popper.js') }}"></script>
<script src="{{ asset('js/editor/geoman.js') }}"></script>
<script src="{{ asset('js/editor/fullscreen.js') }}"></script>
<script src="{{ asset('js/editor/leaflet-draw.js') }}"></script>
<script src="{{ asset('js/editor/cascade_button.js') }}"></script>
<script>
    var layer;
    var shapeData;
    // Initialize Leaflet map
    var map = L.map('map', {
            center: [1, 0], // Initial center coordinates
            zoom: -1, // Initial zoom level
            minZoom: -1, // Minimum allowed zoom level
            maxZoom: 3, // Maximum allowed zoom level
            crs: L.CRS.Simple,
            // measureControl:true
    }).setView([0, 0], 0);

    // Array to store drawn shapes
    var drawnShapes = [];

    $(function() {
        loadDataFromDB();
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);
        map.pm.addControls({
            position: 'topright',
            drawCircle: false, // Disable circle drawing to prevent conflicts
        });

        // Add image overlay
        var imageUrl = 'images/test.jpg'; // Replace 'your-image.jpg' with the path to your image
        var bounds = [[-500, -500], [500, 500]]; // Replace [1000, 1000] with the dimensions of your image
        L.imageOverlay(imageUrl, bounds).addTo(map);

        // Custom control for undo
        var UndoControl = L.Control.extend({
            options: {
                position: 'topleft'
            },
            onAdd: function(map) {
                var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control-undo');
                container.innerHTML = '<span><i class="fa-solid fa-arrow-rotate-left"></i></span>';
                L.DomEvent.on(container, 'click', function() {
                    saveToDatabase({},'undo','','Edit');

                });
                return container;
            }
        });

        // To go in full screen mode
        map.addControl(new L.Control.Fullscreen());
       
       
        var shapetype;
        // Check if Leaflet-Geoman plugin is available
        if (L.PM) {
            // Add Leaflet-Geoman controls
            map.pm.addControls({
                position: 'topleft',
            });
            map.pm.removeControls();
            map.addControl(new UndoControl());
       
            new L.cascadeButtons([
                {icon: 'fas fa-solid fa-pencil', title: 'Add drawing', items:[
                    {icon: 'fas fa-solid fa-grip-lines-vertical',
                        title: 'Add Line',
                        command: () =>{
                        layer_type = 'Line'
                        map.pm.enableDraw('Line', {
                            finishOn: 'click', // Finish drawing on double click
                            snappable: true // Enable snapping to existing shapes
                        });
                    }},
                    {icon: 'fas fa-solid fa-highlighter', command: () =>{
                        L.control.paintPolygon().addTo(map);
                    }},
                ]},
                {icon: 'fas fa-solid fa-mouse-pointer',
                    title: 'Select markups',
                    command: () =>{ 
                    map.pm.disableGlobalEditMode();
                    map.pm.enableGlobalDragMode();
                    reInitializeDragend();
                }},
                {icon: 'fas fa-solid fa-pen-to-square',
                    title: 'Edit markups',
                    command: () =>{
                    map.pm.enableGlobalEditMode();
                    reInitializeEditMode();
                        
                }},
                {icon: 'fas fa-solid fa-font',
                    title: 'Add text',
                    command: () => {
                        shapetype = 'text';
                    map.pm.enableDraw('Text', {
                        finishOn: 'dblclick', // Finish drawing on double click
                        snappable: true // Enable snapping to existing shapes
                    });
                }},
                {icon: 'fas fa-solid fa-location-dot',
                    title: 'Add marker',
                    command: () =>{ 
                    map.pm.enableDraw('Marker', {
                        finishOn: 'dblclick', // Finish drawing on double click
                        snappable: true // Enable snapping to existing shapes
                    });
                }},
                {icon: 'fas fa-solid fa-shapes', items:[
                    {icon: 'fa-regular fa-square-full',
                        title: 'Add rectangle',
                        command: () =>{

                        
                        layer_type = 'rectangle';
                        shapetype = 'non_fill';
                        map.pm.enableDraw('Rectangle', {
                            finishOn: 'dblclick', // Finish drawing on double click
                            snappable: true // Enable snapping to existing shapes
                        });
                    }},
                    {icon: 'fa-regular fa-circle',
                        title: 'Add circle',
                        command: () =>{
                        layer_type = 'circle';
                        shapetype = 'non_fill';
                        // Add drawing mode for circles
                        map.pm.enableDraw('Circle', {
                            shapeOptions: {
                                color:'red',
                                fillColor: null,
                                opacity:0.5
                            },
                            finishOn: 'dblclick', // Finish drawing on double click
                            snappable: true // Enable snapping to existing shapes
                        });
                    }},
                    {icon: 'fa-solid fa-draw-polygon',
                        title: 'Add polygon',
                        command: () =>{
                        layer_type = 'polygon';
                        shapetype = 'non_fill';
                        map.pm.enableDraw('Polygon', {
                            finishOn: 'dblclick', // Finish drawing on double click
                            snappable: true // Enable snapping to existing shapes
                        });
                    }},
                    {icon: 'fas fa-light fa-square',
                        title: 'Add filled rectangle',
                        command: () =>{
                        layer_type = 'rectangle';
                        shapetype = 'fill';
                        map.pm.enableDraw('Rectangle', {
                            finishOn: 'dblclick', // Finish drawing on double click
                            snappable: true // Enable snapping to existing shapes
                        });
                    }},
                    {icon: 'fa-solid fa-circle',
                        title: 'Add filled circle',
                        command: () =>{
                        layer_type = 'circle';
                        shapetype = 'fill';
                        // Add drawing mode for circles
                        map.pm.enableDraw('Circle', {
                            shapeOptions: {
                                color:'red',
                                fillColor: null,
                                opacity:0.5
                            },
                            finishOn: 'dblclick', // Finish drawing on double click
                            snappable: true // Enable snapping to existing shapes
                        });
                    }},
                    {icon: 'fa-solid fa-draw-polygon',
                        title: 'Add filled polygon',
                        ignoreActiveState: true,
                        command: () =>{
                            layer_type = 'polygon';
                            shapetype = 'fill';
                            map.pm.enableDraw('Polygon', {
                                finishOn: 'dblclick', // Finish drawing on double click
                                snappable: true // Enable snapping to existing shapes
                            });
                        }
                    },
                ]}
            ], {position:'topleft', direction:'vertical'}).addTo(map);

            // // Event listener for shape creation
            map.on('pm:create', function(e) {
                map.off('mousemove');
                var layer = e.layer;
              
                if(shapetype=="fill"){
                    layer.setStyle({
                        color: $('#color-input').val(),
                        fillOpacity: 1,
                    });
                }
                else if(shapetype=="non_fill"){
                    layer.setStyle({
                        color: $('#color-input').val(),
                        fillOpacity:0,
                    });
                }
                
                var type = '';
                if (layer instanceof L.Circle) {
                    type = 'circle';
                }else if (layer instanceof L.Rectangle) {
                    type = 'rectangle';
                } else if (layer instanceof L.Polygon) {
                    type = 'polygon';
                }
                else if (layer instanceof L.Polyline) {
                    type = 'line';
                }else if (layer instanceof L.Marker && layer.options.icon.options.className === 'pm-text-marker') {
                    layer.isText = true;
                    type = 'text';
                    
                }
                else if (layer instanceof L.Marker) {
                    type = 'marker';
                } else {
                    type = 'lineString';
                }

                
                layer.addTo(map);
                drawnShapes.push(layer);
                
                var shapeData = {
                    task_id:'12312',
                    type: 'Feature', // Assuming the type is always 'Feature'
                    geometry: {
                        type: type,
                        coordinates: getCoordinates(layer) // Get the coordinates based on the layer type
                    },
                    properties: {
                        color: layer.options.color, // Get the color from layer options
                        fill: layer.options.fill, // Get the fill option from layer options
                        fillColor: layer.options.fillColor, // Get the fillColor from layer options
                        fillOpacity: layer.options.fillOpacity, // Get the fillOpacity from layer options
                        layerType: type, // Get the layerType based on the layer type
                        opacity: layer.options.opacity, // Get the opacity from layer options
                        style: layer.options.style, // Get the style from layer options
                        width: layer.options.weight, // Get the width from layer options
                        id: layer.options.id  // Get the icon from layer options for markers
                    }
                }
                if(type != 'text'){
                    saveToDatabase(shapeData,'insert',layer,'add');
                }
                if(type == 'text'){
                    openModal(shapeData);
                }
                // map.removeLayer(layer);
                // console.log('Shape data:', shapeData);
                shapetype = '';
            });
        } else {
            console.error('Leaflet-Geoman plugin not available.');
        }

        function reInitializeEditMode(){
            map.eachLayer(function(layer) {
                // Check if the layer has the Leaflet.pm instance and the pm:dragend event listener is attached
                if (layer.pm && layer.hasEventListeners('pm:markerdragend')) {
                    // Detach the pm:dragend event listener
                    layer.off('pm:markerdragend');
                }
            });


            map.eachLayer(function(layer) {
                // Check if the layer has the Leaflet.pm instance
                if (layer.pm) {
                    // Event listener for pm:update event
                    layer.on('pm:markerdragend', function(event) {
                        var type;
                        // Determine the layer type
                        if (layer instanceof L.Circle) {
                            type = 'circle';
                        } else if (layer instanceof L.Rectangle) {
                            type = 'rectangle';
                        } else if (layer instanceof L.Polygon) {
                            type = 'polygon';
                        } else if (layer instanceof L.Marker) {
                            type = 'marker';
                        } else {
                            type = 'line';
                        }
                        // console.log(type);
                        // Construct shape data object
                        var shapeData = {
                            task_id: '12312', // Assuming this value is constant or dynamically obtained
                            type: 'Feature', // Assuming the type is always 'Feature'
                            id: layer.options.id,
                            geometry: {
                                type: type,
                                coordinates: getCoordinates(layer) // Get the coordinates based on the layer type
                            },
                            properties: {
                                color: layer.options.color, // Get the color from layer options
                                fill: layer.options.fill, // Get the fill option from layer options
                                fillColor: layer.options.fillColor, // Get the fillColor from layer options
                                fillOpacity: layer.options.fillOpacity, // Get the fillOpacity from layer options
                                layerType: type, // Get the layerType based on the layer type
                                opacity: layer.options.opacity, // Get the opacity from layer options
                                style: layer.options.style, // Get the style from layer options
                                width: layer.options.weight // Get the width from layer options
                            }
                        };
                        saveToDatabase(shapeData,'update',layer,'Edit');
                        return false;
                    });
                }
            });
        }
        
        function reInitializeDragend(){
            // console.log('reInitializeDragend');

            map.eachLayer(function(layer) {
                // Check if the layer has the Leaflet.pm instance and the pm:dragend event listener is attached
                if (layer.pm && layer.hasEventListeners('pm:dragend')) {
                    // Detach the pm:dragend event listener
                    layer.off('pm:dragend');
                }
            });

            map.eachLayer(function(layer) {
                // Check if the layer has the Leaflet.pm instance
                if (layer.pm) {
                    // Add event listener for pm:dragend event
                    layer.on('pm:dragend', function(event) {
                        var type;
                        // Determine the layer type
                        if (layer instanceof L.Circle) {
                            type = 'circle';
                        } else if (layer instanceof L.Rectangle) {
                            type = 'rectangle';
                        } else if (layer instanceof L.Polygon) {
                            type = 'polygon';

                        }else if (layer instanceof L.Marker && layer.options.icon.options.className === 'pm-text-marker') {
                            layer.isText = true;
                            type = 'text';
                        } else if (layer instanceof L.Marker) {
                            type = 'marker';
                        } else {
                            type = 'line';
                        }
                        // console.log(layer);
                        // Construct shape data object
                        var shapeData = {
                            task_id: '12312', // Assuming this value is constant or dynamically obtained
                            type: 'Feature', // Assuming the type is always 'Feature'
                            id: layer.options.id,
                            geometry: {
                                type: type,
                                coordinates: getCoordinates(layer) // Get the coordinates based on the layer type
                            },
                            properties: {
                                color: layer.options.color, // Get the color from layer options
                                fill: layer.options.fill, // Get the fill option from layer options
                                fillColor: layer.options.fillColor, // Get the fillColor from layer options
                                fillOpacity: layer.options.fillOpacity, // Get the fillOpacity from layer options
                                layerType: type, // Get the layerType based on the layer type
                                opacity: layer.options.opacity, // Get the opacity from layer options
                                style: layer.options.style, // Get the style from layer options
                                width: layer.options.weight // Get the width from layer options
                            }
                        };
                        // console.log(shapeData);
                        saveToDatabase(shapeData,'update',layer,'drag');
                        // map.removeLayer(layer);
                        
                    });
                }
            });
        }

        var GlobalEditModeEnabled = false;
        var globalDragModeEnabled = false;

        // Function to toggle global drag mode
        function toggleGlobalDragMode() {
            if (globalDragModeEnabled) {
                // Disable global drag mode
                map.pm.disableGlobalDragMode();
                globalDragModeEnabled = false;
            } else {
                // Enable global drag mode
                map.pm.enableGlobalDragMode();
                globalDragModeEnabled = true;
            }
        }

        // Function to toggle global Edit mode
        function toggleGlobalEditMode() {
            if (GlobalEditModeEnabled) {
                // Disable global Edit mode
                map.pm.disableGlobalEditMode();
                GlobalEditModeEnabled = false;
            } else {
                // Enable global Edit mode
                map.pm.enableGlobalEditMode();
                GlobalEditModeEnabled = true;
            }
        }

        // Function to retrieve saved shape data and restore them on page refresh
        function saveToDatabase(shapeData,queryType,old_layer,layer_type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Send the shapeData to your backend for saving to the database
            shapeData['query_type'] = queryType;
            $.ajax({
                url: '{{ route("save-layer") }}',
                type: 'POST',
                data: shapeData,
                success: function(response) {
                    // console.log('Shape data saved successfully:', response)
                    if(queryType != 'undo'){
                        if(queryType == 'update' && shapeData.text != '' && layer_type != 'drag'){
                            // console.log(".textarea"+shapeData.id);
                            $(".textarea"+shapeData.id).remove();
                        }
                        var layer = response.layer;
                        load_layer(layer,old_layer);

                        if(layer_type == 'drag'){
                            reInitializeDragend();
                        }

                        if(layer_type == 'Edit'){
                            reInitializeEditMode();
                        }
                    }else if(queryType == 'undo'){
                        map.eachLayer(function(layer) {
                            if (layer.options.id == response.layer_to_remove['layer_id'] ) {
                                // Remove the layer from the map
                                map.removeLayer(layer);
                            }
                        });

                        var layer = response.layer;
                        if(layer != ''){
                            load_layer(layer,old_layer);
                            if(layer_type == 'drag'){
                                reInitializeDragend();
                            }
                            if(layer_type == 'Edit'){
                                reInitializeEditMode();
                            }
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error saving shape data:', error);
                }
            });
        }

        function deleteLayer(id){
            $('#contextMenuOptions').hide();
            $(".textarea"+id).remove();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '{{ route("delete-layer") }}',
                type: 'POST',
                data: "id="+id,
                success: function(response) {
                    // console.log('Shape data saved successfully:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error saving shape data:', error);
                }
            });
        }

        // Click event handler for 'Edit Details' option in the dropdown
        $('#editDetailsBtn').click(function() {
            var layer_id = $(this).attr('layer_id');
             console.log(layer_id);
            // Perform actions for 'Edit Details' option
            // console.log("Editing details");
            
            $.ajax({
                url: "{{ route('show-data') }}",
                type: "POST",
                data: {
                    // You can include any data you want to send with the request
                    _token: "{{ csrf_token() }}",
                    layer_id: layer_id
                },
                success: function(response){
                    // console.log(response.text);
                    if(response.text != null){
                        $('#editTextModal').modal('show');
                        $('#contextMenuOptions').hide();
                    }else{
                        $("#created_by").text(response.full_name);
                        $("#created_on").text(response.created_at);
                        $("#modified_by").text(response.full_name);
                        $("#modified_on").text(response.created_at);
                        $('#editModal').modal('show');
                        $('#contextMenuOptions').hide();
                    }
                },
                error: function(xhr){
                    // Handle any errors
                    console.log(xhr.responseText);
                }
            });
        });

        // Click event handler for 'Delete' option in the dropdown
        $('.deleteBtn').click(function() {
            // Perform actions for 'Delete' option
            // console.log("Deleting text");
            var layer_id = $(this).attr('layer_id');
            var is_text  = $(this).attr('is_text');
            if(is_text == 0){
                removeLayerFromMap(layer_id);
            }
            deleteLayer(layer_id);
        });

        $(document).on("contextmenu",'.pm-textarea', function(e){
            e.preventDefault(); // This prevents the default context menu from appearing
            // Get layer ID and current text
            var layer_id = $(this).attr('layer_id');
            var currentText = $(this).val();

            // Set layer ID for the 'Save changes' button in the modal
            $('#saveTextBtn').attr('layer_id', layer_id);
            $('.deleteBtn').attr('layer_id', layer_id);
            $('.deleteBtn').attr('is_text', 1);
            
            // Set current text in the text input of the modal
            $('#markerTextInput').val(currentText);

            // Create and position the context menu options at the cursor position
            var contextMenuOptions = $('#contextMenuOptions');
            contextMenuOptions.css({
                left: e.pageX,
                top: e.pageY
            });
            contextMenuOptions.show();
        });

        // Save changes button event listener
        $(document).on("click",'#saveTextBtn',function () {
            var layer_id = $(this).attr('layer_id');
            // console.log(layer_id);
            var newText = $('#markerTextInput').val();
          
            if(layer_id == 0){
                var shapeDataString = $(this).attr('shapeData');
                var shapeData = JSON.parse(shapeDataString);
                shapeData.text = newText;
                // console.log(shapeData);
                saveToDatabase(shapeData,'insert','','add');
            }else{
                shapeData = {};
                shapeData.id    = layer_id;
                shapeData.text  = newText;
                saveToDatabase(shapeData,'update','','edit');
            }
            
        });

        
    })


function initContextMenuOnAllLyers(){
    map.eachLayer(function(layer) {
        layer.on('contextmenu', function(e) {
            // Prevent the default context menu from appearing
            e.originalEvent.preventDefault();
            
            // Set layer ID for the 'Save changes' button in the modal
            $('#saveTextBtn').attr('layer_id', layer.options.id);
            $('#editDetailsBtn').attr('layer_id', layer.options.id);
            $('.deleteBtn').attr('layer_id', layer.options.id);
            $('.deleteBtn').attr('is_text', 0);
            // Create and position the context menu options at the cursor position
            var contextMenuOptions = $('#contextMenuOptions');
            contextMenuOptions.css({
                left: e.originalEvent.clientX,
                top: e.originalEvent.clientY
            });
            contextMenuOptions.show();
        });
    });
}

function removeLayerFromMap(layer_id){
    map.eachLayer(function(layer) {
        if (layer.options.id == layer_id) {
            map.removeLayer(layer);
        }
    });
}

function openModal(shapeData) {
    // alert(id);
    // console.log('model:', shapeData);
    $('#markerTextInput').val('');
    var shapeDataString = JSON.stringify(shapeData);
    $('#saveTextBtn').attr('layer_id',0);
    $('#saveTextBtn').attr('shapeData',shapeDataString);
    $('#editTextModal').modal('show');
}

function getCoordinates(layer) {
    if (layer instanceof L.Circle) {
        // For Circle, return center coordinates and radius
        return [layer.getLatLng().lng, layer.getLatLng().lat, layer.getRadius()];
    } else if (layer instanceof L.Rectangle) {
        // For Rectangle, return southwest and northeast corner coordinates
        var bounds = layer.getBounds();
        return [
            [bounds.getSouthWest().lng, bounds.getSouthWest().lat], // Southwest corner
            [bounds.getNorthEast().lng, bounds.getSouthWest().lat], // Southeast corner
            [bounds.getNorthEast().lng, bounds.getNorthEast().lat], // Northeast corner
            [bounds.getSouthWest().lng, bounds.getNorthEast().lat] // Northwest corner
        ];
    } else if (layer instanceof L.Polygon) {
        // For Polygon, return an array of arrays representing the coordinates of each vertex
        return layer.getLatLngs()[0].map(latlng => [latlng.lng, latlng.lat]);
    } else if (layer instanceof L.Marker) {
        // For Marker, return its coordinates
        return [layer.getLatLng().lng, layer.getLatLng().lat];
    } else {
        // For other geometries like LineString, return latlngs
        return layer.getLatLngs().map(latlng => [latlng.lng, latlng.lat]);
    }
}

function loadDataFromDB(){
    fetch('/layers')
    .then(response => response.json())
    .then(layers => {
        
        layers.forEach(layer => {
            

            // console.log(layer);

            layer.geometry      = JSON.parse(layer.geometry);

            layer.properties    = JSON.parse(layer.properties);
                var correctedCoordinates = layer.geometry.coordinates.map(function(coord) {
                    return [parseFloat(coord[1]), parseFloat(coord[0])];
                });

                var lng = parseFloat(layer.geometry.coordinates[0]); // Extract the longitude
                var lat = parseFloat(layer.geometry.coordinates[1]); // Extract the latitude
                // console.log(layer.geometry.coordinates);
            
            
            var shape;
            switch (layer.properties.layerType) {
                case 'circle':
                    
                    shape = L.circle([lat, lng], {
                        color: layer.properties.color,
                        fillColor: layer.properties.fillColor,
                        fillOpacity: layer.properties.fillOpacity,
                        opacity: layer.properties.opacity,
                        radius: parseFloat(layer.geometry.coordinates[2]),
                        id:layer.layer_id
                    });
                    break;
                case 'rectangle':
                    
                    shape =  L.rectangle(correctedCoordinates, {
                        color: layer.properties.color,
                        fillColor: layer.properties.fillColor,
                        fillOpacity: layer.properties.fillOpacity,
                        opacity: layer.properties.opacity,
                        id:layer.layer_id
                    });
                    break;
                case 'line':
                    shape =  L.polyline(correctedCoordinates, {
                        color: layer.properties.color,
                        opacity: layer.properties.opacity,
                        weight: layer.properties.width,
                        id:layer.layer_id
                    });
                    break;
                case 'polygon':
                    shape =  L.polygon(correctedCoordinates, {
                        color: layer.properties.color,
                        fillColor: layer.properties.fillColor,
                        fillOpacity: layer.properties.fillOpacity,
                        opacity: layer.properties.opacity,
                        id:layer.layer_id
                    });
                    break;
                case 'marker':
                    shape = L.marker([lat, lng],{
                                id: layer.layer_id
                            });
                    break;
                case 'text':
                    // Define custom CSS for the text marker label
                    var labelStyle = 'background:white; border:1px solid black; display: inline-block; padding: 5px; '; // Set background color to white
                    shape = L.marker([lat, lng], {
                        icon: L.divIcon({
                            className: 'pm-text-marker', // Add CSS class for styling if needed
                            html: `<textarea layer_id="${layer.layer_id}" class="pm-textarea textarea${layer.layer_id}" wrap="off" style="overflow: hidden; height: 21px; width: 110px; outline-style: none;">${layer.text}</textarea>`
                        }),
                        id: layer.layer_id
                    });
                    break;
                default:
                    console.error('Unknown layer type:', layer.layerType);
            }
            shape.addTo(map);
            drawnShapes.push(shape);
            initContextMenuOnAllLyers();
            map.eachLayer(function(layer) {
                layer.on('blur', function(e) {
                        var contextMenuOptions = $('#contextMenuOptions');
                        contextMenuOptions.hide();
                });
            });

        });
    })
    .catch(error => console.error('Error fetching layers:', error));
}

function load_layer(new_layer,old_layer){
    // console.log(old_layer);
    if(old_layer != ''){
        map.removeLayer(old_layer);
    }

    // return false;
    layer = new_layer;
    layer.geometry      = JSON.parse(layer.geometry);

    layer.properties    = JSON.parse(layer.properties);
    var correctedCoordinates = layer.geometry.coordinates.map(function(coord) {
        return [parseFloat(coord[1]), parseFloat(coord[0])];
    });

    var lng = parseFloat(layer.geometry.coordinates[0]); // Extract the longitude
    var lat = parseFloat(layer.geometry.coordinates[1]); // Extract the latitude
    // console.log(layer.geometry.coordinates);
            
    var shape;
    switch (layer.properties.layerType) {
        case 'circle':
            
            shape = L.circle([lat, lng], {
                color: layer.properties.color,
                fillColor: layer.properties.fillColor,
                fillOpacity: layer.properties.fillOpacity,
                opacity: layer.properties.opacity,
                radius: parseFloat(layer.geometry.coordinates[2]),
                id:layer.layer_id
            });
            break;
        case 'rectangle':
            
            shape =  L.rectangle(correctedCoordinates, {
                color: layer.properties.color,
                fillColor: layer.properties.fillColor,
                fillOpacity: layer.properties.fillOpacity,
                opacity: layer.properties.opacity,
                id:layer.layer_id
            });
            break;
        case 'line':
            shape =  L.polyline(correctedCoordinates, {
                color: layer.properties.color,
                opacity: layer.properties.opacity,
                weight: layer.properties.width,
                id:layer.layer_id
            });
            break;
        case 'polygon':
            shape =  L.polygon(correctedCoordinates, {
                color: layer.properties.color,
                fillColor: layer.properties.fillColor,
                fillOpacity: layer.properties.fillOpacity,
                opacity: layer.properties.opacity,
                id:layer.layer_id
            });
            break;
        case 'marker':
          
            shape = L.marker([lat, lng],{
                id: layer.layer_id
            });
            break;
        case 'text':
            // Define custom CSS for the text marker label
            var labelStyle = 'background:white; border:1px solid black; display: inline-block; padding: 5px; '; // Set background color to white
          
            
            shape = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'pm-text-marker', // Add CSS class for styling if needed
                    html: `<textarea layer_id="${layer.layer_id}" class="pm-textarea textarea${layer.layer_id}" wrap="off" style="overflow: hidden; height: 21px; width: 110px; outline-style: none;">${layer.text}</textarea>`
                }),
                id: layer.layer_id
            });
            
            break;
        default:
            console.error('Unknown layer type:', layer.layerType);
    }
    shape.addTo(map);
    drawnShapes.push(shape);
    $('#editTextModal').modal('hide');
    initContextMenuOnAllLyers();
}
</script>
</body>
</html>

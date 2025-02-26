<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Leaflet Geoman with Image Background</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
<!-- // To go in full screen mode -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-fullscreen/dist/leaflet.fullscreen.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

<style>
    #map {
        height: 800px;
        background-size: contain;
        background-repeat: no-repeat;
        position: relative;
    }

    .leaflet-control-undo {
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 4px;
        cursor: pointer;
        padding: 6px 10px;
        text-align: center;
    }

    .hidden {
    visibility: hidden !important;
}

.leaflet-control-cascadeButtons{
    background-color: transparent;
    justify-content: center;
    width: auto;
    height: auto;
    border:none;
}

.leaflet-control-cascadeButtons button{
    border-radius: 2px;
    border: none;
    background-color:#fff;
    box-shadow: 0 1px 5px rgb(0 0 0 / 65%);
    height: 30px;
    width: 30px;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    margin: 3px;
    padding:2px;
}

.leaflet-control-cascadeButtons button:hover{
    background-color:#f4f4f4;;
}

.vertical {
    display: flex;
    flex-direction: column;
}

.horizontal {
    display: flex;
    align-items: row-reverse;
}

.right {
    align-items: flex-end;
}

.row-reverse {
    flex-direction: row-reverse;
}

.col-reverse {
    flex-direction: column-reverse;
}

.bottom {
    align-items: flex-end;
}

.activeButton{
    box-shadow: 0 0 1px 3px #C2CB00 !important;
}
</style>
</head>
<body>

<div id="map"></div>
<div id="color-picker">
    <input type="color" id="color-input" style="position: absolute;
    margin-top: -139px;">
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.js"></script>
<!-- // To go in full screen mode -->
<script src="https://unpkg.com/leaflet-fullscreen/dist/Leaflet.fullscreen.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="js/cascade_button.js"></script>
<script>
var layer;
    // Function to debounce a function call
function debounce(func, delay) {
    let timer;
    return function() {
        const context = this;
        const args = arguments;
        clearTimeout(timer);
        timer = setTimeout(() => {
            func.apply(context, args);
        }, delay);
    };
}

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

        // Custom control for undo
        var UndoControl = L.Control.extend({
            options: {
                position: 'topleft'
            },
            onAdd: function(map) {
                var container = L.DomUtil.create('div', 'leaflet-bar leaflet-control-undo');
                container.innerHTML = '<span><i class="fa-solid fa-arrow-rotate-left"></i></span>';
                L.DomEvent.on(container, 'click', function() {
                    // Revert the last drawn shape
                    var lastShape = drawnShapes.pop();
                    if (lastShape) {
                        map.removeLayer(lastShape);
                    }
                });
                return container;
            }
        });

        // To go in full screen mode
        map.addControl(new L.Control.Fullscreen());
       
        // Add image overlay
        var imageUrl = 'images/test.jpg'; // Replace 'your-image.jpg' with the path to your image
        var bounds = [[-500, -500], [500, 500]]; // Replace [1000, 1000] with the dimensions of your image
        L.imageOverlay(imageUrl, bounds).addTo(map);
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
                    map.pm.disableGlobalDragMode();
                    map.pm.enableGlobalEditMode();
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
                                console.log(type);
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
                // console.log(layer.options.icon.options.className);
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
                saveToDatabase(shapeData,'insert',layer,'add');
                // map.removeLayer(layer);
                console.log('Shape data:', shapeData);
                shapetype = '';
            });
        } else {
            console.error('Leaflet-Geoman plugin not available.');
        }

        function reInitializeDragend(){
            console.log('reInitializeDragend');

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
                        } else if (layer instanceof L.Marker) {
                            type = 'marker';
                        } else {
                            type = 'line';
                        }

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
                        saveToDatabase(shapeData,'update',layer,'drag');
                        map.removeLayer(layer);
                        
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
                    console.log('Shape data saved successfully:', response)
                    var layer = response.layer;
                    load_layer(layer,old_layer);
                    if(layer_type == 'drag'){
                        reInitializeDragend();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error saving shape data:', error);
                }
            });
        }


        $(document).off("blur",".pm-textarea").on("blur",".pm-textarea",function(){
            console.log('layer_id',$(this).attr('layer_id'));
            console.log($(this).val());
        })
    })

    

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
            

            console.log(layer);
            layer.geometry      = JSON.parse(layer.geometry);

            layer.properties    = JSON.parse(layer.properties);
                var correctedCoordinates = layer.geometry.coordinates.map(function(coord) {
                    return [parseFloat(coord[1]), parseFloat(coord[0])];
                });

                var lng = parseFloat(layer.geometry.coordinates[0]); // Extract the longitude
                var lat = parseFloat(layer.geometry.coordinates[1]); // Extract the latitude
                console.log(layer.geometry.coordinates);
            
            
            var shape;
            switch (layer.properties.layerType) {
                case 'circle':
                    
                    shape = L.circle([lat, lng], {
                        color: layer.properties.color,
                        fillColor: layer.properties.fillColor,
                        fillOpacity: layer.properties.fillOpacity,
                        opacity: layer.properties.opacity,
                        radius: parseFloat(layer.geometry.coordinates[2]),
                        id:layer.id
                    });
                    break;
                case 'rectangle':
                    
                    shape =  L.rectangle(correctedCoordinates, {
                        color: layer.properties.color,
                        fillColor: layer.properties.fillColor,
                        fillOpacity: layer.properties.fillOpacity,
                        opacity: layer.properties.opacity,
                        id:layer.id
                    });
                    break;
                case 'line':
                    shape =  L.polyline(correctedCoordinates, {
                        color: layer.properties.color,
                        opacity: layer.properties.opacity,
                        weight: layer.properties.width,
                        id:layer.id
                    });
                    break;
                case 'polygon':
                    shape =  L.polygon(correctedCoordinates, {
                        color: layer.properties.color,
                        fillColor: layer.properties.fillColor,
                        fillOpacity: layer.properties.fillOpacity,
                        opacity: layer.properties.opacity,
                        id:layer.id
                    });
                    break;
                case 'marker':
                    shape = L.marker([lat, lng]);
                    break;
                case 'text':
                    var textContent = "123"; // Assuming there is a 'text' property
                    // Define custom CSS for the text marker label
                    var labelStyle = 'background:white; border:1px solid black; display: inline-block; padding: 5px; '; // Set background color to white
                    shape = L.marker([lat, lng], {
                        icon: L.divIcon({
                            className: 'pm-text-marker', // Add CSS class for styling if needed
                            html: `<textarea layer_id="${layer.id}" class="pm-textarea" wrap="off" style="overflow: hidden; height: 21px; width: 110px; outline-style: none;">${textContent}</textarea>`
                        })
                    });
                    break;
                default:
                    console.error('Unknown layer type:', layer.layerType);
            }
            shape.addTo(map);
            drawnShapes.push(shape);
        });
    })
    .catch(error => console.error('Error fetching layers:', error));
}

function load_layer(new_layer,old_layer){
    // console.log(old_layer);
    map.removeLayer(old_layer);
    // return false;
    layer = new_layer;
    layer.geometry      = JSON.parse(layer.geometry);

    layer.properties    = JSON.parse(layer.properties);
    var correctedCoordinates = layer.geometry.coordinates.map(function(coord) {
        return [parseFloat(coord[1]), parseFloat(coord[0])];
    });

    var lng = parseFloat(layer.geometry.coordinates[0]); // Extract the longitude
    var lat = parseFloat(layer.geometry.coordinates[1]); // Extract the latitude
    console.log(layer.geometry.coordinates);
            
    var shape;
    switch (layer.properties.layerType) {
        case 'circle':
            
            shape = L.circle([lat, lng], {
                color: layer.properties.color,
                fillColor: layer.properties.fillColor,
                fillOpacity: layer.properties.fillOpacity,
                opacity: layer.properties.opacity,
                radius: parseFloat(layer.geometry.coordinates[2]),
                id:layer.id
            });
            break;
        case 'rectangle':
            
            shape =  L.rectangle(correctedCoordinates, {
                color: layer.properties.color,
                fillColor: layer.properties.fillColor,
                fillOpacity: layer.properties.fillOpacity,
                opacity: layer.properties.opacity,
                id:layer.id
            });
            break;
        case 'line':
            shape =  L.polyline(correctedCoordinates, {
                color: layer.properties.color,
                opacity: layer.properties.opacity,
                weight: layer.properties.width,
                id:layer.id
            });
            break;
        case 'polygon':
            shape =  L.polygon(correctedCoordinates, {
                color: layer.properties.color,
                fillColor: layer.properties.fillColor,
                fillOpacity: layer.properties.fillOpacity,
                opacity: layer.properties.opacity,
                id:layer.id
            });
            break;
        case 'marker':
            shape = L.marker([lat, lng]);
            break;
        case 'text':
            var textContent = "123"; // Assuming there is a 'text' property
            // Define custom CSS for the text marker label
            var labelStyle = 'background:white; border:1px solid black; display: inline-block; padding: 5px; '; // Set background color to white
            shape = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'pm-text-marker', // Add CSS class for styling if needed
                    html: `<textarea layer_id="${layer.id}" class="pm-textarea" wrap="off" style="overflow: hidden; height: 21px; width: 110px; outline-style: none;">${textContent}</textarea>`
                })
            });
            break;
        default:
            console.error('Unknown layer type:', layer.layerType);
    }
    shape.addTo(map);
    drawnShapes.push(shape);
}
</script>
</body>
</html>

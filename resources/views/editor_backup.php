<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Leaflet Geoman with Image Background</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link
  rel="stylesheet"
  href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css"
/>
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
<script src="js/measurecontrol.js"></script>
<script>

    
    $(function() {
        fetch('/layers')
            .then(response => response.json())
            .then(layers => {
                
                layers.forEach(layer => {
                   
                    layer.geometry      = JSON.parse(layer.geometry);
                    layer.properties    = JSON.parse(layer.properties);
                    var correctedCoordinates = layer.geometry.coordinates.map(function(coord) {
                        return [parseFloat(coord[1]), parseFloat(coord[0])];
                    });

                    var lng = parseFloat(layer.geometry.coordinates[0]); // Extract the longitude
                    var lat = parseFloat(layer.geometry.coordinates[1]); // Extract the latitude
                    console.log(layer.geometry.coordinates);
                    switch (layer.properties.layerType) {
                        case 'circle':
                            
                            L.circle([lat, lng], {
                                color: layer.properties.color,
                                fillColor: layer.properties.fillColor,
                                fillOpacity: layer.properties.fillOpacity,
                                opacity: layer.properties.opacity,
                                radius: parseFloat(layer.geometry.coordinates[2])
                            }).addTo(map);
                            break;
                        case 'rectangle':
                           
                            L.rectangle(correctedCoordinates, {
                                color: layer.properties.color,
                                fillColor: layer.properties.fillColor,
                                fillOpacity: layer.properties.fillOpacity,
                                opacity: layer.properties.opacity,
                            }).addTo(map);
                            break;
                        case 'line':
                            L.polyline(correctedCoordinates, {
                                color: layer.properties.color,
                                opacity: layer.properties.opacity,
                                weight: layer.properties.width
                            }).addTo(map);
                            break;
                        default:
                            console.error('Unknown layer type:', layer.layerType);
                    }
                });
            })
            .catch(error => console.error('Error fetching layers:', error));
        // Initialize Leaflet map
        var map = L.map('map', {
            center: [1, 0], // Initial center coordinates
            zoom: -1, // Initial zoom level
            minZoom: -1, // Minimum allowed zoom level
            maxZoom: 3, // Maximum allowed zoom level
            crs: L.CRS.Simple,
            // measureControl:true
        }).setView([0, 0], 0);

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        // Array to store drawn shapes
        var drawnShapes = [];

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
        // var mainImageBounds = L.latLngBounds(bounds);
        // var rectangle = L.rectangle(mainImageBounds, {editable: false}).addTo(map);
        var shapetype;
        var layer_type;
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
                        map.pm.enableDraw('Line', {
                            finishOn: 'click', // Finish drawing on double click
                            snappable: true // Enable snapping to existing shapes
                        });
                    }},
                    {icon: 'fas fa-solid fa-highlighter', command: () =>{
                        map.pm.enableDraw('Line', {
                            finishOn: 'click', // Finish drawing on double click
                            snappable: true // Enable snapping to existing shapes
                        });
                    }},
                    {icon: 'fas fa-solid fa-highlighter', command: () =>{
                        map.pm.enableDraw('Line', {
                            finishOn: 'click', // Finish drawing on double click
                            snappable: true // Enable snapping to existing shapes
                        });
                    }},
                    {icon: 'fas fa-solid fa-highlighter', command: () =>{
                        map.pm.enableDraw('Line', {
                            finishOn: 'click', // Finish drawing on double click
                            snappable: true // Enable snapping to existing shapes
                        });
                    }},
                    {icon: 'fas fa-solid fa-highlighter',
                        title: 'Add Line',
                        command: () =>{
                        map.pm.enableDraw('Line', {
                            finishOn: 'click', // Finish drawing on double click
                            snappable: true // Enable snapping to existing shapes
                        });
                    }},
                ]},
                {icon: 'fas fa-solid fa-mouse-pointer',
                    title: 'Select markups',
                    command: () =>{ 
                    toggleGlobalDragMode();
                }},
                {icon: 'fas fa-solid fa-pen-to-square',
                    title: 'Edit markups',
                    command: () =>{ 
                    toggleGlobalEditMode();
                }},
                {icon: 'fas fa-solid fa-font',
                    title: 'Add text',
                    command: () => {
                    map.pm.enableDraw('Text', {
                        finishOn: 'dblclick', // Finish drawing on double click
                        snappable: true // Enable snapping to existing shapes
                    });
                }},
                {icon: 'fas fa-solid fa-scissors',
                    title: 'Add cut',
                    command: () =>{ 
                    map.pm.enableDraw('Cut', {
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
                   
                    // {icon: 'fas fa-home', command: () =>{console.log('hola')}},
                    // {icon: 'fas fa-home', command: () =>{console.log('hola')}},
                    // {icon: 'fas fa-home', command: () =>{console.log('hola')}},
                    // {icon: 'fas fa-home', command: () =>{console.log('hola')}},
                    // {icon: 'fas fa-home', command: () =>{console.log('hola')}},
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

                layer_type = shapetype+'_'+layer_type;
                shapetype = '';
                layer.addTo(map);
                drawnShapes.push(layer);
                var shapeData = {
                    type: 'Feature', // Assuming the type is always 'Feature'
                    geometry: {
                        type: layer instanceof L.Circle ? 'Circle' : (layer instanceof L.Rectangle ? 'Rectangle' : 'LineString'), // Determine the geometry type based on the layer type
                        coordinates: getCoordinates(layer) // Get the coordinates based on the layer type
                    },
                    properties: {
                        color: layer.options.color, // Get the color from layer options
                        fill: layer.options.fill, // Get the fill option from layer options
                        fillColor: layer.options.fillColor, // Get the fillColor from layer options
                        fillOpacity: layer.options.fillOpacity, // Get the fillOpacity from layer options
                        layerType: getLayerType(layer), // Get the layerType based on the layer type
                        opacity: layer.options.opacity, // Get the opacity from layer options
                        style: layer.options.style, // Get the style from layer options
                        width: layer.options.weight // Get the width from layer options
                    }
                }
                saveToDatabase(shapeData);
   
                // var shapeData = {
                //     type: layer instanceof L.Circle ? 'circle' : 'marker',
                //     latlngs: layer.getBounds().toBBoxString().split(',').map(parseFloat),
                //     radius: layer instanceof L.Circle ? layer.getRadius() : null
                // };
                // // Replace with code to save shapeData to your database
                console.log('Shape data:', shapeData);
            });
        } else {
            console.error('Leaflet-Geoman plugin not available.');
        }
    })

    // Function to retrieve saved shape data and restore them on page refresh
    function saveToDatabase(shapeData) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Send the shapeData to your backend for saving to the database
        $.ajax({
                url: '{{ route("save-layer") }}',
                type: 'POST',
                data: shapeData,
                success: function(response) {
                    console.log('Shape data saved successfully:', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error saving shape data:', error);
                }
        });
    }

// Function to get coordinates based on layer type
function getCoordinates(layer) {
    if (layer instanceof L.Circle) {
        // For Circle, return center coordinates and radius
        return [layer.getLatLng().lng, layer.getLatLng().lat, layer.getRadius()];
    } else if (layer instanceof L.Rectangle) {
        // For Rectangle, return southwest and northeast corner coordinates
        var bounds = layer.getBounds();
        return [
            [bounds.getSouthWest().lng, bounds.getSouthWest().lat], // Southwest corner
            [bounds.getNorthEast().lng, bounds.getNorthEast().lat]  // Northeast corner
        ];
    } else {
        // For other geometries like LineString, return latlngs
        return layer.getLatLngs().map(latlng => [latlng.lng, latlng.lat]);
    }
}

// Function to get layer type
function getLayerType(layer) {
    if (layer instanceof L.Circle) {
        return 'circle';
    } else if (layer instanceof L.Rectangle) {
        return 'rectangle';
    } else {
        return 'line';
    }
}

// Function to retrieve saved shape data and restore them on page refresh
function restoreShapesFromDatabase() {
    // Make an AJAX request to fetch saved shape data from the backend
    $.ajax({
        url: 'your-backend-url',
        type: 'GET',
        success: function(savedShapes) {
            savedShapes.forEach(function(shapeData) {
                createShapeFromData(shapeData);
            });
        },
        error: function(xhr, status, error) {
            console.error('Error fetching saved shape data:', error);
        }
    });
}

// Function to create shapes on the map based on saved shape data
function createShapeFromData(shapeData) {
    var shape;
    switch (shapeData.type) {
        case 'circle':
            shape = L.circle(shapeData.latlngs[0], { color: shapeData.color });
            break;
        case 'rectangle':
            shape = L.rectangle(shapeData.latlngs, { color: shapeData.color });
            break;
        case 'line':
            shape = L.polyline(shapeData.latlngs, { color: shapeData.color });
            break;
        // Add cases for other shape types as needed
    }
    // Add the created shape to the map
    shape.addTo(map);
}

</script>
</body>
</html>

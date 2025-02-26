@extends('layouts.admin.app', ['title' => 'All Products'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
div#layer-controls {
    gap: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding-block: 10px;
}

.cvjs_wait_looper {
    display: none !important;
}
    .overlay {
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
	
	aside.main-sidebar.sidebar-dark-primary.elevation-4 ,li.nav-item{
		display: none;
	}
	 .pendent-large-circle {
    stroke-width: 2;
}

.pendent-small-circle {
    stroke-width: 1;
    opacity: 0.7;
}
.pendent-circle-group {
    cursor: pointer; /* Make the entire group clickable */
}


label.required::after {
	content: " *";
	color: #ff0000;
}
/* Overlay styles */
.loading-img,.overlay {
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

    .templateDropdownBox {
        display: inline-block;
        align-items: center;
        gap: 10px;
    }
	.templateDropdownBox select {
		padding: 3px 10px;
		height: auto;
		font-size: 16px;
		outline: none;
	}
	
</style>
@endpush

@section('content')

	<link href="../cadviewer/app/css/cadviewer-core-styles.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="../cadviewer/app/css/font-awesome.min.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="../cadviewer/app/css/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
	<link href="../cadviewer/app/css/jquery.qtip.min.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="../cadviewer/app/css/jquery-ui-1.13.2.min.css" media="screen" rel="stylesheet" type="text/css" />

	<link href="../cadviewer/app/css/cadviewer-bootstrap.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">	
	<script src="../cadviewer/app/js/jquery-2.2.3.js" type="text/javascript"></script>
	<!-- <script src="../cadviewer/app/js/jquery-3.5.1.js" type="text/javascript"></script> -->
	 <script src="../cadviewer/app/js/jquery.qtip.min.js" type="text/javascript"></script> 

	<script src="../cadviewer/app/js/popper.js" type="text/javascript"></script>

	<script src="../cadviewer/app/js/bootstrap-cadviewer.js" type="text/javascript"></script>
	
	<script src="../cadviewer/app/js/jquery-ui-1.13.2.min.js" type="text/javascript"></script>
	<script src="../cadviewer/app/js/eve.js" type="text/javascript" ></script>
	
	<script src="../cadviewer/app/cv/cv-pro/cadviewer.min.js" type="text/javascript" ></script> 

	<script src="../cadviewer/app/cv/cv-pro/custom_rules_template.js" type="text/javascript" ></script>
    <script src="../cadviewer/app/cv/cv-custom_commands/CADViewer_custom_commands.js" type="text/javascript" ></script>

	<script src="../cadviewer/app/cv/cvlicense.js" type="text/javascript" ></script> 
	 
	 
	<script src="../cadviewer/app/js/bootstrap-multiselect.js" type="text/javascript" ></script>
	<script src="../cadviewer/app/js/library_js_svg_path.js" type="text/javascript"></script>			
	<script src="../cadviewer/app/js/snap.svg-min.js" type="text/javascript" ></script>

	<script src="../cadviewer/app/js/cvjs_api_styles_2_0_26.js" type="text/javascript" ></script>
	<script src="../cadviewer/app/js/rgbcolor.js"type="text/javascript" ></script>
	<script src="../cadviewer/app/js/StackBlur.js"type="text/javascript" ></script>
	<script src="../cadviewer/app/js/canvg.js" type="text/javascript"  ></script>
	<script src="../cadviewer/app/js/list.js" type="text/javascript"></script>
	<script src="../cadviewer/app/js/jscolor.js" type="text/javascript" ></script>
	
	<script src="../cadviewer/app/js/jstree/jstree.min.js"></script>
	<script src="../cadviewer/app/js/xml2json.min.js"></script>
	<script src="../cadviewer/app/js/d3.v3.min.js"></script>  
	<script src="../cadviewer/app/js/qrcode.min.js" type="text/javascript"></script> 
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	
	<script type="text/javascript">

	// Location of installation folders
    var ServerBackEndUrl = location.origin+"/cadviewer/";
    var ServerUrl = location.origin+"/cadviewer/";
    var ServerLocation = "";



	// PATH and FILE to be loaded, can be in formats DWG, DXF, DWF, SVG , JS, DGN, PCF, JPG, GIF, PNG
	var FileName = ServerUrl + "content/drawings/dwg/{{$floors->floor_file_name}}.dwg";		
	var JsonFileName = ServerUrl + "content/drawings/json/{{$floors->floor_file_name}}.json";	
	var floorLayerSettings = @json($floors->floor_layer_settings); // Convert to JS object	
	// var FileName = ServerUrl + "/content/drawings/dwg/a2nd_floor.dwg";



	$(document).ready(function(){
		jQuery("#loader, #overlay").show();
		// Set CADViewer with full CADViewer Pro features
		cvjs_CADViewerPro(true);
		cvjs_debugMode(true);
		cvjs_setAllServerPaths_and_Handlers(ServerBackEndUrl, ServerUrl, ServerLocation, "PHP", "JavaScript", "floorPlan");
				
		// uncomment if you want to use NodeJS cadviewer-conversion-server as backend
		//cvjs_setHandlers_FrontEnd("NodeJS", "JavaScript", "floorPlan");
		

		// set to true to embed SpaceObject Menu, false to omit
		cvjs_setSpaceObjectsCustomMenu("/content/customInsertSpaceObjectMenu/", "cadviewercustomspacecommands.json", true);


		cvjs_setOriginatingLocation("origin");


		cvjs_setCADViewerInterfaceVersion(8);
		cvjs_setCADViewerSkin("black"); // lightgray, black, deepblue  // method can be omitted, alternative is "deepblue" , "nextcloud"
	
		
		cvjs_PrintToPDFWindowRelativeSize(0.8);
		cvjs_setFileModalEditMode(false);
	
		var myDrawing = cvjs_GetURLParameter("drawing_name");
		console.log("DRAWING NAME >"+cvjs_GetURLParameter("drawing_name")+"</end>  ");
		if (myDrawing==""){
			console.log("no drawing_name parameter!!!");				
		}
		else{
//			console.log("we pass over to FileName to load Drawing");
			FileName =  myDrawing;
		}

		// For "Merge DWG" / "Merge PDF" commands, set up the email server to send merged DWG files or merged PDF files with redlines/interactive highlight.
		// See php / xampp documentation on how to prepare your server
		cvjs_emailSettings_PDF_publish("From CAD Server", "my_from_address@mydomain.com", "my_cc_address@mydomain.com", "my_reply_to@mydomain.com");

		
		// CHANGE LANGUAGE - DEFAULT IS ENGLISH
        cvjs_loadCADViewerLanguage("English", "");  // "English", "French", "Spanish", "Portuguese", "German", "Indonesian", "Chinese-Traditional", Chinese-Simplified", "Korean", 
		  										//cvjs_loadCADViewerLanguage("English", "/cadviewer/app/cv/cv-pro/custom_language_table/custom_cadviewerProLanguage.xml");
				
		// Set Icon Menu Interface controls. Users can: 
		// 1: Disable all icon interfaces
		//  cvjs_displayAllInterfaceControls(false, "floorPlan");  // disable all icons for user control of interface
		// 2: Disable either top menu icon menus or navigation menu, or both
		//	cvjs_displayTopMenuIconBar(false, "floorPlan");  // disable top menu icon bar
		//	cvjs_displayTopNavigationBar(false, "floorPlan");  // disable top navigation bar
		// 3: Users can change the number of top menu icon pages and the content of pages, based on a configuration file in folder /cadviewer/app/js/menu_config/

		cvjs_setTopMenuXML("floorPlan", "cadviewer_full_commands_01.xml", "/app/cv/cv-pro/menu_config/");
		
				
/*      vertical icon bar sample with integrated zoom icons   - when using this, comment out cvjs_setTopMenuXML("floorPlan", "cadviewer_full_commands_01.xml");   */	
//		cvjs_setTopMenuXML("floorPlan", "cadviewer_verticalmeasurementbar_01.xml"); //cvjs_setTopMenuXML("floorPlan", "cadviewer_full_commands_01.xml", "/app/cv/cv-pro/menu_config/");
//		cvjs_displayZoomIconBar(false, "floorPlan");
// 		cvjs_measurementLinesScaleFactor(1.0);
/*      end vertical icon bar */				
		
		
		// Display Coordinates
//		cvjs_DisplayCoordinatesMenu("floorPlan",true);
				
		
				
		// Initialize CADViewer  - needs the div name on the svg element on page that contains CADViewerJS and the location of the
		// main application "app" folder. It can be either absolute or relative


		// SETTINGS OF THE COLORS OF SPACES
		cvjsRoomPolygonBaseAttributes = {
	            fill: '#D3D3D3',   // #FFF   #ffd7f4
	            "fill-opacity": "0.15",   // 0.1
	            stroke: '#CCC',  
	            'stroke-width': 1,
	            'stroke-linejoin': 'round',
	        };
			
		cvjsRoomPolygonHighlightAttributes = {
						fill: '#a4d7f4',
						"fill-opacity": "0.5",
						stroke: '#a4d7f4',
						'stroke-width': 3
					};
					
		cvjsRoomPolygonSelectAttributes = {
						fill: '#5BBEF6',
						"fill-opacity": "0.5",
						stroke: '#5BBEF6',
						'stroke-width': 3
					};

/** FIXED POP-UP MODAL

		// THIS IS THE DESIGN OF THE pop-up MODAL WHEN CLICKING ON SPACES
		var my_cvjsPopUpBody = "<div class=\"cvjs_modal_1\" onclick=\"my_own_clickmenu1();\">Hello<br>Menu 1<br><i class=\"glyphicon glyphicon-transfer\"></i></div>";
		my_cvjsPopUpBody += "<div class=\"cvjs_modal_1\" onclick=\"my_own_clickmenu2();\">Custom<br>Menu 2<br><i class=\"glyphicon glyphicon-info-sign\"></i></div>";
		my_cvjsPopUpBody += "<div class=\"cvjs_modal_1\" onclick=\"cvjs_zoomHere();\">Zoom<br>Here<br><i class=\"glyphicon glyphicon-zoom-in\"></i></div>";


		// Initialize CADViewer - needs the div name on the svg element on page that contains CADViewerJS and the location of the
		// And we intialize with the Space Object Custom values
		cvjs_InitCADViewer_highLight_popUp_app("floorPlan", ServerUrl+"app/", cvjsRoomPolygonBaseAttributes, cvjsRoomPolygonHighlightAttributes, cvjsRoomPolygonSelectAttributes, my_cvjsPopUpBody );

		 
**/		 
		 
	
/** DYNAMIC POP-UP MODAL ON CALLBACK   **/
	
//      set funtional attributes for popup menu body when clicking on an object
// 		This modal is populated on callback, so this is a placeholder only
		var my_cvjsPopUpBody = "";

//      Setting Space Object Modals Display to be based on a callback method - VisualQuery mode -
//		see documentation:  
//		myCustomPopUpBody is the method with the template for the call back modal  - required to be implemented
//      populateMyCustomPopUpBody is the method which on click will populate the call-back modal dynamically

        cvjs_setCallbackForModalDisplay(true, myCustomPopUpBody, populateMyCustomPopUpBody);
		 
		// Initialize CADViewer - needs the div name on the svg element on page that contains CADViewerJS and the location of the
		// And we intialize with the Space Object Custom values
		cvjs_InitCADViewer_highLight_popUp_app("floorPlan", ServerUrl+"app/", cvjsRoomPolygonBaseAttributes, cvjsRoomPolygonHighlightAttributes, cvjsRoomPolygonSelectAttributes, my_cvjsPopUpBody );
		 
		 		
		 
		// set the location to license key, typically the js folder in main app application folder ../cadviewer/app/js/
		 cvjs_setLicenseKeyPath(ServerUrl+"/app/cv/");
		// alternatively, set the key directly, by pasting in the cvKey portion of the cvlicense.js file, note the JSON \" around all entities 	 
		//cvjs_setLicenseKeyDirect('{ \"cvKey\": \"00110010 00110010 00110000 00110010 00110001 00111001 00111001 00110001 00110100 00111000 00110001 00110100 00110101 00110001 00110101 00110111 00110001 00110101 00111001 00110001 00110100 00111000 00110001 00110101 00110010 00110001 00110100 00110101 00110001 00110100 00110001 00110001 00110100 00110000 00110001 00111001 00110111 00110010 00110000 00110111 00110010 00110000 00110110 00110010 00110000 00110001 00110010 00110001 00110000 00110010 00110000 00111000 00110010 00110001 00110000 00110010 00110000 00111000 00110010 00110001 00110000 00110010 00110000 00110111 00110001 00111001 00111000 00110010 00110000 00110110 00110010 00110000 00111000 00110010 00110000 00110111 00110001 00111001 00111001 00110010 00110001 00110001 00110010 00110000 00111000 00110010 00110000 00110111 00110010 00110001 00110001 00110010 00110000 00110101 00110010 00110000 00111000 \" }');		 
		 
		 

		// Sets the icon interface for viewing, layerhanding, measurement, etc. only
		//cvjs_setIconInterfaceControls_ViewingOnly();

		// disable canvas interface.  For developers building their own interface
		// cvjs_setIconInterfaceControls_DisableIcons(true);

		// Set the icon interface to include image handling
		// cvjs_setIconInterfaceControls_ImageInsert();

		cvjs_allowFileLoadToServer(true);

//		cvjs_setUrl_singleDoubleClick(1);
//		cvjs_encapsulateUrl_callback(true);


		// NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF REDLINES
		// last parameter , set true for dynamic paths
		cvjs_setRedlinesAbsolutePath(ServerUrl+'/content/redlines/v7/', ServerLocation+'/content/redlines/v7/', true);

		// NOTE ABOVE: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF REDLINES


		// NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF FILES AND FILE MANAGER

		// I am setting the full path to the location of the floorplan drawings (typically  /home/myserver/drawings/floorplans/)
		// and the relative location of floorplans drawings relative to my current location
		// as well as the URL to the location of floorplan drawings with username and password if it is protected "" "" if not

		// cvjs_setServerFileLocation(ServerLocation+'/content/drawings/dwg/', '../content/drawings/dwg/', ServerUrl+'/content/drawings/dwg/',"","");
		cvjs_setServerFileLocation_AbsolutePaths(ServerLocation+'/content/drawings/dwg/', ServerUrl+'content/drawings/dwg/',"","");
		// NOTE ABOVE: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF FILES AND FILE MANAGER
		



		cvjs_setInsertImageObjectsAbsolutePath(ServerUrl+'drawings/demo/inserted_image_objects/', ServerLocation+'/drawings/demo/inserted_image_objects/');


		// NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS OF SPACE OBJECTS
		// Set the path to folder location of Space Objects
		cvjs_setSpaceObjectsAbsolutePath(ServerUrl+'/content/spaceObjects/demoUsers/', ServerLocation+'/content/spaceObjects/demoUsers/');
		// NOTE ABOVE: THESE SETTINGS ARE FOR SERVER CONTROLS OF SPACE OBJECTS

		// NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS FOR CONVERTING DWG, DXF, DWF files

		// settings of Converter Path, Controller and Converter Name are done in the XXXHandlerSettings.js files

		cvjs_conversion_clearAXconversionParameters();
		cvjs_conversion_addAXconversionParameter("last", "");		 
		cvjs_conversion_addAXconversionParameter("extents", "");
		cvjs_conversion_addAXconversionParameter("MAPBLOCK", "(F|P).*>*extents*");
		
		
//        cvjs_conversion_addAXconversionParameter("ia", "");		 
//		cvjs_conversion_addAXconversionParameter("ak", "*");		 
		
//		cvjs_conversion_addAXconversionParameter("firstlayout", "");		 

	
//		cvjs_conversion_addAXconversionParameter ("RL", "IDB");
//		cvjs_conversion_addAXconversionParameter ("TL", "IDB_REF");	

		cvjs_conversion_addAXconversionParameter ("hlall", "");	
		
	
		// NOTE ABOVE: THESE SETTINGS ARE FOR SERVER CONTROLS FOR CONVERTING DWG, DXF, DWF files


		// Load file - needs the svg div name and name and path of file to load
		cvjs_LoadDrawing("floorPlan", FileName );

		// set maximum CADViewer canvas side
	    cvjs_resizeWindow_position("floorPlan" );
		// alternatively set a fixed CADViewer canvas size
		//	cvjs_resizeWindow_fixedSize(800, 600, "floorPlan");		
   });  // end ready()




   $(window).resize(function() {
		// set maximum CADViewer canvas side
	    cvjs_resizeWindow_position("floorPlan" );
		// alternatively set a fixed CADViewer canvas size
		//	cvjs_resizeWindow_fixedSize(800, 600, "floorPlan");	
		cvjs_LoadTopIconMenuXML_preconfigured("floorPlan"); //cvjs_setTopMenuXML("floorPlan", "cadviewer_full_commands_01.xml", "/app/cv/cv-pro/menu_config/");
		
   });


/// NOTE: THESE METHODS BELOW ARE JS SCRIPT CALLBACK METHODS FROM CADVIEWER JS, THEY NEED TO BE IMPLEMENTED BUT CAN BE EMPTY


	function cvjs_OnLoadEnd(){
			// generic callback method, called when the drawing is loaded
			// here you fill in your stuff, call DB, set up arrays, etc..
			// this method MUST be retained as a dummy method! - if not implemeted -

			cvjs_resetZoomPan("floorPlan");

			var user_name = "Bob Smith";
			var user_id = "user_1";

			// set a value for redlines
			cvjs_setCurrentStickyNoteValues_NameUserId(user_name, user_id );
			cvjs_setCurrentRedlineValues_NameUserid(user_name, user_id);
			
			/*  If drag-background to front,  so spaceobject or handle interaction
			cvjs_dragBackgroundToFront_SVG("floorPlan");					
			*/

			// Use process handles, if -hlall has been set in conversion to expose AutoCAD DWG Handles
			var processHandles = false;
			if (processHandles){
				cvjs_processHandleObjects();
				cvjs_handleObjectsParceBlocks(false);
			}

			jQuery("#loader, #overlay").hide();
			// activate to print entire document when doing print-to-pdf
			cvjs_overwritePDFOutputParameter(true, "basic", "");
						// Perform your operations
			const svgElement = document.getElementById('floorPlan_svg');
			if (svgElement && svgElement.querySelector('g')) {
				 // showPipeinfo();
				 // Hide all layers in the SVG
				 jQuery('path').css('display', 'none');
				 getLayersData();
				 updateDynamicLayers();
				 const saveTemplateContainer = document.getElementById("save-template-container");
				 saveTemplateContainer.style.display =  hasLayersChanged() ? "inline-block" : "none";

				// populateLayerManagementFromJSON();
				
				// Perform your operations
				jQuery(svgElement).find('rect').remove();
			} else {
				console.log("SVG content not yet loaded.");
			}
			


	}

	
	</script>

<div class="container-fluid mt-4">
	<div class="row">
		<div class="container-fluid mt-4">
			<!-- Button to Open the Modal -->
			<div class="text-center mt-4">
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#layerManagementModal">
				Layer Settings
				</button>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#summaryModal">
				Show Summary
				</button>	
				<button type="button" class="btn btn-primary" id ="saveLayerTask">
				Save Layers & Generate Task
				</button>
				<div id="layer-controls" >
					<div class="save-template-wrapper">
						<label id="save-template-container" style="display:none;margin: 0">
							<input type="checkbox" id="save-as-template" >
							Save as a template
						</label>
					</div>
					<!-- Hidden input field for template name -->
					<div id="template-name-container" style="display: none;">
						<input type="text" id="template-name" placeholder="Enter template name">
					</div>		
					<div class="templateDropdownBox">				
						<select id="templateDropdown">
							<option value="">Select a Template</option>
						</select>
					</div>
				</div>
        <!-- Loader and Overlay -->
        <div class="overlay" id="overlay"></div>
        <div class="loader-container" id="loader">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
			</div>
			<div class="col-lg-12 col-md-12 mb-12">
				<div id="floorPlan" class="cadviewer-bootstrap cadviewer-core-styles">
				  CAD Viewer Layers View
				</div>
			</div>
 		</div>
    </div>
</div>
<!-- Modal Structure -->
<div class="modal fade" id="layerManagementModal" tabindex="-1" aria-labelledby="layerManagementModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="layerManagementModalLabel">Layer Management</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body">
        <!-- Container to display the Layer Management UI -->
        <div class="container">
          <div class="card shadow-lg p-4">
            <!-- Table Header -->
            <div class="row border-bottom py-2 text-muted">
              <div class="col-4">Layer Name</div>
              <div class="col-2">Pipe</div>
              <div class="col-2">Head</div>
              <div class="col-2 d-flex justify-content-between">
                <span>Lock</span>
                <span>Hide</span>
              </div>
            </div>

            <!-- Layers List -->
            <div class="overflow-auto" style="max-height: calc(100vh - 240px); overflow-x: hidden !important;" id="layer-list">
              <!-- Layers will be populated here dynamically -->
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Structure for Detailed Summary -->
<div class="modal fade" id="summaryModal" tabindex="-1" aria-labelledby="summaryModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="detailedSummaryModalLabel">Detailed Layer Summary</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Modal Body for Detailed Summary -->
      <div class="modal-body">
        <div class="container">
          <div class="card shadow-lg p-4">
            <h4>Total Layer Summary</h4>
            <div id="summaryContainer">
              <!-- Detailed summary will be populated here dynamically -->
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Footer with Close Button -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>
<!-- jQuery Script to Extract Layers from JSON and Populate Table -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
    document.getElementById("save-as-template").addEventListener("change", function () {
        const templateContainer = document.getElementById("template-name-container");
        templateContainer.style.display = this.checked ? "inline-block" : "none";
		document.getElementById("template-name").value = ""; // Clears the input field
		document.getElementById("template-name").focus();
    });

    function populateTemplateDropdown(apiResponse) {
        const templateDropdown = document.getElementById("templateDropdown");

        apiResponse.layerTemplates.forEach(template => {
            const option = document.createElement("option");
            option.value = template.template_id;
            option.textContent = template.template_name;
            option.setAttribute("data-layers", template.template_layers); // Store layers in data attribute
			// Check if this template is the default
			if (template.is_default === 1) {
				option.selected = true; // Mark the option as selected
			}			
            templateDropdown.appendChild(option);
        });
    }

    function updateLayersData() {
        const templateDropdown = document.getElementById("templateDropdown");
        
        // Get selected option
        const selectedOption = templateDropdown.options[templateDropdown.selectedIndex];

        if (selectedOption && selectedOption.value) {
            layersData = JSON.parse(selectedOption.getAttribute("data-layers"));
			updateDynamicLayers();
			
        } else {
            layersData = []; // Clear if no template is selected
        }
    }

    // Update layersData on template change
    document.getElementById("templateDropdown").addEventListener("change", updateLayersData);

	
let layersData = [];

let originalLayersData = []; // Store initial layersData

// Function to set initial layersData (Call this when loading data)
function setInitialLayersData(data) {
    originalLayersData = JSON.parse(JSON.stringify(data));
}

// Function to check if layersData has changed
function hasLayersChanged() {
    return JSON.stringify(originalLayersData) !== JSON.stringify(layersData);
}
function fetchCompanyLayers() {
	let companyId = {!! auth()->user()->id !!};
    const apiUrl = "{{route('layers.getCompanyLayers')}}?company_id="+companyId;

    fetch(apiUrl, {
        method: "GET",
        headers: {
            "Content-Type": "application/json"
        }
    })
    .then((response) => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then((data) => {
        layersData = data.layersData;
		setInitialLayersData(data.layersData);
		// Populate the template dropdown on load
		populateTemplateDropdown(data);
    })
    .catch((error) => {
        console.error("Error fetching layers data:", error);
    });
}

function getLayersData(){
	let floorLayerSetting = JSON.parse(floorLayerSettings);
    // Check if floorLayerSettings has valid data
    if (floorLayerSetting && floorLayerSetting.length > 0) {
		layersData = floorLayerSetting;
    } 
}


// Fetch layers data when the page loads
 document.addEventListener("DOMContentLoaded", fetchCompanyLayers);
// document.addEventListener("DOMContentLoaded", () => {
	// const floorLayerSettings = JSON.parse(floorLayerSettings);
    // // Check if floorLayerSettings has valid data
    // if (floorLayerSettings && floorLayerSettings.length > 0) {
        // //layersData = floorLayerSettings;
		// layersData = floorLayerSettings;
    // } else {
        // fetchCompanyLayers();
    // }
// });

function getFloorId() {
    const urlParams = new URLSearchParams(window.location.search);
    const floorId = urlParams.get('floor_id');
    return floorId;
}
const floor_id = getFloorId(); 

document.getElementById("saveLayerTask").addEventListener("click", function () {
	let floorLayerSetting = JSON.parse(floorLayerSettings);
	
	flag  = false;
	if(jQuery('#t_pipecnt').length > 0 || jQuery('#t_headcnt').length > 0){
		flag = true;
	}
	if(flag){
        const saveAsTemplate = document.getElementById("save-as-template").checked;
        const templateName = document.getElementById("template-name").value.trim();			
		if(hasLayersChanged() && templateName === ""){
			if (confirm("Are you sure you want to save as a template")) {
				document.getElementById("save-as-template").checked = true;
				document.getElementById("template-name-container").style.display = "inline-block";
				document.getElementById("template-name").focus();
				if (saveAsTemplate && templateName === "") {
					alert("Template name is required");
					return;
				} 
				return;
		
			} 	
		}
    const inputName = templateName.toLowerCase();
    const templateDropdown = document.getElementById("templateDropdown");
    let nameExists = false;

    for (let option of templateDropdown.options) {
        if (option.textContent.trim().toLowerCase() === inputName) {
            nameExists = true;
            break;
        }
    }

    if (nameExists) {
        alert("Template name already exists!");
        document.getElementById("template-name").focus();
		return;
    }
	if(checkExistingLayers(layersData))
		return;
		
	
		
		if (floorLayerSetting && floorLayerSetting.length > 0) {
			if (confirm("Are you sure you want to regenerate tasks!!!")) {
				$("#overlay, #loader").css({'display':'block'});
				generateTasks(templateName); 
			} 
		}
		else {
			generateTasks(templateName);
		}
	}
	else{
		alert("Please select any layer");
	}
});

function checkExistingLayers(layersData) {
	const templateName = document.getElementById("template-name").value.trim();		
	if(templateName !== ""){
		let normalizedLayersData = JSON.stringify(normalizeLayers(layersData)); // Normalize and convert to JSON string
		for (let option of templateDropdown.options) {
			if (option.value !== "") { // Ignore default "Select a Template" option
				let optionLayers = option.getAttribute("data-layers");
				if (optionLayers) {
					let parsedLayers = JSON.parse(optionLayers);
					let normalizedOptionLayers = JSON.stringify(normalizeLayers(parsedLayers));

					if (normalizedOptionLayers === normalizedLayersData) {
						alert(`Layers already exist in template: ${option.text}`);
						return true;
					}
				}
			}
		}
	}
	return false;
}

function normalizeLayers(layers) {
	return layers.map(layer => ({
		...layer,
		type: layer.type === null ? "" : layer.type // Replace null with ""
	})
	);
}
		
function generateTasks(templateName){
			// Disable the button to prevent multiple submissions
			const button = this;
			button.disabled = true;
			button.innerHTML = "Processing...";

			let companyId = {!! auth()->user()->id !!};
			// Make the API call
			fetch("{{route('floors.generateTasks')}}", {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
				},
				body: JSON.stringify({
					layersData: layersData,
					floor_id:floor_id,
					template_name: templateName,
					company_id: companyId
				})
			})
			.then(response => {
				if (!response.ok) {
					throw new Error("Failed to generate tasks. Please try again.");
				}
				return response.json();
			})
			.then(data => {
				console.log("API Response:", data);
				// Redirect to /floor on success
				if(data.success){
					alert(data.message);
					window.location.href = "/cadeditor-new?floor_id="+floor_id;
				}
				else{
					alert(data.error);
					return;
				}
			})
			.catch(error => {
				console.error("Error:", error);
				alert("An error occurred. Please try again.");
			})
			.finally(() => {
				// Re-enable the button after processing
				button.disabled = false;
				button.innerHTML = "Save Layers & Generate Task";
			});	
}

// Function to populate the layer list from JSON data
function populateLayerManagementFromJSON() {
  // Get the parent container for the layer list
  const layerContainer = $('#layer-list');

  // Clear the current layer list
  layerContainer.empty();

  // Iterate through each layer and create the table rows dynamically
  layersData.forEach((layer, index) => {
    const layerRow = `
      <div class="row py-2 align-items-center">
        <div class="col-4">
          <span class="text-muted">${layer.layer_name}</span>
        </div>
        <div class="col-2">
          <input type="checkbox" name="layer-${index}-pipe" class="form-check-input layer-pipe" data-index="${index}" ${layer.type === 'pipe' ? 'checked' : ''}>
        </div>
        <div class="col-2">
          <input type="checkbox" name="layer-${index}-head" class="form-check-input layer-head" data-index="${index}" ${layer.type === 'head' ? 'checked' : ''}>
        </div>
        <div class="col-2">
          <input type="checkbox" class="form-check-input layer-lock" data-index="${index}" ${layer.lock ? 'checked' : ''}>
        </div>
        <div class="col-2">
          <input type="checkbox" class="form-check-input layer-hide" data-index="${index}" ${layer.hide ? 'checked' : ''}>
        </div>
      </div>
    `;

    // Append the new row to the layer container
    layerContainer.append(layerRow);
  });

  // Attach Change Events
  document.querySelectorAll(".layer-lock").forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      const index = this.dataset.index; // Now correctly references the index
      layersData[index].lock = this.checked ? 1 : 0;
    });
  });

  document.querySelectorAll(".layer-hide").forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      const index = this.dataset.index; // Now correctly references the index
      layersData[index].hide = this.checked ? 1 : 0;
	  updateDynamicLayers();
    });
  });

	$('.layer-pipe').change(function () {
		const index = $(this).data('index');
		const isChecked = $(this).is(':checked');

		// Uncheck "Head" if "Pipe" is checked
		if (isChecked) {
			$(`input[name="layer-${index}-head"]`).prop('checked', false);
			layersData[index].type = 'pipe';
		} else {
			// If "Pipe" is unchecked, set type to an empty string
			layersData[index].type = '';
		}
		const saveTemplateContainer = document.getElementById("save-template-container");
		saveTemplateContainer.style.display =  hasLayersChanged() ? "inline-block" : "none";		
		updateDynamicLayers();
	});

	$('.layer-head').change(function () {
		const index = $(this).data('index');
		const isChecked = $(this).is(':checked');

		// Uncheck "Pipe" if "Head" is checked
		if (isChecked) {
			$(`input[name="layer-${index}-pipe"]`).prop('checked', false);
			layersData[index].type = 'head';
		} else {
			// If "Head" is unchecked, set type to an empty string
			layersData[index].type = '';
		}
		const saveTemplateContainer = document.getElementById("save-template-container");
		saveTemplateContainer.style.display =  hasLayersChanged() ? "inline-block" : "none";		
		updateDynamicLayers();
	});

}

// Populate layers on modal show
$('#layerManagementModal').on('show.bs.modal', function () {
  populateLayerManagementFromJSON();
});

function addPendentCircle(pathElement) {
    const bbox = pathElement[0].getBBox();
    const cx = bbox.x + bbox.width / 2;
    const cy = bbox.y + bbox.height / 2;


    // Create an SVG group to make the entire structure clickable
    const group = document.createElementNS("http://www.w3.org/2000/svg", "g");
    group.setAttribute("class", "pendent-circle-group");
	
    // Outer circle (Large circle)
    const largeCircle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
    largeCircle.setAttribute("class", "large-circle");
    largeCircle.setAttribute("class", "sprinkler");
    largeCircle.setAttribute("cx", cx);
    largeCircle.setAttribute("cy", cy);
    largeCircle.setAttribute("r", 20); // Larger radius for the outer circle
    largeCircle.setAttribute("fill", '#9CA3AF'); // Fill color based on task status
    largeCircle.setAttribute("stroke", '#9CA3AF'); 

    // Middle circle (White circle)
    const middleCircle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
    middleCircle.setAttribute("class", "middle-circle");
    middleCircle.setAttribute("cx", cx);
    middleCircle.setAttribute("cy", cy);
    middleCircle.setAttribute("r", 15); // Radius between large and small circle
    middleCircle.setAttribute("fill", "white"); // Fill color white
    middleCircle.setAttribute("stroke", "none"); // No stroke for the middle circle

    // Inner circle (Small circle)
    const smallCircle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
    smallCircle.setAttribute("class", "small-circle");
    smallCircle.setAttribute("class", "sprinkler");
    smallCircle.setAttribute("cx", cx);
    smallCircle.setAttribute("cy", cy);
    smallCircle.setAttribute("r", 10); // Smaller radius for the inner circle
    smallCircle.setAttribute("fill", '#9CA3AF'); 
    smallCircle.setAttribute("stroke", '#9CA3AF'); 

    // Append circles to the group
    group.appendChild(largeCircle);
    group.appendChild(middleCircle);
    group.appendChild(smallCircle);

    // Append the group to the parent
    pathElement.parent()[0].appendChild(group);
}

function addUprightCircle(pathElement) {
    const bbox = pathElement[0].getBBox();
    const cx = bbox.x + bbox.width / 2;
    const cy = bbox.y + bbox.height / 2;

    const circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
	
    circle.setAttribute("class", "upright-circle");
	circle.setAttribute("class", "sprinkler");
    circle.setAttribute("cx", cx);
    circle.setAttribute("cy", cy);
    circle.setAttribute("r", 15);
    circle.setAttribute("stroke", '#9CA3AF');
    circle.setAttribute("fill", 'none');
    jQuery(circle).css('cursor', 'pointer');

    // Append the circle to the parent
    pathElement.parent()[0].appendChild(circle);
}

function addTriangleBox(pathElement) {
    const bbox = pathElement[0].getBBox(); // Get bounding box of the path element
    const scaleFactor = 2; // Slightly increased scale factor for a larger triangle

    // Adjust the points for a larger right-pointing triangle
    const points = [
        [bbox.x + bbox.width + scaleFactor * 10, bbox.y + bbox.height / 2], // Right point (center of right side)
        [bbox.x - scaleFactor * 10, bbox.y + bbox.height + scaleFactor * 5], // Bottom-left corner
        [bbox.x - scaleFactor * 10, bbox.y - scaleFactor * 5], // Top-left corner
    ];

    // Log the bounding box for debugging purposes
    console.log('BBox:', bbox);

    const polygon = document.createElementNS("http://www.w3.org/2000/svg", "polygon");

    polygon.setAttribute("class", "sidewall-triangle sprinkler"); // Set both classes
    polygon.setAttribute("points", points.map((p) => p.join(",")).join(" "));


    polygon.setAttribute("stroke", '#9CA3AF'); // Set stroke color
    polygon.setAttribute("fill", 'none'); 

    // Append the polygon to the parent SVG element
    pathElement.parent()[0].appendChild(polygon);
}

// Helper function to parse feet-inches format
function parseLength(feetInchesStr) {
    const regex = /(\d+)'-?\s*(\d*)"?/; // Matches `9'-2`, `9'-2"`, `1242'-0`, etc.
    const match = feetInchesStr.match(regex);
    if (match) {
        const feet = parseInt(match[1], 10) || 0; // Extract feet
        const inches = parseInt(match[2], 10) || 0; // Extract inches
        return feet + (inches / 12); // Convert inches to feet and add
    }
    console.warn('Invalid length format:', feetInchesStr);
    return 0; // Return 0 if format doesn't match
}

// Helper function to format length back to feet-inches
function formatLength(totalFeet) {
    const feet = Math.floor(totalFeet);
    const inches = Math.round((totalFeet - feet) * 12);
    return `${feet}'-${inches}"`;
}



// Specify the path to the external JSON file

// const jsonFilePath = '/cadviewer/content/drawings/json/a1st_floor.json';
const jsonFilePath = JsonFileName;

// Variable to hold the JSON data
let jsonData = [];

// Function to fetch the JSON data from an external file
async function fetchJsonData() {
  try {
    // Fetch the data from the external JSON file
    const response = await fetch(jsonFilePath);
    
    // Check if the response is valid
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    // Parse the JSON data
    jsonData = await response.json();

    // Create an array to hold the formatted data
    const formattedData = [];

    // Loop through the BlockRefs and extract the relevant data
    jsonData.Drawing.BlockRefs.forEach(blockRef => {
      // Ensure blockRef.Attributes exists and is an array
      const attributes = Array.isArray(blockRef.Attributes) ? blockRef.Attributes : [];
      
      // Check for the tag "Length" and get the "String" value or "N/A" if not found
      const lengthTag = attributes.find(attr => attr.Tag === "Length");
      const length = lengthTag ? lengthTag.String.trim() : "N/A";

      formattedData.push({
        layer_name: blockRef.Layer,
        handle_name: blockRef.Handle,
        length: length
      });
    });

    // Assign the formatted data to jsonData
    jsonData = formattedData;

  } catch (error) {
    // Handle any errors that occur during the fetch or processing
    console.error('Error fetching or parsing the JSON data:', error);
  }
}

// Call the function to fetch and process the JSON data
fetchJsonData();
/*
function updateDynamicLayers() {
    let pipesTotalCount = 0;
    let pipesTotalLength = 0;
    let headsTotalCount = 0;

    let layersSummaryHtml = '';
    layersData.forEach(function(layer) {
        let layerPipesCount = 0;
        let layerPipesLength = 0;
        let layerHeadsCount = 0;

        const layerSelector = `[cvjs\\:layername="${layer.layer_name}"]`;
        const layerElement = jQuery(layerSelector);

        layerElement.addClass('cursor-pointer');

        // Skip hidden layers or layers with both "Pipe" and "Head" unchecked
        if (layer.hide || (layer.type !== 'pipe' && layer.type !== 'head')) {
            layerElement.css('display', 'none');
            return;
        } else {
            layerElement.css('display', 'block');
        }

        // Process pipes
        if (layer.type === 'pipe') {
            layerElement.find('path').each(function () {
                const pathElement = jQuery(this);
                pathElement.attr('stroke', '#9CA3AF');
                pathElement.attr('fill', '#9CA3AF');
                pathElement.css('display', 'block');
                layerPipesCount++;

                // Retrieve handle and match with jsonData
                const blockHandle = pathElement.attr('cvjs:bhandle');
                if (blockHandle) {
                    // const jsonLayer = jsonData.find(item => item.layer_name === layer.layer_name && item.handle_name === blockHandle);
					const jsonLayer = jsonData.find(item => item.layer_name.toLowerCase() === layer.layer_name.toLowerCase() &&
					item.handle_name === blockHandle
				);
                    if (jsonLayer && jsonLayer.length) {
                        const parsedLength = parseLength(jsonLayer.length);
                        layerPipesLength += parsedLength;
                    }
                }
            });
        }
        // Process heads
        else if (layer.type === 'head') {
            layerElement.find('path').each(function () {
                const pathElement = jQuery(this);
                pathElement.attr('stroke', '#9CA3AF');
                pathElement.attr('fill', '#9CA3AF');
                pathElement.css('display', 'block');
                // addCircle(pathElement);
				                                // Add shapes based on sprinkler type
                                if (layer.layer_name.toLowerCase().includes('pendent')) {
									addPendentCircle(pathElement);

                                } else if (layer.layer_name.toLowerCase().includes('upright')) {
                                    // addUprightCircle(jPathElement, taskName, task.task_id, task.task_status.status_name);
                                    addUprightCircle(pathElement);
                               } else if (layer.layer_name.toLowerCase().includes('sidewall') || layer.layer_name.toLowerCase().includes('side wall')) {
                                    // addTriangleBox(jPathElement, taskName, task.task_id, task.task_status.status_name);
                                    addTriangleBox(pathElement);
                                }
								
                layerHeadsCount++;
            });
        }

        // Add layer details to the summary (conditionally include Pipe or Head details)
        layersSummaryHtml += `
            <h4>Layer: ${layer.layer_name}</h4>
            ${layer.type === 'pipe' ? `
                <div>Pipes Count: ${layerPipesCount}</div>
                <div>Pipes Total Length: ${formatLength(layerPipesLength)}</div>
            ` : ''}
            ${layer.type === 'head' ? `
                <div>Heads Count: ${layerHeadsCount}</div>
            ` : ''}
            <hr />
        `;

        // Accumulate total counts and lengths
        pipesTotalCount += layerPipesCount;
        pipesTotalLength += layerPipesLength;
        headsTotalCount += layerHeadsCount;
    });

    // Add total summary
    layersSummaryHtml += `
        <h4>Total Summary:</h4>
        ${pipesTotalCount > 0 ? `
            <div><strong>Total Pipes Count: <span id="t_pipecnt">${pipesTotalCount}</span></strong></div>
            <div><strong>Total Pipes Length: ${formatLength(pipesTotalLength)}</strong></div>
        ` : ''}
        ${headsTotalCount > 0 ? `
            <div><strong>Total Heads Count: <span id="t_headcnt">${headsTotalCount}</span></strong></div>
        ` : ''}
    `;

    jQuery('#summaryContainer').html(layersSummaryHtml);
}
*/
function updateDynamicLayers() {
    let pipesTotalCount = 0;
    let pipesTotalLength = 0;
    let headsTotalCount = 0;

    let layersSummaryHtml = '';

    layersData.forEach(function (layer) {
        let layerPipesCount = 0;
        let layerPipesLength = 0;
        let layerHeadsCount = 0;

        const layerSelector = '[cvjs\\:layername]';
        
        const layerElement = jQuery(layerSelector).filter(function () {
            return (jQuery(this).attr("cvjs:layername") || "").toLowerCase() === layer.layer_name.toLowerCase();
        });

        layerElement.addClass('cursor-pointer');

        // Skip hidden layers or those without valid "Pipe" or "Head" types
        if (layer.hide || (layer.type !== 'pipe' && layer.type !== 'head')) {
            layerElement.css('display', 'none');
            return;
        } else {
            layerElement.css('display', 'block');
        }

        if (layer.type === 'pipe') {
            layerElement.find('path').each(function () {
                const pathElement = jQuery(this);
                pathElement.attr('stroke', '#9CA3AF');
                pathElement.attr('fill', '#9CA3AF');
                pathElement.css('display', 'block');
                layerPipesCount++;

                // Retrieve block handle and match with jsonData (case-insensitive)
                const blockHandle = pathElement.attr('cvjs:bhandle');
                if (blockHandle) {
                    const jsonLayer = jsonData.find(item => 
                        item.layer_name.toLowerCase() === layer.layer_name.toLowerCase() &&
                        item.handle_name === blockHandle
                    );

                    if (jsonLayer) {
                        const parsedLength = parseLength(jsonLayer.length);
                        layerPipesLength += parsedLength;
                    }
                }
            });
        }

        else if (layer.type === 'head') {
            layerElement.find('path').each(function () {
                const pathElement = jQuery(this);
                pathElement.attr('stroke', '#9CA3AF');
                pathElement.attr('fill', '#9CA3AF');
                pathElement.css('display', 'block');

                // **Case-Insensitive Layer Name Filtering**
                const layerNameLower = layer.layer_name.toLowerCase();
                if (layerNameLower.includes('pendent')) {
                    addPendentCircle(pathElement);
                } else if (layerNameLower.includes('upright')) {
                    addUprightCircle(pathElement);
                } else if (layerNameLower.includes('sidewall') || layerNameLower.includes('side wall')) {
                    addTriangleBox(pathElement);
                }

                layerHeadsCount++;
            });
        }

        layersSummaryHtml += `
            <h4>Layer: ${layer.layer_name}</h4>
            ${layer.type === 'pipe' ? `
                <div>Pipes Count: ${layerPipesCount}</div>
                <div>Pipes Total Length: ${formatLength(layerPipesLength)}</div>
            ` : ''}
            ${layer.type === 'head' ? `
                <div>Heads Count: ${layerHeadsCount}</div>
            ` : ''}
            <hr />
        `;

        pipesTotalCount += layerPipesCount;
        pipesTotalLength += layerPipesLength;
        headsTotalCount += layerHeadsCount;
    });

    layersSummaryHtml += `
        <h4>Total Summary:</h4>
        ${pipesTotalCount > 0 ? `
            <div><strong>Total Pipes Count: <span id="t_pipecnt">${pipesTotalCount}</span></strong></div>
            <div><strong>Total Pipes Length: ${formatLength(pipesTotalLength)}</strong></div>
        ` : ''}
        ${headsTotalCount > 0 ? `
            <div><strong>Total Heads Count: <span id="t_headcnt">${headsTotalCount}</span></strong></div>
        ` : ''}
		
    `;

    jQuery('#summaryContainer').html(layersSummaryHtml);
}

</script>


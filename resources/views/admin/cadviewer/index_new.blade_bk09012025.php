@extends('layouts.admin.app', ['title' => 'All Products'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')

	<link href="../cadviewer/app/css/cadviewer-core-styles.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="../cadviewer/app/css/font-awesome.min.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="../cadviewer/app/css/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
	<link href="../cadviewer/app/css/jquery.qtip.min.css" media="screen" rel="stylesheet" type="text/css" />
	<link href="../cadviewer/app/css/jquery-ui-1.13.2.min.css" media="screen" rel="stylesheet" type="text/css" />

	<link href="../cadviewer/app/css/cadviewer-bootstrap.css" media="screen" rel="stylesheet" type="text/css" />
	
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


    <!--script src="https://cdn.jsdelivr.net/npm/chart.js"></script-->	   

		<script id="matech-json-data" class="matech-json-new">
			const jsonData = @json($tasks);  // The JSON passed from the controller
			const jsonMemberData = @json($members);  // The JSON passed from the controller
		</script>
		<script id="matechjsondata">@json($tasks)</script>
		<script id="jsonMemberData">@json($members)</script>
	
	<script type="text/javascript">

	// Location of installation folders
    var ServerBackEndUrl = location.origin+"/cadviewer/";
    var ServerUrl = location.origin+"/cadviewer/";
    var ServerLocation = "";



	// PATH and FILE to be loaded, can be in formats DWG, DXF, DWF, SVG , JS, DGN, PCF, JPG, GIF, PNG
	var FileName = ServerUrl + "/content/drawings/dwg/{{$floors->floor_file_name}}";		
	// var FileName = ServerUrl + "/content/drawings/dwg/a2nd_floor.dwg";



	$(document).ready(function(){
		const task_json = JSON.parse(document.getElementById("matechjsondata").innerHTML); // Retrieve the data defined in the script tag

		// Initialize counters
		let totalTasks = 0;
		let completedTasks = 0;
		let pendingTasks = 0;
		let holdTasks = 0;

		let totalLength = 0; // Total length of all tasks
		let completedLength = 0; // Total length of completed tasks
		let pendingLength = 0; // Total length of pending tasks (pending)
		let holdLength = 0; // Total length of hold tasks (On Hold)

		let totalSprinklers = 0;
		let coverSprinklers = 0;
		let pendingSprinklers = 0;
		let droppingSprinklers = 0;
		let securingSprinklers = 0;

// Iterate through the task array
task_json.forEach(task => {
	if(task.task_type == 'pipe'){ 
		let totalInches = task.length_in_inches
		totalLength += totalInches;
		const feetAndInches = convertToFeetAndInches(totalInches);

		  totalTasks++;
		  switch (task.status_name) {
			case 'Completed':
			  completedTasks++;
			  completedLength += totalInches;
			  break;
			case 'Working':
			  pendingTasks++;
			  pendingLength += totalInches;
			  break;
			case 'On Hold':
			  holdTasks++;
			  holdLength += totalInches;
			  break;
			// Add more cases if there are other statuses
			default:
			  break;
		}
	}
	else{
		  totalSprinklers++;
		  switch (task.status_name) {
			case 'Covers':
			  coverSprinklers++;
			  break;
			case 'Securing':
			  securingSprinklers++;
			  break;
			case 'Dropping Or Flex':
			  droppingSprinklers++;
			  break;
			case 'Pending':
			  pendingSprinklers++;
			  break;
			// Add more cases if there are other statuses
			default:
			  break;
		}		
	}
});

function convertToInches(lengthStr) {
    // Regular expression to match the feet and inches parts
    const match = lengthStr.match(/(\d+)'-(\d+)/);
    if (!match) {
        console.error("Invalid length format");
        return null;
    }

    const feet = parseInt(match[1], 10); // Extract feet part and convert to number
    const inches = parseInt(match[2], 10); // Extract inches part and convert to number

    // Convert to total inches
    const totalInches = feet * 12 + inches;
    return totalInches;
}

// Convert lengths back to feet and inches for readability
const totalFeetAndInches = convertToFeetAndInches(totalLength);
const completedFeetAndInches = convertToFeetAndInches(completedLength);
const pendingFeetAndInches = convertToFeetAndInches(pendingLength);
const holdFeetAndInches = convertToFeetAndInches(holdLength);


// Update the HTML elements
document.getElementById('total_task').textContent = totalTasks;
document.getElementById('completed_task').textContent = completedTasks;
document.getElementById('pending_task').textContent = pendingTasks;
document.getElementById('hold_task').textContent = holdTasks;

// Update the HTML elements
document.getElementById('total_sprinklers').textContent = totalSprinklers;
document.getElementById('cover_sprinklers').textContent = coverSprinklers;
document.getElementById('secure_sprinklers').textContent = securingSprinklers;
document.getElementById('dropping_sprinklers').textContent = droppingSprinklers;
document.getElementById('pending_sprinklers').textContent = pendingSprinklers;

document.getElementById('total_length').textContent = totalFeetAndInches;
document.getElementById('completed_length').textContent = completedFeetAndInches;
document.getElementById('pending_length').textContent = pendingFeetAndInches;
document.getElementById('hold_length').textContent = holdFeetAndInches;	

		//$(document).html();
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
	
		// http://127.0.0.1:8081/html/CADViewer_json_610.html?drawing_name=/home/mydrawing.dgn&dgn_workspace=/home/workspace.txt&json_location=c:/nodejs/cadviewer/content/helloworld.json&print_modal_custom_checkbox=add_json

		// IF CADVIEWER IS OPENED WITH A URL  http://localhost/cadviewer/html/CADViewer_sample_610.html?drawing_name=../content/drawings/dwg/hq17.dwg
		//  or CADViewer_sample_610.html?drawing_name=http://localhost/cadviewer/content/drawings/dwg/hq17.dwg
		//  this code segment will pass over the drawing_name to FileName for load of drawing

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


			// activate to print entire document when doing print-to-pdf
			cvjs_overwritePDFOutputParameter(true, "basic", "");
						// Perform your operations
			const svgElement = document.getElementById('floorPlan_svg');
			if (svgElement && svgElement.querySelector('g')) {
				 showPipeinfo();
				//updatePipesFromAPI();

				
				// Perform your operations
				jQuery(svgElement).find('rect').remove();
			} else {
				console.log("SVG content not yet loaded.");
			}
			


	}


	function cvjs_OnLoadEndRedlines(){
			// generic callback method, called when the redline is loaded
			// here you fill in your stuff, hide specific users and lock specific users
			// this method MUST be retained as a dummy method! - if not implemeted -

			// I am hiding users added to the hide user list
			cvjs_hideAllRedlines_HiddenUsersList();

			// I am freezing users added to the lock user list
			cvjs_lockAllRedlines_LockedUsersList();


	}


	// generic callback method, tells which FM object has been clicked
	function cvjs_change_space(){

		//window.alert("cvjs_change_space ");
	

	}


	function cvjs_ObjectSelected(rmid){
	
		var e = window.event;
		var posX = e.clientX;
		var posY = e.clientY;
	
		myBoundingBox = cvjs_ObjectBoundingBox_ScreenCoord(rmid);
				
		console.log("See callback: cvjs_ObjectSelected() "+rmid+" BBox: "+myBoundingBox.x+" "+myBoundingBox.y+" "+myBoundingBox.x2+" "+myBoundingBox.y2+" mouse x,y "+posX+"  "+posY);

		// hideOnlyPop(rmid);		
		//  
	 	// placeholder for method in tms_cadviewerjs_modal_1_0_14.js   - must be removed when in creation mode and using creation modal
	}

/// NOTE: THESE METHODS ABOVE ARE JS SCRIPT CALLBACK METHODS FROM CADVIEWER JS, THEY NEED TO BE IMPLEMENTED BUT CAN BE EMPTY


/// NOTE: BELOW REDLINE SAVE LOAD CONTROLLERS

// This method is linked to the save redline icon in the imagemap
function cvjs_saveStickyNotesRedlinesUser(){

	// there are two modes, user handling of redlines
	// alternatively use the build in redline file manager

	cvjs_openRedlineSaveModal("floorPlan");

	// custom method startMethodRed to set the name and location of redline to save
	// see implementation below
	//startMethodRed();
	// API call to save stickynotes and redlines
	//cvjs_saveStickyNotesRedlines("floorPlan");
}


// This method is linked to the load redline icon in the imagemap
function cvjs_loadStickyNotesRedlinesUser(){


	cvjs_openRedlineLoadModal("floorPlan");

	// first the drawing needs to be cleared of stickynotes and redlines
	//cvjs_deleteAllStickyNotes();
	//cvjs_deleteAllRedlines();

	// custom method startMethodRed to set the name and location of redline to load
	// see implementation below
	// startMethodRed();

	// API call to load stickynotes and redlines
	//cvjs_loadStickyNotesRedlines("floorPlan");
}

/// NOTE: ABOVE REDLINE SAVE LOAD CONTROLLERS


	var cvjsPopUpBody;

	function myCustomPopUpBody(rmid){


		cvjs_setQtipLocation("bottom center", "top left");
		cvjs_styleQTip_color(false, '#3DCD5D', '#293133', '#293133', '#293133', '#293133');  // we use standard colors, first parameter is false
		cvjsPopUpBody = "<div class=\"cvjs_modal_1\" onclick=\"my_own_clickmenu1();\">Hello<br>Menu 1<br><i class=\"fa fa-level-down\"></i></div>";
		cvjsPopUpBody += "<div class=\"cvjs_modal_1\" onclick=\"my_own_clickmenu2();\">Custom<br>Menu 2<br><i class=\"fa fa-info\"></i></div>";
		cvjsPopUpBody += "<div class=\"cvjs_modal_1\" onclick=\"cvjs_zoomHere();\">Zoom<br>Here<br><i class=\"fa fa-search-plus\"></i></div>";


		if (true) 		return cvjsPopUpBody;
  // see below for custom programming




		// template pop-up modal body
		cvjsPopUpBody = "<div>Space Id: <span id=\"mymodal_name_"+rmid+"\" ></span><br>";
		cvjsPopUpBody += "Survey: <span id=\"mymodal_survey_"+rmid+"\" ></span><br>";
		cvjsPopUpBody += "Notice: <span id=\"mymodal_notice_"+rmid+"\" ></span><br>";
//		cvjsPopUpBody += "Status: <div class=\"cvjs_callback_modal_1\" onclick=\"my_own_clickmenu1("+rmid+");\"><i class=\"glyphicon glyphicon-transfer\"></i>More Info </div>";
		cvjsPopUpBody += "Status: <a href=\"javascript:my_own_clickmenu1('"+rmid+"');\">More Info <i class=\"glyphicon glyphicon-transfer\" onclick=\"my_own_clickmenu1("+rmid+");\"></i></a> ";

		return cvjsPopUpBody;
	}

	function populateMyCustomPopUpBody(rmid, node){

		var rmid_str = rmid.toString();

		var str = " "+rmid;
		var link = "#mymodal_name_"+rmid;
		$(link).html(str);

		if ((rmid_str.indexOf("05A")==0) || (rmid_str.indexOf("41")==0) || (rmid_str.indexOf("38")==0))	
			str = " Presumed Wall Void";
		else
			str = " Presumed Ceiling Void";

		link = "#mymodal_survey_"+rmid;
		$(link).html(str);


		if ((rmid_str.indexOf("05A")==0) || (rmid_str.indexOf("41")==0) || (rmid_str.indexOf("38")==0))	
			str = " Service Alert";
		else
			str = " Evaluation Pending";
	
		link = "#mymodal_notice_"+rmid;
		$(link).html(str);

	}



	function cvjs_callbackForModalDisplay(rmid, node){
	
		console.log("WE call our server, then we update the modal"+ rmid+"  "+node);
		populateMyCustomPopUpBody(rmid, node);
	}

	// Here we are writing a basic function that will be used in the PopUpMenu
	// this is template on all the good stuff users can add
	function my_own_clickmenu1(){
		var id = cvjs_idObjectClicked();
		//		var node = cvjs_NodeObjectClicked();
		window.alert("Custom menu item 1: Here developers can implement their own methods, the look and feel of the menu is controlled in the settings.  Clicked object ID is: "+id);
	}

	// Here we are writing a basic function that will be used in the PopUpMenu
	// this is template on all the good stuff users can add
	function my_own_clickmenu2(){
		var id = cvjs_idObjectClicked();
		//var node = cvjs_NodeObjectClicked();

		window.alert("Custom menu item 2: Here developers can implement their own methods, the look and feel of the menu is controlled in the settings. Clicked object ID is: "+id);
		//window.alert("Custom menu item 2: Clicked object Node is: "+node);
	}


// MUST BE INCLUDED

	function cvjs_graphicalObjectCreated(graphicalObject){
	// do something with the graphics object created!
//		window.alert(graphicalObject);
	}


	// Callback Method on Creation and Delete 
	function cvjs_graphicalObjectOnChange(type, graphicalObject, spaceID, evt){

	  // do something with the graphics object created! 
		console.log(" cvjs_graphicalObjectOnChange: "+type+" "+graphicalObject+" "+spaceID+" indexSpace: "+graphicalObject.toLowerCase().indexOf("space"));

 /*     UPDATE SERVER WITH REDLINES ON CHANGE       
        if (graphicalObject.toLowerCase().indexOf('redline')>-1 && !type.toLowerCase().indexOf('click')==0 ){
//            cvjs_setStickyNoteSaveRedlineUrl(ServerLocation + "/content/redlines/v7/test"+Math.round(Math.random()*100)+".js");
            cvjs_setStickyNoteSaveRedlineUrl(ServerLocation + "/content/redlines/v7/test_fixed.js");
            cvjs_saveStickyNotesRedlines("floorPlan", false, "THIS IS PLACEHOLDER FOR CUSTOM STUFF TO SERVER");
        }
*/



	if (graphicalObject.toLowerCase().indexOf('redline')>-1 && type.toLowerCase().indexOf('create')==0 ){
		// let's check the number of objects inside the redline created
		//window.alert("here "+spaceID);

		testProcessRectDirect(spaceID);
	}








		if (type == 'Create' && graphicalObject.toLowerCase().indexOf("space")>-1 && graphicalObject.toLowerCase().indexOf("circle")==-1){
				
            /*
            * Return a JSON structure of all content of a space object clicked: <br>
            * 	var jsonStructure =  	{	"path":   path, <br>
            *								"tags": tags, <br>
            *								"node": node, <br>
            *								"area": area, <br>
            *								"outerhtml": outerHTML, <br>
            *								"occupancy": occupancy, <br>
            *								"name": name, <br>
            *								"type": type, <br>
            *								"id": id, <br>
            *								"defaultcolor": defaultcolor, <br>
            *								"layer": layer, <br>
            *								"group": group, <br>
            *								"linked": linked, <br>
            *								"attributes": attributes, <br>
            *								"attributeStatus": attributeStatus, <br>
            *								"displaySpaceObjects": displaySpaceObjects, <br>
            *								"translate_x": translate_x, <br>
            *								"translate_y": translate_y, <br>
            *								"scale_x": scale_x ,<br>
            *								"scale_y": scale_y ,<br>
            *								"rotate": rotate, <br>
            *								"transform": transform} <br>
            * @return {Object} jsonSpaceObject - Object with the entire space objects content
            */
		
			myobject = cvjs_returnSpaceObjectID(spaceID);
			
			// I can save this object into my database, and then use command 
			// cvjs_setSpaceObjectDirect(jsonSpaceObject) 
			// when I am recreating the content of the drawing at load
			
			// for the fun of it, display the SVG geometry of the space:			
			console.log("This is the SVG path: "+myobject.path)
				
		}
		

		if (type == 'Delete' && graphicalObject.toLowerCase().indexOf("space")>-1 ){
			// remove this entry from my DB

			window.alert("We have deleted: "+spaceID)
		}


		if (type == 'Move' && graphicalObject.toLowerCase().indexOf("space")>-1 ){
			// remove this entry from my DB
			console.log("This object has been moved: "+spaceID)		
			myobject = cvjs_returnSpaceObjectID(spaceID);

		}


		if (type == 'Click'){
			// remove this entry from my DB
			console.log(graphicalObject+" has been clicked");		

		}

    }





// ENABLE ALL API EVENT HANDLES FOR AUTOCAD Handles

var selected_handles = [];
var handle_selector = false;
var current_selected_handle = "";



function cvjs_dblclick(id, handle, entity){

	console.log("dblclick "+id+"  "+handle);
	window.alert("We have double clicked entity with AutoCAD Handle: "+handle+"\r\nThe svg id is: "+id);
}

function cvjs_mouseout(id, handle, entity){

    console.log("mouseout "+id+"  "+handle);
    
    if (current_selected_handle == handle){
        // do nothing
    }
    else{
        cvjs_mouseout_handleObjectStyles(id, handle);
    }
}

function cvjs_mouseenter(id, handle, entity){
//	cvjs_mouseenter_handleObjectStyles("#a0a000", 4.0, 1.0, id, handle);
//	cvjs_mouseenter_handleObjectStyles("#ffcccb", 5.0, 0.7, true, id, handle);

// 	cvjs_mouseenter_handleObjectStyles("#F00", 10.0, 1.0, true, id, handle);	
}


var mouseover = false;
var mouseclick = false;


function cvjs_mousedown(id, handle, entity){

	console.log("mousedown "+id);

	// capture mouse down on a space to 
	if (!mouseclick)
		mouseclick = true;

}


function cvjs_click(id, handle, entity){

	console.log("click "+id+"  "+handle);

	// if the popup close menu is called, it will return cvjs_click
	if (mouseclick)
		mouseclick = false;

	// if we click on an object, then we add to the handle list
	if (handle_selector){
		selected_handles.push({id,handle});
		current_selected_handle = handle;
	}

	// tell to update the Scroll bar 
	//vqUpdateScrollbar(id, handle);
	// window.alert("We have clicked an entity: "+entity.substring(4)+"\r\nThe AutoCAD Handle id: "+handle+"\r\nThe svg id is: "+id+"\r\nHighlight SQL pane entry");
}


function cvjs_mouseover(id, handle, entity){

	console.log("mouseover "+id+"  "+handle+"  "+jQuery("#"+id).css("color"));


	/*
	// if we do not have a mouse over we open the modal,  but if the space is clicked we do not do anything
	if (!mouseover){
		mouseover = true;
		if (!mouseclick) cvjs_changeSpaceFixedLocation(id);
	}
	*/

	//cvjs_mouseover_handleObjectPopUp(id, handle);	
}

function cvjs_mouseleave(id, handle, entity){

	console.log("mouseleave "+id+"  "+handle+"  "+jQuery("#"+id).css("color"));

	/*

	mouseover = false;

	console.log("mouseleave variable mouseclick: "+mouseclick);

	if (!mouseclick)       // we hide the pop upon leaving, but do not so if the space is clicked.
		cvjs_hideOnlyPop();
	*/
}


// END OF MOUSE OPERATION






// NEW METHOD TO EXTRACT HANDLES FROM REDLINE!!!! 

/* function testProcessRectDirect(redID){

// I enter the ID of the redline, for ecxample "1", this can also be found directly from the cvjs_graphicalObjectOnChange() methods
//var redID = jQuery("#space_id").val();

var processtext = false;

var handleArray =cvjs_processHandleObjectsRedlineRect("floorPlan",redID, processtext);

console.log("testProcess number of handles:"+handleArray.length);

console.log("handle list:"+JSON.stringify(handleArray));


} */








//   custom js for own use

$(document).ready(function() {

	  $("#view_all_tasks").click(function(){
		 alert('Hello View All Tasks');
	  });
	  $("#view_completed_tasks").click(function(){
		 alert('Hello completed All Tasks');
	  });
	  $("#view_pending_tasks").click(function(){
		 alert('Hello pending All Tasks');
	  });
	  let blinkIntervals = []; // Array to store blinking intervals

    // Function to generate the task list in HTML
    function generateTaskList(tasks) {
        $("#onHoldList, #workingList, #completedList").empty(); // Clear existing lists
        tasks.forEach(function(task) {
			var json_task = JSON.stringify(task);
            let checkbox = '<div><input type="checkbox" id="task_'+task.task_id+'"  data-status_name="'+task.status_name+'" data-layer_name="'+task.layer_name+'" data-task_name="'+task.task_name+'"><label for="task_'+task.task_id+'">'+task.task_name+'</label></div>';
            if (task.status_name === "On Hold") {
                $("#onHoldList").append(checkbox);
            } else if (task.status_name === "Working") {
                $("#workingList").append(checkbox);
            } else if (task.status_name === "Completed") {
                $("#completedList").append(checkbox);
            }
        });
    }

    // Generate task list
    generateTaskList(jsonData);

    // Global "Show All Tasks" button
    $("#showAllTasks").click(function() {
		removeBlinkAndColor(); // Call function to remove blinking and reset colors
        jsonData.forEach(function(task) {
            $("g[cvjs\\:layername='" + task.layer_name + "']").each(function() {
               let target = $(this).find('[cvjs\\:handle="' + task.task_name + '"]').find("g").first();
			   // let target = $(this).find("." + task.task_name).find("g").first();
                if (target.length > 0) {
                    let color = getTaskColor(task.status_name);
                    target.css("stroke", color);
                    let intervalId = blinkStrokeColor(target, color); // Start blinking
                    blinkIntervals.push({ element: target, intervalId: intervalId }); // Track intervals
                }
            });
        });
    });

    // Global "Hide All Tasks" button
    $("#hideAllTasks").click(function() {
        removeBlinkAndColor(); // Call function to remove blinking and reset colors
    });

    // "Show Selected Tasks" button
    $("#showSelectedTasks").click(function() {
		removeBlinkAndColor(); // Call function to remove blinking and reset colors
        $("input[type='checkbox']:checked").each(function() {

			let layer_name = $(this).data("layer_name");
			let task_name = $(this).data("task_name");
			let project_status_name = $(this).data("project_status_name");
            $("g[cvjs\\:layername='" + layer_name + "']").each(function() {
                let target = $(this).find("." + task_name).find("g").first();
                if (target.length > 0) {
                    let color = getTaskColor(project_status_name);
                    target.css("stroke", color);
                    let intervalId = blinkStrokeColor(target, color); // Start blinking
                    blinkIntervals.push({ element: target, intervalId: intervalId }); // Track intervals
                }
            });
        });
    });

    // "Hide Selected Tasks" button
    $("#hideSelectedTasks").click(function() {
		removeBlinkAndColor(); // Call function to remove blinking and reset colors
        $("input[type='checkbox']:checked").each(function() {
           // let task = $(this).data("task");
			let layer_name = $(this).data("layer_name");
			let task_name = $(this).data("task_name");
            $("g[cvjs\\:layername='" + layer_name + "']").each(function() {
                let target = $(this).find("." + task_name).find("g").first();
                if (target.length > 0) {
                    clearIntervalForElement(target); // Remove blinking for this task
                    target.css("stroke", ""); // Reset stroke color
                }
            });
        });
    });

    // Function to get color based on project_status_name
    function getTaskColor(status) {
        switch (status) {
            case "On Hold":
                return "red"; // Orange
            case "Working":
                return "yellow"; // Blue
            case "Completed":
                return "green"; // Green
            default:
                return "yellow"; // Default black
        }
    }

    // Function to make a stroke color blink
    function blinkStrokeColor(element, color) {
        let isBlinking = false;
        let intervalId = setInterval(function() {
            if (isBlinking) {
                element.css("stroke", color); // Set color
            } else {
                element.css("stroke", "transparent"); // Make transparent
            }
            isBlinking = !isBlinking;
        }, 500); // Blink every 500ms
        return intervalId; // Return interval ID
    }

    // Function to remove all blinking and reset colors
    function removeBlinkAndColor() {
        blinkIntervals.forEach(function(item) {
            clearInterval(item.intervalId); // Clear the interval
            item.element.css("stroke", ""); // Reset the stroke color
        });
        blinkIntervals = []; // Clear the intervals array
    }

    // Function to remove blinking for a specific element
    function clearIntervalForElement(element) {
        for (let i = 0; i < blinkIntervals.length; i++) {
            if (blinkIntervals[i].element.is(element)) {
                clearInterval(blinkIntervals[i].intervalId); // Clear the interval
                blinkIntervals.splice(i, 1); // Remove this entry
                break;
            }
        }
    }
	});

	function getSelectedTasks() {
		let tasks = [];
		$(".task-checkbox:checked").each(function () {
			const taskId = $(this).data("id");
			tasks.push(taskId);
		});
		return tasks;
	}





	/*function getTaskbyName(task_name, floor_id,task_type) {
		$.ajax({
			url: "/tasks/getTaskByName",
			type: "GET",
			data: {
				task_name: task_name,
				floor_id: floor_id,
				task_type: task_type,
				_token: $('meta[name="csrf-token"]').attr("content")
			},
			success: function (response) {
				var task = response.task;
				return task;
			},
			error: function () {
				alert("An error occurred.");
			}
		});
	}*/

function getTaskbyName(task_name, floor_id, task_type, callback) {
    $.ajax({
        url: "/tasks/getTaskByName",
        type: "GET",
        data: {
            task_name: task_name,
            floor_id: floor_id,
            task_type: task_type,
            _token: $('meta[name="csrf-token"]').attr("content")
        },
        success: function (response) {
            callback(response); // Pass the data to the callback
        },
        error: function () {
            alert("An error occurred.");
        }
    });
}



	function updateTaskbyId(taskId, statusId,memberId) {
	if (taskId == "") {
			alert("No tasks selected.");
			return;
		}
		$.ajax({
			url: "/tasks/updateById",
			type: "POST",
			data: {
				 task_id: taskId,
				 task_status_id: statusId, 
				 member_id: memberId, 
				_token: $('meta[name="csrf-token"]').attr("content")
			},
			success: function (response) {
				if (response.success) {
					alert("Tasks updated successfully.");
					 showPipeinfo();
					//updatePipesFromAPI();
				} else {
					alert("Failed to update tasks.");
				}
			},
			error: function () {
				alert("An error occurred.");
			}
		});
	}

	
	function updateTasks(taskIds, statusId) {
		if (taskIds.length === 0) {
			alert("No tasks selected.");
			return;
		}

		$.ajax({
			url: "/tasks/updateAll",
			type: "POST",
			data: {
				tasks: taskIds.map(id => ({ task_id: id, task_status_id: statusId })),
				_token: $('meta[name="csrf-token"]').attr("content")
			},
			success: function (response) {
				if (response.success) {
					alert("Tasks updated successfully.");
					//loadTasks(); // Reload task list
				} else {
					alert("Failed to update tasks.");
				}
			},
			error: function () {
				alert("An error occurred.");
			}
		});
	}
	$("#update-selected").click(function () {
		const selectedTasks = getSelectedTasks();
		const statusId = $("#status-dropdown").val();

		if (selectedTasks.length === 0) {
			alert("Please select tasks to update.");
			return;
		}

		if (confirm("Are you sure you want to update the selected tasks?")) {
			updateTasks(selectedTasks, statusId);
		}
	});

	$("#update-all").click(function () {
		const statusId = $("#status-dropdown").val();

		if (confirm("Are you sure you want to update all tasks to this status?")) {
			const allTaskIds = jsonData.map(task => task.task_id); // Get all task IDs
			updateTasks(allTaskIds, statusId);
		}
	});

//  end of custom js for own use
function testProcessRectDirectOld(redID) {
    const handleArray = cvjs_processHandleObjectsRedlineRect("floorPlan", redID, false);

    console.log("Number of handles:", handleArray.length);
    console.log("Handle list:", JSON.stringify(handleArray));

     const jsonDataa = JSON.parse(document.getElementById("matechjsondata").innerHTML); // Retrieve the data defined in the script tag

   // if (!Array.isArray(jsonDataa)) {
    //    console.error("jsonData is not an array:", jsonDataa);
    //    return;
  //  }

    const categorizedData = { 0:[],1: [], 2: [], 3: [],4: [], 5: [], 6: [] }; // On Hold, Working, Completed

    // Extract blockhandle values from handleArray
    const blockHandles = handleArray.map(item => item.blockhandle);

    // Filter and categorize tasks
    jsonDataa.forEach((task) => {
        if (blockHandles.includes(task.task_name)) {
            categorizedData[task.task_status_id]?.push(task);
        }
    });

    // Generate task list with checkboxes
    let modalContentPipe =  modalContentSprinkler = "";
    const statusLabels = { 0: "Pending", 1: "On Hold", 2: "Working", 3: "Completed", 4: "First Step", 5: "Second Step", 6: "Third Step" };

    Object.keys(categorizedData).forEach((statusId) => {
        const tasks = categorizedData[statusId];
        if (tasks.length > 0) {
			if (["1", "2", "3"].includes(statusId)) {
            modalContentPipe += `<h5>${statusLabels[statusId]}</h5>`;
            tasks.forEach((task) => {
                const checked = statusId === "3" ? "checked" : "";
                modalContentPipe += `<div class="form-check"><input class="form-check-input task-checkbox" type="checkbox" value="${task.task_name}" id="task_${task.task_id}" ${checked}><label class="form-check-label" for=task_${task.task_id}">${task.task_name} (${task.layer_name})</label></div>`;
            });
			}
			else {
				modalContentSprinkler += `<h5>${statusLabels[statusId]}</h5>`;
				tasks.forEach((task) => {
					const checked = statusId === "3" ? "checked" : "";
					modalContentSprinkler += `<div class="form-check"><input class="form-check-input task-checkbox" type="checkbox" value="${task.task_name}" id="task_${task.task_id}" ${checked}><label class="form-check-label" for=task_${task.task_id}">${task.task_name} (${task.layer_name})</label></div>`;
				});				
			}
        }
    });
    document.getElementById("categorized-tasks-pipe").innerHTML = modalContentPipe;
    document.getElementById("categorized-tasks-sprinkler").innerHTML = modalContentSprinkler;
    new bootstrap.Modal(document.getElementById("handleTaskModal")).show();

    // Update button action
    document.getElementById("updateTasks").onclick = () => updateTasks(categorizedData);
    document.getElementById("cancelTasks").onclick = () => cancelTasks();
}

function testProcessRectDirect(redID) {
    const handleArray = cvjs_processHandleObjectsRedlineRect("floorPlan", redID, false);

    console.log("Number of handles:", handleArray.length);
    console.log("Handle list:", JSON.stringify(handleArray));

    const jsonDataa = JSON.parse(document.getElementById("matechjsondata").innerHTML); // Retrieve the data defined in the script tag
    if (!Array.isArray(jsonData)) {
        console.error("jsonData is not an array:", jsonData);
        return;
    }

    const blockHandles = handleArray.map(item => item.blockhandle);

    // Categorize tasks into "Pipe" and "Sprinkler"
   // const categorizedData = { Pipe: [], Sprinkler: [] };
	  const categorizedData = {
            Pipe: { 1: [], 2: [],3: [] },
            Sprinkler: { 0: [], 4: [], 5: [],6: [] }
        };
    jsonDataa.forEach((task) => {
		// Check if it's a Pipe or Sprinkler task
		if (task.task_type.includes("pipe")) {
			// Ensure the status array exists for the task's status
			if (!categorizedData.Pipe[task.task_status_id]) {
				categorizedData.Pipe[task.task_status_id] = []; // Initialize if not exists
			}

			if (blockHandles.includes(task.task_name)) {
				categorizedData.Pipe[task.task_status_id].push(task); // Push task to correct status array
			}
		

		} else if (task.task_type.includes("sprinkler")) {
			// Ensure the status array exists for the task's status
			if (!categorizedData.Sprinkler[task.task_status_id]) {
				categorizedData.Sprinkler[task.task_status_id] = []; // Initialize if not exists
			}
			if (blockHandles.includes(task.task_name)) {
				categorizedData.Sprinkler[task.task_status_id].push(task); // Push task to correct status array
			}			

		}
	});

    // Render Modal with Tabs
    renderTabsWithData(categorizedData);
}

function renderTabsWithData(categorizedData) {
    const modalBody = document.getElementById("modal-body");
    modalBody.innerHTML = ""; // Clear previous content

    // Create Tab Navigation
    const tabNav = document.createElement("ul");
    tabNav.className = "nav nav-tabs";
    tabNav.role = "tablist";

    // Create Tab Content Container
    const tabContent = document.createElement("div");
    tabContent.className = "tab-content";

    let isActive = true; // Track the first tab to set it as active

    for (const category in categorizedData) {
        // Generate Tab Navigation Item
        const tabItem = document.createElement("li");
        tabItem.className = "nav-item";

        const tabLink = document.createElement("a");
        tabLink.className = `nav-link ${isActive ? "active" : ""}`;
        tabLink.id = `${category.replace(/\s+/g, '-')}-tab`;
        tabLink.dataset.bsToggle = "tab";
        tabLink.href = `#${category.replace(/\s+/g, '-')}`;
        tabLink.role = "tab";
        tabLink.setAttribute("aria-controls", category.replace(/\s+/g, '-'));
        tabLink.setAttribute("aria-selected", isActive);
        tabLink.textContent = category;
        tabItem.appendChild(tabLink);
        tabNav.appendChild(tabItem);

        // Generate Tab Content Pane
        const tabPane = document.createElement("div");
        tabPane.className = `tab-pane fade ${isActive ? "show active" : ""}`;
        tabPane.id = category.replace(/\s+/g, '-');
        tabPane.role = "tabpanel";
        tabPane.setAttribute("aria-labelledby", `${category.replace(/\s+/g, '-')}-tab`);

        // Group Tasks by Status
        const groupedTasks = groupByStatus(categorizedData[category]);

        for (const status in groupedTasks) {
            const statusHeader = document.createElement("h5");
            statusHeader.textContent = status; // Display the status group (e.g., "On Hold")
            tabPane.appendChild(statusHeader);

            groupedTasks[status].forEach(task => {
                const checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.id = `task-${task.task_id}`;
                checkbox.className = "task-checkbox";
                checkbox.value = task.task_id;
				checkbox.checked = task.status_name === "Completed" || task.status_name === "Covers";
				checkbox.disabled  = task.status_name === "Completed" || task.status_name === "Covers";


                const label = document.createElement("label");
                label.htmlFor = `task-${task.task_id}`;
                label.textContent = `${task.task_name} (${task.layer_name})`;

                const checkboxContainer = document.createElement("div");
                checkboxContainer.className = "mb-2";
                checkboxContainer.appendChild(checkbox);
                checkboxContainer.appendChild(label);
                tabPane.appendChild(checkboxContainer);
            });
        }

        tabContent.appendChild(tabPane);
        isActive = false; // Mark subsequent tabs as inactive
    }

    modalBody.appendChild(tabNav);
    modalBody.appendChild(tabContent);

    // Add Action Buttons
    const actionButtons = document.createElement("div");
    actionButtons.className = "mt-3";

    const updateButton = document.createElement("button");
    updateButton.className = "btn btn-primary me-2";
	updateButton.id = "updateTasks"; // Add the id dynamically
    updateButton.textContent = "Update";
    updateButton.onclick = () => updateTasks(categorizedData);
    // updateButton.onclick = handleUpdate;

    const cancelButton = document.createElement("button");
    cancelButton.className = "btn btn-secondary";
    cancelButton.textContent = "Cancel";
	cancelButton.id = "cancelTasks"; // Add the id dynamically
    cancelButton.onclick = () => cancelTasks();
    // cancelButton.onclick = () => $("#modal").modal("hide");

    actionButtons.appendChild(updateButton);
    actionButtons.appendChild(cancelButton);
    modalBody.appendChild(actionButtons);

    // Show Modal
    $("#modal").modal("show");
}

function groupByStatus(tasks) {
    const grouped = {};
	Object.values(tasks).forEach(tasksData => {
     tasksData.forEach(task => {
        const status = task.status_name || "Unknown Status";
        if (!grouped[status]) {
            grouped[status] = [];
        }
        grouped[status].push(task);
		});
    });

    return grouped;
}





function handleUpdate() {
    const checkedTasks = [];
    document.querySelectorAll("input[type='checkbox']:checked").forEach(checkbox => {
        checkedTasks.push({
            task_name: checkbox.value,
            layer_name: checkbox.parentElement.textContent.split("(")[1].replace(")", "").trim()
        });
    });

    console.log("Checked Tasks:", checkedTasks);

    // Make an AJAX call to update tasks in Laravel
    fetch("/update-tasks", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(checkedTasks)
    })
    .then(response => response.json())
    .then(data => {
        console.log("Update Response:", data);
        $("#modal").modal("hide");
    })
    .catch(error => console.error("Error updating tasks:", error));
}



function cancelTasks(){
	$("#modal").modal("hide");
	cvjs_deleteLastRedline('floorPlan');

}
	
/*function updateTasks(categorizedData) {
	jQuery(".cvjs_wait_looper").fadeIn(1500);
    const selectedTasks = [];
    const checkboxes = document.querySelectorAll(".task-checkbox:checked:not(:disabled)");

    checkboxes.forEach((checkbox) => {
        const taskId = checkbox.value;
        Object.values(categorizedData).flat().forEach((tasksData) => {
			Object.values(tasksData).forEach(tasks => {
				tasks.forEach(task => {
					if (task.task_id == taskId) {
						var status_id = 3;
						if(task.task_type === 'sprinkler'){
							switch (task.task_status_id) {
								case 0:
								  status_id = 4;	  
								  break;
								case 4:
								  status_id = 5;	  
								  break;
								case 5:
								  status_id = 6;	  
								  break;
								// Add more cases if there are other statuses
								default:
								  break;
							}
						}
						selectedTasks.push({
							task_id: task.task_id,
							task_status_id: status_id,
							floor_id: task.floor_id,
						});
					}
				});
			});	
        });
    });
	
    if (selectedTasks.length === 0) {
        alert("No tasks selected.");
		jQuery(".cvjs_wait_looper").fadeOut(1500);
        return;
    }

    console.log("Selected tasks:", selectedTasks);

    // Send data to Laravel API
    fetch("/tasks/updateAll", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        },
        body: JSON.stringify({ tasks: selectedTasks }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {

                alert("Tasks updated successfully.");
				
				document.getElementById("matechjsondata").innerHTML = JSON.stringify(data.task);
				updateCounts(data);
				
               // location.reload(); // Reload to refresh JSON data
            } else {
                alert("Failed to update tasks.");
            }
			jQuery(".cvjs_wait_looper").fadeOut(1500);
        })
        .catch((error) => {
            console.error("Error updating tasks:", error);
            alert("An error occurred.");
        });
		cvjs_deleteLastRedline('floorPlan');
}*/

function updateCounts(data){
	// Initialize counters
	let totalTasks = data.task_count;
	let completedTasks = 0;
	let pendingTasks = 0;
	let holdTasks = 0;

	let totalLength = 0; // Total length of all tasks
	let completedLength = 0; // Total length of completed tasks
	let pendingLength = 0; // Total length of pending tasks (pending)
	let holdLength = 0; // Total length of hold tasks (On Hold)

	let totalSprinklers = 0;
	let coverSprinklers = 0;
	let pendingSprinklers = 0;
	let droppingSprinklers = 0;
	let securingSprinklers = 0;	
	
	// Iterate through the task array
	data.task.forEach(task => {
	  // switch (task.status) {
		// case 'Completed':
		  // completedTasks = task.count;
		  // completedLength += totalInches;		  
		  // break;
		// case 'Working':
		  // pendingTasks = task.count;
		  // pendingLength += totalInches;
		  // break;
		// case 'On Hold':
		  // holdTasks = task.count;
		  // holdLength += totalInches;
		  // break;
		// // Add more cases if there are other statuses
		// default:
		  // break;
	  // }
	if(task.task_type == 'pipe'){ 
		let totalInches = task.length_in_inches
		totalLength += totalInches;
		const feetAndInches = convertToFeetAndInches(totalInches);

		  totalTasks++;
		  switch (task.status_name) {
			case 'Completed':
			  completedTasks++;
			  completedLength += totalInches;
			  break;
			case 'Working':
			  pendingTasks++;
			  pendingLength += totalInches;
			  break;
			case 'On Hold':
			  holdTasks++;
			  holdLength += totalInches;
			  break;
			// Add more cases if there are other statuses
			default:
			  break;
		}
	}
	else{
		  totalSprinklers++;
		  switch (task.status_name) {
			case 'Covers':
			  coverSprinklers++;
			  break;
			case 'Securing':
			  securingSprinklers++;
			  break;
			case 'Dropping Or Flex':
			  droppingSprinklers++;
			  break;
			case 'Pending':
			  pendingSprinklers++;
			  break;
			// Add more cases if there are other statuses
			default:
			  break;
		}		
	}	  
	  
	});

	// Convert lengths back to feet and inches for readability
	const totalFeetAndInches = convertToFeetAndInches(totalLength);
	const completedFeetAndInches = convertToFeetAndInches(completedLength);
	const pendingFeetAndInches = convertToFeetAndInches(pendingLength);
	const holdFeetAndInches = convertToFeetAndInches(holdLength);

	// Update the HTML elements
	document.getElementById('total_task').textContent = totalTasks;
	document.getElementById('completed_task').textContent = completedTasks;
	document.getElementById('pending_task').textContent = pendingTasks;
	document.getElementById('hold_task').textContent = holdTasks;

	document.getElementById('total_length').textContent = totalFeetAndInches;
	document.getElementById('completed_length').textContent = completedFeetAndInches;
	document.getElementById('pending_length').textContent = pendingFeetAndInches;
	document.getElementById('hold_length').textContent = holdFeetAndInches;	
	
	// Update the HTML elements
	document.getElementById('total_sprinklers').textContent = totalSprinklers;
	document.getElementById('cover_sprinklers').textContent = coverSprinklers;
	document.getElementById('secure_sprinklers').textContent = securingSprinklers;
	document.getElementById('dropping_sprinklers').textContent = droppingSprinklers;
	document.getElementById('pending_sprinklers').textContent = pendingSprinklers;	
	cancelTasks();
}

	function convertToFeetAndInches(totalInches) {
		const feet = Math.floor(totalInches / 12); // Calculate the feet
		const inches = totalInches % 12; // Calculate the remaining inches
		return `${feet}'-${inches}"`; // Return in the format feet'-inches"
	}

$(document).ready(function()
{





// Utility function to populate tasks
function populateTasks(containerId, tasks) {
    const container = document.getElementById(containerId);
    container.innerHTML = ""; // Clear previous content

    if (tasks.length === 0) {
        container.innerHTML = "<p>No tasks available.</p>";
        return;
    }

    const taskList = document.createElement("ul");
    taskList.classList.add("list-group");

    tasks.forEach((task) => {
        const taskItem = document.createElement("li");
        taskItem.classList.add("list-group-item");
        taskItem.innerHTML = `
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="task-${task.task_id}" ${task.task_status_id === 3 ? "checked disabled" : ""}>
                <label class="form-check-label" for="task-${task.task_id}">
                    ${task.task_name} - ${task.floor_name}
                </label>
            </div>
        `;
        taskList.appendChild(taskItem);
    });

    container.appendChild(taskList);
}



// All Tasks Button
document.querySelector("#allTaskModalData").addEventListener("click", () => {
    const jsonData = JSON.parse(document.getElementById("matechjsondata").innerHTML);
    populateTasks("categorized-all-tasks", jsonData);
});

// Completed Tasks Button
document.querySelector("#CompletedTaskModalData").addEventListener("click", () => {
    const jsonData = JSON.parse(document.getElementById("matechjsondata").innerHTML);
    const completedTasks = jsonData.filter((task) => task.task_status_id === 3);
    populateTasks("categorized-completed-tasks", completedTasks);
});

// Pending Tasks Button
document.querySelector("#PendingTaskModalData").addEventListener("click", () => {
    const jsonData = JSON.parse(document.getElementById("matechjsondata").innerHTML);
    const pendingTasks = jsonData.filter((task) => task.task_status_id === 2);
    populateTasks("categorized-pending-tasks", pendingTasks);
});

// On-Hold Tasks Button
document.querySelector("#OnHoldTaskModalData").addEventListener("click", () => {
    const jsonData = JSON.parse(document.getElementById("matechjsondata").innerHTML);
    const onHoldTasks = jsonData.filter((task) => task.task_status_id === 1);
    populateTasks("categorized-onhold-tasks", onHoldTasks);
});

$("input[type='checkbox']:checked").each(function () {
    let layer_name = $(this).data("layer_name");
    let task_name = $(this).data("task_name");
    let project_status_name = $(this).data("project_status_name");

    // Select the layer and apply styles
    $("g[cvjs\\:layername='" + layer_name + "']").each(function () {
        $(this).find("g").each(function () {
            let target = $(this).find("path"); // Target <path> inside the <g> element
            if (target.length > 0) {
                // Determine the color based on project status
                let color = getTaskColor(project_status_name);

                // Apply styles to paths
                target.css({
                    "stroke": color,
                    "fill": color,
                    "stroke-width": "5px"
                });
            }
        });
    });
});
	
	});
 	</script>

  
  
 <!--div class="modal fade" id="handleTaskModal" tabindex="-1" aria-labelledby="handleTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="handleTaskModalLabel">Handle Tasks</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
				<i class="fas fa-close"></i>
				</button>
            </div>
            <div class="modal-body">
				<div class="row col-md-12">
					<div class ="col-md-6">
					<h2>Pipe</h2>
					<div id="categorized-tasks-pipe"></div>
					</div>
					<div class ="col-md-6">
					<h2>Sprinkler</h2>
					<div id="categorized-tasks-sprinkler"></div>
					</div>
				</div>
            </div>
            <div class="modal-footer">
                <button id="cancelTasks" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button id="updateTasks" type="button" class="btn btn-primary" data-dismiss="modal">Mark as completed</button>
            </div>
        </div>
    </div>
</div-->

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Task Categories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body"></div>
        </div>
    </div>
</div>


	<section>
    <!-- Content Wrapper. Contains page content -->
    <div class="">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="card">
<div class="cadviewer body">
<div id="task-container">
    <div id="on-hold-tasks">
        <h3>On Hold Tasks</h3>
        <div id="onHoldList"></div>
    </div>
    <div id="working-tasks">
        <h3>Working Tasks</h3>
        <div id="workingList"></div>
    </div>
    <div id="completed-tasks">
        <h3>Completed Tasks</h3>
        <div id="completedList"></div>
    </div>
</div>

<div class="global-buttons">
    <button id="showAllTasks">Show All Tasks</button>
    <button id="hideAllTasks">Hide All Tasks</button>
</div>

<div class="task-actions">
    <button id="showSelectedTasks">Show Selected Tasks</button>
    <!-- <button id="hideSelectedTasks">Hide Selected Tasks</button> -->
</div>

<div class="task_section">
	<div class="tasks_header">
		<div class="tasks_header_left">
			<h1>First floor</h1>
			<span class="priority">high</span>
		</div>
		<div class="tasks_header_right">
			<span class="status">working</span>
		</div>
	</div>
	<div class="tasks_data_list">
		<div class="row">
			<div class="totalTasks_wrapper">
				<div class="col-lg-3 col-6">
					<div class="small-box bg-info">
						<h3 id="total_task"></h3>
						<p>No of Pipes:</h3>
						<div class="icon">
							<i class="ion ion-drag"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">	
					<div class="small-box bg-success">
						<h3 id="completed_task"></h3>
						<p>Completed Pipes:</p>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-warning">
						<h3 id="pending_task"></h3>
						<p>Pending Pipes:</p>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-danger">
						<h3 id="hold_task"></h3>
						<p>Hold Pipes:</p>
					</div>			
				</div>			
			</div>
		</div>
		<div class="row">
			<div class="lengthTasks_wrapper">
				<div class="col-lg-3 col-6">
					<div class="small-box bg-info">
						<h3 id="total_length"></h3>
						<p>Length of Pipes:</p>
						<div class="icon">
							<i class="ion ion-drag"></i>
						</div>						
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box bg-success">
						<h3 id="completed_length"></h3>
						<p>Completed Length of Pipes:</p>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>						
					</div>
				</div>
				<div class="col-lg-3 col-6">					
					<div class="small-box bg-warning">
						<h3 id="pending_length"></h3>
						<p>Pending Length of Pipes:</p>
					</div>
				</div>
				<div class="col-lg-3 col-6">					
					<div class="small-box bg-danger">
						<h3 id="hold_length"></h3>
						<p>Hold Length of Pipes:</p>
					</div>	
				</div>
			</div>		
		</div>		
		<div class="sprinkler-section">
			<div class="row">
				<div class="totalTasks_wrapper">
					<div class="col-lg-3 col-6">
						<div class="small-box bg-info">
							<h3 id="total_sprinklers"></h3>
							<p>No of sprinklers:</h3>
							<div class="icon">
								<i class="ion ion-drag"></i>
							</div>
						</div>
					</div>
					<div class="col-lg-2 col-6">
						<div class="small-box bg-success">
							<h3 id="cover_sprinklers"></h3>
							<p>Covers</p>
							<div class="icon">
								<i class="ion ion-stats-bars"></i>
							</div>
						</div>
					</div>
					<div class="col-lg-2 col-6">	
						<div class="small-box bg-warning">
							<h3 id="pending_sprinklers"></h3>
							<p>Pending:</p>
						</div>
					</div>
					<div class="col-lg-2 col-6">
						<div class="small-box bg-secondary">
							<h3 id="secure_sprinklers"></h3>
							<p>Securing:</p>
						</div>
					</div>
					<div class="col-lg-3 col-6">
						<div class="small-box bg-danger">
							<h3 id="dropping_sprinklers"></h3>
							<p>Dropping Or Flex:</p>
						</div>			
					</div>			
				</div>
			</div>		
		</div>		
		<!--div class="tasks_data">
			<p><span>Member</span><span></span></p>
		</div-->
	</div>
	<div class="tasks_btns_wrapper">
		<div class="tasks_btns_inner">
			<!-- Buttons -->
			<button type="button" data-toggle="modal" id="allTaskModalData" data-target="#allTaskModal">All Tasks</button>
			<button type="button" data-toggle="modal" id="CompletedTaskModalData" data-target="#CompletedTaskModal">Completed Tasks</button>
			<button type="button" data-toggle="modal" id="PendingTaskModalData" data-target="#PendingTaskModal">Pending Tasks</button>
			<button type="button" data-toggle="modal" id="OnHoldTaskModalData" data-target="#OnHoldTaskModal">On-Hold Tasks</button>

		</div>
		<div class="tasks_btns_inner">
			<button type="button" id="view_all_tasks">View All Tasks</button>
			<button type="button" id="view_completed_tasks">View Completed Tasks</button>
			<button type="button" id="view_pending_tasks">View Pending Tasks</button>
		</div>
	</div>
</div>

<div class="row">
<div class="col-sm-12 col-lg-8 mb-8 ">


	<!--This is the CADViewer floorplan div declaration -->

		<div id="floorPlan" class="cadviewer-bootstrap cadviewer-core-styles"  style="border:2px none; width:1800px;height:1800px;">
		</div>

	<!--End of CADViewer declaration -->

	
	
	</div>
</div>
<div class="col-sm-12 col-lg-4 mb-4 task-details">
<div class="card"></div>

</div>

</div>
	
	
	
	
                </div>
            </div>
        </div>
    </div>
</section>
<!-- All Tasks Modal -->
<div class="modal fade" id="allTaskModal" tabindex="-1" aria-labelledby="allTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="allTaskModalLabel">All Tasks</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="categorized-all-tasks">
                    <!-- Dynamic content for all tasks -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Completed Tasks Modal -->
<div class="modal fade" id="CompletedTaskModal" tabindex="-1" aria-labelledby="CompletedTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="CompletedTaskModalLabel">Completed Tasks</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="categorized-completed-tasks">
                    <!-- Dynamic content for completed tasks -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Pending Tasks Modal -->
<div class="modal fade" id="PendingTaskModal" tabindex="-1" aria-labelledby="PendingTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="PendingTaskModalLabel">Pending Tasks</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="categorized-pending-tasks">
                    <!-- Dynamic content for pending tasks -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- On-Hold Tasks Modal -->
<div class="modal fade" id="OnHoldTaskModal" tabindex="-1" aria-labelledby="OnHoldTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="OnHoldTaskModalLabel">On-Hold Tasks</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="categorized-onhold-tasks">
                    <!-- Dynamic content for on-hold tasks -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>






<style>
	.cadviewer.body, .cadviewer.body div#task-container {
		display: none;
	}

	.cadviewer.body .global-buttons {
		display: none;
	}

	.cadviewer.body .task-actions {
		display: none;
	}

	.tasks_header {
    display: flex;
    align-items: center;
    padding-bottom: 20px;
}

.tasks_header .tasks_header_left {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 11px;
}

section .content-wrapper {
    background: none;
}

section .content-wrapper .card {
    box-shadow: none;
}

.wrapper aside.main-sidebar {
    box-shadow: none !important;
}

.tasks_header_left h1 {
    font-size: 22px;
    font-weight: 600;
    line-height: 33px;
    text-align: left;
}

span.priority {
    padding: 4px 8.5px;
    color: rgba(235, 110, 110, 1);
    font-size: 13px;
    font-weight: 700;
    line-height: 15px;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    background: rgba(255, 220, 220, 1);
    border: 1px solid rgba(235, 110, 110, 1);
    border-radius: 8px;
}

span.status {
    font-size: 13px;
    font-weight: 700;
    line-height: 15px;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 1);
    padding: 4px 9px;
    background: rgba(42, 189, 39, 1);
    border-radius: 8px;
}

.tasks_data {
    text-align: right;
}

.tasks_data p {
    display: flex;
    justify-content: end;
    gap: 10px;
    font-size: 16px;
    font-weight: 500;
    line-height: 21px;
    color: rgba(104, 104, 104, 1);
	margin: 0;
}

.tasks_data p span:first-child {
    font-weight: 600;
}

.cadviewer-core-styles .topIconMenu_placeholder_1 {
    width: 100% !important;
	/*display: none !important;*/
}

.tasks_btns_wrapper {
    padding-bottom: 20px;
}

.tasks_btns_wrapper button {
    font-size: 16px;
    font-weight: 500;
    line-height: 24px;
    text-align: center;
    padding: 7px 18px;
    border-radius: 8px;
    background: rgba(0, 85, 230, 1);
    border: 1px solid rgba(0, 85, 230, 1);
    color: #fff;
    transition: .3s;
}

.tasks_btns_wrapper button:hover {
    color: rgba(0, 85, 230, 1);
    background: transparent;
}

.modal-open .modal {
    z-index: 99999999;
}

.modal-content .modal-header {
	align-items: center;
	padding: 12px 14px;
}

.modal-content .modal-header h5 {
    font-size: 22px;
    font-weight: 600;
    line-height: 33px;
    color: rgba(4, 4, 4, 1);
}

.modal-content .modal-header button.btn-close {
    background: none;
    border: 0;
    color: rgba(0, 85, 230, 1);
    font-size: 18px;
}

.form-check input.form-check-input {
    width: 16px;
    height: 16px;
	accent-color: #0055e6;
}

.form-check label.form-check-label {
	padding-left: 3px;
	color: #000;
}

.modal-footer button.btn.btn-secondary {
    background: none;
    color: rgba(0, 85, 230, 1);
    border-color: rgba(0, 85, 230, 1);
}

.modal-footer button.btn.btn-primary {
    background-color: rgba(0, 85, 230, 1);
    border-color: rgba(0, 85, 230, 1);
}

.modal-footer button.btn.btn-primary:hover {
    background: none;
    color: rgba(0, 85, 230, 1);
}

.modal-footer button.btn.btn-secondary:hover {
    background-color: rgba(0, 85, 230, 1);
    color: #fff;
}

.tasks_btns_wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
	display:none;
}

.totalTasks_wrapper ,.lengthTasks_wrapper{
    display: flex;
    flex: 1;
}

.tasks_data_list {
    /*display: none;*/
    gap: 10px;
    padding-bottom: 20px;
}

.totalTasks_wrapper p, .lengthTasks_wrapper p {
	font-size: 17px;
	margin: 0;
	padding: 10px 10px;
	border-right: 1px solid #d3d3d3;
	margin-bottom:10px;
}

.totalTasks_wrapper h3, .lengthTasks_wrapper h3 {
    padding: 10px 10px 0px 10px;
}

.totalTasks_wrapper p:last-child {
    border: 0;
}

.totalTasks_wrapper p span:last-child, .lengthTasks_wrapper p span:last-child  {
	font-weight: 600;
}

.modal-body .form-check {
    width: 100%;
    margin-bottom: 10px;
}
svg#floorPlan_svg {
    width: 100% !important;
    height: 500px !important;
}
</style>

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

	</script>
@endpush


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CAD Viewer with Pipe Details</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .cadviewer-bootstrap {
      width: 100%;
      height: 500px;
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #6c757d;
    }

    .task-details .card {
      height: 100%;
    }
	div#spaceobjectCustomMenu_placeholder_floorPlan_svg {
		display: none !important;
	}
.legend-container {
  width: 100%;
  background-color: #f9f9f9;
  padding: 10px 15px;
  border-top: 1px solid #ddd;
  /*position: fixed;*/
  bottom: 0;
  left: 0;
}

.legend {
  display: flex;
  justify-content: space-around;
  align-items: center;
}

.legend-item {
  display: flex;
  align-items: center;
}

.legend-color {
  width: 20px;
  height: 20px;
  margin-right: 8px;
  border-radius: 50%;
  border: 1px solid #ccc;
}

.legend-text {
  font-size: 14px;
  color: #333;
}


  </style>
</head>
<body>
  <div class="container mt-4">
    <div class="row">
<div class="container mt-4">
    <div class="card border rounded p-3">
        <div class="d-flex justify-content-between mb-2">
                    <h5 class="card-title font-weight-bold">Tasks Progress</h5>
            <span id="progress-percentage" class="font-weight-bold text-primary">0%</span>
        </div>
        <div class="progress rounded-pill">
            <div id="progress-bar" class="progress-bar bg-primary progress-bar-striped progress-bar-animated" 
                 role="progressbar" style="width: 0%;" 
                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>
        <div class="mt-2 d-flex justify-content-between text-muted small">
            <span id="completed-pipes">0′ completed</span>
            <span id="total-pipes">0′ total</span>
        </div>
    </div>
</div>
	
      <!-- Left Column: CAD Viewer -->
      <div class="col-lg-9 col-md-12 mb-9">
        <div id="floorPlan" class="cadviewer-bootstrap cadviewer-core-styles">
          CAD Viewer Placeholder
        </div>
		<div class="container mt-4">
			<div class="card border rounded p-3">
				<div class="legend-container">
				  <div id="legend" class="legend">
					<!-- Legend items will be dynamically populated -->
				  </div>
				</div>
			</div>
		</div>
      </div>

      <!-- Right Column: Pipe Details -->
      <div class="col-lg-3 col-md-12 mb-3">
        <div class="task-details card">
          <div class="card-header bg-primary text-white">
            Pipe Details
          </div>
          <div class="card-body">
            <p class="text-muted">Click on a pipe in the viewer to see details here.</p>
            <div id="pipeDetails">
              <div class="form-group">
                <label for="pipeName" class="font-weight-bold">Task Name:</label>
                <input type="text" id="taskName" class="form-control" readonly>
                <input type="hidden" id="taskId" class="form-control">
              </div>
              <div class="form-group">
                <label for="layerName" class="font-weight-bold">Layer Name:</label>
                <input type="text" id="layerName" class="form-control" readonly>
              </div>
              <div class="form-group">
                <label for="projectName" class="font-weight-bold">Project Name:</label>
                <input type="text" id="projectName" class="form-control" readonly>
              </div>
              <div class="form-group">
                <label for="priority" class="font-weight-bold">Priority:</label>
                <input type="text" id="priority" class="form-control" readonly>
              </div>
              <div class="form-group">
                <label for="length" class="font-weight-bold">Length:</label>
                <input type="text" id="length" class="form-control" readonly>
              </div>
              <div class="form-group">
                <label for="status" class="font-weight-bold">Status:</label>
                <select id="status" class="form-control px-3 py-1 rounded-full text-sm bg-green-100 text-green-800 cursor-pointer border-none focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  <!--option value="not-started" class="bg-gray-100 text-gray-800">Not Started</option>
                  <option value="in-progress" class="bg-yellow-100 text-yellow-800">In Progress</option>
                  <option value="issue" class="bg-red-100 text-red-800">Issue Found</option>
                  <option value="completed" class="bg-green-100 text-green-800">Completed</option-->
                </select>
              </div>
              <div class="form-group">
                <label for="assignMember" class="font-weight-bold">Assign Member:</label>
                <select id="assignMember" class="form-control px-3 py-1 rounded text-sm border-gray-300 cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                </select>
              </div>
              <button class="btn btn-primary btn-block mt-3" style="display: none;" id="updateDetails">Update Details</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
  function getFloorId() {
    const urlParams = new URLSearchParams(window.location.search);
    const floorId = urlParams.get('floor_id');
    return floorId;
}
const floor_id = getFloorId();
  

// Function to render the legend
function renderLegend(pipeData) {
  const legendContainer = document.getElementById("legend");
  legendContainer.innerHTML = ""; // Clear existing content

    // Create a legend item
    const legendItem = document.createElement("div");
    legendItem.className = "legend-item";

    // Create the color box
    const colorBox = document.createElement("div");
    colorBox.className = "legend-color";
    colorBox.style.backgroundColor = '#007bff';

    // Create the text
    const legendText = document.createElement("span");
    legendText.className = "legend-text";
    legendText.textContent = `Total (${pipeData.total_count})`;

    // Append the color box and text to the legend item
    legendItem.appendChild(colorBox);
    legendItem.appendChild(legendText);

    // Append the legend item to the container
    legendContainer.appendChild(legendItem);
  pipeData.statuses.forEach((status) => {
    // Create a legend item
    const legendItem = document.createElement("div");
    legendItem.className = "legend-item";

    // Create the color box
    const colorBox = document.createElement("div");
    colorBox.className = "legend-color";
    colorBox.style.backgroundColor = status.color;

    // Create the text
    const legendText = document.createElement("span");
    legendText.className = "legend-text";
    legendText.textContent = `${status.status_name} (${status.count})`;

    // Append the color box and text to the legend item
    legendItem.appendChild(colorBox);
    legendItem.appendChild(legendText);

    // Append the legend item to the container
    legendContainer.appendChild(legendItem);
  });
}



function getTaskByFilters(floor_id, task_type, callback) {
    $.ajax({
        url: "/tasks/getTaskByFilters",
        type: "GET",
        data: {
            floor_id: floor_id,
            task_type: task_type,
            _token: $('meta[name="csrf-token"]').attr("content")
        },
        success: function (response) {
            callback(response); // Pass the data to the callback
        },
        error: function () {
            alert("An error occurred.");
        }
    });
}


function getTaskColor(status) {
	switch (status) {
		case "On Hold":
		case "Dropping Or Flex":
			return "#ff5722"; // Orange
		case "Working":
		case "Securing":
			return "#ffcc00"; // Blue
		case "Completed":
		case "Covers":
			return "#28a745"; // Green
		default:
			return "#808080"; // Default black
	}
}

/*function showPipeinfo(){
    // jQuery('#layer_AS_AUTOSPRINK_PIPE_block, #layer_AS_AUTOSPRINK_Pipe_Jumps_block').children().each(function (index) {
    // jQuery('layer_AS_AUTOSPRINK_2_20_INCH_20_PIPE_block, #layer_AS_AUTOSPRINK_1_20_1_2_20_INCH_20_PIPE_block, #layer_AS_AUTOSPRINK_1_20_1_2_20_INCH_20_PIPE_block ,#layer_AS_AUTOSPRINK_1_20_1_2_20_INCH_20_PIPE_block, #layer_AS_AUTOSPRINK_4_20_INCH_20_PIPE_block, #layer_AS_AUTOSPRINK_1_20_INCH_20_IGS_20_PIPE_block, #layer_AS_AUTOSPRINK_1_20_INCH_20_THR_20_PIPE_block, #layer_AS_AUTOSPRINK_3_20_INCH_20_PIPE_block').children().each(function (index) {
    jQuery('[id^="layer_"]').filter(function () {
    // Perform a case-insensitive check for "PIPE" in the id
    return jQuery(this).attr('id').toLowerCase().includes('pipe');
}).children().each(function (index) { 
        const currentElement = jQuery(this);
			const pathElement = jQuery(this).find('path')[0];

			// Retrieve the cvjs:bhandle attribute from the path element
			const taskName = pathElement.getAttribute('cvjs:bhandle');
        // Add class "cursor-pointer" to the <g> element
        currentElement.addClass('cursor-pointer');
			getTaskbyName(taskName, floor_id, 'pipe', function (PipeData) {
				// Update stroke and fill attributes of <path> elements
				currentElement.find('path').each(function () {
					const pathElement = jQuery(this);
					let color = getTaskColor(PipeData.task.task_status_name);

					// Update attributes for stroke and fill
					pathElement.attr('stroke', color); // Set stroke color to blue
					pathElement.attr('fill', color);   // Set fill color to blue
					pathElement.addClass('pipe'); // Add the 'pipe' class
				});
			});
			getTaskByFilters(floor_id, 'pipe', function (PipesData) {
				// Render the legend
				renderLegend(PipesData.statuses);
			});			


        // Add style for cursor pointer (for fallback)
        currentElement.css('cursor', 'pointer');

        // Add click event listener to make the pipe clickable (only once)
		currentElement.one('click', function () {
			// Get the path element clicked
			const pathElement = jQuery(this).find('path')[0];
			// Retrieve the cvjs:bhandle attribute from the path element
			const taskName = pathElement.getAttribute('cvjs:bhandle');
			// Example usage
			// const floor_id = getFloorId();
	
			// Usage
			getTaskbyName(taskName, floor_id, 'pipe', function (PipeData) {
				displayPipeDetails(PipeData); // Use the data in the callback
			});

			pathElement.setAttribute("stroke-width", "4");
		});
    });
}

function showPipeinfo() {
    jQuery('[id^="layer_"]').each(function () {
        const id = jQuery(this).attr('id').toLowerCase();
        const includeKeywords = /(pipe|head|pendent|upright|side wall)/;

        // Show only relevant layers (based on keywords)
        if (includeKeywords.test(id)) {
            jQuery(this).css('display', 'inline');

            jQuery(this).children().each(function () {
                const currentElement = jQuery(this);
                const pathElement = jQuery(this).find('path')[0];

                if (!pathElement) return;

                // Retrieve the `cvjs:bhandle` attribute from the path element
                const taskName = pathElement.getAttribute('cvjs:bhandle');

                // Add class for pointer style
                currentElement.addClass('cursor-pointer');

                // Common logic for both "pipe" and other sprinkler components
                const elementType = id.includes("pipe") ? "pipe" : "sprinkler";

                // Get task data for the element type
                getTaskbyName(taskName, floor_id, elementType, function (ElementData) {
                    currentElement.find('path').each(function () {
                        const pathElement = jQuery(this);
                        let color = getTaskColor(ElementData.task.task_status_name);

                        // Update attributes for stroke and fill
                        pathElement.attr('stroke', color); // Set stroke color
                        pathElement.attr('fill', color);   // Set fill color
                        pathElement.addClass(elementType); // Add class for type
                    });
                });

                // Get all tasks by filters for rendering the legend
                getTaskByFilters(floor_id, elementType, function (FilteredData) {
                    renderLegend(FilteredData.statuses);
					// Example usage (replace these values with dynamic data)
					const completedPipes = 100;
					const totalPipes = 260;
					updateProgress(completedPipes, totalPipes);
                });

                // Add click event listener to make the element clickable (only once)
                currentElement.one('click', function () {
                    const pathElement = jQuery(this).find('path')[0];
                    if (!pathElement) return;

                    const taskName = pathElement.getAttribute('cvjs:bhandle');
                    getTaskbyName(taskName, floor_id, elementType, function (ElementData) {
                        displayPipeDetails(ElementData); // Use the data in the callback
                    });

                    // Highlight the clicked element
						pathElement.setAttribute("stroke-width", "4");
                });

                // Add fallback cursor pointer style
                currentElement.css('cursor', 'pointer');
            });
        } else {
            // Hide all other layers
            jQuery(this).css('display', 'none');
        }
    });
}
*/

function showPipeinfo(){
	jQuery('[id^="layer_"]').each(function () {
		const id = jQuery(this).attr('id').toLowerCase();
		// Regular expression to match keywords
		const includeKeywords = /(pipe|head|pendent|upright|side wall)/;

		// Check for "pipe" specifically
		if (id.includes("pipe")) {
			// Show the PIPE layers (case-insensitive check)
			jQuery(this).css('display', 'inline');

			jQuery(this).children().each(function (index) { 
				const currentElement = jQuery(this);
				const pathElement = jQuery(this).find('path')[0];

				// Retrieve the cvjs:bhandle attribute from the path element
				const taskName = pathElement.getAttribute('cvjs:bhandle');
				// Add class "cursor-pointer" to the <g> element
				currentElement.addClass('cursor-pointer');
				
				getTaskbyName(taskName, floor_id, 'pipe', function (PipeData) {
					// Update stroke and fill attributes of <path> elements
					currentElement.find('path').each(function () {
						const pathElement = jQuery(this);
						let color = getTaskColor(PipeData.task.task_status_name);

						// Update attributes for stroke and fill
						pathElement.attr('stroke', color); // Set stroke color to blue
						pathElement.attr('fill', color);   // Set fill color to blue
						pathElement.addClass('pipe'); // Add the 'pipe' class
					});
				});
				getTaskByFilters(floor_id, 'pipe', function (PipesData) {
					// Render the legend
					renderLegend(PipesData);
					const completedPipes = PipesData.completed_length_in_inches;
					const totalPipes = PipesData.total_length_in_inches;
					updateProgress(completedPipes, totalPipes);					
				});			

				// Add style for cursor pointer (for fallback)
				currentElement.css('cursor', 'pointer');

				// Add click event listener to make the pipe clickable (only once)
				currentElement.one('click', function () {
					jQuery(".cvjs_wait_looper").fadeIn(1500);
					// Get the path element clicked
					const pathElement = jQuery(this).find('path')[0];
					// Retrieve the cvjs:bhandle attribute from the path element
					const taskName = pathElement.getAttribute('cvjs:bhandle');
					// Example usage
					// const floor_id = getFloorId();
			
					// Usage
					getTaskbyName(taskName, floor_id, 'pipe', function (PipeData) {
						displayPipeDetails(PipeData); // Use the data in the callback
						jQuery(".cvjs_wait_looper").fadeOut(1500);
					});

					pathElement.setAttribute("stroke-width", "4");
				});
			});
		} 	
		else if (includeKeywords.test(id)) {		
        // Handle other keywords
        jQuery(this).css('display', 'inline');
		} 		
		else {
			// Hide all other layers
			jQuery(this).css('display', 'none');
		}
	});
}

/*
function updatePipesFromAPI() {
    // Fetch task data from API
    const floorId = 20; // Replace with your actual floor ID
    const taskType = 'pipe'; // Specify the task type
    const apiUrl = `/tasks/getTaskByFilters?floor_id=${floorId}&task_type=${taskType}`;

    jQuery.getJSON(apiUrl, function (response) {
        if (!response || !response.tasks) {
            console.error("No task data available from API response.");
            return;
        }

        const tasks = response.tasks;

        // Iterate over all <path> elements in the SVG
        jQuery('path').each(function () {
            const pathElement = jQuery(this);
            const taskName = pathElement.attr('cvjs:bhandle'); // Get task name from the path's attribute

            if (taskName) {
                // Find the corresponding task in the API response
                const task = tasks.find((t) => t.task_name === taskName);

                if (task) {
                    // If task exists, make the pipe visible and update its attributes
                    pathElement.css('display', 'block'); // Show the pipe

                    // Get the color for the task
                    const color = getTaskColor(task.status_name);

                    // Update the path's stroke and fill colors
                    pathElement.attr('stroke', color);
                    pathElement.attr('fill', color);

                    // Add a clickable class and make the pipe interactive
                    pathElement.addClass('pipe cursor-pointer');

                    // Add click event listener to show pipe details
                    pathElement.off('click').on('click', function () {
                        jQuery(".cvjs_wait_looper").fadeIn(1500);

                        // Display pipe details using the task data
                        displayPipeDetails(task);

                        jQuery(".cvjs_wait_looper").fadeOut(1500);

                        // Highlight the clicked pipe
                        pathElement.attr('stroke-width', '4');
                    });
                } else {
                    // If task does not exist, hide the pipe
                    pathElement.css('display', 'none');
                }
            } else {
                // If no taskName attribute is found, hide the pipe
                pathElement.css('display', 'none');
            }
        });

        // Update progress and legend based on the response
        renderLegend(response);
        updateProgress(response.completed_length_in_inches, response.total_length_in_inches);
    }).fail(function (error) {
        console.error("Failed to fetch task data:", error);
    });
}
*/

function convertToFeetAndInches(value) {
    const feet = Math.floor(value); // Extract the feet part
    const inches = Math.round((value - feet) * 12); // Convert the fractional part to inches
    return `${feet}' - ${inches}"`;
}




function updateProgress(completed, total) {
    const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;

    // Update the progress bar and text
    const progressBar = document.getElementById('progress-bar');
    const progressPercentage = document.getElementById('progress-percentage');
    const completedPipesText = document.getElementById('completed-pipes');
    const totalPipesText = document.getElementById('total-pipes');

    progressBar.style.width = `${percentage}%`;
    progressBar.setAttribute('aria-valuenow', percentage);
    progressPercentage.textContent = `${percentage}%`;
    completedPipesText.textContent = `${convertToFeetAndInches(completed)} completed`;
    totalPipesText.textContent = `${convertToFeetAndInches(total)} total`;
}




let previousPathElement = null; // To store the previously clicked path element
let originalColor = ""; // To store the original color of the previous path

// Function to handle pipe click
function onPipeClick(event) {
    const clickedPath = event.target; // Get the clicked path element

    // Check if a previous pipe was clicked
    if (previousPathElement) {
        // Restore the original color of the previously clicked pipe
        previousPathElement.setAttribute("stroke", originalColor);
        previousPathElement.setAttribute("fill", originalColor);
    }

    // Save the current pipe's original color
    originalColor = clickedPath.getAttribute("stroke");

    // Highlight the clicked pipe by changing its color to blue
    clickedPath.setAttribute("stroke", "blue");
    clickedPath.setAttribute("fill", "blue");

    // Update the reference to the currently clicked pipe
    previousPathElement = clickedPath;
}

// Add event listeners to all pipe path elements
document.querySelectorAll("path.cvjs_handles").forEach((path) => {
    path.addEventListener("click", onPipeClick);
});
  
    // Function to display pipe details dynamically
    function displayPipeDetails(pipeData) {
		// Populate fields dynamically
		document.getElementById("taskId").value = pipeData.task.task_id || "";
		document.getElementById("taskName").value = pipeData.task.task_name || "N/A";
		document.getElementById("layerName").value = pipeData.task.layer_name || "N/A";
		document.getElementById("projectName").value = pipeData.task.project_name || "N/A";
		document.getElementById("priority").value = pipeData.task.priority_name || "N/A";
		document.getElementById("length").value = pipeData.task.length || "N/A";

		// Populate the dropdowns
		populateDropdowns(pipeData.task, pipeData.statuses, pipeData.members);

    }



    // Handle update button click
    document.getElementById("updateDetails").addEventListener("click", () => {
      const updatedDetails = {
        id: document.getElementById("taskId").value,
        name: document.getElementById("taskName").value,
        status: document.getElementById("status").value,
        memberId: document.getElementById("assignMember").value
      };
	  $taskIds = [{
		  id:updatedDetails.id,
		  
	  }];
	  
	  updateTaskbyId(updatedDetails.id, updatedDetails.status,updatedDetails.memberId);
      // console.log("Updated Details:", updatedDetails.id + ' ' + updatedDetails.status);
      console.log("Updated Details:", updatedDetails.id + ' ' + updatedDetails.status + ' ' + updatedDetails.memberId);
      // Add AJAX call or API request here
    });
	
function populateDropdowns(pipeData, statuses, members) {
  // Populate Task Status Dropdown
  const statusDropdown = document.getElementById("status");
  statusDropdown.innerHTML = ""; // Clear existing options

  statuses.forEach((status) => {
    const option = document.createElement("option");
    option.value = status.status_id;
    option.textContent = status.status_name;
    // Set selected value based on pipeData.task_status_id
    if (pipeData.task_status_id === status.status_id) {
      option.selected = true;
    }
    statusDropdown.appendChild(option);
  });

  // Populate Member Dropdown
  const memberDropdown = document.getElementById("assignMember");
  memberDropdown.innerHTML = ""; // Clear existing options
    const option = document.createElement("option");
    option.value = '';
    option.textContent = `Select a Member`;
	// option.disabled = true; // Disable selection of the placeholder
	option.selected = true; // Set as the default selected option
	memberDropdown.appendChild(option);
  members.forEach((member) => {
    const option = document.createElement("option");
    option.value = member.id;
    option.textContent = `${member.first_name} ${member.last_name}`;
    // Set selected value based on pipeData.member_id
    if (pipeData.member_id === member.id) {
      option.selected = true;
    }
    memberDropdown.appendChild(option);
  });
  memberDropdown.addEventListener("change", checkSelections);
  statusDropdown.addEventListener("change", checkSelections);
}

// Get references to dropdowns and button
const memberDropdown = document.getElementById("assignMember");
const statusDropdown = document.getElementById("status");
const updateDetailsButton = document.getElementById("updateDetails");

  
// Function to check if a valid selection is made
function checkSelections() {
    const isMemberSelected = memberDropdown.value !== ""; // Check if a member is selected
    const isStatusSelected = statusDropdown.value !== ""; // Check if a status is selected

    // Show the button only if both dropdowns have a valid selection
    if (isMemberSelected || isStatusSelected) {
        updateDetailsButton.style.display = "inline-block";
    } else {
        updateDetailsButton.style.display = "none";
    }
}




  </script>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

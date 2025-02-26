@extends('layouts.admin.app', ['title' => 'All Products'])

@push('styles')
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('Admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')


	<link href="{{ asset('cadviewer/app/css/cvjs-core-styles.css')}}" media="screen" rel="stylesheet" type="text/css" />
	<link href="{{ asset('cadviewer/app/css/font-awesome.min.css')}}" media="screen" rel="stylesheet" type="text/css" />
	<link href="../cadviewer/app/css/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
	<link href="{{ asset('cadviewer/app/css/jquery.qtip.min.css')}}" media="screen" rel="stylesheet" type="text/css" />
	<link href="{{ asset('cadviewer/app/css/jquery-ui-1.13.2.min.css')}}" media="screen" rel="stylesheet" type="text/css" />

	<link href="{{ asset('cadviewer/app/css/bootstrap-cadviewer.css')}}" media="screen" rel="stylesheet" type="text/css" />
	

	<script src="{{ asset('cadviewer/app/js/jquery-2.2.3.js')}}" type="text/javascript"></script>
	<!-- <script src="{{ asset('cadviewer/app/js/jquery-3.5.1.js')}}" type="text/javascript"></script> -->
	 <script src="{{ asset('cadviewer/app/js/jquery.qtip.min.js')}}" type="text/javascript"></script> 

	<script src="{{ asset('cadviewer/app/js/popper.js')}}" type="text/javascript"></script>

	<script src="{{ asset('cadviewer/app/js/bootstrap-cadviewer.js')}}" type="text/javascript"></script>

	 

	<script src="{{ asset('cadviewer/app/js/jquery-ui-1.13.2.min.js')}}" type="text/javascript"></script>

	<script src="{{ asset('cadviewer/app/cv/cv-pro/cadviewer.min.js')}}" type="text/javascript" ></script> 


	<script src="{{ asset('cadviewer/app/cv/cv-pro/custom_rules_template.js')}}" type="text/javascript" ></script>
    <script src="{{ asset('cadviewer/app/cv/cv-custom_commands/CADViewer_custom_commands.js')}}" type="text/javascript" ></script>

	<script src="{{ asset('cadviewer/app/cv/cvlicense.js')}}" type="text/javascript" ></script> 
	 
	 
	<script src="{{ asset('cadviewer/app/js/bootstrap-multiselect.js')}}" type="text/javascript" ></script>
	<script src="{{ asset('cadviewer/app/js/library_js_svg_path.js')}}" type="text/javascript"></script>			
	<script src="{{ asset('cadviewer/app/js/snap.svg-min.js')}}" type="text/javascript" ></script>

	<script src="{{ asset('cadviewer/app/js/cvjs_api_styles_2_0_26.js')}}" type="text/javascript" ></script>
	<script src="{{ asset('cadviewer/app/js/rgbcolor.js')}}" type="text/javascript" ></script>
	<script src="{{ asset('cadviewer/app/js/StackBlur.js')}}" type="text/javascript" ></script>
	<script src="{{ asset('cadviewer/app/js/canvg.js')}}" type="text/javascript"  ></script>
	<script src="{{ asset('cadviewer/app/js/list.js')}}" type="text/javascript"></script>
	<script src="{{ asset('cadviewer/app/js/jscolor.js')}}" type="text/javascript" ></script>
	
	<script src="{{ asset('cadviewer/app/js/jstree/jstree.min.js')}}"></script>
	<script src="{{ asset('cadviewer/app/js/xml2json.min.js')}}"></script>
	<script src="{{ asset('cadviewer/app/js/d3.v3.min.js')}}"></script>  
	<script src="{{ asset('cadviewer/app/js/qrcode.min.js')}}"> type="text/javascript"></script> 
		<div id="json-data" class="matech-json" style="display: none;">
			{{ $tasksJson }}
		</div>
		<script id="matech-json-data">
			const jsonData = {!! $tasksJson !!};  // The JSON passed from the controller
		</script>
	<script type="text/javascript">

	// Location of installation folders
    var ServerBackEndUrl = location.origin+"/cadviewer/";
    var ServerUrl = location.origin+"/cadviewer/";
    var ServerLocation = "";



	// PATH and FILE to be loaded, can be in formats DWG, DXF, DWF, SVG , JS, DGN, PCF, JPG, GIF, PNG
	//var FileName = ServerUrl + "/content/drawings/dwg/Asbestos_drawing_01.dwg";		
      var FileName = ServerUrl + "/content/drawings/dwg/a2nd_floor.dwg";	


	$(document).ready(function()
		{

		//$(document).html();
		// Set CADViewer with full CADViewer Pro features
		cvjs_CADViewerPro(true);
		cvjs_debugMode(true);
		cvjs_setAllServerPaths_and_Handlers(ServerBackEndUrl, ServerUrl, ServerLocation, "PHP", "JavaScript", "floorPlan");
				
		// uncomment if you want to use NodeJS cadviewer-conversion-server as backend
		//cvjs_setHandlers_FrontEnd("NodeJS", "JavaScript", "floorPlan");
		

		// set to true to embed SpaceObject Menu, false to omit
		cvjs_setSpaceObjectsCustomMenu("/content/customInsertSpaceObjectMenu/", "cadviewercustomspacecommands.json", true);


										
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
		cvjs_loadCADViewerLanguage("English", "/app/cv/cv-pro/language_table/cadviewerProLanguage.xml");
		// Available languages:  "English" ; "French, "Korean", "Spanish", "Portuguese", "Portuguese (Brazil)" ;  "Russian" ; "Malay" ;  "Chinese-Simplified"
				
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
		 
		 		
		 
		// set the location to license key, typically the js folder in main app application folder 
		 cvjs_setLicenseKeyPath(ServerUrl+"/app/cv/");
		// alternatively, set the key directly, by pasting in the cvKey portion of the cvlicense.js file, note the JSON \" around all entities 	 
		//cvjs_setLicenseKeyDirect('{ \"cvKey\": \"00110010 00110010 00110000 00110010 00110001 00111001 00111001 00110001 00110100 00111000 00110001 00110100 00110101 00110001 00110101 00110111 00110001 00110101 00111001 00110001 00110100 00111000 00110001 00110101 00110010 00110001 00110100 00110101 00110001 00110100 00110001 00110001 00110100 00110000 00110001 00111001 00110111 00110010 00110000 00110111 00110010 00110000 00110110 00110010 00110000 00110001 00110010 00110001 00110000 00110010 00110000 00111000 00110010 00110001 00110000 00110010 00110000 00111000 00110010 00110001 00110000 00110010 00110000 00110111 00110001 00111001 00111000 00110010 00110000 00110110 00110010 00110000 00111000 00110010 00110000 00110111 00110001 00111001 00111001 00110010 00110001 00110001 00110010 00110000 00111000 00110010 00110000 00110111 00110010 00110001 00110001 00110010 00110000 00110101 00110010 00110000 00111000 \" }');		 
		//  cvjs_setLicenseKeyDirect('{ "cvKey": "00110001 00110010 00110000 00111000 00110001 00110101 00110111 00110001 00110100 00111000 00110001 00110101 00110101 00110001 00110011 00111000 00110001 00110101 00110101 00110001 00110101 00111000 00110001 00110011 00111001 00110001 00110101 00110001 00110001 00110100 00111000 00110001 00110101 00110110 00110001 00110101 00110111 00110001 00110101 00111001 00110001 00110101 00110110 00110010 00110001 00110000 00110001 00110100 00110111 00110001 00110101 00111001 00110001 00110100 00110000 00110001 00110101 00110101 00110001 00110101 00110111 00110001 00110101 00110010 00110001 00110101 00110111 00110001 00110100 00110101 00110010 00110001 00110000 00110001 00110101 00110111 00110001 00110100 00110101 00110001 00110100 00110111 00110001 00111001 00111000 00110010 00110000 00110110 00110010 00110000 00111000 00110010 00110000 00110110 00110010 00110000 00110100 00110010 00110001 00110001 00110010 00110000 00110111 00110010 00110000 00110110 00110010 00110001 00110001 00110010 00110000 00110111 00110010 00110000 00110000 " }');
		 

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
		
		cvjs_setRedlinesAbsolutePath(ServerUrl+'/content/redlines/v7/', ServerLocation+'/content/redlines/v7/');

		// NOTE ABOVE: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF REDLINES


		// NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF FILES AND FILE MANAGER

		// I am setting the full path to the location of the floorplan drawings (typically  /home/myserver/drawings/floorplans/)
		// and the relative location of floorplans drawings relative to my current location
		// as well as the URL to the location of floorplan drawings with username and password if it is protected "" "" if not

		// cvjs_setServerFileLocation(ServerLocation+'/content/drawings/dwg/', 'cadviewer/content/drawings/dwg/', ServerUrl+'/content/drawings/dwg/',"","");
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
//		cvjs_conversion_addAXconversionParameter("firstlayout", "");		 
		cvjs_conversion_addAXconversionParameter("fpath", ServerLocation + "/converters/ax2022/windows/fonts/");		 

	
//		cvjs_conversion_addAXconversionParameter ("RL", "IDB");
//		cvjs_conversion_addAXconversionParameter ("TL", "IDB_REF");	

//		cvjs_conversion_addAXconversionParameter ("hlall", "");	
		
	
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
	
		window.alert("WE call our server, then we update the modal"+ rmid+"  "+node);
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
	function cvjs_graphicalObjectOnChange(type, graphicalObject, spaceID){
		debugger;
	  // do something with the graphics object created! 
		console.log(" cvjs_graphicalObjectOnChange: "+type+" "+graphicalObject+" "+spaceID+" indexSpace: "+graphicalObject.toLowerCase().indexOf("space"));

 /*     UPDATE SERVER WITH REDLINES ON CHANGE       
        if (graphicalObject.toLowerCase().indexOf('redline')>-1 && !type.toLowerCase().indexOf('click')==0 ){
//            cvjs_setStickyNoteSaveRedlineUrl(ServerLocation + "/content/redlines/v7/test"+Math.round(Math.random()*100)+".js");
            cvjs_setStickyNoteSaveRedlineUrl(ServerLocation + "/content/redlines/v7/test_fixed.js");
            cvjs_saveStickyNotesRedlines("floorPlan", false, "THIS IS PLACEHOLDER FOR CUSTOM STUFF TO SERVER");
        }
*/


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

	cvjs_mouseenter_handleObjectStyles("#F00", 10.0, 1.0, true, id, handle);	
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

	// if we do not have a mouse over we open the modal,  but if the space is clicked we do not do anything
	if (!mouseover){
		mouseover = true;
		if (!mouseclick) cvjs_changeSpaceFixedLocation(id);
	}


	//cvjs_mouseover_handleObjectPopUp(id, handle);	
}

function cvjs_mouseleave(id, handle, entity){

	console.log("mouseleave "+id+"  "+handle+"  "+jQuery("#"+id).css("color"));

	mouseover = false;

	console.log("mouseleave variable mouseclick: "+mouseclick);

	if (!mouseclick)       // we hide the pop upon leaving, but do not so if the space is clicked.
		cvjs_hideOnlyPop();

}



// END OF MOUSE OPERATION




//   custom js for own use

$(document).ready(function() {
	  let blinkIntervals = []; // Array to store blinking intervals

    // Function to generate the task list in HTML
    function generateTaskList(tasks) {
        $("#onHoldList, #workingList, #completedList").empty(); // Clear existing lists
        tasks.forEach(function(task) {
			var json_task = JSON.stringify(task);
            let checkbox = '<div><input type="checkbox" id="task_'+task.task_id+'"  data-project_status_name="'+task.project_status_name+'" data-layer_name="'+task.layer_name+'" data-task_name="'+task.task_name+'"><label for="task_'+task.task_id+'">'+task.task_name+'</label></div>';
            if (task.project_status_name === "On Hold") {
                $("#onHoldList").append(checkbox);
            } else if (task.project_status_name === "Working") {
                $("#workingList").append(checkbox);
            } else if (task.project_status_name === "Completed") {
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
                let target = $(this).find("." + task.task_name).find("g").first();
                if (target.length > 0) {
                    let color = getTaskColor(task.project_status_name);
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
			debugger;
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
                return "yellow"; // Orange
            case "In Progress":
                return "yellow"; // Blue
            case "Completed":
                return "yellow"; // Green
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
	
	function updateTasks(taskIds, statusId) {
		if (taskIds.length === 0) {
			alert("No tasks selected.");
			return;
		}

		$.ajax({
			url: "/update-tasks",
			type: "POST",
			data: {
				tasks: taskIds.map(id => ({ task_id: id, project_status_id: statusId })),
				_token: $('meta[name="csrf-token"]').attr("content")
			},
			success: function (response) {
				if (response.success) {
					alert("Tasks updated successfully.");
					loadTasks(); // Reload task list
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

 	</script>
<section>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
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


<div id="task-actions">
    <label for="status-dropdown">Set Status:</label>
    <select id="status-dropdown">
        <option value="1">On Hold</option>
        <option value="2">Working</option>
        <option value="3">Completed</option>
    </select>
    <button id="update-selected">Update Selected</button>
    <button id="update-all">Update All</button>
</div>


	<table id="none">
	<tr>
	<td>

	<!--This is the CADViewer floorplan div declaration -->

		<div id="floorPlan"  style="border:2px none; width:1800;height:1400;">
		</div>

	<!--End of CADViewer declaration -->

	</td>
	</tr>
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

	</script>
@endpush
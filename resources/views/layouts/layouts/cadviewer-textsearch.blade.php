<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>Laravel CADViewer Sample 1</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


	<script src="{{ asset('/app/js/jquery-2.2.3.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/app/js/jquery.qtip.min.js') }}" type="text/javascript"></script>
	<link href="{{ asset('/app/css/jquery.qtip.min.css') }}" media="screen" rel="stylesheet" type="text/css" />

	<script src="{{ asset('/app/js/popper.js') }}" type="text/javascript"></script>

	<script src="{{ asset('/app/js/bootstrap.min.js') }}" type="text/javascript"></script>
	<link href="{{ asset('/app/css/bootstrap.min.css') }}" media="screen" rel="stylesheet" type="text/css" />

	<script src="{{ asset('/app/js/jquery-ui-1.11.4.min.js') }}" type="text/javascript"></script>
	<link href="{{ asset('/app/css/jquery-ui-1.11.4.min.css') }}" media="screen" rel="stylesheet" type="text/css" />

	<script src="{{ asset('/app//cv/cv-core/axuploader_2_19.js') }}" type="text/javascript" ></script>

  <script src="{{ asset('/app/cv/cv-pro/cadviewer.min.js') }}" type="text/javascript" ></script> 
<!--    <script src="{{ asset('/app/cv/cv-pro/cadviewer_base_pro_6_4_31_laravel.js') }}" type="text/javascript" ></script>  -->
 
	<script src="{{ asset('/app/cv/cv-custom_commands/CADViewer_custom_commands.js') }}" type="text/javascript" ></script>

	<script src="{{ asset('/app/cv/cvlicense.js') }}" type="text/javascript" ></script>

	<script src="{{ asset('/app/js/bootstrap-multiselect.js') }}" type="text/javascript" ></script>
	<link href="{{ asset('/app/css/bootstrap-multiselect.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/app/css/cvjs_6.1.0.css') }}" media="screen" rel="stylesheet" type="text/css" />

	<script src="{{ asset('/app/js/library_js_svg_path.js') }}" type="text/javascript"></script>			
	<script src="{{ asset('/app/js/snap.svg-min.js') }}" type="text/javascript" ></script>

	<script src="{{ asset('/app/js/rgbcolor.js') }}"type="text/javascript" ></script>
	<script src="{{ asset('/app/js/StackBlur.js') }}"type="text/javascript" ></script>
	<script src="{{ asset('/app/js/canvg.js') }}" type="text/javascript"  ></script>
	<script src="{{ asset('/app/js/list.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/app/js/jscolor.js') }}" type="text/javascript" ></script>
	
	<script src="{{ asset('/app/js/jstree/jstree.min.js') }}"></script>
	<script src="{{ asset('/app/js/xml2json.min.js') }}"></script>

	<script type="text/javascript">

// PATH and FILE to be loaded, can be in formats DWG, DXF, DWF, SVG , JS, DGN, PCF, JPG, GIF, PNG

// Location of installation folders
var ServerBackEndUrl = "";
var ServerUrl = "http://localhost/";
var ServerLocation = "/home/casper/laravel/example-app/public/";

var FileName = ServerUrl + "/home/casper/laravel/example-app/public/content/drawings/dwg/hq17_.dwg";	

// NodeJS server  with JavaScript front-end
var ServerBackEndUrl = "http://127.0.0.1:3000/";
var ServerUrl = "http://localhost/";
var ServerLocation = "/nodejs/cadviewerServer/";


var FileName = ServerUrl + "/content/drawings/dwg/hq17_.dwg";	
        

$(document).ready(function()
    {

    // Set CADViewer with full CADViewer Pro features
    cvjs_CADViewerPro(true);

    cvjs_setServerLocationURL(ServerLocation, ServerUrl);
    cvjs_setServerBackEndUrl(ServerBackEndUrl);
    // cvjs_setServerBackEndUrl(ServerBackEndUrl);
   // cvjs_setHandlerSettings("PHP", "floorPlan");


    // cvjs_setServerBackEndUrl(ServerBackEndUrl);
    // cvjs_setHandlerSettings("PHP", "floorPlan");

    cvjs_setHandlers_FrontEnd("NodeJS", "JavaScript", "floorPlan");




    cvjs_debugMode(true);
    

    // http://127.0.0.1:8081/html/CADViewer_json_610.html?drawing_name=/home/mydrawing.dgn&dgn_workspace=/home/workspace.txt&json_location=c:/nodejs/cadviewer/content/helloworld.json&print_modal_custom_checkbox=add_json

    // IF CADVIEWER IS OPENED WITH A URL  http://localhost/cadviewer/html/CADViewer_sample_610.html?drawing_name=../content/drawings/dwg/hq17.dwg
    //  or CADViewer_sample_610.html?drawing_name=http://localhost/cadviewer/content/drawings/dwg/hq17.dwg
    //  this code segment will pass over the drawing_name to FileName for load of drawing


    // For "Merge DWG" / "Merge PDF" commands, set up the email server to send merged DWG files or merged PDF files with redlines/interactive highlight.
    // See php / xampp documentation on how to prepare your server
    cvjs_emailSettings_PDF_publish("From CAD Server", "my_from_address@mydomain.com", "my_cc_address@mydomain.com", "my_reply_to@mydomain.com");
    
    // CHANGE LANGUAGE - DEFAULT IS ENGLISH
    cvjs_loadCADViewerLanguage("English");// cvjs_loadCADViewerLanguage("English", "/app/cv/cv-pro/language_table/cadviewerProLanguage.xml");

        
    // Available languages:  "English" ; "French, "Korean", "Spanish", "Portuguese", "Portuguese (Brazil)" ;  "Russian" ; "Malay" ;  "Chinese-Simplified"
            
    // Set Icon Menu Interface controls. Users can: 
    // 1: Disable all icon interfaces
    //  cvjs_displayAllInterfaceControls(false, "floorPlan");  // disable all icons for user control of interface
    // 2: Disable either top menu icon menus or navigation menu, or both
    //	cvjs_displayTopMenuIconBar(false, "floorPlan");  // disable top menu icon bar
    //	cvjs_displayTopNavigationBar(false, "floorPlan");  // disable top navigation bar
    // 3: Users can change the number of top menu icon pages and the content of pages, based on a configuration file in folder /cadviewer/app/js/menu_config/

    cvjs_setTopMenuXML("floorPlan", "cadviewer_full_commands_01.xml"); //cvjs_setTopMenuXML("floorPlan", "cadviewer_full_commands_01.xml", "/app/cv/cv-pro/menu_config/");
    
    // Initialize CADViewer  - needs the div name on the svg element on page that contains CADViewerJS and the location of the
    // main application "app" folder. It can be either absolute or relative


    // THIS IS THE DESIGN OF THE pop-up MODAL WHEN CLICKING ON SPACES
    var my_cvjsPopUpBody = "<div class=\"cvjs_modal_1\" onclick=\"my_own_clickmenu1();\">Load<br>Status<br><i class=\"glyphicon glyphicon-transfer\"></i></div>";
    my_cvjsPopUpBody += "<div class=\"cvjs_modal_1\" onclick=\"my_own_clickmenu2();\">Engine<br>Status<br><i class=\"glyphicon glyphicon-info-sign\"></i></div>";
    my_cvjsPopUpBody += "<div class=\"cvjs_modal_1\" onclick=\"cvjs_zoomHere();\">Zoom<br>Here<br><i class=\"glyphicon glyphicon-zoom-in\"></i></div>";

    // SETTINGS OF THE COLORS OF SPACES
    cvjsRoomPolygonBaseAttributes = {
            fill: '#FFF',   // #FFF   #ffd7f4
            "fill-opacity": "0.1",   // 0.1
            stroke: '#CCC',  
            'stroke-width': 0.5,
            'stroke-linejoin': 'round',
        };
        
    cvjsRoomPolygonHighlightAttributes = {
                    fill: '#a4d7f4',
                    "fill-opacity": "0.5",
                    stroke: '#a4d7f4',
                    'stroke-width': 1.0
                };
                
    cvjsRoomPolygonSelectAttributes = {
                    fill: '#5BBEF6',
                    "fill-opacity": "0.5",
                    stroke: '#5BBEF6',
                    'stroke-width': 1.0
                };

    // Initialize CADViewer - needs the div name on the svg element on page that contains CADViewerJS and the location of the
    // And we intialize with the Space Object Custom values
    cvjs_InitCADViewer_highLight_popUp_app("floorPlan", ServerUrl+"app/", cvjsRoomPolygonBaseAttributes, cvjsRoomPolygonHighlightAttributes, cvjsRoomPolygonSelectAttributes, my_cvjsPopUpBody );

//		cvjs_InitCADViewer_app("floorPlan", ServerUrl+"app/");
//		//cvjs_InitCADViewerJS_app("floorPlan", "../app/");
     
    // set the location to license key, typically the js folder in main app application folder ../app/js/
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
    
    // If the redline load/save is application controlled, the user will set the load/save files using the following methods:
    //cvjs_setStickyNoteRedlineUrl("../redlines/filename-red_t1.js");
    //cvjs_setStickyNoteSaveRedlineUrl("../redlines/filename-red_s1.js");

    // Redines folder location is
    // Redlines folder location used when file-manager is used for upload and redline selection
    //cvjs_setRedlinesRelativePath('../redlines/demo_red/', ServerLocation+'/redlines/demo_red/');
    cvjs_setRedlinesAbsolutePath(ServerUrl+'/content/redlines/fileloader_610/', ServerLocation+'/content/redlines/fileloader_610/');

    // NOTE ABOVE: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF REDLINES


    // NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF FILES AND FILE MANAGER

    // I am setting the full path to the location of the floorplan drawings (typically  /home/myserver/drawings/floorplans/)
    // and the relative location of floorplans drawings relative to my current location
    // as well as the URL to the location of floorplan drawings with username and password if it is protected "" "" if not

    // cvjs_setServerFileLocation(ServerLocation+'/content/drawings/dwg/', '../content/drawings/dwg/', ServerUrl+'/content/drawings/dwg/',"","");
    cvjs_setServerFileLocation_AbsolutePaths(ServerLocation+'/content/drawings/dwg/', ServerUrl+'content/drawings/dwg/',"","");
    // NOTE ABOVE: THESE SETTINGS ARE FOR SERVER CONTROLS FOR UPLOAD OF FILES AND FILE MANAGER
    


    // NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS OF SPACE OBJECTS
    // Set the path to folder location of Space Objects
    cvjs_setSpaceObjectsAbsolutePath(ServerUrl+'/content/spaceObjects/', ServerLocation+'/content/spaceObjects/');
    // NOTE ABOVE: THESE SETTINGS ARE FOR SERVER CONTROLS OF SPACE OBJECTS

    // NOTE BELOW: THESE SETTINGS ARE FOR SERVER CONTROLS FOR CONVERTING DWG, DXF, DWF files

    // settings of Converter Path, Controller and Converter Name are done in the XXXHandlerSettings.js files

    cvjs_conversion_clearAXconversionParameters();
    cvjs_conversion_addAXconversionParameter("last", "");		 

    cvjs_conversion_addAXconversionParameter("rl", "RM_");		 
    cvjs_conversion_addAXconversionParameter("tl", "RM_TXT");		 
    
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
});


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
        
        cvjs_setSpaceObjectsDefaultLayer("floorPlan", "spaceLayer1");

        // NEW:  clear the internal space layer  for selection and highlight
        cvjs_clearSpaceLayer();

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

}

function cvjs_graphicalObjectCreated(graphicalObject){

// do something with the graphics object created!
//		window.alert(graphicalObject);

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



var hatchtype = 0;

// This is the function that illustrates how to color and label stuff
function highlight_objects(){

hatchtype++;
if (hatchtype > 5) hatchtype=1;
    
var spaceObjectIds = cvjs_getSpaceObjectIdList();

for (spc in spaceObjectIds)
{
    if (hatchtype == 1) cvjs_highlightSpace(spaceObjectIds[spc], highlight_purple);
    if (hatchtype == 2) cvjs_highlightSpace(spaceObjectIds[spc], highlight_blue); 
    if (hatchtype == 3) cvjs_highlightSpace(spaceObjectIds[spc], highlight_red); 
    if (hatchtype == 4) cvjs_highlightSpace(spaceObjectIds[spc], highlight_green); 
    if (hatchtype == 5) cvjs_highlightSpace(spaceObjectIds[spc], highlight_yellow); 	
}

}

// This is the function that illustrates how to color and label stuff
function highlight_border_objects(){

hatchtype++;
if (hatchtype > 6) hatchtype=1;
    
var spaceObjectIds = cvjs_getSpaceObjectIdList();

for (spc in spaceObjectIds)
{
    if (hatchtype == 1) cvjs_highlightSpace(spaceObjectIds[spc], highlight_purple_borders);
    if (hatchtype == 2) cvjs_highlightSpace(spaceObjectIds[spc], highlight_blue_borders); 
    if (hatchtype == 3) cvjs_highlightSpace(spaceObjectIds[spc], highlight_red_borders); 
    if (hatchtype == 4) cvjs_highlightSpace(spaceObjectIds[spc], highlight_green_borders); 
    if (hatchtype == 5) cvjs_highlightSpace(spaceObjectIds[spc], highlight_yellow_borders); 	
    if (hatchtype == 6) cvjs_highlightSpace(spaceObjectIds[spc], highlight_bordeau_red_borders); 	
}

}


function hatch_objects(){

// I am making an API call to the function cvjs_getSpaceObjectIdList()
// this will give me an array with IDs of all Spaces in the drawing
var spaceObjectIds = cvjs_getSpaceObjectIdList();

hatchtype++;
if (hatchtype >4) hatchtype=1;

for (spc in spaceObjectIds)
{
    if (hatchtype == 1) cvjs_hatchSpace(spaceObjectIds[spc], "pattern_45degree_crosshatch_fine", "#550055" , "0.5");
    if (hatchtype == 2) cvjs_hatchSpace(spaceObjectIds[spc], "pattern_45degree_standard", "#AA2200" , "0.5");
    if (hatchtype == 3) cvjs_hatchSpace(spaceObjectIds[spc],  "pattern_135degree_wide", "#0055BB" , "0.5");
    if (hatchtype == 4) cvjs_hatchSpace(spaceObjectIds[spc],  "pattern_90degree_wide", "#220088" , "0.5");

/*
            cvjs_ApplyRelativeLinearGradientStandard3ColorsOnSpaceObjectId(roomLayer1, spaceObjectIds[spc], '#AA2200', '#009922', '#0000FF', "0.7")
    if (hatchtype == 5)
    if (hatchtype == 6)
            cvjs_ApplyRelativeLinearGradientStandard3ColorsOnSpaceObjectId(roomLayer1, spaceObjectIds[spc], '#FFF', '#00CC00', '#0000FF', "0.4")
*/
}
}

// This is the function that illustrates how set a custom mouse-over label
function tooltip_objects(){

// I am setting the mode to custom tool tips
cvjs_setCustomToolTip(true);

// I am making an API call to the function cvjs_getSpaceObjectIdList()
// this will give me an array with IDs of all Spaces in the drawing
var spaceObjectIds = cvjs_getSpaceObjectIdList();
var i=0;

for (spc in spaceObjectIds)
{

    // We randomly set the status
    
    var myObject = cvjs_returnSpaceObjectID(spaceObjectIds[spc]);
    
    if ((i % 3) ==0){
        var textString = new Array("ID: "+spaceObjectIds[spc]+" Type:"+myObject.type, "Linked:"+myObject.linked);
        cvjs_setCustomToolTipValue(spaceObjectIds[spc], textString);
    }
    else{
        if ((i % 3) == 1){
            var textString = new Array('Hi!', 'second line custom tooltip');
            cvjs_setCustomToolTipValue(spaceObjectIds[spc], textString);
        }
        else{
            var textString = new Array("line 1 line 1 line 1 line 1  ", "line 2 line 2 line 2 line 2", "line 3 line 3 line 3 line 3","line4 line4 line 4 line 4");
            cvjs_setCustomToolTipValue(spaceObjectIds[spc], textString);
        }
    }
    i++;
}
}




// Callback Method on Creation and Delete 
function cvjs_graphicalObjectOnChange(type, graphicalObject, spaceID){
  // do something with the graphics object created! 
    window.alert(" cvjs_graphicalObjectOnChange: "+type+" "+graphicalObject+" "+spaceID+" indexSpace: "+graphicalObject.toLowerCase().indexOf("space"));

/*     UPDATE SERVER WITH REDLINES ON CHANGE       
    if (graphicalObject.toLowerCase().indexOf('redline')>-1 && !type.toLowerCase().indexOf('click')==0 ){
//            cvjs_setStickyNoteSaveRedlineUrl(ServerLocation + "/content/redlines/fileloader_610/test"+Math.round(Math.random()*100)+".js");
        cvjs_setStickyNoteSaveRedlineUrl(ServerLocation + "/content/redlines/fileloader_610/test_fixed.js");
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



//custom method to invoice Search API
function customSearchText(){

var search_string = jQuery("#search_id").val();
var zoom_factor = jQuery("#zoom_factor").val();

console.log(search_string+"  "+zoom_factor);

cvjs_searchTextNext(search_string, zoom_factor);

}




var multipleSelectColor = {
fill: '#fa8072',
"fill-opacity": 0.9,
stroke: '#fa8072',
'stroke-width': 2.0,
'stroke-opacity': 1,
'stroke-linejoin': 'round'
};


function cvjs_ObjectSelected(rmid){

}


var  highlight_red = {
        fill: '#fa8072',
        "fill-opacity": 0.8,
        stroke: '#8B0000',   // #8B0000   #fa8072    // red
        'stroke-width': 2.0, // 
        'stroke-opacity': 1,
        'stroke-linejoin': 'round'
    };


var  highlight_green = {
        fill: '#32CD32',     // 0dff8a
        "fill-opacity": 0.8,
        stroke: '#228B22',       // 0dff8a green
        'stroke-width': 2.0,
        'stroke-opacity': 1,
        'stroke-linejoin': 'round'
    };


var  highlight_blue = {
        fill: '#0c8dff',
        'fill-opacity': 0.8,
        stroke: '#003366',           // midnight blue 003366 ,    #0c8dff
        'stroke-width': 2.0,
        'stroke-opacity': 1.0,
        'stroke-linejoin': 'round'
    };


var  highlight_yellow = {
        fill: '#fafa00',            //  #FFFCBB yellow
        "fill-opacity": 0.8,
        stroke: '#FFD300',    //  orange
        'stroke-width': 2.0,
        'stroke-opacity': 1,
        'stroke-linejoin': 'round'
    };


var  highlight_purple = {
        fill: '#ff00dd',     
        "fill-opacity": 0.8,
        stroke: '#800080', //  purple          #ff00dd
        'stroke-width': 2.0,
        'stroke-opacity': 1,
        'stroke-linejoin': 'round'
    };


var  highlight_bordeau_red_borders = {
        fill: '#fff',
        "fill-opacity": 0.01,
        stroke: '#8B0000',   // #8B0000   #fa8072    // red
        'stroke-width': 6.0, // 
        'stroke-opacity': 1,
        'stroke-linejoin': 'round'
    };


var  highlight_red_borders = {
        fill: '#fff',
        "fill-opacity": 0.01,
        stroke: '#FF0000',   // #8B0000   #fa8072    // red
        'stroke-width': 6.0, // 
        'stroke-opacity': 1,
        'stroke-linejoin': 'round'
    };



var  highlight_green_borders = {
        fill: '#fff',
        "fill-opacity": 0.01,
        stroke: '#228B22',       // 0dff8a green
        'stroke-width': 6.0,
        'stroke-opacity': 1,
        'stroke-linejoin': 'round'
    };

var  highlight_blue_borders = {
        fill: '#fff',
        "fill-opacity": 0.01,
        stroke: '#003366',           // midnight blue 003366 ,    #0c8dff
        'stroke-width': 6.0,
        'stroke-opacity': 1.0,
        'stroke-linejoin': 'round'
    };

var  highlight_yellow_borders = {
        fill: '#fff',
        "fill-opacity": 0.01,
        stroke: '#FFD300',    //  orange
        'stroke-width': 6.0,
        'stroke-opacity': 1.0,
        'stroke-linejoin': 'round'
    };

var  highlight_purple_borders = {
        fill: '#fff',
        "fill-opacity": 0.01,
        stroke: '#800080', //  purple          #ff00dd
        'stroke-width': 6.0,
        'stroke-opacity': 1.0,
        'opacity': 1.0,
        'stroke-linejoin': 'round'
    };




/////////  CANVAS CONTROL METHODS END



// ENABLE ALL API EVENT HANDLES FOR AUTOCAD Handles
function cvjs_mousedown(id, handle, entity){

}

function cvjs_click(id, handle, entity){


console.log("mysql click "+id+"  "+handle);
// if we click on an object, then we add to the handle list
if (handle_selector){
    selected_handles.push({id,handle});
    current_selected_handle = handle;
}

// tell to update the Scroll bar 
//vqUpdateScrollbar(id, handle);
// window.alert("We have clicked an entity: "+entity.substring(4)+"\r\nThe AutoCAD Handle id: "+handle+"\r\nThe svg id is: "+id+"\r\nHighlight SQL pane entry");
}

function cvjs_dblclick(id, handle, entity){

console.log("mysql dblclick "+id+"  "+handle);
window.alert("We have double clicked entity with AutoCAD Handle: "+handle+"\r\nThe svg id is: "+id);
}

function cvjs_mouseout(id, handle, entity){

console.log("mysql mouseout "+id+"  "+handle);

if (current_selected_handle == handle){
    // do nothing
}
else{
    cvjs_mouseout_handleObjectStyles(id, handle);
}
}

function cvjs_mouseover(id, handle, entity){

console.log("mysql mouseover "+id+"  "+handle+"  "+jQuery("#"+id).css("color"))
//cvjs_mouseover_handleObjectPopUp(id, handle);	
}

function cvjs_mouseleave(id, handle, entity){

console.log("mysql mouseleave "+id+"  "+handle+"  "+jQuery("#"+id).css("color"));
}


function cvjs_mouseenter(id, handle, entity){
//	cvjs_mouseenter_handleObjectStyles("#a0a000", 4.0, 1.0, id, handle);
//	cvjs_mouseenter_handleObjectStyles("#ffcccb", 5.0, 0.7, true, id, handle);


cvjs_mouseenter_handleObjectStyles("#F00", 2.0, 1.0, true, id, handle);

}

// END OF MOUSE OPERATION




</script>

</head>
<body bgcolor="white" style="margin:0" >



<style>

#space_icon_table5 {
transform: scale(.8);
/* position: absolute; 
left: 120px; 
top: 40px; */ 
}



#cadviewer_table_01 {
/*position: absolute; 
left: 0px; 
top: 120px; */
}

</style>


<table id="space_icon_table5" border="0">	
<tr>
<td>
<button class="w3-button demo" onclick="customSearchText()">TEXT-Search API Start/Continue</button> 
&nbsp&nbsp&nbsp&nbspSearch String: &nbsp
<input type="text" id="search_id" value="WKSTA-TECH" />
&nbsp&nbsp&nbsp&nbspZoom Factor: &nbsp
<input type="text" style="margin-right:5px" id="zoom_factor" value="200" /> %
<br>
<br>
<button class="w3-button demo" style="margin-left:0px" onclick="highlight_objects()">Highlight Space Objects</button> 
<button class="w3-button demo" onclick="hatch_objects()">Hatch Space Objects</button> 
<button class="w3-button demo" onclick="highlight_border_objects()">Highlight Border Lines</button> 
<button class="w3-button demo" style="margin-left:20px" onclick="tooltip_objects()">Set a custom tooltip</button> 
<button class="w3-button demo" style="margin-left:20px" onclick="cvjs_clearSpaceLayer()">Clear all highlights</button> 
<button class="w3-button demo" style="margin-left:10px" onclick="cvjs_clearAllCustomTooltips()">Clear all tooltips</button> 



</td>
</tr>
</table>


<script>

</script>

<table id="cadviewer_table_01">
<tr>
<td>

<!--This is the CADViewer floorplan div declaration -->

    <div id="floorPlan"  style="border:2px none; width:1800;height:1400;">
    </div>

<!--End of CADViewer declaration -->

</td>
</tr>
</table>

</body>
</html>
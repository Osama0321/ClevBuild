<?php

namespace App\Http\Controllers;
use App\Models\{User,LayerTemplate};
use Auth;
use DataTables;
use Bouncer;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LayerTemplateController extends Controller
{
    public function index()
    {
        return view('admin.layer-templates.index');
    }

    public function getLayersTemplates(Request $request)
    {
        $layers = LayerTemplate::select('template_id','template_name','user_id','template_layers','created_at','created_by','updated_by');
        
        if(Auth::user()->user_type == 6){
            $layers = $layers->where('user_id',Auth::user()->id);
        } else if($request->company_id){
            $layers = $layers->where('user_id',$request->company_id);
        }

        $layers = $layers->get();

        // return response()->json($layers);

        return Datatables::of($layers)
        ->addColumn('created_by', function($row){
            $created_by = $row->getCreatedUser;
            return $created_by ? $created_by->first_name.' '.$created_by->last_name : '--';
        })
        ->addColumn('updated_by', function($row){
            $updated_by = $row->getupdatedUser;
            return $updated_by ? $updated_by->first_name.' '.$updated_by->last_name : '--';
        })
        ->addColumn('action', function($row){
            $actionBtn = '';
            if(Bouncer::can('viewLayerTemplates')){
                $actionBtn .='<a href="' . route('layer-templates.view', ['layerTemplate' => $row->template_id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-eye"></i></a>';
            }
            // if(Bouncer::can('updateLayerTemplates')){
            //     $actionBtn .='<a href="' . route('layer-templates.edit', ['layerTemplate' => $row->template_id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
            // }
            return $actionBtn;
        })
        ->rawColumns(['company','action'])
        ->toJson();

        return response()->json($layers);
        
    }

    public function create(Request $request)
    {
        $companies = [];
        $layersListing = [];

        if(Auth::user()->user_type == 1){
            $companies = User::active()->select('id','first_name','last_name')->where('user_type', 6)->get();
        } else if (Auth::user()->user_type == 6){
            $companies = User::active()->select('id','first_name','last_name')->where('id', Auth::user()->id)->first();
        }
        
        return view('admin.layer-templates.add',compact('layersListing','companies'));

    }

    public function uploadAndProcess(Request $request)
    {
        set_time_limit(0);

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:dwg',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()
            ]);

        } else {
            $file = $request->file('file');
            
            if ($file && $file->isValid()) {
                $file_original_name = strtolower(str_replace(' ', '_', $file->getClientOriginalName()));
                $file_name = explode('.dwg', $file_original_name)[0].'_'.date("dmY_his").'.dwg';
                // $file_name = 'a2nd_floor.dwg';
                $destinationPath = 'cadviewer/content/drawings/dwg';
                $file->move($destinationPath,$file_name); 
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to upload or invalid file.'
                ]);
            }

            if (!file_exists(public_path($destinationPath."/".$file_name))){
                return response()->json([
                    'success' => false,
                    'error' => 'Could not move file to Upload folder.'
                ]);
            } else {

                $this->dwgToJson($file_name);

                $filename_explode = explode(".dwg",$file_name);
                $filename = $filename_explode[0];
                
                // check if json converted successfully
                if (!file_exists(public_path("cadviewer/content/drawings/json/".$filename.".json"))) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Could not find the converted json file in path'.public_path("uploads/".$filename.".json")
                    ]);
                }
                
                // Process Json Data
                $jsonData = file_get_contents(public_path("cadviewer/content/drawings/json/".$filename.".json"));
                $data = json_decode($jsonData, true);
                
                // check if there is any error in json
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json([
                        'success' => false,
                        'error' => "Invalid JSON data: " . json_last_error_msg()
                    ]);
                }
                
                if (!isset($data['Drawing']['BlockRefs'])) {
                    return response()->json([
                        'success' => false,
                        'error' => "BlockRefs data not found in the JSON!"
                    ]);
                }
                
                $blockRefs = $data['Drawing']['BlockRefs'];
                
                // Extracting Layer values
                $layers = array_values(array_map("unserialize", array_unique(array_map("serialize", array_map(function ($blockRef) {
                    return array(
                        'layer_name' => $blockRef['Layer'],
                        'type' => '',
                        'lock' => false,
                        'hide' => false,
                    );
                }, $blockRefs)))));
                

                // $html = view('admin.companies.layer-listing', compact('layers'))->render();

                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded successfully!',
                    'layers' => $layers,
                    // 'html' => $html, // Include the rendered HTML
                    'filename' => $file_name
                ]);
            }
        }         
    }

    /**
	* Generate json from dwg
	**/
	public function dwgToJson($filename){
		$inputFile = public_path("cadviewer/content/drawings/dwg/".$filename);
		$filename_explode = explode(".dwg",$filename);
	    $filename = $filename_explode[0];
		$outputFile = public_path("cadviewer/content/drawings/json/".$filename.".json");
		$licensePath = public_path("linklist");
		$command = public_path("linklist")."/LinkList_2025 -i=\"$inputFile\" -o=\"$outputFile\" -licensepath=\"$licensePath\" -blocks -json";
		exec($command, $output, $returnVar);
		// Check the output and return status
		if ($returnVar === 0) {
			return true;
		} else {
			echo "Command failed with status $returnVar.";
		}
	}

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'template_name' => [
                'required',
                Rule::unique('layer_templates')->where(function ($query) use ($request) {
                    if($request->get('company_id')){
                        return $query->where('user_id', $request->get('company_id'));
                    } else {
                        return $query->where('user_id', Auth::user()->id);
                    }
                }),
                'max:80',
            ],
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()
            ]);

        }

        if($request->company_id){
            $company_id = $request->company_id;
        } else {
            $company_id = Auth::user()->id;
        }

        $layerData = json_decode($request['layer_data']);

        $layers = [];

        if(count($layerData)){
            $key = 0;

            foreach($layerData as $key => $layer){
                $layers[$key]['layer_name'] = $layer->layer_name;
                $layers[$key]['type'] = $layer->type == 'pipe' ? 'pipe' : ($layer->type == 'head' ?  'head' : '');
                $layers[$key]['lock'] = $layer->lock;
                $layers[$key]['hide'] = $layer->hide;
                $key++;
            }
      
            $layerTemplate = array(
                'template_name' => $request->template_name,
                'user_id' => $company_id,
                'template_layers' => json_encode($layers),
                'is_default' => ($request->set_as_default == "true") ? 1 : 0,
                'created_by' => Auth::user()->id,
                'created_at' => NOW()
            );

            if($request->set_as_default == "true"){
                LayerTemplate::where('user_id', $company_id)->update(['is_default' => 0]);
            }

            $addLayerTemplate = LayerTemplate::insert($layerTemplate);

            if($addLayerTemplate){
                return response()->json([
                    'success' => true,
                    'message' => 'Template Layers have been added.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong.'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Template layers cannot be empty!'
            ]);
        }   
    }

    public function view(LayerTemplate $layerTemplate)
    {
        // return response()->json($layerTemplate);

        // $layersListing = json_decode($layerTemplate->template_layers);
        return view('admin.layer-templates.view',compact('layerTemplate'));
    }

    public function edit(LayerTemplate $layerTemplate)
    {
        $layersListing = json_decode($layerTemplate->template_layers);
        return view('admin.layer-templates.edit',compact('layersListing'));
    }

}

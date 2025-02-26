<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '512M');

use Illuminate\Http\Request;
use DB;
use Auth;
use DataTables;
use App\Models\{Projects,User,City,ProjectStatus,Category,Country,Task,Floor,Statuses,TaskLogs};
use App\Http\Requests\FloorRequest;
use Redirect;
use Bouncer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FloorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $floors = Floor::select(
            'floors.floor_id',
            'floors.floor_name',
            'floors.project_id',
            'floors.category_id',
            'floors.floor_status_id',
            'floors.floor_file_name',
            'floors.floor_layer_settings',
            'floors.updated_at',
            'projects.manager_id'
        )
        ->with('status','category','tasks.status')
        ->join('projects','projects.project_id','=','floors.project_id');

        if($request->project_id){
            $floors = $floors->where('floors.project_id',$request->project_id);
        }

        if (Auth::user()->user_type == 6) {
            $managerIds = Auth::user()->managers()->pluck('id');
            $floors = $floors->whereIn('projects.manager_id',$managerIds);
        }

        $floors = $floors->get();

        // return response()->json($floors);

        return view('admin.floors.listing',compact('floors'));
    }

    public function getFloors(Request $request)
    {
        $floors = Floor::with('project','category','country','city','createdBy')->active();
        
        if(isset($request->floor_id) && !empty($request->floor_id)){
            $floors = $floors->where('floors.floor_id', $request->floor_id);
        }        
        $floors = $floors->get();

        return Datatables::of($floors)
        ->addColumn('action', function($row){
            $actionBtn = '<div class="d-flex align-items-start">';
            if(Bouncer::can('updateFloors')){
                $actionBtn .='<a href="' . route('floors.edit', ['floor' => $row->floor_id]) . '" class="mr-1 btn btn-circle btn-sm btn-info" data-toggle="tooltip" title="Edit Task"><i class="fas fa-pencil-alt"></i></a>';
            }
            if(Bouncer::can('deleteFloors')){
                $actionBtn .= '<form action="'.route('floors.delete', ['floor' => $row->floor_id]).'" method="post" class="d-flex gap-3">'.csrf_field().'
                <a class="mr-1 btn-circle btn btn-sm btn-danger remove_floor" data-toggle="tooltip" title="Delete Task"><i class="fas fa-trash-alt"></i></a>';
            }
            if(Bouncer::can('viewFloors')){
                $actionBtn .='<a href="' . route('tasks').'?floor_id='.$row->floor_id. '" class="mr-1 btn btn-circle btn-sm btn-info" target="_blank" data-toggle="tooltip" title="View Tasks"><i class="fas fa-eye"></i></a>';
            }
            if(Bouncer::can('viewFloors')){
                $actionBtn .='<a href="' . route('cadeditor').'?floor_id='.$row->floor_id. '" class="btn btn-circle btn-sm btn-info" target="_blank" data-toggle="tooltip" title="View In Cadviewer"><i class="fas fa-eye"></i></a>';
            }
            $actionBtn .= '</div>';
            return $actionBtn;
        })
        ->rawColumns(['action'])
        ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active',1)->get();
        $members    = User::active()->where('user_type',3);
        $followers  = User::active()->where('user_type',4)->get();
        $statuses = Statuses::active()->get();

        $projects = Projects::active();

        if (Auth::user()->user_type == 6) {
            $managerIds = Auth::user()->managers()->pluck('id');
            $projects = $projects->whereIn('projects.manager_id',$managerIds);

            $members = $members->where('parent_id',Auth::user()->id)->orWhereIn('parent_id', $managerIds);
        }

        $members = $members->get();

        $projects = $projects->get();
        
        // $managers = User::where(['user_type' => 2,'is_active' => 1])->get();
        
        return view('admin.floors.add')
                ->with('projects', $projects)
                ->with('categories', $categories)
                ->with('statuses', $statuses)
                // ->with('managers', $managers)
                ->with('members', $members)
                ->with('followers', $followers);
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
    
    /**
     * Store a newly created resource in storage.
     */
	 
    public function store(FloorRequest $request)
    {
        set_time_limit(0);
        $floortData             = $request->getFloorData();
        $floor                  = new Floor();
        $floor->project_id      = $floortData['project_id'];
        $floor->floor_name      = $floortData['floor_name'];
        $floor->member_id       = $floortData['member_id'];
        $floor->category_id     = $floortData['category_id'];
        $floor->floor_status_id = $floortData['floor_status_id'];
        $floor->address         = $floortData['address'];
        $floor->created_by      = Auth::user()->id;
        $floor->is_active       = 1;

        $file = $request->file('file');

        if ($file && $file->isValid()) {
            $file_original_name = strtolower(str_replace(' ', '_', $file->getClientOriginalName()));
            $file_name = explode('.dwg', $file_original_name)[0].'_'.date("dmY_his").'.dwg';
            // $file_name = $file_original_name;
            $destinationPath = 'cadviewer/content/drawings/dwg';
            $file->move($destinationPath,$file_name); 
        } else {
            return redirect()->route('floors')->with('error', 'Failed to upload or invalid file.');
        }
        
        if (!file_exists(public_path($destinationPath."/".$file_name))){
            return redirect()->route('floors')->with('error', 'Could not move file to Upload folder.');
        } else {

            $this->dwgToJson($file_name);
         
            $filename_explode = explode(".dwg",$file_name);
            $filename = $filename_explode[0];
            
            // check if json converted successfully
            if (!file_exists(public_path("cadviewer/content/drawings/json/".$filename.".json"))) {
                return redirect()->route('floors')->with('error', 'Could not find the converted json file in path'.public_path("uploads/".$filename.".json"));
            }

            // Process Json Data
            $jsonData = file_get_contents(public_path("cadviewer/content/drawings/json/".$filename.".json"));
			if (!json_decode($jsonData)) {
				return response()->json(['error' => 'Invalid JSON format'], 400);
			}
  
            $data = json_decode($jsonData, true);
    
            // check if there is any error in json
            if (json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->route('floors')->with('error', "Invalid JSON data: " . json_last_error_msg());
            }
            
            if (!isset($data['Drawing']['BlockRefs'])) {
                return redirect()->route('floors')->with('error', "BlockRefs data not found in the JSON!");
            }
            
            $floor->floor_file_name = $filename;
            
            if($floor->save()){
                return redirect()->route('layers', ['floor_id' => $floor->floor_id]);
            } else {
                return redirect()->route('floors')->with('error', "Something went wrong.");
            }
        }
    }

    /**
     * Generate Tasks a newly created resource in storage.
     */
	 
    public function generateTasks(Request $request)
    {
        set_time_limit(0);
        $floortData = Floor::select('floor_id','floor_file_name','member_id')->where('floor_id', $request['floor_id'])->first();
        $filename = $floortData?->floor_file_name;

        if(!$filename){
            return response()->json([
                'success' => false,
                'error' => "File doesn't exist"
            ]);
        }
        
        if (!file_exists(public_path("cadviewer/content/drawings/json/".$filename.".json"))) {
            
            return response()->json([
                'success' => false,
                'error' => 'Could not find the converted json file in path'.public_path("uploads/".$filename.".json")
            ]);

        } else {
            
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
            $map_data = array();

            $selectedLayers = array_values(array_unique(array_map(function ($selectedLayer) {
                return isset($selectedLayer['type']) && $selectedLayer['type'] !== '' ? $selectedLayer['layer_name'] : null;
            }, $request['layersData'])));
    
            $selectedLayers = array_filter($selectedLayers);

            // Filter json based on matching layer_name with layers
            $matchingLayers = array_values(array_filter($blockRefs, function ($layer) use ($selectedLayers) {
                return in_array($layer['Layer'], $selectedLayers);
            }));

            foreach($matchingLayers as $data){
                
                $layer_name = $data['Layer'];

                // Use array_filter to find the matching object(s)
                $result = array_filter($request['layersData'], function ($layer) use ($layer_name) {
                    return $layer['layer_name'] === $layer_name;
                });

                // Reset array keys and get the first matching object (if needed)
                $result = array_values($result)[0] ?? null;

                $attributes = array();
                if(isset($data['Attributes'])){
                    foreach($data['Attributes'] as $attribute){
                        $attributes[$attribute['Tag']] = $attribute["String"];
                        $attributes['Type'] = $result['type'] ?? '';
                    }
                }

                $block_data = array(
                    'Name'      	 => isset($data['Handle']) ? $data['Handle'] : '',
                    'LayerName' 	 => isset($data['Layer']) ? $data['Layer'] : '',
                    'ColorName' 	 => isset($data['Color']['Color_Method']) ? $data['Color']['Color_Method'] : '',
                    'LineType'  	 => isset($data['Linetype']) ? $data['Linetype'] : '',
                    'LineWeight' 	 => isset($data['Height']) ? $data['Height'] : '',
                    'ScaleX'         => isset($data['Scale_Factors']["x"]) ? $data['Scale_Factors']["x"] : '',
                    'ScaleY'         => isset($data['Scale_Factors']["y"]) ? $data['Scale_Factors']["y"] : '',
                    'Rotation'       => isset($data['Rotation']) ? $data['Rotation'] : '',
                    'InsertionPoint' => isset($data['Entity_Extents']) ? $data['Entity_Extents'] : '',
                    'Attributes'     => $attributes,
                    'Type'           => isset($attributes['Type']) ? strtolower($attributes['Type']) : '',
                    'LengthInInches' => isset($attributes['Length']) && !empty($attributes['Length']) && $attributes['Length'] !== "" ? $this->convertLengthToInches($attributes['Length']) : 0
                );

                $map_data[] = $block_data;
            }

            if ($map_data) {
                
                $updateLayerSettings = Floor::where('floor_id', $floortData->floor_id)->update(['floor_layer_settings' => $request['layersData']]);
                $jsonDataString = json_encode($map_data);
                
                $tasks = Task::where('floor_id', $floortData->floor_id)->get();
                
                if($tasks){

                    $taskIds = $tasks->pluck('task_id');
                    TaskLogs::whereIn('task_id', $taskIds)->delete();

                    Task::where('floor_id', $floortData->floor_id)->update([
                        'is_active' => 0,
                        'is_delete' => 1,
                        'deleted_at' => NOW(),
                        'updated_at' => NOW(),
                        'updated_by' => Auth::user()->id,
                    ]);
                }

                DB::statement('CALL savetasks(?, ?, ?, ?, ?)', [$jsonDataString,  $floortData->floor_id, $floortData->member_id, Auth::user()->id, $filename.".dwg"]);
                
                return response()->json([
                    'success' => true,
                    'message' => "Task Created Successfully!"
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => "There is something went wrong"
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Projects $projects)
    {
        $members    = User::where(['user_type' => 3,'is_active' => 1])->get();
        $followers  = User::where(['user_type' => 4,'is_active' => 1])->get();
        $project_statuses = ProjectStatus::where('is_active', 1)->get();
        $managers = [];
        if(auth()->user()->user_type == 1){
            $managers = User::where(['user_type' => 2,'is_active' => 1])->get();
        }
        return view('admin.projects.edit', compact('projects','members','followers','project_statuses','managers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectUpdateRequest $request, Projects $projects)
    {
        $ProjectData = $request->getProjectData();
        $projects->update([
            'project_name'     => $ProjectData['project_name'],
            'category_id'      => $ProjectData['category_id'],
            'address'          => $ProjectData['address'],
            'member_id'        => $ProjectData['member_id'],
            'project_status_id'=> $ProjectData['project_status_id'],
            'created_by'       => $ProjectData['created_by'],
            'updated_by'       => Auth::user()->id,
        ]);

        if(count($ProjectData['followers']) > 0 ){
            $projects->followers()->detach();
            $projects->followers()->sync($ProjectData['followers']);
        }else{
            $projects->followers()->sync([]);
        }
        return Redirect::route('projects')->with(['msg' => 'Project Updated Successfully!', 'msg_type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $projects = new Projects();
        $projects->Destory($id);

        // return redirect()->route('projects')->with('error','Project Deleted Successfully!');
        return Redirect::back()->with('error','Task Deleted Successfully!');
    }

    public function getCities($id)
    {
        $data = City::where(['is_active' => 1, 'country_id' => $id])->get();
        return response()->json($data);
    }

    public function completedProjects(Request $request){
        return view('admin.projects.completed');
    }

    public function getCompletedProjects(Request $request)
    {
        $projects = new Projects();
        return $projects->GetCompletedProjects($request->all());
    }

    function convertLengthToInches($lengthString) {
        // Remove spaces
        $lengthString = trim($lengthString);

        // Extract feet and inches using regex
        preg_match('/(\d+)\'-(\d+)/', $lengthString, $matches);
        
        // Check if the regex found feet and inches, otherwise set them to 0
        $feet = isset($matches[1]) ? (int)$matches[1] : 0;
        $inches = isset($matches[2]) ? (int)$matches[2] : 0;
    
        // Convert to total inches and return
        return ($feet * 12) + $inches;
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use DataTables;
use App\Models\{Projects,User,City,ProjectStatus,Category,Country,Task};
use App\Http\Requests\ProjectRequest;
use Redirect;
use Bouncer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $projects = Projects::select(
                        'project_id',
                        'project_name',
                        'address',
                        'category_id',
                        'address',
                        'project_status_id',
                        'updated_at'
                    )
                    ->with('status','category','floors.tasks.status','floors.status');
        if(Auth::user()->user_type == 6){
            $managerIds = Auth::user()->managers()->pluck('id');
            $projects = $projects->whereIn('manager_id',$managerIds);
        }            

        $projects = $projects->get();

        // return response()->json($projects);
        return view('admin.projects.listing',compact('projects'));
    }

    public function getprojects(Request $request)
    {
        $projects = new Projects();
        return $projects->GetProjects($request->all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active',1)->get();
        $members    = User::where(['user_type' => 3,'is_active' => 1])->get();
        $followers  = User::where(['user_type' => 4,'is_active' => 1])->get();
        $project_statuses = ProjectStatus::where('is_active', 1)->get();
        $managers   = [];
        
        // if(Auth::user()->user_type == 1){
        //     $managers = User::where(['user_type' => 2,'is_active' => 1])->get();

        // } else if(Auth::user()->user_type == 6) {
        //     $managers = Auth::user()->managers()->get();
        // }

        if(Auth::user()->user_type == 6) {
            $managers = Auth::user()->managers()->get();
        }

        return view('admin.projects.add')
                ->with('categories', $categories)
                ->with('project_statuses', $project_statuses)
                ->with('managers', $managers)
                ->with('members', $members)
                ->with('followers', $followers);
    }
	
	/**
	* Generate json from dwg
	**/
	public function dwgToJson($file){
		$filename_explode = explode(".",strtolower(str_replace(' ', '_', $file->getClientOriginalName())));
	    $filename = $filename_explode[0];
		$inputFile = public_path("cadviewer/content/drawings/dwg/".strtolower(str_replace(' ', '_', $file->getClientOriginalName())));
		$outputFile = public_path("cadviewer/content/drawings/json/".$filename.".json");
		$licensePath = public_path("linklist");

		echo $command = public_path("linklist")."/LinkList_2025 -i=\"$inputFile\" -o=\"$outputFile\" -licensepath=\"$licensePath\" -blocks -json";

		// Execute the command
		exec($command, $output, $returnVar);

		// Check the output and return status
		if ($returnVar === 0) {
			echo "Command executed successfully.";
		  
		} else {
			echo "Command failed with status $returnVar.";
			die("test");
		}
	}

    /**
     * Store a newly created resource in storage.
     */
	 
    public function store(ProjectRequest $request)
    {
        // dd($request->all());
        set_time_limit(0);
        $ProjectData                 = $request->getProjectData();
        $projects                    = new Projects();
        $projects->project_name      = $ProjectData['project_name'];
        $projects->manager_id        = $ProjectData['manager_id'];
        $projects->category_id       = $ProjectData['category_id'];
        $projects->project_status_id = $ProjectData['project_status_id'];
        $projects->address           = $ProjectData['address'];
        $projects->start_date        = date('Y-m-d', strtotime($ProjectData['start_date']));
        $projects->end_date          = date('Y-m-d', strtotime($ProjectData['end_date']));
        $projects->created_by        = Auth::user()->id;
        $projects->is_active         = 1;
		
		if($projects->save()){
            return redirect()->route('projects')->with('success','Project Created Successfully!');
        } else {
            return redirect()->route('projects')->with('error', 'There is something went wrong');
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
        if(Auth::user()->user_type == 1){
            $managers = User::where(['user_type' => 2,'is_active' => 1])->get();

        } else if(Auth::user()->user_type == 6) {
            $managers = Auth::user()->managers()->get();
        }
        return view('admin.projects.edit', compact('projects','members','followers','project_statuses','managers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, Projects $projects)
    {
        $ProjectData = $request->getProjectData();

        $projectUpdate = $projects->update([
            'project_name'      => $ProjectData['project_name'],
            'manager_id'        => $ProjectData['manager_id'],
            'category_id'       => $ProjectData['category_id'],
            'project_status_id' => $ProjectData['project_status_id'],
            'address'           => $ProjectData['address'],
            'updated_by'        => Auth::user()->id,
        ]);

        if ($projectUpdate) {
            return Redirect::route('projects')->with('success','Project Updated Successfully!');
        } else {
            return Redirect::route('projects')->with('error', 'There is something went wrong');
        }

       
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
}

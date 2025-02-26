<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\{Projects};
use Bouncer;
use Validator;

class ProjectAPIController extends BaseController
{
    public function getAllProjectsOld(Request $request)
    {
        $projects = Projects::select(['project_id','project_name','updated_at','category_id'])
            ->active()
            ->withCount('tasks')
            ->with('category');

        if(isset($request->search) && !empty($request->search) && $request->search != ''){
            $projects = $projects->Where('project_name', 'like', "%" . $request->search . "%");
        }

        $projects = $projects->get();
        
        return $this->sendResponse($projects, '', 200);
    }

    public function getProjectDetailsOld(Request $request){
        
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 422);       
        }

        $projectDetails = Projects::select(['project_id','project_name','updated_at','category_id'])
            ->active()
            ->where('project_id',$request->project_id)
            ->with(['tasks.projectStatus','category','tasks.member'])
            ->withCount('tasks')->first();
        
        return $this->sendResponse($projectDetails, '', 200);
    }
	
	public function getAllProjects(Request $request)
    {
		$memberId = Auth::user()->id;
		$projects = Projects::select(['project_id', 'project_name', 'updated_at', 'category_id'])
			->active()
			->withCount(['floors' => function ($query) use ($memberId) {
				$query->where('member_id', $memberId);
			}])
			->whereHas('floors', function ($query) use ($memberId) {
				$query->where('member_id', $memberId);
			})
			->with('category');

        if(isset($request->search) && !empty($request->search) && $request->search != ''){
            $projects = $projects->Where('project_name', 'like', "%" . $request->search . "%");
        }

        $projects = $projects->get();
        
        return $this->sendResponse($projects, '', 200);
    }

	public function getProjectDetails(Request $request){
        
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 422);       
        }

        $memberId = Auth::user()->id;
        $projectDetails = Projects::select(['project_id','project_name','updated_at','category_id'])
            ->active()
            ->where('project_id',$request->project_id)
            //->with(['floors.projectStatus','category','floors.member'])
			->with(['floors.status','category','floors.member'])
            ->withCount('floors')
            ->whereHas('floors', function ($query) use ($memberId) {
				$query->where('member_id', $memberId);
			})
            ->first();
            // ->first() ?? new \stdClass();
        
        return $this->sendResponse($projectDetails, '', 200);
    }
}

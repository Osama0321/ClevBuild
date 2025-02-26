<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Projects};
use DataTables;
use App\Http\Requests\ManagerRequest;
use Redirect;
use Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Projects::select(
            'project_id',
            'project_name',
            'address',
            'category_id',
            'address',
            'start_date',
            'end_date',
            'project_status_id',
            'updated_at'
        )
        ->limit(1)
        // ->with('status','category','floors.status');
        ->with('status','category','floors.tasks.status','floors.status');
        
        if(Auth::user()->user_type == 6){
            $managerIds = Auth::user()->managers()->pluck('id');
            $projects = $projects->whereIn('manager_id',$managerIds);
        }            

        $projects = $projects->where('project_id',10);
        $projects = $projects->get();

        return response()->json($projects);
        
        return view('admin.dashboard.dashboard',compact('projects'));
    }

   
}

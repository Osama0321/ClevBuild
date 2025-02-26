<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use DataTables;
use App\Models\{Projects,User,City,ProjectStatus,Category,Country,Task,Floor};
use App\Http\Requests\ProjectRequest;
use App\Http\Requests\ProjectUpdateRequest;
use Redirect;
use Bouncer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CadViewerController extends Controller
{
	
    public function indexOld(Request $request)
    {
		$task = new Task();
        $tasksQuery =  $task->generalTaskQuery($request['project_id']);
		$projects = Projects::find($request->project_id);
		//dd(DataTables::of($task_list)->toJson());
		$tasks = $tasksQuery->get();
		//$tasksJson = $tasks->toJson();
		return view('admin.cadviewer.index', compact('tasks','projects'));
    }

	public function index(Request $request)
    {
		$task = new Task();
        $tasksQuery =  $task->generalTaskQuery($request['floor_id']);
		$floors = Floor::where('floor_id',$request->floor_id)->first();
		$tasks = $tasksQuery->get();

		// return response()->json($tasks);
		return view('admin.cadviewer.index', compact('tasks','floors'));
    }
	
	public function indexNew(Request $request)
    {
		$task = new Task();
        $tasksQuery =  $task->generalTaskQuery($request['floor_id']);
		$floors = Floor::where('floor_id',$request->floor_id)->first();
		$tasks = $tasksQuery->get();
		$members = User::select('id','first_name','last_name')->active()->where('user_type',3)->orderBy('created_at','desc')->get();

		// return response()->json($tasks);
		return view('admin.cadviewer.index_new', compact('tasks','floors','members'));
    }

	public function indexApp(Request $request)
    {
		$task = new Task();
        $tasksQuery =  $task->generalTaskQuery($request['floor_id']);
		$floors = Floor::where('floor_id',$request->floor_id)->first();
		$tasks = $tasksQuery->get();
		$members = User::select('id','first_name','last_name')->active()->where('user_type',3)->orderBy('created_at','desc')->get();

		// return response()->json($tasks);
		return view('admin.cadviewer.index_app', compact('tasks','floors','members'));
    }

	public function layer(Request $request)
    {
		$task = new Task();
        $tasksQuery =  $task->generalTaskQuery($request['floor_id']);
		$floors = Floor::where('floor_id',$request->floor_id)->first();
		$tasks = $tasksQuery->get();
		$members = User::select('id','first_name','last_name')->active()->where('user_type',3)->orderBy('created_at','desc')->get();

		// return response()->json($tasks);
		return view('admin.cadviewer.layers', compact('tasks','floors','members'));
    }
}

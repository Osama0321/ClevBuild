<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use DataTables;
use App\Models\{Task,Priority,ProjectStatus,Projects,TaskImages,User};
use Redirect;
use App\Http\Requests\TaskRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = $request->id;
        return view('admin.task.index', compact('id'));
    }

    public function gettask(Request $request)
    {
        $task = new Task();
        return $task->GetTask($request->all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $priorities = Priority::get();
        $project_statuses = ProjectStatus::get();
        $projects = Projects::find($request->project_id);

        // dd($projects->members);

        return view('admin.task.add', compact('priorities','project_statuses','projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $TaskData                = $request->getTaskData();
        $task                    = new Task;
        $task->task_name         = $TaskData['task_name'];
        $task->member_id         = $TaskData['member_id'];
        $task->project_id        = $TaskData['project_id'];
        $task->priority_id       = $TaskData['priority_id'];
        $task->project_status_id = $TaskData['project_status_id'];
        $task->description       = $TaskData['description'];
        $task->created_by        = Auth::user()->id;
        $task->is_active         = 1;
        $task->save();

        if($request->has('task_images')){
            foreach ($request->get('task_images') as $image) {
                $taskImage        = new TaskImages();
                $taskImage->image = str_replace(config('app.url'),"",$image);
                $task->images()->save($taskImage); 
            }
        }
        return Redirect::route('tasks', ['id' => $TaskData['project_id']])->with(['msg' => 'Task Added Successfully!', 'msg_type' => 'success']);
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
    public function edit(Task $task)
    {
        $members    = User::where(['user_type' => 3,'is_active' => 1])->get();
        $followers  = User::where(['user_type' => 4,'is_active' => 1])->get();
        $projects = Projects::find($task->project_id);
        $priorities = Priority::get();
        $project_statuses = ProjectStatus::get();

        return view('admin.task.edit', compact('task','members','followers','projects','priorities','project_statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $task->update([
            'priority_id'            => $request->priority_id,
            'project_status_id'      => $request->project_status_id,
            'updated_by'             => Auth::user()->id,
            'updated_at'             => NOW(),
        ]);

        // return Redirect::back()->with('success','Task Updated Successfully!');
        return Redirect::route('tasks', ['id' => $request->project_id])->with('success','Task Updated Successfully!');
    }

    /**
     * Update all the specified resource in storage.
     */
  
    public function updateAll(Request $request)
    {
        foreach ($request->tasks as $task_details) {
            $task = Task::where('task_name',$task_details['task_name'])->first();
            if ($task) {
                $task->update([
                    'project_status_id' => 3,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => now(),
                ]);
            }
        }
        
        $data = DB::table('tasks')
                ->selectRaw('project_statuses.project_status_name as status,count(*) as count')
                ->join('project_statuses', 'project_statuses.project_status_id', '=', 'tasks.project_status_id')
                ->where('project_id', $request->tasks[0]['project_id'])
                ->groupBy('tasks.project_status_id', 'project_statuses.project_status_name')
                ->get();
				
	    $task = new Task();
        $tasksQuery =  $task->generalTaskQuery($request->tasks[0]['project_id']);
		$tasks = $tasksQuery->get();
	
       
        $response = array(
            "success" => true,
            "message" => "Record Updated Succesfully.",
            'task_count' => $data->sum('count'),
            "data" => $data,
			"task" => $tasks
        );
        
        return response()->json($response);

    }
    
    /**
     * Get Tasks With Status By Project Id
     */
  
    public function getTasksWithStatusByProjectId(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,project_id'
        ]);

    
        if ($validator->fails()) {
            $response = array(
                "success" => false,
                "message"=>"Validation Error.",
                "data" => $validator->errors()
            );
        
        } else {

            $data = DB::table('tasks')
                    ->selectRaw('project_statuses.project_status_name as status,count(*) as count')
                    ->join('project_statuses', 'project_statuses.project_status_id', '=', 'tasks.project_status_id')
                    ->where('project_id', $request->project_id)
                    ->groupBy('tasks.project_status_id', 'project_statuses.project_status_name')
                    ->get();

            $response = array(
                "success" => true,
                'task_count' => $data->sum('count'),
                "data" => $data
            );

        }
        
        return response()->json($response);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $task = new Task();
        $task->Destory($id);

        // return redirect()->route('task')->with('success','Project Created Successfully!');
        return Redirect::back()->with('error','Task Deleted Successfully!');
    }
}

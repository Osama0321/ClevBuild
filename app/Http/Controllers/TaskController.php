<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use DataTables;
use App\Models\{Task,Priority,ProjectStatus,Projects,TaskImages,User,Statuses,Floor,TaskLogs};
use Redirect;
use App\Http\Requests\TaskRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $floor_id = $request->floor_id;
        return view('admin.task.index', compact('floor_id'));
    }

    public function gettask(Request $request)
    {
        $task = new Task();
        return $task->GetTask($request->all());
    }

    public function getTaskByName(Request $request){
        
        $validator = Validator::make($request->all(), [
            'task_name' => 'required',
            'floor_id' => 'required',
            'task_type' => 'required'
        ]);

    
        if ($validator->fails()) {
            $response = array(
                "message"=>"Validation Error.",
                "data" => $validator->errors()
            );
            return response()->json($response);
        }
        
        
        $task = Task::select(
            'task_id',
            'task_name',
            'layer_name',
            'attributes',
            'length_in_inches',
            'task_type',
            'floor_id',
            'task_status_id',
            'member_id',
            'priority_id'
        )
        // ->with('member','task_status','floor','priority','floor.project')
        ->active()
        ->where('task_name',$request->task_name)
        ->where('floor_id',$request->floor_id)
        ->where('task_type',$request->task_type)
        ->first();

        if($task){
            if($task->attributes){
                $attributes = json_decode($task->attributes);
                $task->length = $attributes && isset($attributes->Length) ? $attributes->Length : '';
                $task->elevation = $attributes && isset($attributes->Elevation) ? $attributes->Elevation : '';
                $task->description = $attributes && isset($attributes->Description) ? $attributes->Description : '';
            }

            $task->member_name =  $task->member ? $task->member->first_name.' '.$task->member->last_name : '';
            $task->project_name =  $task->floor->project ? $task->floor->project->project_name : '';
            $task->floor_name =  $task->floor ? $task->floor->floor_name : '';
            $task->task_status_name =  $task->task_status ? $task->task_status->status_name : '';
            $task->priority_name =  $task->priority ? $task->priority->priority_name : '';
            

        } else {
            $task = new \stdClass();
        }
        $statuses = Statuses::select('status_id','status_name')->active()->where('status_type',$request->task_type)->get();
        $members = User::select('id','first_name','last_name')->active()->where('user_type','3')->get();
        $data = array(
            'task' => $task,
            'statuses' => $statuses,
            'members' => $members
        );
        return response()->json($data);
    }
    
    public function getTaskByFilters(Request $request){
        
        $validator = Validator::make($request->all(), [
            // 'task_name' => 'required',
            'floor_id' => 'required',
            // 'task_type' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array(
                "message"=>"Validation Error.",
                "error" => $validator->errors()
            );

            return response()->json($response);
        }
        
        $tasks = Task::select(
            'task_id',
            'task_name',
            'layer_name',
            'attributes',
            'length_in_inches',
            'task_type',
            'floor_id',
            'task_status_id',
            'member_id',
            'priority_id',
            'created_at'
        )
        ->with('member','task_status','floor','priority','floor.project')
        ->active()
        ->where('floor_id',$request->floor_id);

        if($request->task_type){
            $tasks = $tasks->where('task_type',$request->task_type);
        }
        
        $tasks = $tasks->get();

        $statusWithCount = Statuses::select(
                                'status_id',
                                'status_name',
                                'status_type',
                                'color',
                            )
                            ->active()
                            ->where('status_type',$request->task_type)
                            ->orderBy('statuses.status_id','asc')
                            ->get();

        
        
        $completed_length_in_inches = 0;

        $statusWithCount = $statusWithCount->map(function ($status) use(&$completed_length_in_inches,$request){
            $task = Task::select(
                'task_status_id',
                DB::raw('COUNT(task_status_id) as count'),
                DB::raw('SUM(length_in_inches) as length_in_inches'),
    
            )
            ->active()
            ->where('floor_id',$request->floor_id)
            ->where('task_type',$request->task_type)
            ->where('task_status_id',$status->status_id)
            ->groupBy('task_status_id')
            ->first();
            
            $status->task_status_id = $task ? $task->task_status_id : $status->status_id;
            $status->count = $task ? $task->count : '0';
            $status->length_in_inches = $task ? $task->length_in_inches : '0';

            if($status->status_name == "Completed"){
                $completed_length_in_inches =  $task->length_in_inches ?? '0';
            }
            
            return $status;
        });

        $statuses = Statuses::select('status_id','status_name')->active()->where('status_type','pipe')->get();
        $members = User::select('id','first_name','last_name')->active()->where('user_type','3')->get();
        $data = array(
            'tasks' => $tasks,
            'statuses' => $statusWithCount,
            'total_count' => $statusWithCount->sum('count'),
            'total_length_in_inches'=> $statusWithCount->sum('length_in_inches'),
            'completed_length_in_inches'=> $completed_length_in_inches,
            'members' => $members
        );
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $priorities = Priority::get();
        $statuses = Statuses::active()->get();
        $floor = Floor::find($request->floor_id);

        $task_types = array(
            (object) ['task_type_id' => 'pipe', 'task_type_name' => 'pipe'],
            (object) ['task_type_id' => 'head', 'task_type_name' => 'head']
        );

        return view('admin.task.add', compact('priorities','statuses','floor','task_types'));
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
            $task = Task::where('task_id',$task_details['task_id'])->first();
            // dd($task);
            if ($task) {
                $task->update([
                    'task_status_id' => $task_details['task_status_id'],
                    'updated_by' => Auth::user()->id,
                    'updated_at' => now(),
                ]);
            }
        }
        
        $data = DB::table('tasks')
                ->selectRaw('statuses.status_name as status,count(*) as count')
                ->join('statuses', 'statuses.status_id', '=', 'tasks.task_status_id')
                ->where('floor_id', $request->tasks[0]['floor_id'])
                ->groupBy('tasks.task_status_id', 'statuses.status_name')
                ->get();
				
	    $task = new Task();
        $tasksQuery =  $task->generalTaskQuery($request->tasks[0]['floor_id']);
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
     * Update task the specified resource in storage.
     */
  
    public function updateById(Request $request)
    {
        $task = Task::where('task_id',$request->task_id)->first();

        if ($task) {
            
            $data = array(
                'task_status_id' => $request->task_status_id,
                'member_id' => $request->member_id,
                'updated_by' => Auth::user()->id,
                'updated_at' => now()
            );

            $task->update($data);

            $task_log = array(
                'task_id'        => $task->task_id,                
                'member_id'      => $task->member_id, 
                'priority_id'    => $task->priority_id,
                'task_status_id' => $task->task_status_id,
                'created_at'     => NOW(),
                'created_by'     => Auth::user()->id,
            );

            TaskLogs::insert($task_log);
        }
       
        $response = array(
            "success" => true,
            "message" => "Record Updated Succesfully."
        );
        
        return response()->json($response);

    }
    
    /**
     * Get Tasks With Status By Project Id
     */
  
    public function getTasksWithStatusByProjectId(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'floor_id' => 'required|exists:projects,floor_id'
        ]);

    
        if ($validator->fails()) {
            $response = array(
                "success" => false,
                "message"=>"Validation Error.",
                "data" => $validator->errors()
            );
        
        } else {

            $data = DB::table('tasks')
                    ->selectRaw('statuses.status_name as status,count(*) as count')
                    ->join('statuses', 'statuses.status_id', '=', 'tasks.task_status_id')
                    ->where('floor_id', $request->floor_id)
                    ->groupBy('tasks.task_status_id', 'statuses.status_name')
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
    
    /**
     * get Status By Task Type.
     */
    public function getStatusByTaskType(Request $request)
    {
        $statuses = Statuses::select(
                                'status_id',
                                'status_name'
                            )
                            ->where('status_type',$request->task_type)
                            ->active()
                            ->get();
        return response()->json($statuses);
    }

    public function getTaskDetailsByFilters(Request $request){
   
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'floor_id' => 'required',
            'task_type' => 'required',
            // 'date_to' => 'required',
        ]);

        if ($validator->fails()) {
            $response = array(
                "message"=>"Validation Error.",
                "error" => $validator->errors()
            );
            return response()->json($response);
        }

        $tasks = Task::select(
            'task_id',
            'task_name',
            'layer_name',
            'attributes',
            'length_in_inches',
            'task_type',
            'floor_id',
            'task_status_id',
            'member_id',
            'priority_id',
            'created_at'
        )
        ->with([
            'member', 
            // 'task_status', 
            'floor', 
            'priority', 
            'floor.project', 
            'task_logs' => function($query) use($request){
                $query->whereDate('created_at', '<=', $request->date)
                  ->orderBy('task_log_id', 'desc')
                  ->limit(1);
            }
        ])
        ->active()
        ->where('floor_id',$request->floor_id)
        ->where('task_type',$request->task_type)
        ->get();
        
        $tasks->each(function ($task) {
            if ($task->task_logs->isNotEmpty()) {
                $task->task_status_id = $task->task_logs->first()->task_status_id;
            } else {
                if($task->task_type == 'pipe'){
                    $task->task_status_id = 1;
                } else if($task->task_type == 'head'){
                    $task->task_status_id = 5;
                }
            }
            $task->task_status = $task->task_status;
        });

        
        $statusWithCount = Statuses::select(
                                'status_id',
                                'status_name',
                                'status_type',
                                'color',
                            )
                            ->active()
                            ->where('status_type',$request->task_type)
                            ->orderBy('statuses.status_id','asc')
                            ->get();
        $statusWithCount = $statusWithCount->map(function ($status) use(&$completed_length_in_inches, $request, $tasks) {
            
            // Convert the Eloquent collection to an array
            $tasksArray = $tasks->toArray(); 
            // Filter the tasks based on task_status_id
            $tasks = array_filter($tasksArray, function ($task) use ($status) {
                return $task['task_status_id'] === $status['status_id'];
            });

            $status->tasks = array_values($tasks);
            
            $status->task_status_id = $status['status_id'];
            $status->count = $tasks ? count($tasks) : '0';
            $status->length_in_inches = $tasks ? array_sum(array_column($tasks, 'length_in_inches')) : '0';

             if($status['status_name'] == "Completed"){
                $completed_length_in_inches =  $tasks ? array_sum(array_column($tasks, 'length_in_inches')) : '0';
            }
            return $status;
           
        });

        $members = User::select('id','first_name','last_name')->active()->where('user_type','3')->get();
        $data = array(
            'tasks' => $tasks,
            'statuses' => $statusWithCount,
            'total_count' => $statusWithCount->sum('count'),
            'total_length_in_inches'=> $statusWithCount->sum('length_in_inches'),
            'completed_length_in_inches'=> $completed_length_in_inches,
            'members' => $members
        );
        return response()->json($data);
    }


}

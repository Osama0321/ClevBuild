<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\{Task,Priority,ProjectStatus,Projects,TaskImages,User,Statuses,Floor,TaskLogs};
use Bouncer;

class TaskAPIController extends BaseController
{
    public function getAllTask(Request $request)
    {
        $task = new Task();
        $task = $task->GetALLTask($request->all());
        return $this->sendResponse($task, '', 200);
    }
	
    public function getTaskByFilters(Request $request){
        
        
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
	
}

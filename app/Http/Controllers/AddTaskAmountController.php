<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\{Projects,User,City,Invoice,Task};
use Datatables;
use App\Http\Requests\AddTaskAmountRequest;
use Redirect;

class AddTaskAmountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('add_task_amount.index');
    }


    public function getaddtaskamount()
    {
        $model = Task::orderBy('created_at','desc');
        return Datatables::of($model)
        ->addColumn('action', function($row){
            $actionBtn = '';
            if(Bouncer::can('updateInvoices')){
                $actionBtn .='<a href="' . route('addtaskamount.edit', ['accountant' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
            }
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
        $Projects  = Projects::where('is_active', 1)->get();
        $followers  = User::where('user_type', 4)->get();
        return view('add_task_amount.add')->with('Projects', $Projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddTaskAmountRequest $request)
    {
        $AddTaskAmount              = $request->RequestData();

        $taskIds = $AddTaskAmount['task_ids'];
        $taskAmounts = $AddTaskAmount['task_amounts'];

        if ($taskIds && $taskAmounts) {
            foreach ($taskIds as $index => $taskId) {
                $amount = $taskAmounts[$index] ?? null;  

                if (!is_null($amount)) {
                    Task::where('task_id', $taskId)->update(['task_amount' => $amount]);
                }
            }
        }
        return Redirect::route('addtaskamount')->with(['msg' => 'Add Task Amount Added Successfully!', 'msg_type' => 'success']);
    }

     
     
     
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $accounts = User::where('account_id', $id)->delete();
        // if ($accounts) {
        //     return Redirect::back()->with(['msg' => 'Invoice deleted successfully!', 'msg_type' => 'success']);
        // }
        // abort(404);
    }

    public function getTasks($id)
    {
        $data = array();
        $Projects = Projects::find($id);
        $data['followers'] = $Projects->followers;
        $data['tasks']     = $Projects->tasks;
        return response()->json($data);
    }


}

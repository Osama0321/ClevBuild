<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Redirect;
use App\Http\Requests\TaskRequest;
use App\Traits\IsActive;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes, IsActive;
    protected $primaryKey = 'task_id';
    protected $fillable = ['priority_id','task_status_id','updated_by','member_id'];

    public function images()
    {
        return $this->hasMany(TaskImages::class, 'task_id');
    }

    public function generalTaskQueryOld($project_id)
    {
        return DB::table('tasks as a')
        ->join('projects as b', 'a.project_id', '=', 'b.project_id')
        ->join('users as c', 'a.member_id', '=', 'c.id')
        ->join('project_statuses as e', 'a.project_status_id', '=', 'e.project_status_id')
        ->join('priorities as f', 'a.priority_id', '=', 'f.priority_id')
        ->join('users as g', 'a.created_by', '=', 'g.id')
        ->select(
            'a.*',
            'b.project_name',
            DB::raw("CONCAT(c.first_name, ' ', c.last_name) AS member_name"),
            DB::raw("CONCAT(g.first_name, ' ', g.last_name) AS created_by_name"),
            'e.project_status_name',
            'f.priority_name'
        )
        ->where('a.project_id', '=', $project_id)
        ->where('a.is_active', '=', 1);
    }

    public function GetALLTaskOld($request)
    {
        $query = $this->generalTaskQuery($request['project_id']);
        if(isset($request['search'])){
         $query =  $query->where('a.task_name', 'like', "%" . $request['search'] . "%")
                    ->orWhere('b.project_name', 'like', "%" . $request['search'] . "%")
                    ->orWhere('e.project_status_name', 'like', "%" . $request['search'] . "%")
                    ->orWhere('f.priority_name', 'like', "%" . $request['search'] . "%")
                    ->orWhere(DB::raw("CONCAT(c.first_name, ' ', c.last_name)"), 'like', "%" . $request['search'] . "%")
                    ->orWhere(DB::raw("CONCAT(g.first_name, ' ', g.last_name)"), 'like', "%" . $request['search'] . "%");
        }
        return $query->get()->toArray();
    }

    public function generalTaskQuery($floor_id)
    {
        return DB::table('tasks as a')
        ->join('floors as b', 'a.floor_id', '=', 'b.floor_id')
        ->leftjoin('users as c', 'a.member_id', '=', 'c.id')
        ->leftjoin('statuses as e', 'a.task_status_id', '=', 'e.status_id')
        ->join('priorities as f', 'a.priority_id', '=', 'f.priority_id')
        ->join('users as g', 'a.created_by', '=', 'g.id')
        ->select(
            'a.*',
            'b.floor_name',
            DB::raw("CONCAT(c.first_name, ' ', c.last_name) AS member_name"),
            DB::raw("CONCAT(g.first_name, ' ', g.last_name) AS created_by_name"),
            'e.status_name',
            'f.priority_name'
        )
        ->where('a.floor_id', '=', $floor_id)
        ->where('a.is_active', '=', 1);
    }

	public function generalTaskAPIQuery($floor_id)
    {
        return DB::table('tasks as a')
        ->join('floors as b', 'a.floor_id', '=', 'b.floor_id')
        ->leftjoin('users as c', 'a.member_id', '=', 'c.id')
        ->leftjoin('statuses as e', 'a.task_status_id', '=', 'e.status_id')
        ->join('priorities as f', 'a.priority_id', '=', 'f.priority_id')
        ->join('users as g', 'a.created_by', '=', 'g.id')
        ->select(
            'a.*',
            'b.floor_name',
            DB::raw("CONCAT(c.first_name, ' ', c.last_name) AS member_name"),
            DB::raw("CONCAT(g.first_name, ' ', g.last_name) AS created_by_name"),
            'e.status_name',
            'f.priority_name'
        )
        ->where('a.floor_id', '=', $floor_id)
        ->where('a.is_active', '=', 1);
    }

    public function GetALLTask($request)
    {
        $query = $this->generalTaskQuery($request['floor_id']);
        if(isset($request['search'])){
         $query =  $query->where('a.task_name', 'like', "%" . $request['search'] . "%")
                    ->orWhere('b.floor_name', 'like', "%" . $request['search'] . "%")
                    ->orWhere('e.status_name', 'like', "%" . $request['search'] . "%")
                    ->orWhere('f.priority_name', 'like', "%" . $request['search'] . "%")
                    ->orWhere(DB::raw("CONCAT(c.first_name, ' ', c.last_name)"), 'like', "%" . $request['search'] . "%")
                    ->orWhere(DB::raw("CONCAT(g.first_name, ' ', g.last_name)"), 'like', "%" . $request['search'] . "%");
        }
        return $query->get()->toArray();
    }

    public function GetTask($request)
    {
        $query = $this->generalTaskQuery($request['floor_id']);
        return DataTables::of($query)
        ->filter(function ($query) {
                if ( request()->has('search') ) {
                    $search = request('search');
                    $query->where(function ($query) use ($search) {
                        $query->where('a.task_name', 'like', "%" . $search['value'] . "%")
                        ->orWhere('b.floor_name', 'like', "%" . $search['value'] . "%")
                        ->orWhere('e.status_name', 'like', "%" . $search['value'] . "%")
                        ->orWhere('f.priority_name', 'like', "%" . $search['value'] . "%")
                        ->orWhere(DB::raw("CONCAT(c.first_name, ' ', c.last_name)"), 'like', "%" . $search['value'] . "%")
                        ->orWhere(DB::raw("CONCAT(g.first_name, ' ', g.last_name)"), 'like', "%" . $search['value'] . "%");
                    });
                }
            })
            ->addColumn('barcode', function($row){
                $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                $barcode = $generator->getBarcode($row->task_name, $generator::TYPE_CODE_128);
                return '<img src="data:image/png;base64,' . base64_encode($barcode) . '" />';
            })
        ->addColumn('action', function($row){
            $actionBtn = '';
            $actionBtn .='<a href="' . route('tasks.edit', ['task' => $row->task_id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
            $actionBtn .= '<form action="'.route('tasks.delete', ['task' => $row->task_id]).'" method="post">'.csrf_field().'
            <a class="btn-circle btn btn-sm btn-danger remove_task" style="margin-left: 43px; margin-top: -55px;"><i class="fas fa-trash-alt"></i></a>';
            return $actionBtn;
        })
        ->rawColumns(['action','barcode'])
        ->toJson();
    }
    
    public function Destory($id)
    {
        return DB::table('tasks')
            ->where('task_id', $id)
            ->update([
                'is_active' => 0,
                'is_delete' => 1,
                'deleted_at' => NOW(),
                'updated_at' => NOW(),
                'updated_by' => Auth::user()->id,
            ]);
    }

    public function projectStatus(){
        return $this->hasOne(ProjectStatus::class, 'project_status_id', 'project_status_id');
    }
    
    public function member(){
        return $this->hasOne(User::class, 'id', 'member_id');
    }

    public function task_status(){
        return $this->hasOne(Statuses::class, 'status_id', 'task_status_id')->select('status_id','status_name','status_type','color');
    }
    
    public function floor(){
        return $this->hasOne(Floor::class,'floor_id','floor_id');
    }
    
    public function priority(){
        return $this->hasOne(Priority::class,'priority_id','priority_id');
    }

    public function status(){
        return $this->hasOne(Statuses::class,'status_id','task_status_id')->select('status_id','status_name','status_type','color');
    }

    public function task_logs(){
        return $this->hasMany(TaskLogs::class,'task_id','task_id');
    }

    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}

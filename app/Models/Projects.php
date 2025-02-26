<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DB;
use Auth;
use DataTables;
use Redirect;
use Bouncer;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\IsActive;

class Projects extends Model
{
    use HasFactory, SoftDeletes, IsActive;

    protected $primaryKey = 'project_id';

    protected $dateFormat = 'Y-m-d H:i:s';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_name',
        'manager_id',
        'category_id',
        'address',
        'country_id',
        'city_id',
        'member_id',
        'updated_by',
        'created_by',
        'project_status_id',
		'project_file_name'
    ];

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'member_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'project_followers', 'project_id', 'follower_id');
    }

    public function member()
    {
        return $this->hasOne(User::class, 'id', 'member_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id', 'project_id');
    }
    

    public function generalQuery()
    {
        return DB::table('projects as a')
        ->join('countries as b', 'a.country_id', '=', 'b.country_id')
        ->join('cities as c', 'a.city_id', '=', 'c.city_id')
        ->join('categories as d', 'a.category_id', '=', 'd.category_id')
        ->join('users as e', 'a.created_by', '=', 'e.id')
        ->select(
            'a.*',
            'b.country_name',
            'c.city_name',
            'd.category_name',
            DB::raw("CONCAT(e.first_name, ' ', e.last_name) AS created_by_name")
        )
        ->where('a.is_active', '=', 1)
        ->orderBy('a.created_at', 'desc');
    }
    
    public function GetALLProjects($request)
    {
        $query = $this->generalQuery();
        if(isset($request['search'])){
         $query =  $query->where('a.project_name', 'like', "%" . $request['search'] . "%")
            ->orWhere('b.country_name', 'like', "%" . $request['search'] . "%")
            ->orWhere('c.city_name', 'like', "%" . $request['search'] . "%")
            ->orWhere('d.category_name', 'like', "%" . $request['search'] . "%")
            ->orWhere(DB::raw("CONCAT(e.first_name, ' ', e.last_name)"), 'like', "%" . $request['search'] . "%");
        }
        return $query->get()->toArray();
    }

    public function GetProjects($request)
    {
        $query = $this->generalQuery();
        return DataTables::of($query)
        ->filter(function ($query) {
                if ( request()->has('search') ) {
                    $search = request('search');
                    $query->where(function ($query) use ($search) {
                        $query->where('a.project_name', 'like', "%" . $search['value'] . "%")
                        ->orWhere('b.country_name', 'like', "%" . $search['value'] . "%")
                        ->orWhere('c.city_name', 'like', "%" . $search['value'] . "%")
                        ->orWhere('d.category_name', 'like', "%" . $search['value'] . "%")
                        ->orWhere(DB::raw("CONCAT(e.first_name, ' ', e.last_name)"), 'like', "%" . $search['value'] . "%");
                    });
                }
            })
        ->addColumn('action', function($row){
            $actionBtn = '<div class="btn-actions">';
            if(Bouncer::can('updateProjects')){
                $actionBtn .='<a href="' . route('projects.edit', ['projects' => $row->project_id]) . '" class="btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
            }
            if(Bouncer::can('deleteProjects')){
                $actionBtn .= '<form action="'.route('projects.delete', ['project' => $row->project_id]).'" method="post">'.csrf_field().'
                <a class="btn-circle btn btn-sm btn-danger remove_project"><i class="fas fa-trash-alt"></i></a></form>';
            }
            // if(Bouncer::can('viewProjects')){
            //     $actionBtn .='<a href="' . route('cadeditor').'?project_id='.$row->project_id. '" class="btn btn-circle btn-sm btn-info"><i class="fas fa-eye"></i></a>';
            // }
            $actionBtn .= '</div>';
            return $actionBtn;
        })
        ->rawColumns(['action'])
        ->toJson();
    }
    
    public function GetCompletedProjects($request)
    {
        $query = DB::table('projects as a')
        ->join('countries as b', 'a.country_id', '=', 'b.country_id')
        ->join('cities as c', 'a.city_id', '=', 'c.city_id')
        ->join('categories as d', 'a.category_id', '=', 'd.category_id')
        ->join('users as e', 'a.created_by', '=', 'e.id')
        ->select(
            'a.*',
            'b.country_name',
            'c.city_name',
            'd.category_name',
            DB::raw("CONCAT(e.first_name, ' ', e.last_name) AS created_by_name")
        )
        ->where('a.is_active', '=', 1)->where('a.project_status_id', '=', 3);

        return DataTables::of($query)
        ->filter(function ($query) {
                if ( request()->has('search') ) {
                    $search = request('search');
                    $query->where(function ($query) use ($search) {
                        $query->where('a.project_name', 'like', "%" . $search['value'] . "%")
                        ->orWhere('b.country_name', 'like', "%" . $search['value'] . "%")
                        ->orWhere('c.city_name', 'like', "%" . $search['value'] . "%")
                        ->orWhere('d.category_name', 'like', "%" . $search['value'] . "%")
                        ->orWhere(DB::raw("CONCAT(e.first_name, ' ', e.last_name)"), 'like', "%" . $search['value'] . "%");
                    });
                }
            })
        ->addColumn('action', function($row){
            $btn = '<div class="dropdown">
                        <a class="btn btn-sm btn-icon-only dropdown-toggle text-light" role="button" style="color: #ced4da !important;"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a class="dropdown-item" href="'.route('projects.edit', ['projects' => $row->project_id]).'">Edit</a>
                            <form action="'.route('projects.delete', ['project' => $row->project_id]).'" method="post">'.csrf_field().'
                            <button class="dropdown-item delete btn-link remove_project ">Delete</button>
                            </form>
                        </div>
                    </div>';

            return $btn;
           
        })
        ->rawColumns(['action'])
        ->toJson();
    }

    public function Destory($id)
    {
        DB::table('projects')
        ->where('project_id', $id)
        ->update([
            'is_active' => 0,
            'is_delete' => 1,
            'deleted_at' => NOW(),
            'updated_at' => NOW(),
            'updated_by' => Auth::user()->id,
        ]);

        DB::table('floors')
        ->where('project_id', $id)
        ->update([
            'is_active' => 0,
            'is_delete' => 1,
            'deleted_at' => NOW(),
            'updated_at' => NOW(),
            'updated_by' => Auth::user()->id,
        ]);

        $deletedFloorId = DB::table('floors')->where('project_id', $id)->first();

        if($deletedFloorId){
            DB::table('tasks')
            ->where('floor_id', $deletedFloorId->floor_id)
            ->update([
                'is_active' => 0,
                'is_delete' => 1,
                'deleted_at' => NOW(),
                'updated_at' => NOW(),
                'updated_by' => Auth::user()->id,
            ]);
        }
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'category_id', 'category_id');
    }

    public function floors(){
        return $this->hasMany(Floor::class,'project_id','project_id')->select('floor_id','floor_name','project_id','member_id','category_id','floor_status_id','floor_file_name','floor_layer_settings',
		DB::raw('COALESCE(
                ROUND(
                    (SELECT SUM(length_in_inches) FROM tasks 
                     WHERE tasks.floor_id = floors.floor_id 
                     AND task_status_id = 4 
                     AND deleted_at IS NULL) 
                    / NULLIF(
                        (SELECT SUM(length_in_inches) FROM tasks 
                         WHERE tasks.floor_id = floors.floor_id 
                         AND deleted_at IS NULL), 0) 
                    * 100, 0), 
                0) AS task_percent')
		);
    }

    public function status(){
        return $this->hasOne(ProjectStatus::class,'project_status_id','project_status_id');
    }

    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}

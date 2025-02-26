<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Roles;
use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
use Validator;
use Redirect;
use Bouncer;
use Silber\Bouncer\Bouncer as BouncerBouncer;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.roles.index');
    }

    public function getRoles(Request $request)
    {
        $model = Roles::where('id','!=','1');

        if ($request->has('order') && count($request->order) > 0) {
            $columnIndex = $request->order[0]['column'];
            $columnName = $request->columns[$columnIndex]['name'] ?? null;
            $columnDirection = $request->order[0]['dir'] ?? 'asc';
        
            if ($columnName && $columnName !== 'default') {
                $model = $model->orderBy($columnName, $columnDirection);
            } else {
                $model = $model->orderBy('id','asc');
            }
        } else {
            $model = $model->orderBy('id','asc');
        }

        return DataTables::eloquent($model)
        ->addColumn('action', function($row){
            $actionBtn = "";
            if(Bouncer::can('updateRoles') && $row->id != 1){
                $actionBtn .='<a href="' . route('roles.edit', ['id' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
            }
            return $actionBtn;
        })
        ->rawColumns(['action'])
        ->toJson();
    }

    public function edit($id)
    {
        $role = Bouncer::role()->find($id);

        if (!$role) {
            abort(404, "Role not found!");
        }
        
        $this->authorize('updateRolesPermissions', $role);
        $abilitiesarray = $role->getAbilities()->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'abilitiesarray'));
    }

    public function update(Request $request, $id)
    {
        if ($id != 1):
            $role =Bouncer::role()->find($id);
            $role->save();
            
            if(count($role->getAbilities())){
                Bouncer::sync($role)->abilities([]);
                if ($request->has('permission') ) {            
                    foreach($request->permission as $key => $value){
                        Bouncer::allow($role)->to($key);         
                    }
                }
                else{
                    Bouncer::disallowAll($role);
                }
            } else {
                return Redirect::route('roles')->with(['msg' => $role->name." doesn't have any permissions.", 'msg_type' => 'error']);
            }

            return Redirect::route('roles')->with(['msg' => 'Permissions Updated Successfully.', 'msg_type' => 'success']);
        else:
            return Redirect::route('roles')->with(['msg' => 'Unauthorized access.', 'msg_type' => 'error']);
        endif;
    }
}

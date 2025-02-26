<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,City};
use DataTables;
use App\Http\Requests\ManagerRequest;
use App\Http\Requests\ManagerUpdateRequest;
use App\Http\Requests\GetManagerRequest;
use Redirect;
use Illuminate\Support\Str;
use App\Http\Service\SendCreatePasswordlink;
use App\Http\Service\SendUpdatedPassword;
use Bouncer;
use Auth;
use Hash;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.managers.index');
    }

    public function getmanagers(GetManagerRequest $request)
    {
        $model = User::select(
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'parent_id',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by'
                )
                ->active()
                // ->orderByRaw('CASE WHEN updated_at IS NULL THEN 0 ELSE 1 END DESC, updated_at DESC, id DESC')
                ->where('users.user_type', 2);

        if(Auth::user()->user_type == 6){
            $model = $model->where('users.parent_id', Auth::user()->id);
            
        } else if(Auth::user()->user_type == 1 && isset($request->company_id) && !empty($request->company_id)){
            $model = $model->where('users.parent_id', $request->company_id);
        }

        if ($request->has('order') && count($request->order) > 0) {
            $columnIndex = $request->order[0]['column'];
            $columnName = $request->columns[$columnIndex]['name'] ?? null;
            $columnDirection = $request->order[0]['dir'] ?? 'asc';
        
            if ($columnName && $columnName !== 'default') {
                $model = $model->orderBy($columnName, $columnDirection);
            } else {
                $model = $model->orderByRaw('CASE WHEN updated_at IS NULL THEN 0 ELSE 1 END DESC, updated_at DESC, id DESC');
            }
        } else {
            $model = $model->orderByRaw('CASE WHEN updated_at IS NULL THEN 0 ELSE 1 END DESC, updated_at DESC, id DESC');
        }

        return Datatables::of($model)
        ->addColumn('action', function($row){
            $actionBtn = '';
            if(Bouncer::can('updateManagers')){
                $actionBtn .='<a href="' . route('managers.edit', ['manager' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
            }
          
            if(Bouncer::can('deleteManagers')){
                $actionBtn .= '<form action="'.route('managers.delete', ['manager' => $row->id]).'" method="post">'.csrf_field().'
                <a class="btn-circle btn btn-sm btn-danger remove_manager" style="margin-left: 43px; margin-top: -55px;"><i class="fas fa-trash-alt"></i></a>';
            }
            return $actionBtn;
        })
        ->addColumn('created_by', function($row){
            $created_by = $row->getCreatedUser;
            return $created_by ? $created_by->first_name.' '.$created_by->last_name : 'N/A';
        })
        ->addColumn('company', function($row){
            $company = $row->getCompany;
            return $company ? $company->first_name.' '.$company->last_name : 'N/A';
        })
        ->rawColumns(['created_by','company','action'])
        ->toJson();
    }

    public function getManagersByComapnyId(GetManagerRequest $request)
    {
        $managers = User::select('id','first_name','last_name')->where('user_type',2)->active();

        if(isset($request->company_id) && !empty($request->company_id)){
            $managers = $managers->where('users.parent_id', $request->company_id);
        }   

        return $managers = $managers->get();
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = User::select('id','first_name','last_name');

        if(Auth::user()->id == 1){
            $companies = $companies->where('users.user_type', 6);
        } else if(Auth::user()->id == 6){
            $companies = $companies->with('managers')->where('users.id', Auth::user()->id);
        }
        
        $companies = $companies->get();

        return view('admin.managers.add',compact('companies'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ManagerRequest $request)
    {
        $UserData               = $request->getUserData();
        $manager                = new User;
        $manager->first_name    = $UserData['first_name'];
        $manager->last_name     = $UserData['last_name'];
        $manager->email         = $UserData['email'];
        $manager->password      = Str::random(64);
        $manager->address       = $UserData['address'];
        $manager->phone_no      = $UserData['phone_no'];
        $manager->parent_id     = isset($UserData['company']) ? $UserData['company'] : (Auth::user()->isAn('admin') ? NULL : Auth::user()->id);
        $manager->user_type     = 2;
        $manager->is_active     = 1;
        $manager->created_by    = Auth::user()->id;
        $manager->created_at    = NOW();
        $manager->save();
        Bouncer::assign('manager')->to($manager);
        SendCreatePasswordlink::send($manager);
        return Redirect::route('managers')->with(['msg' => 'Manager Added Successfully!', 'msg_type' => 'success']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $manager)
    {
        $companies = User::select('id','first_name','last_name');

        if(Auth::user()->id == 1){
            $companies = $companies->where('users.user_type', 6);
        } else if(Auth::user()->id == 6){
            $companies = $companies->with('managers')->where('users.id', Auth::user()->id);
        }
        
        $companies = $companies->get();

        return view('admin.managers.edit', compact('companies','manager'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ManagerUpdateRequest $request, User $manager)
    {
        $UserData = $request->getUserData();

        $data = [
            'first_name' => $UserData['first_name'],
            'last_name'  => $UserData['last_name'],
            'email'      => $UserData['email'],
            'address'    => $UserData['address'],
            'phone_no'   => $UserData['phone_no'],
            'updated_by' => Auth::user()->id,
            'updated_at' => NOW()
        ];

        if(isset($UserData['company'])){
            $data['parent_id'] = $UserData['company'];
        }
      
        $manager->update($data);

        if(isset($UserData['password'])){
            $manager->update(['password' => Hash::make($UserData['password'])]);
            SendUpdatedPassword::send($UserData);
        }
        return Redirect::route('managers')->with(['msg' => 'Manager Updated Successfully!', 'msg_type' => 'success']);
    }

    public function show(Quote $quote)
    {
        if (!empty($quote)) {
            $quote->update([
                'status' => 'read',
            ]);
            return view('admin.quotes.show', compact('quote'));
        }
        abort(404);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $manager = User::where('id', $id)->delete();
        if ($manager) {
            return Redirect::back()->with(['msg' => 'Manager deleted successfully!', 'msg_type' => 'success']);
        }
        abort(404);
    }

    public function getGetCityByCountry(Request $request){
        $cities = City::select('city_id','city_name')->where('country_id',$request->country_id)->get();
        return $cities;
    }
}
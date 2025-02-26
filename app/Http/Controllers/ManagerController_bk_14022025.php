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
use Bouncer;
use Auth;

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
        $model = User::GetDataForYajra(2,'created_at','desc');

        if(Auth::user()->user_type == 6){
            $model = $model->where('users.parent_id', Auth::user()->id);
            
        } else if(Auth::user()->user_type == 1 && isset($request->company_id) && !empty($request->company_id)){
            $model = $model->where('users.parent_id', $request->company_id);
        }

        return Datatables::of($model)
        ->addColumn('action', function($row){
            $actionBtn = '';
            $actionBtn .='<a href="' . route('managers.edit', ['manager' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
            $actionBtn .= '<form action="'.route('managers.delete', ['manager' => $row->id]).'" method="post">'.csrf_field().'
            <a class="btn-circle btn btn-sm btn-danger remove_manager" style="margin-left: 43px; margin-top: -55px;"><i class="fas fa-trash-alt"></i></a>';
            
            return $actionBtn;
        })
        ->addColumn('country', function($row){
            $country = $row->user_country;
            return $country ? $country->country_name : 'N/A';
        })
        ->addColumn('city', function($row){
            $city = $row->user_city;
            return $city ? $city->city_name : 'N/A';
        })
        ->addColumn('created_by', function($row){
            $created_by = $row->getCreatedUser;
            return $created_by ? $created_by->first_name.' '.$created_by->last_name : 'N/A';
        })
        ->addColumn('company', function($row){
            $company = $row->getCompany;
            return $company ? $company->first_name.' '.$company->last_name : 'N/A';
        })
        ->rawColumns(['country','city','created_by','company','action'])
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
        $cities = [];
        $countryId = old('country');
        if($countryId){
            $cities = City::where(['is_active'=> 1,'country_id' => old('country') ])->get();
        }
        $companies = User::select('id','first_name','last_name')->where('users.user_type', 6)->get();
        return view('admin.managers.add',compact('companies','cities'));
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
        $manager->country       = $UserData['country'];
        $manager->city          = $UserData['city'];
        $manager->parent_id     = $UserData['company'];
        $manager->user_type     = 2;
        $manager->is_active     = 1;
        $manager->created_by     = Auth::user()->id;
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
        $companies = User::select('id','first_name','last_name')->where('users.user_type', 6)->get();
        $cities = City::select('city_id','city_name')->where('country_id',$manager->country)->get();
        return view('admin.managers.edit', compact('companies','cities','manager'));
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
            'first_name'        => $UserData['first_name'],
            'last_name'         => $UserData['last_name'],
            'email'             => $UserData['email'],
            'address'           => $UserData['address'],
            'phone_no'          => $UserData['phone_no'],
            'country'           => $UserData['country'],
            'state'             => $UserData['state'],
            'city'              => $UserData['city'],
            // 'parent_id'         => $UserData['company'],
            'updated_by'        => Auth::user()->id
        ];

        if(isset($UserData['company'])){
            $data['parent_id'] = $UserData['company'];
        }
      
        $manager->update($data);

        if(isset($UserData['password'])){
            $manager->update(['password' => $UserData['password']]);
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
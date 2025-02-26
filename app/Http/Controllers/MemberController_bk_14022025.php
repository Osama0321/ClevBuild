<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,City};
use DataTables;
use App\Http\Requests\MemberRequest;
use App\Http\Requests\MemberUpdateRequest;
use App\Http\Requests\GetMemberRequest;
use Redirect;
use Illuminate\Support\Str;
use App\Http\Service\SendCreatePasswordlink;
use Bouncer;
use Auth;
use StdClass;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $managers   = [];
        
        if(Auth::user()->user_type == 6) {
            $managers = Auth::user()->managers()->get();
        }

        return view('admin.members.index',compact('managers'));
    }

    public function getMembers(GetMemberRequest $request)
    {
        $model = User::where('user_type',3)->orderBy('created_at','desc');

        if(isset($request->manager_id) && !empty($request->manager_id)){
            $model = $model->where('users.parent_id', $request->manager_id);

        } else if(Auth::user()->user_type == 6){
            $manager_ids = User::where('parent_id',Auth::user()->id)->pluck('id');
            $model = $model->where('users.parent_id', Auth::user()->id)->orWhereIn('users.parent_id', $manager_ids);
        } else if(Auth::user()->user_type == 1 && isset($request->company_id) && !empty($request->company_id)){
            //get managers
            $manager_ids = User::where('parent_id',$request->company_id)->pluck('id');
            $model = $model->where('users.parent_id', $request->company_id)->orWhereIn('users.parent_id', $manager_ids);
        }
        
        return Datatables::of($model)
        ->addColumn('action', function($row){
            $actionBtn = '';
            if(Bouncer::can('updateMembers')){
                $actionBtn .='<a href="' . route('members.edit', ['member' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
            }
          
            if(Bouncer::can('deleteMembers')){
                $actionBtn .= '<form action="'.route('members.delete', ['member' => $row->id]).'" method="post">'.csrf_field().'
                <a class="btn-circle btn btn-sm btn-danger remove_member" style="margin-left: 43px; margin-top: -55px;"><i class="fas fa-trash-alt"></i></a>';
            }
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
        ->addColumn('updated_by', function($row){
            $updated_by = $row->getupdatedUser;
            return $updated_by ? $updated_by->first_name.' '.$updated_by->last_name : 'N/A';
        })
        ->addColumn('company', function($row){
            $company_name = '';
            $company = $row->getCompany;
            if($company){
                $company_name = $company->first_name.' '.$company->last_name;
            } else {
               
                $manager = $row->getManager;
                if($manager){
                    if($manager->parent_id){
                        $company_name = $manager->getCompany;
                        if($company_name){
                            $company_name = $company_name->first_name.' '.$company_name->last_name;
                        }
                    }
                }
            }

            return $company_name ?: 'N/A';
        })
        ->addColumn('manager', function($row){
            $manager = $row->getManager;
            return $manager ? $manager->first_name.' '.$manager->last_name : 'N/A';
        })
        ->rawColumns(['country','city','created_by','created_by','company','manager','action'])
        ->toJson();
    }
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = [];
        $managers = [];
        
        $countryId = old('country');
        if($countryId){
            $cities = City::where(['is_active'=> 1,'country_id' => old('country') ])->get();
        }

        $companyId = old('company');
        if($companyId){
            $managers = User::select('id','first_name','last_name')->where('user_type', 2)->where('parent_id', $companyId)->get();
        }

        $companies = User::select('id','first_name','last_name')->where('users.user_type', 6)->get();
        return view('admin.members.add',compact('companies','cities','managers'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MemberRequest $request)
    {
        $UserData              = $request->getUserData();
        $member                = new User;
        $member->first_name    = $UserData['first_name'];
        $member->last_name     = $UserData['last_name'];
        $member->email         = $UserData['email'];
        $member->password      = Str::random(64);
        $member->address       = $UserData['address'];
        $member->phone_no      = $UserData['phone_no'];
        $member->country       = $UserData['country'];
        $member->city          = $UserData['city'];
        $member->parent_id    = isset($UserData['manager']) ? $UserData['manager']  : (isset($UserData['company']) ? $UserData['company'] : NULL);
        $member->user_type     = 3;
        $member->is_active    = 1;
        $member->created_by   = Auth::user()->id;
        $member->save();
        Bouncer::assign('member')->to($member);
        SendCreatePasswordlink::send($member);
        return Redirect::route('members')->with(['msg' => 'Member Added Successfully!', 'msg_type' => 'success']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $member)
    {   
        // $member_details = $member;
        // $member_company =  $member_manager= new StdClass();
        // $company = $member->company;
        
        // if($company){
        //     $member_company = $company->managers;
        // } else {
        //     $manager = $member->manager;
        //     if($manager){
        //         $member_manager = $manager;
        //         if($manager->parent_id){
        //             $company = $manager->company;
        //             if($company){
        //                 $member_company = $company->managers;
        //             }
        //         }
        //     }
        // }
        
        // $member_details->company = $member_company;
        // $member_details->manager = $member_manager;


        // return response()->json($member_details->company);

        // return response()->json($member_details);

        return view('admin.members.edit', compact('member'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MemberUpdateRequest $request, User $member)
    {
        $UserData = $request->getUserData();
        $member->update([
            'first_name'        => $UserData['first_name'],
            'last_name'         => $UserData['last_name'],
            'email'             => $UserData['email'],
            'address'           => $UserData['address'],
            'phone_no'          => $UserData['phone_no'],
            'country'           => $UserData['country'],
            'state'             => $UserData['state'],
            'city'              => $UserData['city'],
            'parent_id'         => $UserData['manager'] ? $UserData['manager'] : ($UserData['company'] ? $UserData['company'] : NULL),
            'updated_by'        => Auth::user()->id
        ]);
        if(isset($UserData['password'])){
            $member->update(['password' => $UserData['password']]);
        }
        return Redirect::route('members')->with(['msg' => 'Member Updated Successfully!', 'msg_type' => 'success']);
    }

    public function show(Quote $quote)
    {
        // if (!empty($quote)) {
        //     $quote->update([
        //         'status' => 'read',
        //     ]);
        //     return view('admin.quotes.show', compact('quote'));
        // }
        // abort(404);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member = User::where('id', $id)->delete();
        if ($member) {
            return Redirect::back()->with(['msg' => 'Member deleted successfully!', 'msg_type' => 'success']);
        }
        abort(404);
    }
}
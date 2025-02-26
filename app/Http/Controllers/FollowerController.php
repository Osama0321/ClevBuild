<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
use App\Http\Requests\FollowerRequest;
use App\Http\Requests\FollowerUpdateRequest;
use App\Http\Requests\GetFollowerRequest;
use Redirect;
use Illuminate\Support\Str;
use App\Http\Service\SendCreatePasswordlink;
use App\Http\Service\SendUpdatedPassword;
use Bouncer;
use Auth;
use Hash;

class FollowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.followers.index');
    }

    public function getFollowersOld()
    {
        $model = User::where('user_type',4)->orderBy('created_at','desc');
        return Datatables::of($model)
        ->addColumn('action', function($row){
            $actionBtn = '';
            if(Bouncer::can('updateFollowers')){
                $actionBtn .='<a href="' . route('followers.edit', ['follower' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
            }

            if(Bouncer::can('deleteFollowers')){
                $actionBtn .= '<form action="'.route('followers.delete', ['follower' => $row->id]).'" method="post">'.csrf_field().'
                <a class="btn-circle btn btn-sm btn-danger remove_follower" style="margin-left: 43px; margin-top: -55px;"><i class="fas fa-trash-alt"></i></a>';
            }

            return $actionBtn;
        })
        ->rawColumns(['action'])
        ->toJson();
    }

    public function getFollowers(GetFollowerRequest $request)
    {
        $model = User::where('user_type',4);

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
                $model = $model->orderBy('updated_at','desc');
            }
        } else {
            $model = $model->orderBy('updated_at','desc');
        }

        return Datatables::of($model)
        ->addColumn('action', function($row){
            $actionBtn = '';
            if(Bouncer::can('updateFollowers')){
                $actionBtn .='<a href="' . route('followers.edit', ['follower' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
            }

            if(Bouncer::can('deleteFollowers')){
                $actionBtn .= '<form action="'.route('followers.delete', ['follower' => $row->id]).'" method="post">'.csrf_field().'
                <a class="btn-circle btn btn-sm btn-danger remove_follower" style="margin-left: 43px; margin-top: -55px;"><i class="fas fa-trash-alt"></i></a>';
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
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.followers.add');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FollowerRequest $request)
    {
        $UserData               = $request->getUserData();
        $follower               = new User;
        $follower->first_name   = $UserData['first_name'];
        $follower->last_name    = $UserData['last_name'];
        $follower->email        = $UserData['email'];
        $follower->password     = Str::random(64);
        $follower->address      = $UserData['address'];
        $follower->phone_no     = $UserData['phone_no'];
        $follower->parent_id    = isset($UserData['company']) ? $UserData['company'] : ( Auth::user()->isAn('admin') ? NULL : (Auth::user()->user_type == 2 ? Auth::user()->parent_id : Auth::user()->id));
        $follower->user_type    = 4;
        $follower->is_active    = 1;
        $follower->created_by   = Auth::user()->id;
        $follower->save();
        Bouncer::assign('follower')->to($follower);
        SendCreatePasswordlink::send($follower);
        return Redirect::route('followers')->with(['msg' => 'Follower Added Successfully!', 'msg_type' => 'success']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $follower)
    {
        return view('admin.followers.edit', compact('follower'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FollowerUpdateRequest $request, User $follower)
    {
        $UserData = $request->getUserData();
        $data = [
            'first_name' => $UserData['first_name'],
            'last_name'  => $UserData['last_name'],
            'email'      => $UserData['email'],
            'address'    => $UserData['address'],
            'phone_no'   => $UserData['phone_no'],
            'updated_by' => Auth::user()->id
        ];

        if(isset($UserData['company'])){
            $data['parent_id'] = $UserData['company'];
        }

        $follower->update($data);

        if(isset($UserData['password'])){
            $follower->update(['password' => Hash::make($UserData['password'])]);
            SendUpdatedPassword::send($UserData);
        }
        
        return Redirect::route('followers')->with(['msg' => 'Follower Updated Successfully!', 'msg_type' => 'success']);
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
        $follower = User::where('id', $id)->delete();
        if ($follower) {
            return Redirect::back()->with(['msg' => 'Follower deleted successfully!', 'msg_type' => 'success']);
        }
        abort(404);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
use App\Http\Requests\ManagerRequest;
use App\Http\Requests\ManagerUpdateRequest;
use Redirect;
use Illuminate\Support\Str;
use App\Http\Service\SendCreatePasswordlink;
use Bouncer;

class FollowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('followers.index');
    }

    public function getFollowers()
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
                <a class="btn-circle btn btn-sm btn-danger remove_manager" style="margin-left: 43px; margin-top: -55px;"><i class="fas fa-trash-alt"></i></a>';
            }

            return $actionBtn;
        })
        ->rawColumns(['action'])
        ->toJson();
    }
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('followers.add');
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
        $follower               = new User;
        $follower->first_name   = $UserData['first_name'];
        $follower->last_name    = $UserData['last_name'];
        $follower->email        = $UserData['email'];
        $follower->password     = Str::random(64);
        $follower->address      = $UserData['address'];
        $follower->phone_no     = $UserData['phone_no'];
        $follower->country      = $UserData['country'];
        $follower->city         = $UserData['city'];
        $follower->user_type    = 4;
        $manager->is_active     = 1;
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
        return view('followers.edit', compact('follower'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ManagerUpdateRequest $request, User $follower)
    {
        $UserData = $request->getUserData();
        $follower->update([
            'first_name'        => $UserData['first_name'],
            'last_name'         => $UserData['last_name'],
            'email'             => $UserData['email'],
            'address'           => $UserData['address'],
            'phone_no'          => $UserData['phone_no'],
            'country'           => $UserData['country'],
            'state'             => $UserData['state'],
            'city'              => $UserData['city'],
        ]);

        if(isset($UserData['password'])){
            $follower->update(['password' => $UserData['password']]);
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

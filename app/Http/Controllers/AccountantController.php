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

class AccountantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('accountants.index');
    }

    public function getAccountants()
    {
        $model = User::where('user_type',5)->orderBy('created_at','desc');
        return Datatables::of($model)
        ->addColumn('action', function($row){
            $actionBtn = '';
            if(Bouncer::can('updateAccountants')){
                $actionBtn .='<a href="' . route('accountants.edit', ['accountant' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
            }
            if(Bouncer::can('deleteAccountants')){
                $actionBtn .= '<form action="'.route('accountants.delete', ['accountant' => $row->id]).'" method="post">'.csrf_field().'
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
        return view('accountants.add');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ManagerRequest $request)
    {
        $UserData                  = $request->getUserData();
        $accountant                = new User;
        $accountant->first_name    = $UserData['first_name'];
        $accountant->last_name     = $UserData['last_name'];
        $accountant->email         = $UserData['email'];
        $accountant->password      = Str::random(64);
        $accountant->address       = $UserData['address'];
        $accountant->phone_no      = $UserData['phone_no'];
        $accountant->country       = $UserData['country'];
        $accountant->city          = $UserData['city'];
        $accountant->user_type     = 5;
        $manager->is_active        = 1;
        $accountant->save();
        Bouncer::assign('accountant')->to($accountant);
        SendCreatePasswordlink::send($accountant);
        return Redirect::route('accountants')->with(['msg' => 'Accountant Added Successfully!', 'msg_type' => 'success']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $accountant)
    {
        return view('accountants.edit', compact('accountant'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ManagerUpdateRequest $request, User $accountant)
    {
        $UserData = $request->getUserData();
        $accountant->update([
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
            $accountant->update(['password' => $UserData['password']]);
        }
        return Redirect::route('accountants')->with(['msg' => 'Accountant Updated Successfully!', 'msg_type' => 'success']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $accountant = User::where('id', $id)->delete();
        if ($accountant) {
            return Redirect::back()->with(['msg' => 'Accountant deleted successfully!', 'msg_type' => 'success']);
        }
        abort(404);
    }
}

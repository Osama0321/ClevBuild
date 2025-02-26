<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\{Projects,User,City,Invoice};
use DataTables;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('invoices.index');
    }


    public function getInvoices()
    {
        $model = Invoice::orderBy('created_at','desc');
        return Datatables::of($model)
        ->addColumn('action', function($row){
            $actionBtn = '';
            if(Bouncer::can('updateInvoices')){
                $actionBtn .='<a href="' . route('invoices.edit', ['accountant' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
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
        return view('invoices.add')->with('Projects', $Projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $UserData              = $request->getUserData();
        // $member                = new User;
        // $member->first_name    = $UserData['first_name'];
        // $member->last_name     = $UserData['last_name'];
        // $member->email         = $UserData['email'];
        // $member->password      = Str::random(64);
        // $member->address       = $UserData['address'];
        // $member->phone_no      = $UserData['phone_no'];
        // $member->country       = $UserData['country'];
        // $member->city          = $UserData['city'];
        // $member->user_type     = 5;
        // $member->save();
        // SendCreatePasswordlink::send($member);
        // return Redirect::route('accountants')->with(['msg' => 'Accountant Added Successfully!', 'msg_type' => 'success']);
    }

     
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       // return view('accountants.edit', compact('accountant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $UserData = $request->getUserData();
        // $accountant->update([
        //     'first_name'        => $UserData['first_name'],
        //     'last_name'         => $UserData['last_name'],
        //     'email'             => $UserData['email'],
        //     'address'           => $UserData['address'],
        //     'phone_no'          => $UserData['phone_no'],
        //     'country'           => $UserData['country'],
        //     'state'             => $UserData['state'],
        //     'city'              => $UserData['city'],
        // ]);

        // if(isset($UserData['password'])){
        //     $accountant->update(['password' => $UserData['password']]);
        // }
        // return Redirect::route('accountants')->with(['msg' => 'Accountant Updated Successfully!', 'msg_type' => 'success']);
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

    public function getFollowers($id)
    {
        $data = array();
        $Projects = Projects::find($id);
        $data['followers'] = $Projects->followers;
        $data['tasks']     = $Projects->tasks;
        return response()->json($data);
    }


}

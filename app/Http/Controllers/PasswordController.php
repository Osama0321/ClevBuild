<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewPasswordRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Redirect;


class PasswordController extends Controller
{
    public $data =  array();   
    public function __construct()
    {   
        $this->data['title'] = "Password";

    }

    public function CreatePassword($token)
    {   
        $updatePassword = DB::table('password_reset_tokens')
                                ->where([ 
                                'token' => $token
                                ])
                                ->first();
    
        if(!$updatePassword){
            abort(404);
        }
        $this->data['email'] = $updatePassword->email;
        return view('auth.createpassword', $this->data);
    }

    public function submitCreatePasswordForm(StoreNewPAsswordRequest $request)
    {
        $user = User::where('email', $request->email)
        ->update(['email_verified_at' => date('Y-m-d H:i:s'), 'is_active' => 1,'password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();

        return Redirect::route('login')->with('success','Password Created Successfully! Please Login');
    }
}

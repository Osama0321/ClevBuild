<?php
namespace App\Http\Service;
use DB; 
use Carbon\Carbon; 
use App\Models\User; 
use Mail; 
use Hash;
use Illuminate\Support\Str;

class SendCreatePasswordlink{
  public static function send($user){
    $token = Str::random(64);

    DB::table('password_reset_tokens')->where(['email'=> $user->email])->delete();
    DB::table('password_reset_tokens')->insert([
      'email' => $user->email, 
      'token' => $token, 
      'created_at' => Carbon::now()
    ]);
    
    try {
      Mail::send('email.create_password', ['token' => $token, 'user' => $user], function($message) use($user){
        $message->to($user->email);
        $message->subject('Create Password');
      });
    } catch (Exception $e) {
      Log::error('Mail sending failed: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Unable to send email at this moment, please try again later.');
    }  

    return true;
  }
}

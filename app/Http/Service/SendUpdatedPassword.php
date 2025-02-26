<?php
namespace App\Http\Service;
use DB; 
use Carbon\Carbon; 
use App\Models\User; 
use Mail; 
use Illuminate\Support\Str;

class SendUpdatedPassword{
  public static function send($user){
    try {
      Mail::send('email.updated_password', ['user' => $user], function($message) use($user){
        $message->to($user['email']);
        $message->subject('Updated Password');
      });
    } catch (Exception $e) {
      Log::error('Mail sending failed: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Unable to send email at this moment, please try again later.');
    }  

    return true;
  }
}

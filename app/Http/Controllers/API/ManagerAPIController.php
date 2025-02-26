<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\{user};
use Bouncer;

class ManagerAPIController extends BaseController
{
    public function getAllUser(Request $request)
    {
        $user = new user();
        $user = $user->getAllUser($request->user_type,'created_at','desc');
        return $this->sendResponse($user, '', 200);
    }
}

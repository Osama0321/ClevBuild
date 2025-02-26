<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\{Floors};
use Bouncer;
use Validator;

class FloorAPIController extends BaseController
{
    public function getAllFloors(Request $request)
    {
        $floors = Floors::select(['floor_id','floor_name','updated_at','category_id'])
            ->active()
            ->withCount('tasks')
            ->with('category');

        if(isset($request->search) && !empty($request->search) && $request->search != ''){
            $floors = $floors->Where('floor_name', 'like', "%" . $request->search . "%");
        }

        $floors = $floors->get();
        
        return $this->sendResponse($floors, '', 200);
    }

    public function getFloorDetails(Request $request){
        
        $validator = Validator::make($request->all(), [
            'floor_id' => 'required',
        ]);
        
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 422);       
        }

        $floorDetails = Projects::select(['floor_id','floor_name','updated_at','category_id'])
            ->active()
            ->where('floor_id',$request->floor_id)
            ->with(['tasks.projectStatus','category','tasks.member'])
            ->withCount('tasks')->first();
        
        return $this->sendResponse($floorDetails, '', 200);
    }
}

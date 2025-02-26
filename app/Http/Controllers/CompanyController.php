<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Requests\GetCompanyRequest;
use App\Models\{User,Companies};
use DB;
use Bouncer;
use DataTables;
use Redirect;
use App\Http\Service\SendCreatePasswordlink;
use App\Http\Service\SendUpdatedPassword;
use Illuminate\Support\Str;
use Auth;
use Validator;
use Hash;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function index()
    {
        return view('admin.companies.index');
    }

    public function getCompanies(GetCompanyRequest $request)
    {
        $users = User::select(
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by'
                )
                ->active()
                // ->orderByRaw('CASE WHEN updated_at IS NULL THEN 0 ELSE 1 END DESC, updated_at DESC, id DESC')
                ->where('users.user_type', 6);

        if(isset($request->company_id) && !empty($request->company_id)){
            $users = $users->where('users.id', $request->company_id);
        }        
        // $users = $users->get();

        if ($request->has('order') && count($request->order) > 0) {
            $columnIndex = $request->order[0]['column'];
            $columnName = $request->columns[$columnIndex]['name'] ?? null;
            $columnDirection = $request->order[0]['dir'] ?? 'asc';
        
            if ($columnName && $columnName !== 'default') {
                $users = $users->orderBy($columnName, $columnDirection);
            } else {
                $users = $users->orderByRaw('CASE WHEN updated_at IS NULL THEN 0 ELSE 1 END DESC, updated_at DESC, id DESC');
            }
        } else {
            $users = $users->orderByRaw('CASE WHEN updated_at IS NULL THEN 0 ELSE 1 END DESC, updated_at DESC, id DESC');
        }
        

        return Datatables::of($users)
        ->addColumn('action', function($row){
            $actionBtn = '';
            if(Bouncer::can('updateCompanies')){
                $actionBtn .='<a href="' . route('companies.edit', ['company' => $row->id]) . '" class="mr-1 btn btn-circle btn-sm btn-info"><i class="fas fa-pencil-alt"></i></a>';
            }
            if(Bouncer::can('deleteCompanies')){
                $actionBtn .= '<form action="'.route('companies.delete', ['company' => $row->id]).'" method="post">'.csrf_field().'
                <a class="btn-circle btn btn-sm btn-danger remove_company" style="margin-left: 43px; margin-top: -55px;"><i class="fas fa-trash-alt"></i></a>';
            }
            return $actionBtn;
        })
        ->addColumn('created_by_name', function($row){
            $created_by = $row->getCreatedUser;
            return $created_by ? $created_by->first_name.' '.$created_by->last_name : '--';
        })
        ->addColumn('updated_by_name', function($row){
            $updated_by = $row->getupdatedUser;
            return $updated_by ? $updated_by->first_name.' '.$updated_by->last_name : '--';
        })
        ->rawColumns(['action'])
        ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.companies.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $UserData             = $request->getUserData();
        $company              = new User;
        $company->first_name  = $UserData['first_name'];
        $company->last_name   = $UserData['last_name'];
        $company->email       = $UserData['email'];
        $company->password    = Str::random(64);
        $company->address     = $UserData['address'];
        $company->phone_no    = $UserData['phone_no'];
        $company->user_type   = 6;
        $company->is_active   = 1;
        $company->created_by  = Auth::user()->id;
        $company->created_at  = NOW();
        $company->save();
        Bouncer::assign('company')->to($company);
        SendCreatePasswordlink::send($company);
        return Redirect::route('companies')->with(['msg' => 'Company Added Successfully!', 'msg_type' => 'success']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $company)
    {
        return view('admin.companies.edit',compact('company'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyUpdateRequest $request, User $company)
    {
        $UserData = $request->getUserData();
        $company->update([
            'first_name' => $UserData['first_name'],
            'last_name'  => $UserData['last_name'],
            'email'      => $UserData['email'],
            'address'    => $UserData['address'],
            'phone_no'   => $UserData['phone_no'],
            'updated_by' => Auth::user()->id,
            'updated_at' => NOW()
        ]);

        if(isset($UserData['password'])){
            $company->update(['password' => Hash::make($UserData['password'])]);
            SendUpdatedPassword::send($UserData);
        }
        return Redirect::route('companies')->with(['msg' => 'Company Updated Successfully!', 'msg_type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = User::where('id', $id)->delete();
        if ($company) {
            return Redirect::back()->with(['msg' => 'Company deleted successfully!', 'msg_type' => 'success']);
        }
        abort(404);
    }

    public function layerSettings(Request $request)
    {
        if($request->company_id){
            $company_id = $request->company_id;
        } else {
            $company_id = Auth::user()->id;
        }
        $layerSettings = User::select('general_settings')->where('id', $company_id)->first();
        $layersListing = json_decode($layerSettings->general_settings)?->layer_settings?->layers;
        return view('admin.companies.layer-settings',compact('layersListing'));
    }

    /**
	* Generate json from dwg
	**/
	public function dwgToJson($filename){
		$inputFile = public_path("cadviewer/content/drawings/dwg/".$filename);
		$filename_explode = explode(".dwg",$filename);
	    $filename = $filename_explode[0];
		$outputFile = public_path("cadviewer/content/drawings/json/".$filename.".json");
		$licensePath = public_path("linklist");
		$command = public_path("linklist")."/LinkList_2025 -i=\"$inputFile\" -o=\"$outputFile\" -licensepath=\"$licensePath\" -blocks -json";
		exec($command, $output, $returnVar);
		// Check the output and return status
		if ($returnVar === 0) {
			return true;
		} else {
			echo "Command failed with status $returnVar.";
		}
	}

    public function uploadFile(Request $request)
    {
        set_time_limit(0);

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:dwg',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()
            ]);

        } else {
            $file = $request->file('file');
            
            if ($file && $file->isValid()) {
                $file_original_name = strtolower(str_replace(' ', '_', $file->getClientOriginalName()));
                $file_name = explode('.dwg', $file_original_name)[0].'('.date("dmY_his").').dwg';
                // $file_name = $file_original_name;
                $destinationPath = 'cadviewer/content/drawings/dwg';
                $file->move($destinationPath,$file_name); 
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to upload or invalid file.'
                ]);
            }

            if (!file_exists(public_path($destinationPath."/".$file_name))){
                return response()->json([
                    'success' => false,
                    'error' => 'Could not move file to Upload folder.'
                ]);
            } else {

                $this->dwgToJson($file_name);

                $filename_explode = explode(".dwg",$file_name);
                $filename = $filename_explode[0];
                
                // check if json converted successfully
                if (!file_exists(public_path("cadviewer/content/drawings/json/".$filename.".json"))) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Could not find the converted json file in path'.public_path("uploads/".$filename.".json")
                    ]);
                }
                
                // Process Json Data
                $jsonData = file_get_contents(public_path("cadviewer/content/drawings/json/".$filename.".json"));
                $data = json_decode($jsonData, true);
                
                // check if there is any error in json
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json([
                        'success' => false,
                        'error' => "Invalid JSON data: " . json_last_error_msg()
                    ]);
                }
                
                if (!isset($data['Drawing']['BlockRefs'])) {
                    return response()->json([
                        'success' => false,
                        'error' => "BlockRefs data not found in the JSON!"
                    ]);
                }
                
                $blockRefs = $data['Drawing']['BlockRefs'];
                
                // Extracting Layer values
                $layers = array_values(array_map("unserialize", array_unique(array_map("serialize", array_map(function ($blockRef) {
                    return array(
                        'layer_name' => $blockRef['Layer'],
                        'type' => '',
                        'lock' => false,
                        'hide' => false,
                    );
                }, $blockRefs)))));
                

                // $html = view('admin.companies.layer-listing', compact('layers'))->render();

                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded successfully!',
                    'layers' => $layers,
                    // 'html' => $html, // Include the rendered HTML
                    'filename' => $file_name
                ]);
            }
        }         
    }

    public function saveLayerSettings(Request $request){

        if($request->company_id){
            $company_id = $request->company_id;
        } else {
            $company_id = Auth::user()->id;
        }

        $layerData = json_decode($request['layerData']);

        $layers = [];

        if(count($layerData)){
            $key = 0;

            foreach($layerData as $key => $layer){
                $layers[$key]['layer_name'] = $layer->layer_name;
                $layers[$key]['type'] = $layer->type == 'pipe' ? 'pipe' : ($layer->type == 'head' ?  'head' : '');
                $layers[$key]['lock'] = $layer->lock;
                $layers[$key]['hide'] = $layer->hide;
                $key++;
            }
        }

        $generalSettings['layer_settings']['layers'] = $layers;

        $updateGeneralSettings = User::where('id', $company_id)->update(['general_settings' => $generalSettings]);

        if($updateGeneralSettings){
            return response()->json([
                'success' => true,
                'message' => 'Layers settings have been updated.'
            ]);
        }
    }
   
}

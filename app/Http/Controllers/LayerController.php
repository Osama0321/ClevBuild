<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Layer,LayersDetails, User, LayerTemplate};
use DB;
use Auth;
use Validator;

class LayerController extends Controller
{
    public function save(Request $request)
    {
        $undo = '';
        // print_r($request->all()); exit;
        if($request->query_type == 'update'){
            $layersDetails = new LayersDetails();
            $layersDetails->created_by  = Auth::user()->id;
            $layersDetails->layer_id    = $request->id;
            if(isset($request->geometry)){
                $layersDetails->geometry    = json_encode($request->geometry);
                $layersDetails->properties  = json_encode($request->properties);
            }else{
                $text_prop = collect(DB::select("SELECT * FROM layers_details WHERE created_at IN (SELECT MAX(created_at) created_at FROM layers_details WHERE layer_id = ".$request->id.")"))->first();
                $layersDetails->text        = $request->text;
                $layersDetails->geometry    = $text_prop->geometry;
                $layersDetails->properties  = $text_prop->properties;
                // $dataToUpdate = [
                //     'text'       => $request->text,
                //     'updated_at' => NOW(),
                //     'updated_by' => Auth::user()->id,
                // ];
            }

            $layersDetails->save();
            
            $layer = Layer::join('layers_details as b', 'layers.id', '=', 'b.layer_id')
            ->select('layers.*', 'b.*')
            ->where('b.is_delete', 0)
            ->where('b.id', $layersDetails->id)
            ->first();
            $status = 200;
        }elseif($request->query_type == 'undo'){
           
            // get the last edited layer
            $undo = $this->max_layer_detail();

            // get the count of last edited layer
            $count = $this->count_layer_detail($undo->layer_id);

            // This condition to check deleted layer and delete the last edited layer. and restore the deleted data
            if($undo->is_delete == 1){
                DB::delete("DELETE FROM layers_details WHERE id = ".$undo->id." and created_by = ".Auth::user()->id." AND DATE(created_at) = DATE(NOW())");

                DB::table('layers_details')
                ->where('layer_id', $undo->layer_id)
                ->where('created_by', Auth::user()->id)
                ->update([
                    'is_delete' => 0,
                    'updated_at' => NOW()
                ]);
            }else{
                DB::delete("DELETE FROM layers_details WHERE id = ".$undo->id." and created_by = ".Auth::user()->id." AND DATE(created_at) = DATE(NOW())");

                $count_dtl = $this->count_layer_detail($undo->layer_id);

                if($count_dtl->cnt == 0){
                    DB::delete("DELETE FROM layers WHERE id = ".$undo->layer_id." and created_by = ".Auth::user()->id." AND DATE(created_at) = DATE(NOW())");
                }
            }

            // This condition will check the last layer count
            if($count->cnt == 1){
                $layer = '';
            }else{
                // This condition will check the deleted layer and send data accordingly
                if($undo->is_delete == 1){
                    $layer = collect(DB::select("SELECT * FROM layers_details WHERE created_at IN (SELECT MAX(created_at) created_at FROM layers_details where layer_id = ".$undo->layer_id.")"))->first();
                }else{
                    if($count_dtl->cnt > 0){
                        $layer = collect(DB::select("SELECT * FROM layers_details WHERE created_at IN (SELECT MAX(created_at) created_at FROM layers_details where layer_id = ".$undo->layer_id.")"))->first();
                    }else{
                        $layer = $this->max_layer_detail();
                    }
                }
            }
            $status = 200;
        }else{
            $layer = new Layer();
            $layer->task_id     = $request->task_id;
            $layer->created_by  = Auth::user()->id;
            $layer->save();

            $layersDetails = new LayersDetails();
            $layersDetails->layer_id    = $layer->id;
            $layersDetails->geometry    = json_encode($request->geometry);
            $layersDetails->properties  = json_encode($request->properties);
            $layersDetails->text        = $request->text;
            $layersDetails->created_by  = Auth::user()->id;
            $layersDetails->save();
            $status = 201;

            $layer = Layer::join('layers_details as b', 'layers.id', '=', 'b.layer_id')
            ->select('layers.*', 'b.*')
            ->where('b.is_delete', 0)
            ->where('b.id', $layersDetails->id)
            ->first();
        }
        return response()->json(['layer' => $layer,'layer_to_remove' => $undo], $status);
    }

    public function index()
    {
        $layers = Layer::join('layers_details as b', 'layers.id', '=', 'b.layer_id')
            ->select('layers.*', 'b.*')
            ->where('b.is_delete', 0) // Condition for b.is_delete = 0
            ->whereIn(DB::raw('(layers.id, b.created_at)'), function($query) {
                $query->select(DB::raw('layer_id, MAX(created_at) created_at'))
                    ->from('layers_details')
                    ->where('is_delete', 0) // Condition for is_delete = 0
                    ->groupBy('layer_id');
            })
            ->get();
        return response()->json($layers);
    }

    public function deleteLayer(Request $request)
    {
        $new_row = collect(DB::select("SELECT * FROM layers_details WHERE created_at IN (SELECT MAX(created_at) created_at FROM layers_details WHERE layer_id = ".$request->id.")"))->first();

        $layersDetails = new LayersDetails();
        $layersDetails->created_by  = Auth::user()->id;
        $layersDetails->layer_id    = $request->id;
        $layersDetails->text        = $new_row->text;
        $layersDetails->geometry    = $new_row->geometry;
        $layersDetails->properties  = $new_row->properties;
        $layersDetails->is_delete   = 1;
        $layersDetails->save();

        DB::table('layers_details')
            ->where('layer_id', $request->id)
            ->update([
                'is_delete' => 1,
                'updated_at' => NOW()
        ]);

        return response()->json(['msg' => 'deleted successfully'], 200);
    }

    public function count_layer_detail($layer_id)
    {
        return collect(DB::select("SELECT COUNT(*) cnt FROM layers_details WHERE layer_id = ".$layer_id.""))->first();
    }

    public function max_layer_detail()
    {
        return collect(DB::select("SELECT * FROM layers_details WHERE created_at IN (SELECT MAX(created_at) created_at FROM layers_details)"))->first();
    }

    public function ShowData(Request $request)
    {
        // print_r($request->all()); exit;
        $layer = collect(DB::select("SELECT a.*, CONCAT(b.first_name, ' ', b.last_name) full_name FROM layers_details a, users b
        WHERE a.created_by = b.id
        AND a.created_at IN (SELECT MAX(created_at) FROM layers_details WHERE layer_id = ".$request->layer_id.")"))->first();

        return response()->json($layer);
    }

    public function getCompanyLayers(Request $request){

        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:users,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()
            ]);

        }
        
        $layersData = LayerTemplate::where('user_id', $request->company_id)->where('is_default',1)->orderBy('template_id','desc')->first();
        $layersData = isset($layersData) && ($layersData) ? json_decode($layersData->template_layers) : new \stdClass();

        $layerTemplates = LayerTemplate::where('user_id', $request->company_id)->get();

        return response()->json(
            array(
                'success' => true,
                'layersData' => $layersData,
                'layerTemplates' => $layerTemplates
            )
        );
    }
}

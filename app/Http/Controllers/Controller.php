<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use \ConvertApi\ConvertApi;
use Spatie\PdfToImage\Pdf;
use Illuminate\Support\Facades\Http;
use Bouncer;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function register()
    {
        if(Auth::check()){
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function login()
    {
        if(Auth::check()){
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function registration(Request $request)
    {
        if(Auth::check()){
            return redirect()->route('dashboard');
        }
        $ins = array(
                    'first_name'    => $request->first_name, 
                    'last_name'     => $request->last_name, 
                    'email'         => $request->email, 
                    'password'      => Hash::make($request->pass));

        DB::table('users')->insert($ins);

        return redirect()->route('dashboard');
    }

    public function logedin(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->pass, 'is_active' => 1])){
            if(!in_array(Auth::user()->user_type,[1,2,6])){
                Auth::logout();
                return back()->with('error',"You don't have access!");
            } else {
                return redirect()->route('dashboard');
            }
        }
            
        return back()->with('error','Invalid email or password!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function Editor()
    {
        return view('image_editor');
    }

    public function TestEditor()
    {
        return view('testeditor');
    }

    public function ConvertDwg()
    {
        return view('convert_dwg');
    }

    public function pdftoimage()
    {
        
    }

    public function ConvertDwgSave(Request $request)
    {
        // $path = $request->file('dwg')->store('uploads');
        // ConvertApi::setApiSecret('AAvOynVCsD8AWzkZ');
        // $result = ConvertApi::convert('jpg', [
        //         'File' => 'D:/laragon/www/cleve-build/public/images/79T7jXY6LsOxuzIMpFU5GPweDVJhE3KfuV3qSgGh.pdf',
        //     ], 'pdf'
        // );
        // $result->saveFiles('D:/laragon/www/cleve-build/public/images');
    }

    public function saveImage(Request $request)
    {
        // // Get image data from the request
        // $imageData = $request->input('image_data');

        // // Convert data URL to image file
        // $image = str_replace('data:image/png;base64,', '', $imageData);
        // $image = str_replace(' ', '+', $image);
        // $imageName = 'enhanced_image.png'; // Or generate a unique filename
        // $imagePath = public_path('images/' . $imageName);
        // file_put_contents($imagePath, base64_decode($image));

        // // Optionally, you can save the image path to the database or perform other actions
        // return response()->json(['message' => 'Image saved successfully']);
    }

    public function upload(Request $request)
    {
        // $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
        // echo    $barcode = $generator->getBarcode('pipe123123', $generator::TYPE_CODE_128);
        // exit();
       
        $file = $request->file('file');
        $fileContent = fopen($file->getPathname(), 'r');
        try {
            // Send the file to the external API using Guzzle
            $response = Http::timeout(60) // Setting timeout to 60 seconds
            ->attach('file', $fileContent, $file->getClientOriginalName())
            ->post('http://localhost:5192/api/Clevebuild/upload');
            
            if ($response->successful()) {
                $res = $response->json();
                // CALL InsertEntityData($res['BlockReferences'], 31, 36);

                foreach($res['BlockReferences'] as $ref_key => $r){
                    $attributes = array();
                    foreach($r['Attributes'] as $key => $value){
                        $attributes[$value['Name']] = $value['Value'];
                    }
                    $res['BlockReferences'][$ref_key]['Attributes'] = $attributes;
                }

                $jsonDataString = json_encode($res['BlockReferences']);
                DB::statement('CALL test(?, ?, ?)', [$jsonDataString, 31, 36]);
                // print_r($res['BlockReferences']); exit;
                // // dd($res['BlockReferences']);
                
                // foreach($res['BlockReferences'] as $ref_key => $r){
                //     $attributes = array();
                //     foreach($r['Attributes'] as $key => $value){
                //         $attributes[$value['Name']] = $value['Value'];
                //     }
                //     $res['BlockReferences'][$ref_key]['Attributes'] = $attributes;
                // }

                //     echo "<pre>";
                //     print_r($res['BlockReferences']);
                //     exit();
               
            } else {
                return back()->with('error', 'Failed to upload file.')->with('response', $response->json());
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to upload file. Error: ' . $e->getMessage());
        }
    }
}

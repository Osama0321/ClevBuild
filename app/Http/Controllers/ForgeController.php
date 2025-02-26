<?php
// app/Http/Controllers/ForgeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ForgeController extends Controller
{
    private $clientId;
    private $clientSecret;
    private $accessToken;

    public function __construct()
    {
        $this->clientId = "rLspvRtRafS0Ai5lQYfjgzfwFtBh4IEZTP5YJ78srf8GVq0g";
        $this->clientSecret = "hGan6pLdlOhcrmO9rRWSxNCOTtEWWqXzNfmiA2Varsq1hMy0VlDT4YHeuUSL8Yqw";
        $this->accessToken = null;
    }

    private function authenticate()
    {
        // echo $this->clientId;
        // echo $this->clientSecret; exit();
        $response = Http::asForm()->post('https://developer.api.autodesk.com/authentication/v1/authenticate', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
            'scope' => 'data:read data:write data:create'
        ]);

        // dd($response->json());

        $this->accessToken = $response->json()['access_token'];
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:dwg',
        ]);

        $file = $request->file('file');
        $filePath = $file->getPathname();
        $fileName = $file->getClientOriginalName();

        if (!$this->accessToken) {
            $this->authenticate();
        }

        // Upload file
        $this->uploadFile($filePath, $fileName);

        return view('viewer', ['fileName' => $fileName]);
    }

    private function uploadFile($filePath, $fileName)
    {

        // Obtain an access token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://developer.api.autodesk.com/authentication/v1/authenticate");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
            'scope' => 'data:read data:write data:create bucket:create bucket:read'
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $accessToken = $data['access_token'];

        // Create a bucket (if not already created)
        $bucketKey = 'clevebuild-05-21-2024';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://developer.api.autodesk.com/oss/v2/buckets");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'bucketKey' => $bucketKey,
            'policyKey' => 'transient'
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $accessToken",
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

     

        // Upload the file
        $fileName = basename($filePath);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://developer.api.autodesk.com/oss/v2/buckets/$bucketKey/objects/$fileName");
        curl_setopt($ch, CURLOPT_PUT, 1);
        curl_setopt($ch, CURLOPT_INFILE, fopen($filePath, 'r'));
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($filePath));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $accessToken",
            'Content-Type: application/octet-stream'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        // dd($response);

     $urn = $this->translateFile($data['objectId']);
        // exit();
        
//         $fileData = file_get_contents($filePath);
//          $response = Http::withToken($this->accessToken)
//             ->attach('file', $fileData, $fileName)
//             ->post('https://developer.api.autodesk.com/oss/v2/objects');



//         if ($response->successful()) {
//     // Handle successful upload
//     // You may want to log or return some information about the uploaded file
//     $uploadedFileInfo = $response->json();
//     // Log or return $uploadedFileInfo
// } else {
//     // Handle failed upload
//     $errorMessage = $response->body();
//     // Log or handle the error message appropriately
//     throw new \Exception('Failed to upload file: ' . $errorMessage);
// }
       

        // if ($response->status() !== 200) {
        //     throw new \Exception('Failed to upload file: ' . $response->body());
        // }
    }

    function translateFile($objectId) {

    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://developer.api.autodesk.com/authentication/v1/authenticate");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
            'scope' => 'data:read data:write data:create bucket:create bucket:read'
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        $accessToken = $data['access_token'];
    
        // Request translation
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://developer.api.autodesk.com/modelderivative/v2/designdata/job");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'input' => [
                'urn' => base64_encode($objectId)
            ],
            'output' => [
                'formats' => [
                    [
                        'type' => 'svf',
                        'views' => ['2d', '3d']
                    ]
                ]
            ]
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $accessToken",
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    
        $data = json_decode($response, true);
        return base64_encode($objectId); 
    }


    public function cadToken(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://developer.api.autodesk.com/authentication/v1/authenticate");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
            'scope' => 'data:read data:write data:create bucket:create bucket:read'
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            echo json_encode(['error' => $error_msg]);
            exit;
        }

        curl_close($ch);

        $response_data = json_decode($response, true);
        if (isset($response_data['access_token'])) {
            echo json_encode([
                'access_token' => $response_data['access_token'],
                'expires_in' => $response_data['expires_in']
            ]);
        } else {
            echo json_encode(['error' => 'Failed to get access token']);
        }
        // echo json_encode([
        //     'access_token' => $data['access_token'],
        //     'expires_in' => $data['expires_in']
        // ]);
    }
}


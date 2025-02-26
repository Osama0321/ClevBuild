<?php
     
namespace App\Http\Controllers\API;
     
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
     
class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function registered(Request $request): JsonResponse
    {
        // Define custom validation rule to check for duplicate email
        Validator::extend('unique_email', function ($attribute, $value, $parameters, $validator) {
            // Check if the email already exists in the database
            return !User::where('email', $value)->exists();
        });

        // Define custom error message for the unique_email rule
        $customMessages = [
            'unique_email' => 'This email has already been taken.',
        ];

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'user_type' => 'required',
            'country' => 'required',
            'city' => 'required',
            'email' => 'required|email|unique_email',
            'password' => 'required',
        ], $customMessages);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), 422);       
        }
     
        $input              = $request->all();
        $input['password']  = Hash::make($input['password']);
        $input['is_active'] = 1;
        $user               = User::create($input);
        $success            = $user;
        return $this->sendResponse($success, 'User registered successfully.');
    }
     
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function loginUser(Request $request): JsonResponse
    {
        // Validate the request parameters
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        // Check if validation fails
        if($validator->fails()){
            // Return error response with status code 422 for missing parameters
            return $this->sendError('Validation Error.', $validator->errors(), 422);       
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            // Get the authenticated user
            $user = Auth::user();
            // Check if the user is active and user type is member member1@test.com
			if(in_array($user->user_type,[3,4])){    
				if ($user->is_active) {
					// Generate token and prepare success response
					$success['token'] = $user->createToken('MyApp')->accessToken;
					$success['name'] = $user->first_name . ' ' . $user->last_name;
					return $this->sendResponse($success, 'User login successfully.', 200);
				} else {
					// If user is not active, return unauthorized error
					return $this->sendError('Unauthorised.', ['error' => 'User is not active.'], 401);
				}
			}
			else {
				return $this->sendError('Unauthorised.', ['error' => 'User is not a member.'], 401);
			}
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Email or Password is incorrect'], 401);
        } 
    }
}
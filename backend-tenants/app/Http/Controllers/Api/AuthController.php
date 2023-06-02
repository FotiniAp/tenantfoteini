<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request){
        try
        {
            $rules = ['username' => 'required', 'password' => 'required'];
            $validator = Validator::make($request->all() , $rules, 
                         $messages = ['username.required' => 'Username is required', 'password.required' => 'password is required']);

            if ($validator->fails())
            {
                return response()->json(['status' => false, 'status_code' => 422, 'message' => $validator->errors() ], 422);
            } 
            
            $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            if (!auth()
                ->attempt(array(
                $fieldType => $request->username,
                'password' => $request->password,
                )))
            {
                return response()->json(['status' => false, 'message' => 'Invalid Credentials'], 400);
            }            

            $accessToken = auth()->user()
                ->createToken('authToken')->accessToken;
            $user = auth()->user();  
            $user_data=$user;                
            $user_data['role_name'] = $user->role()->first()->name; 
           
            return response()->json(['status' => true, 'status_code' => 200, 'data' => $user_data,'access_token'=>$accessToken], 200);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() ], 500);
        }
    }

    public function logout(Request $request)
    {
        try
        {
            $auth_user = Auth::user()->token();
            $auth_user->revoke();
            return response()->json(['status' => true, 'status_code' => 200, 'data' =>''], 200);
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'status_code' => 500, 'message' => $e->getMessage() ], 500);
        }
    }
}

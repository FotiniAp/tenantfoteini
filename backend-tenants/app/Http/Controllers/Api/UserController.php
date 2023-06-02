<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\User;
use App\Models\Tenant;

class UserController extends Controller
{
    public function index()
    {
        try{
            $users = User::with(['tenant','role'])->get();
            return response()->json(['status'=>true,'status_code'=>200,'message'=>'get user data','data'=> $users],200);
        }catch(Exception $e){
            return response()->json(['status'=>false,'status_code'=>500,'message' =>$e->getMessage(),'data'=> []],500);
        }        
    }
    public function getTenants()
    {
        try{
            $tenants = Tenant::all();
            return response()->json(['status'=>true,'status_code'=>200,'message'=>'get tenants','data'=> $tenants],200);
        }catch(Exception $e){
            return response()->json(['status'=>false,'status_code'=>500,'message' =>$e->getMessage(),'data'=> []],500);
        }
    }

    public function getUserDetails(Request $request)
    {
        try{
            $user = [];
            if($request->tenant_id){
                $user = User::where('tenant_id','=', $request->tenant_id)->with(['tenant','role'])->get();
            }else if($request->user_id){
                $user = User::where('id','=',$request->user_id)->with(['tenant','role'])->first();
            }
            return response()->json(['status'=>true,'status_code'=>200,'message'=>'get user','data'=> $user],200);
        }catch(Exception $e){
            return response()->json(['status'=>false,'status_code'=>500,'message' =>$e->getMessage(),'data'=> []],500);
        }
    }

    public function store(Request $request,$id=NULL)
    {
        try{
            $rules = [
                    'username' => [
                        'required',
                        $id?Rule::unique('users')->ignore($id):'unique:users'], 
                    'password' => $id?'':'required|min:6',
                    'tenant_id' => 'required',
                    'role_id'  => 'required',
                ];
            $validator = Validator::make($request->all() , $rules);
            
            if ($validator->fails())
            {
                return response()->json(['status' => false, 'status_code' => 422, 'message' => $validator->errors() ], 422);
            } 
            $msg = '';
            if($id){
                $user = User::find($id);
                $user->username = $request->input('username');
                $user->tenant_id = $request->input('tenant_id');
                $user->role_id = $request->role_id; 
                $user->save();
                $msg = 'User Updated successfully';
            }else{
                $user = User::create([
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'tenant_id' => $request->tenant_id,
                    'role_id' =>$request->role_id,
                ]);
                $msg = 'User created successfully';
            }
            
            if($user){
                return response()->json(['status'=>true,'status_code'=>200,'message'=>$msg,'data'=> $user],200);
            }else {
                return response()->json(['status'=>false,'status_code'=>200,'message'=>'something went wrong','data'=> []],200);
            }
        }catch(Exception $e){
            return response()->json(['status'=>false,'status_code'=>500,'message' =>$e->getMessage(),'data'=> []],500);
        }
    }
 

    public function destroy(Request $request,$id=NULL)
    {
        try{
            $user = User::find($id);
            $user->delete();
            if($user){
                return response()->json(['status'=>true,'status_code'=>200,'message'=>'User deleted successfully!','data'=> []],200);
            }
            return response()->json(['status'=>false,'status_code'=>200,'message'=>'No user found','data'=> []],200);
        }catch(Exception $e){
            return response()->json(['status'=>false,'status_code'=>500,'message' =>$e->getMessage(),'data'=> []],500);
        }
    }
}

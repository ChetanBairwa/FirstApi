<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Students;
use App\Models\users;
use Validator;

class ApiController extends Controller
{
    public function adda(){
        echo "HEllo world";
    }


    public function register(Request $request )
    {   
        $rules=array(
            "name"=>"required",
            "email"=>"required","email",
            "contact"=>"required",
            "password"=>"required"
        );
        if(users::where('email',$request->email)->first())
        {
            return response([
                'message'=>'email already exists',
                'status'=>'failed'
            ]);
        }
        $validator = Validator::make($request->all(),$rules);       
        $users = new users;
        $users->name = $request['name'];
        $users->email = $request['email'];
        $users->contact = $request['contact'];
        $users->password = Hash::make($request->password);
        $users->save();
        $token=$users->createToken($request->email)->plainTextToken;
        return response([
            'token'=>$token,
            'message'=>'Registration Successful',
            'status'=>'Success'
        ]);
    }
     
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);
        $user=users::where('email',$request->email)->first();
        if($user && Hash::Check($request->password,$user->password)){
            $token=$user->createToken($request->email)->plainTextToken;
            return response([
                'token'=> $token,
                'message'=>'Login Successful',
                'status'=>'Success'
            ]);
        }
        return response([
            'message'=>'The provided credentials are incorrect',
            'status'=>'Failed'
        ]);
    }


    public function list($id=null)
    {
        // return apidata::find($id); 
        return $id?apidata::find($id):apidata::all();
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'contact' => 'required',
            'password' => 'required'
        ]);
        $apidata= new Apidata;
        $apidata->name=$request->name;
        $apidata->email=$request->email;
        $apidata->contact=$request->contact;
        $apidata->password=$request->password;
        $result = $apidata->save();
        if($result)
        {
            return ["Result"=> "Data has been saved"];
        }
        else
        {
            return ["Result"=> "Data has not saved"];
        }
    }

    public function update(Request $req)
    {
        $apidata =Apidata::find($req->id);
        $apidata->name=$req->name;
        $apidata->email=$req->email;
        $apidata->contact=$req->contact;
        $apidata->password=$req->password;
        $result=$apidata->save();
        if($result)
        {
            return ["Result" => "Updation is successfull"];
        }
        else
        {
            return ["Result" => "Updation is unsuccessfull"];
        }

    }

    public function delete($id)
    {
        $apidata=Apidata::find($id);
        $result=$apidata->delete();
        if($result)
        {
            return ["Result" => "data is deleted"];
        }
        else
        {
            return ["Result" => "unable to delete the data"];
        }
    }

    public function search ($name)
    {
        $result = apidata::where('name', 'like', '%'.$name.'%')->get();
        if(count($result))
        {
            return $result;
        }
         else
          {
            return array('Result'=> 'No records found');
          }
    }

    public function testdata(Request $request)
    {
        $rules=array(
            "name"=>"required",
            "email"=>"required|email|unique:users",
            "contact"=>"required",
            "password"=>"required|min:6"
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return response()->json($validator->errors(),401);
        }
        else
        {
            $apidata= new Apidata;
            $apidata->name=$request->name;
            $apidata->email=$request->email;
            $apidata->contact=$request->contact;
            $apidata->password=$request->password;
            $result = $apidata->save();
            if($result)
            {
                return ["Result"=> "Data has been saved"];
            }
            else
            {
                return ["Result"=> "operation deny"];
            }
        }       
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'message'=>'logout successfull',
            'status'=>'success'
        ]);
    }

    public function loggeduser(){
        $loggeduser=auth()->user();
        return response([
            'user'=>'loggeduser',
            'message'=>'Logged user data',
            'status'=>'success'
        ]);
    }

    public function change_password(Request $request){
        $request->$validate([
            'passowrd'=>'required|confirmed'
        ]);
        $loggeduser->password=Hash::make($request->password);
        $loggeduser->save();
        return response([
            'message'=>'Password changed successfully',
            'status'=>'success'
        ]);

    }
    
}
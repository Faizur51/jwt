<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;
class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }


    public function index()
    {
        $user=User::all();
        return response()->json([
            'success'=>true,
            'message'=>'Data retrived successfully',
            'data'=>$user
        ]);
    }

    public function store(Request $request)
    {
       $validator=Validator::make($request->all(),[
               'name'=>'required',
               'email'=>'required|email|unique:users',
               'password'=>'required'
           ]);

       if($validator->fails()){
           return response()->json([
               'success'=>false,
               'errors'=>$validator->errors()
           ],401);
       }

  try{
      $user=User::create([
          'name'=>$request->name,
          'email'=>$request->email,
          'password'=>bcrypt($request->password),
      ]);

      return response()->json([
          'success'=>true,
          'message'=>'user data add success',
          'data'=>$user
      ],200);
  }catch(Exception $e){
      return response()->json([
          'success'=>false,
          'message'=>'something went worng'
      ],400);
  }


    }

    public function show($id)
    {
        try{
            $user=User::findOrFail($id);
            return response()->json([
                'success'=>true,
                'message'=>'data show success',
                'data'=>$user
            ]);
        }catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'something went worng'
            ]);
        }


    }


    public function update(Request $request, $id)
    {
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'errors'=>$validator->errors()
            ],401);
        }

        try{

           $user=User::findOrFail($id);
                $user->name=$request->name;
                $user->email=$request->email;
                $user->password=bcrypt($request->password);
                 $user->save();

            return response()->json([
                'success'=>true,
                'message'=>'user data update success',
                'data'=>$user
            ],200);
        }catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'something update went worng'
            ],400);
        }

    }


    public function destroy($id)
    {
        try{
            User::findOrFail($id)->delete();
            return response()->json([
                'success'=>true,
                'message'=>'data delete success'
            ]);
        }catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'something went worng'
            ]);
        }

    }

    public function login(Request $request){
        $credentials = $request->only('email', 'password');

        if ($token = auth()->attempt($credentials)) {
            return $this->respondWithToken(auth()->user(),$token);
        }

        return response()->json(['error' => 'Unauthorized bhi tomi'], 401);

    }

    protected function respondWithToken($user,$token)
    {
        return response()->json([
            'name'=>$user->name,
            'email'=>$user->email,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    public function logout(){

        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function guard()
    {
        return Auth::guard();
    }



}

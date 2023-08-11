<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|unique:users,email|email',
            'password'  => 'required|min:6',
            'name'      => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $user = User::create(array_merge($request->all(), [
            'role_id' => 2,
            'password' => Hash::make($request->input('password'))
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Kullanıcı başarılı bir şekilde oluşturuldu.'
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required|min:6'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $user = User::where('email', $request->input('email'))->first();

        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'E-posta adresi veya şifreniz yanlış.'
            ]);
        }

        if(!Hash::check($request->input('password'), $user->password)){
            return response()->json([
                'status' => false,
                'message' => 'E-posta adresi veya şifreniz yanlış.<<'
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('login-'.$user->id);

        return response()->json([
            'status' => true,
            'message' => 'Kullanıcı başarılı bir şekilde giriş yaptı.',
            'data'  => [
                'token' => $token->plainTextToken
            ]
        ]);

    }

    public function logout(Request $request)
    {
        auth('sanctum')->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Güle güle',
        ]); 
    }
}

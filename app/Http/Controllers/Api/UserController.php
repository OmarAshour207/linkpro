<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'sometimes|nullable',
            'email'     => 'sometimes|nullable|unique:users',
            'password'  => 'required|string|confirmed',
            'phonenumber'   => 'required|unique:users',
            'role'          => 'required|string|in:company,user,supervisor',
            'address'       => 'sometimes|nullable|string',
            'lat'           => 'sometimes|nullable',
            'lng'           => 'sometimes|nullable'
        ]);

        if($validator->fails())
            return response()->json(['success' => false, 'errors' => $validator->errors()]);

        $data = $validator->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $token = $user->createToken('LinkPro')->plainTextToken;

        return response()->json(['success' => true, 'token' => $token]);
    }

    public function login(Request $request)
    {
        $credentials = [
            'password' => $request->get('password')
        ];

        if($request->get('phonenumber'))
            $credentials['phonenumber'] = $request->get('phonenumber');
        elseif ($request->get('email'))
            $credentials['email'] = $request->get('email');
        else
            return response()->json(['success' => false, 'errors' => "s_authError"]);

        if(Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('LinkPro')->plainTextToken;

            return response()->json(['success' => true, 'token' => $token]);
        }
        return response()->json(['success' => false, 'error' => 's_authError']);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SampleUserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ContractController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'phonenumber'   => 'required|unique:users'
        ]);

        if($validator->fails())
            return $this->sendError(__('Validation Error.'), $validator->errors()->getMessages(), 400);

        $data = $validator->validated();
        $data['role'] = 'contract';
        $data['password'] = Hash::make('123456');

        $user = User::create($data);

        return $this->sendResponse(new SampleUserResource($user), __('Contract Registered Successfully.'));
    }
}

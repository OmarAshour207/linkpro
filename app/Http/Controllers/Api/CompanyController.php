<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\SampleUserResource;
use App\Http\Resources\SupervisorResource;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends BaseController
{
    public function getCompany()
    {
        if(auth()->user()->role == 'company') {
            $company = User::with('floors.paths.offices.contents', 'supervisor', 'supplies')
                ->whereRole('company')
                ->whereId(auth()->user()->id)
                ->first();

            if($company)
                return $this->sendResponse(new CompanyResource($company), __('Company'));
        } elseif (auth()->user()->role == 'supervisor') {
            $companies = User::with('floors.paths.offices.contents', 'supervisor', 'supplies')
                        ->where('supervisor_id', auth()->user()->id)
                        ->get();
            $result = CompanyResource::collection($companies);

            return $this->sendResponse($result, __('Companies'));
        } elseif (auth()->user()->role == 'admin') {
            $companies = User::with('floors.paths.offices.contents', 'supervisor', 'supplies')
                ->paginate(20);

            $result = CompanyResource::collection($companies);

            return $this->sendResponse($result, __('Companies'));
        }

        return $this->sendError(__('Auth Error!'), ['s_authError'], 401);
    }

    public function getUser()
    {
        if (auth()->user()->role == 'admin') {
            $admin = User::whereRole('admin')
                ->whereId(auth()->user()->id)
                ->first();
            if($admin)
                return $this->sendResponse(new SampleUserResource($admin), __('Admin'));
        } elseif (auth()->user()->role == 'supervisor') {
            $supervisor = User::with('company')
                ->whereRole('supervisor')
                ->whereId(auth()->user()->id)
                ->first();

            if($supervisor)
                return $this->sendResponse(new SupervisorResource($supervisor), __('Supervisor'));

        } elseif (auth()->user()->role == 'user') {
            $user = User::whereRole('user')
                ->whereId(auth()->user()->id)
                ->first();

            if($user)
                return $this->sendResponse(new SampleUserResource($user), __('User'));
        }

        return $this->sendError(__('Auth Error!'), ['s_authError'], 401);
    }

}

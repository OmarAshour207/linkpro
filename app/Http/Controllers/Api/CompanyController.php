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
        if(auth()->user()->role != 'company')
            return $this->sendError(__('Auth Error!'), ['s_authError'], 401);

        $company = User::with('floors.paths.offices.contents', 'supervisor', 'supplies')
            ->whereRole('company')
            ->whereId(auth()->user()->id)
            ->first();

        if($company)
            return $this->sendResponse(new CompanyResource($company), __('Company'));
    }

    public function getUser()
    {
        if (auth()->user()->role == 'supervisor') {
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

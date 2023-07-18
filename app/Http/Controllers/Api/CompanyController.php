<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends BaseController
{
    public function get()
    {
        if(auth()->user()->role != 'company')
            return $this->sendError(__('Auth Error!'), ['s_authError'], 401);

        $company = User::with('floors.paths.offices.contents', 'supervisor')
            ->whereRole('company')
            ->whereId(auth()->user()->id)
            ->first();

        if(!$company)
            return $this->sendError(__('Auth Error!'), ['s_authError'], 401);

        return $this->sendResponse(new CompanyResource($company), __('Company'));
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function get($id)
    {
        $company = User::with('floors.paths.offices.contents', 'supervisor')
            ->whereRole('company')
            ->whereId($id)
            ->get();

        if(count($company) > 0)
            if (auth()->user()->id != $company->first()->id)
                return response()->json(['errors'  => ['s_authError'], 'success' => false]);

        return response()->json(['company'  => CompanyResource::collection($company), 'success' => true]);
    }
}

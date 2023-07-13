<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends BaseController
{
    public function index()
    {
        $services = Service::all();

        return $this->sendResponse(ServiceResource::collection($services), __('Services'));
//        return response()->json(['services' => ServiceResource::collection($services), 'success' => true]);
    }
}

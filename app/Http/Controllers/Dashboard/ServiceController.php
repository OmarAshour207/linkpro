<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::paginate(20);
        return view('dashboard.services.index', compact('services'));
    }

    public function create()
    {
        return view('dashboard.services.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name:ar'       => 'required|string|max:255',
            'name:en'       => 'required|string|max:255',
            'description:ar' => 'required|string',
            'description:en' => 'required|string',
        ]);

        Service::create($data);
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('services.index');
    }

    public function edit(Service $service)
    {
        return view('dashboard.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name:ar'       => 'required|string|max:255',
            'name:en'       => 'required|string|max:255',
            'description:ar' => 'required|string',
            'description:en' => 'required|string',
        ]);

        $service->update($data);
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('services.index');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        session()->flash('success', __('Deleted successfully'));
        return redirect()->route('services.index');
    }
}

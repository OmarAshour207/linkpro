<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Office;
use App\Models\Path;
use App\Models\User;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index()
    {
        $offices = Office::whenSearch(\request()->search)
            ->with('company', 'floor', 'path')
            ->orderBy('id', 'desc')
            ->paginate(20);
        return view('dashboard.offices.index', compact('offices'));
    }

    public function create()
    {
        $companies = User::companies()->get();
        return view('dashboard.offices.create', compact('companies'));
    }

    public function showPaths(Request $request)
    {
        if ($request->ajax()) {
            $paths = Path::where('floor_id', $request->get('floor_id'))->get();
            $pathId = $request->get('path_id');
            return view('dashboard.offices.ajax.paths', compact('paths', 'pathId'));
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'     => 'required',
            'user_id'   => 'required|numeric',
            'floor_id'  => 'required|array',
            'path_id'   => 'required|array',
            'office_id' => 'sometimes|nullable|array'
        ]);

        $titles = $data['title'];

        foreach ($titles as $index => $title) {
            if (empty($title) || $data['floor_id'][$index] == 0 || $data['path_id'][$index] == 0)
                continue;
            Office::updateOrCreate(['id' => $data['office_id'][$index]], [
                'title'     => $data['title'][$index],
                'user_id'   => $data['user_id'],
                'floor_id'  => $data['floor_id'][$index],
                'path_id'   => $data['path_id'][$index]
            ]);
        }

        session()->flash('success', __('Saved successfully'));
        return redirect("/dashboard/companies/{$data['user_id']}/edit?tab=offices");
//        return redirect()->route('offices.index');
    }

    public function show($id)
    {
        //
    }

    public function edit(Office $office)
    {
        $companies = User::companies()->get();
        $floors = Floor::where('id', $office->floor_id)->get();
        $paths = Path::where('id', $office->path_id)->get();
        return view('dashboard.offices.edit', compact('office', 'companies', 'floors', 'paths'));
    }

    public function update(Request $request, Office $office)
    {
        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'user_id'   => 'required|numeric',
            'floor_id'  => 'required|numeric'
        ]);

        $office->update($data);
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('offices.index');
    }

    public function destroy(Office $office)
    {
        $office->delete();
        session()->flash('success', __('Deleted successfully'));
        return response()->json(['success' => true], 200);
    }
}

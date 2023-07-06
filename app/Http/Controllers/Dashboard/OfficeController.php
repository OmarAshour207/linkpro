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
            'floor_id'  => 'required|numeric',
            'path_id'   => 'required|numeric'
        ]);

        $titles = $data['title'];
        for ($i = 0; $i < count($titles);$i++) {
            if(!empty($data['title'][$i])) {
                Office::create([
                    'title'     => $data['title'][$i],
                    'user_id'   => $data['user_id'],
                    'floor_id'  => $data['floor_id'],
                    'path_id'   => $data['path_id']
                ]);
            }
        }
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('offices.index');
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
        return redirect()->route('offices.index');
    }
}

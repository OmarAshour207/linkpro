<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Path;
use App\Models\User;
use Illuminate\Http\Request;

class PathController extends Controller
{
    public function index()
    {
        $paths = Path::whenSearch(\request()->search)
            ->with(['company', 'floor'])
            ->orderBy('id')
            ->paginate(20);
        return view('dashboard.paths.index', compact('paths'));
    }

    public function create()
    {
        $companies = User::companies()->get();
        return view('dashboard.paths.create', compact('companies'));
    }

    public function showFloors(Request $request)
    {
        if ($request->ajax()) {
            $floors = Floor::where('user_id', $request->get('company_id'))->get();
            $floorId = $request->get('floor_id');
            return view('dashboard.paths.ajax.floors', compact('floors', 'floorId'));
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'     => 'required|array',
            'user_id'   => 'required|numeric',
            'floor_id'  => 'required|array',
            'floor_id.*'=> 'required|string',
            'path_id'   => 'sometimes|nullable'
        ]);

        $titles = $data['title'];
        foreach ($titles as $index => $title) {
            Path::updateOrCreate(['id' => $data['path_id'][$index]], [
                'title'         => $title,
                'user_id'       => $data['user_id'],
                'floor_id'      => $data['floor_id'][$index]
            ]);
        }
        session()->flash('success', __('Saved successfully'));
        return redirect("/dashboard/companies/{$data['user_id']}/edit?tab=paths");
//        return redirect()->route('paths.index');
    }

    public function show($id)
    {
        //
    }

    public function edit(Path $path)
    {
        $companies = User::companies()->get();
        $floors = Floor::where('id', $path->floor_id)->get();
        return view('dashboard.paths.edit', compact('path', 'companies', 'floors'));
    }

    public function update(Request $request, Path $path)
    {
        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'user_id'   => 'required|numeric',
            'floor_id'  => 'required|numeric'
        ]);

        $path->update($data);
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('paths.index');
    }

    public function destroy(Path $path)
    {
        $path->delete();
        session()->flash('success', __('Deleted successfully'));
        return response()->json(['success' => true], 200);
//        return redirect()->route('paths.index');
    }
}

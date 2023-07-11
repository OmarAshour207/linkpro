<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\User;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    public function index()
    {
        $floors = Floor::whenSearch(\request()->search)
            ->with('company')
            ->orderBy('id')
            ->paginate(20);
        return view('dashboard.floors.index', compact('floors'));
    }

    public function create()
    {
        $companies = User::companies()->get();
        return view('dashboard.floors.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'     => 'required|array',
            'user_id'   => 'required|numeric',
            'floor_id'  => 'sometimes|nullable'
        ]);

        foreach ($data['title'] as $index => $title) {
            if(empty($title))
                continue;
            Floor::updateOrCreate(['id' => $data['floor_id'][$index]], [
                'user_id'       => $data['user_id'],
                'title'         => $title
            ]);
        }
        session()->flash('success', __('Saved successfully'));
        return redirect("/dashboard/companies/{$data['user_id']}/edit?tab=floors");
//        return redirect()->back();
    }

    public function show($id)
    {
        //
    }

    public function edit(Floor $floor)
    {
        $companies = User::companies()->get();
        return view('dashboard.floors.edit', compact('floor', 'companies'));
    }

    public function update(Request $request, Floor $floor)
    {
        $data = $request->validate([
            'title'     => 'required',
            'user_id'   => 'required|numeric'
        ]);

        $floor->update($data);
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('floors.index');
    }

    public function destroy(Floor $floor)
    {
        $floor->delete();
        session()->flash('success', __('Deleted successfully'));
        return response()->json(['success' => true], 200);
//        return redirect()->route('floors.index');
    }
}

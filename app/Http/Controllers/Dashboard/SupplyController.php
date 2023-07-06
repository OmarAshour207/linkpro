<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Supply;
use App\Models\User;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function index()
    {
        $supplies = Supply::with('company')
            ->whenSearch(\request()->search)
            ->orderBy('id', 'desc')
            ->paginate(20);
        return view('dashboard.supplies.index', compact('supplies'));
    }

    public function create()
    {
        $companies = User::companies()->get();
        return view('dashboard.supplies.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required',
            'quantity'  => 'sometimes|nullable',
            'user_id'   => 'required|numeric'
        ]);

        $names = $data['name'];

        for ($i = 0;$i < count($names);$i++) {
            if(!empty($data['name'][$i])) {
                Supply::create([
                    'name'      => $data['name'][$i],
                    'quantity'  => $data['quantity'][$i],
                    'user_id'   => $data['user_id']
                ]);
            }
        }
        session()->flash('success', __('Saved Successfully'));
        return redirect()->route('supplies.index');
    }

    public function show(Supply $supply)
    {
        //
    }

    public function edit(Supply $supply, Request $request)
    {
        $companies = User::companies()->get();
        return view('dashboard.supplies.edit', compact('companies', 'supply'));
    }

    public function update(Request $request, Supply $supply)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'quantity'  => 'sometimes|nullable|numeric',
            'user_id'   => 'required|numeric'
        ]);

        $supply->update($data);
        session()->flash('success', __('Saved Successfully'));
        return redirect()->route('supplies.index');
    }

    public function destroy(Supply $supply)
    {
        $supply->delete();
        session()->flash('success', __('Deleted Successfully'));
        return redirect()->route('supplies.index');
    }
}

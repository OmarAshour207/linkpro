<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Notification;
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
            'user_id'   => 'required|numeric',
            'supply_id' => 'sometimes|nullable|array',
            'name'      => 'required|array',
            'quantity'  => 'sometimes|nullable|array',
            'unit'      => 'sometimes|nullable|array'
        ]);

        $supplies = $data['name'];

        foreach ($supplies as $index => $supply) {
            if(empty($supply))
                continue;
            Supply::updateOrCreate([
                'id'    => $data['supply_id'][$index]
            ],[
                'user_id'   => $data['user_id'],
                'name'      => $data['name'][$index],
                'quantity'  => $data['quantity'][$index],
                'unit'      => $data['unit'][$index],
            ]);
        }

        session()->flash('success', __('Saved successfully'));
        return redirect("/dashboard/companies/{$data['user_id']}/edit?tab=supplies");
//        return redirect()->route('supplies.index');
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
        session()->flash('success', __('Deleted successfully'));
        return response()->json(['success' => true], 200);
//        return redirect()->route('supplies.index');
    }
}

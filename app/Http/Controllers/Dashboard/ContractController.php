<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\User;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = User::whenSearch(\request()->search)
            ->whereRole('contract')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('dashboard.contracts.index', compact('contracts'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        session()->flash('success', __('Deleted successfully'));
        return redirect()->back();
    }
}

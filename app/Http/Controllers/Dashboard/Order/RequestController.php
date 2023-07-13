<?php

namespace App\Http\Controllers\Dashboard\Order;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Floor;
use App\Models\Office;
use App\Models\Path;
use App\Models\Service;
use App\Models\Supply;
use App\Models\Ticket;
use App\Models\TicketData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RequestController extends Controller
{
    public function index()
    {
        $requests = Ticket::whereType('users')
            ->with('user', 'service')
            ->latest()
            ->paginate(20);

        return view('dashboard.orders.requests.index', compact('requests'));
    }

    public function create()
    {
        $users = User::whereRole('user')->get();
        $services = Service::all();

        return view('dashboard.orders.requests.create', compact('users', 'services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_id'    => 'required|numeric',
            'notes'         => 'sometimes|nullable|string',
            'date'          => 'required|date',
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i',
            'user_id'       => 'required|numeric'
        ]);
        $data['type'] = 'request';

        Ticket::create($data);
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('requests.index');
    }

    public function edit($id)
    {
        $request = Ticket::findOrFail($id);
        $users = User::whereRole('user')->get();
        $services = Service::all();
        return view('dashboard.orders.requests.edit', compact('users', 'request', 'services'));
 }

    public function update($id, Request $request)
    {
        $ticket = Ticket::findOrFail($id);

        $data = $request->validate([
            'service_id'    => 'required|numeric',
            'notes'         => 'sometimes|nullable|string',
            'date'          => 'required|date',
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i',
            'user_id'       => 'required|numeric',
            'status'        => 'required|numeric',
            'reason'        => Rule::requiredIf(fn() => ($request->status == 4))
        ]);

        $ticket->update($data);
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('requests.index');

    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        session()->flash('success', __('Deleted successfully'));
        return redirect()->back();
    }
}

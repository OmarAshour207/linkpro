<?php

namespace App\Http\Controllers\Dashboard\Order;

use App\Http\Controllers\Controller;
use App\Models\Comment;
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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RequestController extends Controller
{
    public function index()
    {
        $requests = Ticket::whereType('request')
            ->with('user', 'service')
            ->latest()
            ->paginate(2);

        return view('dashboard.orders.requests.index', compact('requests'));
    }

    public function create()
    {
        $users = User::whereRole('user')->get();
        $companies = User::whereRole('company')->get();
        $services = Service::all();

        return view('dashboard.orders.requests.create', compact('users', 'services', 'companies'));
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
        $data['company_id'] = $data['user_id'];

        Ticket::create($data);
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('requests.index');
    }

    public function edit($id)
    {
        $request = Ticket::findOrFail($id);

        $users = User::whereRole('user')->get();
        $companies = User::whereRole('company')->get();
        $services = Service::all();
        $comments = Comment::with('user')
            ->where('ticket_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        return view('dashboard.orders.requests.edit', compact('users', 'request', 'services', 'comments', 'companies'));
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
            'reason'        => Rule::requiredIf(fn() => ($request->status == 4)),
            'prepare_time'  => Rule::requiredIf(fn() => ($request->status == 2 && $ticket->status != 2))
        ]);
        $data['company_id'] = $data['user_id'];

        if ($data['status'] == 2 && $ticket->status != 2)
            $data['status_updated_at'] = Carbon::now();

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

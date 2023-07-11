<?php

namespace App\Http\Controllers\Dashboard\Order;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Floor;
use App\Models\Office;
use App\Models\Path;
use App\Models\Supply;
use App\Models\Ticket;
use App\Models\TicketData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::whereType('company')
            ->with('company', 'content')
            ->latest()
            ->paginate(20);

        return view('dashboard.orders.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $companies = User::companies()->get();
        return view('dashboard.orders.tickets.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'notes'         => 'sometimes|nullable',
            'company_id'    => 'required|numeric',
            'floor_id'      => 'required|numeric',
            'path_id'       => 'required|numeric',
            'office_id'     => 'required|numeric',
            'content_id'    => 'required|numeric',
        ]);
        $data['notes'] = $data['notes'][$data['content_id']];

        $data['type'] = 'company';
        $data['user_id'] = auth()->user()->id;
        Ticket::create($data);

        session()->flash('success', __('Saved successfully'));
        return redirect()->route('tickets.index');
    }
    public function edit(Ticket $ticket)
    {
        $companies = User::companies()->get();
        $floors = Floor::where('user_id', $ticket->company_id)->get();
        $paths = Path::where('floor_id', $ticket->floor_id)->get();
        $offices = Office::where('path_id', $ticket->path_id)->get();
        $contents = Content::where('office_id', $ticket->office_id)->get();

        return view('dashboard.orders.tickets.edit', [
            'ticket'    => $ticket,
            'companies' => $companies,
            'floors'    => $floors,
            'paths'     => $paths,
            'offices'   => $offices,
            'contents'  => $contents,
        ]);
    }

    public function update(Ticket $ticket, Request $request)
    {
        $data = $request->validate([
            'notes'         => 'sometimes|nullable|array',
            'company_id'    => 'required|numeric',
            'floor_id'      => 'required|numeric',
            'path_id'       => 'required|numeric',
            'office_id'     => 'required|numeric',
            'content_id'    => 'required|numeric',
            'status'        => 'required|numeric',
            'reason'        => Rule::requiredIf(fn() => ($request->status == 4))
        ]);
        $data['notes'] = $data['notes'][$data['content_id']];

        $ticket->update($data);
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('tickets.index');

    }

    public function showContents(Request $request)
    {
        if ($request->ajax()) {
            $officeId = $request->get('office_id');
            $contents = Content::where('office_id', $officeId)->get();
            return view('dashboard.orders.tickets.ajax.officecontents', compact('contents'));
        }
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        session()->flash('success', __('Deleted successfully'));
        return redirect()->route('tickets.index');
    }
}

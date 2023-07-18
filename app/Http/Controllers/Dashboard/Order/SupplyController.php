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

class SupplyController extends Controller
{
    public function index()
    {
        $tickets = Ticket::whereType('supply')
            ->with('company', 'ticketData.supply')
            ->latest()
            ->paginate(20);

        return view('dashboard.orders.supplies.index', compact('tickets'));
    }

    public function create()
    {
        $companies = User::companies()->get();

        return view('dashboard.orders.supplies.create', compact('companies'));
    }

    public function store(Request $request)
    {
        dd($request->all());
        $data = $request->validate([
            'notes'       => 'sometimes|nullable|string',
            'company_id'  => 'required|numeric',
            'supplies'    => 'required'
        ]);

        $data['user_id'] = auth()->user()->id;
        $data['type'] = 'supply';

        $ticket = Ticket::create($data);

        $supplies = $data['supplies'];

        foreach ($supplies as $supply) {
            if(isset($supply['box'])) {
                TicketData::create([
                    'ticket_id' => $ticket->id,
                    'supply_id' => $supply['supply_id'],
                    'quantity'  => $supply['quantity'],
                    'unit'      => $supply['unit']
                ]);
            }
        }
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('orders.supplies.index');
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        $supplies = Supply::where('user_id', $ticket->company_id)->get();

        $suppliesIds = [];

        foreach ($ticket->ticketData as $data) {
            foreach ($supplies as $supply) {
                if($supply->id == $data->supply_id) {
                    $suppliesIds[$supply->id] = [
                        'unit'      => $data->unit,
                        'quantity'  => $data->quantity,
                        'ticket_data_id' => $data->id
                    ];
                    break;
                }
            }
        }

        return view('dashboard.orders.supplies.edit', compact('ticket', 'supplies', 'suppliesIds'));
    }

    public function update($id, Request $request)
    {
        $ticket = Ticket::findOrFail($id);
        $data = $request->validate([
            'notes'       => 'sometimes|nullable|string',
            'company_id'  => 'required|numeric',
            'supplies'    => 'required',
            'status'      => 'required|numeric',
            'reason'      => Rule::requiredIf(fn() => ($request->status == 4))
        ]);
        $supplies = $data['supplies'];

        foreach ($supplies as $supply) {
            if(isset($supply['box'])) {
                unset($supply['box']);
                TicketData::updateOrCreate(['id' => $supply['ticket_data_id'] ],
                [
                    'ticket_id'     => $ticket->id,
                    'supply_id'     => $supply['supply_id'],
                    'quantity'      => $supply['quantity'],
                    'unit'          => $supply['unit']
                ]);
            } elseif (isset($supply['ticket_data_id']) && $supply['ticket_data_id'] != 0 && !isset($supply['box'])) {
                TicketData::whereId($supply['ticket_data_id'])->delete();
            }
        }

        $ticket->update($data);

        session()->flash('success', __('Saved successfully'));
        return redirect()->route('orders.supplies.index');

    }

    public function supplies(Request $request)
    {
        if ($request->ajax()) {
            $userId = $request->get('user_id');
            $supplies = Supply::where('user_id', $userId)->get();
            return view('dashboard.orders.supplies.supplies', compact('supplies'));
        }
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        session()->flash('success', __('Deleted successfully'));
        return redirect()->route('orders.supplies.index');
    }
}

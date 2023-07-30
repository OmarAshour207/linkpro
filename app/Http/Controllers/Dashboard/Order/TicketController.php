<?php

namespace App\Http\Controllers\Dashboard\Order;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Content;
use App\Models\Floor;
use App\Models\Office;
use App\Models\Path;
use App\Models\Supply;
use App\Models\Ticket;
use App\Models\TicketData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::whereType('ticket')
            ->with('ticketData', 'company', 'comments')
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
            'tickets'       => 'required'
        ]);

        $data['type'] = 'ticket';
        $data['user_id'] = auth()->user()->id;

        $ticket = Ticket::create($data);

        $tickets = $data['tickets'];

        foreach ($tickets as $index => $ticketData) {
            if(isset($ticketData['box'])) {
                TicketData::create([
                    'ticket_id'     => $ticket->id,
                    'content_id'    => $ticketData['content_id'],
                    'note'          => $ticketData['note']
                ]);
            }
        }

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
        $comments = Comment::with('user')->where('ticket_id', $ticket->id)->orderBy('id', 'desc')->get();

        $contentsIds = [];

        foreach ($ticket->ticketData as $data) {
            foreach ($contents as $content) {
                if($content->id == $data->content_id) {
                    $contentsIds[$content->id] = [
                        'note'              => $data->note,
                        'ticket_data_id'    => $data->id
                    ];
                    break;
                }
            }
        }

        return view('dashboard.orders.tickets.edit', [
            'ticket'    => $ticket,
            'companies' => $companies,
            'floors'    => $floors,
            'paths'     => $paths,
            'offices'   => $offices,
            'contents'  => $contents,
            'contentsIds'=> $contentsIds,
            'comments'  => $comments
        ]);
    }

    public function update(Ticket $ticket, Request $request)
    {
        $data = $request->validate([
            'notes'         => 'sometimes|nullable|string',
            'status'        => 'required|numeric',
            'reason'        => Rule::requiredIf(fn() => ($request->status == 4)),
            'prepare_time'  => Rule::requiredIf(fn() => ($request->status == 2 && $ticket->status != 2)),
            'tickets'       => 'required|array'
        ]);

        $tickets = $data['tickets'];

        foreach ($tickets as $index => $ticketData) {
            if(isset($ticketData['box'])) {
                unset($ticketData['box']);
                TicketData::updateOrCreate(['id' => $ticketData['ticket_data_id'] ],
                    [
                        'ticket_id'     => $ticket->id,
                        'content_id'    => $ticketData['content_id'],
                        'note'          => $ticketData['note'],
                    ]);
            } elseif (isset($ticketData['ticket_data_id']) && $ticketData['ticket_data_id'] != 0 && !isset($ticketData['box'])) {
                TicketData::whereId($ticketData['ticket_data_id'])->delete();
            }
        }

        if ($data['status'] == 2 && $ticket->status != 2)
            $data['status_updated_at'] = Carbon::now();

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

<?php

namespace App\Exports;

use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportTickets implements FromView
{
    public function view(): View
    {
        $tickets = Ticket::with('company', 'ticketData.content', 'user', 'floor', 'path', 'office')
            ->whenSearch(\request()->get('search'))
            ->whenFrom(\request()->get('from'))
            ->whenTo(\request()->get('to'))
            ->whenStatus(\request()->get('status'))
            ->whereType('ticket')
            ->get();

        return view('dashboard.exports.tickets', compact('tickets'));
    }
}

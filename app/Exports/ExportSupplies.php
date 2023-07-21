<?php

namespace App\Exports;

use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportSupplies implements FromView
{
    public function view(): View
    {
        $tickets = Ticket::with('company', 'ticketData.supply', 'user')
            ->whenSearch(\request()->get('search'))
            ->whenFrom(\request()->get('from'))
            ->whenTo(\request()->get('to'))
            ->whenStatus(\request()->get('status'))
            ->whereType('supply')
            ->get();

        return view('dashboard.exports.supplies', compact('tickets'));
    }
}

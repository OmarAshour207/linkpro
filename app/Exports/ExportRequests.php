<?php

namespace App\Exports;

use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportRequests implements FromView
{
    public function view(): View
    {
        $tickets = Ticket::with('user', 'service')
            ->whenSearch(\request()->get('search'))
            ->whenFrom(\request()->get('from'))
            ->whenTo(\request()->get('to'))
            ->whenStatus(\request()->get('status'))
            ->whereType('request')
            ->get();

        return view('dashboard.exports.requests', compact('tickets'));
    }

}

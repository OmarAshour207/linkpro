<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function login()
    {
        return view('dashboard.login');
    }

    public function index()
    {
        $tickets =
            Ticket::whenSearch(\request()->get('search'))
            ->whenType(\request()->get('type'))
            ->whenFrom(\request()->get('from'))
            ->whenTo(\request()->get('to'))
            ->whenStatus(\request()->get('status'))
            ->get();

        return view('dashboard.home', compact('tickets'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\ExportRequests;
use App\Exports\ExportSupplies;
use App\Exports\ExportTickets;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function login()
    {
        return view('dashboard.login');
    }

    public function index()
    {
        $ordersCount = DB::table('tickets')
            ->selectRaw('COUNT(id) as count, type')
            ->groupBy('type')
            ->get()
            ->toArray();

        $ordersCountStatus = DB::table('tickets')
            ->selectRaw('COUNT(id) as count, status')
            ->groupBy('status')
            ->get()
            ->toArray();

        $orders = Ticket::with('user')
                ->orderBy('id', 'desc')
                ->limit(20)
                ->get();

        return view('dashboard.home', compact('ordersCount', 'ordersCountStatus', 'orders'));
    }

    public function export(Request $request)
    {
        $type = $request->request->get('type');

        if ($type == 'ticket') {
            return Excel::download(new ExportTickets, 'orders.xlsx');
        } elseif ($type == 'supply') {
            return Excel::download(new ExportSupplies, 'orders.xlsx');
        } elseif ($type == 'request') {
            return Excel::download(new ExportRequests, 'orders.xlsx');
        }
        abort(404);
    }
}

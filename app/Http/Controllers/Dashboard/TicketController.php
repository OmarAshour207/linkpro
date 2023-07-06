<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Office;
use App\Models\OfficeContent;
use App\Models\Path;
use App\Models\Supply;
use App\Models\Ticket;
use App\Models\TicketData;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index($mode)
    {
        $tickets = Ticket::whereType($mode)
            ->with('user', 'ticketData.supply', 'ticketData.officeContent')
            ->latest()
            ->paginate(20);

        return view('dashboard.tickets.'.$mode.'.index', compact('tickets'));
    }

    public function create($mode)
    {
        if ($mode == 'users') {
            $users = User::whereRole('user')->get();
        } else {
            $users = User::companies()->get();
        }
        return view('dashboard.tickets.'.$mode.'.create', compact('users'));
    }

    public function store($mode, Request $request)
    {
        if($mode == 'users') {
            $data = $this->validateUsers($request);
        } elseif ($mode == 'supplies') {
            $data = $this->validateSupplies($request);
        } elseif ($mode == 'companies') {
            $data = $this->validateCompanies($request);
        }

        $data['type'] = $mode;
        $ticket = Ticket::create($data);

        if ($mode == 'supplies') {

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
        } elseif ($mode == 'companies') {
            $contents = $data['officecontents'];

            foreach ($contents as $content) {
                if (isset($content['box'])) {
                    TicketData::create([
                        'ticket_id'         => $ticket->id,
                        'officecontent_id'  => $content['office_content_id'],
                        'notes'             => $content['notes']
                    ]);
                }
            }
        }
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('tickets', $mode);
    }
    public function validateUsers($request)
    {
        return $request->validate([
            'content'       => 'required|string',
            'date_from'     => 'required|date',
            'date_to'       => 'required|date',
            'time_from'     => 'required|date_format:H:i',
            'user_id'       => 'required|numeric'
        ]);
    }

    public function validateSupplies($request)
    {
        return $request->validate([
            'content'       => 'required|string',
            'user_id'       => 'required|numeric',
            'supplies'      => 'required'
        ]);
    }

    public function validateCompanies($request)
    {
        return $request->validate([
            'notes'        => 'sometimes|nullable|string',
            'user_id'      => 'required|numeric',
            'officecontents'  => 'required'
        ]);
    }

    public function edit($mode, Ticket $ticket)
    {
        if ($mode == 'users') {
            $users = User::whereRole('user')->get();
            return view('dashboard.tickets.users.edit', compact('users', 'ticket'));
        }
        elseif ($mode == 'companies') {
            $users = User::companies()->get();
            $floors = Floor::where('user_id', $ticket->user_id)->get();
            $paths = Path::where('user_id', $ticket->user_id)->get();
            $offices = Office::where('user_id', $ticket->user_id)->get();
            $officeContents = OfficeContent::where('user_id', $ticket->user_id)->get();

            $contentsIds = [];

            foreach ($ticket->ticketData as $data) {
                foreach ($officeContents as $officeContent) {
                    $content = $officeContent;
                    if ($officeContent->id == $data->officecontent_id){
                        $contentsIds[$officeContent->id] = [
                            'notes'     => $data->notes,
                            'id'        => $data->id
                        ];
                        break;
                    }
                }
            }

//            foreach ($officeContents as $officeContent) {
//                if(key_exists($officeContent->id, $contentsIds)) {
////                    dd($officeContent->id);
////                    dd($contentsIds);
//                    var_dump(array_search($officeContent->id, array_keys($contentsIds)));
//                }
//            }
//            dd($contentsIds);
            return view('dashboard.tickets.companies.edit', [
                'ticket'    => $ticket,
                'contentsIds'=> $contentsIds,
                'users'     => $users,
                'floors'    => $floors,
                'paths'     => $paths,
                'offices'   => $offices,
                'officeContent' => isset($content) ? $content : '',
                'officeContents'    => $officeContents
            ]);
        }
        elseif ($mode == 'supplies') {
            $supplies = Supply::where('user_id', $ticket->user_id)->get();
            $users = User::companies()->get();

            $suppliesIds = [];

            foreach ($ticket->ticketData as $data) {
                foreach ($supplies as $supply) {
                    if($supply->id == $data->supply_id) {
                        $suppliesIds[$supply->id] = [
                            'unit'      => $data->unit,
                            'quantity'  => $data->quantity,
                            'id'        => $data->id
                        ];
                        break;
                    }
                }
            }

            return view('dashboard.tickets.supplies.edit', compact('ticket', 'supplies', 'users', 'suppliesIds'));
        }
        abort(404);
    }

    public function update($mode, Ticket $ticket, Request $request)
    {
        if($mode == 'users') {
            $data = $this->validateUsers($request);
        } elseif ($mode == 'supplies') {
            $data = $this->validateSupplies($request);

            $supplies = $data['supplies'];

            foreach ($supplies as $supply) {
                if(isset($supply['box']) && isset($supply['id'])) {
                    unset($supply['box']);
                    TicketData::whereId($supply['id'])->update($supply);
                } elseif (isset($supply['id']) && !isset($supply['box'])) {
                    TicketData::whereId($supply['id'])->delete();
                } elseif(isset($supply['box']) && !isset($supply['id'])) {
                    TicketData::create([
                        'ticket_id' => $ticket->id,
                        'supply_id' => $supply['supply_id'],
                        'quantity'  => $supply['quantity'],
                        'unit'      => $supply['unit']
                    ]);
                }
            }
        } elseif ($mode == 'companies') {
            $data = $this->validateCompanies($request);

            $contents = $data['officecontents'];
//            dd($contents);
            foreach ($contents as $content) {
                if(isset($content['box']) && isset($content['id'])) {
                    unset($content['box']);
                    TicketData::whereId($content['id'])->update($content);
                } elseif (isset($content['id']) && !isset($content['box'])) {
                    TicketData::whereId($content['id'])->delete();
                } elseif(isset($content['box']) && !isset($content['id'])) {
                    TicketData::create([
                        'ticket_id'         => $ticket->id,
                        'officecontent_id'  => $content['officecontent_id'],
                        'notes'             => $content['notes']
                    ]);
                }
            }
        }

        $ticket->update($data);
        session()->flash('success', __('Saved successfully'));
        return redirect()->route('tickets', $mode);

    }

    public function supplies(Request $request)
    {
        if ($request->ajax()) {
            $userId = $request->get('user_id');
            $supplies = Supply::where('user_id', $userId)->get();
            return view('dashboard.tickets.supplies.supplies', compact('supplies'));
        }
    }

    public function showContents(Request $request)
    {
        if ($request->ajax()) {
            $officeId = $request->get('office_id');
            $contents = OfficeContent::where('office_id', $officeId)->get();
            return view('dashboard.tickets.companies.ajax.officecontents', compact('contents'));
        }
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        session()->flash('success', __('Deleted successfully'));
        return redirect()->back();
    }
}

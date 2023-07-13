<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends BaseController
{
    public function storeTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|numeric',
            'floor_id'   => 'required|numeric',
            'path_id'    => 'required|numeric',
            'office_id'  => 'required|numeric',
            'content_id' => 'required|numeric',
            'notes'      => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $this->adaptErrorMessages($validator->errors()->getMessages()));
        }

        $data = $validator->validated();
        $data['type'] = 'ticket';
        $data['user_id'] = auth()->user()->id;

        $success['ticket'] = Ticket::create($data);

        return $this->sendResponse(__('Saved successfully'), $success);
    }

    public function storeRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|numeric',
            'date'       => 'required|date_format:Y-n-j',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i',
            'notes'      => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $this->adaptErrorMessages($validator->errors()->getMessages()));
        }

        $data = $validator->validated();
        $data['type'] = 'request';
        $data['user_id'] = auth()->user()->id;

        $success['request'] = Ticket::create($data);

        return $this->sendResponse(__('Saved successfully'), $success);

    }

    public function storeSupply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id'    => 'required|numeric',
            'notes'         => 'sometimes|nullable|string',
            'supplies'      => 'required|array'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $this->adaptErrorMessages($validator->errors()->getMessages()));
        }

        $data = $validator->validated();
        $data['type'] = 'supply';
        $data['user_id'] = auth()->user()->id;

        $ticket = Ticket::create($data);

        $supplies = $data['supplies'];

        foreach ($supplies as $supply) {
            TicketData::create([
                'ticket_id' => $ticket->id,
                'supply_id' => $supply['supply_id'],
                'quantity'  => $supply['quantity'],
                'unit'      => $supply['unit'],
                'note'      => isset($supply['note']) ? $supply['note'] : null
            ]);
        }
        $success['supplies'] = Ticket::with('ticketData')->find($ticket->id);

        return $this->sendResponse(__('Saved successfully'), $success);
    }
}

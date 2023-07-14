<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderRequestResource;
use App\Http\Resources\OrderSupplyResource;
use App\Http\Resources\OrderTicketResource;
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

        $result = Ticket::create($data);

        return $this->sendResponse($result, __('Saved successfully'));
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

        $result = Ticket::create($data);

        return $this->sendResponse($result, __('Saved successfully'));

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
        $result = Ticket::with('ticketData')->find($ticket->id);

        return $this->sendResponse($result, __('Saved successfully'));
    }

    public function get($mode, $id)
    {
        if ($mode == 'request') {
            return $this->getRequest($id);
        } elseif ($mode == 'supplies') {
            return $this->getSupplies($id);
        } elseif ($mode == 'company') {
            return $this->getCompany($id);
        }
        return $this->sendError('s_authError', [__('Mode not available')]);
    }

    public function getRequest($id)
    {
        $request = Ticket::with('service')
            ->whereType('request')
            ->whereId($id)
            ->first();

        if ($request->user_id != auth()->user()->id)
            return $this->sendError(__('Unauthorized'), [__('s_unauthorized')], 401);

        $result = new OrderRequestResource($request);
        return $this->sendResponse($result, __('Data getting successfully'));
    }

    public function getSupplies($id)
    {
        $supply = Ticket::with('ticketData', 'company')
            ->whereType('supply')
            ->whereId($id)
            ->first();

        if ($supply->user_id != auth()->user()->id)
            if (auth()->user()->role == 'supervisor')
                if ($supply->company->supervisor_id != auth()->user()->id)
                    return $this->sendError(__('Unauthorized'), [__('s_unauthorized')], 401);

        $result = new OrderSupplyResource($supply);
        return $this->sendResponse($result, __('Data getting successfully'));
    }

    public function getCompany($id)
    {
        $ticket = Ticket::with(['company', 'floor', 'path', 'office', 'content'])
            ->whereType('ticket')
            ->whereId($id)
            ->first();

        if ($ticket->user_id != auth()->user()->id)
            if (auth()->user()->role == 'supervisor')
                if ($ticket->company->supervisor_id != auth()->user()->id)
                    return $this->sendError(__('Unauthorized'), [__('s_unauthorized')], 401);

        $result = new OrderTicketResource($ticket);
        return $this->sendResponse($result, __('Data getting successfully'));
    }

    public function getUserOrders($mode)
    {

    }
}

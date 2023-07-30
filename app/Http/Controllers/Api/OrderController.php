<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\OrderRequestResource;
use App\Http\Resources\OrderSupplyResource;
use App\Http\Resources\OrderTicketResource;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\TicketData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderController extends BaseController
{
    public function storeTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|numeric',
            'floor_id'   => 'required|numeric',
            'path_id'    => 'required|numeric',
            'office_id'  => 'required|numeric',
            'contents'   => 'required',
            'notes'      => 'sometimes|nullable|string',
        ]);

        if ($validator->fails())
            return $this->sendError(__('Validation Error'), $validator->errors()->getMessages());

        $data = $validator->validated();
        $data['type'] = 'ticket';
        $data['user_id'] = auth()->user()->id;

        $ticket = Ticket::create($data);

        if (isset($data['contents'])) {
            for ($i = 0;$i < count($data['contents']);$i++) {
                TicketData::create([
                    'ticket_id'     => $ticket->id,
                    'content_id'    => $data['contents'][$i],
                ]);
            }
        }
        $result = Ticket::whereId($ticket->id)->with('ticketData')->first();

        return $this->sendResponse(new OrderTicketResource($result), __('Saved successfully'));
    }

    public function storeRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|numeric',
            'date'       => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i',
            'notes'      => 'sometimes|nullable|string',
        ]);

        if ($validator->fails())
            return $this->sendError('Validation Error.', $validator->errors()->getMessages());

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
            'supplies'      => 'required'
        ]);

        if ($validator->fails())
            return $this->sendError('Validation Error.', $validator->errors()->getMessages());

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

    // TODO check for admin
    public function get()
    {
        $companyId = [];

        if(auth()->user()->role == 'company')
            $companyId = [auth()->user()->id];
        elseif (auth()->user()->role == 'supervisor') {
            $companyId = User::where('supervisor_id', auth()->user()->id)->pluck('id');
        }

        $tickets = Ticket::with(['company', 'floor', 'path', 'office', 'ticketData', 'service'])
            ->whereIn('type', ['ticket', 'supply', 'request'])
            ->whereIn('company_id', $companyId)
            ->orWhere('user_id', auth()->user()->id)
            ->get()
            ->groupBy(function ($data) {
                return $data->type;
            });

        if (count($tickets) < 1)
            return $this->sendError(__('Empty Order'), [__('Empty Order')], 401);

        $result['tickets'] = isset($tickets['ticket']) ? OrderTicketResource::collection($tickets['ticket']) : [];
        $result['supplies'] = isset($tickets['supply']) ? OrderSupplyResource::collection($tickets['supply']) : [];
        $result['requests'] = isset($tickets['request']) ? OrderRequestResource::collection($tickets['request']) : [];

        return $this->sendResponse($result, __('Data getting successfully'));
    }

    // TODO check for admin
    public function changeStatus($id, Request $request)
    {
        if (auth()->user()->role != 'supervisor')
            return $this->sendError(__('Unauthorized'), [__('s_unauthorized')], 401);

        $order = Ticket::whereId($id)->first();

        if ($order->type == 'ticket' || $order->type == 'supply')
            if ($order->company->supervisor_id != auth()->user()->id)
                return $this->sendError(__('Unauthorized'), [__('s_unauthorized')], 401);

        $validator = Validator::make($request->all(), [
            'status'        => 'required|numeric',
            'prepare_time' => Rule::requiredIf(fn() => ($request->status == 2 || $request->status == 3))
        ]);

        if ($validator->fails())
            return $this->sendError('Validation Error.', $validator->errors()->getMessages());

        $order->update($validator->validated());

        $result = [];
        if ($order->type == 'ticket')
            $result = new OrderTicketResource($order);
        if ($order->type == 'supply')
            $result = new OrderSupplyResource($order);

        return $this->sendResponse($result, __('Saved successfully'));
    }

    public function storeComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_id'    => 'required|numeric',
            'content'      => 'required|string',
        ]);

        if ($validator->fails())
            return $this->sendError('Validation Error.', $validator->errors()->getMessages());

        $data = $validator->validated();
        $data['user_id'] = auth()->user()->id;

        $comment = Comment::create($data);
        $comment = Comment::with('ticket', 'user')->find($comment->id);

        $result = new CommentResource($comment);

        return $this->sendResponse($result, __('Saved successfully'));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\OrderRequestResource;
use App\Http\Resources\OrderSupplyResource;
use App\Http\Resources\OrderTicketResource;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Ticket;
use App\Models\TicketData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

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
            return $this->sendError(__('Validation Error.'), $validator->errors()->getMessages(), 422);

        $data = $validator->validated();
        $data['type'] = 'ticket';
        $data['user_id'] = auth()->user()->id;

        $ticket = Ticket::create($data);

        if (isset($data['contents'])) {
            foreach ($data['contents'] as $content) {
                TicketData::create([
                    'ticket_id'     => $ticket->id,
                    'content_id'    => $content['id'],
                ]);
            }
//            for ($i = 0;$i < count($data['contents']);$i++) {
//
//            }
        }

        $result = Ticket::with('ticketData')->find($ticket->id);

        $notifyData = [];
        $notifyData['title'] = __('New ticket');
        $notifyData['body'] = __('New ticket registered') . ' ' . __('From') . ' ' . auth()->user()->name;
        $notifyData['admin'] = true;
        sendNotification($notifyData);

        return $this->sendResponse($result, __('Saved successfully'));
    }

    public function storeRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|numeric',
            'date'       => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i',
            'notes'      => 'sometimes|nullable|string',
            'company_id' => 'sometimes|nullable|numeric'
        ]);

        if ($validator->fails())
            return $this->sendError(__('Validation Error.'), $validator->errors()->getMessages(), 422);

        $data = $validator->validated();
        $data['type'] = 'request';
        $data['user_id'] = auth()->user()->id;

        if (!isset($data['company_id']))
            $data['company_id'] = $data['user_id'];

        $result = Ticket::create($data);

        $notifyData = [];
        $notifyData['title'] = __('New request');
        $notifyData['body'] = __('New request registered') . ' ' . __('From') . ' ' . auth()->user()->name;
        $notifyData['admin'] = true;

        sendNotification($notifyData);

        return $this->sendResponse($result, __('Saved successfully'));
    }

    public function storeSupply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id'    => 'required|numeric',
            'notes'         => 'sometimes|nullable|string',
            'supplies'      => 'required|array'
        ]);

        if ($validator->fails())
            return $this->sendError(__('Validation Error.'), $validator->errors()->getMessages(), 422);

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
//                'note'      => isset($supply['note']) ? $supply['note'] : null
            ]);
        }
        $result = Ticket::with('ticketData')->find($ticket->id);

        $notifyData = [];
        $notifyData['title'] = __('New supply');
        $notifyData['body'] = __('New supply registered') . ' ' . __('From') . ' ' . auth()->user()->name;
        $notifyData['admin'] = true;
        sendNotification($notifyData);

        return $this->sendResponse($result, __('Saved successfully'));
    }

    public function get()
    {
        $companyId = [];
        $roles = ['company', 'supervisor', 'admin', 'user'];
        $role = auth()->user()->role;

        if(!in_array($role, $roles))
            return $this->sendResponse([], __('Empty Order'));

        if($role == 'company')
            $companyId = [auth()->user()->id];
        elseif ($role == 'supervisor') {
            $companyId = User::where('supervisor_id', auth()->user()->id)->pluck('id');
        }
//        elseif (auth()->user()->role == 'admin') {
//            $companyId = User::whereRole('company')->pluck('id');
//        }

        $tickets = Ticket::with(['company', 'floor', 'path', 'office', 'ticketData', 'service', 'user'])
            ->whereIn('type', ['ticket', 'supply', 'request'])
            // if company or supervisor
            ->when($role == 'company' || $role == 'supervisor', function ($query) use ($companyId) {
                return $query->whereIn('company_id', $companyId);
            })
            ->when($role == 'user', function ($query) {
                return $query->where('user_id', auth()->user()->id);
            })
            ->paginate(60)
            ->groupBy(function ($data) {
                return $data->type;
            });

        if (count($tickets) < 1)
            return $this->sendResponse([], __('Empty Order'));

        $result['tickets'] = isset($tickets['ticket']) ? OrderTicketResource::collection($tickets['ticket']) : [];
        $result['supplies'] = isset($tickets['supply']) ? OrderSupplyResource::collection($tickets['supply']) : [];
        $result['requests'] = isset($tickets['request']) ? OrderRequestResource::collection($tickets['request']) : [];

        return $this->sendResponse($result, __('Data getting successfully'));
    }

    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id'      => 'required|numeric',
            'status'        => 'required|numeric',
            'prepare_time'  => Rule::requiredIf(fn() => ($request->status == 2)),
            'reason'        => Rule::requiredIf(fn() => ($request->status == 4))
        ]);

        if ($validator->fails())
            return $this->sendError(__('Validation Error.'), $validator->errors()->getMessages(), 422);

        $data = $validator->validated();
        $id = $data['order_id'];

        if(!$id)
            return $this->sendError(__('Not Found Order!'), [__('s_notFoundOrder')]);

        $order = Ticket::with('user', 'company')->find($id);

        if(!$order)
            return $this->sendError(__('Not Found Order!'), [__('s_notFoundOrder')]);

        $types = ['supervisor', 'company', 'admin'];
        $role = auth()->user()->role;

        if(!in_array($role, $types))
            return $this->sendError(__('Unauthorized'), ['s_unauthorized'], 401);

        if($role == 'supervisor') {
            if ($order->company->supervisor_id != auth()->user()->id)
                return $this->sendError(__('Unauthorized'), ['s_unauthorized'], 401);
        }

        if ($role == 'company') {
            $companyId = $order->company_id;

            if (($companyId != auth()->user()->id && $order->user_id != auth()->user()->id) || $data['status'] != 3)
                return $this->sendError(__('Unauthorized'), ['s_unauthorized'], 401);
        }

        if($data['status'] != $order->status && ($data['status'] == 4 || $data['status'] == 2))
            saveActivity(array('activity' => __('changed Status for') . ' ' . ($data['status'] == 2 ? __('Under Processing') : __('Rejected')) , 'ticket_id' => $order->id));

        $order->update($validator->validated());

        $result = [];
        if ($order->type == 'ticket')
            $result = new OrderTicketResource($order);
        if ($order->type == 'supply')
            $result = new OrderSupplyResource($order);
        if ($order->type == 'request')
            $result = new OrderRequestResource($order);

        $statusName = [
            '1'     => __('On hold'),
            '2'     => __('Under Processing'),
            '3'     => __('Delivered'),
            '4'     => __('Rejected'),
            '5'     => __('Delayed')
        ];

        $notifyData = [];
        $notifyData['title'] = __('Order status changed');
        $notifyData['body'] = auth()->user()->name . ' ' . __('changed Status for') . ' ' . $order->user->name . " " . __('To') . " " . $statusName[$order->status];

        Notification::create([
            'user_id'   => auth()->user()->id,
            'title'     => $notifyData['title'],
            'content'   => $notifyData['body']
        ]);

        $notifyData['admin'] = true;
        $notifyData['tokens'] = [auth()->user()->fcm_token];
        sendNotification($notifyData);

        return $this->sendResponse($result, __('Saved successfully'));
    }

    public function storeComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_id'    => 'required|numeric',
            'content'      => 'required|string',
        ]);

        if ($validator->fails())
            return $this->sendError(__('Validation Error.'), $validator->errors()->getMessages(), 422);

        $data = $validator->validated();
        $data['user_id'] = auth()->user()->id;

        $comment = Comment::create($data);
        $comment = Comment::with('ticket', 'user')->find($comment->id);

        $result = new CommentResource($comment);

        return $this->sendResponse($result, __('Saved successfully'));
    }
}

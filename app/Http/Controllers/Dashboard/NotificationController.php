<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function clear()
    {
        Notification::where(
            ['user_id', auth()->user()->id],
            'status', 0)->update([
                'status'    => 1
        ]);

        return redirect()->back();
    }

    public function test()
    {
        $notifyData = [];
        $notifyData['title'] = __('New contract');
        $notifyData['body'] = __('New contract registered');
        $notifyData['admin'] = true;
        $result = sendNotification($notifyData);

        return response()->json([
            'success'   => $result ?? false
        ]);
    }
}

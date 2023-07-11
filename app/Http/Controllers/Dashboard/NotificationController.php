<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function clear()
    {
        Notification::where('user_id', auth()->user()->id)->update([
            'status'    => 1
        ]);

        return redirect()->back();
    }
}

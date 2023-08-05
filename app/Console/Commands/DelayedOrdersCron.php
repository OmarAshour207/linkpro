<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DelayedOrdersCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delayed_orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check order preparation time and if exceeded it change status to delayed';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $delayedOrders = Ticket::where('status', 2)
            ->whereNotNull('prepare_time')
            ->get();

        $notifyData = [];
        $notifyData['title'] = __('Order status changed');
        $notifyData['body'] = __('Order status changed') . " " . __('To') . " " . __('Delayed');

        foreach ($delayedOrders as $order) {
            if (Carbon::parse($order->status_updated_at)->addMinutes($order->prepare_time) <= Carbon::now()) {

                $order->update(['status' => 5]);

                Notification::create([
                    'user_id'   => $order->user_id,
                    'title'     => $notifyData['title'],
                    'content'   => $notifyData['body']
                ]);

                $notifyData['admin'] = true;
                $notifyData['tokens'] = [$order->user->fcm_token];
                sendNotification($notifyData);
            }
        }
        \Log::info('Cron worked Fine');

        return Command::SUCCESS;
    }
}

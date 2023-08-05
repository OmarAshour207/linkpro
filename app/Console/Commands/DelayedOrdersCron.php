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
        $delayedOrders = Ticket::where('status', 2)->whereNotNull('prepare_time')->get();

        foreach ($delayedOrders as $order) {
            if ($order->status_updated_at->addMinutes($order->prepare_time) <= Carbon::now()) {
                $order->update(['status' => 5]);

                Notification::create([
                    'user_id'   => $order->user_id,
                    'content'   => __('Order changed to delayed')
                ]);
            }
        }
        \Log::info('Cron worked Fine');

        return Command::SUCCESS;
    }
}

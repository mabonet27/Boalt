<?php

namespace App\Console\Commands;

use App\Notifications;
use Illuminate\Console\Command;

class ClearNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all notifications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notifications = Notifications::all();
        
        foreach ($notifications as $notification) {
            $notification->delete();
        }
    }
}

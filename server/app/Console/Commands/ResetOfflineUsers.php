<?php

namespace App\Console\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ResetOfflineUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:offline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to change user status id he/she last action date > 15 minute';

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
     * @return mixed
     */
    public function handle()
    {
        $users = User::select('is_online', 'last_action_date')
            ->where('last_action_date', '<=', Carbon::now()->subMinutes(15))
            ->whereNotNull('last_action_date')
            ->update(['is_online' => false, 'last_action_date' => null, 'is_mobile' => false]);
    }
}

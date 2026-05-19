<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\TimeLog;
use App\Mail\MissingTimeLogReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

#[Signature('app:send-missing-time-log-reminders')]
#[Description('Command description')]
class SendMissingTimeLogReminders extends Command
{
    /**
     * Execute the console command.
     */
    protected $signature = 'reminders:missing-time-logs';
    protected $description = "Send reminders to user missing today's time log";
    public function handle(): int
    {
        $today = Carbon::today()->toDateString();
        $users = User::where('status', true)->get();
        foreach ($users as $user) {
            $log = TimeLog::where('user_id', $user->id)->where('log_date', $today)->first();

            if(!$log || !$log->arrival_time || !$log->departure_time){
                Mail::to($user->email)->send(new MissingTimeLogReminder($user->name, $today));
            }
    }
    return Command::SUCCESS;
    }
}

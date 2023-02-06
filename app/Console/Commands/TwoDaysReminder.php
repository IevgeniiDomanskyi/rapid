<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\Book\TwoDaysReminder as BookTwoDaysReminderNotification;

class TwoDaysReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twodays:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '2 days before each track and road dates';

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
        $reminders = service('Book')->twoDaysUsers();
        foreach ($reminders as $reminder) {
            $auth_token = md5(time().$reminder['user']->id.$reminder['user']->email);
            service('User')->update($reminder['user'], [
                'auth_token' => $auth_token,
            ]);

            $reminder['user']->notify(new BookTwoDaysReminderNotification($reminder['book'], $reminder['type'], $reminder['date'], $auth_token));
        }
    }
}

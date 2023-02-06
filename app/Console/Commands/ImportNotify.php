<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\User\ImportNotify as UserImportNotifyNotification;

class ImportNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify customers after import';

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
        /* $users = service('User')->importNotify();
        foreach ($users as $user) {
            $auth_token = md5(time().$reminder['user']->id.$reminder['user']->email);
            service('User')->update($reminder['user'], [
                'auth_token' => $auth_token,
            ]);

            $reminder['user']->notify(new BookTwoDaysReminderNotification($reminder['book'], $reminder['type'], $reminder['date'], $auth_token));
        } */
    }
}

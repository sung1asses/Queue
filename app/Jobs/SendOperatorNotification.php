<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOperatorNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $queue_list_id;
    public function __construct($queue_list_id)
    {
        $this->queue_list_id = $queue_list_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $queue_list = \App\QueueList::find($this->queue_list_id);
        if(!$queue_list) return false;
        $email = new \App\Mail\OperatorNotification($queue_list);

        $users = $queue_list->users()->get();
        foreach ($users as $user) {
            \Mail::to($user->email)->send($email);
        }
    }
}

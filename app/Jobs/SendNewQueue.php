<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendNewQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $request;
    public $queue_list;

    public function __construct($request, $queue_list)
    {
        $this->request = $request;
        $this->queue_list = $queue_list;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new \App\Mail\NewQueue($this->request, $this->queue_list);
        \Mail::to($this->request->email)->send($email);
    }
}
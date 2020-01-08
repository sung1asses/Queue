<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateQueueStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $id;
    public $status=0;
    public $operators;
    public function __construct($id, $status, $operators)
    {
        $this->id = $id;
        $this->status = $status;
        $this->operators = $operators;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \App\QueueList::find($this->id)->update([
            'status' => $this->status,
        ]);
        if($this->status == 1){
            
            foreach ($this->operators as $key => $value) {
                \App\User::find($key)->update([
                    'queue_list_id' => $this->id,
                ]);
            }
        }
        elseif($this->status == 2){
            foreach ($this->operators as $key => $value) {
                \App\User::find($key)->update([
                    'queue_list_id' => null,
                ]);
            }
        }
    }
}

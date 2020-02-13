<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class YouAreFired extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $queue_name;

    public function __construct($queue_name)
    {
        $this->queue_name = $queue_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Увы, но вы пропустили свою очередь!')
                    ->markdown('emails.you_are_fired',['queue_name' => $this->queue_name ]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
        return $this->from('laravel@example.com')
                    ->subject('Увы, но вы пропустили свою очередь!')
                    ->markdown('emails.fired',['queue_name' => $this->queue_name ]);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemainingOnePeople extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $request;
    public $queue_name;

    public function __construct($request, $queue_name)
    {
        $this->request = $request;
        $this->queue_name = $queue_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Не пропустите свою очередь!')
                    ->markdown('emails.remaining_one',[ 'request' => $this->request, 'queue_name' => $this->queue_name]);
    }
}
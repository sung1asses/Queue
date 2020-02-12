<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OperatorNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $queue_list;
    public function __construct($queue_list)
    {
        $this->queue_list = $queue_list;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Вас назначили оператором!')
                    ->markdown('emails.operator_notification',[ 'queue_list' => $this->queue_list ]);
    }
}

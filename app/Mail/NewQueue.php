<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewQueue extends Mailable
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
        return $this->from('laravel@example.com')
                    ->subject('Вы встали в очередь!')
                    ->markdown('emails.new_queue',[ 'request' => $this->request, 'queue_name' => $this->queue_name]);
    }
}
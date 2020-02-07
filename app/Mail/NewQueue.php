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
    public $queue_list;
    public $encrypted_key;

    public function __construct($request, $queue_list)
    {
        $this->request = $request;
        $this->queue_list = $queue_list;
        $this->encrypted_key = encrypt($request->key);
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
                    ->markdown('emails.new_queue',[ 'request' => $this->request, 'queue_list' => $this->queue_list, 'encrypted_key' => $this->encrypted_key]);
    }
}
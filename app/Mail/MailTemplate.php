<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param string $blade     'mail.blade.php'
     * @param array $details    example: ['title' => 'test title', 'content' => 'test content']
     * @param array $receiver
     * @example: [
     * 'to' => 'receiver@mail.com',
     * 'subject' => 'test subject',
     * 'cc' => 'cc@mail.com',
     * 'bcc' => 'bcc@mail.com',
     * 'replyTo' => 'replyTo@mail.com',
     * 'priority' => '1',
     * 'subject' => 'test subject',
     * ]
     */
    public $blade;
    public $details;
    public $receiver;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($blade, $details, $receiver)
    {
        $this->blade = $blade;
        $this->details = $details;
        $this->receiver = $receiver;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->to($this->receiver['to']);
        $this->subject($this->receiver['subject']);
        if (isset($this->receiver[''])) {
            $this->cc($this->receiver['subject']);
        }

        if (isset($this->receiver[''])) {
            $this->bcc($this->receiver['subject']);
        }
        if (isset($this->receiver[''])) {
            $this->replyTo($this->receiver['replyTo']);
        }
        if (isset($this->receiver[''])) {
            $this->priority($this->receiver['priority']);
        }

        return $this->view($this->blade);
    }
}

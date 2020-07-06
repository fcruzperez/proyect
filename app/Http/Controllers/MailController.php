<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MailService;

class MailController extends Controller
{
    private $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function test_email()
    {

        $details = [
            'title' => 'Mail from ItSolutionStuff.com',
            'firstname' => 'Ali David',
            'content' => 'This is for testing email using smtp'
        ];
        $receiver = [
            'from' => config('mail.from.address'),
            'to' => 'iceberg198819@gmail.com',
            'subject' => 'About Laravel Mailable'
        ];

        try {
            $error = $this->mailService->send('emails.test', $details, $receiver);
            if ($error === '') {
                echo 'mail sent';
            } else {
                echo $error;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}

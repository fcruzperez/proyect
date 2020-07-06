<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\MailTemplate;

/**
 * Class MailService
 * @package App\Http\Services
 */
class MailService
{
    /**
     * @param string $template          name of email template
     * @param array $template_params    email parameters
     * @param array $receiver_params    receiver parameters
     * @return string
     * @throws
     */
    public function send($template, $template_params, $receiver_params)
    {
        try {
            $mailable = new MailTemplate($template, $template_params, $receiver_params);
            Mail::send($mailable);
            return '';
        } catch (\Throwable $e) {
            $errMsg = $e->getMessage();
            logger()->error('mail sending error: ' . $errMsg);
            return $errMsg;
        }
    }
}

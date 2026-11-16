<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TempleNotificationMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subjectLine,
        public string $htmlContent,
        public ?string $fromAddress = null,
        public ?string $fromSenderName = null
    ) {}

    public function build()
    {
        $mail = $this->subject($this->subjectLine)
            ->html($this->htmlContent);

        if ($this->fromAddress) {
            $mail->from($this->fromAddress, $this->fromSenderName);
        }

        return $mail;
    }
}

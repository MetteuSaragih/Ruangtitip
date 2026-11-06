<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Email pemberitahuan untuk admin. Dibuat lewat App\Services\AdminNotifier. */
class AdminAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<int, array{0:string,1:string}>  $rows  baris rincian [label, nilai]
     * @param  string  $kind  order | action | info
     */
    public function __construct(
        public string $mailSubject,
        public string $headline,
        public array $rows = [],
        public ?string $url = null,
        public string $kind = 'info',
        public ?string $note = null,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: '[Admin RuangTitip] ' . $this->mailSubject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.admin-alert');
    }
}

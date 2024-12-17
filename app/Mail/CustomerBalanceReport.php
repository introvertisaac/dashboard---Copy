<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerBalanceReport extends Mailable
{
    use Queueable, SerializesModels;

    public $customers;
    private $customSubject;

    public function __construct($customers, $subject = null)
    {
        $this->customers = $customers;
        $this->customSubject = $subject;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->customSubject ?? 'Customer Balance Report - ' . now()->format('Y-m-d'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-balances-simple',
        );
    }

    public function attachments(): array
    {
        $pdf = PDF::loadView('emails.customer-balances-pdf', [
            'customers' => $this->customers
        ]);

        $tempPath = storage_path('app/customer_balances_'.time().'.pdf');
        file_put_contents($tempPath, $pdf->output());

        return [
            Attachment::fromPath($tempPath)
                ->as('customer_balances.pdf')
                ->withMime('application/pdf')
        ];
    }
}
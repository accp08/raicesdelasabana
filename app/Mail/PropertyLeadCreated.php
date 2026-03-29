<?php

namespace App\Mail;

use App\Models\PropertyLead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PropertyLeadCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PropertyLead $lead)
    {
    }

    public function build(): self
    {
        return $this->subject('Nuevo contacto de propiedad')
            ->view('emails.property-lead');
    }
}

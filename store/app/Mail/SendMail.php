<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $batchId;

    public function __construct($email, $batchId)
    {
        $this->email = $email;
        $this->batchId = $batchId;
    }

    public function build()
    {
        return $this->view('emails.product_created')
                  
                    ->to($this->email)
                    ->subject('Your Subject Here');
    }
}

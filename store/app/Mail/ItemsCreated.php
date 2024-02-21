<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ItemsCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $batchId;

    /**
     * Create a new message instance.
     *
     * @param string $email
     * @param string $batchId
     * @return void
     */
    public function __construct($email, $batchId)
    {
        $this->email = $email;
        $this->batchId = $batchId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.items_created')
                    
                    ->to($this->email)
                    ->subject('Item Created');
    }
}

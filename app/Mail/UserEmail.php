<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserEmail extends Mailable
{
    use Queueable, SerializesModels;


    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function build()
    {
        $address = 'constructorasystem@gmail.com';
        $subject = 'Solicitud de cita';
        $name = 'Cita';

        return $this->markdown('email.email-user')
            ->from($address, $name)
            ->subject($subject)
            ->bcc("erik4tohj@gmail.com")
            ->with([ 'user_info' =>  $this->data]);
    }
}

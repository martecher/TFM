<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MensajeAsignacionTarea extends Mailable
{   public $subject = 'Su tarea solictada ha sido asiganda.';
    public $tarea;
    use Queueable, SerializesModels;
   
    /**
     * Create a new message instance.
     *
     * @return void
     */
    
    public function __construct($tarea)
    {
         $this->tarea = $tarea;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.mensaje-asignacion');
    }
}

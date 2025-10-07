<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class OcorrenciaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dados;

    /**
     * Create a new message instance.
     */
    public function __construct(array $dados) // Adicione o parâmetro aqui
    {
        $this->dados = $dados;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this
            ->subject("Ticket registrado: {$this->dados['codigo_ocorrencia']}")
            ->view('emails.ocorrencia.registada')
            ->with('dados', $this->dados); // explícito para evitar problemas
    }
}

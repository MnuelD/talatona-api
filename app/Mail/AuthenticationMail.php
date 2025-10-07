<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuthenticationMail extends Mailable
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
            ->subject("Link de Autenticação: {$this->dados['code']}")
            ->view('emails.autenticacao.login')
            ->with('dados', $this->dados); // explícito para evitar problemas
    }
}

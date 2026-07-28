<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SolicitacaoVinculacaoProgramaNotification extends Notification
{
    use Queueable;

    public $data;
    public $url;
    public $titulo;
    public $nomePrograma;
    public $nomeProponente;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($trabalho, $programa)
    {
        $this->data = date('d/m/Y \à\s  H:i\h', strtotime(now()));
        $url = '/programa/' . $programa->id;// TODO: PROCURAR A URL CERTA
        $this->url = url($url);
        $this->titulo = $trabalho->titulo;
        $this->nomePrograma = $programa->nome;
        $this->nomeProponente = $trabalho->proponente->user->name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Sistema Submeta - Solicitação de vinculação de proposta')
            ->greeting('Saudações!')
            ->line("O proponente {$this->nomeProponente} solicitou a vinculação da proposta \"{$this->titulo}\" ao Programa de Extensão \"{$this->nomePrograma}\".\n\n.")
            ->line('Acesse o programa para aprovar ou reprovar esta solicitação.')
            ->line("{$this->data}")
            ->action('Revisar Solicitação', $this->url)
            ->markdown('vendor.notifications.email');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VinculacaoProgramaNotification extends Notification //TESTAR
{
    use Queueable;

    private $tituloTrab;
    private $nomePrograma;
    private $nomeProponente;
    private $data;
    private $url;
    private $status;
    private $motivoRejeicao;

    public function __construct($trabalho, $programa, $status)
    {
        $this->data = date('d/m/Y \à\s H:i\h', strtotime(now()));
        $this->url = url('/projeto/visualizar/' . $trabalho->id);
        $this->tituloTrab = $trabalho->titulo;
        $this->nomePrograma = $programa->nome;
        $this->nomeProponente = $trabalho->proponente->user->name;
        $this->status = $status;
        $this->motivoRejeicao = $trabalho->motivo_rejeicao;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mensagens = [
            'aceito'    => 'foi aceita(o) para o programa',
            'rejeitado' => 'foi rejeitada(o) para o programa',
        ];
        $aceito = $this->status === 'aceito';

        $texto = $mensagens[$this->status] ?? 'teve seu status atualizado no programa';

        return (new MailMessage)
            ->line('Sistema Submeta - Notificação de vinculação a programa de extensão')
            ->greeting('Saudações!')
            ->line("Sua proposta/projeto intitulada(o) {$this->tituloTrab} {$texto} {$this->nomePrograma}". $aceito ? '!' : '.')
            ->line(($aceito && $this->motivoRejeicao) ? "Motivo: {$this->motivoRejeicao}" : '')
            ->line("{$this->data}")
            ->action('Ver', $this->url)
            ->markdown('vendor.notifications.email');
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
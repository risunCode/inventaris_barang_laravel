<?php

namespace App\Notifications;

use App\Models\Transfer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class TransferRequested extends Notification
{
    use Queueable;

    protected $transfer;
    protected $requestedBy;

    public function __construct(Transfer $transfer, User $requestedBy)
    {
        $this->transfer = $transfer;
        $this->requestedBy = $requestedBy;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable): DatabaseMessage
    {
        return new DatabaseMessage($this->toArray($notifiable));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pengajuan Transfer Barang',
            'message' => "Transfer {$this->transfer->commodity->name} dari {$this->transfer->fromLocation->name} ke {$this->transfer->toLocation->name} oleh {$this->requestedBy->name}",
            'url' => route('transfers.show', $this->transfer->id),
            'transfer_id' => $this->transfer->id,
            'requested_by' => $this->requestedBy->name,
            'type' => 'transfer_request',
            'actions' => [
                [
                    'label' => 'Lihat Detail',
                    'url' => route('transfers.show', $this->transfer->id),
                    'class' => 'btn-primary',
                    'icon' => 'bx bx-show'
                ],
                [
                    'label' => 'Setujui',
                    'url' => route('transfers.approve', $this->transfer->id),
                    'class' => 'btn-success',
                    'icon' => 'bx bx-check',
                    'method' => 'POST'
                ],
                [
                    'label' => 'Tolak',
                    'url' => route('transfers.reject', $this->transfer->id),
                    'class' => 'btn-danger', 
                    'icon' => 'bx bx-x',
                    'method' => 'POST'
                ]
            ]
        ];
    }
}

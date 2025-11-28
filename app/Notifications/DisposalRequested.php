<?php

namespace App\Notifications;

use App\Models\Disposal;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class DisposalRequested extends Notification
{
    use Queueable;

    protected $disposal;
    protected $requester;

    public function __construct(Disposal $disposal, User $requester)
    {
        $this->disposal = $disposal;
        $this->requester = $requester;
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
        $reasonLabel = match($this->disposal->reason) {
            'rusak_total' => 'Rusak Total',
            'hilang' => 'Hilang',
            'usang' => 'Usang',
            'tidak_ekonomis' => 'Tidak Ekonomis',
            'lainnya' => 'Lainnya',
            default => ucfirst($this->disposal->reason)
        };

        return new DatabaseMessage([
            'title' => 'Permintaan Penghapusan Barang',
            'message' => "Penghapusan barang '{$this->disposal->commodity->name}' diminta oleh {$this->requester->name} dengan alasan: {$reasonLabel}",
            'type' => 'disposal_requested',
            'disposal_id' => $this->disposal->id,
            'commodity_name' => $this->disposal->commodity->name,
            'commodity_code' => $this->disposal->commodity->item_code,
            'reason' => $this->disposal->reason,
            'reason_label' => $reasonLabel,
            'method' => $this->disposal->method,
            'disposal_date' => $this->disposal->disposal_date->format('d/m/Y'),
            'requester_name' => $this->requester->name,
            'action_url' => route('disposals.show', $this->disposal),
            'icon' => 'trash',
            'icon_color' => 'text-red-500',
            'created_at' => now()->format('d/m/Y H:i'),
        ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $reasonLabel = match($this->disposal->reason) {
            'rusak_total' => 'Rusak Total',
            'hilang' => 'Hilang',
            'usang' => 'Usang',
            'tidak_ekonomis' => 'Tidak Ekonomis',
            'lainnya' => 'Lainnya',
            default => ucfirst($this->disposal->reason)
        };

        return [
            'title' => 'Pengajuan Penghapusan Barang',
            'message' => "Pengajuan penghapusan {$this->disposal->commodity->name} dengan alasan {$reasonLabel} oleh {$this->requester->name}",
            'url' => route('disposals.show', $this->disposal->id),
            'disposal_id' => $this->disposal->id,
            'requested_by' => $this->requester->name,
            'reason' => $this->disposal->reason,
            'type' => 'disposal_request',
            'actions' => [
                [
                    'label' => 'Lihat Detail',
                    'url' => route('disposals.show', $this->disposal->id),
                    'class' => 'btn-primary',
                    'icon' => 'bx bx-show'
                ],
                [
                    'label' => 'Setujui',
                    'url' => route('disposals.approve', $this->disposal->id),
                    'class' => 'btn-success',
                    'icon' => 'bx bx-check',
                    'method' => 'POST'
                ],
                [
                    'label' => 'Tolak',
                    'url' => route('disposals.reject', $this->disposal->id),
                    'class' => 'btn-danger',
                    'icon' => 'bx bx-x',
                    'method' => 'POST'
                ]
            ]
        ];
    }
}

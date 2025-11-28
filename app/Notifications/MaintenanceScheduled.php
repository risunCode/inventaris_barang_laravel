<?php

namespace App\Notifications;

use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class MaintenanceScheduled extends Notification
{
    use Queueable;

    protected $maintenance;
    protected $scheduler;

    public function __construct(Maintenance $maintenance, User $scheduler)
    {
        $this->maintenance = $maintenance;
        $this->scheduler = $scheduler;
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
        return new DatabaseMessage([
            'title' => 'Maintenance Dijadwalkan',
            'message' => "Maintenance '{$this->maintenance->description}' dijadwalkan untuk barang '{$this->maintenance->commodity->name}' oleh {$this->scheduler->name}",
            'type' => 'maintenance_scheduled',
            'maintenance_id' => $this->maintenance->id,
            'commodity_name' => $this->maintenance->commodity->name,
            'commodity_code' => $this->maintenance->commodity->item_code,
            'maintenance_description' => $this->maintenance->description,
            'maintenance_date' => $this->maintenance->maintenance_date->format('d/m/Y'),
            'technician' => $this->maintenance->performed_by,
            'cost' => number_format($this->maintenance->cost, 0, ',', '.'),
            'scheduler_name' => $this->scheduler->name,
            'action_url' => route('maintenance.show', $this->maintenance),
            'icon' => 'wrench',
            'icon_color' => 'text-orange-500',
            'created_at' => now()->format('d/m/Y H:i'),
        ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'maintenance_id' => $this->maintenance->id,
            'scheduler_id' => $this->scheduler->id,
        ];
    }
}

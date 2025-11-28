<?php

namespace App\Notifications;

use App\Models\Commodity;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class CommodityCreated extends Notification
{
    use Queueable;

    protected $commodity;
    protected $creator;

    public function __construct(Commodity $commodity, User $creator)
    {
        $this->commodity = $commodity;
        $this->creator = $creator;
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
            'title' => 'Barang Baru Ditambahkan',
            'message' => "Barang '{$this->commodity->name}' telah ditambahkan oleh {$this->creator->name}",
            'type' => 'commodity_created',
            'commodity_id' => $this->commodity->id,
            'commodity_code' => $this->commodity->item_code,
            'commodity_name' => $this->commodity->name,
            'creator_name' => $this->creator->name,
            'action_url' => route('commodities.show', $this->commodity),
            'icon' => 'plus-circle',
            'icon_color' => 'text-green-500',
            'created_at' => now()->format('d/m/Y H:i'),
        ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'commodity_id' => $this->commodity->id,
            'creator_id' => $this->creator->id,
        ];
    }
}

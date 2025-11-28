<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:notifications.view'),
        ];
    }

    /**
     * Tampilkan semua notifikasi.
     */
    public function index(Request $request): View
    {
        $perPage = min($request->get('per_page', 20), 100); // Max 100 per page
        $notifications = Auth::user()->notifications()->paginate($perPage);
        $unreadCount = Auth::user()->unreadNotifications()->count();
        $readCount = Auth::user()->readNotifications()->count();
        $totalCount = $notifications->total();
        
        return view('notifications.index', compact('notifications', 'unreadCount', 'readCount', 'totalCount'));
    }

    /**
     * Tandai satu notifikasi sebagai dibaca.
     */
    public function markRead(DatabaseNotification $notification): RedirectResponse
    {
        // Ownership check - pastikan notifikasi milik user yang login
        if ($notification->notifiable_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke notifikasi ini.');
        }

        $notification->markAsRead();

        // Redirect ke action URL jika ada
        if (isset($notification->data['action_url'])) {
            return redirect($notification->data['action_url']);
        }

        return back();
    }

    /**
     * Tandai semua notifikasi sebagai dibaca.
     */
    public function markAllRead(): RedirectResponse
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}

<x-app-layout title="Notifikasi">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Notifikasi</h1>
                <p style="color: var(--text-secondary);">Semua notifikasi sistem</p>
            </div>
            @if($unreadCount > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm hover:opacity-80" style="background-color: var(--bg-input); color: var(--text-secondary);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Tandai Semua Dibaca
                </button>
            </form>
            @endif
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $totalCount }}</p>
                        <p class="text-sm" style="color: var(--text-secondary);">Total Notifikasi</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-yellow-100 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $unreadCount }}</p>
                        <p class="text-sm" style="color: var(--text-secondary);">Belum Dibaca</p>
                    </div>
                </div>
            </div>
            <div class="rounded-xl p-4 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $readCount }}</p>
                        <p class="text-sm" style="color: var(--text-secondary);">Sudah Dibaca</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="rounded-xl overflow-hidden border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead style="background-color: var(--bg-input);">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Notifikasi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase" style="color: var(--text-secondary);">Waktu</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase" style="color: var(--text-secondary);">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color: var(--border-color);">
                        @forelse($notifications as $notification)
                        <tr class="hover:opacity-80">
                            <td class="px-4 py-4">
                                @if(!$notification->read_at)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Baru
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Dibaca
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: var(--bg-input);">
                                        <svg class="w-5 h-5" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    </div>
                                    <div>
                                        <p class="font-medium" style="color: var(--text-primary);">{{ $notification->data['title'] ?? 'Notifikasi' }}</p>
                                        <p class="text-sm" style="color: var(--text-secondary);">{{ Str::limit($notification->data['message'] ?? '', 60) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-sm" style="color: var(--text-primary);">{{ $notification->created_at->format('d M Y') }}</p>
                                <p class="text-xs" style="color: var(--text-secondary);">{{ $notification->created_at->format('H:i') }} • {{ $notification->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 flex-wrap">
                                    @if(!$notification->read_at)
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-lg hover:opacity-80" style="background-color: var(--bg-input); color: var(--text-secondary);" title="Tandai Dibaca">
                                            <i class="bx bx-check text-sm"></i>
                                        </button>
                                    </form>
                                    @endif
                                    
                                    @if(isset($notification->data['actions']))
                                        @foreach($notification->data['actions'] as $action)
                                            @if(isset($action['method']) && $action['method'] === 'POST')
                                            <form action="{{ $action['url'] }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium {{ $action['class'] ?? 'btn-primary' }}"
                                                        onclick="return confirm('{{ $action['label'] === 'Tolak' ? 'Yakin tolak pengajuan ini?' : 'Yakin ' . strtolower($action['label']) . ' pengajuan ini?' }}')">
                                                    <i class="{{ $action['icon'] ?? 'bx bx-link-external' }}"></i>
                                                    {{ $action['label'] }}
                                                </button>
                                            </form>
                                            @else
                                            <a href="{{ $action['url'] }}" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium {{ $action['class'] ?? 'btn-primary' }}">
                                                <i class="{{ $action['icon'] ?? 'bx bx-link-external' }}"></i>
                                                {{ $action['label'] }}
                                            </a>
                                            @endif
                                        @endforeach
                                    @elseif(isset($notification->data['url']))
                                    <a href="{{ $notification->data['url'] }}" class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs btn-primary">
                                        <i class="bx bx-show"></i>
                                        Lihat
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center" style="color: var(--text-secondary);">
                                <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                <p>Tidak ada notifikasi</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($notifications->hasPages())
            <div class="p-4 border-t" style="border-color: var(--border-color);">
                <x-pagination :paginator="$notifications" />
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

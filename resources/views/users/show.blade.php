<x-app-layout title="Detail Pengguna">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('users.index') }}" class="p-2 rounded-lg border hover:bg-gray-50" style="border-color: var(--border-color);" title="Kembali">
                <svg class="w-5 h-5" style="color: var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="flex items-center gap-3">
                <img src="{{ $user->avatar_url }}" class="w-12 h-12 rounded-full object-cover" alt="{{ $user->name }}">
                <div>
                    <h1 class="text-xl font-bold" style="color: var(--text-primary);">{{ $user->name }}</h1>
                    <p class="text-sm" style="color: var(--text-secondary);">{{ $user->email }}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="badge {{ $user->role === 'admin' ? 'badge-warning' : ($user->role === 'staff' ? 'badge-info' : 'badge-gray') }}">
                @if($user->role === 'admin')
                    Admin
                @elseif($user->role === 'staff')
                    Staff
                @else
                    Staff
                @endif
            </span>
            @if($user->is_active)
                <span class="badge badge-success">Aktif</span>
            @else
                <span class="badge badge-gray">Nonaktif</span>
            @endif
        </div>
    </div>

    <!-- Grid Layout - Wide -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        <!-- Left Column -->
        <div class="lg:col-span-3 space-y-4">
            <!-- Info Cards Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Referral Code -->
                <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
                    <p class="text-xs mb-1" style="color: var(--text-secondary);">Kode Referral</p>
                    <div class="flex items-center gap-2">
                        <code class="text-sm font-mono px-2 py-1 rounded" style="background-color: var(--bg-input); color: var(--text-primary);">{{ $user->referral_code }}</code>
                        <button type="button" onclick="copyToClipboard('{{ url('register?ref=' . $user->referral_code) }}')" class="p-1 rounded hover:bg-gray-100" title="Salin Link">
                            <svg class="w-4 h-4" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Referred By -->
                <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
                    <p class="text-xs mb-1" style="color: var(--text-secondary);">Direferensikan Oleh</p>
                    <p class="text-sm font-medium" style="color: var(--text-primary);">{{ $user->referrer?->name ?? 'Admin Pertama' }}</p>
                </div>

                <!-- Contact -->
                <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
                    <p class="text-xs mb-1" style="color: var(--text-secondary);">No. Telepon</p>
                    <p class="text-sm font-medium" style="color: var(--text-primary);">{{ $user->phone ?? '-' }}</p>
                </div>
            </div>

            <!-- Referrals -->
            @if($user->referrals->count() > 0)
            <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <h3 class="text-sm font-semibold mb-3" style="color: var(--text-primary);">Staff yang Direferensikan ({{ $user->referrals->count() }})</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($user->referrals as $referral)
                    <a href="{{ route('users.show', $referral) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm hover:opacity-80" style="background-color: var(--bg-input); color: var(--text-primary);">
                        <img src="{{ $referral->avatar_url }}" class="w-5 h-5 rounded-full" alt="">
                        {{ $referral->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Activity -->
            <div class="rounded-lg border overflow-hidden" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="px-4 py-3 border-b" style="border-color: var(--border-color);">
                    <h3 class="text-sm font-semibold flex items-center justify-between" style="color: var(--text-primary);">
                        Aktivitas Terakhir
                        <span class="text-xs font-normal px-2 py-1 rounded-full" style="background-color: var(--bg-input); color: var(--text-secondary);">{{ $activities->count() }} aktivitas</span>
                    </h3>
                </div>
                @if($activities->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead style="background-color: var(--bg-input);">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium" style="color: var(--text-secondary);">Aksi</th>
                                <th class="px-4 py-2 text-left font-medium" style="color: var(--text-secondary);">Deskripsi</th>
                                <th class="px-4 py-2 text-left font-medium" style="color: var(--text-secondary);">IP Address</th>
                                <th class="px-4 py-2 text-left font-medium" style="color: var(--text-secondary);">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="border-color: var(--border-color);">
                            @foreach($activities->take(10) as $activity)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-4 py-3">
                                    <span class="badge {{ $activity->action_badge_class }}">{{ $activity->action_label }}</span>
                                </td>
                                <td class="px-4 py-3" style="color: var(--text-primary);">
                                    {{ $activity->description }}
                                </td>
                                <td class="px-4 py-3 text-xs font-mono" style="color: var(--text-secondary);">
                                    {{ $activity->ip_address ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-xs" style="color: var(--text-secondary);">
                                    <div class="flex flex-col">
                                        <span>{{ $activity->created_at->format('d M Y, H:i') }}</span>
                                        <span class="text-xs opacity-70">{{ $activity->created_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($activities->count() > 10)
                <div class="px-4 py-3 border-t text-center" style="border-color: var(--border-color); background-color: var(--bg-input);">
                    <p class="text-xs" style="color: var(--text-secondary);">Menampilkan 10 aktivitas terbaru dari {{ $activities->count() }} total</p>
                </div>
                @endif
                @else
                <div class="px-4 py-8 text-center" style="color: var(--text-secondary);">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-sm">Belum ada aktivitas tercatat</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column - Stats -->
        <div class="space-y-4">
            <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <p class="text-xs mb-1" style="color: var(--text-secondary);">Bergabung</p>
                <p class="text-sm font-medium" style="color: var(--text-primary);">{{ $user->created_at->format('d M Y') }}</p>
                <p class="text-xs" style="color: var(--text-secondary);">{{ $user->created_at->diffForHumans() }}</p>
            </div>

            <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <p class="text-xs mb-1" style="color: var(--text-secondary);">Total Referral</p>
                <p class="text-2xl font-bold" style="color: var(--accent-color);">{{ $user->referrals->count() }}</p>
            </div>

            <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <p class="text-xs mb-1" style="color: var(--text-secondary);">Total Aktivitas</p>
                <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $activities->count() }}</p>
            </div>

            @can('users.edit')
            <a href="{{ route('users.edit', $user) }}" class="btn btn-outline w-full">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Pengguna
            </a>
            @endcan
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({ toast: true, position: 'bottom-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });
        
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                Toast.fire({
                    icon: 'success',
                    title: 'Link referral berhasil disalin!'
                });
            }).catch(() => {
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal menyalin link'
                });
            });
        }
    </script>
</x-app-layout>

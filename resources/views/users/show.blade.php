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
            @foreach($user->roles as $role)
            <span class="badge {{ $role->name === 'super-admin' ? 'badge-danger' : ($role->name === 'admin' ? 'badge-warning' : 'badge-info') }}">
                {{ ucfirst($role->name) }}
            </span>
            @endforeach
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
                    <p class="text-sm font-medium" style="color: var(--text-primary);">{{ $user->referrer?->name ?? 'User Pertama' }}</p>
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
                <h3 class="text-sm font-semibold mb-3" style="color: var(--text-primary);">User yang Direferensikan ({{ $user->referrals->count() }})</h3>
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
                    <h3 class="text-sm font-semibold" style="color: var(--text-primary);">Aktivitas Terakhir</h3>
                </div>
                <div class="divide-y max-h-64 overflow-y-auto" style="border-color: var(--border-color);">
                    @forelse($activities as $activity)
                    <div class="flex items-start gap-3 px-4 py-3">
                        <span class="badge {{ $activity->action_badge_class }} shrink-0">{{ $activity->action_label }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm truncate" style="color: var(--text-primary);">{{ $activity->description }}</p>
                            <p class="text-xs" style="color: var(--text-secondary);">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="px-4 py-8 text-center" style="color: var(--text-secondary);">
                        <p class="text-sm">Belum ada aktivitas</p>
                    </div>
                    @endforelse
                </div>
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

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text);
        }
    </script>
</x-app-layout>

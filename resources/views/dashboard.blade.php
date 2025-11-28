<x-app-layout title="Dashboard">
    <!-- Compact Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl font-semibold" style="color: var(--text-primary);">Selamat Datang, {{ auth()->user()->name }}</h1>
            <p class="text-sm" style="color: var(--text-secondary);">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        @can('commodities.create')
        <a href="{{ route('commodities.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors" style="background-color: var(--accent-color);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Barang
        </a>
        @endcan
    </div>

    <!-- Stats Cards - Compact -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: var(--bg-input);">
                    <svg class="w-5 h-5" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs" style="color: var(--text-secondary);">Total Barang</p>
                    <p class="text-lg font-bold" style="color: var(--text-primary);">{{ number_format($stats['total_commodities']) }}</p>
                </div>
            </div>
        </div>

        @can('reports.view')
        <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: var(--bg-input);">
                    <svg class="w-5 h-5" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs" style="color: var(--text-secondary);">Total Nilai</p>
                    @php
                        $currency = \App\Helpers\NumberHelper::formatCurrency($stats['total_value']);
                    @endphp
                    <p class="text-lg font-bold cursor-help" style="color: var(--text-primary);" 
                       title="Rp {{ ucwords(trim(\App\Helpers\NumberHelper::terbilang($stats['total_value']))) }} Rupiah">
                       {{ $currency['formatted'] }}
                    </p>
                </div>
            </div>
        </div>
        @else
        <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: var(--bg-input);">
                    <svg class="w-5 h-5" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs" style="color: var(--text-secondary);">Transfer Bulan Ini</p>
                    <p class="text-lg font-bold" style="color: var(--text-primary);">{{ $stats['transfers_this_month'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        @endcan

        <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: var(--bg-input);">
                    <svg class="w-5 h-5" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs" style="color: var(--text-secondary);">Kategori</p>
                    <p class="text-lg font-bold" style="color: var(--text-primary);">{{ number_format($stats['total_categories']) }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: var(--bg-input);">
                    <svg class="w-5 h-5" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs" style="color: var(--text-secondary);">Lokasi</p>
                    <p class="text-lg font-bold" style="color: var(--text-primary);">{{ number_format($stats['total_locations']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts Row - Admin/Manager Only -->
    @canany(['transfers.approve', 'disposals.approve', 'maintenance.view'])
    @if($pendingTransfers > 0 || $pendingDisposals > 0 || $overdueMaintenance > 0 || $upcomingMaintenance > 0)
    <div class="flex flex-wrap gap-2 mb-6">
        @can('transfers.approve')
        @if($pendingTransfers > 0)
        <a href="{{ route('transfers.index', ['status' => 'pending']) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium border transition-colors hover:opacity-80" style="background-color: var(--bg-card); border-color: #fbbf24; color: #d97706;">
            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            {{ $pendingTransfers }} Transfer Pending
        </a>
        @endif
        @endcan

        @can('disposals.approve')
        @if($pendingDisposals > 0)
        <a href="{{ route('disposals.index', ['status' => 'pending']) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium border transition-colors hover:opacity-80" style="background-color: var(--bg-card); border-color: #f87171; color: #dc2626;">
            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
            {{ $pendingDisposals }} Penghapusan Pending
        </a>
        @endif
        @endcan

        @can('maintenance.view')
        @if($overdueMaintenance > 0)
        <a href="{{ route('maintenance.index', ['overdue' => 1]) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium border transition-colors hover:opacity-80" style="background-color: var(--bg-card); border-color: #f87171; color: #dc2626;">
            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
            {{ $overdueMaintenance }} Maintenance Overdue
        </a>
        @endif

        @if($upcomingMaintenance > 0)
        <a href="{{ route('maintenance.index', ['upcoming' => 1]) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium border transition-colors hover:opacity-80" style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-secondary);">
            <span class="w-2 h-2 rounded-full" style="background-color: var(--accent-color);"></span>
            {{ $upcomingMaintenance }} Maintenance Mendatang
        </a>
        @endif
        @endcan
    </div>
    @endif
    @endcanany

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <!-- Kondisi Barang Chart -->
        <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <h3 class="text-sm font-semibold mb-4" style="color: var(--text-primary);">Kondisi Barang</h3>
            <div class="flex items-center gap-6">
                <div class="w-32 h-32 shrink-0">
                    <canvas id="conditionChart"></canvas>
                </div>
                <div class="flex-1 space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <span style="color: var(--text-secondary);">Baik</span>
                        </div>
                        <span class="font-medium" style="color: var(--text-primary);">{{ $conditionStats['baik'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span style="color: var(--text-secondary);">Rusak Ringan</span>
                        </div>
                        <span class="font-medium" style="color: var(--text-primary);">{{ $conditionStats['rusak_ringan'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                            <span style="color: var(--text-secondary);">Rusak Berat</span>
                        </div>
                        <span class="font-medium" style="color: var(--text-primary);">{{ $conditionStats['rusak_berat'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kategori Chart -->
        <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <h3 class="text-sm font-semibold mb-4" style="color: var(--text-primary);">Distribusi per Kategori</h3>
            <div class="h-32">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Barang Terbaru -->
        <div class="rounded-lg border overflow-hidden" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="flex items-center justify-between px-4 py-3 border-b" style="border-color: var(--border-color);">
                <h3 class="text-sm font-semibold" style="color: var(--text-primary);">Barang Terbaru</h3>
                <a href="{{ route('commodities.index') }}" class="text-xs hover:underline" style="color: var(--accent-color);">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead style="background-color: var(--bg-input);">
                        <tr>
                            <th class="px-2 py-2 text-center font-medium w-8" style="color: var(--text-secondary);">No</th>
                            <th class="px-3 py-2 text-left font-medium" style="color: var(--text-secondary);">Kode</th>
                            <th class="px-3 py-2 text-left font-medium" style="color: var(--text-secondary);">Nama</th>
                            <th class="px-3 py-2 text-left font-medium" style="color: var(--text-secondary);">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color: var(--border-color);">
                        @forelse($recentCommodities as $index => $commodity)
                        <tr>
                            <td class="px-2 py-2 text-center font-medium" style="color: var(--text-secondary);">{{ $index + 1 }}</td>
                            <td class="px-3 py-2 font-mono" style="color: var(--text-secondary);">{{ $commodity->item_code }}</td>
                            <td class="px-3 py-2">
                                <a href="{{ route('commodities.show', $commodity) }}" class="hover:underline" style="color: var(--accent-color);">
                                    {{ Str::limit($commodity->name, 20) }}
                                </a>
                            </td>
                            <td class="px-3 py-2"><span class="badge {{ $commodity->condition_badge_class }}">{{ $commodity->condition_label }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-3 py-6 text-center" style="color: var(--text-secondary);">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Aktivitas Terbaru - Admin Only -->
        @can('reports.view')
        <div class="rounded-lg border overflow-hidden" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="px-4 py-3 border-b" style="border-color: var(--border-color);">
                <h3 class="text-sm font-semibold" style="color: var(--text-primary);">Aktivitas Terbaru</h3>
            </div>
            <div class="p-3 space-y-2 max-h-64 overflow-y-auto">
                @forelse($recentActivities as $activity)
                <div class="flex items-start gap-2 p-2 rounded text-xs" style="background-color: var(--bg-input);">
                    <img src="{{ $activity->user?->avatar_url ?? 'https://ui-avatars.com/api/?name=System&size=24' }}" 
                         class="w-6 h-6 rounded-full object-cover shrink-0" alt="">
                    <div class="flex-1 min-w-0">
                        <p style="color: var(--text-primary);">
                            <span class="font-medium">{{ $activity->user?->name ?? 'System' }}</span>
                            <span class="badge {{ $activity->action_badge_class }} ml-1">{{ $activity->action_label }}</span>
                        </p>
                        <p class="truncate" style="color: var(--text-secondary);">{{ $activity->description }}</p>
                        <p style="color: var(--text-secondary); opacity: 0.6;">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-6 text-xs" style="color: var(--text-secondary);">Belum ada aktivitas</div>
                @endforelse
            </div>
        </div>
        @else
        <!-- Quick Links for Regular Users -->
        <div class="rounded-lg border overflow-hidden" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="px-4 py-3 border-b" style="border-color: var(--border-color);">
                <h3 class="text-sm font-semibold" style="color: var(--text-primary);">Akses Cepat</h3>
            </div>
            <div class="p-3 grid grid-cols-2 gap-2">
                @can('commodities.view')
                <a href="{{ route('commodities.index') }}" class="p-3 rounded-lg text-center hover:opacity-80 transition-opacity" style="background-color: var(--bg-input);">
                    <svg class="w-6 h-6 mx-auto mb-1" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <p class="text-xs font-medium" style="color: var(--text-primary);">Barang</p>
                </a>
                @endcan
                @can('transfers.view')
                <a href="{{ route('transfers.index') }}" class="p-3 rounded-lg text-center hover:opacity-80 transition-opacity" style="background-color: var(--bg-input);">
                    <svg class="w-6 h-6 mx-auto mb-1" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    <p class="text-xs font-medium" style="color: var(--text-primary);">Transfer</p>
                </a>
                @endcan
                @can('locations.view')
                <a href="{{ route('locations.index') }}" class="p-3 rounded-lg text-center hover:opacity-80 transition-opacity" style="background-color: var(--bg-input);">
                    <svg class="w-6 h-6 mx-auto mb-1" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    <p class="text-xs font-medium" style="color: var(--text-primary);">Lokasi</p>
                </a>
                @endcan
                @can('categories.view')
                <a href="{{ route('categories.index') }}" class="p-3 rounded-lg text-center hover:opacity-80 transition-opacity" style="background-color: var(--bg-input);">
                    <svg class="w-6 h-6 mx-auto mb-1" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <p class="text-xs font-medium" style="color: var(--text-primary);">Kategori</p>
                </a>
                @endcan
            </div>
        </div>
        @endcan
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SweetAlert success notifications
            @if(session('success'))
                @if(str_contains(session('success'), 'Setup keamanan berhasil'))
                    // Special welcome for security setup completion
                    Swal.fire({
                        icon: 'success',
                        title: '🎉 Setup Keamanan Berhasil!',
                        html: 
                            '<div class="text-left">' +
                            '<p class="mb-2"><strong>Selamat datang di Dashboard!</strong></p>' +
                            '<p class="text-sm text-gray-600">Akun Anda sekarang sudah aman dan siap digunakan.</p>' +
                            '<p class="text-sm text-blue-600 mt-2"><em>Anda dapat mulai mengelola inventaris sekarang.</em></p>' +
                            '</div>',
                        timer: 6000,
                        showConfirmButton: true,
                        confirmButtonColor: '#10b981',
                        confirmButtonText: 'Mulai!',
                        showClass: {
                            popup: 'animate__animated animate__bounceIn'
                        }
                    });
                @else
                    // Regular success messages
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        timer: 4000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    });
                @endif
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    timer: 5000,
                    showConfirmButton: true,
                    confirmButtonColor: '#dc2626'
                });
            @endif

            // Kondisi Chart - Doughnut
            const conditionCtx = document.getElementById('conditionChart');
            if (conditionCtx) {
                new Chart(conditionCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Baik', 'Rusak Ringan', 'Rusak Berat'],
                        datasets: [{
                            data: [{{ $conditionStats['baik'] }}, {{ $conditionStats['rusak_ringan'] }}, {{ $conditionStats['rusak_berat'] }}],
                            backgroundColor: ['#10b981', '#f59e0b', '#f43f5e'],
                            borderWidth: 0,
                            cutout: '70%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }

            // Kategori Chart - Horizontal Bar
            const categoryCtx = document.getElementById('categoryChart');
            if (categoryCtx) {
                new Chart(categoryCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($commoditiesByCategory->pluck('name')->map(fn($n) => Str::limit($n, 15))) !!},
                        datasets: [{
                            data: {!! json_encode($commoditiesByCategory->pluck('commodities_count')) !!},
                            backgroundColor: 'rgba(var(--accent-rgb, 59, 130, 246), 0.7)',
                            borderRadius: 4,
                            barThickness: 16
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: { display: false },
                                ticks: { 
                                    color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim() || '#6b7280',
                                    font: { size: 10 }
                                }
                            },
                            y: {
                                grid: { display: false },
                                ticks: { 
                                    color: getComputedStyle(document.documentElement).getPropertyValue('--text-secondary').trim() || '#6b7280',
                                    font: { size: 10 }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>

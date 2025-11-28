<x-app-layout title="Laporan Berdasarkan Kondisi">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('reports.index') }}" class="text-sm hover:underline flex items-center gap-1" style="color: var(--text-secondary);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Laporan
            </a>
            <h2 class="text-2xl font-bold mt-2" style="color: var(--text-primary);">Laporan Berdasarkan Kondisi Barang</h2>
        </div>

        <!-- Condition Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold mb-1 text-green-600">Kondisi Baik</h3>
                        <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $conditionStats['baik'] ?? 0 }}</p>
                        <p class="text-sm" style="color: var(--text-secondary);">{{ number_format(($conditionStats['baik'] ?? 0) / max(array_sum($conditionStats ?? []), 1) * 100, 1) }}% dari total</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold mb-1 text-yellow-600">Rusak Ringan</h3>
                        <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $conditionStats['rusak_ringan'] ?? 0 }}</p>
                        <p class="text-sm" style="color: var(--text-secondary);">{{ number_format(($conditionStats['rusak_ringan'] ?? 0) / max(array_sum($conditionStats ?? []), 1) * 100, 1) }}% dari total</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold mb-1 text-red-600">Rusak Berat</h3>
                        <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $conditionStats['rusak_berat'] ?? 0 }}</p>
                        <p class="text-sm" style="color: var(--text-secondary);">{{ number_format(($conditionStats['rusak_berat'] ?? 0) / max(array_sum($conditionStats ?? []), 1) * 100, 1) }}% dari total</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Table by Category -->
        <div class="theme-card rounded-xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Detail Kondisi per Kategori</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b" style="border-color: var(--border-color);">
                                <th class="text-center py-3 px-4 font-medium w-16" style="color: var(--text-primary);">No</th>
                                <th class="text-left py-3 px-4 font-medium" style="color: var(--text-primary);">Kategori</th>
                                <th class="text-center py-3 px-4 font-medium" style="color: var(--text-primary);">Total Barang</th>
                                <th class="text-center py-3 px-4 font-medium text-green-600">Baik</th>
                                <th class="text-center py-3 px-4 font-medium text-yellow-600">Rusak Ringan</th>
                                <th class="text-center py-3 px-4 font-medium text-red-600">Rusak Berat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categoryConditions ?? [] as $index => $category)
                            <tr class="border-b hover:opacity-80 transition-opacity" style="border-color: var(--border-color);">
                                <td class="py-3 px-4 text-center font-medium" style="color: var(--text-secondary);">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-medium" style="color: var(--text-primary);">{{ $category->name }}</td>
                                <td class="py-3 px-4 text-center" style="color: var(--text-secondary);">{{ $category->total_items }}</td>
                                <td class="py-3 px-4 text-center text-green-600 font-medium">{{ $category->baik }}</td>
                                <td class="py-3 px-4 text-center text-yellow-600 font-medium">{{ $category->rusak_ringan }}</td>
                                <td class="py-3 px-4 text-center text-red-600 font-medium">{{ $category->rusak_berat }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center" style="color: var(--text-secondary);">Belum ada data kondisi barang</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

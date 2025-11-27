<x-app-layout title="Penghapusan Barang">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Penghapusan Barang</h2>
            <p class="text-sm text-gray-500">Kelola pengajuan penghapusan barang inventaris</p>
        </div>

        @can('disposals.create')
        <button onclick="openModal('modal-create-disposal')" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Penghapusan
        </button>
        @endcan
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form action="{{ route('disposals.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <x-form.input name="search" placeholder="Cari nomor/barang..." :value="request('search')" />
                <x-form.select name="status" placeholder="Semua Status" :value="request('status')" :options="[
                    'pending' => 'Pending',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'completed' => 'Selesai'
                ]" />
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary flex-1">Filter</button>
                    <a href="{{ route('disposals.index') }}" class="btn btn-outline">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-12">No</th>
                        <th>No. Pengajuan</th>
                        <th>Barang</th>
                        <th>Alasan</th>
                        <th>Nilai Sisa</th>
                        <th>Pengaju</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disposals as $index => $disposal)
                    <tr>
                        <td class="text-gray-500">{{ $disposals->firstItem() + $index }}</td>
                        <td class="font-mono text-xs">{{ $disposal->disposal_number }}</td>
                        <td>
                            <a href="{{ route('commodities.show', $disposal->commodity) }}" class="text-primary-600 hover:underline">
                                {{ Str::limit($disposal->commodity->name, 25) }}
                            </a>
                        </td>
                        <td>{{ $disposal->reason_label }}</td>
                        <td>{{ $disposal->formatted_value }}</td>
                        <td>{{ $disposal->requester->name }}</td>
                        <td><span class="badge {{ $disposal->status_badge_class }}">{{ $disposal->status_label }}</span></td>
                        <td class="text-gray-500">{{ $disposal->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('disposals.show', $disposal) }}" class="btn btn-sm btn-outline">Detail</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-gray-500 py-8">Belum ada pengajuan penghapusan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($disposals->hasPages())
        <div class="card-footer">
            <x-pagination :paginator="$disposals" />
        </div>
        @endif
    </div>

    <!-- Create Disposal Modal -->
    <x-modal name="modal-create-disposal" title="Ajukan Penghapusan Barang" maxWidth="4xl">
        <form action="{{ route('disposals.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <x-form.select label="Pilih Barang" name="commodity_id" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($commodities ?? [] as $commodity)
                        <option value="{{ $commodity->id }}" {{ old('commodity_id') == $commodity->id ? 'selected' : '' }}>
                            {{ $commodity->item_code }} - {{ $commodity->name }} ({{ $commodity->condition_label }})
                        </option>
                        @endforeach
                    </x-form.select>

                    <x-form.select label="Alasan Penghapusan" name="reason" required :options="[
                        'rusak_berat' => 'Rusak Berat / Tidak Dapat Diperbaiki',
                        'hilang' => 'Hilang',
                        'usang' => 'Usang / Tidak Layak Pakai',
                        'dicuri' => 'Dicuri',
                        'dijual' => 'Dijual',
                        'dihibahkan' => 'Dihibahkan',
                        'lainnya' => 'Lainnya'
                    ]" />

                    <x-form.input label="Taksiran Nilai Sisa (Rp)" name="estimated_value" type="number" min="0" placeholder="0" helper="Perkiraan nilai barang saat ini" />
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <x-form.textarea label="Justifikasi" name="description" required rows="6" placeholder="Jelaskan alasan detail mengapa barang ini harus dihapus dari inventaris..." />

                    <x-form.textarea label="Catatan Tambahan" name="notes" rows="4" placeholder="Catatan tambahan (opsional)" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('modal-create-disposal')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-danger">Ajukan Penghapusan</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>

@section('meta-description', 'Sistem transfer barang inventaris antar lokasi. Proses pengajuan, persetujuan, dan tracking perpindahan aset dengan workflow yang terstruktur.')
<x-app-layout title="Transfer Barang">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Transfer Barang</h2>
            <p class="text-sm text-gray-500">Kelola perpindahan barang antar lokasi</p>
        </div>

        @can('transfers.create')
        <button onclick="openModal('modal-create-transfer')" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Transfer
        </button>
        @endcan
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form action="{{ route('transfers.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <x-form.input name="search" placeholder="Cari nomor transfer..." :value="request('search')" />
                <x-form.select name="status" placeholder="Semua Status" :value="request('status')" :options="[
                    'pending' => 'Pending',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan'
                ]" />
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary flex-1">Filter</button>
                    <a href="{{ route('transfers.index') }}" class="btn btn-outline">Reset</a>
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
                        <th>No. Transfer</th>
                        <th>Barang</th>
                        <th>Dari</th>
                        <th>Ke</th>
                        <th>Pengaju</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $index => $transfer)
                    <tr>
                        <td class="text-gray-600">{{ $transfers->firstItem() + $index }}</td>
                        <td class="font-mono text-xs">{{ $transfer->transfer_number }}</td>
                        <td>
                            @if($transfer->commodity)
                            <div class="flex items-center gap-3">
                                @php
                                    $primaryImage = $transfer->commodity->images()->where('is_primary', true)->first();
                                @endphp
                                @if($primaryImage)
                                <img src="{{ Storage::url($primaryImage->image_path) }}" 
                                     alt="{{ $transfer->commodity->name }}" 
                                     class="w-10 h-10 rounded-lg object-cover">
                                @else
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('commodities.show', $transfer->commodity) }}" class="text-primary-600 hover:underline font-medium">
                                        {{ Str::limit($transfer->commodity->name, 25) }}
                                    </a>
                                    <p class="text-xs text-gray-500 font-mono">{{ $transfer->commodity->item_code }}</p>
                                </div>
                            </div>
                            @else
                            <span class="text-gray-400">Barang tidak tersedia</span>
                            @endif
                        </td>
                        <td class="text-gray-600">{{ $transfer->fromLocation->name }}</td>
                        <td class="text-gray-600">{{ $transfer->toLocation->name }}</td>
                        <td>{{ $transfer->requester->name }}</td>
                        <td><span class="badge {{ $transfer->status_badge_class }}">{{ $transfer->status_label }}</span></td>
                        <td class="text-gray-600">{{ $transfer->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('transfers.show', $transfer) }}" class="btn btn-sm btn-outline">Detail</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-gray-600 py-8">Belum ada data transfer</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transfers->hasPages())
        <div class="card-footer">
            <x-pagination :paginator="$transfers" />
        </div>
        @endif
    </div>

    <!-- Create Transfer Modal -->
    <x-modal name="modal-create-transfer" title="Ajukan Transfer Barang" maxWidth="4xl">
        <form action="{{ route('transfers.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <x-form.select label="Pilih Barang" name="commodity_id" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($commodities ?? [] as $commodity)
                        <option value="{{ $commodity->id }}" {{ old('commodity_id') == $commodity->id ? 'selected' : '' }}>
                            {{ $commodity->item_code }} - {{ $commodity->name }} ({{ $commodity->location->name }})
                        </option>
                        @endforeach
                    </x-form.select>

                    <x-form.select label="Lokasi Tujuan" name="to_location_id" required>
                        <option value="">-- Pilih Lokasi Tujuan --</option>
                        @foreach($locations ?? [] as $location)
                        <option value="{{ $location->id }}" {{ old('to_location_id') == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                        @endforeach
                    </x-form.select>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <x-form.textarea label="Alasan Transfer" name="reason" required rows="3" placeholder="Jelaskan alasan perpindahan barang ini..." />

                    <x-form.textarea label="Catatan Tambahan" name="notes" rows="3" placeholder="Catatan tambahan (opsional)" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('modal-create-transfer')" class="btn btn-outline">Batal</button>
                <button type="submit" class="btn btn-primary">Ajukan Transfer</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>

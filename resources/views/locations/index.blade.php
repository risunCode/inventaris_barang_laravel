@section('meta-description', 'Kelola lokasi penyimpanan barang inventaris. Tracking gedung, lantai, ruangan, dan PIC untuk manajemen aset yang terstruktur.')
<x-app-layout title="Lokasi">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Daftar Lokasi</h2>
            <p class="text-sm text-gray-600">Kelola lokasi penyimpanan barang</p>
        </div>

        @can('locations.create')
        <button onclick="openCreateModal()" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Lokasi
        </button>
        @endcan
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form action="{{ route('locations.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <x-form.input name="search" placeholder="Cari nama/kode..." :value="request('search')" />

                <x-form.select name="building" placeholder="Semua Gedung" :value="request('building')">
                    @foreach($buildings as $building)
                    <option value="{{ $building }}" {{ request('building') == $building ? 'selected' : '' }}>{{ $building }}</option>
                    @endforeach
                </x-form.select>

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary flex-1">Filter</button>
                    <a href="{{ route('locations.index') }}" class="btn btn-outline">Reset</a>
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
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Gedung</th>
                        <th>Lantai/Ruang</th>
                        <th>Jumlah Barang</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $index => $location)
                    <tr>
                        <td class="text-gray-500">{{ $locations->firstItem() + $index }}</td>
                        <td class="font-mono">{{ $location->code }}</td>
                        <td class="font-medium">{{ $location->name }}</td>
                        <td class="text-gray-500">{{ $location->building ?? '-' }}</td>
                        <td class="text-gray-500">{{ $location->floor }} {{ $location->room }}</td>
                        <td>{{ $location->commodities_count }}</td>
                        <td>
                            @if($location->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-gray">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex justify-end gap-1">
                                @can('locations.edit')
                                <button onclick="openEditModal({{ json_encode($location) }})" class="p-1.5 rounded hover:bg-gray-100" title="Edit">
                                    <svg class="w-4 h-4" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @endcan

                                @can('locations.delete')
                                <button onclick="deleteLocation({{ $location->id }}, '{{ $location->name }}')" class="p-1.5 rounded hover:bg-red-50" title="Hapus">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-gray-500 py-8">Belum ada data lokasi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($locations->hasPages())
        <div class="card-footer">
            <x-pagination :paginator="$locations" />
        </div>
        @endif
    </div>
    <!-- Create Modal -->
    <x-modal name="createModal" title="Tambah Lokasi Baru" maxWidth="2xl">
        <form id="createForm" action="{{ route('locations.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Nama Lokasi <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="createName" class="input w-full" autocomplete="organization" required placeholder="Contoh: Ruang Server Lt.2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Kode Lokasi <span class="text-xs" style="color: var(--text-secondary);">(otomatis jika kosong)</span></label>
                    <input type="text" name="code" id="createCode" class="input w-full" autocomplete="off" placeholder="LOK-001">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">PIC (Person in Charge)</label>
                    <input type="text" name="pic" id="createPic" class="input w-full" autocomplete="name" placeholder="Nama penanggung jawab lokasi">
                </div>
                <div class="flex items-center pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" id="createIsActive" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        <span class="ms-3 text-sm font-medium" style="color: var(--text-primary);">Status Aktif</span>
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Deskripsi</label>
                    <textarea name="description" id="createDescription" class="input w-full" rows="3" autocomplete="off" placeholder="Keterangan tentang lokasi ini..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Alamat Lengkap</label>
                    <textarea name="address" id="createAddress" class="input w-full" rows="3" autocomplete="street-address" placeholder="Jl. Contoh No. 123, Kota..."></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('createModal')" class="btn btn-outline flex-1">Batal</button>
                <button type="submit" class="btn btn-primary flex-1">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Modal -->
    <x-modal name="editModal" title="Edit Lokasi" maxWidth="2xl">
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Nama Lokasi <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="editName" class="input w-full" autocomplete="organization" required placeholder="Nama lokasi">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Kode Lokasi</label>
                    <input type="text" name="code" id="editCode" class="input w-full" autocomplete="off" placeholder="LOK-001">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">PIC (Person in Charge)</label>
                    <input type="text" name="pic" id="editPic" class="input w-full" autocomplete="name" placeholder="Nama penanggung jawab lokasi">
                </div>
                <div class="flex items-center pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" id="editIsActive" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        <span class="ms-3 text-sm font-medium" style="color: var(--text-primary);">Status Aktif</span>
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Deskripsi</label>
                    <textarea name="description" id="editDescription" class="input w-full" rows="3" autocomplete="off" placeholder="Keterangan tentang lokasi ini..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Alamat Lengkap</label>
                    <textarea name="address" id="editAddress" class="input w-full" rows="3" autocomplete="street-address" placeholder="Jl. Contoh No. 123, Kota..."></textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('editModal')" class="btn btn-outline flex-1">Batal</button>
                <button type="submit" class="btn btn-primary flex-1">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Update
                </button>
            </div>
        </form>
    </x-modal>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });

        function openCreateModal() {
            document.getElementById('createForm').reset();
            document.getElementById('createIsActive').checked = true;
            openModal('createModal');
        }

        function openEditModal(location) {
            document.getElementById('editForm').action = `/master/locations/${location.id}`;
            document.getElementById('editName').value = location.name || '';
            document.getElementById('editCode').value = location.code || '';
            document.getElementById('editDescription').value = location.description || '';
            document.getElementById('editAddress').value = location.address || '';
            document.getElementById('editPic').value = location.pic || '';
            document.getElementById('editIsActive').checked = location.is_active;
            openModal('editModal');
        }

        async function deleteLocation(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Lokasi?',
                html: `Yakin ingin menghapus <strong>${name}</strong>?<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });
            
            if (!result.isConfirmed) return;
            
            try {
                const response = await fetch(`/master/locations/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                
                const data = await response.json();
                
                if (response.ok && data.success !== false) {
                    Toast.fire({ icon: 'success', title: 'Lokasi berhasil dihapus!' });
                    setTimeout(() => location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Gagal menghapus lokasi');
                }
            } catch (error) {
                console.error('Delete error:', error);
                Swal.fire({ icon: 'error', title: 'Gagal!', text: error.message || 'Terjadi kesalahan' });
            }
        }

        @if(session()->has('success') && session('success'))
        Toast.fire({ icon: 'success', title: '{{ session("success") }}' });
        @endif
    </script>
</x-app-layout>

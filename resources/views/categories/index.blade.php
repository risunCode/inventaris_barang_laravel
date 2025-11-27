@section('meta-description', 'Kelola kategori barang inventaris dengan sistem hierarki. Organisir aset perusahaan berdasarkan jenis dan fungsi untuk tracking yang lebih efisien.')
<x-app-layout title="Kategori">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Daftar Kategori</h2>
            <p class="text-sm text-gray-600">Kelola kategori barang inventaris</p>
        </div>

        @can('categories.create')
        <button onclick="openCreateModal()" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kategori
        </button>
        @endcan
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form action="{{ route('categories.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <x-form.input 
                    name="search" 
                    placeholder="Cari nama atau kode..." 
                    :value="request('search')"
                />

                <x-form.select 
                    name="parent_id" 
                    placeholder="Semua Parent"
                    :value="request('parent_id')"
                >
                    <option value="root" {{ request('parent_id') === 'root' ? 'selected' : '' }}>Kategori Utama</option>
                    @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                    @endforeach
                </x-form.select>

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary flex-1">Filter</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline">Reset</a>
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
                        <th>Parent</th>
                        <th>Jumlah Barang</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                    <tr>
                        <td class="text-gray-500">{{ $categories->firstItem() + $index }}</td>
                        <td class="font-mono">{{ $category->code }}</td>
                        <td class="font-medium">{{ $category->name }}</td>
                        <td class="text-gray-500">{{ $category->parent?->name ?? '-' }}</td>
                        <td>{{ $category->commodities_count }}</td>
                        <td>
                            @if($category->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-gray">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex justify-end gap-1">
                                @can('categories.edit')
                                <button onclick="openEditModal({{ json_encode($category) }})" class="p-1.5 rounded hover:bg-gray-100" title="Edit">
                                    <svg class="w-4 h-4" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @endcan

                                @can('categories.delete')
                                <button onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')" class="p-1.5 rounded hover:bg-red-50" title="Hapus">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <p>Belum ada data kategori</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
        <div class="card-footer">
            <x-pagination :paginator="$categories" />
        </div>
        @endif
    </div>

    <!-- Create Modal -->
    <x-modal name="createModal" title="Tambah Kategori Baru" maxWidth="2xl">
        <form id="createForm" action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="createName" class="input w-full" autocomplete="organization" required placeholder="Contoh: Elektronik">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Kode Kategori <span class="text-xs" style="color: var(--text-secondary);">(otomatis jika kosong)</span></label>
                    <input type="text" name="code" id="createCode" class="input w-full" autocomplete="off" placeholder="KAT-001">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Parent Kategori</label>
                    <select name="parent_id" id="createParentId" class="input w-full">
                        <option value="">-- Tidak ada (Kategori Utama) --</option>
                        @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" id="createIsActive" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        <span class="ms-3 text-sm font-medium" style="color: var(--text-primary);">Status Aktif</span>
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Deskripsi</label>
                    <textarea name="description" id="createDescription" class="input w-full" rows="3" autocomplete="off" placeholder="Keterangan tentang kategori ini..."></textarea>
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
    <x-modal name="editModal" title="Edit Kategori" maxWidth="2xl">
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="editName" class="input w-full" autocomplete="organization" required placeholder="Nama kategori">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Kode Kategori</label>
                    <input type="text" name="code" id="editCode" class="input w-full" autocomplete="off" placeholder="KAT-001">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Parent Kategori</label>
                    <select name="parent_id" id="editParentId" class="input w-full">
                        <option value="">-- Tidak ada (Kategori Utama) --</option>
                        @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" id="editIsActive" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        <span class="ms-3 text-sm font-medium" style="color: var(--text-primary);">Status Aktif</span>
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Deskripsi</label>
                    <textarea name="description" id="editDescription" class="input w-full" rows="3" autocomplete="off" placeholder="Keterangan tentang kategori ini..."></textarea>
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

        function openEditModal(category) {
            document.getElementById('editForm').action = `/master/categories/${category.id}`;
            document.getElementById('editName').value = category.name || '';
            document.getElementById('editCode').value = category.code || '';
            document.getElementById('editDescription').value = category.description || '';
            document.getElementById('editParentId').value = category.parent_id || '';
            document.getElementById('editIsActive').checked = category.is_active;
            openModal('editModal');
        }

        async function deleteCategory(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Kategori?',
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
                const response = await fetch(`/master/categories/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                
                const data = await response.json();
                
                if (response.ok && data.success !== false) {
                    Toast.fire({ icon: 'success', title: 'Kategori berhasil dihapus!' });
                    setTimeout(() => location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Gagal menghapus kategori');
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

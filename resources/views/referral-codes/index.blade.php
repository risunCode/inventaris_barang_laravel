<x-app-layout title="Kode Referral">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold" style="color: var(--text-primary);">Kelola Kode Referral</h1>
            <p class="text-sm" style="color: var(--text-secondary);">
                @can('referral-codes.manage')
                    Buat dan kelola kode referral untuk pendaftaran user baru
                @else
                    Buat dan kelola kode referral Anda sendiri
                @endcan
            </p>
        </div>
        @can('referral-codes.create')
        <button onclick="openCreateModal()" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Kode Baru
        </button>
        @endcan
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <p class="text-xs" style="color: var(--text-secondary);">Total Kode</p>
            <p class="text-xl font-bold" style="color: var(--text-primary);">{{ $stats['total'] }}</p>
        </div>
        <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <p class="text-xs" style="color: var(--text-secondary);">Kode Aktif</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="rounded-lg border p-4" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <p class="text-xs" style="color: var(--text-secondary);">Total Penggunaan</p>
            <p class="text-xl font-bold" style="color: var(--accent-color);">{{ $stats['total_used'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-lg border p-4 mb-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <form action="{{ route('referral-codes.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-form.input name="search" placeholder="Cari kode..." :value="request('search')" />
            <x-form.select name="status" placeholder="Semua Status" :value="request('status')" :options="['active' => 'Aktif', 'inactive' => 'Nonaktif', 'expired' => 'Kadaluarsa']" />
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary flex-1">Filter</button>
                <a href="{{ route('referral-codes.index') }}" class="btn btn-outline">Reset</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="rounded-lg border overflow-hidden" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead style="background-color: var(--bg-input);">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium" style="color: var(--text-secondary);">Kode</th>
                        <th class="px-4 py-3 text-left font-medium" style="color: var(--text-secondary);">Deskripsi</th>
                        <th class="px-4 py-3 text-left font-medium" style="color: var(--text-secondary);">Role</th>
                        <th class="px-4 py-3 text-left font-medium" style="color: var(--text-secondary);">Penggunaan</th>
                        <th class="px-4 py-3 text-left font-medium" style="color: var(--text-secondary);">Masa Aktif</th>
                        <th class="px-4 py-3 text-left font-medium" style="color: var(--text-secondary);">Status</th>
                        <th class="px-4 py-3 text-left font-medium" style="color: var(--text-secondary);">Dibuat</th>
                        <th class="px-4 py-3 text-right font-medium" style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--border-color);">
                    @forelse($referralCodes as $code)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <code class="font-mono text-xs px-2 py-1 rounded" style="background-color: var(--bg-input); color: var(--text-primary);">{{ $code->code }}</code>
                                <button type="button" onclick="copyCode('{{ $code->code }}')" class="text-gray-400 hover:text-gray-600" title="Salin">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                            </div>
                        </td>
                        <td class="px-4 py-3" style="color: var(--text-secondary);">{{ $code->description ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $roleColors = [
                                    'admin' => 'bg-red-100 text-red-800',
                                    'staff' => 'bg-blue-100 text-blue-800', 
                                    'user' => 'bg-gray-100 text-gray-800'
                                ];
                            @endphp
                            <span class="badge {{ $roleColors[$code->role ?? 'user'] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($code->role ?? 'user') }}
                            </span>
                        </td>
                        <td class="px-4 py-3" style="color: var(--text-primary);">
                            {{ $code->used_count }}{{ $code->max_uses ? ' / ' . $code->max_uses : '' }}
                        </td>
                        <td class="px-4 py-3" style="color: var(--text-secondary);">
                            @if($code->expires_at)
                                {{ $code->expires_at->format('d M Y') }}
                                @if($code->expires_at->isPast())
                                    <span class="text-xs text-red-500">(Expired)</span>
                                @endif
                            @else
                                <span class="text-xs">Unlimited</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge {{ $code->status_badge_class }}">{{ $code->status_label }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs" style="color: var(--text-secondary);">
                            {{ $code->creator->name }}<br>
                            {{ $code->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-1">
                                @can('referral-codes.manage')
                                    <!-- Admin: Can manage all codes -->
                                    <button onclick="openEditModal({{ json_encode($code) }})" class="p-1.5 rounded hover:bg-gray-100" title="Edit">
                                        <svg class="w-4 h-4" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button onclick="deleteCode({{ $code->id }})" class="p-1.5 rounded hover:bg-red-50" title="Hapus">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @else
                                    <!-- User: Can only manage own codes -->
                                    @if($code->created_by === auth()->id())
                                        <button onclick="openEditModal({{ json_encode($code) }})" class="p-1.5 rounded hover:bg-gray-100" title="Edit">
                                            <svg class="w-4 h-4" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button onclick="deleteCode({{ $code->id }})" class="p-1.5 rounded hover:bg-red-50" title="Hapus">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @else
                                        <!-- Not owner - no actions -->
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center" style="color: var(--text-secondary);">
                            Belum ada kode referral. Klik tombol "Buat Kode Baru" untuk membuat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($referralCodes->hasPages())
        <div class="p-4 border-t" style="border-color: var(--border-color);">
            <x-pagination :paginator="$referralCodes" />
        </div>
        @endif
    </div>

    <!-- Create Modal -->
    <div id="createModal-backdrop" class="modal-backdrop" style="display:none;"></div>
    <div id="createModal" class="modal-content w-full max-w-md rounded-xl p-6" style="display:none; background-color: var(--bg-card);">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Buat Kode Referral</h3>
            <button onclick="closeModal('createModal')" class="p-1 rounded hover:bg-gray-100">
                <svg class="w-5 h-5" style="color: var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="createForm" onsubmit="submitCreate(event)">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Kode <span class="text-xs text-gray-400">(opsional, kosongkan untuk auto-generate)</span></label>
                    <div class="flex gap-2">
                        <input type="text" name="code" id="createCode" class="input flex-1" placeholder="Contoh: WELCOME2024" maxlength="20" style="text-transform: uppercase;">
                        <button type="button" onclick="generateCode('createCode')" class="btn btn-outline">Generate</button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Deskripsi</label>
                    <input type="text" name="description" class="input" placeholder="Contoh: Promo Tahun Baru">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Role yang Diberikan</label>
                    <select name="role" class="input w-full">
                        @if(auth()->user()->role === 'admin')
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="user" selected>User (Default)</option>
                        @elseif(auth()->user()->role === 'staff')
                            <option value="staff">Staff</option>
                            <option value="user" selected>User (Default)</option>
                        @else
                            <option value="user" selected>User</option>
                        @endif
                    </select>
                    @if(auth()->user()->role === 'admin')
                        <p class="text-xs mt-1" style="color: var(--text-secondary);">Admin dapat membuat kode untuk semua role</p>
                    @elseif(auth()->user()->role === 'staff')
                        <p class="text-xs mt-1" style="color: var(--text-secondary);">Staff dapat membuat kode untuk staff dan user</p>
                    @else
                        <p class="text-xs mt-1" style="color: var(--text-secondary);">User dapat membuat kode untuk user</p>
                    @endif
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Maks. Penggunaan</label>
                        <input type="number" name="max_uses" class="input" placeholder="Unlimited" min="1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Kadaluarsa</label>
                        <input type="date" name="expires_at" class="input" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                               max="{{ date('Y-m-d', strtotime('+5 years')) }}">
                        <p class="text-xs mt-1" style="color: var(--text-secondary);">Maksimal 5 tahun dari sekarang</p>
                    </div>
                </div>
            </div>
            <div class="flex gap-2 mt-6">
                <button type="button" onclick="closeModal('createModal')" class="btn btn-outline flex-1">Batal</button>
                <button type="submit" class="btn btn-primary flex-1">Simpan</button>
            </div>
        </form>
    </div>

    <!-- Edit Modal -->
    <div id="editModal-backdrop" class="modal-backdrop" style="display:none;"></div>
    <div id="editModal" class="modal-content w-full max-w-md rounded-xl p-6" style="display:none; background-color: var(--bg-card);">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Edit Kode Referral</h3>
            <button onclick="closeModal('editModal')" class="p-1 rounded hover:bg-gray-100">
                <svg class="w-5 h-5" style="color: var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="editForm" onsubmit="submitEdit(event)">
            <input type="hidden" name="id" id="editId">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Kode</label>
                    <input type="text" id="editCode" class="input" disabled style="background-color: var(--bg-input);">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Deskripsi</label>
                    <input type="text" name="description" id="editDescription" class="input" placeholder="Contoh: Promo Tahun Baru">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Role yang Diberikan</label>
                    <select name="role" id="editRole" class="input w-full">
                        @if(auth()->user()->role === 'admin')
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="user">User (Default)</option>
                        @elseif(auth()->user()->role === 'staff')
                            <option value="staff">Staff</option>
                            <option value="user">User (Default)</option>
                        @else
                            <option value="user">User</option>
                        @endif
                    </select>
                    @if(auth()->user()->role === 'admin')
                        <p class="text-xs mt-1" style="color: var(--text-secondary);">Admin dapat mengubah role untuk semua level</p>
                    @elseif(auth()->user()->role === 'staff')
                        <p class="text-xs mt-1" style="color: var(--text-secondary);">Staff dapat mengubah role untuk staff dan user</p>
                    @else
                        <p class="text-xs mt-1" style="color: var(--text-secondary);">User dapat membuat kode untuk user</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Status Kode</label>
                    <div class="flex items-center gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" id="editIsActive" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm" style="color: var(--text-primary);">Kode Aktif</span>
                        </label>
                        <p class="text-xs" style="color: var(--text-secondary);">Nonaktif = Tidak bisa digunakan registrasi</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Maks. Penggunaan</label>
                        <input type="number" name="max_uses" id="editMaxUses" class="input" placeholder="Unlimited" min="1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Kadaluarsa</label>
                        <input type="date" name="expires_at" id="editExpiresAt" class="input"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                               max="{{ date('Y-m-d', strtotime('+5 years')) }}">
                        <p class="text-xs mt-1" style="color: var(--text-secondary);">Maksimal 5 tahun dari sekarang</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="editIsActive" class="rounded">
                    <label for="editIsActive" class="text-sm" style="color: var(--text-primary);">Aktif</label>
                </div>
            </div>
            <div class="flex gap-2 mt-6">
                <button type="button" onclick="closeModal('editModal')" class="btn btn-outline flex-1">Batal</button>
                <button type="submit" class="btn btn-primary flex-1">Simpan</button>
            </div>
        </form>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const csrfToken = '{{ csrf_token() }}';
        const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });

        function copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                Toast.fire({ icon: 'success', title: 'Kode berhasil disalin!' });
            });
        }

        function openCreateModal() {
            document.getElementById('createForm').reset();
            openModal('createModal');
        }

        function openEditModal(code) {
            document.getElementById('editId').value = code.id;
            document.getElementById('editCode').value = code.code;
            document.getElementById('editDescription').value = code.description || '';
            document.getElementById('editRole').value = code.role || 'user';
            document.getElementById('editMaxUses').value = code.max_uses || '';
            document.getElementById('editExpiresAt').value = code.expires_at ? code.expires_at.split('T')[0] : '';
            document.getElementById('editIsActive').checked = code.is_active;
            openModal('editModal');
        }

        async function generateCode(inputId) {
            try {
                const res = await fetch('{{ route("referral-codes.generate") }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                
                const data = await res.json();
                
                if (data.success === false) {
                    throw new Error(data.message || 'Generate failed');
                }
                
                document.getElementById(inputId).value = data.code;
            } catch (error) {
                console.error('Generate code error:', error);
                alert('Gagal generate kode: ' + error.message);
            }
        }

        async function submitCreate(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            
            try {
                const res = await fetch('{{ route("referral-codes.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(Object.fromEntries(formData)),
                });
                const data = await res.json();
                
                if (data.success) {
                    Toast.fire({ icon: 'success', title: 'Kode referral berhasil dibuat!' });
                    setTimeout(() => location.reload(), 1000);
                } else {
                    throw new Error(data.message);
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: err.message || 'Terjadi kesalahan' });
            }
        }

        async function submitEdit(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const id = formData.get('id');
            
            const payload = {
                description: formData.get('description'),
                role: formData.get('role'),
                max_uses: formData.get('max_uses') || null,
                expires_at: formData.get('expires_at') || null,
                is_active: document.getElementById('editIsActive').checked,
            };
            
            try {
                const res = await fetch(`/kode-referral/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();
                
                if (data.success) {
                    Toast.fire({ icon: 'success', title: 'Kode referral berhasil diupdate!' });
                    setTimeout(() => location.reload(), 1000);
                } else {
                    throw new Error(data.message);
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: err.message || 'Terjadi kesalahan' });
            }
        }

        async function toggleCode(id, currentStatus) {
            const action = currentStatus ? 'menonaktifkan' : 'mengaktifkan';
            const result = await Swal.fire({
                title: 'Konfirmasi',
                text: `Yakin ingin ${action} kode referral ini?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            });
            
            if (!result.isConfirmed) return;
            
            try {
                const res = await fetch(`/kode-referral/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json();
                
                if (data.success) {
                    Toast.fire({ icon: 'success', title: data.message || 'Status berhasil diubah!' });
                    setTimeout(() => location.reload(), 1000);
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan' });
            }
        }

        async function deleteCode(id) {
            const result = await Swal.fire({
                title: 'Hapus Kode Referral?',
                text: 'Kode yang sudah dihapus tidak dapat dikembalikan!',
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
                const res = await fetch(`/kode-referral/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json();
                
                if (data.success) {
                    Toast.fire({ icon: 'success', title: 'Kode berhasil dihapus!' });
                    setTimeout(() => location.reload(), 1000);
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan' });
            }
        }

        @if(session()->has('success') && session('success'))
        Toast.fire({ icon: 'success', title: '{{ session("success") }}' });
        @endif
    </script>
</x-app-layout>

@section('meta-description', 'Manajemen pengguna sistem inventaris dengan role-based access control. Kontrol akses berbasis peran untuk keamanan dan efisiensi manajemen.')
<x-app-layout title="Pengguna">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold" style="color: var(--text-primary);">
                @if(auth()->user()->role === 'admin')
                    Kelola Pengguna
                @elseif(auth()->user()->role === 'staff')
                    Kelola Staff
                @else
                    Data Akun
                @endif
            </h1>
            @if(auth()->user()->role === 'admin')
                <p class="text-sm" style="color: var(--text-secondary);">Admin/Super Admin: {{ $adminCount }}/3 (Staff: unlimited)</p>
            @elseif(auth()->user()->role === 'staff')
                <p class="text-sm" style="color: var(--text-secondary);">Manajemen staff dengan role akses terbatas</p>
            @else
                <p class="text-sm" style="color: var(--text-secondary);">Informasi akun pribadi</p>
            @endif
        </div>

        <div class="flex gap-2">
            @can('users.manage')
            <a href="{{ route('referral-codes.index') }}" class="btn btn-outline">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                Kode Referral
            </a>
            @endcan
            @can('users.create')
            @if(auth()->user()->role === 'admin')
            <button onclick="openCreateModal()" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Pengguna
            </button>
            @elseif(auth()->user()->role === 'staff')
            <button onclick="openCreateModal()" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah User
            </button>
            @endif
            @endcan
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form action="{{ route('users.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <x-search-input name="search" placeholder="Cari nama/email/kode referral..." :value="request('search')" />

                <x-form.select name="role" placeholder="Semua Role" :value="request('role')">
                    @foreach($roles as $value => $label)
                    <option value="{{ $value }}" {{ request('role') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </x-form.select>

                <x-form.select name="status" placeholder="Semua Status" :value="request('status')" :options="['active' => 'Aktif', 'inactive' => 'Nonaktif']" />

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary flex-1">Filter</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline">Reset</a>
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
                        <th>Pengguna</th>
                        <th>Kode Referral</th>
                        <th>Direferensikan Oleh</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td class="text-gray-600">{{ $users->firstItem() + $index }}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar_url }}" class="w-10 h-10 rounded-full object-cover cursor-pointer hover:opacity-80" alt="{{ $user->name }}" onclick="viewImage('{{ $user->avatar_url }}', '{{ $user->name }}')">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $user->referral_code }}</code>
                                <button type="button" 
                                        onclick="copyToClipboard('{{ url('register?ref=' . $user->referral_code) }}')"
                                        class="text-gray-600 hover:text-primary-600" title="Salin Link Referral">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="text-gray-600">
                            {{ $user->referrer?->name ?? '-' }}
                        </td>
                        <td>
                            <span class="badge {{ $user->role === 'admin' ? 'badge-warning' : ($user->role === 'staff' ? 'badge-info' : 'badge-gray') }}">
                                {{ ucfirst($user->role ?? 'User') }}
                            </span>
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-gray">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('users.show', $user) }}" class="p-1.5 rounded hover:bg-gray-100" title="Detail">
                                    <svg class="w-4 h-4" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>

                                @can('users.edit')
                                <button onclick="openEditModal({{ json_encode($user->only(['id', 'name', 'email', 'phone', 'is_active', 'role'])) }})" class="p-1.5 rounded hover:bg-gray-100" title="Edit">
                                    <svg class="w-4 h-4" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @endcan

                                @can('users.delete')
                                @if($user->id !== auth()->id())
                                <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" class="p-1.5 rounded hover:bg-red-50" title="Hapus">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <x-empty-state 
                                icon="users"
                                title="Belum ada pengguna"
                                description="Tambah pengguna baru untuk mulai mengelola tim Anda"
                                action="{{ route('users.create') }}"
                                actionText="Tambah Pengguna"
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="card-footer">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Menampilkan <span class="font-medium">{{ $users->firstItem() }}</span> - 
                    <span class="font-medium">{{ $users->lastItem() }}</span> 
                    dari <span class="font-medium">{{ $users->total() }}</span> pengguna
                </p>
                <div>
                    <x-pagination :paginator="$users" />
                </div>
            </div>
        </div>
        @else
        @if($users->count() > 0)
        <div class="card-footer">
            <p class="text-sm text-gray-600">
                Menampilkan <span class="font-medium">{{ $users->count() }}</span> pengguna
            </p>
        </div>
        @endif
        @endif
    </div>

    <!-- Create Modal -->
    <x-modal name="createModal" title="Tambah Pengguna Baru" maxWidth="2xl">
        <form id="createForm" action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="createName" class="input w-full" placeholder="Masukkan nama lengkap" autocomplete="name" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="createEmail" class="input w-full" placeholder="contoh@email.com" autocomplete="email" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">No. Telepon</label>
                    <input type="text" name="phone" id="createPhone" class="input w-full" placeholder="08xxxxxxxxxx" autocomplete="tel">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Role <span class="text-red-500">*</span></label>
                    <select name="role" id="createRole" class="input w-full" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="createPassword" class="input w-full pr-10" placeholder="Min. 8 karakter" autocomplete="new-password" required>
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700" onclick="togglePasswordVisibility('createPassword')" tabindex="-1">
                            <svg class="w-5 h-5 password-icon-show" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="w-5 h-5 password-icon-hide hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.27 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="createPasswordConfirmation" class="input w-full pr-10" placeholder="Ulangi password" autocomplete="new-password" required>
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700" onclick="togglePasswordVisibility('createPasswordConfirmation')" tabindex="-1">
                            <svg class="w-5 h-5 password-icon-show" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="w-5 h-5 password-icon-hide hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.27 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Kode Referral <span class="text-xs" style="color: var(--text-secondary);">(opsional)</span></label>
                    <input type="text" name="referral_code" id="createReferralCode" class="input w-full" placeholder="Masukkan kode referral">
                </div>
                <div class="flex items-center pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="createIsActive" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        <span class="ms-3 text-sm font-medium" style="color: var(--text-primary);">Status Aktif</span>
                    </label>
                </div>
            </div>
            <div class="flex gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('createModal')" class="btn btn-outline flex-1">Batal</button>
                <button type="submit" class="btn btn-primary flex-1" data-loading>
                    <span class="btn-text">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan
                    </span>
                    <span class="btn-loading hidden">
                        <x-loading-spinner size="sm" class="mr-2" />
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Modal -->
    <x-modal name="editModal" title="Edit Pengguna" maxWidth="2xl">
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="editName" class="input w-full" placeholder="Masukkan nama lengkap" autocomplete="name" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="editEmail" class="input w-full" placeholder="contoh@email.com" autocomplete="email" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">No. Telepon</label>
                    <input type="text" name="phone" id="editPhone" class="input w-full" placeholder="08xxxxxxxxxx" autocomplete="tel">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Role <span class="text-red-500">*</span></label>
                    <select name="role" id="editRole" class="input w-full" required>
                        @foreach($roles as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2 flex items-center">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="editIsActive" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        <span class="ms-3 text-sm font-medium" style="color: var(--text-primary);">Status Aktif</span>
                    </label>
                </div>
            </div>
            <div class="flex gap-3 mt-6 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="closeModal('editModal')" class="btn btn-outline flex-1">Batal</button>
                <button type="submit" class="btn btn-primary flex-1" data-loading>
                    <span class="btn-text">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Update
                    </span>
                    <span class="btn-loading hidden">
                        <x-loading-spinner size="sm" class="mr-2" />
                        Memperbarui...
                    </span>
                </button>
            </div>
        </form>
    </x-modal>

    <!-- SweetAlert2 CDN - only for this page -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toast config
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                Toast.fire({ icon: 'success', title: 'Link berhasil disalin!' });
            });
        }

        function openCreateModal() {
            document.getElementById('createForm').reset();
            document.getElementById('createIsActive').checked = true;
            openModal('createModal');
        }

        function openEditModal(user) {
            document.getElementById('editForm').action = `/pengguna/${user.id}`;
            document.getElementById('editName').value = user.name || '';
            document.getElementById('editEmail').value = user.email || '';
            document.getElementById('editPhone').value = user.phone || '';
            document.getElementById('editRole').value = user.role || '';
            document.getElementById('editIsActive').checked = user.is_active;
            openModal('editModal');
        }

        async function deleteUser(id, name) {
            const result = await Swal.fire({
                title: 'Hapus Pengguna?',
                html: `Yakin ingin menghapus <strong>${name}</strong>?<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            });
            
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/pengguna/${id}`;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Show session messages
        @if(session()->has('success') && session('success'))
        Toast.fire({ icon: 'success', title: '{{ session("success") }}' });
        @endif
        @if(session()->has('error') && session('error'))
        Toast.fire({ icon: 'error', title: '{{ session("error") }}' });
        @endif

        // Loading state handler
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[data-loading]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.querySelector('.btn-text').classList.add('hidden');
                        submitBtn.querySelector('.btn-loading').classList.remove('hidden');
                    }
                });
            });
        });
    </script>
</x-app-layout>

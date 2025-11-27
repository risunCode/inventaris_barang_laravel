<x-app-layout title="Profil Saya">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold mb-6" style="color: var(--text-primary);">Profil Saya</h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Profile Card -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile Photo -->
                <div class="rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                    <div class="text-center mb-6">
                        <img id="profile-preview" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                             class="w-24 h-24 rounded-full object-cover mx-auto mb-4 ring-4" style="--tw-ring-color: var(--border-color);">
                        <h3 class="text-xl font-semibold" style="color: var(--text-primary);">{{ $user->name }}</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">{{ $user->email }}</p>
                        <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full btn-primary">{{ $user->roles->first()?->name ?? 'User' }}</span>
                    </div>
                    
                    <!-- Photo Upload with Crop -->
                    <div class="space-y-3">
                        <input type="file" id="photo-input" accept="image/*" class="hidden" onchange="openCropModal(this)">
                        <button type="button" onclick="document.getElementById('photo-input').click()" class="w-full px-4 py-2 rounded-lg text-sm font-medium btn-primary">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Ganti Foto Profil
                        </button>
                        <p class="text-xs text-center" style="color: var(--text-secondary);">Klik untuk pilih & crop foto</p>
                    </div>
                </div>

                <!-- Account Stats -->
                <div class="rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                    <h4 class="font-semibold mb-4" style="color: var(--text-primary);">Statistik Akun</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm" style="color: var(--text-secondary);">Bergabung</span>
                            <span class="text-sm font-medium" style="color: var(--text-primary);">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm" style="color: var(--text-secondary);">Terakhir Login</span>
                            <span class="text-sm font-medium" style="color: var(--text-primary);">{{ $user->last_login_at?->diffForHumans() ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm" style="color: var(--text-secondary);">Status</span>
                            <span class="px-2 py-1 text-xs rounded-full {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Forms -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Profile Info -->
                <div class="rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2" style="color: var(--text-primary);">
                        <svg class="w-5 h-5" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Informasi Profil
                    </h3>
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <x-form.input label="Nama Lengkap" name="name" :value="$user->name" required />
                            <x-form.input label="Email" name="email" type="email" :value="$user->email" required />
                            <x-form.input label="No. Telepon" name="phone" :value="$user->phone" placeholder="08xxxxxxxxxx" />
                            <x-form.input label="Tanggal Lahir" name="birth_date" type="date" :value="$user->birth_date?->format('Y-m-d')" />
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan Profil
                        </button>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2" style="color: var(--text-primary);">
                        <svg class="w-5 h-5" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Ubah Password
                    </h3>
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <x-form.input label="Password Saat Ini" name="current_password" type="password" required />
                            <x-form.input label="Password Baru" name="password" type="password" required />
                            <x-form.input label="Konfirmasi Password" name="password_confirmation" type="password" required />
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            Ubah Password
                        </button>
                    </form>
                </div>

                <!-- Security Section -->
                <div id="security" class="rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2" style="color: var(--text-primary);">
                        <svg class="w-5 h-5" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Keamanan Akun
                    </h3>
                    <p class="text-sm mb-4" style="color: var(--text-secondary);">Pertanyaan keamanan digunakan untuk verifikasi saat reset password</p>
                    
                    <!-- Status Pertanyaan Keamanan -->
                    <div class="p-4 rounded-lg mb-4" style="background-color: var(--bg-input);">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">
                                    @if($user->security_question_1)
                                        <span class="inline-flex items-center gap-1 text-green-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Pertanyaan keamanan sudah diatur
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-amber-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            Pertanyaan keamanan belum diatur
                                        </span>
                                    @endif
                                </p>
                                <p class="text-xs mt-1" style="color: var(--text-secondary);">Verifikasi tanggal lahir untuk mengubah pertanyaan keamanan</p>
                            </div>
                            <button type="button" onclick="openVerifyBirthModal()" class="btn btn-primary text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                Ubah
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Crop Modal -->
    <div id="crop-modal-backdrop" class="modal-backdrop" style="display:none;"></div>
    <div id="crop-modal" class="modal-content w-full max-w-lg" style="display:none;">
        <div class="rounded-2xl shadow-2xl border overflow-hidden" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="p-4 border-b flex items-center justify-between" style="border-color: var(--border-color);">
                <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Crop Foto Profil</h3>
                <button onclick="closeCropModal()" class="p-1 rounded hover:opacity-70" style="color: var(--text-secondary);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-4">
                <div class="relative w-full aspect-square bg-gray-900 rounded-lg overflow-hidden mb-4">
                    <img id="crop-image" src="" class="max-w-full">
                </div>
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <button onclick="cropper.zoom(-0.1)" class="p-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);" title="Zoom Out">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/></svg>
                        </button>
                        <button onclick="cropper.zoom(0.1)" class="p-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);" title="Zoom In">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                        </button>
                        <button onclick="cropper.rotate(-90)" class="p-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);" title="Rotate">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="closeCropModal()" class="px-4 py-2 rounded-lg" style="background-color: var(--bg-input); color: var(--text-secondary);">Batal</button>
                        <button onclick="uploadCroppedImage()" class="px-4 py-2 rounded-lg font-medium btn-primary">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verify Birth Date Modal -->
    <x-modal name="verifyBirthModal" title="Verifikasi Identitas" maxWidth="sm">
        <div class="text-center mb-4">
            <div class="w-16 h-16 mx-auto mb-3 rounded-full flex items-center justify-center" style="background-color: var(--bg-input);">
                <svg class="w-8 h-8" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-sm" style="color: var(--text-secondary);">Masukkan tanggal lahir Anda untuk melanjutkan</p>
        </div>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Tanggal Lahir</label>
                <input type="date" id="verify-birth-date" class="input w-full" required>
            </div>
            <p id="verify-error" class="text-sm text-red-500 hidden">Tanggal lahir tidak sesuai</p>
        </div>
        <div class="flex gap-2 mt-6">
            <button type="button" onclick="closeModal('verifyBirthModal')" class="btn btn-outline flex-1">Batal</button>
            <button type="button" onclick="verifyBirthDate()" class="btn btn-primary flex-1">Verifikasi</button>
        </div>
    </x-modal>

    <!-- Security Questions Modal -->
    <x-modal name="securityModal" title="Ubah Pertanyaan Keamanan" maxWidth="lg">
        <form id="securityForm" action="{{ route('profile.security') }}" method="POST">
            @csrf @method('PUT')
            <div class="space-y-4">
                <!-- Pertanyaan Template -->
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Pertanyaan Keamanan</label>
                    <select name="security_question_1" id="securityQuestion" class="input w-full" onchange="toggleCustomQuestion()">
                        <option value="">Pilih pertanyaan...</option>
                        @foreach($securityQuestions ?? [] as $key => $question)
                        <option value="{{ $key }}" {{ $user->security_question_1 == $key ? 'selected' : '' }}>{{ $question }}</option>
                        @endforeach
                        <option value="0" {{ $user->security_question_1 === 0 ? 'selected' : '' }}>Tulis pertanyaan sendiri...</option>
                    </select>
                </div>
                
                <!-- Custom Question Input -->
                <div id="customQuestionWrapper" class="{{ $user->security_question_1 === 0 ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Pertanyaan Custom</label>
                    <input type="text" name="custom_security_question" id="customQuestion" value="{{ $user->custom_security_question ?? '' }}" placeholder="Tulis pertanyaan Anda sendiri..." class="input w-full">
                </div>
                
                <!-- Jawaban -->
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Jawaban Keamanan <span class="text-red-500">*</span></label>
                    <input type="text" name="security_answer_1" placeholder="Masukkan jawaban..." class="input w-full" required>
                    <p class="text-xs mt-1" style="color: var(--text-secondary);">Jawaban bersifat case-insensitive</p>
                </div>
            </div>
            <div class="flex gap-2 mt-6">
                <button type="button" onclick="closeModal('securityModal')" class="btn btn-outline flex-1">Batal</button>
                <button type="submit" class="btn btn-primary flex-1">Simpan</button>
            </div>
        </form>
    </x-modal>

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let cropper = null;
const userBirthDate = '{{ $user->birth_date?->format("Y-m-d") ?? "" }}';
const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });

// Show session messages
@if(session()->has('success') && session('success'))
Toast.fire({ icon: 'success', title: '{{ session("success") }}' });
@endif
@if(session()->has('error') && session('error'))
Toast.fire({ icon: 'error', title: '{{ session("error") }}' });
@endif

// Verify Birth Date Modal
function openVerifyBirthModal() {
    if (!userBirthDate) {
        alert('Silakan atur tanggal lahir Anda terlebih dahulu di bagian Informasi Profil.');
        return;
    }
    document.getElementById('verify-birth-date').value = '';
    document.getElementById('verify-error').classList.add('hidden');
    openModal('verifyBirthModal');
}

function verifyBirthDate() {
    const inputDate = document.getElementById('verify-birth-date').value;
    const errorEl = document.getElementById('verify-error');
    
    if (inputDate === userBirthDate) {
        closeModal('verifyBirthModal');
        openModal('securityModal');
    } else {
        errorEl.classList.remove('hidden');
    }
}

// Toggle custom question
function toggleCustomQuestion() {
    const select = document.getElementById('securityQuestion');
    const wrapper = document.getElementById('customQuestionWrapper');
    if (select.value === '0') {
        wrapper.classList.remove('hidden');
    } else {
        wrapper.classList.add('hidden');
    }
}

function openCropModal(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('crop-image');
            img.src = e.target.result;
            
            const backdrop = document.getElementById('crop-modal-backdrop');
            const modal = document.getElementById('crop-modal');
            backdrop.style.display = 'block';
            modal.style.display = 'block';
            void modal.offsetWidth;
            backdrop.classList.add('active');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            if (cropper) cropper.destroy();
            
            cropper = new Cropper(img, {
                aspectRatio: 1,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                cropBoxResizable: false,
                cropBoxMovable: false,
                guides: false,
                center: true,
                highlight: false,
                background: false,
                ready: function() {
                    const cropBox = document.querySelector('.cropper-crop-box');
                    if (cropBox) cropBox.style.borderRadius = '50%';
                    const viewBox = document.querySelector('.cropper-view-box');
                    if (viewBox) viewBox.style.borderRadius = '50%';
                }
            });
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function closeCropModal() {
    const backdrop = document.getElementById('crop-modal-backdrop');
    const modal = document.getElementById('crop-modal');
    backdrop.classList.remove('active');
    modal.classList.remove('active');
    document.body.style.overflow = '';
    setTimeout(() => {
        backdrop.style.display = 'none';
        modal.style.display = 'none';
    }, 200);
    document.getElementById('photo-input').value = '';
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
}

function uploadCroppedImage() {
    if (!cropper) return;
    
    cropper.getCroppedCanvas({
        width: 256,
        height: 256,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    }).toBlob(function(blob) {
        const formData = new FormData();
        formData.append('avatar', blob, 'profile.jpg');
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PATCH');
        
        fetch('{{ route("profile.update") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Gagal: ' + (data.message || 'Gagal mengupload foto.'));
                closeCropModal();
            }
        }).catch(error => {
            console.error('Upload error:', error);
            alert('Error: Terjadi kesalahan saat upload.');
            closeCropModal();
        });
    }, 'image/jpeg', 0.9);
}
</script>
<style>
.cropper-view-box, .cropper-face { border-radius: 50%; }
</style>
@endpush
</x-app-layout>

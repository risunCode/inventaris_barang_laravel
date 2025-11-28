<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Setup Keamanan - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Force Light Theme for Auth Pages -->
    <style>
        body {
            color-scheme: light !important;
        }
        
        .bg-gray-100 {
            background-color: #f3f4f6 !important;
        }
        
        .bg-white {
            background-color: #ffffff !important;
        }
        
        .text-gray-900 {
            color: #1f2937 !important;
        }
        
        .text-gray-700 {
            color: #374151 !important;
        }
        
        .text-gray-600 {
            color: #4b5563 !important;
        }
        
        .text-gray-500 {
            color: #6b7280 !important;
        }
        
        .text-gray-400 {
            color: #9ca3af !important;
        }
        
        .border-gray-200 {
            border-color: #e5e7eb !important;
        }
        
        .border-gray-300 {
            border-color: #d1d5db !important;
        }
        
        .card {
            background-color: #ffffff !important;
            border-color: #e5e7eb !important;
            color: #1f2937 !important;
        }
        
        .input {
            background-color: #ffffff !important;
            border-color: #d1d5db !important;
            color: #1f2937 !important;
        }
        
        .input::placeholder {
            color: #6b7280 !important;
        }
        
        .input:focus {
            border-color: #3b82f6 !important;
        }
        
        /* Override any dark mode styles */
        * {
            color-scheme: light !important;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Top Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex items-center justify-center gap-3">
                <img src="{{ asset('images/logo-kab.png') }}" alt="Logo" class="h-10 w-auto">
                <div class="text-center">
                    <h1 class="text-lg font-bold text-gray-900">Sistem Inventaris Barang</h1>
                    <p class="text-xs text-gray-500">Pemerintah Kabupaten Kubu Raya</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center p-4 py-8">
        <div class="w-full max-w-2xl">
            <!-- Header -->
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Setup Keamanan Akun</h2>
                <p class="text-gray-500 mt-2">Lengkapi informasi keamanan untuk melindungi akun Anda</p>
            </div>

        @if(session('warning'))
        <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-lg mb-6">
            {{ session('warning') }}
        </div>
        @endif

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <form method="POST" action="{{ route('security.store') }}" onsubmit="console.log('Form submitted', new FormData(this))">
                @csrf
                
                <div class="space-y-5">
                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" required
                               class="input w-full @error('birth_date') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Digunakan sebagai verifikasi identitas saat reset password</p>
                        @error('birth_date')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pertanyaan Keamanan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan Keamanan <span class="text-red-500">*</span></label>
                        <select name="security_question_1" id="securityQuestion" onchange="toggleCustomQuestion()" required
                                class="input w-full @error('security_question_1') border-red-500 @enderror">
                            <option value="">Pilih pertanyaan keamanan</option>
                            @foreach($securityQuestions as $key => $question)
                            <option value="{{ $key }}" {{ old('security_question_1') == $key ? 'selected' : '' }}>{{ $question }}</option>
                            @endforeach
                            <option value="0" {{ old('security_question_1') == '0' ? 'selected' : '' }}>Buat pertanyaan sendiri...</option>
                        </select>
                        @error('security_question_1')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Custom Question Input -->
                    <div id="customQuestionWrapper" class="{{ old('security_question_1') == '0' ? '' : 'hidden' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan Custom <span class="text-red-500">*</span></label>
                        <input type="text" name="custom_security_question" id="customQuestion" 
                               value="{{ old('custom_security_question') }}" 
                               placeholder="Tulis pertanyaan Anda sendiri..." 
                               class="input w-full">
                        @error('custom_security_question')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jawaban -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jawaban <span class="text-red-500">*</span></label>
                        <input type="text" name="security_answer_1" value="{{ old('security_answer_1') }}" required
                               placeholder="Masukkan jawaban dari pertanyaan di atas..."
                               class="input w-full @error('security_answer_1') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Tidak case-sensitive, huruf besar atau kecil akan dianggap sama</p>
                        @error('security_answer_1')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-200 text-gray-600 px-4 py-3 rounded-lg text-sm mt-6">
                    <strong>Catatan:</strong> Informasi ini diperlukan untuk reset password jika Anda lupa.
                </div>

                <div class="flex gap-4 mt-6 pt-4 border-t border-gray-200">
                    <button type="submit" class="btn btn-primary flex-1">
                        Simpan & Lanjutkan
                    </button>
                </div>
            </form>
            
            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 hover:underline w-full text-center">
                    Logout dari akun ini
                </button>
            </form>
        </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-4">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between gap-3 text-sm text-gray-500">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo-kab.png') }}" alt="Logo" class="h-6 w-auto opacity-50">
                    <span>&copy; {{ date('Y') }} Pemerintah Kabupaten Kubu Raya</span>
                </div>
                <div class="flex items-center gap-4">
                    <span>{{ config('app.name') }}</span>
                    <span class="text-gray-300">|</span>
                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">v{{ config('sibarang.version', '0.0.7-beta-007') }}</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function toggleCustomQuestion() {
        const select = document.getElementById('securityQuestion');
        const wrapper = document.getElementById('customQuestionWrapper');
        if (select.value === '0') {
            wrapper.classList.remove('hidden');
        } else {
            wrapper.classList.add('hidden');
        }
    }

    // SweetAlert notifications
    
    // Show security setup warning on page load
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'info',
            title: 'Setup Keamanan Wajib!',
            html: 
                '<div class="text-left">' +
                '<p class="mb-3"><strong>Anda harus menyelesaikan setup keamanan ini.</strong></p>' +
                '<p class="mb-3 text-sm text-gray-600">Sistem ini tidak memiliki OTP verifikasi karena aplikasi dirancang untuk penggunaan internal saja, tidak public.</p>' +
                '<ul class="text-sm text-gray-600 space-y-1 ml-4">' +
                '<li>• Tanggal lahir untuk verifikasi identitas</li>' +
                '<li>• Pertanyaan keamanan untuk pemulihan akun</li>' +
                '<li>• Jawaban yang aman dan mudah diingat</li>' +
                '</ul>' +
                '<p class="mt-3 text-sm text-blue-600"><em>Setup ini hanya dilakukan sekali saat registrasi.</em></p>' +
                '</div>',
            confirmButtonColor: '#3b82f6',
            confirmButtonText: 'Saya Mengerti',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    });

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 3000,
        toast: true,
        position: 'bottom-end'
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        @if(app()->environment('local', 'testing'))
            html: '<strong>DEBUG MODE:</strong><br>{{ session('error') }}',
            timer: 8000,
        @else
            text: '{{ session('error') }}',
            timer: 5000,
        @endif
        showConfirmButton: true,
        confirmButtonColor: '#dc2626'
    });
    @endif

    @if ($errors->any())
    let errorMessages = [];
    @foreach ($errors->all() as $error)
        errorMessages.push('{{ $error }}');
    @endforeach
    
    Swal.fire({
        icon: 'error',
        title: 'Validasi Gagal!',
        @if(app()->environment('local', 'testing'))
            html: '<strong>DEBUG MODE - Validation Errors:</strong><br>' + errorMessages.join('<br>'),
            timer: 10000,
        @else
            html: errorMessages.join('<br>'),
            timer: 5000,
        @endif
        showConfirmButton: true,
        confirmButtonColor: '#dc2626'
    });
    @endif

    // Debug info for local environment
    @if(app()->environment('local', 'testing'))
        console.log('=== SECURITY SETUP DEBUG ===');
        console.log('Environment: {{ app()->environment() }}');
        console.log('Session errors:', {!! json_encode(session()->all()) !!});
        console.log('Validation errors:', {!! json_encode($errors->all()) !!});
        @if(session('error'))
            console.log('Error message:', '{{ session('error') }}');
        @endif
    @endif
    </script>
</body>
</html>

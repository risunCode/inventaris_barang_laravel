<x-guest-layout title="Verifikasi Keamanan">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Verifikasi Keamanan</h2>
        <p class="text-sm text-gray-500 mt-1">Jawab pertanyaan untuk melanjutkan</p>
    </div>

    <!-- Step Indicator (E-Surat-Perkim style) -->
    <div class="flex items-center justify-center mb-6">
        <div class="flex items-center">
            <!-- Step 1: Email (Completed) -->
            <div class="flex items-center justify-center w-10 h-10 bg-green-500 text-white rounded-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </div>
            
            <!-- Connector -->
            <div class="flex-1 h-1 bg-purple-600 mx-2 w-16"></div>
            
            <!-- Step 2: Security (Active) -->
            <div class="flex items-center justify-center w-10 h-10 bg-purple-600 text-white rounded-full font-bold">
                2
            </div>
            
            <!-- Connector -->
            <div class="flex-1 h-1 bg-gray-300 mx-2 w-16"></div>
            
            <!-- Step 3: Reset (Pending) -->
            <div class="flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-500 rounded-full font-bold">
                3
            </div>
        </div>
    </div>

    <!-- Reset Email Display -->
    <div class="text-center mb-6">
        <p class="text-sm text-gray-600">Reset password untuk</p>
        <p class="font-semibold text-gray-900">{{ $email }}</p>
    </div>

    <form method="POST" action="{{ route('password.verify') }}" class="space-y-4">
        @csrf

        <!-- Birth Date Input -->
        <div>
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="birth_date" class="form-input" placeholder="Pilih tanggal lahir" required autofocus>
            @error('birth_date')
            <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Security Question (User's Set Question) -->
        <div>
            <label class="form-label">{{ $question1 }}</label>
            <input type="text" name="answer1" class="form-input" placeholder="Masukkan jawaban Anda" required>
            @error('answer1')
            <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <p class="text-xs text-gray-500">
            Jawaban bersifat case-insensitive (huruf besar/kecil tidak berpengaruh)
        </p>

        <button type="submit" class="btn btn-primary w-full">
            Verifikasi Identitas
        </button>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" class="text-primary-600 hover:underline">Kembali ke login</a>
        </p>
    </form>
</x-guest-layout>

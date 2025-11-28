<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Login' }} - {{ config('app.name', 'Inventaris Barang') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Force Light Theme for Auth Pages -->
    <style>
        body {
            background-color: #f3f4f6 !important;
            color: #1f2937 !important;
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
        
        .border-gray-200 {
            border-color: #e5e7eb !important;
        }
        
        .border-gray-300 {
            border-color: #d1d5db !important;
        }
        
        /* Override any dark mode styles */
        * {
            color-scheme: light !important;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100" style="color-scheme: light;">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <!-- Logo -->
        <div class="mb-6">
            <a href="/" class="flex items-center gap-3">
                <img src="/images/logo-pbj-kalbar.png?v={{ time() }}" alt="Logo" class="w-12 h-12 object-contain">
                <span class="text-xl font-bold text-gray-900">Inventaris Barang</span>
            </a>
        </div>

        <!-- Card -->
        <div class="w-full max-w-md">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="text-center text-sm text-gray-500 mt-6">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
    @endif

    <!-- Password Toggle Script -->
    <script>
        function togglePassword(inputId, iconId) {
            console.log('togglePassword called with:', inputId, iconId);
            
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            console.log('Input found:', input);
            console.log('Icon found:', icon);
            console.log('Current input type:', input ? input.type : 'not found');
            
            if (!input || !icon) {
                console.error('Element not found!');
                return;
            }
            
            if (input.type === 'password') {
                console.log('Changing to text');
                input.type = 'text';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464M9.878 9.878l-5.414-5.414m0 0L2 2m7.878 7.878L18.32 18.32M7.05 14.95L18.32 18.32" />`;
            } else {
                console.log('Changing to password');
                input.type = 'password';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            }
            
            console.log('New input type:', input.type);
        }
    </script>
</body>
</html>

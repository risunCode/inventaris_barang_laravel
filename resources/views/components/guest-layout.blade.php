@props(['title' => ''])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title . ' - ' : '' }}{{ config('app.name') }}</title>
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
        
        .input,
        .form-input {
            background-color: #ffffff !important;
            border-color: #d1d5db !important;
            color: #1f2937 !important;
        }
        
        .input::placeholder,
        .form-input::placeholder {
            color: #6b7280 !important;
        }
        
        .input:focus,
        .form-input:focus {
            border-color: #3b82f6 !important;
        }
        
        .form-label {
            color: #374151 !important; /* text-gray-700 */
        }
        
        .form-error {
            color: #dc2626 !important; /* text-red-600 */
        }
        
        /* Override any dark mode styles */
        * {
            color-scheme: light !important;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-600 rounded-xl mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900">{{ config('app.name') }}</h1>
        </div>

        <!-- Card -->
        <div class="card">
            <div class="card-body">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-gray-500 mt-6">
            &copy; {{ date('Y') }} {{ config('app.name') }} v{{ config('app.version') }}. All rights reserved.
        </p>
    </div>

    <script>
        // Toggle Password Visibility
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            if (!input) return;
            
            const button = input.parentElement.querySelector('button');
            const showIcon = button.querySelector('.password-icon-show');
            const hideIcon = button.querySelector('.password-icon-hide');
            
            if (input.type === 'password') {
                input.type = 'text';
                showIcon.classList.add('hidden');
                hideIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                showIcon.classList.remove('hidden');
                hideIcon.classList.add('hidden');
            }
        }
        
        // Legacy function for reset-password page (if used)
        function togglePassword(inputId, iconId) {
            togglePasswordVisibility(inputId);
        }
    </script>

    @stack('scripts')
</body>
</html>

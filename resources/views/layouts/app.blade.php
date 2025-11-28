<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name', 'Inventaris Barang') }}</title>
    
    <!-- Meta Description -->
    <meta name="description" content="@yield('meta-description', 'Sistem inventaris barang untuk mengelola aset perusahaan dengan mudah dan efisien. Lacak, kelola, dan pantau semua barang inventaris dalam satu platform.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen">
        <!-- Mobile Sidebar Backdrop -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-black/50 lg:hidden"
             @click="sidebarOpen = false">
        </div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto">
            
            <!-- Logo -->
            <div class="flex items-center gap-3 h-16 px-6 border-b border-gray-200">
                <img src="/images/logo-pbj-kalbar.png?v={{ time() }}" alt="Logo" class="w-8 h-8 object-contain">
                <span class="font-semibold text-gray-900">Inventaris</span>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-1">
                <a href="{{ route('dashboard') }}" 
                   class="sidebar-link {{ request()->routeIs('dashboard') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                @can('commodities.view')
                <a href="{{ route('commodities.index') }}" 
                   class="sidebar-link {{ request()->routeIs('commodities.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Barang
                </a>
                @endcan

                @can('categories.view')
                <a href="{{ route('categories.index') }}" 
                   class="sidebar-link {{ request()->routeIs('categories.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Kategori
                </a>
                @endcan

                @can('locations.view')
                <a href="{{ route('locations.index') }}" 
                   class="sidebar-link {{ request()->routeIs('locations.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Lokasi
                </a>
                @endcan

                @can('transfers.view')
                <a href="{{ route('transfers.index') }}" 
                   class="sidebar-link {{ request()->routeIs('transfers.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Transfer
                </a>
                @endcan

                @can('maintenance.view')
                <a href="{{ route('maintenance.index') }}" 
                   class="sidebar-link {{ request()->routeIs('maintenance.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Maintenance
                </a>
                @endcan

                @can('disposals.view')
                <a href="{{ route('disposals.index') }}" 
                   class="sidebar-link {{ request()->routeIs('disposals.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Penghapusan
                </a>
                @endcan

                @can('reports.view')
                <a href="{{ route('reports.index') }}" 
                   class="sidebar-link {{ request()->routeIs('reports.*') ? 'sidebar-link-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Laporan
                </a>
                @endcan

                @if(auth()->user()->role === 'admin')
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Pengaturan</p>
                    <a href="{{ route('users.index') }}" 
                       class="sidebar-link {{ request()->routeIs('users.*') ? 'sidebar-link-active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Pengguna
                    </a>
                </div>
                @endif

                @can('referral-codes.own')
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Pengaturan</p>
                    <a href="{{ route('referral-codes.index') }}" 
                       class="sidebar-link {{ request()->routeIs('referral-codes.*') ? 'sidebar-link-active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Kode Referral
                    </a>
                </div>
                @endcan
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="lg:pl-64">
            <!-- Top Navigation -->
            <header class="sticky top-0 z-30 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6">
                    <!-- Mobile menu button - Only on mobile -->
                    <button @click="sidebarOpen = true" class="block lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Page Title (Desktop) -->
                    <h1 class="hidden lg:block text-lg font-semibold text-gray-900">
                        {{ $header ?? ($title ?? 'Dashboard') }}
                    </h1>

                    <!-- Right side -->
                    <div class="flex items-center gap-3">
                        <!-- Create Referral Code Button -->
                        @can('referral-codes.create')
                        <button onclick="openCreateReferralModal()" class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-white transition-colors" style="background-color: var(--accent-color);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            Buat Kode Referral
                        </button>
                        @else
                            @if(auth()->check())
                                @if(app()->environment('local'))
                                <button class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-red-600 border border-red-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    DEBUG: No Permission ({{ auth()->user()->role }})
                                </button>
                                @endif
                            @endif
                        @endcan

                        <!-- Notifications -->
                        <x-notification-bell />

                        <!-- User dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100">
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover">
                                <span class="hidden sm:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    Profil Saya
                                </a>
                                @can('referral-codes.own')
                                <a href="{{ route('referral-codes.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    Kode Referral Saya
                                </a>
                                @endcan
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page Title (Mobile) -->
                <div class="lg:hidden px-4 pb-3">
                    <h1 class="text-lg font-semibold text-gray-900">{{ $header ?? ($title ?? 'Dashboard') }}</h1>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 sm:p-6">
                <!-- Flash Messages -->
                @if (session('success'))
                    <x-alert type="success" :message="session('success')" class="mb-4" />
                @endif

                @if (session('error'))
                    <x-alert type="danger" :message="session('error')" class="mb-4" />
                @endif

                @if (session('warning'))
                    <x-alert type="warning" :message="session('warning')" class="mb-4" />
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
    
    <!-- Global Scripts -->
    <script>
        // Global function to open referral create modal from top navigation
        function openCreateReferralModal() {
            // Check if we're on referral codes page
            if (typeof openCreateModal === 'function') {
                openCreateModal();
            } else {
                // Redirect to referral codes page if modal function not available
                window.location.href = '{{ route("referral-codes.index") }}';
            }
        }
    </script>
</body>
</html>

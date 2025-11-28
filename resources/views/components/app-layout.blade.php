@props(['title' => ''])

<!DOCTYPE html>
<html lang="id">
<head>
    <script>
        // Theme initialization - prevent flash
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme !== 'light') {
                document.documentElement.classList.add(theme);
            }
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title . ' - ' : '' }}{{ config('app.name') }}</title>
    <!-- DNS Prefetch & Preconnect for faster loading -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Font with display=swap for faster text rendering -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"></noscript>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Hide Alpine.js elements until initialized - prevents dropdown flash */
        [x-cloak] { display: none !important; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { scrollbar-width: none; }
    </style>
</head>
<body class="min-h-screen transition-colors duration-200" style="background-color: var(--bg-secondary); color: var(--text-primary);" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 transform -translate-x-full lg:translate-x-0 transition-transform duration-300" 
               style="background: var(--bg-sidebar);"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-4 border-b border-white/10">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <img src="/images/logo-pbj-kalbar.png?v={{ time() }}" alt="Logo" width="24" height="24" class="w-6 h-6 object-contain">
                    <div>
                        <span class="font-bold text-white text-lg">SIBARANG</span>
                        <p class="text-[10px] text-white/60 -mt-1">Menu Aplikasi</p>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-white/70 hover:text-white" aria-label="Tutup Menu" title="Tutup Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            @php
                $activeStyle = 'background-color: var(--sidebar-active); color: var(--sidebar-text); border-left: 3px solid white;';
                $inactiveStyle = 'color: var(--sidebar-text-muted);';
            @endphp
            <!-- Navigation -->
            <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-4rem)] hide-scrollbar">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'active' : '' }}" style="{{ request()->routeIs('dashboard') ? $activeStyle : $inactiveStyle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="font-medium">Beranda</span>
                </a>

                <!-- Section: Master Data -->
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--sidebar-text-muted);">Master Data</p>
                </div>

                @can('commodities.view')
                <a href="{{ route('commodities.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('commodities.*') ? 'active' : '' }}" style="{{ request()->routeIs('commodities.*') ? $activeStyle : $inactiveStyle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="font-medium">Barang</span>
                </a>
                @endcan

                @can('categories.view')
                <a href="{{ route('categories.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('categories.*') ? 'active' : '' }}" style="{{ request()->routeIs('categories.*') ? $activeStyle : $inactiveStyle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span class="font-medium">Kategori</span>
                </a>
                @endcan

                @can('locations.view')
                <a href="{{ route('locations.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('locations.*') ? 'active' : '' }}" style="{{ request()->routeIs('locations.*') ? $activeStyle : $inactiveStyle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <span class="font-medium">Lokasi</span>
                </a>
                @endcan

                <!-- Section: Transaksi -->
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--sidebar-text-muted);">Transaksi</p>
                </div>

                @can('transfers.view')
                <a href="{{ route('transfers.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('transfers.*') ? 'active' : '' }}" style="{{ request()->routeIs('transfers.*') ? $activeStyle : $inactiveStyle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <span class="font-medium">Transfer</span>
                </a>
                @endcan

                @can('maintenance.view')
                <a href="{{ route('maintenance.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('maintenance.*') ? 'active' : '' }}" style="{{ request()->routeIs('maintenance.*') ? $activeStyle : $inactiveStyle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    </svg>
                    <span class="font-medium">Maintenance</span>
                </a>
                @endcan

                @can('disposals.view')
                <a href="{{ route('disposals.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('disposals.*') ? 'active' : '' }}" style="{{ request()->routeIs('disposals.*') ? $activeStyle : $inactiveStyle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span class="font-medium">Penghapusan</span>
                </a>
                @endcan

                <!-- Section: Laporan -->
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--sidebar-text-muted);">Laporan</p>
                </div>

                @can('reports.view')
                <a href="{{ route('reports.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('reports.*') ? 'active' : '' }}" style="{{ request()->routeIs('reports.*') ? $activeStyle : $inactiveStyle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-medium">Laporan</span>
                </a>
                @endcan

                <!-- Section: Admin -->
                @if(auth()->user()->role === 'admin')
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--sidebar-text-muted);">Administrator</p>
                </div>

                <a href="{{ route('users.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('users.*') ? 'active' : '' }}" style="{{ request()->routeIs('users.*') ? $activeStyle : $inactiveStyle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="font-medium">Pengguna</span>
                </a>
                @endif

                <!-- Section: Referral -->
                @can('referral-codes.own')
                @if(auth()->user()->role !== 'admin')
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--sidebar-text-muted);">Referral</p>
                </div>
                @endif
                <a href="{{ route('referral-codes.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('referral-codes.*') ? 'active' : '' }}" style="{{ request()->routeIs('referral-codes.*') ? $activeStyle : $inactiveStyle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    <span class="font-medium">Kode Referral</span>
                </a>
                @endcan

                <!-- Section: Lainnya -->
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--sidebar-text-muted);">Lainnya</p>
                </div>

                <a href="{{ route('about') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('about') ? 'active' : '' }}" style="{{ request()->routeIs('about') ? $activeStyle : $inactiveStyle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">Tentang</span>
                </a>
            </nav>
        </aside>

        <!-- Overlay -->
        <div class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col lg:ml-64 min-w-0 overflow-x-hidden">
            <!-- Navbar - Sticky -->
            <header class="sticky top-0 z-30 flex items-center justify-between px-6 py-4 border-b" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <div class="flex items-center gap-4">
                    <!-- Mobile Menu Toggle - Only on mobile -->
                    <button @click="sidebarOpen = !sidebarOpen" class="block lg:hidden p-2 rounded-lg transition-colors hover:opacity-80" style="color: var(--text-primary); background-color: var(--bg-input);" aria-label="Buka Menu" title="Buka Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    <!-- Logos -->
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-kab.png') }}" alt="Logo Kabupaten" width="40" height="40" class="h-10 w-auto object-contain cursor-help" onerror="this.style.display='none'" title="Logo Kabupaten Kubu Raya">
                        <img src="{{ asset('images/logo-pbj-kalbar.png') }}" alt="Logo PBJ Kalbar" width="40" height="40" class="h-10 w-auto object-contain cursor-help" onerror="this.style.display='none'" title="Logo PBJ (Pengadaan Barang Jasa) Kalimantan Barat">
                        <div class="border-l pl-3 hidden sm:block cursor-help" style="border-color: var(--border-color);" title="SIBARANG v{{ config('app.version', '0.0.7-beta') }} - Sistem Inventaris Barang Kabupaten Kubu Raya. Dikembangkan untuk manajemen aset dan inventaris pemerintah daerah.">
                            <h1 class="text-sm font-semibold" style="color: var(--text-primary);">Sistem Inventaris Barang</h1>
                            <p class="text-xs" style="color: var(--text-secondary);">Kabupaten Kubu Raya</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Dev Badge + Notifications + Profile -->
                <div class="flex items-center gap-3">
                    <!-- Development Badge (Enhanced from E-Surat-Perkim) -->
                    @if(config('app.debug'))
                    <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-lg mr-2 border-2 border-dashed border-orange-400 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 cursor-help" 
                         title="Mode Development Aktif&#10;&#10;Untuk menonaktifkan:&#10;1. Edit file .env&#10;2. Ubah APP_DEBUG=false&#10;3. Restart server&#10;&#10;⚠️ Jangan lupa nonaktifkan di production!">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-1">
                                <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></div>
                                <span class="text-xs font-bold text-orange-700 dark:text-orange-400">DEVELOPMENT</span>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">|</div>
                            <div class="flex items-center gap-1">
                                <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-mono text-blue-700 dark:text-blue-300">{{ request()->ip() }}</span>
                            </div>
                            @php
                                // Get machine IP (prioritize IPv4)
                                $machineIp = '127.0.0.1';
                                if (!empty($_SERVER['SERVER_ADDR']) && filter_var($_SERVER['SERVER_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                                    $machineIp = $_SERVER['SERVER_ADDR'];
                                } else {
                                    try {
                                        $output = shell_exec('ipconfig');
                                        if ($output && preg_match('/IPv4 Address[.\s]*:\s*([0-9.]+)/', $output, $matches)) {
                                            if (filter_var($matches[1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                                                $machineIp = $matches[1];
                                            }
                                        }
                                    } catch (Exception $e) {
                                        // Keep default
                                    }
                                }
                                $port = request()->getPort();
                            @endphp
                            <div class="text-xs text-gray-500 dark:text-gray-400">|</div>
                            <div class="flex items-center gap-1">
                                <svg class="w-3 h-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                                <span class="text-xs font-mono text-green-700 dark:text-green-300">{{ $machineIp }}:{{ $port }}</span>
                            </div>
                        </div>
                    </div>
                    @else
                    <!-- Production IP Display (Desktop Only) -->
                    <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-lg mr-2" style="background-color: var(--bg-input); border: 1px solid var(--border-color);">
                        <svg class="w-4 h-4" style="color: var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        <span class="text-xs font-mono" style="color: var(--text-primary);" title="IP Address Anda">{{ request()->ip() }}</span>
                    </div>
                    @endif

                    <!-- Theme Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2.5 rounded-xl transition-colors hover:opacity-80" style="color: var(--text-secondary);" title="Ganti Tema">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-40 rounded-xl shadow-xl overflow-hidden border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                            <div class="p-2 text-xs font-semibold uppercase tracking-wider border-b" style="color: var(--text-secondary); border-color: var(--border-color);">Pilih Tema</div>
                            <div class="p-1">
                                <button onclick="setTheme('light')" @click="open = false" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-left hover:opacity-80" style="color: var(--text-primary);">
                                    <span class="w-5 h-5 rounded-full bg-white border-2 border-gray-300"></span>
                                    Light
                                </button>
                                <button onclick="setTheme('dark')" @click="open = false" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-left hover:opacity-80 mt-1" style="color: var(--text-primary);">
                                    <span class="w-5 h-5 rounded-full bg-gray-700 border-2 border-gray-600"></span>
                                    Dark
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Divider -->
                    <div class="w-px h-6" style="background-color: var(--border-color);" title="Pemisah bagian header"></div>

                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-2 rounded-lg hover:opacity-80 relative" style="color: var(--text-secondary);" aria-label="Notifikasi" title="Notifikasi{{ auth()->check() && auth()->user()->unreadNotifications->count() > 0 ? ' (' . auth()->user()->unreadNotifications->count() . ' belum dibaca)' : ' - Tidak ada notifikasi baru' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            @endif
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-80 rounded-lg shadow-xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
                            <div class="p-3 border-b flex justify-between items-center" style="border-color: var(--border-color);">
                                <span class="font-medium" style="color: var(--text-primary);">Notifikasi</span>
                                <a href="{{ route('notifications.index') }}" class="text-xs hover:underline" style="color: var(--accent-color);">Lihat Semua</a>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                @forelse(auth()->check() ? auth()->user()->unreadNotifications->take(5) : [] as $notif)
                                <div class="p-3 border-b" style="border-color: var(--border-color);">
                                    <p class="text-sm" style="color: var(--text-primary);">{{ $notif->data['title'] ?? 'Notifikasi' }}</p>
                                    <p class="text-xs" style="color: var(--text-secondary);">{{ $notif->created_at->diffForHumans() }}</p>
                                </div>
                                @empty
                                <div class="p-4 text-center text-sm" style="color: var(--text-secondary);">Tidak ada notifikasi baru</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-lg hover:opacity-80" style="background-color: var(--bg-input);" aria-label="Profil Pengguna" title="Profil Pengguna">
                            <img src="{{ auth()->user()->avatar_url }}" class="w-8 h-8 rounded-full object-cover" alt="">
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-medium" style="color: var(--text-primary);">{{ auth()->user()->name }}</p>
                                <p class="text-xs" style="color: var(--text-secondary);">{{ ucfirst(auth()->user()->role ?? 'User') }}</p>
                            </div>
                            <svg class="w-4 h-4" style="color: var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-56 rounded-lg shadow-xl border py-1" style="background-color: var(--bg-card); border-color: var(--border-color);">
                            <div class="px-4 py-3 border-b" style="border-color: var(--border-color);">
                                <p class="text-sm font-medium" style="color: var(--text-primary);">{{ auth()->user()->name }}</p>
                                <p class="text-xs truncate" style="color: var(--text-secondary);">{{ auth()->user()->email }}</p>
                                <div class="mt-2">
                                    <span class="inline-block px-2 py-0.5 text-xs rounded" style="background-color: var(--bg-input); color: var(--text-secondary);">{{ ucfirst(auth()->user()->role ?? 'User') }}</span>
                                    
                                    @if(config('app.debug'))
                                    <!-- Development Info for Mobile -->
                                    <div class="lg:hidden mt-2 p-2 rounded border-2 border-dashed border-orange-400 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20" title="Mode Development Aktif">
                                        <div class="flex items-center gap-1 mb-1">
                                            <div class="w-1.5 h-1.5 bg-orange-500 rounded-full animate-pulse"></div>
                                            <span class="text-xs font-bold text-orange-700 dark:text-orange-400">DEV MODE</span>
                                        </div>
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-1 text-xs">
                                                <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                <span class="font-mono text-blue-700 dark:text-blue-300">{{ request()->ip() }}</span>
                                            </div>
                                            @php
                                                $machineIp = '127.0.0.1';
                                                if (!empty($_SERVER['SERVER_ADDR']) && filter_var($_SERVER['SERVER_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                                                    $machineIp = $_SERVER['SERVER_ADDR'];
                                                } else {
                                                    try {
                                                        $output = shell_exec('ipconfig');
                                                        if ($output && preg_match('/IPv4 Address[.\s]*:\s*([0-9.]+)/', $output, $matches)) {
                                                            if (filter_var($matches[1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                                                                $machineIp = $matches[1];
                                                            }
                                                        }
                                                    } catch (Exception $e) {
                                                        // Keep default
                                                    }
                                                }
                                                $port = request()->getPort();
                                            @endphp
                                            <div class="flex items-center gap-1 text-xs">
                                                <svg class="w-3 h-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                                                <span class="font-mono text-green-700 dark:text-green-300">{{ $machineIp }}:{{ $port }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <!-- IP for Mobile - Production Only -->
                                    <div class="lg:hidden flex items-center gap-1 px-2 py-0.5 rounded text-xs mt-2 w-fit" style="background-color: var(--bg-input); color: var(--text-secondary);" title="IP Address Anda">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                        <span class="font-mono">{{ request()->ip() }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:opacity-80" style="color: var(--text-primary);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Pengaturan Profil
                            </a>
                            <div class="border-t my-1" style="border-color: var(--border-color);"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm hover:opacity-80 text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @if(session('success'))
                <div class="mb-4">
                    <x-alert type="success" :message="session('success')" />
                </div>
                @endif

                @if(session('error'))
                <div class="mb-4">
                    <x-alert type="error" :message="session('error')" />
                </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Gallery Lightbox -->
    <div id="gallery-lightbox" class="fixed inset-0 z-[9999] hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/90 backdrop-blur-sm" onclick="closeGallery()"></div>
        
        <!-- Header -->
        <div class="absolute top-0 left-0 right-0 p-4 flex items-center justify-between z-10">
            <div class="flex items-center gap-4">
                <span id="gallery-counter" class="text-white/80 text-sm font-medium bg-black/30 px-3 py-1 rounded-full">1 / 1</span>
                <span id="gallery-filename" class="text-white/80 text-sm truncate max-w-xs hidden sm:block"></span>
            </div>
            <div class="flex items-center gap-2">
                <a id="gallery-download" href="#" target="_blank" class="p-2 text-white/80 hover:text-white bg-black/30 rounded-lg" title="Download">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </a>
                <button onclick="closeGallery()" class="p-2 text-white/80 hover:text-white bg-black/30 rounded-lg" title="Tutup (ESC)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="absolute inset-0 flex items-center justify-center p-4 pt-16 pb-24">
            <div id="gallery-content" class="max-w-full max-h-full flex items-center justify-center"></div>
        </div>
        
        <!-- Navigation Buttons -->
        <button id="gallery-prev" onclick="prevGallery()" class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 p-3 text-white hover:text-white bg-black/50 hover:bg-black/70 rounded-full transition-all z-10 hidden">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button id="gallery-next" onclick="nextGallery()" class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 p-3 text-white hover:text-white bg-black/50 hover:bg-black/70 rounded-full transition-all z-10 hidden">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
        
        <!-- Zoom Controls -->
        <div id="gallery-zoom-controls" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 bg-black/40 backdrop-blur-sm rounded-full px-3 py-2">
            <button onclick="zoomOut()" class="p-1 text-white/80 hover:text-white" title="Zoom Out (-)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
            </button>
            <span id="gallery-zoom" class="text-white/80 text-sm min-w-[50px] text-center">100%</span>
            <button onclick="zoomIn()" class="p-1 text-white/80 hover:text-white" title="Zoom In (+)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </button>
            <div class="w-px h-4 bg-white/20 mx-1"></div>
            <button onclick="resetZoom()" class="p-1 text-white/80 hover:text-white text-xs">Reset</button>
        </div>
        
        <!-- Footer Hint -->
        <div class="absolute bottom-12 left-1/2 -translate-x-1/2 text-white/40 text-xs text-center hidden sm:block">
            Klik gambar untuk zoom • Scroll +/- • ← → Navigasi • ESC Tutup
        </div>
    </div>

    <!-- Modal CSS & JS -->
    <style>
        /* Modal Styles - hidden by default via inline style, shown via JS */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index: 9998;
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        .modal-backdrop.active {
            opacity: 1;
        }
        
        .modal-content {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            z-index: 9999;
            max-height: 90vh;
            overflow-y: auto;
            opacity: 0;
            transition: all 0.2s ease;
        }
        .modal-content.active {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
    </style>

    <script>
        // Modal state for accessibility
        let previousActiveElement = null;
        
        // Modal Functions with accessibility
        function openModal(modalId) {
            previousActiveElement = document.activeElement;
            const modal = document.getElementById(modalId);
            const backdrop = document.getElementById(modalId + '-backdrop');
            if (modal && backdrop) {
                backdrop.style.display = 'block';
                modal.style.display = 'block';
                // ARIA attributes
                backdrop.setAttribute('aria-hidden', 'false');
                modal.setAttribute('aria-hidden', 'false');
                // Trigger reflow for animation
                void modal.offsetWidth;
                backdrop.classList.add('active');
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
                // Focus first focusable element
                setTimeout(() => {
                    const firstInput = modal.querySelector('input, select, textarea, button');
                    if (firstInput) firstInput.focus();
                }, 100);
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            const backdrop = document.getElementById(modalId + '-backdrop');
            if (modal && backdrop) {
                backdrop.classList.remove('active');
                modal.classList.remove('active');
                document.body.style.overflow = '';
                // ARIA attributes
                backdrop.setAttribute('aria-hidden', 'true');
                modal.setAttribute('aria-hidden', 'true');
                // Hide after animation
                setTimeout(() => {
                    backdrop.style.display = 'none';
                    modal.style.display = 'none';
                }, 200);
                // Return focus
                if (previousActiveElement) {
                    previousActiveElement.focus();
                    previousActiveElement = null;
                }
            }
        }

        // Close modal on backdrop click
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-backdrop') && e.target.classList.contains('active')) {
                const modalId = e.target.id.replace('-backdrop', '');
                closeModal(modalId);
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-backdrop.active').forEach(el => {
                    const modalId = el.id.replace('-backdrop', '');
                    closeModal(modalId);
                });
            }
        });

        // Preview multiple files before upload (dari E-Surat-Perkim)
        function previewMultipleFiles(input, previewContainerId) {
            const container = document.getElementById(previewContainerId);
            if (!container) return;
            container.innerHTML = '';
            
            if (input.files && input.files.length > 0) {
                let totalSize = 0;
                const files = Array.from(input.files);
                files.forEach(f => totalSize += f.size);
                const totalMB = (totalSize / 1024 / 1024).toFixed(1);
                
                // Summary + toggle button
                const summaryDiv = document.createElement('div');
                summaryDiv.className = 'flex items-center justify-between py-2';
                summaryDiv.innerHTML = `
                    <span class="text-sm" style="color: var(--text-primary);"><strong>${files.length}</strong> file dipilih • Total: <strong>${totalMB} MB</strong></span>
                    <button type="button" onclick="togglePreviewTable('${previewContainerId}-table')" class="text-xs px-2 py-1 rounded" style="background-color: var(--accent-color); color: white;">
                        <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>Lihat Preview
                    </button>`;
                container.appendChild(summaryDiv);
                
                // Table (hidden by default)
                const tableDiv = document.createElement('div');
                tableDiv.id = previewContainerId + '-table';
                tableDiv.className = 'hidden mt-2 rounded-lg border overflow-hidden';
                tableDiv.style.cssText = 'border-color: var(--border-color);';
                
                let tableHTML = `<table class="w-full text-sm">
                    <thead style="background-color: var(--bg-input);">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium" style="color: var(--text-secondary);">Preview</th>
                            <th class="px-3 py-2 text-left text-xs font-medium" style="color: var(--text-secondary);">Nama File</th>
                            <th class="px-3 py-2 text-left text-xs font-medium" style="color: var(--text-secondary);">Tipe</th>
                            <th class="px-3 py-2 text-right text-xs font-medium" style="color: var(--text-secondary);">Ukuran</th>
                        </tr>
                    </thead>
                    <tbody>`;
                
                files.forEach((file, idx) => {
                    const ext = file.name.split('.').pop().toLowerCase();
                    const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(ext);
                    const sizeMB = file.size > 1024*1024 ? (file.size/1024/1024).toFixed(1) + ' MB' : (file.size/1024).toFixed(0) + ' KB';
                    const typeBadge = isImage ? 'bg-green-100 text-green-700' : (ext === 'pdf' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700');
                    
                    tableHTML += `<tr style="border-top: 1px solid var(--border-color);">
                        <td class="px-3 py-2">
                            <div id="thumb-${previewContainerId}-${idx}" class="w-10 h-10 rounded flex items-center justify-center" style="background-color: var(--bg-input);">
                                <svg class="w-6 h-6" style="color: var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    ${isImage ? 
                                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>' :
                                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'
                                    }
                                </svg>
                            </div>
                        </td>
                        <td class="px-3 py-2" style="color: var(--text-primary);">
                            <p class="truncate max-w-[200px]">${file.name}</p>
                        </td>
                        <td class="px-3 py-2">
                            <span class="px-2 py-0.5 text-xs rounded ${typeBadge}">${ext.toUpperCase()}</span>
                        </td>
                        <td class="px-3 py-2 text-right" style="color: var(--text-secondary);">${sizeMB}</td>
                    </tr>`;
                });
                
                tableHTML += '</tbody></table>';
                tableDiv.innerHTML = tableHTML;
                container.appendChild(tableDiv);
                
                // Generate image thumbnails
                files.forEach((file, idx) => {
                    const ext = file.name.split('.').pop().toLowerCase();
                    const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(ext);
                    if (isImage) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const thumb = document.getElementById(`thumb-${previewContainerId}-${idx}`);
                            if (thumb) thumb.innerHTML = `<img src="${e.target.result}" class="w-10 h-10 rounded object-cover">`;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        }
        
        function togglePreviewTable(tableId) {
            const table = document.getElementById(tableId);
            if (table) {
                table.classList.toggle('hidden');
                const btn = table.previousElementSibling.querySelector('button');
                if (btn) {
                    const isHidden = table.classList.contains('hidden');
                    btn.innerHTML = isHidden ? 
                        '<svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>Lihat Preview' : 
                        '<svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/></svg>Sembunyikan';
                }
            }
        }

        // Gallery Lightbox Functions
        let galleryFiles = [];
        let galleryIndex = 0;
        let galleryZoom = 1;
        let isDragging = false;
        let dragStart = { x: 0, y: 0 };
        let imgPos = { x: 0, y: 0 };
        let dragMoved = false;
        
        function openGallery(files, startIndex = 0) {
            galleryFiles = files;
            galleryIndex = startIndex;
            showGalleryItem();
            document.getElementById('gallery-lightbox').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            const prevBtn = document.getElementById('gallery-prev');
            const nextBtn = document.getElementById('gallery-next');
            if (files.length > 1) {
                prevBtn.classList.remove('hidden');
                nextBtn.classList.remove('hidden');
            } else {
                prevBtn.classList.add('hidden');
                nextBtn.classList.add('hidden');
            }
        }
        
        function closeGallery() {
            document.getElementById('gallery-lightbox').classList.add('hidden');
            document.body.style.overflow = '';
            resetZoom();
        }
        
        function prevGallery() {
            galleryIndex = (galleryIndex - 1 + galleryFiles.length) % galleryFiles.length;
            resetZoom();
            showGalleryItem();
        }
        
        function nextGallery() {
            galleryIndex = (galleryIndex + 1) % galleryFiles.length;
            resetZoom();
            showGalleryItem();
        }
        
        function resetZoom() {
            galleryZoom = 1;
            imgPos = { x: 0, y: 0 };
            applyZoom();
        }
        
        function zoomIn() {
            galleryZoom = Math.min(galleryZoom + 0.5, 4);
            applyZoom();
        }
        
        function zoomOut() {
            galleryZoom = Math.max(galleryZoom - 0.5, 1);
            if (galleryZoom === 1) imgPos = { x: 0, y: 0 };
            applyZoom();
        }
        
        function toggleZoom() {
            if (galleryZoom > 1) {
                galleryZoom = 1;
                imgPos = { x: 0, y: 0 };
            } else {
                galleryZoom = 2;
            }
            applyZoom();
        }
        
        function applyZoom() {
            const img = document.querySelector('#gallery-content img');
            if (img) {
                img.style.transform = `translate(${imgPos.x}px, ${imgPos.y}px) scale(${galleryZoom})`;
                img.style.cursor = galleryZoom > 1 ? (isDragging ? 'grabbing' : 'grab') : 'zoom-in';
            }
            const display = document.getElementById('gallery-zoom');
            if (display) display.textContent = Math.round(galleryZoom * 100) + '%';
        }
        
        function showGalleryItem() {
            const file = galleryFiles[galleryIndex];
            const ext = file.name.split('.').pop().toLowerCase();
            const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(ext);
            const isPdf = ext === 'pdf';
            
            const content = document.getElementById('gallery-content');
            const counter = document.getElementById('gallery-counter');
            const filename = document.getElementById('gallery-filename');
            const downloadBtn = document.getElementById('gallery-download');
            const zoomControls = document.getElementById('gallery-zoom-controls');
            
            counter.textContent = `${galleryIndex + 1} / ${galleryFiles.length}`;
            filename.textContent = file.name;
            downloadBtn.href = file.url;
            
            if (isImage) {
                zoomControls.classList.remove('hidden');
                // Sanitize file.url before using in innerHTML
                const sanitizedUrl = file.url.replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                content.innerHTML = `<img src="${sanitizedUrl}" class="max-w-full max-h-[70vh] rounded-lg shadow-2xl transition-transform duration-100 cursor-zoom-in select-none" id="gallery-img" draggable="false">`;
                applyZoom();
                
                const img = document.getElementById('gallery-img');
                img.addEventListener('click', (e) => {
                    if (!dragMoved) toggleZoom();
                });
                img.addEventListener('mousedown', (e) => {
                    if (galleryZoom > 1) {
                        e.preventDefault();
                        isDragging = true;
                        dragStart = { x: e.clientX - imgPos.x, y: e.clientY - imgPos.y };
                        img.style.cursor = 'grabbing';
                    }
                });
            } else if (isPdf) {
                zoomControls.classList.add('hidden');
                content.innerHTML = `<iframe src="${file.url}" class="w-full max-w-4xl h-[70vh] rounded-lg bg-white"></iframe>`;
            } else {
                zoomControls.classList.add('hidden');
                content.innerHTML = `
                    <div class="text-center p-12 rounded-lg" style="background-color: var(--bg-card);">
                        <svg class="w-16 h-16 mx-auto mb-4" style="color: var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-lg font-medium" style="color: var(--text-primary);">${file.name}</p>
                        <p class="text-sm mt-2" style="color: var(--text-secondary);">Preview tidak tersedia</p>
                    </div>`;
            }
        }
        
        // Legacy single file preview
        function previewFile(url, filename) {
            openGallery([{ url, name: filename }], 0);
        }
        
        // Gallery event listeners
        document.getElementById('gallery-lightbox')?.addEventListener('wheel', (e) => {
            const img = document.querySelector('#gallery-content img');
            if (img) {
                e.preventDefault();
                if (e.deltaY < 0) zoomIn();
                else zoomOut();
            }
        }, { passive: false });
        
        document.addEventListener('mousemove', (e) => {
            if (isDragging && galleryZoom > 1) {
                dragMoved = true;
                imgPos = { x: e.clientX - dragStart.x, y: e.clientY - dragStart.y };
                applyZoom();
            }
        });
        
        document.addEventListener('mouseup', () => {
            if (isDragging) {
                setTimeout(() => { isDragging = false; dragMoved = false; }, 10);
                const img = document.getElementById('gallery-img');
                if (img) img.style.cursor = galleryZoom > 1 ? 'grab' : 'zoom-in';
            }
        });
        
        document.addEventListener('keydown', (e) => {
            const gallery = document.getElementById('gallery-lightbox');
            if (gallery && !gallery.classList.contains('hidden')) {
                if (e.key === 'Escape') closeGallery();
                if (e.key === 'ArrowLeft') prevGallery();
                if (e.key === 'ArrowRight') nextGallery();
                if (e.key === '+' || e.key === '=') zoomIn();
                if (e.key === '-') zoomOut();
                if (e.key === '0') resetZoom();
            }
        });

        // Form Validation dengan AJAX
        function handleFormSubmit(form, event) {
            event.preventDefault();
            
            // Get form data
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Menyimpan...';
            
            // Submit form via AJAX
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        location.reload();
                    }
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Form submission error:', error);
                
                let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                
                // Show detailed error in development
                @if(config('app.debug'))
                if (error.message) {
                    errorMessage = error.message;
                } else if (error.response) {
                    errorMessage = `Error ${error.response.status}: ${error.response.statusText}`;
                }
                @endif
                
                // Parse validation errors
                if (error.response && error.response.status === 422) {
                    error.response.json().then(errorData => {
                        if (errorData.errors) {
                            const firstError = Object.values(errorData.errors)[0];
                            errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                        } else if (errorData.message) {
                            errorMessage = errorData.message;
                        }
                        showValidationError(errorMessage);
                    }).catch(() => {
                        showValidationError(errorMessage);
                    });
                } else {
                    showValidationError(errorMessage);
                }
            })
            .finally(() => {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
            
            return false; // Prevent default form submission
        }
        
        function showValidationError(message) {
            alert('Error: ' + message);
        }

        // Auto-show validation errors dari session
        @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            const errors = @json($errors->all());
            let errorMessage = errors.length > 0 ? errors[0] : 'Terjadi kesalahan validasi';
            showValidationError(errorMessage);
        });
        @endif

        // Skip links for screen readers
        document.addEventListener('DOMContentLoaded', function() {
            const skipLink = document.createElement('a');
            skipLink.href = '#main-content';
            skipLink.textContent = 'Skip to main content';
            skipLink.className = 'sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-primary-600 focus:text-white focus:rounded';
            document.body.insertBefore(skipLink, document.body.firstChild);
            
            const mainContent = document.querySelector('main') || document.querySelector('.main-content');
            if (mainContent) {
                mainContent.id = 'main-content';
                mainContent.setAttribute('role', 'main');
            }
        });
        
        // Global Unsaved Changes Warning
        let formChanged = false;
        document.addEventListener('DOMContentLoaded', function() {
            // Track all form inputs globally
            const allForms = document.querySelectorAll('form:not([data-no-warn])');
            allForms.forEach(form => {
                const inputs = form.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    // Skip hidden, submit, and button inputs
                    if (input.type === 'hidden' || input.type === 'submit' || input.type === 'button') return;
                    input.addEventListener('change', () => { formChanged = true; });
                    input.addEventListener('input', () => { formChanged = true; });
                });
                
                // Reset flag on form submit
                form.addEventListener('submit', () => { formChanged = false; });
            });
            
            // Also reset on modal close (to avoid false positives)
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                backdrop.addEventListener('click', () => { formChanged = false; });
            });
        });
        
        // Warn before leaving if changes exist
        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = 'Ada perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
                return e.returnValue;
            }
        });

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
    </script>

    @stack('scripts')
</body>
</html>

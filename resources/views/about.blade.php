@section('meta-description', 'Kenali lebih jauh tentang sistem inventaris barang kami. Fitur lengkap untuk manajemen aset, pelacakan barang, dan laporan inventaris yang komprehensif.')
<x-app-layout title="Tentang Aplikasi">
    <div class="max-w-7xl mx-auto">
        
        <!-- Hero Section -->
        <div class="rounded-2xl p-8 mb-8 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold mb-4" style="color: var(--text-primary);">Sistem Inventaris Barang</h1>
                    <p class="text-lg mb-6" style="color: var(--text-secondary);">Dikembangkan oleh <span class="font-semibold" style="color: var(--accent-color);">Ferdi</span> - Solusi modern untuk pengelolaan aset dan inventaris dengan fitur lengkap, tracking real-time, dan laporan komprehensif.</p>
                    <div class="flex flex-wrap gap-3">
                        <span class="px-3 py-1 rounded-full text-sm" style="background-color: var(--bg-input); color: var(--text-secondary);">v1.0.0</span>
                        <span class="px-3 py-1 rounded-full text-sm" style="background-color: var(--bg-input); color: var(--text-secondary);">Laravel 12</span>
                        <span class="px-3 py-1 rounded-full text-sm" style="background-color: var(--bg-input); color: var(--text-secondary);">Tailwind CSS</span>
                    </div>
                </div>
                <div class="hidden lg:flex justify-center">
                    <img src="/images/logo-pbj-kalbar.png?v={{ time() }}" alt="Logo PBJ Kalbar" width="192" height="192" class="w-48 h-48 object-contain">
                </div>
            </div>
        </div>

        <!-- Fitur Utama Section -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-8">
            <div class="rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2" style="color: var(--text-primary);">
                    <svg class="w-5 h-5" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Fitur Lengkap
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-4 rounded-lg" style="background-color: var(--bg-input);">
                        <h4 class="font-medium mb-2" style="color: var(--text-primary);">Manajemen Aset</h4>
                        <p class="text-sm" style="color: var(--text-secondary);">Kelola seluruh inventaris barang dengan sistem pencatatan yang terstruktur dan mudah dilacak.</p>
                    </div>
                    <div class="p-4 rounded-lg" style="background-color: var(--bg-input);">
                        <h4 class="font-medium mb-2" style="color: var(--text-primary);">Tracking Real-time</h4>
                        <p class="text-sm" style="color: var(--text-secondary);">Pantau perpindahan, kondisi, dan status barang secara real-time dengan notifikasi otomatis.</p>
                    </div>
                    <div class="p-4 rounded-lg" style="background-color: var(--bg-input);">
                        <h4 class="font-medium mb-2" style="color: var(--text-primary);">Laporan Komprehensif</h4>
                        <p class="text-sm" style="color: var(--text-secondary);">Generate laporan berdasarkan kategori, lokasi, kondisi, dan periode waktu tertentu.</p>
                    </div>
                    <div class="p-4 rounded-lg" style="background-color: var(--bg-input);">
                        <h4 class="font-medium mb-2" style="color: var(--text-primary);">Multi-user & Role</h4>
                        <p class="text-sm" style="color: var(--text-secondary);">Sistem permission berbasis role untuk kontrol akses yang aman dan terstruktur.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tech Stack Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            
            <!-- Backend Stack -->
            <div class="rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2" style="color: var(--text-primary);">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                    </svg>
                    Backend Stack
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-lg" style="background-color: var(--bg-input);">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">Laravel</p>
                                <p class="text-xs" style="color: var(--text-secondary);">PHP Framework</p>
                            </div>
                        </div>
                        <span class="text-sm font-mono" style="color: var(--text-secondary);">v12.x</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg" style="background-color: var(--bg-input);">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">PHP</p>
                                <p class="text-xs" style="color: var(--text-secondary);">Server Language</p>
                            </div>
                        </div>
                        <span class="text-sm font-mono" style="color: var(--text-secondary);">v8.3</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg" style="background-color: var(--bg-input);">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7m0 2a2 2 0 012-2h12a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">MySQL</p>
                                <p class="text-xs" style="color: var(--text-secondary);">Database</p>
                            </div>
                        </div>
                        <span class="text-sm font-mono" style="color: var(--text-secondary);">v8.x</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg" style="background-color: var(--bg-input);">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">Spatie Permission</p>
                                <p class="text-xs" style="color: var(--text-secondary);">Role & Permission</p>
                            </div>
                        </div>
                        <span class="text-sm font-mono" style="color: var(--text-secondary);">v6.x</span>
                    </div>
                </div>
            </div>

            <!-- Frontend Stack -->
            <div class="rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2" style="color: var(--text-primary);">
                    <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Frontend Stack
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-lg" style="background-color: var(--bg-input);">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-cyan-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">Tailwind CSS</p>
                                <p class="text-xs" style="color: var(--text-secondary);">CSS Framework</p>
                            </div>
                        </div>
                        <span class="text-sm font-mono" style="color: var(--text-secondary);">v3.x</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg" style="background-color: var(--bg-input);">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">Alpine.js</p>
                                <p class="text-xs" style="color: var(--text-secondary);">Interactive UI</p>
                            </div>
                        </div>
                        <span class="text-sm font-mono" style="color: var(--text-secondary);">v3.x</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg" style="background-color: var(--bg-input);">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M13 9h5.5L13 3.5V9M6 2h8l6 6v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2m5 16v-4h4v4h-4z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">Vite</p>
                                <p class="text-xs" style="color: var(--text-secondary);">Build Tool</p>
                            </div>
                        </div>
                        <span class="text-sm font-mono" style="color: var(--text-secondary);">v5.x</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-lg" style="background-color: var(--bg-input);">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19.5A2.5 2.5 0 016.5 17H20"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium" style="color: var(--text-primary);">SweetAlert2</p>
                                <p class="text-xs" style="color: var(--text-secondary);">Alert & Modal</p>
                            </div>
                        </div>
                        <span class="text-sm font-mono" style="color: var(--text-secondary);">v11.x</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="rounded-xl border p-6 mb-8" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <h3 class="text-lg font-semibold mb-6 flex items-center gap-2" style="color: var(--text-primary);">
                <svg class="w-5 h-5" style="color: var(--accent-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                Fitur Utama
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach([
                    ['path' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'title' => 'Master Barang', 'desc' => 'CRUD lengkap'],
                    ['path' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z', 'title' => 'Kategori', 'desc' => 'Grouping barang'],
                    ['path' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z', 'title' => 'Lokasi', 'desc' => 'Multi lokasi'],
                    ['path' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'title' => 'Transfer', 'desc' => 'Perpindahan barang'],
                    ['path' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'title' => 'Maintenance', 'desc' => 'Log perbaikan'],
                    ['path' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16', 'title' => 'Disposal', 'desc' => 'Penghapusan'],
                    ['path' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Laporan', 'desc' => 'Multi format'],
                    ['path' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'title' => 'User & Role', 'desc' => 'Multi permission']
                ] as $feature)
                <div class="p-4 rounded-lg text-center border transition-all hover:shadow-lg" style="background-color: var(--bg-input); border-color: var(--border-color);">
                    <div class="w-12 h-12 rounded-lg mx-auto mb-3 flex items-center justify-center bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h4 class="font-medium text-sm" style="color: var(--text-primary);">{{ $feature['title'] }}</h4>
                    <p class="text-xs" style="color: var(--text-secondary);">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Developer Section -->
        <div class="rounded-2xl p-8 mb-8 border" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                
                <!-- Ferdi Card -->
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold text-white" style="background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);">
                            F
                        </div>
                        <div>
                            <h3 class="text-xl font-bold" style="color: var(--text-primary);">Ferdi</h3>
                            <p class="text-sm" style="color: var(--text-secondary);">Developer & Author</p>
                        </div>
                    </div>
                    <p class="text-sm mb-4" style="color: var(--text-secondary);">
                        Aplikasi ini dikembangkan sebagai solusi manajemen inventaris modern dengan fokus pada kemudahan penggunaan dan efisiensi.
                    </p>
                    <div class="flex gap-2">
                        <span class="px-2 py-1 rounded text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);">Full Stack</span>
                        <span class="px-2 py-1 rounded text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);">Laravel</span>
                    </div>
                </div>

                <!-- Risuncode Card -->
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold text-white" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            R
                        </div>
                        <div>
                            <h3 class="text-xl font-bold" style="color: var(--text-primary);">Risuncode</h3>
                            <p class="text-sm" style="color: var(--text-secondary);">Templating Base</p>
                        </div>
                    </div>
                    <p class="text-sm mb-4" style="color: var(--text-secondary);">
                        Template dasar yang digunakan sebagai fondasi pengembangan aplikasi dengan struktur yang modern dan best practices.
                    </p>
                    <div class="flex gap-2">
                        <span class="px-2 py-1 rounded text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);">Template</span>
                        <span class="px-2 py-1 rounded text-xs" style="background-color: var(--bg-input); color: var(--text-secondary);">Base Architecture</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- App Info -->
        <div class="rounded-xl border p-6 text-center" style="background-color: var(--bg-card); border-color: var(--border-color);">
            <h2 class="text-2xl font-bold mb-2" style="color: var(--text-primary);">SIBARANG</h2>
            <p class="text-sm" style="color: var(--text-secondary);">Sistem Inventaris Barang</p>
            <p class="text-xs mt-1" style="color: var(--text-secondary);">Version {{ config('sibarang.version', '0.0.7-beta-007') }}</p>
        </div>

    </div>
</x-app-layout>

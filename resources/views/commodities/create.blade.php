<x-app-layout title="Tambah Barang">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('commodities.index') }}" class="text-sm hover:underline flex items-center gap-1" style="color: var(--text-secondary);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-2xl font-bold mt-2" style="color: var(--text-primary);">Tambah Barang Baru</h2>
        </div>

        <form action="{{ route('commodities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Grid Layout: 2 Kolom Utama -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                
                <!-- Left Column (2/3 width) -->
                <div class="xl:col-span-2 space-y-6">
                    
                    <!-- Informasi Dasar -->
                    <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Informasi Dasar</h3>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <x-form.input label="Nama Barang" name="name" required placeholder="Masukkan nama barang" />
                            </div>
                            <div>
                                <label class="form-label">Kode Barang</label>
                                <div class="flex gap-2">
                                    <input type="text" name="item_code" id="itemCodeInput" class="form-input font-mono flex-1" 
                                           placeholder="Otomatis berdasarkan kategori" value="{{ old('item_code') }}" 
                                           style="font-family: 'Consolas', 'Monaco', 'Courier New', monospace;">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    <span id="codeHint">Pilih kategori untuk generate otomatis atau input manual</span>
                                </p>
                                @error('item_code')
                                <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label">Kategori <span class="text-red-500">*</span></label>
                                <select name="category_id" id="categorySelect" class="form-input" required onchange="updateItemCodePreview()">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-code="{{ $category->code }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} {{ $category->code ? "({$category->code})" : '' }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                                    Lokasi <span class="text-red-500">*</span>
                                </label>
                                <div class="space-y-3">
                                    <select name="location_id" id="locationSelect" class="input w-full" onchange="toggleCustomLocation()">
                                        <option value="">Pilih dari daftar lokasi</option>
                                        @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }} - {{ $location->building ?? 'Gedung' }} {{ $location->floor ?? '' }} {{ $location->room ?? '' }}
                                        </option>
                                        @endforeach
                                        <option value="custom" {{ old('location_id') == 'custom' ? 'selected' : '' }}>🏷️ Input Manual / Lainnya</option>
                                    </select>
                                    
                                    <div id="customLocationInput" class="hidden">
                                        <input type="text" name="custom_location" id="customLocation" 
                                               placeholder="Contoh: Ruang Server Lt.3, Gudang Belakang, dll..." 
                                               class="input w-full" 
                                               value="{{ old('custom_location') }}">
                                        <p class="text-xs mt-1" style="color: var(--text-secondary);">Masukkan lokasi sesuai kebutuhan</p>
                                    </div>
                                </div>
                                @error('location_id')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                @error('custom_location')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
                            <x-form.input label="Merk/Brand" name="brand" placeholder="Contoh: HP, Dell" />
                            <x-form.input label="Model/Tipe" name="model" placeholder="Contoh: Pavilion 14" />
                            <x-form.input label="Serial Number" name="serial_number" placeholder="Nomor seri (opsional)" />
                        </div>
                    </div>

                    <!-- Detail Perolehan -->
                    <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Detail Perolehan</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <x-form.select label="Cara Perolehan" name="acquisition_type" required :options="[
                                'pembelian' => 'Pembelian',
                                'hibah' => 'Hibah',
                                'bantuan' => 'Bantuan',
                                'produksi' => 'Produksi Sendiri',
                                'lainnya' => 'Lainnya'
                            ]" />
                            <x-form.input label="Sumber Perolehan" name="acquisition_source" placeholder="Contoh: PT ABC, Donatur" />
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
                            <x-form.input label="Tahun Perolehan" name="purchase_year" type="number" min="1900" max="{{ date('Y') }}" placeholder="{{ date('Y') }}" />
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Harga Perolehan</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm" style="color: var(--text-secondary);">Rp</span>
                                    <input type="text" id="purchase_price_display" 
                                           class="input pl-10" 
                                           placeholder="0"
                                           oninput="formatRupiahInput(this)"
                                           onchange="updatePriceHidden(this)">
                                    <input type="hidden" name="purchase_price" id="purchase_price" value="{{ old('purchase_price', 0) }}">
                                </div>
                            </div>
                            <x-form.input label="Jumlah" name="quantity" type="number" min="1" value="1" required />
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
                            <x-form.select label="Kondisi" name="condition" required :options="[
                                'baik' => 'Baik',
                                'rusak_ringan' => 'Rusak Ringan',
                                'rusak_berat' => 'Rusak Berat'
                            ]" />
                            <x-form.input label="Penanggung Jawab" name="responsible_person" placeholder="Nama penanggung jawab barang" />
                        </div>
                    </div>

                </div>
                
                <!-- Right Column (1/3 width) -->
                <div class="space-y-6">
                    
                    <!-- Foto Barang -->
                    <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Foto Barang</h3>
                        <div class="space-y-3">
                            <input type="file" name="images[]" id="images-upload" multiple accept="image/*,.pdf,.doc,.docx" class="w-full text-sm theme-input border rounded-lg p-2" style="background-color: var(--bg-input); border-color: var(--border-color); color: var(--text-primary);" onchange="previewMultipleFiles(this, 'preview-images')">
                            <p class="text-xs" style="color: var(--text-secondary);">Upload maksimal 5 file (JPG, PNG, PDF, DOC max 2MB per file). Gambar pertama akan menjadi foto utama.</p>
                            <div id="preview-images"></div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Informasi Tambahan</h3>
                        <div class="space-y-4">
                            <x-form.textarea label="Spesifikasi" name="specifications" rows="3" placeholder="Spesifikasi teknis barang (opsional)" />
                            <x-form.textarea label="Catatan" name="notes" rows="2" placeholder="Catatan tambahan (opsional)" />
                        </div>
                    </div>

                </div>
            </div>

            <!-- Submit Actions -->
            <div class="flex justify-end gap-3 pt-6">
                <a href="{{ route('commodities.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Barang
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function formatRupiahInput(input) {
            // Remove non-digits
            let value = input.value.replace(/\D/g, '');
            // Format with dots
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = value;
            // Update hidden field
            updatePriceHidden(input);
        }
        
        function updatePriceHidden(input) {
            const numericValue = input.value.replace(/\./g, '');
            document.getElementById('purchase_price').value = numericValue || 0;
        }

        // Toggle custom location input
        function toggleCustomLocation() {
            const select = document.getElementById('locationSelect');
            const customInput = document.getElementById('customLocationInput');
            const customField = document.getElementById('customLocation');
            
            if (select.value === 'custom') {
                customInput.classList.remove('hidden');
                customField.setAttribute('required', 'required');
            } else {
                customInput.classList.add('hidden');
                customField.removeAttribute('required');
                customField.value = '';
            }
        }

        // Update item code when category changes
        function updateItemCodePreview() {
            const categorySelect = document.getElementById('categorySelect');
            const codeHint = document.getElementById('codeHint');
            const itemCodeInput = document.getElementById('itemCodeInput');
            const categoryId = categorySelect.value;
            
            if (categoryId) {
                const selectedOption = categorySelect.options[categorySelect.selectedIndex];
                const categoryCode = selectedOption.getAttribute('data-code') || 'INV';
                codeHint.textContent = `Format: ${categoryCode}-${new Date().getFullYear()}-XXXX`;
                
                // Auto-generate kode jika input kosong atau placeholder
                const shouldGenerate = !itemCodeInput.value || 
                                     itemCodeInput.value === 'Loading...' || 
                                     itemCodeInput.value === 'Otomatis berdasarkan kategori' ||
                                     itemCodeInput.getAttribute('data-auto-generated') !== 'false';
                
                if (shouldGenerate) {
                    
                    // Show loading state
                    itemCodeInput.value = 'Loading...';
                    itemCodeInput.disabled = true;
                    
                    // Fetch new code from server
                    fetch(`{{ route('commodities.preview-code') }}?category_id=${categoryId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                        .then(response => response.json())
                        .then(data => {
                            itemCodeInput.value = data.code;
                            itemCodeInput.setAttribute('data-auto-generated', 'true');
                            itemCodeInput.disabled = false;
                        })
                        .catch(error => {
                            itemCodeInput.value = '';
                            itemCodeInput.setAttribute('data-auto-generated', 'false');
                            itemCodeInput.disabled = false;
                        });
                }
            } else {
                codeHint.textContent = 'Pilih kategori untuk generate otomatis atau input manual';
                // Clear code jika kategori dihapus dan kode auto-generated
                if (itemCodeInput.getAttribute('data-auto-generated') === 'true') {
                    itemCodeInput.value = '';
                    itemCodeInput.setAttribute('data-auto-generated', 'false');
                }
            }
        }

        // Track manual input
        function trackManualInput() {
            const itemCodeInput = document.getElementById('itemCodeInput');
            itemCodeInput.addEventListener('input', function() {
                // Mark as manual input jika user mengetik
                if (this.value && this.value !== 'Loading...') {
                    this.setAttribute('data-auto-generated', 'false');
                }
            });
        }


        // Initialize on page load if there's old value
        document.addEventListener('DOMContentLoaded', function() {
            const hiddenPrice = document.getElementById('purchase_price');
            const displayPrice = document.getElementById('purchase_price_display');
            if (hiddenPrice.value && hiddenPrice.value > 0) {
                displayPrice.value = parseInt(hiddenPrice.value).toLocaleString('id-ID');
            }
            
            // Initialize custom location toggle
            toggleCustomLocation();
            
            // Initialize item code functionality
            trackManualInput();
            
            // Update code preview if category already selected (old value)
            const categorySelect = document.getElementById('categorySelect');
            if (categorySelect.value) {
                updateItemCodePreview();
            }
        });
    </script>
    @endpush
</x-app-layout>

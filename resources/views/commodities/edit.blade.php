<x-app-layout title="Edit Barang">
    <div class="mb-6">
        <a href="{{ route('commodities.show', $commodity) }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        <h2 class="text-xl font-bold text-gray-900 mt-2">Edit: {{ $commodity->name }}</h2>
        <p class="text-sm text-gray-500">Kode: {{ $commodity->item_code }}</p>
    </div>

    <form action="{{ route('commodities.update', $commodity) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Left Column (2/3) -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Informasi Dasar -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900">Informasi Dasar</h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <x-form.input label="Nama Barang" name="name" :value="$commodity->name" required />
                            </div>
                            <div>
                                <x-form.input label="Kode Barang" name="item_code" :value="$commodity->item_code" 
                                              placeholder="Kode unik untuk barang ini" 
                                              style="font-family: 'Consolas', 'Monaco', 'Courier New', monospace;" />
                                <p class="text-xs text-gray-500 mt-1">Kosongkan untuk generate otomatis</p>
                            </div>
                            <x-form.select label="Kategori" name="category_id" :value="$commodity->category_id" required>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $commodity->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </x-form.select>
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                                    Lokasi <span class="text-red-500">*</span>
                                </label>
                                <div class="space-y-3">
                                    <select name="location_id" id="locationSelect" class="input w-full" onchange="toggleCustomLocation()">
                                        <option value="">Pilih dari daftar lokasi</option>
                                        @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id', $commodity->location_id) == $location->id ? 'selected' : '' }}>
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
                            <x-form.input label="Merk/Brand" name="brand" :value="$commodity->brand" />
                            <x-form.input label="Model/Tipe" name="model" :value="$commodity->model" />
                            <x-form.input label="Serial Number" name="serial_number" :value="$commodity->serial_number" />
                            <x-form.input label="Penanggung Jawab" name="responsible_person" :value="$commodity->responsible_person" />
                        </div>
                    </div>
                </div>

                <!-- Detail Perolehan -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900">Detail Perolehan</h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <x-form.select label="Cara Perolehan" name="acquisition_type" :value="$commodity->acquisition_type" required :options="[
                                'pembelian' => 'Pembelian',
                                'hibah' => 'Hibah',
                                'bantuan' => 'Bantuan',
                                'produksi' => 'Produksi Sendiri',
                                'lainnya' => 'Lainnya'
                            ]" />
                            <x-form.input label="Sumber Perolehan" name="acquisition_source" :value="$commodity->acquisition_source" />
                            <x-form.input label="Tahun Perolehan" name="purchase_year" type="number" :value="$commodity->purchase_year" />
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Perolehan</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">Rp</span>
                                    <input type="text" id="purchase_price_display" class="input pl-10" placeholder="0" oninput="formatRupiahInput(this, 'purchase_price')">
                                    <input type="hidden" name="purchase_price" id="purchase_price" value="{{ old('purchase_price', $commodity->purchase_price ?? 0) }}">
                                </div>
                            </div>
                            <x-form.input label="Jumlah" name="quantity" type="number" min="1" :value="$commodity->quantity" required />
                            <x-form.select label="Kondisi" name="condition" :value="$commodity->condition" required :options="[
                                'baik' => 'Baik',
                                'rusak_ringan' => 'Rusak Ringan',
                                'rusak_berat' => 'Rusak Berat'
                            ]" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column (1/3) -->
            <div class="space-y-6">
                <!-- Foto Barang -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900">Foto Barang</h3>
                    </div>
                    <div class="card-body space-y-4">
                        @if($commodity->images->count() > 0)
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($commodity->images as $image)
                            <div class="relative group">
                                <img src="{{ $image->url }}" alt="" class="w-full h-20 object-cover rounded-lg {{ $image->is_primary ? 'ring-2 ring-primary-500' : '' }}">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center gap-1">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="primary_image" value="{{ $image->id }}" {{ $image->is_primary ? 'checked' : '' }} class="sr-only">
                                        <span class="text-white text-xs bg-primary-600 px-1.5 py-0.5 rounded">Utama</span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="sr-only">
                                        <span class="text-white text-xs bg-danger-600 px-1.5 py-0.5 rounded">Hapus</span>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                        <div>
                            <label class="form-label text-sm">Tambah Foto Baru</label>
                            <input type="file" name="images[]" multiple accept="image/*" class="text-sm w-full">
                            <p class="text-xs text-gray-500 mt-1">Max 5 gambar (JPG, PNG, 2MB/file)</p>
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900">Informasi Tambahan</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <x-form.textarea label="Spesifikasi" name="specifications" :value="$commodity->specifications" rows="3" />
                        <x-form.textarea label="Catatan" name="notes" :value="$commodity->notes" rows="2" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Actions -->
        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('commodities.show', $commodity) }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>

    @push('scripts')
    <script>
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

        document.addEventListener('DOMContentLoaded', function() {
            initRupiahDisplay('purchase_price_display', 'purchase_price');
            
            // Initialize custom location toggle
            toggleCustomLocation();
        });
    </script>
    @endpush
</x-app-layout>

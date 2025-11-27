@props([
    'name' => 'modal',
    'maxWidth' => '5xl',
    'title' => ''
])

@php
$maxWidthClass = match($maxWidth) {
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
    '5xl' => 'max-w-5xl',
    '6xl' => 'max-w-6xl',
    '7xl' => 'max-w-7xl',
    'full' => 'max-w-full mx-4',
    default => 'max-w-5xl',
};
@endphp

<!-- Modal Backdrop -->
<div id="{{ $name }}-backdrop" class="modal-backdrop" style="display:none;"></div>

<!-- Modal Content -->
<div id="{{ $name }}" class="modal-content w-full {{ $maxWidthClass }}" style="display:none;">
    <div class="rounded-2xl shadow-2xl border" style="background-color: var(--bg-card); border-color: var(--border-color);">
        <!-- Header -->
        @if($title)
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color: var(--border-color);">
            <h3 class="text-xl font-semibold" style="color: var(--text-primary);">{{ $title }}</h3>
            <button onclick="closeModal('{{ $name }}')" class="p-2 rounded-lg transition-colors hover:opacity-80" style="color: var(--text-secondary);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif
        
        <!-- Body -->
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>

import './bootstrap';
import Alpine from 'alpinejs';

// Theme System (Light / Dark)
function initTheme() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    applyTheme(savedTheme);
}

function applyTheme(themeName) {
    const html = document.documentElement;
    html.classList.remove('dark');
    
    if (themeName === 'dark') {
        html.classList.add('dark');
    }
    
    localStorage.setItem('theme', themeName);
    updateThemeChecks(themeName);
}

function updateThemeChecks(currentTheme) {
    const lightCheck = document.querySelector('.theme-check-light');
    const darkCheck = document.querySelector('.theme-check-dark');
    
    if (lightCheck) lightCheck.style.display = currentTheme === 'light' ? 'block' : 'none';
    if (darkCheck) darkCheck.style.display = currentTheme === 'dark' ? 'block' : 'none';
}

window.setTheme = applyTheme;
window.getTheme = () => localStorage.getItem('theme') || 'light';

// Initialize theme on load
initTheme();

// Update checks when DOM ready
document.addEventListener('DOMContentLoaded', () => {
    updateThemeChecks(getTheme());
});

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Simple confirm delete using native browser confirm
window.confirmDelete = (formId, title = 'Hapus Data?') => {
    if (confirm(title + '\n\nData yang dihapus tidak dapat dikembalikan!')) {
        document.getElementById(formId).submit();
    }
};

// Simple confirm action using native browser confirm
window.confirmAction = (callback, title = 'Konfirmasi', text = 'Apakah Anda yakin?') => {
    if (confirm(title + '\n\n' + text)) {
        callback();
    }
};

// Format currency to Rupiah
window.formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(number);
};

// Format number with thousand separator
window.formatNumber = (number) => {
    return new Intl.NumberFormat('id-ID').format(number);
};

// Format Rupiah input (for form inputs)
window.formatRupiahInput = (input, hiddenId) => {
    let value = input.value.replace(/\D/g, '');
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    input.value = value;
    if (hiddenId) {
        const hiddenField = document.getElementById(hiddenId);
        if (hiddenField) {
            hiddenField.value = value.replace(/\./g, '') || 0;
        }
    }
};

// Initialize Rupiah display from hidden value
window.initRupiahDisplay = (displayId, hiddenId) => {
    const hiddenVal = document.getElementById(hiddenId);
    const displayVal = document.getElementById(displayId);
    if (hiddenVal && displayVal && hiddenVal.value && parseInt(hiddenVal.value) > 0) {
        displayVal.value = parseInt(hiddenVal.value).toLocaleString('id-ID');
    }
};

// Simple Image Viewer - opens in new tab
window.viewImage = (src, title = '') => {
    window.open(src, '_blank');
};

import './bootstrap';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal   = Swal;

Alpine.start();

// ---------------------------------------------------------------------------
// Toast notifications — listen for global "toast" event
// ---------------------------------------------------------------------------
window.toast = (type, message, title = '') => {
    const Toast = Swal.mixin({
        toast: true, position: 'top-end',
        showConfirmButton: false, timer: 3500, timerProgressBar: true,
    });
    Toast.fire({ icon: type, title: title || message, text: title ? message : '' });
};

// Bootstrapped from the Blade layout via @if(session('toast')) ... @endif
document.addEventListener('toast:show', e => {
    const { type = 'info', title = '', message = '' } = e.detail || {};
    window.toast(type, message, title);
});

// ---------------------------------------------------------------------------
// Password strength meter (used on register & change-password forms)
// ---------------------------------------------------------------------------
window.passwordStrength = (value) => {
    let score = 0;
    if (!value) return { score, label: '—', color: 'bg-slate-200' };
    if (value.length >= 8) score++;
    if (/[A-Z]/.test(value)) score++;
    if (/[a-z]/.test(value)) score++;
    if (/\d/.test(value)) score++;
    if (/[^A-Za-z0-9]/.test(value)) score++;
    const labels = ['Very weak', 'Weak', 'Fair', 'Strong', 'Very strong', 'Excellent'];
    const colors = ['bg-rose-500', 'bg-rose-400', 'bg-amber-400', 'bg-lime-400', 'bg-emerald-500', 'bg-emerald-600'];
    return { score, label: labels[score], color: colors[score] };
};

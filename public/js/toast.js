/**
 * Simple Toast Notification System
 * Relies on Tailwind CSS for styling.
 */
window.Toast = {
    container: null,

    init: function () {
        if (this.container) return;
        this.container = document.createElement('div');
        this.container.id = 'toast-container';
        this.container.className = 'fixed top-4 right-4 z-[9999] flex flex-col gap-3 pointer-events-none';
        document.body.appendChild(this.container);
    },

    /**
     * Show a toast message
     * @param {string} message 
     * @param {'success'|'error'|'info'} type 
     * @param {number} duration 
     */
    show: function (message, type = 'info', duration = 3000) {
        if (!this.container) this.init();

        const toast = document.createElement('div');

        // Icon based on type
        let icon = '';
        let colorClass = '';

        switch (type) {
            case 'success':
                colorClass = 'border-green-500/50 bg-green-500/10 text-green-400';
                icon = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                break;
            case 'error':
                colorClass = 'border-red-500/50 bg-red-500/10 text-red-500';
                icon = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                break;
            default: // info
                colorClass = 'border-brand-500/50 bg-brand-500/10 text-brand-400';
                icon = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
        }

        toast.className = `max-w-sm w-full shadow-lg rounded-xl pointer-events-auto flex items-center gap-3 p-4 border backdrop-blur-md transition-all duration-300 transform translate-x-full opacity-0 ${colorClass}`;
        toast.innerHTML = `
            <div class="flex-shrink-0">${icon}</div>
            <div class="text-sm font-medium pr-2">${message}</div>
        `;

        this.container.appendChild(toast);

        // Animate In
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
        });

        // Remove after duration
        setTimeout(() => {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                if (toast.parentElement) toast.parentElement.removeChild(toast);
            }, 300);
        }, duration);
    },

    success: function (msg, duration) { this.show(msg, 'success', duration); },
    error: function (msg, duration) { this.show(msg, 'error', duration); },
    info: (msg) => {
        Toast.show(msg, 'info');
    }
};

// Global Error Handler to catch crashes
window.onerror = function (msg, url, line, col, error) {
    console.error("Global Error:", { msg, url, line, error });
    Toast.error("App Error: " + msg);
    return false;
};

window.onunhandledrejection = function (event) {
    console.error("Unhandled Promise:", event.reason);
    Toast.error("Network/Async Error: " + (event.reason ? (event.reason.message || event.reason) : "Unknown"));
};

// Initialize on load
document.addEventListener('DOMContentLoaded', () => window.Toast.init());

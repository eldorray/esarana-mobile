import './bootstrap';

// Register Service Worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js');
    });
}

// Capture beforeinstallprompt so login page can trigger it
window.addEventListener('beforeinstallprompt', e => {
    e.preventDefault();
    window.__pwaPrompt = e;
    window.dispatchEvent(new Event('pwa-installable'));
});

const CACHE = 'esarana-v1';
const OFFLINE = '/offline.html';

const PRECACHE = [
  '/',
  OFFLINE,
];

// Install: cache shell
self.addEventListener('install', e => {
  e.waitUntil(
    caches.open(CACHE).then(c => c.addAll(PRECACHE)).then(() => self.skipWaiting())
  );
});

// Activate: purge old caches
self.addEventListener('activate', e => {
  e.waitUntil(
    caches.keys()
      .then(keys => Promise.all(keys.filter(k => k !== CACHE).map(k => caches.delete(k))))
      .then(() => self.clients.claim())
  );
});

// Fetch: network-first for navigation, stale-while-revalidate for assets
self.addEventListener('fetch', e => {
  if (e.request.method !== 'GET') return;

  const url = new URL(e.request.url);

  // Skip Livewire wire requests & Vite HMR
  if (url.pathname.startsWith('/livewire') || url.pathname.startsWith('/@')) return;

  if (e.request.mode === 'navigate') {
    // Navigation: network-first, fall back to offline page
    e.respondWith(
      fetch(e.request)
        .then(res => { caches.open(CACHE).then(c => c.put(e.request, res.clone())); return res; })
        .catch(() => caches.match(e.request).then(r => r || caches.match(OFFLINE)))
    );
  } else {
    // Assets: stale-while-revalidate
    e.respondWith(
      caches.open(CACHE).then(cache =>
        cache.match(e.request).then(cached => {
          const fresh = fetch(e.request).then(res => { cache.put(e.request, res.clone()); return res; });
          return cached || fresh;
        })
      )
    );
  }
});

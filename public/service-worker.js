const CACHE_NAME = 'tecsisa-ch-cache-v4';
const STATIC_ASSETS = [
    '/offline',
    '/dashboard',
    '/tasks',
    '/technician/dashboard',
    '/technician/scanner',
    '/manifest.json',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png'
];

// Install Event: Cache static shell
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS);
        })
    );
    self.skipWaiting();
});

// Activate Event: Cleanup old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.map((key) => {
                    if (key !== CACHE_NAME) return caches.delete(key);
                })
            );
        })
    );
    self.clients.claim();
});

// Fetch Event: Network First strategy for pages, Cache First for assets
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests and internal Laravel routes like livewire or poses
    if (request.method !== 'GET') return;

    // Strategy: Network First for HTML/Pages
    if (request.mode === 'navigate' || request.headers.get('accept').includes('text/html')) {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    // Clone and save to cache for offline use
                    const copy = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                    return response;
                })
                .catch(() => {
                    // If network fails, try to serve from cache
                    return caches.match(request).then((cachedResponse) => {
                        return cachedResponse || caches.match('/offline');
                    });
                })
        );
        return;
    }

    // Strategy: Cache First for CSS, JS, and Images
    event.respondWith(
        caches.match(request).then((cachedResponse) => {
            if (cachedResponse) return cachedResponse;

            return fetch(request).then((networkResponse) => {
                // Cache dynamic assets from our own origin OR trusted CDNs
                const trustedCDNs = [
                    'unpkg.com',
                    'cdn.jsdelivr.net',
                    'cdn.tailwindcss.com',
                    'fonts.bunny.net'
                ];
                
                const isTrustedCDN = trustedCDNs.some(domain => url.hostname.includes(domain));

                if ((url.origin === location.origin || isTrustedCDN) && !url.pathname.includes('storage')) {
                    const copy = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                }
                return networkResponse;
            });
        })
    );
});

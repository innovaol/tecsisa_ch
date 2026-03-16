// Unregister any previous Service Worker immediately
self.addEventListener('install', function(e) {
  self.skipWaiting();
});

self.addEventListener('activate', function(e) {
  e.waitUntil(
    caches.keys().then(function(cacheNames) {
      return Promise.all(
        cacheNames.map(function(cacheName) {
          return caches.delete(cacheName);
        })
      );
    }).then(function() {
      self.registration.unregister();
    }).then(function() {
      self.clients.claim();
    })
  );
});

self.addEventListener('fetch', function(event) {
    // If the old SW intercepted requests, we must pass them directly to network.
    // If it fails, whatever, but don't stall for 60 seconds.
    event.respondWith(fetch(event.request));
});

const CACHE_NAME = 'khonYab-v2';
const urlsToCache = [
  '/',
  '/manifest.json',
  '/icons/icon-192x192.png',
  '/icons/icon-512x512.png',
];

// Install event - cache resources
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        return cache.addAll(urlsToCache);
      })
      .catch((error) => {
        console.error('Service Worker: Cache failed', error);
      })
  );
  self.skipWaiting();
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  return self.clients.claim();
});

// Fetch event - network-first for home page (so language switch shows fresh locale), fallback to cache when offline
self.addEventListener('fetch', (event) => {
  const isHomePage = event.request.mode === 'navigate' &&
    event.request.url.includes(self.location.origin) &&
    (event.request.url === self.location.origin + '/' ||
     event.request.url === self.location.origin + '/index');

  if (isHomePage) {
    event.respondWith(
      fetch(event.request)
        .then((response) => {
          if (response && response.status === 200 && response.type === 'basic') {
            const responseToCache = response.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(event.request, responseToCache);
            });
          }
          return response;
        })
        .catch(() => {
          return caches.match(event.request);
        })
    );
    return;
  }

  // For other cached URLs (manifest, icons), use cache-first
  if (event.request.url.includes(self.location.origin)) {
    const cachedUrl = urlsToCache.some((url) => {
      const full = url === '/' ? self.location.origin + '/' : self.location.origin + url;
      return event.request.url === full;
    });
    if (cachedUrl) {
      event.respondWith(
        caches.match(event.request).then((response) => response || fetch(event.request))
      );
    }
  }
});


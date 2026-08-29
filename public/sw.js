// E-Asrama — Service Worker
const CACHE_VERSION = 'eizin-v4';

// Aset statis yang akan di-cache saat install
const STATIC_ASSETS = [
    '/offline.html',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
];

// Install: cache aset statis inti
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_VERSION).then((cache) => {
            return cache.addAll(STATIC_ASSETS);
        })
    );
    // Langsung aktifkan tanpa menunggu tab lama ditutup
    self.skipWaiting();
});

// Activate: bersihkan cache lama
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_VERSION)
                    .map((name) => caches.delete(name))
            );
        })
    );
    // Ambil alih semua tab/client yang terbuka
    self.clients.claim();
});

// Fetch: Network-first strategy untuk request navigasi,
// Cache-first untuk aset statis (CSS, JS, gambar, font)
self.addEventListener('fetch', (event) => {
    const { request } = event;

    // Abaikan request non-GET (POST form, dll)
    if (request.method !== 'GET') return;

    const url = new URL(request.url);

    // BANYAKAN INTERCEPT: Jangan pernah tangani route admin, route export, atau API status di Service Worker
    if (
        url.pathname.includes('/admin/') ||
        url.pathname.includes('export') ||
        url.pathname.startsWith('/student/permits/latest-status')
    ) {
        return;
    }

    // Request navigasi (halaman HTML): Network-first
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    // Jika respons adalah file download / attachment atau bukan HTML, langsung kembalikan tanpa clone/cache
                    const disposition = response.headers.get('content-disposition') || '';
                    const type = response.headers.get('content-type') || '';

                    if (disposition.includes('attachment') || !type.includes('text/html')) {
                        return response;
                    }

                    const responseClone = response.clone();
                    caches.open(CACHE_VERSION).then((cache) => {
                        cache.put(request, responseClone);
                    });
                    return response;
                })
                .catch(() => {
                    // Jika offline, coba dari cache, lalu fallback offline page
                    return caches.match(request).then((cached) => {
                        return cached || caches.match('/offline.html');
                    });
                })
        );
        return;
    }

    // Aset statis (CSS, JS, gambar, font): Cache-first
    if (
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'image' ||
        request.destination === 'font'
    ) {
        event.respondWith(
            caches.match(request).then((cached) => {
                if (cached) return cached;

                return fetch(request).then((response) => {
                    // Cache respons yang valid
                    if (response && response.status === 200) {
                        const responseClone = response.clone();
                        caches.open(CACHE_VERSION).then((cache) => {
                            cache.put(request, responseClone);
                        });
                    }
                    return response;
                });
            })
        );
        return;
    }
});

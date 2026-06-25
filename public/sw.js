// E-Asrama — Service Worker
// Versi cache: ubah string ini untuk memaksa update cache saat deploy
const CACHE_VERSION = 'eizin-v2';

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

    // Abaikan request ke API/endpoint yang selalu harus fresh
    const url = new URL(request.url);
    if (url.pathname.startsWith('/student/permits/latest-status')) return;

    // Request navigasi (halaman HTML): Network-first
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    // Simpan salinan ke cache
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

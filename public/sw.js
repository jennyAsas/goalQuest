// GoalQuest service worker — caches static assets for fast, app-like loading.
// Deliberately does NOT cache POST requests or auth pages, so check-ins,
// logins, and form submissions always hit the live server.

const CACHE_NAME = 'goalquest-shell-v1';
const SHELL_ASSETS = [
  '/manifest.json',
  '/icon-192.png',
  '/icon-512.png',
  '/favicon-32.png',
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(SHELL_ASSETS))
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  const { request } = event;

  // Never touch non-GET requests (form posts, check-ins, deletes, etc.)
  if (request.method !== 'GET') return;

  // Never cache auth-sensitive or dynamic pages — always go to network.
  const url = new URL(request.url);
  if (
    url.pathname.startsWith('/login') ||
    url.pathname.startsWith('/register') ||
    url.pathname.startsWith('/dashboard') ||
    url.pathname.startsWith('/calendar') ||
    url.pathname.startsWith('/profile')
  ) {
    event.respondWith(fetch(request).catch(() => caches.match(request)));
    return;
  }

  // Static assets: cache-first for speed.
  event.respondWith(
    caches.match(request).then((cached) => cached || fetch(request))
  );
});

import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import { VitePWA } from 'vite-plugin-pwa'
import path from 'path'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel({ input: ['resources/css/app.css', 'resources/js/app.js'], refresh: true }),
        vue(),
        tailwindcss(),
        VitePWA({
            outDir: 'public',
            injectRegister: null,
            devOptions: {
                enabled: true,
            },
            manifest: {
                name: 'Huellas Perdidas',
                short_name: 'Huellas',
                description: 'Reportá y encontrá mascotas perdidas cerca tuyo',
                theme_color: '#212529',
                background_color: '#ffffff',
                display: 'standalone',
                start_url: '/',
                scope: '/',
                icons: [
                    { src: '/img/pwa-192.png', sizes: '192x192', type: 'image/png' },
                    { src: '/img/pwa-512.png', sizes: '512x512', type: 'image/png' },
                    { src: '/img/pwa-maskable.png', sizes: '512x512', type: 'image/png', purpose: 'maskable' },
                ],
            },
                workbox: {
                globDirectory: 'public/build',   // <-- agregar: acá es donde están los assets reales
                globPatterns: ['**/*.{js,css,html,png,svg}'],
                navigateFallback: null,           // <-- agregar: no hay index.html estático en Laravel/Inertia
                runtimeCaching: [
                    {
                        urlPattern: /^https:\/\/res\.cloudinary\.com\/.*/i,
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'cloudinary-images',
                            expiration: { maxEntries: 100, maxAgeSeconds: 60 * 60 * 24 * 30 },
                        },
                    },
                    {
                        urlPattern: ({ url }) => url.pathname.startsWith('/api') || url.pathname.startsWith('/publicaciones'),
                        handler: 'NetworkFirst',
                        options: { cacheName: 'huellas-data', networkTimeoutSeconds: 5 },
                    },
                ],
            },
        }),
    ],
    resolve: {
        alias: { '@': path.resolve(__dirname, 'resources/js') }
    },
    server: {
        host: '0.0.0.0',
        origin: 'http://127.0.0.1:5173',
        cors: true,
        hmr: {
            host: '127.0.0.1',
            protocol: 'ws',
            clientPort: 5173,
        },
        allowedHosts: ['127.0.0.1', 'localhost'],
    },
})
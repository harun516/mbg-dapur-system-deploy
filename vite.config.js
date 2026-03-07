import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/dapur/menu/notif-menu.js',
                'resources/css/sidebar.css',
                'resources/js/sidebar-toggle.js',
                'resources/js/auth.js',  // JS khusus auth
            ],
            refresh: [
                'resources/views/**/*.blade.php',  // auto-refresh semua Blade
                'resources/js/**/*.js',
                'resources/css/**/*.css',
            ],
            detectTls: false,  // matikan kalau tidak pakai https lokal (lebih stabil)
        }),
    ],

    server: {
        host: '0.0.0.0',           // biar bisa diakses dari HP/tablet di jaringan lokal
        port: 5173,
        strictPort: true,          // paksa pakai port 5173, tidak ganti otomatis
        hmr: {
            host: 'localhost',     // atau ganti ke IP lokal kalau akses dari device lain
            // protocol: 'ws',     // uncomment kalau ada masalah HTTPS lokal
        },
        watch: {
            usePolling: true,      // SUPER PENTING kalau pakai WSL2, Docker, VM, atau live reload sering mati
            interval: 1000,        // polling setiap 1 detik
        },
    },

    // Optimasi build production
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        return 'vendor';  // pisah vendor biar chunk lebih kecil
                    }
                },
            },
        },
        chunkSizeWarningLimit: 1200,  // naikkan batas warning kalau vendor besar
        sourcemap: false,             // matikan sourcemap di production biar build lebih cepat
    },

    // Biar dev lebih nyaman
    css: {
        devSourcemap: true,  // sourcemap aktif di dev biar debug mudah
    },
});
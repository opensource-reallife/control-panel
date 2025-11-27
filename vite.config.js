import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/sass/app.scss',
                'resources/js/sentry.min.js',
            ],
            refresh: true,
        }),
        react({
            include: '**/*.jsx'
        }),
        viteStaticCopy({
            watch: true,
            targets: [
                {
                src: 'node_modules/@fortawesome/fontawesome-free/webfonts/*',
                dest: 'webfonts',
                }
            ],
        }),
    ],
});
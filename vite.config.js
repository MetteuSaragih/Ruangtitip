import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/postcss';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/rt.css',
                'resources/js/rt-shell.js',
                'resources/css/rt-landing.css',
                'resources/js/rt-landing.js',
                'resources/css/rt-auth.css',
                'resources/css/rt-legal.css',
            ],
            refresh: true,
        }),
    ],
    css: {
        postcss: {
            plugins: [tailwindcss],
        },
    },
});

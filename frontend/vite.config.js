import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        outDir: '../backend/public/frontend',
        emptyOutDir: true,
        rollupOptions: {
            input: {
                main: './index.html',
            },
            output: {
                entryFileNames: '[name].js',
                assetFileNames: '[name].[ext]',
            }
        }
    },
    plugins: [
        laravel({
            input: ['src/main.js'],
            refresh: true,
        }),
    ],
    server: {
        proxy: {
            '/api': 'http://localhost:8080',
        },
    },
});

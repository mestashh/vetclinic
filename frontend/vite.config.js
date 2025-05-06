import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            // Точки входа — ваш "frontend/src"
            input: ['src/app.css', 'src/app.js'],
            refresh: true,
            // Здесь указано, куда писать статику и манифест
            publicDirectory: '../backend/public',
            outDir: '../backend/public/build',
        }),
    ],
});

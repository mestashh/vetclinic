import { defineConfig } from 'vite';

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
    server: {
        port: 5173,
    },
});

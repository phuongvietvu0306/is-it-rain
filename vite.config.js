import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import svgr from "vite-plugin-svgr";
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.jsx',
            refresh: true,
        }),
        svgr(),
        react(),
    ],
});

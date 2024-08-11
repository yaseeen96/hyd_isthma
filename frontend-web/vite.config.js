import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [react()],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    pdfjsLib: ['pdfjs-dist/build/pdf'],
                    pdfjsWorker: ['pdfjs-dist/build/pdf.worker'],
                },
            },
        },
    },
});

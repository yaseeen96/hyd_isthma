import React, { useEffect, useRef } from 'react';
import { pdfjs } from 'pdfjs-dist';

// Specify the workerSrc path for PDF.js
pdfjs.GlobalWorkerOptions.workerSrc = `//cdnjs.cloudflare.com/ajax/libs/pdf.js/${pdfjs.version}/pdf.worker.min.js`;

const PDFViewer = ({ pdfUrl }) => {
    const canvasRef = useRef(null);

    useEffect(() => {
        const loadPDF = async () => {
            const loadingTask = pdfjs.getDocument(pdfUrl);
            const pdf = await loadingTask.promise;
            const page = await pdf.getPage(1);
            const scale = 1.5;
            const viewport = page.getViewport({ scale });
            const canvas = canvasRef.current;
            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: context,
                viewport: viewport,
            };
            page.render(renderContext);
        };

        loadPDF();
    }, [pdfUrl]);

    return <canvas ref={canvasRef}></canvas>;
};

export default PDFViewer;

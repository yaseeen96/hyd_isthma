import React, { useEffect, useState } from 'react';
import { pdfjs } from 'pdfjs-dist';

// Specify the workerSrc path for PDF.js
pdfjs.GlobalWorkerOptions.workerSrc = `//cdnjs.cloudflare.com/ajax/libs/pdf.js/${pdfjs.version}/pdf.worker.min.js`;

const PDFViewer = ({ pdfUrl }) => {
    const [images, setImages] = useState([]);

    useEffect(() => {
        const loadPDF = async () => {
            const loadingTask = pdfjs.getDocument(pdfUrl);
            const pdf = await loadingTask.promise;
            const numPages = pdf.numPages;
            const imagePromises = [];

            for (let i = 1; i <= numPages; i++) {
                imagePromises.push(
                    pdf.getPage(i).then(async (page) => {
                        const scale = 1.5;
                        const viewport = page.getViewport({ scale });
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport,
                        };
                        await page.render(renderContext).promise;
                        return canvas.toDataURL(); // Convert the canvas to a data URL (image)
                    })
                );
            }

            // Wait for all the pages to be rendered and store them in state
            const imageUrls = await Promise.all(imagePromises);
            setImages(imageUrls);
        };

        loadPDF();
    }, [pdfUrl]);

    return (
        <div className="pdf-images-container">
            {images.length > 0 ? images.map((imageSrc, index) => <img key={index} src={imageSrc} alt={`Page ${index + 1}`} className="pdf-page-image" />) : <p>Loading PDF...</p>}
        </div>
    );
};

export default PDFViewer;

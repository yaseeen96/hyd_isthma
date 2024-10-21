// GeneratePDF.js
import html2pdf from 'html2pdf.js';
import React, { useEffect, useRef, useState } from 'react';
import PDFContent from './pdfContent'; // Ensure the correct casing for the component name
import { FiDownload } from 'react-icons/fi';

const GeneratePDF = ({ data }) => {
    const [error, setError] = useState(null);
    const [base64Images, setBase64Images] = useState({});
    const [imagesReady, setImagesReady] = useState(false);
    const pdfRef = useRef();

    useEffect(() => {
        // Convert all image URLs to base64
        const convertImages = async () => {
            try {
                const imagePromises = data.flatMap(event => event.programs)
                    .filter(program => program.speaker_image)
                    .map(async program => {
                        try {
                            const base64Image = await convertImageToBase64(program.speaker_image);
                            return { [program.speaker_image]: base64Image };
                        } catch (error) {
                            console.warn(`Skipping image due to conversion failure: ${program.speaker_image}`, error);
                            return { [program.speaker_image]: 'assets/images/auth/logo_app_black.png' }; // Use placeholder image
                        }
                    });

                const imagesArray = await Promise.all(imagePromises);
                const updatedBase64Images = imagesArray.reduce((acc, curr) => ({ ...acc, ...curr }), {});
                setBase64Images(updatedBase64Images);
                setImagesReady(true);
            } catch (error) {
                console.error('Error converting images to base64', error);
                setError('Failed to convert some images. Please try again.');
            }
        };

        convertImages();
    }, [data]);

    const convertImageToBase64 = async (url) => {
        try {
            const response = await fetch(url, { mode: 'cors' });
            if (!response.ok) {
                throw new Error(`Failed to fetch image at ${url}`);
            }
            const blob = await response.blob();
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(blob);
            });
        } catch (error) {
            console.error(`Failed to convert image to base64: ${url}`, error);
            throw error;
        }
    };

    const generatePDF = async () => {
        if (!imagesReady) {
            setError("Images are still loading. Please wait and try again.");
            return;
        }

        try {
            const element = pdfRef.current;
            element.style.display = 'block'; // Make the element visible for PDF generation

            // Allow time for the content to render properly
            setTimeout(async () => {
                const opt = {
                    margin: 1.5,
                    filename: 'event_timeline.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true, allowTaint: false },
                    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
                };
                await html2pdf()
                    .from(element)
                    .set(opt)
                    .save();
                element.style.display = 'none'; // Hide the element again after generating
                setError(null);
            }, 500); // Delay for 500ms to allow rendering to complete
        } catch (err) {
            setError(err.message || "An error occurred while generating the PDF.");
            console.error("PDF Generation Error:", err);
        }
    };

    return (
        <div>
            <div id="pdf-content" ref={pdfRef} style={{ display: 'none' }}>
                <PDFContent data={data} base64Images={base64Images} />
            </div>
            <button
                onClick={generatePDF}
                disabled={!imagesReady}
                className={`fixed flex flex-row items-center bottom-5 left-1/2 transform -translate-x-1/2 bg-primary text-white rounded-full p-3 shadow-lg z-50 ${!imagesReady ? 'opacity-50 cursor-not-allowed' : ''}`}
                aria-label="Download PDF"
            >
                <span className="text-sm mx-1">Download PDF</span>
                <FiDownload size={20} color='gray' />
            </button>
            {error && <p className="mt-4 text-red-600">{error}</p>}
        </div>
    );
};

export default GeneratePDF;
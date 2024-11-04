// GeneratePDF.js
import html2pdf from 'html2pdf.js';
import React, { useEffect, useRef, useState } from 'react';
import PDFContent from './pdfContent'; // Ensure the correct casing for the component name
import { FiDownload } from 'react-icons/fi';

const GeneratePDF = ({ data, title }) => {
    const [error, setError] = useState(null);

    const pdfRef = useRef();

    const generatePDF = async () => {
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
                    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' },
                };
                await html2pdf().from(element).set(opt).save();
                element.style.display = 'none'; // Hide the element again after generating
                setError(null);
            }, 500); // Delay for 500ms to allow rendering to complete
        } catch (err) {
            setError(err.message || 'An error occurred while generating the PDF.');
            console.error('PDF Generation Error:', err);
        }
    };

    return (
        <div>
            <div id="pdf-content" ref={pdfRef} style={{ display: 'none' }}>
                <PDFContent data={data} />
            </div>
            <button
                onClick={generatePDF}
                className={`fixed flex flex-row items-center bottom-5 left-1/2 transform -translate-x-1/2 bg-primary text-white rounded-full p-3 shadow-lg z-50 `}
                aria-label={title}
            >
                <span className="text-sm mx-1">{title}</span>
                <FiDownload size={20} color="gray" />
            </button>
            {error && <p className="mt-4 text-red-600">{error}</p>}
        </div>
    );
};

export default GeneratePDF;

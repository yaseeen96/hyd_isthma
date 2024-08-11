import React from 'react';

const PDFViewer = ({ embedUrl }) => {
    return (
        <div className="w-full flex justify-center items-center">
            <iframe src={embedUrl} className="w-full h-screen max-w-screen-md shadow-lg" frameBorder="0" allowFullScreen title="PDF Viewer"></iframe>
        </div>
    );
};

export default PDFViewer;

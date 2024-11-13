import React from 'react';
import { QRCodeCanvas } from 'qrcode.react';

const BottomSheetModal = ({ response, isOpen, onClose }) => {
    console.log(response);
    if (!isOpen) return null;

    // Extract necessary fields from the response
    const { rukn_id, name, gender } = response.user;

    // Prepare QR data with the specified fields only
    const qrData = JSON.stringify({ category: 'arkan', id: rukn_id, gender });

    // Close modal if clicked outside of it
    const handleBackgroundClick = (e) => {
        if (e.target === e.currentTarget) {
            onClose();
        }
    };

    return (
        <div
            className="fixed inset-0 flex items-end justify-center bg-gray-900 bg-opacity-50 z-50"
            onClick={handleBackgroundClick} // Close on background click
        >
            <div className="bg-white w-full max-w-md rounded-t-lg p-6 shadow-lg animate-slide-up pb-12">
                <button onClick={onClose} className="text-gray-500 absolute top-2 right-4 text-lg font-semibold hover:text-gray-700">
                    ✕
                </button>
                <h2 className="text-center font-bold text-xl mb-4 text-indigo-600">My Card</h2>
                <div className="flex justify-center mb-4">
                    <QRCodeCanvas value={qrData} size={150} />
                </div>
                <div className="text-center text-gray-700 mt-4">
                    <p className="text-lg font-semibold">{name}</p> {/* Display name */}
                    <p className="text-sm text-gray-500">Rukn ID: {rukn_id}</p> {/* Display ID */}
                    <p className="text-sm text-gray-500">Gender: {gender}</p> {/* Display Gender */}
                </div>
            </div>
        </div>
    );
};

export default BottomSheetModal;

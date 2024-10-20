import React from 'react';
const ConfirmEnrollModal = ({ isOpen, onConfirm, onCancel }) => {
    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div className="bg-white p-6 rounded-lg shadow-lg text-center">
                <h2 className="text-lg font-semibold mb-4">Are you sure you want to enroll in this event?</h2>
                <div className="flex justify-center space-x-4">
                    <button className="bg-primary text-white px-4 py-2 rounded" onClick={onConfirm}>
                        Yes, Enroll
                    </button>
                    <button className="bg-gray-300 text-gray-700 px-4 py-2 rounded" onClick={onCancel}>
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    );
};

export default ConfirmEnrollModal;

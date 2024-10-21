import React from 'react';

const ConfirmEnrollModal = ({ isOpen, onConfirm, onCancel }) => {
    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 flex items-center justify-center z-50">
            <div className="bg-white rounded-lg shadow-lg p-6">
                <h2 className="text-lg font-bold mb-4">Confirm Enrollment</h2>
                <p>Are you sure you want to enroll in this session?</p>
                <div className="flex justify-end mt-4">
                    <button className="mr-2 px-4 py-2 bg-gray-200 rounded-md" onClick={onCancel}>
                        Cancel
                    </button>
                    <button className="px-4 py-2 bg-primary text-white rounded-md" onClick={onConfirm}>
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    );
};

export default ConfirmEnrollModal;

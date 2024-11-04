import React, { useState } from 'react';
import { FiX } from 'react-icons/fi';
import { submitFeedback } from '../../../services/programs_service';
import { toast } from 'react-toastify';

const FeedbackModal = ({ isOpen, onClose, onSubmit, programId }) => {
    const [title, setTitle] = useState('');
    const [description, setDescription] = useState('');
    const [loading, setLoading] = useState(false);

    const handleSubmit = async () => {
        if (title && description) {
            setLoading(true);
            try {
                const feedbackType = programId ? 'program' : 'event';
                const response = await submitFeedback(feedbackType, title, description, programId);

                if (response.status === 'success') {
                    toast.success(response.message);
                    setTitle('');
                    setDescription('');
                    onSubmit({ title, description });
                    onClose(); // Close the modal after submission
                } else {
                    toast.error(response.message || 'Failed to submit feedback. Please try again.');
                }
            } catch (err) {
                console.error(err);
                toast.error('Failed to submit feedback. Please try again.');
            } finally {
                setLoading(false);
            }
        }
    };

    const handleBackgroundClick = (e) => {
        if (e.target.id === 'modal-overlay') {
            onClose();
        }
    };

    if (!isOpen) return null;

    return (
        <div id="modal-overlay" className="fixed inset-0 z-50 flex items-end justify-center bg-black bg-opacity-50 backdrop-blur-sm" onClick={handleBackgroundClick}>
            <div className="relative w-full max-w-lg p-6 pb-32 bg-white rounded-t-3xl shadow-lg dark:bg-gray-800 animate-slide-up" onClick={(e) => e.stopPropagation()}>
                {/* Close Button */}
                <button onClick={onClose} className="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition duration-200">
                    <FiX size={24} />
                </button>

                {/* Header */}
                <h2 className="text-xl font-semibold text-gray-800 dark:text-white mb-4 text-center">Share Your Feedback</h2>

                {/* Title Input */}
                <input
                    type="text"
                    value={title}
                    onChange={(e) => setTitle(e.target.value)}
                    placeholder="Feedback Title (e.g., Event Feedback)"
                    className="w-full p-3 mb-4 text-gray-700 bg-gray-100 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none dark:bg-gray-700 dark:text-gray-200"
                />

                {/* Description Input */}
                <textarea
                    value={description}
                    onChange={(e) => setDescription(e.target.value)}
                    placeholder="Describe your experience..."
                    rows="4"
                    className="w-full p-3 text-gray-700 bg-gray-100 rounded-lg resize-none focus:ring-2 focus:ring-primary focus:outline-none dark:bg-gray-700 dark:text-gray-200"
                ></textarea>

                {/* Submit Button */}
                <button
                    onClick={handleSubmit}
                    disabled={!title || !description || loading}
                    className={`w-full mt-4 py-3 rounded-lg text-white font-semibold ${
                        title && description && !loading ? 'bg-primary hover:bg-primary-dark' : 'bg-gray-300 cursor-not-allowed'
                    } transition duration-200 flex items-center justify-center`}
                >
                    {loading ? <span className="loader mr-2 w-5 h-5 border-2 border-t-2 border-white rounded-full animate-spin"></span> : 'Submit Feedback'}
                </button>
            </div>
        </div>
    );
};

export default FeedbackModal;

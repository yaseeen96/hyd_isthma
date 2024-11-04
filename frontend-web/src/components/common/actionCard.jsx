import React from 'react';

const ActionCard = ({ message, buttonText, onButtonClick, progress }) => {
    return (
        <div className="relative bg-white p-6 rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300 flex flex-col justify-between items-center border border-gray-200">
            {/* Top Progress Bar */}
            <div className="absolute top-0 left-0 w-full h-2 bg-gray-200 rounded-t-lg">
                <div className="h-full bg-primary rounded-t-lg transition-all duration-500" style={{ width: `${progress}%` }}></div>
            </div>

            {/* Message */}
            <p className="text-gray-700 dark:text-gray-300 font-semibold text-lg text-center leading-relaxed">{message}</p>

            {/* Action Button */}
            <div className="w-full mt-4">
                <button
                    aria-label={buttonText}
                    onClick={onButtonClick}
                    className="relative w-full py-3 px-4 bg-primary text-white font-semibold rounded-lg shadow-md transition-transform duration-300 ease-in-out transform hover:scale-105 hover:bg-primary-dark"
                >
                    <span>{buttonText}</span>
                </button>
            </div>
        </div>
    );
};

export default ActionCard;

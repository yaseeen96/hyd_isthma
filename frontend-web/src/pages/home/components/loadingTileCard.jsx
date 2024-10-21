import React, { useEffect, useRef } from 'react';

const LoadingTileCard = ({ icon, title, onClick, percentage }) => {
    const progressRef = useRef(null);

    // Function to calculate the background color based on the percentage
    const getBackgroundColor = (percentage) => {
        if (percentage < 50) {
            return `rgba(184, 0, 0, 0.8)`; // Red color (#B80000) with opacity 0.8
        } else if (percentage >= 50 && percentage < 75) {
            return `rgba(255, 164, 71, 0.8)`; // Yellow color (#FFA447) with opacity 0.8
        } else {
            return `rgba(57, 153, 24, 0.8)`; // Green color (#399918) with opacity 0.8
        }
    };

    useEffect(() => {
        if (progressRef.current) {
            progressRef.current.style.height = `${percentage}%`;
            progressRef.current.style.backgroundColor = getBackgroundColor(percentage);
        }
    }, [percentage]);

    return (
        <div className="relative w-full h-full bg-white dark:bg-gray-900 rounded-lg">
            <div
                className="flex justify-center items-center flex-col rounded-lg shadow-lg text-black dark:text-white p-4 cursor-pointer relative z-10 h-full bg-opacity-90"
                onClick={onClick}
                style={{ backgroundColor: 'rgba(255, 255, 255, 0)' }}
            >
                {icon}
                <h3 className="text-xl">{title}</h3>
                <p className="absolute top-2 right-2 text-lg text-gray-600">{percentage}%</p> {/* Progress text */}
            </div>
            <div className="absolute top-0 left-0 w-full h-full rounded-lg overflow-hidden pointer-events-none z-0">
                <div ref={progressRef} className="absolute bottom-0 w-full transition-all duration-500 ease-in-out"></div>
            </div>
        </div>
    );
};

export default LoadingTileCard;

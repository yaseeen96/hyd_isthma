import React from 'react';
import { FiChevronRight } from 'react-icons/fi';

const TileCard = ({ icon, title, onClick, className }) => {
    return (
        <div
            className={`flex items-center rounded-lg shadow-lg bg-white dark:bg-gray-900 text-black dark:text-white p-4 cursor-pointer transition-transform transform hover:scale-105 h-24 ${className}`}
            onClick={onClick}
        >
            {/* Icon on the Left */}
            <div className="mr-4 text-primary text-2xl">{icon}</div>

            {/* Title in the Center */}
            <h3 className="flex-grow text-xl font-semibold text-center">{title}</h3>

            {/* Right Arrow for navigation indication */}
            <FiChevronRight className="text-gray-500" />
        </div>
    );
};

export default TileCard;

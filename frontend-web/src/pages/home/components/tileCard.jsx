import React from 'react';

const TileCard = ({ icon, title, onClick }) => {
    return (
        <div className="flex justify-center items-center flex-col rounded-lg bg-white shadow-lg dark:bg-gray-900 text-black dark:text-white p-4 cursor-pointer" onClick={onClick}>
            {icon}
            <h3 className="text-xl">{title}</h3>
        </div>
    );
};

export default TileCard;

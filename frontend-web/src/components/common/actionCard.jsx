import React, { useEffect, useState } from 'react';
import { localStorageConstant } from '../../utils/constants/localStorageConstants';

const ActionCard = ({ message, buttonText, onButtonClick }) => {
    const [name, setName] = useState('');

    useEffect(() => {
        // Retrieve the name from localStorage
        const storedName = localStorage.getItem(localStorageConstant.name) || 'Guest';
        setName(storedName);
    }, []);

    return (
        <div className="w-full  p-6 bg-white dark:bg-gray-900 shadow-lg rounded-lg flex flex-col justify-between animate-slide-in">
            <div className="flex flex-col items-start">
                <h1 className="text-2xl text-black dark:text-white font-bold">
                    Welcome <br /> <span className="text-primary dark:text-primary-500">{name}</span>
                </h1>
                <p className="text-lg text-black dark:text-white mt-2">{message}</p>
            </div>
            <button
                onClick={onButtonClick}
                className="mt-4 py-2 px-4 bg-primary text-white font-semibold rounded-lg shadow-md transition duration-3000 ease-in-out transform hover:scale-105 animate-grow-shrink flex justify-center items-center"
            >
                {buttonText}
            </button>
        </div>
    );
};

export default ActionCard;

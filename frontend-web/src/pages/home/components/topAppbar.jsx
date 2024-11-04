import React from 'react';
import { FiLogOut, FiArrowLeft } from 'react-icons/fi';
import { useNavigate } from 'react-router-dom';

const TopAppBar = ({ title, onLogout, showBackButton }) => {
    const navigate = useNavigate();

    return (
        <div className="bg-white text-black h-16 flex items-center px-4">
            {showBackButton && (
                <button
                    onClick={() => {
                        localStorage.removeItem('token');
                        window.location.reload();
                    }}
                    className="text-black hover:text-gray-200 mr-4"
                >
                    <FiArrowLeft size={24} />
                </button>
            )}
            <h1 className="text-2xl font-semibold flex-grow text-black">{title}</h1>
            <button onClick={onLogout} className="text-black hover:text-gray-200">
                <FiLogOut size={24} />
            </button>
        </div>
    );
};

export default TopAppBar;

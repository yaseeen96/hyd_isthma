import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { FiArrowLeft } from 'react-icons/fi';
import LoadingComponent from '../../components/common/loadingComponent';

const MapComponent = () => {
    const navigate = useNavigate();
    const [loading, setLoading] = useState(true); // Track loading state

    return (
        <div className="relative w-full h-screen">
            {/* App Bar */}
            <div className="fixed top-0 left-0 w-full bg-white shadow-md z-10 flex items-center p-4">
                <button onClick={() => navigate(-1)} className="flex items-center text-primary">
                    <FiArrowLeft className="mr-2" size={20} />
                    <span className="text-base font-semibold">Back</span>
                </button>
            </div>

            {/* Loading Component */}
            {loading && <LoadingComponent />}

            {/* Map iframe */}
            <iframe
                src="https://www.google.com/maps/d/view?mid=1tqvYt_COGjE38bFrsggeDekVDO7x018&usp=sharing"
                title="Google Map"
                className="w-full h-full border-0 pt-16" // Add padding top to avoid overlap with app bar
                allowFullScreen
                onLoad={() => setLoading(false)} // Hide loading on map load
            ></iframe>
        </div>
    );
};

export default MapComponent;

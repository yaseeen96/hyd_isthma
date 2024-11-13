import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { FiArrowLeft, FiMapPin, FiMap } from 'react-icons/fi';
import LoadingComponent from '../../components/common/loadingComponent';
import { FaLocationArrow, FaMapMarker, FaMapMarkerAlt, FaSearchLocation } from 'react-icons/fa';

const MapComponent = () => {
    const navigate = useNavigate();
    const [loading, setLoading] = useState(false); // Loading state if necessary

    return (
        <div className="relative w-full h-screen bg-gradient-to-b from-blue-50 to-blue-100 flex flex-col items-center">
            {/* App Bar */}
            <div className="fixed top-0 left-0 w-full bg-white shadow-md z-10 flex items-center p-4 rounded-b-2xl">
                <button onClick={() => navigate(-1)} className="flex items-center text-indigo-600">
                    <FiArrowLeft className="mr-2" size={24} />
                    <span className="text-lg font-semibold">Back</span>
                </button>
            </div>

            {/* Loading Component */}
            {loading && <LoadingComponent />}

            {/* Content */}
            <div className="flex flex-col items-center justify-center text-center h-full space-y-6 px-4 pt-20">
                {/* Header Section */}
                <div>
                    <h1 className="text-2xl font-bold text-indigo-700 mb-4">Explore Our Location</h1>
                    <p className="text-gray-600 max-w-md mx-auto text-sm">Access directions and location details in Google Maps or view our PDF guide.</p>
                </div>

                {/* Large Map Icon */}
                <div className="flex justify-center items-center my-8">
                    <FaMapMarkerAlt className="text-blue-600 opacity-80" size={120} />
                </div>

                {/* Accessible Button Section */}
                <div className="flex flex-col items-center space-y-6 w-full max-w-md">
                    <button
                        onClick={() => window.open('https://www.google.com/maps/d/viewer?mid=1PfKx-RVGzuCzR7lYVkYnodqOrxmFbpQ&ll=17.28723493661389%2C78.4684089850369&z=18', '_blank')}
                        className="flex items-center justify-center w-full px-8 py-4 bg-green-600 text-white font-semibold text-xl rounded-lg shadow-lg hover:bg-green-700 transition duration-200"
                    >
                        <FiMapPin className="text-white mr-2" size={24} />
                        Open in Google Maps
                    </button>
                    <button
                        onClick={() => window.open(`assets/pdfs/map.pdf`, '_blank')}
                        className="flex items-center justify-center w-full px-8 py-4 bg-blue-600 text-white font-semibold text-xl rounded-lg shadow-lg hover:bg-blue-700 transition duration-200"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 mr-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        View PDF
                    </button>
                </div>
            </div>
        </div>
    );
};

export default MapComponent;

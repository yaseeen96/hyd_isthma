import React from 'react';
import { FiArrowDown, FiArrowUp } from 'react-icons/fi';

const SessionCard = ({ session, index, expandedSessions, toggleSession, openModal }) => {
    const isExpanded = expandedSessions[index];

    // Function to extract time from datetime
    const formatTime = (datetime) => {
        // Split by space and get the second part (time) until the end
        const timePart = datetime.split(' ').slice(1).join(' ');
        return timePart; // Returns "09:30 AM - 12:30 PM"
    };

    return (
        <div className="border rounded-md shadow-lg mb-4 p-4 transition-transform duration-300 ease-in-out hover:shadow-xl">
            <div className="flex justify-between items-center cursor-pointer" onClick={() => toggleSession(index)}>
                <h3 className="text-lg font-bold text-gray-800">{session.theme_name}</h3>
                <button className="text-gray-600">
                    {isExpanded ? <FiArrowUp /> : <FiArrowDown />}
                </button>
            </div>
            <p className="mt-1 text-gray-500 font-bold">{formatTime(session.datetime)}</p> {/* Show only time */}
            <div className="mt-2 text-gray-600">
                <p className="mt-1">Hall: <span className="font-medium">{session.hall_name}</span></p>
                <p className="mt-1">Convenor: <span className="font-medium">{session.session_convener}</span></p>
                <p className="mt-1">Bio: <span className="font-medium">{session.convener_bio}</span></p>
            </div>

            {isExpanded && (
                <div className="mt-2">
                    {session.programs.length > 0 ? (
                        session.programs.map((program) => (
                            <div key={program.id} className="mt-2 p-2 border rounded-md flex bg-gray-50 shadow-sm">
                                {/* Speaker image on the left */}
                                {program.speaker_image && (
                                    <img src={program.speaker_image} alt={program.speaker_name} className="w-16 h-16 rounded-full mr-4" />
                                )}
                                <div className="flex-grow">
                                    <h4 className="font-bold text-gray-800">{program.name}</h4>
                                    <p className="text-gray-600">Date & Time: {formatTime(program.datetime)}</p> {/* Display only time for programs */}
                                    <p className="text-gray-600">Speaker Bio: {program.speaker_bio}</p>
                                    <p className="text-gray-600">Status: {program.status}</p>

                                    {/* Check if the user is enrolled */}
                                    {
                                    
                                    program.enrolled === true ? (
                                        <span className="inline-flex items-center justify-center px-2 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                                            Enrolled
                                        </span>
                                    ) : (
                                        // Only show the enroll button for parallel sessions
                                        session.theme_type === 'Parallel' && (
                                            <button
                                                onClick={() => openModal(program.id)}
                                                className="bg-primary text-white py-1 px-2 rounded hover:bg-primary-dark mt-2 transition duration-200"
                                            >
                                                Enroll
                                            </button>
                                        )
                                    )}
                                </div>
                            </div>
                        ))
                    ) : (
                        <p>No programs available for this session.</p>
                    )}
                </div>
            )}
        </div>
    );
};

export default SessionCard;

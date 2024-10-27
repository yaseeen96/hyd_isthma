import React, { forwardRef } from 'react';
import { FiChevronDown, FiChevronUp, FiClock, FiMapPin, FiUser, FiMessageSquare, FiExternalLink } from 'react-icons/fi';

const SessionCard = forwardRef(
    (
        {
            session,
            index,
            expandedSessions,
            toggleSession,
            openModal,
            backgroundColor,
            programColor,
            handleFeedbackOpen, // handle feedback function passed as prop
            noProgramsAvailable,
            enrollMessage,
            giveFeedback,
            viewTranslation,
        },
        ref
    ) => {
        const isExpanded = expandedSessions[session.id];

        // Function to extract time from datetime
        const formatTime = (datetime) => {
            const timePart = datetime.split(' ').slice(1).join(' ');
            return timePart;
        };

        // Function to decide status color
        const getStatusColor = (status) => {
            switch (status) {
                case 'Yet to Start':
                    return 'bg-blue-100 text-blue-800';
                case 'In Progress':
                    return 'bg-yellow-100 text-yellow-800';
                case 'Completed':
                    return 'bg-gray-100 text-gray-800';
                case 'Cancelled':
                    return 'bg-red-100 text-red-800';
                default:
                    return 'bg-blue-100 text-blue-800';
            }
        };

        // Determine if the feedback button should be clickable

        return (
            <div
                ref={ref}
                onClick={() => toggleSession(session.id)}
                className={`border rounded-lg shadow-lg mb-6 p-4 transition-all duration-200 ease-in-out cursor-pointer active:scale-95 ${backgroundColor}`}
            >
                <div className="flex justify-between items-center">
                    <h3 className="text-lg font-bold text-gray-800 flex-grow">{session.theme_name}</h3>
                    <span className="text-gray-600 transform transition-transform duration-200 ease-in-out">{isExpanded ? <FiChevronUp /> : <FiChevronDown />}</span>
                </div>
                <div className="mt-2 text-gray-500 font-semibold flex items-center space-x-2">
                    <FiClock className="text-primary-500" />
                    <p>{formatTime(session.datetime)}</p>
                </div>
                <div className="mt-4 text-gray-600">
                    {session.hall_name && (
                        <div className="flex items-center space-x-2 mb-2 font-medium">
                            <FiMapPin className="text-primary-500" />
                            <p>{session.hall_name}</p>
                        </div>
                    )}
                    <div className="flex items-center space-x-2 mb-2 font-medium">
                        <FiUser className="text-primary-500" />
                        <p>{session.session_convener}</p>
                    </div>
                    <p className="text-gray-500">{session.convener_bio}</p>
                </div>

                {session.theme_type === 'Parallel' && !session.enrolled && (
                    <button
                        onClick={(e) => {
                            e.stopPropagation();
                            openModal(session.id);
                        }}
                        className="bg-primary-500 text-white py-3 px-6 w-full rounded-lg font-semibold text-lg hover:bg-primary-dark mt-6 transition duration-200"
                    >
                        {enrollMessage}
                    </button>
                )}
                {session.enrolled && <span className="inline-flex items-center justify-center px-3 py-1 mt-6 text-sm font-medium text-green-800 bg-green-100 rounded-full">Enrolled</span>}

                {isExpanded && (
                    <div className="mt-4">
                        {session.programs.length > 0 ? (
                            session.programs.map((program) => {
                                const isFeedbackClickable = ['In Progress', 'Completed', 'Cancelled'].includes(program.status);
                                return (
                                    <div key={program.id} className={`mt-4 p-4 border rounded-lg flex ${programColor} shadow-sm`}>
                                        <div className="flex-shrink-0 mr-4">
                                            {program.speaker_image ? (
                                                <img src={program.speaker_image} alt={program.speaker.name} className="w-12 h-12 rounded-full" />
                                            ) : (
                                                <FiUser className="text-primary-500 w-12 h-12" />
                                            )}
                                        </div>
                                        <div className="flex-grow">
                                            <h4 className="text-lg font-bold text-gray-800">{program.name}</h4>
                                            <div className="flex items-center space-x-2 text-gray-600 mt-1">
                                                <FiClock className="text-primary-500" />
                                                <p>{formatTime(program.datetime)}</p>
                                            </div>
                                            <div className="flex items-center space-x-2 text-gray-600 mt-1">
                                                <FiUser className="text-primary-500" size={session.theme_type == 'Fixed' ? 18 : 38} />
                                                <p className="text-gray-600 mt-1">{program.speaker.bio}</p>
                                            </div>
                                            <span className={`inline-flex items-center justify-center px-3 py-1 mt-2 text-sm font-medium rounded-full ${getStatusColor(program.status)}`}>
                                                {program.status}
                                            </span>

                                            {/* View Translation Button */}
                                            {(program.status === 'In Progress' || program.status === 'Completed') && (
                                                <a href={program.translation} target="_blank" rel="noopener noreferrer" className="block w-full">
                                                    <button className="bg-primary-500 text-white py-2 px-4 mt-2 rounded-lg font-semibold hover:bg-primary-dark transition duration-200 w-full flex items-center justify-center space-x-2">
                                                        <FiExternalLink />
                                                        <span>{viewTranslation}</span>
                                                    </button>
                                                </a>
                                            )}

                                            {/* Give Feedback Button - always visible, only clickable if status is 'In Progress', 'Completed', or 'Cancelled' */}
                                            <button
                                                onClick={(e) => {
                                                    e.stopPropagation();
                                                    if (isFeedbackClickable) handleFeedbackOpen(program.id);
                                                }}
                                                disabled={!isFeedbackClickable}
                                                className={`py-2 px-4 mt-2 rounded-lg font-semibold transition duration-200 w-full flex items-center justify-center space-x-2 ${
                                                    isFeedbackClickable ? 'bg-primary-500 text-white hover:bg-primary-dark' : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                                }`}
                                            >
                                                <FiMessageSquare />
                                                <span>{giveFeedback}</span>
                                            </button>
                                        </div>
                                    </div>
                                );
                            })
                        ) : (
                            <p className="mt-4">{noProgramsAvailable}</p>
                        )}
                    </div>
                )}
            </div>
        );
    }
);

export default SessionCard;

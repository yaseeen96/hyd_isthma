import React, { useState, forwardRef } from 'react';
import { FiChevronDown, FiChevronUp, FiClock, FiMapPin, FiUser, FiMessageSquare, FiExternalLink, FiFileText } from 'react-icons/fi';

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
            handleFeedbackOpen,
            noProgramsAvailable,
            enrollMessage,
            giveFeedback,
            viewTranslation,
            viewTranscript,
        },
        ref
    ) => {
        const [transcriptModal, setTranscriptModal] = useState({ isOpen: false, content: '' });

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

        return (
            <>
                {/* Transcript Modal (Bottom Sheet) */}
                {transcriptModal.isOpen && (
                    <>
                        <div className="fixed inset-0 bg-black bg-opacity-50 z-40" onClick={() => setTranscriptModal({ isOpen: false, content: '' })} />
                        <div
                            className="fixed inset-x-0 bottom-0 z-50 bg-white rounded-t-2xl p-6 shadow-lg transition-transform duration-300 animate-slide-up"
                            style={{ maxHeight: '80vh', overflowY: 'auto' }}
                        >
                            <div className="flex justify-between items-center mb-4">
                                <h3 className="text-lg font-bold text-gray-800">Transcript</h3>
                                <button onClick={() => setTranscriptModal({ isOpen: false, content: '' })} className="text-gray-500 hover:text-gray-800">
                                    Close
                                </button>
                            </div>
                            <p className="text-gray-600 whitespace-pre-line">{transcriptModal.content}</p>
                        </div>
                    </>
                )}

                <div ref={ref} className={`border rounded-lg shadow-lg mb-6 p-4 transition-transform duration-300 ease-in-out ${backgroundColor} ${isExpanded ? 'scale-105' : 'scale-100'}`}>
                    {/* Session Header */}
                    <div className="flex justify-between items-center cursor-pointer" onClick={() => toggleSession(session.id)}>
                        <div className="flex flex-col">
                            <h3 className="text-lg font-bold text-gray-800">{session.theme_name}</h3>
                            {session.datetime && (
                                <div className="flex items-center space-x-2 text-gray-500 mt-1">
                                    <FiClock className="text-primary-500" />
                                    <p>{formatTime(session.datetime)}</p>
                                </div>
                            )}
                            {session.hall_name && (
                                <div className="flex items-center space-x-2 text-gray-500 mt-1">
                                    <FiMapPin className="text-primary-500" />
                                    <p>{session.hall_name}</p>
                                </div>
                            )}
                        </div>
                        <button
                            className="text-gray-600 flex items-center"
                            onClick={(e) => {
                                e.stopPropagation();
                                toggleSession(session.id);
                            }}
                        >
                            {isExpanded ? <FiChevronUp /> : <FiChevronDown />}
                            <span className="ml-1 text-sm">{isExpanded ? 'Collapse' : 'Expand'}</span>
                        </button>
                    </div>

                    {/* Expandable Content */}
                    {isExpanded && (
                        <div className="mt-4 transition-all duration-300 ease-in-out opacity-100">
                            {/* Convener Details */}
                            <div className="mt-4 text-gray-600">
                                <div className="flex items-center space-x-2 mb-2 font-medium">
                                    {session.session_convener &&
                                        (session.convener_image ? (
                                            <img src={session.convener_image} alt={session.convener_image} className="w-12 h-12 rounded-full" />
                                        ) : (
                                            <FiUser className="text-primary-500" />
                                        ))}
                                    <p>{session.session_convener ?? ''}</p>
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

                            {/* Program List */}
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
                                                    <p className="text-gray-600 text-sm">{program.speaker.name}</p>
                                                    <div className="flex items-center space-x-2 text-gray-600 mt-1">
                                                        {program.speaker_name && <FiUser className="text-primary-500" size={18} />}
                                                        <p className="text-gray-600 mt-1">{program.speaker.bio}</p>
                                                    </div>
                                                    {program.datetime && (
                                                        <div className="flex items-center space-x-2 text-gray-600 mt-1">
                                                            <FiClock className="text-primary-500" />
                                                            <p>{formatTime(program.datetime)}</p>
                                                        </div>
                                                    )}

                                                    <span className={`inline-flex items-center justify-center px-3 py-1 mt-2 text-sm font-medium rounded-full ${getStatusColor(program.status)}`}>
                                                        {program.status}
                                                    </span>

                                                    {/* View Translation Button */}
                                                    {(program.status === 'In Progress' || program.status === 'Completed') && program.url != null && (
                                                        <a href={program.url} target="_blank" rel="noopener noreferrer" className="block w-full">
                                                            <button className="bg-primary-500 text-white py-2 px-4 mt-2 rounded-lg font-semibold hover:bg-primary-dark transition duration-200 w-full flex items-center justify-center space-x-2">
                                                                <FiExternalLink />
                                                                <span>{viewTranslation}</span>
                                                            </button>
                                                        </a>
                                                    )}

                                                    {/* Give Feedback Button */}
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

                                                    {/* View Transcript Button */}
                                                    {program.transcript && (
                                                        <button
                                                            onClick={(e) => {
                                                                e.stopPropagation();
                                                                setTranscriptModal({
                                                                    isOpen: true,
                                                                    content: program.transcript,
                                                                });
                                                            }}
                                                            className="bg-purple-500 text-white py-2 px-4 mt-2 rounded-lg font-semibold hover:bg-purple-600 transition duration-200 w-full flex items-center justify-center space-x-2"
                                                        >
                                                            <FiFileText />
                                                            <span>{viewTranscript}</span>
                                                        </button>
                                                    )}
                                                </div>
                                            </div>
                                        );
                                    })
                                ) : (
                                    <div></div>
                                )}
                            </div>
                        </div>
                    )}
                </div>
            </>
        );
    }
);

export default SessionCard;

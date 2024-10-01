import React, { useState, useEffect } from 'react';
import { useQuery } from 'react-query';
import { useNavigate } from 'react-router-dom';
import dayjs from 'dayjs';
import LoadingComponent from '../../../components/common/loadingComponent';
import { getProgramDetails, enrollforProgram } from '../../../services/programs_service';
import { FiArrowLeft } from 'react-icons/fi';
import { FiArrowDown, FiArrowUp } from 'react-icons/fi';

// Helper function to group events by date
const groupEventsByDate = (events) => {
    return events.reduce((groupedEvents, event) => {
        const eventDate = dayjs(event.datetime.split(' ')[0]).format('YYYY-MM-DD');
        if (!groupedEvents[eventDate]) {
            groupedEvents[eventDate] = [];
        }
        groupedEvents[eventDate].push(event);
        return groupedEvents;
    }, {});
};

// Generate an array of days for a week (or more)
const generateCalendarDates = (events) => {
    // Find the earliest event date
    const firstEventDate = dayjs(events[0].datetime.split(' ')[0]);

    // Create an array starting 2 days before and ending 5 days after the first event date
    const dates = [];
    for (let i = -5; i <= 10; i++) {
        dates.push(firstEventDate.add(i, 'day').format('YYYY-MM-DD'));
    }
    return dates;
};

const Timeline = () => {
    const [selectedDate, setSelectedDate] = useState(dayjs().format('YYYY-MM-DD'));
    const [isRefetching, setIsRefetching] = useState(false);
    const [enrollMessage, setEnrollMessage] = useState('');
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedEventId, setSelectedEventId] = useState(null);
    const navigate = useNavigate();
    const [expandedSessions, setExpandedSessions] = useState({}); // Track which sessions are expanded
    const [showFullBio, setShowFullBio] = useState({}); // Track which speaker bios are fully expanded

    const toggleSession = (index) => {
        setExpandedSessions((prevState) => ({
            ...prevState,
            [index]: !prevState[index], // Toggle the session
        }));
    };

    // Use react-query to fetch the timeline data
    const { data, isLoading, isError, error, refetch } = useQuery('timelineData', getProgramDetails, {
        refetchOnWindowFocus: true,
        refetchOnMount: true,
        staleTime: 0,
        onSettled: () => setIsRefetching(false),
    });

    // Trigger refetch and manage refetch state
    useEffect(() => {
        setIsRefetching(true);
        refetch().then(() => setIsRefetching(false));
    }, [refetch]);

    if (isLoading || isRefetching) {
        return <LoadingComponent />;
    }

    if (isError) {
        return (
            <div className="text-center p-6">
                <p className="text-red-500">Error: {error.message}</p>
            </div>
        );
    }

    // Group events by date
    const groupedEvents = groupEventsByDate(data.data);

    // Generate dates for the next 7 days
    const calendarDates = generateCalendarDates(data.data);

    // Function to open the confirmation modal
    const openModal = (eventId) => {
        setSelectedEventId(eventId);
        setIsModalOpen(true);
    };
    const toggleBio = (index) => {
        setShowFullBio((prevState) => ({
            ...prevState,
            [index]: !prevState[index],
        }));
    };

    const getTruncatedBio = (bio, length = 40) => {
        if (bio.length > length) {
            return `${bio.substring(0, length)}...`;
        }
        return bio;
    };

    // Function to handle the actual enrollment
    const handleEnroll = async () => {
        if (selectedEventId) {
            const response = await enrollforProgram(selectedEventId);
            if (response.status === 'success') {
                setEnrollMessage(response.message);
                refetch();
            } else {
                setEnrollMessage('Enrollment failed, please try again.');
            }
        }
        setIsModalOpen(false);
    };

    const handleCancel = () => {
        setIsModalOpen(false);
    };

    return (
        <div className="container mx-auto p-4">
            {/* Back Button */}
            <button onClick={() => navigate(-1)} className="flex items-center text-primary mb-4">
                <FiArrowLeft className="mr-2" size={20} />
                <span className="text-base font-semibold">Back</span>
            </button>

            <h1 className="text-2xl font-bold text-primary mb-6">Programs Timeline</h1>

            {/* Show enroll message */}
            {enrollMessage && <div className="text-center p-3 mb-4 bg-green-100 text-green-700 rounded-md">{enrollMessage}</div>}

            {/* Horizontally scrollable list of dates */}
            <div className="overflow-x-auto mb-6">
                <div className="flex space-x-2">
                    {calendarDates.map((date) => (
                        <button
                            key={date}
                            onClick={() => setSelectedDate(date)}
                            disabled={!groupedEvents[date]}
                            className={`py-2 px-3 rounded-lg ${
                                selectedDate === date
                                    ? 'bg-primary text-white'
                                    : !groupedEvents[date]
                                    ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                    : 'bg-gray-200 text-primary hover:bg-primary hover:text-white'
                            }`}
                        >
                            <div>{dayjs(date).format('DD')}</div>
                            <div>{dayjs(date).format('MMM')}</div>
                        </button>
                    ))}
                </div>
            </div>

            {/* Timeline Layout */}
            <div className="timeline">
                {groupedEvents[selectedDate] ? (
                    groupedEvents[selectedDate].map((session, index) => {
                        const dateTimeRange = session.datetime.match(/(\d{2}:\d{2} (AM|PM))/g); // Match time range
                        const sessionStartTime = dateTimeRange ? dateTimeRange[0] : null;
                        const sessionEndTime = dateTimeRange ? dateTimeRange[1] : null;

                        return (
                            <div key={index} className="mb-6 bg-white p-4 rounded-lg shadow-md">
                                {/* Session Theme (Collapsible Header with Up/Down Arrow) */}
                                <div
                                    className="flex items-center justify-between mb-4 cursor-pointer"
                                    onClick={() => toggleSession(index)} // Toggle collapse
                                >
                                    <div>
                                        <h3 className="text-lg font-bold text-primary">{session.session_theme}</h3>
                                        <p className="text-sm text-gray-600">Convener: {session.session_convener ?? 'Unknown'}</p>
                                        <p className="text-sm text-gray-500">{session.convener_bio ?? 'Bio not available'}</p>
                                        <p className="text-sm text-gray-500">
                                            {sessionStartTime} to {sessionEndTime}
                                        </p>
                                        <StatusChip status={session.status} />
                                    </div>
                                    <div>{expandedSessions[index] ? <FiArrowUp size={24} /> : <FiArrowDown size={24} />}</div>
                                </div>

                                {/* Program Details (Collapsible Content) */}
                                {expandedSessions[index] && (
                                    <div className={`ml-6 ${groupedEvents[selectedDate].length > 1 ? 'relative' : ''}`}>
                                        {groupedEvents[selectedDate].length > 1 && <div className="absolute left-4 top-0 w-1 h-full bg-gray-300"></div> /* Timeline Line */}

                                        {groupedEvents[selectedDate].map((program, programIndex) => (
                                            <div key={programIndex} className="flex items-start mb-6 relative">
                                                {/* Show timeline point only if there are multiple programs */}
                                                {groupedEvents[selectedDate].length > 1 && (
                                                    <div className="absolute left-4 top-1 transform -translate-x-1/2 translate-y-1/2 w-6 h-6 bg-primary rounded-full flex items-center justify-center">
                                                        <span className="w-3 h-3 bg-white rounded-full"></span>
                                                    </div>
                                                )}

                                                <div className="ml-10">
                                                    <div className="flex items-center">
                                                        <img src={program.speaker_image} alt={program.speaker_name} className="w-12 h-12 rounded-full object-cover mr-3" />
                                                        <div>
                                                            <h5 className="text-md font-bold text-primary">{program.speaker_name ?? 'Unknown'}</h5>
                                                            <p className="text-sm text-gray-500">Speaker: {program.speaker_name ?? 'Unknown'}</p>
                                                            <p className="text-sm text-gray-500">
                                                                {sessionStartTime} to {sessionEndTime}
                                                            </p>
                                                            <StatusChip status={program.status} />
                                                        </div>
                                                    </div>

                                                    {/* About Speaker */}
                                                    <h4 className="text-sm font-bold text-black mt-4">About Speaker</h4>
                                                    <div className="text-sm text-gray-500 mt-2">
                                                        {showFullBio[programIndex] ? program.speaker_bio : getTruncatedBio(program.speaker_bio)}
                                                        {program.speaker_bio.length > 100 && (
                                                            <button className="text-primary font-semibold ml-2" onClick={() => toggleBio(programIndex)}>
                                                                {showFullBio[programIndex] ? 'Show Less' : 'Read More'}
                                                            </button>
                                                        )}
                                                    </div>

                                                    {/* Enroll Button */}
                                                    <div className="mt-2">
                                                        {program.theme_type === 'parallel' ? (
                                                            program.enrolled ? (
                                                                <p className="text-sm text-green-600">You are already enrolled in this event.</p>
                                                            ) : (
                                                                <button
                                                                    className="bg-primary text-white text-sm px-3 py-2 rounded-md hover:bg-primary-600"
                                                                    onClick={() => openModal(program.id)} // Open confirmation modal
                                                                >
                                                                    Enroll
                                                                </button>
                                                            )
                                                        ) : null}
                                                    </div>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                )}
                            </div>
                        );
                    })
                ) : (
                    <p className="text-center text-gray-500">No events for this day.</p>
                )}
            </div>

            {/* Confirmation Modal */}
            <ConfirmEnrollModal isOpen={isModalOpen} onConfirm={handleEnroll} onCancel={handleCancel} />
        </div>
    );
};

export default Timeline;

// StatusChip Component
const StatusChip = ({ status }) => {
    let bgColor, textColor;

    // Color mapping for each status
    switch (status) {
        case 'Yet to Start':
            bgColor = 'bg-yellow-100';
            textColor = 'text-yellow-700';
            break;
        case 'In Progress':
            bgColor = 'bg-blue-100';
            textColor = 'text-blue-700';
            break;
        case 'Completed':
            bgColor = 'bg-green-100';
            textColor = 'text-green-700';
            break;
        case 'Cancelled':
            bgColor = 'bg-red-100';
            textColor = 'text-red-700';
            break;
        default:
            bgColor = 'bg-gray-100';
            textColor = 'text-gray-700';
            break;
    }

    return <span className={`inline-block px-3 py-1 text-xs font-semibold ${bgColor} ${textColor} rounded-full`}>{status}</span>;
};

const ConfirmEnrollModal = ({ isOpen, onConfirm, onCancel }) => {
    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div className="bg-white p-6 rounded-lg shadow-lg text-center">
                <h2 className="text-lg font-semibold mb-4">Are you sure you want to enroll in this event?</h2>
                <p className="text-sm text-gray-600 mb-6">This action cannot be undone.</p>
                <div className="flex justify-center space-x-4">
                    <button className="bg-primary text-white px-4 py-2 rounded hover:bg-primary-600" onClick={onConfirm}>
                        Yes, Enroll
                    </button>
                    <button className="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400" onClick={onCancel}>
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    );
};

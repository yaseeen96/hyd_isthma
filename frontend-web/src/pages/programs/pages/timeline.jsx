import React, { useState, useEffect } from 'react';
import { useQuery } from 'react-query';
import { useNavigate } from 'react-router-dom'; // Import useNavigate for back button
import dayjs from 'dayjs'; // For date formatting
import LoadingComponent from '../../../components/common/loadingComponent';
import { getProgramDetails, enrollforProgram } from '../../../services/programs_service';
import { FiArrowLeft } from 'react-icons/fi'; // Import a back arrow icon from react-icons

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
    const firstEventDate = dayjs(events[0].datetime.split(' ')[0]); // Assuming datetime format includes the date at the beginning

    // Create an array starting 2 days before and ending 5 days after the first event date
    const dates = [];
    for (let i = -5; i <= 10; i++) {
        dates.push(firstEventDate.add(i, 'day').format('YYYY-MM-DD'));
    }
    return dates;
};

const Timeline = () => {
    const [selectedDate, setSelectedDate] = useState(dayjs().format('YYYY-MM-DD'));
    const [isRefetching, setIsRefetching] = useState(false); // Add refetch state
    const [enrollMessage, setEnrollMessage] = useState(''); // State to store enrollment status
    const [isModalOpen, setIsModalOpen] = useState(false); // State to handle modal visibility
    const [selectedEventId, setSelectedEventId] = useState(null); // State to track which event is selected for enrollment
    const navigate = useNavigate(); // Create a navigate function to go back

    // Use react-query to fetch the timeline data
    const { data, isLoading, isError, error, refetch } = useQuery('timelineData', getProgramDetails, {
        refetchOnWindowFocus: true,
        refetchOnMount: true,
        staleTime: 0,
        onSettled: () => setIsRefetching(false), // Reset refetch state after refetch
    });

    // Trigger refetch and manage refetch state
    useEffect(() => {
        setIsRefetching(true);
        refetch().then(() => setIsRefetching(false)); // End refetching after refetch completes
    }, [refetch]);

    // Show loading state or refetching state
    if (isLoading || isRefetching) {
        return <LoadingComponent />;
    }

    // If there is an error, display an error message
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
        setSelectedEventId(eventId); // Track the event to enroll in
        setIsModalOpen(true); // Open the modal
    };

    // Function to handle the actual enrollment
    const handleEnroll = async () => {
        if (selectedEventId) {
            const response = await enrollforProgram(selectedEventId);
            if (response.status === 'success') {
                setEnrollMessage(response.message); // Show success message
                refetch(); // Refetch timeline data to reflect updated enrollment status
            } else {
                setEnrollMessage('Enrollment failed, please try again.');
            }
        }
        setIsModalOpen(false); // Close the modal
    };

    // Function to cancel the enrollment process
    const handleCancel = () => {
        setIsModalOpen(false); // Close the modal
    };

    return (
        <div className="container mx-auto p-4">
            {/* Back Button */}
            <button
                onClick={() => navigate(-1)} // Navigate back to the previous page
                className="flex items-center text-primary mb-4"
            >
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
                            disabled={!groupedEvents[date]} // Disable if no events on this date
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
                    groupedEvents[selectedDate].map((event) => {
                        const [startTime, endTime] = event.datetime.split('-').map((time) => time.trim());
                        return (
                            <div key={event.id} className="timeline-item flex flex-col mb-4 bg-white p-4 rounded-lg shadow-md">
                                {/* Time Display */}
                                <div className="flex items-center mb-2">
                                    <div className="time-badge bg-primary text-white text-sm font-semibold rounded-full py-1 px-2 mr-2">{dayjs(startTime, 'YYYY-MM-DD h:mm A').format('h:mm A')}</div>
                                    <span className="text-gray-500 text-sm">to</span>
                                    <div className="time-badge bg-primary text-white text-sm font-semibold rounded-full py-1 px-2 ml-2">{dayjs(endTime, 'YYYY-MM-DD h:mm A').format('h:mm A')}</div>
                                </div>

                                {/* Event Details */}
                                <div>
                                    <h3 className="text-lg font-bold text-primary mb-1">{event.name ?? 'No name provided'}</h3>
                                    <p className="text-sm text-gray-600 mb-2">Convener: {event.session_convener ?? 'Unknown'}</p>

                                    {/* Speaker Info */}
                                    <div className="flex items-center mb-3">
                                        <img src={event.speaker_image} alt={event.speaker_name} className="w-12 h-12 rounded-full object-cover mr-3" />
                                        <div>
                                            <h4 className="text-sm font-semibold">{event.speaker_name}</h4>
                                            <p className="text-xs text-gray-500">{event.speaker_bio}</p>
                                        </div>
                                    </div>

                                    {/* Enroll Button */}
                                    <div>
                                        {event.theme_type === 'parallel' ? (
                                            event.enrolled ? (
                                                <p className="text-sm text-green-600">You are already enrolled in this event.</p>
                                            ) : (
                                                <button
                                                    className="bg-primary text-white text-sm px-3 py-2 rounded-md hover:bg-primary-600"
                                                    onClick={() => openModal(event.id)} // Open confirmation modal
                                                >
                                                    Enroll
                                                </button>
                                            )
                                        ) : null}
                                    </div>
                                </div>
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

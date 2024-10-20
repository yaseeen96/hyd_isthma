import React, { useState, useEffect } from 'react';
import { useQuery } from 'react-query';
import { useNavigate } from 'react-router-dom';
import dayjs from 'dayjs';
import LoadingComponent from '../../../components/common/loadingComponent';
import { getProgramDetails, enrollforProgram } from '../../../services/programs_service';
import { FiArrowLeft } from 'react-icons/fi';
import ConfirmEnrollModal from '../components/confirmEnrollModal';
import SessionCard from '../components/sessionCard';

// Helper function to group events by type (Fixed or Parallel) and filter by selected date
const groupEventsByTypeAndDate = (events, selectedDate) => {
    return events.reduce(
        (groupedEvents, event) => {
            const eventDate = dayjs(event.datetime.split(' ')[0]).format('YYYY-MM-DD');
            if (eventDate === selectedDate) {
                if (event.theme_type === 'Fixed') {
                    groupedEvents.fixed.push(event);
                } else {
                    groupedEvents.parallel.push(event);
                }
            }
            return groupedEvents;
        },
        { fixed: [], parallel: [] }
    );
};

// Generate an array of dates for the calendar from the events
const generateCalendarDates = (events) => {
    const firstEventDate = dayjs(events[0].datetime.split(' ')[0]);
    const dates = [];
    for (let i = -3; i <= 3; i++) {
        dates.push(firstEventDate.add(i, 'day').format('YYYY-MM-DD'));
    }
    return dates;
};

const Timeline = () => {
    const [selectedDate, setSelectedDate] = useState(dayjs().format('YYYY-MM-DD'));
    const [enrollMessage, setEnrollMessage] = useState('');
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedEventId, setSelectedEventId] = useState(null);
    const navigate = useNavigate();
    const [expandedSessions, setExpandedSessions] = useState({});

    const toggleSession = (index) => {
        setExpandedSessions((prevState) => ({
            ...prevState,
            [index]: !prevState[index],
        }));
    };

    // Use react-query to fetch the timeline data
    const { data, isLoading, isError, error, refetch } = useQuery('timelineData', getProgramDetails, {
        refetchOnWindowFocus: true,
        refetchOnMount: true,
        staleTime: 0,
    });

    useEffect(() => {
        refetch();
    }, [refetch]);

    if (isLoading) {
        return <LoadingComponent />;
    }

    if (isError) {
        return (
            <div className="text-center p-6">
                <p className="text-red-500">Error: {error.message}</p>
            </div>
        );
    }

    // Generate the calendar dates (3 days before and 3 days after the event)
    const calendarDates = generateCalendarDates(data.data);

    // Group events by type (Fixed and Parallel) and filter by selected date
    const { fixed, parallel } = groupEventsByTypeAndDate(data.data, selectedDate);

    // Function to open the confirmation modal
    const openModal = (eventId) => {
        setSelectedEventId(eventId);
        setIsModalOpen(true);
    };

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
                            className={`py-2 px-3 rounded-lg ${selectedDate === date ? 'bg-primary text-white' : 'bg-gray-200 text-primary hover:bg-primary hover:text-white'}`}
                        >
                            <div>{dayjs(date).format('DD')}</div>
                            <div>{dayjs(date).format('MMM')}</div>
                        </button>
                    ))}
                </div>
            </div>

            {/* Segregated timeline for Fixed and Parallel sessions */}
            <div className="grid grid-cols-2 gap-6">
                {/* Parallel Sessions */}
                <div className="parallel-sessions">
                    <h2 className="text-xl font-bold text-primary mb-4">Parallel Sessions</h2>
                    {parallel.length > 0 ? (
                        parallel.map((session, index) => (
                            <SessionCard key={session.id} session={session} index={index} expandedSessions={expandedSessions} toggleSession={toggleSession} openModal={openModal} />
                        ))
                    ) : (
                        <p className="text-gray-500">No Parallel sessions available for this date.</p>
                    )}
                </div>

                {/* Fixed Sessions */}
                <div className="fixed-sessions">
                    <h2 className="text-xl font-bold text-primary mb-4">Fixed Sessions</h2>
                    {fixed.length > 0 ? (
                        fixed.map((session, index) => (
                            <SessionCard key={session.id} session={session} index={index} expandedSessions={expandedSessions} toggleSession={toggleSession} openModal={openModal} />
                        ))
                    ) : (
                        <p className="text-gray-500">No Fixed sessions available for this date.</p>
                    )}
                </div>
            </div>

            {/* Confirmation Modal */}
            <ConfirmEnrollModal isOpen={isModalOpen} onConfirm={handleEnroll} onCancel={handleCancel} />
        </div>
    );
};

export default Timeline;

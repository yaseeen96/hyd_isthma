import React, { useState, useEffect, useMemo } from 'react';
import { useQuery } from 'react-query';
import { useNavigate } from 'react-router-dom';
import dayjs from 'dayjs';
import LoadingComponent from '../../../components/common/loadingComponent';
import { getProgramDetails, enrollforProgram } from '../../../services/programs_service';
import { FiArrowLeft } from 'react-icons/fi';
import ConfirmEnrollModal from '../components/confirmEnrollModal';
import SessionCard from '../components/sessionCard';
import translations from '../utils/translations';
import { ToastContainer, toast } from 'react-toastify'; // Import toast
import 'react-toastify/dist/ReactToastify.css'; // Import toast styles

// Helper function to group events by type (Fixed or Parallel) and filter by selected date
const groupEventsByTypeAndDate = (events, selectedDate) => {
    return events.reduce(
        (groupedEvents, event) => {
            if (event && event.datetime) {
                const eventDate = dayjs(event.datetime?.split(' ')[0]).format('YYYY-MM-DD');
                if (eventDate === selectedDate) {
                    if (event.theme_type === 'Fixed') {
                        groupedEvents.fixed.push(event);

                    } else {
                        groupedEvents.parallel.push(event);
                    }
                }
            }
            return groupedEvents;
        },
        { fixed: [], parallel: [] }
    );
};

// Generate an array of dates for the calendar from the events
const generateCalendarDates = (events) => {
    const dates = new Set();
    events.forEach(event => {
        if (event && event.datetime) {
            const eventDate = dayjs(event.datetime?.split(' ')[0]);
            dates.add(eventDate.format('YYYY-MM-DD'));
        }
    });

    if (dates.size === 0) return [];

    const uniqueDates = Array.from(dates).map(date => dayjs(date));
    const startDate = uniqueDates.reduce((minDate, currentDate) => {
        return currentDate.isBefore(minDate) ? currentDate : minDate;
    });

    return Array.from({ length: 7 }, (_, i) => startDate.add(i, 'day').format('YYYY-MM-DD'));
};

// Function to process data based on selected language
const processData = (data) => {
    const newData = { ...data };
    newData.data = data.data.map(session => {
        const newSession = { ...session };
        newSession.programs = session.programs.map(program => {
            const newProgram = { ...program };
            const langData = program.english; // Using English as default

            if (langData && langData.topic) {
                newProgram.topic = langData.topic;
                newProgram.transcript = langData.transcript;
                newProgram.translation = langData.translation;
            } else {
                // Fallback to English or default name
                newProgram.topic = program.english?.topic || program.name;
                newProgram.transcript = program.english?.transcript || null;
                newProgram.translation = program.english?.translation || null;
            }

            return newProgram;
        });
        return newSession;
    });
    return newData;
};

const Timeline = () => {
    const [selectedDate, setSelectedDate] = useState('');
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedEventId, setSelectedEventId] = useState(null);
    const navigate = useNavigate();
    const [expandedSessions, setExpandedSessions] = useState({});

    const toggleSession = (index) => {
        setExpandedSessions(prevState => ({
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
        if (data && data.data.length > 0) {
            const nearestEventDate = dayjs(data.data[0].datetime?.split(' ')[0]).format('YYYY-MM-DD');
            setSelectedDate(nearestEventDate);
        }
    }, [data]);

    const handleEnroll = async () => {
        if (selectedEventId) {
            const response = await enrollforProgram(selectedEventId);
            if (response.status === 'success') {
                toast.success(translations.english.enrollMessageSuccess); // Show success toast
                refetch();
                await refetch(); // Refetch the timeline data
            } else {
                toast.error(response.message || translations.english.enrollMessageFailure); // Show error toast
            }
        }
        setIsModalOpen(false);
    };

    const handleCancel = () => {
        setIsModalOpen(false);
    };

    // Process data based on selected language
    const processedData = useMemo(() => {
        if (data && data.data) {
            return processData(data);
        }
        return null;
    }, [data]);

    if (isLoading || !processedData) {
        return <LoadingComponent />;
    }

    if (isError) {
        return (
            <div className="text-center p-6">
                <p className="text-red-500">Error: {error.message}</p>
            </div>
        );
    }

    const calendarDates = generateCalendarDates(processedData.data);
    const { fixed, parallel } = groupEventsByTypeAndDate(processedData.data, selectedDate);

    const openModal = (eventId) => {
        setSelectedEventId(eventId);
        setIsModalOpen(true);
    };

    return (
        <div className="container mx-auto p-4">
            <button onClick={() => navigate(-1)} className="flex items-center text-primary mb-4">
                <FiArrowLeft className="mr-2" size={20} />
                <span className="text-base font-semibold">{translations.english.back}</span>
            </button>

            <h1 className="text-2xl font-bold text-primary mb-6">{translations.english.title}</h1>

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

            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h2 className="text-xl font-semibold mb-2">{translations.english.fixedSessions}</h2>
                    <p className="mb-4 text-gray-600">{translations.english.fixedSessionsDescription}</p>
                    <p className='mb-4 text-gray-600'>Fixed sessions are compulsory to attend</p>
                    {fixed.length === 0 ? (
                        <p>{translations.english.noSessions}</p>
                    ) : (
                        fixed.map((event, index) => (
                            <SessionCard
                                key={event.id}
                                session={event}
                                index={index}
                                expandedSessions={expandedSessions}
                                toggleSession={toggleSession}
                                openModal={openModal}
                            />
                        ))
                    )}
                </div>

                <div>
                    <h2 className="text-xl font-semibold mb-2">{translations.english.parallelSessions}</h2>
                    <p className="mb-4 text-gray-600">{translations.english.parallelSessionsDescription}</p>
                    <p className='mb-4 text-gray-600'>You can attend only 1 parallel session. Once enrolled, it cannot be undone</p>
                    {parallel.length === 0 ? (
                        <p>{translations.english.noSessions}</p>
                    ) : (
                        parallel.map((event, index) => (
                            <SessionCard
                                key={event.id}
                                session={event}
                                index={index}
                                expandedSessions={expandedSessions}
                                toggleSession={toggleSession}
                                openModal={openModal}
                            />
                        ))
                    )}
                </div>
            </div>

            {isModalOpen && (
                <ConfirmEnrollModal
                    isOpen={isModalOpen}
                    onConfirm={handleEnroll}
                    onCancel={handleCancel}
                />
            )}

            
        </div>
    );
};

export default Timeline;

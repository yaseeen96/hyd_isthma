import React, { useState, useEffect, useMemo, useRef } from 'react';
import { useQuery } from 'react-query';
import { useNavigate } from 'react-router-dom';
import dayjs from 'dayjs';
import LoadingComponent from '../../../components/common/loadingComponent';
import { getProgramDetails, enrollforProgram } from '../../../services/programs_service';
import { FiArrowLeft } from 'react-icons/fi';
import ConfirmEnrollModal from '../components/confirmEnrollModal';
import SessionCard from '../components/sessionCard';
import FeedbackModal from '../../home/components/feedbackModal';
import translations from '../utils/translations';
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import GeneratePDF from '../utils/generatePdf';

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

const generateCalendarDates = (events) => {
    const dates = new Set(events.map((event) => dayjs(event.datetime.split(' ')[0]).format('YYYY-MM-DD')).filter((date) => ['2024-11-15', '2024-11-16', '2024-11-17'].includes(date)));
    if (dates.size === 0) {
        return ['2024-11-15', '2024-11-16', '2024-11-17'];
    }
    const uniqueDates = Array.from(dates).map((date) => dayjs(date));
    const startDate = uniqueDates.reduce((minDate, currentDate) => (currentDate.isBefore(minDate) ? currentDate : minDate), dayjs('2024-11-15'));

    return Array.from({ length: 3 }, (_, i) => startDate.add(i, 'day').format('YYYY-MM-DD'));
};

const processData = (data) => {
    return data.map((session) => ({
        ...session,
        programs: session.programs.map((program) => ({
            ...program,
            topic: program.name,
            speaker: {
                name: program.speaker_name,
                bio: program.speaker_bio,
            },
        })),
    }));
};

const Timeline = () => {
    const [selectedDate, setSelectedDate] = useState('');
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedEventId, setSelectedEventId] = useState(null);
    const [isFeedbackModalOpen, setIsFeedbackModalOpen] = useState(false);
    const [selectedProgramId, setSelectedProgramId] = useState(null);
    const [language, setLanguage] = useState('english');
    const navigate = useNavigate();
    const [expandedSessions, setExpandedSessions] = useState({});
    const scrollToRef = useRef(null);

    const toggleSession = (sessionId) => {
        setExpandedSessions((prevState) => ({
            ...prevState,
            [sessionId]: !prevState[sessionId],
        }));
    };

    const { data, isLoading, isError, error, refetch } = useQuery(['timelineData', language], () => getProgramDetails(language), {
        refetchOnWindowFocus: true,
        refetchOnMount: true,
        staleTime: 0,
    });

    useEffect(() => {
        if (data && data.data.length > 0) {
            const earliestInProgress = data.data.find((event) => event.status === 'In Progress');
            const defaultDate = '2024-11-15';
            const nearestEventDate = earliestInProgress ? dayjs(earliestInProgress.datetime.split(' ')[0]).format('YYYY-MM-DD') : defaultDate;
            setSelectedDate(nearestEventDate);

            if (scrollToRef.current) {
                scrollToRef.current.scrollIntoView({ behavior: 'smooth' });
            }
        }
    }, [data]);

    const handleEnroll = async () => {
        if (selectedEventId) {
            const response = await enrollforProgram(selectedEventId);
            if (response.status === 'success') {
                toast.success(translations[language].enrollMessageSuccess);
                refetch();
            } else {
                toast.error(response.message || translations[language].enrollMessageFailure);
            }
        }
        setIsModalOpen(false);
    };

    const handleCancel = () => {
        setIsModalOpen(false);
    };

    const handleLanguageChange = (e) => setLanguage(e.target.value);

    const handleFeedbackOpen = (programId) => {
        setSelectedProgramId(programId);
        setIsFeedbackModalOpen(true);
    };

    const processedData = useMemo(() => {
        return data && data.data ? processData(data.data) : null;
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

    const calendarDates = generateCalendarDates(processedData);
    const { fixed, parallel } = groupEventsByTypeAndDate(processedData, selectedDate);

    const openModal = (eventId) => {
        setSelectedEventId(eventId);
        setIsModalOpen(true);
    };

    return (
        <div className="container mx-auto p-4 min-h-screen overflow-y-auto">
            <button onClick={() => navigate(-1)} className="flex items-center text-primary mb-4">
                <FiArrowLeft className="mr-2" size={20} />
                <span className="text-base font-semibold">{translations[language].back}</span>
            </button>

            <h1 className="text-2xl font-bold text-primary mb-6">{translations[language].title}</h1>

            <div className="mb-6">
                <label htmlFor="language-select" className="mr-2 font-semibold">
                    {translations[language].selectLanguage}
                </label>
                <select
                    id="language-select"
                    value={language}
                    onChange={handleLanguageChange}
                    className="py-2 px-4 w-full border rounded-md text-gray-700 bg-white shadow-sm hover:border-primary focus:ring-2 focus:ring-primary focus:outline-none"
                >
                    <option value="english">English</option>
                    <option value="urdu">Urdu</option>
                    <option value="malyalam">Malayalam</option>
                    <option value="bengali">Bengali</option>
                    <option value="tamil">Tamil</option>
                    <option value="kannada">Kannada</option>
                </select>
            </div>

            <div className="overflow-x-auto mb-6">
                <div className="flex justify-between space-x-2">
                    {calendarDates.map((date) => (
                        <button
                            key={date}
                            onClick={() => setSelectedDate(date)}
                            className={`flex-1 py-2 px-3 rounded-lg text-center ${selectedDate === date ? 'bg-primary text-white' : 'bg-gray-200 text-primary hover:bg-primary hover:text-white'}`}
                        >
                            <div>{dayjs(date).format('DD')}</div>
                            <div>{dayjs(date).format('MMM')}</div>
                        </button>
                    ))}
                </div>
            </div>

            <div className="mb-8">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {fixed.length === 0 ? (
                        <p>{translations[language].noSessions}</p>
                    ) : (
                        fixed.map((event, index) => (
                            <SessionCard
                                ref={index === 0 ? scrollToRef : null}
                                key={event.id}
                                session={event}
                                index={index}
                                expandedSessions={expandedSessions}
                                toggleSession={() => toggleSession(event.id)}
                                openModal={openModal}
                                handleFeedbackOpen={handleFeedbackOpen}
                                backgroundColor="bg-purple-100"
                                programColor="bg-purple-200"
                                noProgramsAvailable={translations[language].noPrograms}
                                enrollMessage={translations[language].enroll}
                                giveFeedback={translations[language].giveFeedback}
                                viewTranslation={translations[language].viewTranslation}
                            />
                        ))
                    )}
                </div>
            </div>

            <div className="mb-8">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {parallel.length === 0 ? (
                        <p>{translations[language].noSessions}</p>
                    ) : (
                        parallel.map((event, index) => (
                            <SessionCard
                                key={event.id}
                                session={event}
                                index={index}
                                expandedSessions={expandedSessions}
                                toggleSession={() => toggleSession(event.id)}
                                openModal={openModal}
                                handleFeedbackOpen={handleFeedbackOpen}
                                backgroundColor="bg-sky-100"
                                programColor="bg-sky-200"
                                noProgramsAvailable={translations[language].noPrograms}
                                enrollMessage={translations[language].enroll}
                                giveFeedback={translations[language].giveFeedback}
                                viewTranslation={translations[language].viewTranslation}
                            />
                        ))
                    )}
                </div>
            </div>

            <GeneratePDF data={data.data} title={translations[language].downloadPdf} />

            {isModalOpen && <ConfirmEnrollModal isOpen={isModalOpen} onConfirm={handleEnroll} onCancel={handleCancel} />}

            {/* Feedback Modal */}
            <FeedbackModal isOpen={isFeedbackModalOpen} onClose={() => setIsFeedbackModalOpen(false)} onSubmit={() => setIsFeedbackModalOpen(false)} programId={selectedProgramId} />
        </div>
    );
};

export default Timeline;

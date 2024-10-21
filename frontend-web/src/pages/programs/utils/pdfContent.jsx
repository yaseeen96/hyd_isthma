import React from 'react';

const PDFContent = ({ data, base64Images }) => {
    const formatTime = (datetime) => {
        const timePart = datetime.split(' ').slice(1).join(' ');
        return timePart;
    };

    const groupByDate = (events) => {
        const grouped = events.reduce((grouped, event) => {
            const eventDate = event.datetime.split(' ')[0];
            if (!grouped[eventDate]) {
                grouped[eventDate] = [];
            }
            grouped[eventDate].push(event);
            return grouped;
        }, {});
        return Object.keys(grouped)
            .sort((a, b) => new Date(a) - new Date(b))
            .reduce((sorted, key) => {
                sorted[key] = grouped[key];
                return sorted;
            }, {});
    };

    const groupedEvents = groupByDate(data);

    return (
        <div className="w-full bg-white flex flex-col items-center justify-start p-8">
            <h1 className="text-center text-5xl font-extrabold text-purple-800 mb-10">Event Timeline</h1>
            <img
                src={base64Images['assets/images/auth/logo_web_black.png'] || 'assets/images/auth/logo_web_black.png'}
                alt="Logo"
                className="w-1/2 mb-8 rounded-full shadow-md"
            />
            <h2 className="text-center text-4xl font-extrabold text-purple-800 mb-4">All India Ijtema e Arkan 2024</h2>
            <h3 className="text-center text-2xl font-semibold text-purple-700">Wadi e Huda</h3>
            <h3 className="text-center text-xl text-purple-600 mb-10">15 - 17 Nov 2024</h3>

            <div className="timeline w-full max-w-5xl mx-auto mt-12 relative">
                {Object.keys(groupedEvents).map((date, index) => {
                    const eventsForDate = groupedEvents[date];
                    const formattedDate = new Date(date).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                    });

                    return (
                        <div key={index} className="mb-16" style={{ pageBreakInside: 'avoid' }}>
                            <div className="flex justify-center items-center mb-8">
                                <div className="w-1 h-16 bg-purple-600"></div>
                                <h2 className="text-3xl font-bold text-gray-900 mx-4">
                                    {formattedDate}
                                </h2>
                                <div className="w-1 h-16 bg-purple-600"></div>
                            </div>

                            {eventsForDate.map((event, eventIndex) => {
                                const formattedTime = formatTime(event.datetime);

                                return (
                                    <div
                                        key={eventIndex}
                                        className="w-full bg-white p-8 mb-12 rounded-lg shadow-lg border-l-8 border-purple-600"
                                        style={{ pageBreakInside: 'avoid' }}
                                    >
                                        <h2 className="text-2xl font-bold text-purple-800 mb-4">
                                            {event.theme_name}
                                        </h2>
                                        <small className="block text-sm text-gray-500 mb-4">
                                            <strong>Convener:</strong> {event.session_convener} - {event.hall_name}
                                        </small>

                                        <div className="flex items-center space-x-8 text-gray-700">
                                            <div className="flex items-center">
                                                <i className="fas fa-clock text-purple-600 mr-2"></i>
                                                <p className="text-md font-medium">{formattedTime}</p>
                                            </div>
                                            <div className="flex items-center">
                                                <i className="fas fa-door-open text-purple-600 mr-2"></i>
                                                <p className="text-md font-medium">Hall: {event.hall_name}</p>
                                            </div>
                                            <div className="flex items-center">
                                                <i className="fas fa-info-circle text-purple-600 mr-2"></i>
                                                <p className="text-md font-medium">Status: {event.status}</p>
                                            </div>
                                        </div>

                                        {event.programs && event.programs.length > 0 && (
                                            <div className="mt-8">
                                                <h3 className="font-semibold text-xl text-purple-700 mb-4">Program Details</h3>
                                                {event.programs.map((program, progIndex) => (
                                                    <div key={progIndex} className="flex items-start mb-6">
                                                        <div className="w-1/4">
                                                            <img
                                                                src={base64Images[program.speaker_image] || 'assets/images/placeholder.png'}
                                                                alt={program.speaker_name}
                                                                className="w-full h-auto rounded-lg shadow-md"
                                                            />
                                                        </div>
                                                        <div className="w-3/4 ml-6">
                                                            <h4 className="font-bold text-lg text-gray-800">
                                                                Speaker: {program.speaker_name}
                                                            </h4>
                                                            <p className="text-md text-gray-700 mt-2">
                                                                {program.speaker_bio}
                                                            </p>
                                                            <ul className="list-disc list-inside text-md text-gray-700 mt-2">
                                                                <li>{program.name}</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                ))}
                                            </div>
                                        )}
                                    </div>
                                );
                            })}
                        </div>
                    );
                })}
            </div>
        </div>
    );
};

export default PDFContent;

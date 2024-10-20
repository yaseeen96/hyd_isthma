import React from 'react';
import { FiArrowDown, FiArrowUp } from 'react-icons/fi';

const SessionCard = ({ session, index, expandedSessions, toggleSession, openModal }) => {
    const dateTimeRange = session.datetime.match(/(\d{2}:\d{2} (AM|PM))/g);
    const sessionStartTime = dateTimeRange ? dateTimeRange[0] : null;
    const sessionEndTime = dateTimeRange ? dateTimeRange[1] : null;

    return (
        <div className="mb-6 bg-white p-4 rounded-lg shadow-md">
            <div className="flex items-center justify-between mb-4 cursor-pointer" onClick={() => toggleSession(index)}>
                <div>
                    <h3 className="text-lg font-bold text-primary">{session.theme_name}</h3>
                    <p className="text-sm text-gray-600">Convener: {session.session_convener ?? 'Unknown'}</p>
                    <p className="text-sm text-gray-500">{session.convener_bio ?? 'Bio not available'}</p>
                    <p className="text-sm text-gray-500">
                        {sessionStartTime} to {sessionEndTime}
                    </p>
                    <StatusChip status={session.status} />
                </div>
                <div>{expandedSessions[index] ? <FiArrowUp size={24} /> : <FiArrowDown size={24} />}</div>
            </div>

            {expandedSessions[index] && (
                <div className="ml-6">
                    {session.programs.length > 0 ? (
                        session.programs.map((program) => (
                            <div key={program.id} className="flex items-start mb-6">
                                <img src={program.speaker_image} alt={program.speaker_name} className="w-12 h-12 rounded-full object-cover mr-3" />
                                <div>
                                    <h5 className="text-md font-bold text-primary">{program.name}</h5>
                                    <p className="text-sm text-gray-500">Speaker: {program.speaker_name}</p>
                                    <StatusChip status={program.status} />
                                    {!program.enrolled && (
                                        <button className="bg-primary text-white text-sm px-3 py-2 mt-2 rounded-md" onClick={() => openModal(program.id)}>
                                            Enroll
                                        </button>
                                    )}
                                </div>
                            </div>
                        ))
                    ) : (
                        <p className="text-gray-500">No programs for this session.</p>
                    )}
                </div>
            )}
        </div>
    );
};

const StatusChip = ({ status }) => {
    let bgColor, textColor;
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
export default SessionCard;

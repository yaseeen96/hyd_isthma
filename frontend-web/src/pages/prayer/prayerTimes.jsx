import React from 'react';
import { FaPrayingHands, FaMosque } from 'react-icons/fa';

const PrayerTimes = () => {
    const dayOneTimings = [
        { prayer: 'Fajr', startTime: '5:15 AM', congregationalTime: '5:45 AM' },
        { prayer: 'Friday (Khutbah)', startTime: '12:30 PM', congregationalTime: '1:00 PM' },
        { prayer: 'Zuhr', startTime: '12:05 PM', congregationalTime: '12:30 PM' },
        { prayer: 'Asr', startTime: '4:45 PM', congregationalTime: '5:05 PM' },
        { prayer: 'Maghrib', startTime: '5:40 PM', congregationalTime: '5:43 PM' },
        { prayer: 'Isha (First and Last Day)', startTime: '8:30 PM', congregationalTime: '8:50 PM' },
    ];

    const dayTwoTimings = [
        { prayer: 'Fajr', startTime: '5:15 AM', congregationalTime: '5:45 AM' },
        { prayer: 'Zuhr', startTime: '12:05 PM', congregationalTime: '12:30 PM' },
        { prayer: 'Asr', startTime: '4:45 PM', congregationalTime: '5:05 PM' },
        { prayer: 'Maghrib', startTime: '5:40 PM', congregationalTime: '5:43 PM' },
        { prayer: 'Isha (Second Day)', startTime: '9:30 PM', congregationalTime: '9:50 PM' },
    ];

    const TimingCard = ({ prayer, startTime, congregationalTime }) => (
        <div className="flex justify-between items-center bg-white p-4 rounded-lg shadow-lg transform transition duration-200 hover:scale-105">
            <div className="flex items-center space-x-3">
                <FaPrayingHands className="text-primary text-xl" />
                <div>
                    <p className="text-lg font-semibold text-gray-800">{prayer}</p>
                    <p className="text-sm text-gray-500">Start: {startTime}</p>
                </div>
            </div>
            <p className="text-lg font-bold text-primary">{congregationalTime}</p>
        </div>
    );

    return (
        <div className="bg-gradient-to-r from-blue-100 via-blue-200 to-blue-300 p-6 rounded-lg shadow-md max-w-md mx-auto ">
            <h2 className="text-3xl font-bold text-primary mb-6 text-center">Prayer Times</h2>

            {/* Day 1 Section */}
            <div className="mb-6">
                <div className="flex justify-center items-center mb-4">
                    <FaMosque className="text-primary text-2xl mr-2" />
                    <h3 className="text-xl font-semibold text-gray-700">Day 1</h3>
                </div>
                <div className="space-y-4">
                    {dayOneTimings.map((time, index) => (
                        <TimingCard key={index} {...time} />
                    ))}
                </div>
            </div>

            <div className="border-t border-primary my-6"></div>

            {/* Day 2 Section */}
            <div className="mb-6">
                <div className="flex justify-center items-center mb-4">
                    <FaMosque className="text-primary text-2xl mr-2" />
                    <h3 className="text-xl font-semibold text-gray-700">Day 2</h3>
                </div>
                <div className="space-y-4">
                    {dayTwoTimings.map((time, index) => (
                        <TimingCard key={index} {...time} />
                    ))}
                </div>
            </div>

            {/* Note Section */}
            <div className="mt-6 p-4 bg-white rounded-lg shadow-lg text-gray-600 text-center">
                <p className="text-sm">
                    <strong>Note:</strong> Those who wish to combine prayers may do so at the congregation area, where arrangements will be made to collectively perform Zuhr and Asr during Zuhr time
                    and Maghrib and Isha during Maghrib time.
                </p>
            </div>
        </div>
    );
};

export default PrayerTimes;

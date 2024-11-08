import React from 'react';
import { FaMosque, FaArrowLeft } from 'react-icons/fa';
import { useNavigate } from 'react-router-dom';

const PrayerTimes = () => {
    const navigate = useNavigate();

    const dayOneTimings = [
        { prayer: 'Fajr (فجر)', adhan: '5:15 AM', jamaat: '5:45 AM' },
        { prayer: 'Jummah - Khutbah (جمعہ)', adhan: '12:30 PM', jamaat: '1:00 PM' },
        { prayer: 'Asr (عصر)', adhan: '4:45 PM', jamaat: '5:05 PM' },
        { prayer: 'Maghrib (مغرب)', adhan: '5:40 PM', jamaat: '5:43 PM' },
        { prayer: 'Isha (عشاء)', adhan: '8:30 PM', jamaat: '8:50 PM' },
    ];

    const dayTwoTimings = [
        { prayer: 'Fajr (فجر)', adhan: '5:15 AM', jamaat: '5:45 AM' },
        { prayer: 'Zuhr (ظہر)', adhan: '12:05 PM', jamaat: '12:30 PM' },
        { prayer: 'Asr (عصر)', adhan: '4:45 PM', jamaat: '5:05 PM' },
        { prayer: 'Maghrib (مغرب)', adhan: '5:40 PM', jamaat: '5:43 PM' },
        { prayer: 'Isha (عشاء)', adhan: '9:30 PM', jamaat: '9:50 PM' },
    ];

    const dayThreeTimings = [
        { prayer: 'Fajr (فجر)', adhan: '5:15 AM', jamaat: '5:45 AM' },
        { prayer: 'Zuhr (ظہر)', adhan: '12:05 PM', jamaat: '12:30 PM' },
        { prayer: 'Asr (عصر)', adhan: '4:45 PM', jamaat: '5:05 PM' },
        { prayer: 'Maghrib (مغرب)', adhan: '5:40 PM', jamaat: '5:43 PM' },
        { prayer: 'Isha (عشاء)', adhan: '8:30 PM', jamaat: '8:50 PM' },
    ];

    const TimingCard = ({ prayer, adhan, jamaat }) => (
        <div className="flex justify-between items-center bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-transform duration-200 transform hover:scale-105">
            <div className="flex items-center space-x-4 w-1/3">
                <FaMosque className="text-primary text-3xl" />
                <p className="text-lg font-bold text-gray-800">{prayer}</p>
            </div>
            <div className="flex flex-col md:flex-row md:space-x-6 text-center w-1/3">
                <p className="text-md font-semibold">
                    <span className="text-gray-600">Adhan (اذان): </span>
                    <span className="text-blue-600 text-lg font-bold">{adhan}</span>
                </p>
            </div>
            <div className="flex flex-col md:flex-row md:space-x-6 text-center w-1/3">
                <p className="text-md font-semibold">
                    <span className="text-gray-600">Jamaat (جماعت): </span>
                    <span className="text-green-600 text-lg font-bold">{jamaat}</span>
                </p>
            </div>
        </div>
    );

    return (
        <div className="bg-gradient-to-r from-blue-100 via-blue-200 to-blue-300 p-8 rounded-lg shadow-lg max-w-xl mx-auto space-y-12">
            <button onClick={() => navigate(-1)} className="flex items-center text-primary font-semibold mb-8">
                <FaArrowLeft className="mr-2" />
                Back
            </button>

            <h2 className="text-3xl font-bold text-primary mb-8 text-center">Prayer Times</h2>

            {[
                { day: '1st Day - 15th Nov', timings: dayOneTimings },
                { day: '2nd Day - 16th Nov', timings: dayTwoTimings },
                { day: '3rd Day - 17th Nov', timings: dayThreeTimings },
            ].map((dayData, index) => (
                <div key={index} className="space-y-6">
                    <div className="flex justify-center items-center mb-4">
                        <FaMosque className="text-primary text-2xl mr-2" />
                        <h3 className="text-2xl font-semibold text-gray-800">{dayData.day}</h3>
                    </div>
                    <div className="space-y-4">
                        {dayData.timings.map((time, idx) => (
                            <TimingCard key={idx} {...time} />
                        ))}
                    </div>
                    {index < 2 && <div className="border-t border-primary my-8"></div>}
                </div>
            ))}

            <div className="mt-8 p-6 bg-white rounded-lg shadow-md text-gray-700 text-center leading-relaxed">
                <p className="text-md mb-3">
                    <strong>نوٹ:</strong> جو لوگ نمازیں جمع کرنا چاہیں، ان کے لیے اجتماع کی نماز گاہ میں <strong>ظہر کے وقت ظہر و عصر</strong> اور <strong>مغرب کے وقت مغرب و عشا</strong> کی نمازیں جمع
                    کرکے باجماعت ادا کرنے کا نظم رہے گا۔
                </p>
                <p className="text-md mt-4">
                    <strong>Note:</strong> Those who wish to combine prayers may do so at the congregation area, where arrangements will be made to collectively perform{' '}
                    <strong>Zuhr and Asr during Zuhr</strong> and <strong>Maghrib and Isha during Maghrib</strong> time.
                </p>
            </div>
        </div>
    );
};

export default PrayerTimes;

import React from 'react';
import { FaMosque, FaArrowLeft } from 'react-icons/fa';
import { useNavigate } from 'react-router-dom';

const PrayerTimes = () => {
    const navigate = useNavigate();

    // Prayer timings by day with only Adhan times as provided
    const dayOneTimings = [
        { prayer: 'فجر (Fajr)', adhan: '5:15 AM' },
        { prayer: 'جمعہ (Jummah - Khutbah)', adhan: '12:30 PM' },
        { prayer: 'عصر (Asr)', adhan: '4:45 PM' },
        { prayer: 'مغرب (Maghrib)', adhan: '5:40 PM' },
        { prayer: 'عشاء (Isha)', adhan: '8:30 PM' },
    ];

    const dayTwoTimings = [
        { prayer: 'فجر (Fajr)', adhan: '5:15 AM' },
        { prayer: 'ظہر (Zuhr)', adhan: '12:05 PM' },
        { prayer: 'عصر (Asr)', adhan: '4:45 PM' },
        { prayer: 'مغرب (Maghrib)', adhan: '5:40 PM' },
        { prayer: 'عشاء (Isha)', adhan: '9:30 PM' },
    ];

    const dayThreeTimings = [
        { prayer: 'فجر (Fajr)', adhan: '5:15 AM' },
        { prayer: 'ظہر (Zuhr)', adhan: '12:05 PM' },
        { prayer: 'عصر (Asr)', adhan: '4:45 PM' },
        { prayer: 'مغرب (Maghrib)', adhan: '5:40 PM' },
        { prayer: 'عشاء (Isha)', adhan: '8:30 PM' },
    ];

    const TimingCard = ({ prayer, adhan }) => (
        <div className="flex justify-between items-center bg-white p-5 rounded-lg shadow-lg hover:shadow-2xl transition duration-200 transform hover:scale-105">
            <div className="flex items-center space-x-4">
                <FaMosque className="text-blue-600 text-3xl" />
                <div>
                    <p className="text-xl font-bold text-gray-800">{prayer}</p>
                    <p className="text-sm text-gray-500">Adhan: {adhan}</p>
                </div>
            </div>
        </div>
    );

    return (
        <div className="bg-gradient-to-r from-indigo-100 via-blue-200 to-blue-300 p-6 rounded-lg shadow-lg max-w-lg mx-auto space-y-8">
            {/* Back Button */}
            <button onClick={() => navigate(-1)} className="flex items-center text-blue-600 font-semibold mb-4">
                <FaArrowLeft className="mr-2" />
                Back
            </button>

            <h2 className="text-3xl font-bold text-blue-800 mb-8 text-center">Prayer Times</h2>

            {/* Day 1 Section */}
            <div className="mb-8">
                <div className="flex justify-center items-center mb-4">
                    <FaMosque className="text-blue-600 text-2xl mr-2" />
                    <h3 className="text-2xl font-semibold text-gray-800">1st Day - 15th Nov</h3>
                </div>
                <div className="space-y-4">
                    {dayOneTimings.map((time, index) => (
                        <TimingCard key={index} {...time} />
                    ))}
                </div>
            </div>

            <div className="border-t border-blue-500 my-6"></div>

            {/* Day 2 Section */}
            <div className="mb-8">
                <div className="flex justify-center items-center mb-4">
                    <FaMosque className="text-blue-600 text-2xl mr-2" />
                    <h3 className="text-2xl font-semibold text-gray-800">2nd Day - 16th Nov</h3>
                </div>
                <div className="space-y-4">
                    {dayTwoTimings.map((time, index) => (
                        <TimingCard key={index} {...time} />
                    ))}
                </div>
            </div>

            <div className="border-t border-blue-500 my-6"></div>

            {/* Day 3 Section */}
            <div className="mb-8">
                <div className="flex justify-center items-center mb-4">
                    <FaMosque className="text-blue-600 text-2xl mr-2" />
                    <h3 className="text-2xl font-semibold text-gray-800">3rd Day - 17th Nov</h3>
                </div>
                <div className="space-y-4">
                    {dayThreeTimings.map((time, index) => (
                        <TimingCard key={index} {...time} />
                    ))}
                </div>
            </div>

            {/* Note Section */}
            <div className="mt-6 p-5 bg-white rounded-lg shadow-lg text-gray-700 text-center">
                <p className="text-md mb-2">
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

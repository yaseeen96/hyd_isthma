import React from 'react';
import { FaMosque, FaArrowLeft } from 'react-icons/fa';
import { useNavigate } from 'react-router-dom';

const PrayerTimes = () => {
    const navigate = useNavigate();

    // Prayer timings by day with Adhan and Jamaat times
    const dayOneTimings = [
        { prayer: 'فجر (Fajr)', adhan: '5:15 AM', jamaat: '5:45 AM' },
        { prayer: 'جمعہ (Jummah - Khutbah)', adhan: '12:30 PM', jamaat: '1:00 PM' },
        { prayer: 'عصر (Asr)', adhan: '4:45 PM', jamaat: '5:05 PM' },
        { prayer: 'مغرب (Maghrib)', adhan: '5:40 PM', jamaat: '5:43 PM' },
        { prayer: 'عشاء (Isha)', adhan: '8:30 PM', jamaat: '8:50 PM' },
    ];

    const dayTwoTimings = [
        { prayer: 'فجر (Fajr)', adhan: '5:15 AM', jamaat: '5:45 AM' },
        { prayer: 'ظہر (Zuhr)', adhan: '12:05 PM', jamaat: '12:30 PM' },
        { prayer: 'عصر (Asr)', adhan: '4:45 PM', jamaat: '5:05 PM' },
        { prayer: 'مغرب (Maghrib)', adhan: '5:40 PM', jamaat: '5:43 PM' },
        { prayer: 'عشاء (Isha)', adhan: '9:30 PM', jamaat: '9:50 PM' },
    ];

    const dayThreeTimings = [
        { prayer: 'فجر (Fajr)', adhan: '5:15 AM', jamaat: '5:45 AM' },
        { prayer: 'ظہر (Zuhr)', adhan: '12:05 PM', jamaat: '12:30 PM' },
        { prayer: 'عصر (Asr)', adhan: '4:45 PM', jamaat: '5:05 PM' },
        { prayer: 'مغرب (Maghrib)', adhan: '5:40 PM', jamaat: '5:43 PM' },
        { prayer: 'عشاء (Isha)', adhan: '8:30 PM', jamaat: '8:50 PM' },
    ];

    const TimingCard = ({ prayer, adhan, jamaat }) => (
        <div className="flex justify-between items-center bg-white p-5 rounded-lg shadow-lg hover:shadow-xl transition duration-200 transform hover:scale-105 space-y-2 md:space-y-0">
            <div className="flex items-center space-x-3 md:space-x-5">
                <FaMosque className="text-primary text-3xl" />
                <div className="text-left">
                    <p className="text-lg font-bold text-gray-800">{prayer}</p>
                </div>
            </div>
            <div className="flex flex-col md:flex-row md:space-x-6">
                <p className="text-md font-semibold">
                    <span className="text-gray-600">Adhan (اذان): </span>
                    <span className="text-blue-600 text-lg font-bold">{adhan}</span>
                </p>
                <p className="text-md font-semibold">
                    <span className="text-gray-600">Jamaat (جماعت): </span>
                    <span className="text-green-600 text-lg font-bold">{jamaat}</span>
                </p>
            </div>
        </div>
    );

    return (
        <div className="bg-gradient-to-r from-blue-100 via-blue-200 to-blue-300 p-6 rounded-lg shadow-lg max-w-lg mx-auto space-y-8">
            {/* Back Button */}
            <button onClick={() => navigate(-1)} className="flex items-center text-primary font-semibold mb-6">
                <FaArrowLeft className="mr-2" />
                Back
            </button>

            <h2 className="text-3xl font-bold text-primary mb-10 text-center">Prayer Times</h2>

            {/* Day 1 Section */}
            <div className="mb-8">
                <div className="flex justify-center items-center mb-4">
                    <FaMosque className="text-primary text-2xl mr-2" />
                    <h3 className="text-2xl font-semibold text-gray-800">1st Day - 15th Nov</h3>
                </div>
                <div className="space-y-4">
                    {dayOneTimings.map((time, index) => (
                        <TimingCard key={index} {...time} />
                    ))}
                </div>
            </div>

            <div className="border-t border-primary my-8"></div>

            {/* Day 2 Section */}
            <div className="mb-8">
                <div className="flex justify-center items-center mb-4">
                    <FaMosque className="text-primary text-2xl mr-2" />
                    <h3 className="text-2xl font-semibold text-gray-800">2nd Day - 16th Nov</h3>
                </div>
                <div className="space-y-4">
                    {dayTwoTimings.map((time, index) => (
                        <TimingCard key={index} {...time} />
                    ))}
                </div>
            </div>

            <div className="border-t border-primary my-8"></div>

            {/* Day 3 Section */}
            <div className="mb-8">
                <div className="flex justify-center items-center mb-4">
                    <FaMosque className="text-primary text-2xl mr-2" />
                    <h3 className="text-2xl font-semibold text-gray-800">3rd Day - 17th Nov</h3>
                </div>
                <div className="space-y-4">
                    {dayThreeTimings.map((time, index) => (
                        <TimingCard key={index} {...time} />
                    ))}
                </div>
            </div>

            {/* Note Section */}
            <div className="mt-6 p-5 bg-white rounded-lg shadow-lg text-gray-700 text-center leading-relaxed">
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

import React, { useState } from 'react';
import HomeLayout from '../layout/Homelayout';
import { FiArrowLeft } from 'react-icons/fi';
import FeedbackModal from '../components/feedbackModal';
import { useNavigate } from 'react-router-dom';
import { FaWhatsapp } from 'react-icons/fa';

const SupportPage = () => {
    const [isModalOpen, setIsModalOpen] = useState(false);
    const navigate = useNavigate();

    const handleFeedbackSubmit = (feedback) => {
        console.log('Feedback submitted:', feedback);
        setIsModalOpen(false);
    };

    return (
        <HomeLayout>
            <div className="w-full flex flex-col justify-center items-center p-4">
                {/* Back button for navigation */}

                {/* Email Us Section */}
                <div className="p-4 rounded-lg bg-gray-50 md:p-6 dark:bg-gray-800 border border-gray-100 w-full my-2">
                    <span className="inline-block p-3 text-primary rounded-lg bg-blue-100/80 dark:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" className="w-5 h-5">
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"
                            />
                        </svg>
                    </span>
                    <h2 className="mt-4 text-base font-medium text-gray-800 dark:text-white">Email Us</h2>
                    <p className="mt-2 text-sm text-gray-500 dark:text-gray-400">Talk with our friendly team.</p>
                    <a href="mailto:aiia@jih.org.in" className="mt-2 text-sm text-primary dark:text-primary">
                        aiia@jih.org.in
                    </a>
                </div>

                {/* Call for Support Section */}
                <div className="p-4 rounded-lg bg-gray-50 md:p-6 dark:bg-gray-800 border border-gray-100 w-full my-2">
                    <span className="inline-block p-3 text-primary rounded-lg bg-blue-100/80 dark:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" className="w-5 h-5">
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"
                            />
                        </svg>
                    </span>
                    <h2 className="mt-4 text-base font-medium text-gray-800 dark:text-white">Call for Support</h2>
                    <p className="mt-2 text-sm text-gray-500 dark:text-gray-400">Speak to our friendly team.</p>
                    <a href="tel:+919319404079" className="mt-2 text-sm text-primary dark:text-primary">
                        +91 89202 21894
                    </a>
                </div>

                {/* Feedback Button and Modal */}
                <button onClick={() => setIsModalOpen(true)} className="bg-primary text-white py-2 px-6 rounded-lg font-semibold mt-4 hover:bg-primary-dark transition duration-200 w-full">
                    Give Feedback
                </button>

                {/* Feedback Modal Component */}
                <FeedbackModal isOpen={isModalOpen} onClose={() => setIsModalOpen(false)} onSubmit={handleFeedbackSubmit} />

                {/* WhatsApp Floating Action Button */}
                <a
                    href="https://api.whatsapp.com/send?phone=917676079163&text=Assalamualaikum,%0A%0AI’m%20experiencing%20an%20issue%20with%20JIH%20Ijtema%202024.%0ACould%20someone%20assist%20me?%0A%0AIssue%20details:"
                    target="_blank"
                    rel="noopener noreferrer"
                    className="fixed bottom-20 right-6 bg-green-500 text-white p-4 rounded-full shadow-lg hover:bg-green-600 transition duration-200 flex items-center justify-center"
                >
                    <FaWhatsapp className="w-8 h-8" />
                </a>
            </div>
        </HomeLayout>
    );
};

export default SupportPage;

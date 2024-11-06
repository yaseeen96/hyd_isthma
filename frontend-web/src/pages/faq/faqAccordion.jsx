import React, { useState } from 'react';
import { useQuery } from 'react-query';
import { getFAQs } from '../../services/programs_service';
import { FaChevronDown, FaChevronUp, FaArrowLeft, FaDownload } from 'react-icons/fa';
import { useNavigate } from 'react-router-dom';
import LoadingComponent from '../../components/common/loadingComponent';
import './faqAccordion.css'; // Ensure this CSS file exists

const FAQAccordion = () => {
    const navigate = useNavigate();
    const { data, isLoading, isError } = useQuery('faqs', getFAQs);
    const [expandedIndex, setExpandedIndex] = useState(null);

    // Function to detect Urdu text
    const isUrdu = (text) => {
        const urduPattern = /[\u0600-\u06FF]/;
        return urduPattern.test(text);
    };

    const toggleAccordion = (index) => {
        setExpandedIndex(expandedIndex === index ? null : index);
    };

    if (isLoading) return <LoadingComponent />;
    if (isError) return <p>Something went wrong. Please try again later.</p>;

    return (
        <div className="max-w-md mx-auto bg-gradient-to-r from-blue-100 via-blue-200 to-blue-300 p-4 rounded-lg shadow-lg min-h-screen">
            {/* Back Button */}
            <button onClick={() => navigate(-1)} className="flex items-center text-primary font-semibold mb-4">
                <FaArrowLeft className="mr-2" />
                Back
            </button>

            <h2 className="text-3xl font-bold text-primary mb-6 text-center">Announcements/FAQs</h2>

            {data?.data.map((faq, index) => {
                const isContentUrdu = isUrdu(faq.question) || isUrdu(faq.answer);
                const directionClass = isContentUrdu ? 'rtl' : 'ltr';

                return (
                    <div key={faq.id} className={`mb-4 bg-white rounded-lg shadow-md p-4 ${directionClass}`}>
                        {/* Accordion Header */}
                        <div className="flex justify-between items-center cursor-pointer" onClick={() => toggleAccordion(index)}>
                            <h3 className={`text-lg font-semibold ${isContentUrdu ? 'text-right' : 'text-left'} text-gray-800`}>{faq.question}</h3>
                            <div className={`chevron ${expandedIndex === index ? 'rotate' : ''}`}>{expandedIndex === index ? <FaChevronUp /> : <FaChevronDown />}</div>
                        </div>

                        {/* Accordion Content with Animation */}
                        <div
                            className={`faq-content ${expandedIndex === index ? 'expanded' : ''}`}
                            style={{
                                maxHeight: expandedIndex === index ? '500px' : '0',
                                opacity: expandedIndex === index ? 1 : 0,
                            }}
                        >
                            <p className={`mb-3 whitespace-pre-line ${isContentUrdu ? 'text-right' : 'text-left'}`}>{faq.answer}</p>

                            {faq.faq_attachment !== 'NA' && faq.faq_attachment && (
                                <div className="mt-3">
                                    <img src={faq.faq_attachment} alt="FAQ Attachment" className="w-full h-auto rounded-lg shadow-md mb-3" />
                                    <a
                                        href={faq.faq_attachment}
                                        download="attachment.jpg" // specify filename for download
                                        className="flex items-center text-blue-500 hover:text-blue-700 mt-2 px-4 py-2 border border-blue-500 rounded-md"
                                    >
                                        <FaDownload className="mr-2" />
                                        Download Image
                                    </a>
                                </div>
                            )}
                        </div>
                    </div>
                );
            })}
        </div>
    );
};

export default FAQAccordion;

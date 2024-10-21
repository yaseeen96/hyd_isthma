import { useNavigate } from 'react-router-dom';

const RegistrationLayout = ({ children }) => {
    const navigate = useNavigate();

    const handleBackClick = () => {
        navigate(-1); // This will navigate to the previous page
    };

    return (
        <div className="flex flex-col min-h-screen items-start py-16 px-8 bg-gray-50 dark:bg-black">
            {/* Back Button (visible only on mobile devices) */}
            <button onClick={handleBackClick} className="md:hidden mb-4 text-gray-900 dark:text-gray-50" aria-label="Go back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" className="h-6 w-6">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <h1 className="text-2xl text-gray-900 dark:text-gray-50 font-bold w-5/6">Please fill the below details</h1>
            {children}
        </div>
    );
};

export default RegistrationLayout;

import React, { useState, useEffect } from 'react';
import StepsList from './components/stepsList';
import { localStorageConstant } from '../../utils/constants/localStorageConstants';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import { ROUTES } from '../../router/routes';

const RegisterPage = () => {
    const navigate = useNavigate();
    const steps = [
        { id: 1, title: 'RSVP for the Event', description: 'Let us know if you’ll be attending the event.' },
        { id: 2, title: 'Family Details', description: 'Tell us if you’re bringing any family members, including mehrams or children.' },
        { id: 3, title: 'Payment Information', description: 'Confirm whether you’ve completed the payment for the event.' },
        { id: 4, title: 'Travel and Stay Arrangements', description: 'Share your travel details and let us know if you need accommodation or have any special requirements.' },
    ];

    const [activeStep, setActiveStep] = useState(0);

    useEffect(() => {
        const arrivalConfirmed = localStorage.getItem(localStorageConstant.arrivalConfirmed) === '1';
        const arrivalDetails = localStorage.getItem(localStorageConstant.arrivalDetails) === '1';
        const familyDetails = localStorage.getItem(localStorageConstant.familyDetails) === '1';
        const financialDetails = localStorage.getItem(localStorageConstant.financialDetails) === '1';

        let step = 0;

        if (arrivalConfirmed) step = 1;
        if (familyDetails) step = 2;
        if (financialDetails) step = 3;
        if (arrivalDetails) step = 4;

        setActiveStep(step);
    }, []);

    const handleStepClick = (stepId) => {
        switch (stepId) {
            case 1:
                navigate(ROUTES.rsvpRegistration);
                break;
            case 2:
                navigate(ROUTES.familyRegistration);
                break;
            case 3:
                console.log('to navigate payment details');
                break;
            case 4:
                console.log('to navigate travel details');

                break;
            default:
                toast.error('invalid selection');
        }
    };

    const progress = (activeStep / steps.length) * 100;

    return (
        <div className="h-screen flex flex-col items-center justify-start p-6 bg-gray-50 dark:bg-gray-900">
            <h2 className="text-2xl font-bold mb-2 dark:text-white">Welcome to the Registration Page</h2>
            <p className="text-lg text-gray-600 dark:text-gray-300 text-start mb-2">Please complete these steps for us to serve you better</p>
            <div className="w-full max-w-3xl ">
                <div className="relative pt-1 mb-6">
                    <div className="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                        <div
                            style={{ width: `${progress}%` }}
                            className="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary transition-all duration-500"
                        ></div>
                    </div>
                </div>
                <StepsList steps={steps} activeStep={activeStep} onStepClick={handleStepClick} />
            </div>
        </div>
    );
};

export default RegisterPage;

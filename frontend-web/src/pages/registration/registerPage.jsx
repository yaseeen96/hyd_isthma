import React, { useState, useEffect } from 'react';
import StepsList from './components/stepsList';
import { localStorageConstant } from '../../utils/constants/localStorageConstants';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import { ROUTES } from '../../router/routes';
import { useQuery } from 'react-query';
import { getRegistrationDetails } from '../../services/registration_service';
import LoadingComponent from '../../components/common/loadingComponent';
import { useSetRecoilState } from 'recoil';
import { registrationDetailsAtom } from '../../store/atoms/registrationDetailsAtom';

const RegisterPage = () => {
    const setRegistrationDetails = useSetRecoilState(registrationDetailsAtom);
    const [isRefetching, setIsRefetching] = useState(false); // State to manage refetch indicator

    const navigate = useNavigate();

    // Fetch registration details with react-query
    const { isLoading, isError, data, error, refetch } = useQuery('getRegistrationDetails', getRegistrationDetails, {
        onSettled: () => setIsRefetching(false),
        refetchOnWindowFocus: true,
        refetchOnMount: true,
        staleTime: 0,
    });

    // Updated order of steps
    const steps = [
        { id: 1, title: 'RSVP for the Event', description: 'Let us know if you’ll be attending the event.' },
        { id: 2, title: 'Family Details', description: 'Tell us if you’re bringing any family members, including mehrams or children.' },
        { id: 3, title: 'Travel and Stay Arrangements', description: 'Share your travel details and let us know if you need accommodation or have any special requirements.' },
        { id: 4, title: 'Payment Information', description: 'Confirm whether you’ve completed the payment for the event.' },
    ];

    const [activeStep, setActiveStep] = useState(0);

    useEffect(() => {
        setIsRefetching(true); // Set refetching to true when component mounts or when you navigate back
        refetch().then(() => {
            const arrivalConfirmed = localStorage.getItem(localStorageConstant.arrivalConfirmed) === '1';
            const arrivalDetails = localStorage.getItem(localStorageConstant.arrivalDetails) === '1';
            const familyDetails = localStorage.getItem(localStorageConstant.familyDetails) === '1';
            const financialDetails = localStorage.getItem(localStorageConstant.financialDetails) === '1';

            let step = 0;

            if (arrivalConfirmed) step = 1;
            if (familyDetails) step = 2;
            if (arrivalDetails) step = 3;
            if (financialDetails) step = 4;

            if (arrivalConfirmed && familyDetails && arrivalDetails && financialDetails) {
                step = steps.length; // Set to the length of the steps array when all are completed
            }

            setActiveStep(step);
        });
    }, [refetch]);

    const handleStepClick = (stepId) => {
        switch (stepId) {
            case 1:
                navigate(ROUTES.rsvpRegistration);
                break;
            case 2:
                navigate(ROUTES.familyRegistration);
                break;
            case 3:
                navigate(ROUTES.additionalRegistration); // Now refers to travel and stay arrangements
                break;
            case 4:
                navigate(ROUTES.financialRegistration); // Now refers to payment information
                break;
            default:
                toast.error('Invalid selection');
        }
    };

    // Updated progress calculation
    const progress = (activeStep / steps.length) * 100;

    useEffect(() => {
        if (data) {
            const newData = data.data;
            setRegistrationDetails((prev) => ({ ...prev, ...newData }));
        }
    }, [data, setRegistrationDetails]);

    const handleRefreshClick = async () => {
        setIsRefetching(true);
        await refetch();
    };

    if (isLoading || isRefetching) return <LoadingComponent />;

    if (isError)
        return (
            <div className="flex flex-col justify-center items-center h-screen">
                <h3>An Error Occurred. Please come back later</h3>
                <h2>Error: {error}</h2>
            </div>
        );

    return (
        <div className="h-screen flex flex-col items-center justify-start p-6 bg-gray-50 dark:bg-gray-900">
            <div className="flex justify-between items-center w-full max-w-3xl mb-2">
                <h2 className="text-2xl font-bold dark:text-white">Welcome to the Registration Page</h2>
                {/* Refresh Button */}
                <button
                    onClick={handleRefreshClick}
                    className="ml-4 p-2 bg-primary text-white rounded hover:bg-primary-dark transition-all duration-300"
                    aria-label="Refresh"
                    disabled={isRefetching} // Disable button during refetch
                >
                    {isRefetching ? 'Refreshing...' : 'Refresh'}
                </button>
            </div>
            <p className="text-lg text-gray-600 dark:text-gray-300 text-start mb-2">Please complete these steps for us to serve you better</p>
            <div className="w-full max-w-3xl">
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

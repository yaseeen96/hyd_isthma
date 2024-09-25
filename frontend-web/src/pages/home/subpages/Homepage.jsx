import React, { useState, useEffect } from 'react';
import { useQuery } from 'react-query';
import { useNavigate } from 'react-router-dom';
import ActionCard from '../../../components/common/actionCard';
import HomeLayout from '../layout/Homelayout';
import LoadingTileCard from '../components/loadingTileCard';
import { localStorageConstant } from '../../../utils/constants/localStorageConstants';
import { ROUTES } from '../../../router/routes';
import { isUserLoggedIn } from '../../../services/check_token_validity_service';
import LoadingComponent from '../../../components/common/loadingComponent';

import { RiProfileFill } from 'react-icons/ri';
import IjtemaBanner from '../components/Banner';
import TileCard from '../components/tileCard';
import { FiList } from 'react-icons/fi';

const HomePage = () => {
    const [isRefetching, setIsRefetching] = useState(false); // State to manage refetch indicator
    const navigate = useNavigate();

    // Fetch user details with react-query
    const { isLoading, isError, data, error, refetch } = useQuery('userDetails', isUserLoggedIn, {
        onSettled: () => setIsRefetching(false),
        refetchOnWindowFocus: true,
        refetchOnMount: true,
        staleTime: 0,
    });

    useEffect(() => {
        setIsRefetching(true);
        refetch().then(() => {
            if (data?.isLoggedIn) {
                const { user } = data;
                localStorage.setItem(localStorageConstant.arrivalConfirmed, user.registration.confirm_arrival);
                localStorage.setItem(localStorageConstant.arrivalDetails, user.registration.arrival_dtls);
                localStorage.setItem(localStorageConstant.familyDetails, user.registration.family_dtls);
                localStorage.setItem(localStorageConstant.financialDetails, user.registration.financial_dtls);
            }
        });
    }, [refetch, data]);

    // Calculate progress based on localStorage values
    const arrivalConfirmed = localStorage.getItem(localStorageConstant.arrivalConfirmed);
    const familyDetails = localStorage.getItem(localStorageConstant.familyDetails);
    const financialDetails = localStorage.getItem(localStorageConstant.financialDetails);
    const arrivalDetails = localStorage.getItem(localStorageConstant.arrivalDetails);

    const completedSteps = [arrivalConfirmed === '1', familyDetails === '1', financialDetails === '1', arrivalDetails === '1'].filter(Boolean).length;
    const progress = (completedSteps / 4) * 100; // Percentage of completion

    const onRegisterIjtema = () => {
        navigate(ROUTES.register);
    };
    const onTimelineSelect = () => {
        navigate(ROUTES.timeline);
    };

    if (isLoading || isRefetching) {
        return <LoadingComponent />;
    }

    if (isError) {
        return (
            <div className="flex flex-col justify-center items-center h-screen">
                <h3>An Error Occurred. Please come back later</h3>
                <h2>Error: {error.message}</h2>
            </div>
        );
    }

    return (
        <HomeLayout>
            <IjtemaBanner />
            <ActionCard
                message={progress === 100 ? 'Thank you. Your registration is 100% complete' : 'Your registration is not yet completed, click below & complete all steps'}
                buttonText={progress === 100 ? `Program Details` : ' Register now'}
                onButtonClick={onRegisterIjtema}
                progress={progress}
            />

            <div className="my-6 grid grid-cols-2 w-full h-32 gap-4 animate-slide-in">
                <TileCard
                    icon={<RiProfileFill size={32} />}
                    title={'Register'}
                    onClick={onRegisterIjtema}
                    // percentage={progress} // Pass calculated progress
                />
                <TileCard icon={<FiList size={32} />} title={'Program Details'} onClick={onTimelineSelect} />
            </div>
        </HomeLayout>
    );
};

export default HomePage;

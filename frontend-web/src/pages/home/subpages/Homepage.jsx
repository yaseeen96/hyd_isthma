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
import { FiList, FiMap } from 'react-icons/fi';
import StickyNavBar from '../components/navBar';
import BottomBar from '../components/bottomBar';
import TopAppBar from '../components/topAppbar';
import { MdFeedback, MdNotifications, MdQuestionAnswer } from 'react-icons/md';
import { BiLogoWhatsapp, BiSupport } from 'react-icons/bi';
import FeedbackModal from '../components/feedbackModal';
import BigCard from '../components/bigCard';
import { FaMosque } from 'react-icons/fa';
import AnimatedCard from '../components/animatedCard';
import BottomSheetModal from '../../../components/common/bottomModalSheet';

const HomePage = () => {
    const [isRefetching, setIsRefetching] = useState(false);
    const navigate = useNavigate();
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [isBottomSheetOpen, setIsBottomSheetOpen] = useState(false); // New state for bottom sheet

    const handleFeedbackSubmit = (feedback) => {
        console.log('Feedback submitted:', feedback);
        setIsModalOpen(false);
    };

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

    const arrivalConfirmed = localStorage.getItem(localStorageConstant.arrivalConfirmed);
    const familyDetails = localStorage.getItem(localStorageConstant.familyDetails);
    const financialDetails = localStorage.getItem(localStorageConstant.financialDetails);
    const arrivalDetails = localStorage.getItem(localStorageConstant.arrivalDetails);

    const completedSteps = [arrivalConfirmed === '1', familyDetails === '1', financialDetails === '1', arrivalDetails === '1'].filter(Boolean).length;
    const progress = (completedSteps / 4) * 100;

    const onRegisterIjtema = () => {
        navigate(ROUTES.register);
    };
    const onTimelineSelect = () => {
        navigate(ROUTES.timeline);
    };
    const onMapsSelect = () => {
        navigate(ROUTES.maps);
    };
    const onNotificationsSelect = () => {
        navigate(ROUTES.notifications);
    };
    const onCardPress = () => {
        setIsBottomSheetOpen(true); // Open the bottom sheet modal
    };
    const onSupportSelect = () => {
        navigate(ROUTES.support);
    };
    const onPrayerSelect = () => {
        navigate(ROUTES.prayer);
    };
    const onFaqSelect = () => {
        navigate(ROUTES.faq);
    };
    const openWhatsApp = () => {
        window.open('https://api.whatsapp.com/send?phone=917290010194', '_blank', 'noopener,noreferrer');
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
        <div className="bg-white w-screen">
            <TopAppBar
                title={'Home'}
                onLogout={() => {
                    localStorage.removeItem('token');
                    window.location.reload();
                }}
            />
            <IjtemaBanner />
            {/* Full-width AnimatedCard positioned at the top */}
            <div className="px-4 mt-6 w-full">
                <AnimatedCard
                    isCentered={true}
                    title={'My Card'}
                    className={'w-full h-[150px] p-4 bg-indigo-300'}
                    icon={<RiProfileFill size={50} />}
                    textClassName={'text-2xl font-bold'}
                    onSelect={onCardPress}
                />
            </div>

            <div className="mb-14 mt-2 p-4 grid grid-cols-2 w-full gap-4 animate-slide-in">
                <BigCard
                    isCentered={true}
                    title={'Registration\nDetails'}
                    className={'h-[250px] bg-purple-200'}
                    icon={<RiProfileFill size={50} />}
                    textClassName={'text-2xl font-bold'}
                    onSelect={onRegisterIjtema}
                />
                <div className="flex flex-col">
                    <BigCard
                        title={'Program Details'}
                        className={'h-[115px] bg-yellow-50'}
                        icon={<FiList size={32} />}
                        isCentered={true}
                        textClassName={'text-center font-bold'}
                        onSelect={onTimelineSelect}
                    />
                    <div className="h-[20px]"></div>
                    <BigCard
                        title={'Ijtema Gah \n Map'}
                        className={'h-[115px] bg-gray-200'}
                        icon={<FiMap size={32} />}
                        isCentered={true}
                        textClassName={'text-center font-bold'}
                        onSelect={onMapsSelect}
                    />
                </div>
                <BigCard
                    title={'Notifications'}
                    className={'h-[115px] bg-orange-100'}
                    icon={<MdNotifications size={32} />}
                    isCentered={true}
                    textClassName={'text-center font-bold'}
                    onSelect={onNotificationsSelect}
                />
                <BigCard title={'Call/Email'} className={'h-[115px] bg-blue-100'} icon={<BiSupport size={32} />} isCentered={true} textClassName={'text-center font-bold'} onSelect={onSupportSelect} />
                <BigCard
                    title={'Feedback/\nQuestions'}
                    className={'h-[115px] bg-red-200'}
                    icon={<MdFeedback size={32} />}
                    isCentered={true}
                    textClassName={'text-center font-bold'}
                    onSelect={() => setIsModalOpen(true)}
                />
                <BigCard
                    isDisabled={false}
                    title={'Need help?'}
                    className={'h-[115px] bg-green-200'}
                    icon={<BiLogoWhatsapp size={32} />}
                    isCentered={true}
                    textClassName={'text-center font-bold'}
                    onSelect={openWhatsApp}
                />
                <BigCard title={'Prayer Times'} className={'h-[115px] bg-cyan-100'} icon={<FaMosque size={32} />} isCentered={true} textClassName={'text-center font-bold'} onSelect={onPrayerSelect} />
                <BigCard
                    title={'Announcements/\nFAQs'}
                    className={'h-[115px] bg-Fuchsia-100'}
                    icon={<MdQuestionAnswer size={32} />}
                    isCentered={true}
                    textClassName={'text-center font-bold'}
                    onSelect={onFaqSelect}
                />

                <FeedbackModal isOpen={isModalOpen} onClose={() => setIsModalOpen(false)} onSubmit={handleFeedbackSubmit} />
                <BottomSheetModal response={data} isOpen={isBottomSheetOpen} onClose={() => setIsBottomSheetOpen(false)} />
            </div>
        </div>
    );
};

export default HomePage;

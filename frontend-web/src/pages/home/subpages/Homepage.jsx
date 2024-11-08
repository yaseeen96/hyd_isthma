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

const HomePage = () => {
    const [isRefetching, setIsRefetching] = useState(false); // State to manage refetch indicator
    const navigate = useNavigate();
    const [isModalOpen, setIsModalOpen] = useState(false);

    const handleFeedbackSubmit = (feedback) => {
        console.log('Feedback submitted:', feedback);
        setIsModalOpen(false);
    };

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
    const onMapsSelect = () => {
        navigate(ROUTES.maps);
    };
    const onNotificationsSelect = () => {
        navigate(ROUTES.notifications);
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
        window.open(
            'https://api.whatsapp.com/send?phone=917290010194&text=Assalamualaikum,%0A%0AI’m%20experiencing%20an%20issue%20with%20JIH%20Ijtema%202024.%0ACould%20someone%20assist%20me?%0A%0AIssue%20details:',
            '_blank',
            'noopener,noreferrer'
        );
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
        // <HomeLayout>
        <div className="bg-purple-50 min-h-screen">
            <TopAppBar
                title={'Home'}
                onLogout={() => {
                    localStorage.removeItem('token');
                    window.location.reload();
                }}
            />
            <IjtemaBanner />
            {/* <ActionCard
                message={progress === 100 ? 'Thank you. Your registration is 100% complete' : 'Your registration is not yet completed, click below & complete all steps'}
                buttonText={progress === 100 ? `Program Details` : ' Register now'}
                onButtonClick={progress === 100 ? onTimelineSelect : onRegisterIjtema}
                progress={progress}
            /> */}

            <div className=" mb-14 mt-2 p-4 grid grid-cols-2 w-full gap-4 animate-slide-in">
                <BigCard
                    isCentered={true}
                    title={'Registration Details'}
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
                        title={'Ijtema Gah Map'}
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
                    title={'Feedback/Question'}
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
                    title={'Announcements/FAQs'}
                    className={'h-[115px] bg-Fuchsia-100'}
                    icon={<MdQuestionAnswer size={32} />}
                    isCentered={true}
                    textClassName={'text-center font-bold'}
                    onSelect={onFaqSelect}
                />

                {/* <TileCard
                    icon={<RiProfileFill size={32} />}
                    title={'Register'}
                    onClick={onRegisterIjtema}
                    className={'bg-yellow-100'}
                    // percentage={progress} // Pass calculated progress
                />
                <TileCard icon={<FiList size={32} />} title={'Program Details'} onClick={onTimelineSelect} />
                <TileCard icon={<FiMap size={32} />} title={'Event Copy'} onClick={onMapsSelect} />
                <TileCard icon={<MdNotifications size={32} />} title={'Notifications'} onClick={onNotificationsSelect} />
                <TileCard icon={<BiSupport size={32} />} title={'Support'} onClick={onSupportSelect} />
                <TileCard icon={<MdFeedback size={32} />} title={'Feedback'} onClick={() => setIsModalOpen(true)} />
                <a
                    href="https://api.whatsapp.com/send?phone=917290010194&text=Assalamualaikum,%0A%0AI’m%20experiencing%20an%20issue%20with%20JIH%20Ijtema%202024.%0ACould%20someone%20assist%20me?%0A%0AIssue%20details:"
                    target="_blank"
                    rel="noopener noreferrer"
                    // className="fixed bottom-20 right-6 bg-green-500 text-white p-4 rounded-full shadow-lg hover:bg-green-600 transition duration-200 flex items-center justify-center"
                >
                    <TileCard icon={<BiLogoWhatsapp size={32} />} title={'Whatsapp Us'} onClick={() => {}} />
                </a>
                */}
                <FeedbackModal isOpen={isModalOpen} onClose={() => setIsModalOpen(false)} onSubmit={handleFeedbackSubmit} />
            </div>
            {/* <BottomBar /> */}
        </div>
        // </HomeLayout>
    );
};

export default HomePage;

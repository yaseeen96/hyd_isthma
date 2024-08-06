import ActionCard from '../../../components/common/actionCard';
import HomeLayout from '../layout/Homelayout';
import { useNavigate } from 'react-router-dom';
import { RiProfileFill } from 'react-icons/ri';

import LoadingTileCard from '../components/loadingTileCard';
import { localStorageConstant } from '../../../utils/constants/localStorageConstants';
import { ROUTES } from '../../../router/routes';
import useAsyncEffect from 'use-async-effect';
import { useLoading } from '../../../utils/hooks/useLoading';
import { isUserLoggedIn } from '../../../services/check_token_validity_service';
import LoadingComponent from '../../../components/common/loadingComponent';
import TileCard from '../components/tileCard';
import { MdNotifications } from 'react-icons/md';

const HomePage = () => {
    const { loading, setLoading } = useLoading();
    const navigate = useNavigate();
    const onRegisterIjtema = () => {
        navigate(ROUTES.register);
    };

    useAsyncEffect(async () => {
        setLoading(true);
        const { user, isLoggedIn } = isUserLoggedIn();
        if (isLoggedIn) {
            localStorage.setItem(localStorageConstant.arrivalConfirmed, user.registration.confirm_arrival);
            localStorage.setItem(localStorageConstant.arrivalDetails, user.registration.arrival_dtls);
            localStorage.setItem(localStorageConstant.familyDetails, user.registration.family_dtls);
            localStorage.setItem(localStorageConstant.financialDetails, user.registration.financial_dtls);
        }
        setLoading(false);
    }, []);

    // Retrieve values from localStorage
    const arrivalConfirmed = localStorage.getItem(localStorageConstant.arrivalConfirmed);
    const familyDetails = localStorage.getItem(localStorageConstant.familyDetails);
    const financialDetails = localStorage.getItem(localStorageConstant.financialDetails);
    const arrivalDetails = localStorage.getItem(localStorageConstant.arrivalDetails);

    // Calculate progress
    const completedSteps = [arrivalConfirmed === '1', familyDetails === '1', financialDetails === '1', arrivalDetails === '1'].filter(Boolean).length;

    const progress = (completedSteps / 4) * 100; // Percentage of completion

    if (loading) {
        return <LoadingComponent />;
    }
    return (
        <HomeLayout>
            <ActionCard
                message={progress === 100 ? 'Thank you. Your registration is 100% complete' : 'Your registration is not yet completed, click below & complete all steps'}
                buttonText={progress === 100 ? `Registration completed (View/Edit)` : ' Register now'}
                onButtonClick={onRegisterIjtema}
            />

            <div className="mt-6 grid grid-cols-2 w-full h-32 gap-4 animate-slide-in">
                <LoadingTileCard
                    icon={<RiProfileFill size={32} />}
                    title={'Registration Progress'}
                    onClick={onRegisterIjtema}
                    percentage={progress} // Pass calculated progress
                />
                <TileCard
                    icon={<MdNotifications size={32} />}
                    title={'Notification Testing'}
                    onClick={() => {
                        navigate(ROUTES.notificationDetails);
                    }}
                />
            </div>
            {/* 
            <TileButton
                title={arrivalConfirmed === '1' ? 'Registered Successfully' : 'Register for Ijtema'}
                isCompleted={arrivalConfirmed === '1'}
                onClick={onRegisterIjtema}
                icon={<MdEventNote color="black" size={30} />}
            /> */}
        </HomeLayout>
    );
};

export default HomePage;

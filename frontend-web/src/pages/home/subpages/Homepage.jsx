import ActionCard from '../../../components/common/actionCard';
import HomeLayout from '../layout/Homelayout';
import { useNavigate } from 'react-router-dom';
import { RiAlertFill, RiProfileFill } from 'react-icons/ri';
import TileCard from '../components/tileCard';
import LoadingTileCard from '../components/loadingTileCard';
import { localStorageConstant } from '../../../utils/constants/localStorageConstants';
import { ROUTES } from '../../../router/routes';

const HomePage = () => {
    const navigate = useNavigate();
    const onRegisterIjtema = () => {
        navigate(ROUTES.register);
    };

    // Retrieve values from localStorage
    const arrivalConfirmed = localStorage.getItem(localStorageConstant.arrivalConfirmed);
    const familyDetails = localStorage.getItem(localStorageConstant.familyDetails);
    const financialDetails = localStorage.getItem(localStorageConstant.financialDetails);
    const arrivalDetails = localStorage.getItem(localStorageConstant.arrivalDetails);

    // Calculate progress
    const completedSteps = [arrivalConfirmed === '1', familyDetails === '1', financialDetails === '1', arrivalDetails === '1'].filter(Boolean).length;

    const progress = (completedSteps / 4) * 100; // Percentage of completion

    return (
        <HomeLayout>
            <ActionCard
                message={progress === 100 ? 'Thank you.Your registration is 100% complete' : 'Your registration is not yet completed, click below & complete all steps'}
                buttonText={'Register now'}
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
                    icon={<RiAlertFill size={32} />}
                    title={'Other Card'}
                    onClick={() => {
                        console.log('clicked');
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

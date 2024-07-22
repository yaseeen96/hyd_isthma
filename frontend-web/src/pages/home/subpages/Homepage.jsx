import ActionCard from '../../../components/common/actionCard';
import TileButton from '../components/tileButton';
import HomeLayout from '../layout/Homelayout';
import { MdEventNote } from 'react-icons/md';
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
    const isArrivalConfirmed = localStorage.getItem(localStorageConstant.arrivalConfirmed);
    return (
        <HomeLayout>
            <ActionCard message={'Have you completed your registration yet?'} buttonText={'Register now'} onButtonClick={onRegisterIjtema} />

            <div className="mt-6 grid grid-cols-2 w-full h-32 gap-4 animate-slide-in">
                <LoadingTileCard icon={<RiProfileFill size={32} />} title={'Registration'} onClick={onRegisterIjtema} percentage={30} />
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
                title={isArrivalConfirmed == '1' ? 'Registered Successfully' : 'Register for Ijtema'}
                isCompleted={isArrivalConfirmed == '1'}
                onClick={onRegisterIjtema}
                icon={<MdEventNote color="black" size={30} />}
            /> */}
        </HomeLayout>
    );
};

export default HomePage;

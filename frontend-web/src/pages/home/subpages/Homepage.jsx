import TileButton from '../components/tileButton';
import HomeLayout from '../layout/Homelayout';
import { MdEventNote } from 'react-icons/md';
import { useNavigate } from 'react-router-dom';

const HomePage = () => {
    const navigate = useNavigate();
    const onRegisterIjtema = () => {
        navigate('/home/register');
    };
    const isArrivalConfirmed = localStorage.getItem('arrivalConfirmed');
    return (
        <HomeLayout>
            <div className="mb-4">
                <h1 className=" text-2xl text-black dark:text-white font-bold">
                    Welcome <br /> <span className=" text-primary">{localStorage.getItem('name')}</span>
                </h1>
            </div>
            <TileButton
                title={isArrivalConfirmed == '1' ? 'Registered Successfully' : 'Register for Ijtema'}
                isCompleted={isArrivalConfirmed == '1'}
                onClick={onRegisterIjtema}
                icon={<MdEventNote color="black" size={30} />}
            />
        </HomeLayout>
    );
};

export default HomePage;

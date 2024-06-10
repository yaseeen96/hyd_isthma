import TileButton from '../components/tileButton';
import HomeLayout from '../layout/Homelayout';
import { MdEventNote } from 'react-icons/md';
import { useNavigate } from 'react-router-dom';

const HomePage = () => {
    const navigate = useNavigate();
    const onRegisterIjtema = () => {
        console.log('Register for Ijtema');
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
                title={isArrivalConfirmed ? 'Registered Successfully' : 'Register for Ijtema'}
                isCompleted={isArrivalConfirmed}
                onClick={isArrivalConfirmed ? null : onRegisterIjtema}
                icon={<MdEventNote color="black" size={30} />}
            />
        </HomeLayout>
    );
};

export default HomePage;

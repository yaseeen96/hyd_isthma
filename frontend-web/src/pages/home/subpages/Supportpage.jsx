import { useNavigate } from 'react-router-dom';
import HomeLayout from '../layout/Homelayout';

const SupportPage = () => {
    const navigate = useNavigate();
    return (
        <HomeLayout>
            <h1>Support Page</h1>
        </HomeLayout>
    );
};

export default SupportPage;

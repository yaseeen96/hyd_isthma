import { ToastContainer } from 'react-toastify';
import { useDarkMode } from './utils/hooks/useDarkMode';
import 'react-toastify/dist/ReactToastify.css';
import { useNavigate } from 'react-router-dom';
import useAsyncEffect from 'use-async-effect';
import { isUserLoggedIn } from './services/check_token_validity_service';
import { useLocation } from 'react-router-dom';
function App({ children }) {
    const location = useLocation();
    const navigate = useNavigate();
    const isDarkMode = useDarkMode();
    useAsyncEffect(async () => {
        const { user, isLoggedIn } = await isUserLoggedIn();
        if (isLoggedIn) {
            localStorage.setItem('name', user.name);
            localStorage.setItem('arrivalConfirmed', user.confirm_arrival);
            if (location.pathname == '/') {
                navigate('/home');
            }
        } else {
            navigate('/login');
        }
    }, []);
    return (
        <>
            {children}
            <ToastContainer
                position="top-center"
                autoClose={3000}
                hideProgressBar={false}
                newestOnTop={false}
                closeOnClick
                rtl={false}
                pauseOnFocusLoss
                draggable
                pauseOnHover
                theme={isDarkMode ? 'dark' : 'light'}
            />
        </>
    );
}

export default App;

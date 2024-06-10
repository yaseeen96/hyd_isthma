import { ToastContainer } from 'react-toastify';
import { useDarkMode } from './utils/hooks/useDarkMode';
import 'react-toastify/dist/ReactToastify.css';
import { useNavigate } from 'react-router-dom';
import useAsyncEffect from 'use-async-effect';
import { isUserLoggedIn } from './services/check_token_validity_service';
function App({ children }) {
    const navigate = useNavigate();
    const isDarkMode = useDarkMode();
    useAsyncEffect(async () => {
        const { user, isLoggedIn } = await isUserLoggedIn();
        if (isLoggedIn) {
            localStorage.setItem('name', user.name);
            localStorage.setItem('arrivalConfirmed', user.confirm_arrival);
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

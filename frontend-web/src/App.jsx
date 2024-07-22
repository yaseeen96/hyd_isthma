import React, { useEffect } from 'react';
import { ToastContainer } from 'react-toastify';
import { useDarkMode } from './utils/hooks/useDarkMode';
import 'react-toastify/dist/ReactToastify.css';
import { useNavigate } from 'react-router-dom';
import useAsyncEffect from 'use-async-effect';
import { isUserLoggedIn } from './services/check_token_validity_service';
import { useLocation } from 'react-router-dom';
import { RecoilRoot, useRecoilState, useSetRecoilState } from 'recoil';
import { analyticsState } from './store/atoms/analyticsAtom';

// Import the functions you need from the SDKs you need
import { initializeApp } from 'firebase/app';
import { getAnalytics } from 'firebase/analytics';
import { useLoading } from './utils/hooks/useLoading';
import LoadingComponent from './components/common/loadingComponent';
import { localStorageConstant } from './utils/constants/localStorageConstants';
import { ROUTES } from './router/routes';

// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
    storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
    messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
    appId: import.meta.env.VITE_FIREBASE_APP_ID,
    measurementId: import.meta.env.VITE_FIREBASE_MEASUREMENT_ID,
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);

function App({ children }) {
    const location = useLocation();
    const navigate = useNavigate();
    const isDarkMode = useDarkMode();
    const setAnalyticsInstance = useSetRecoilState(analyticsState);
    const { loading, setLoading } = useLoading();

    useEffect(() => {
        setAnalyticsInstance(analytics);
    }, []);

    useAsyncEffect(async () => {
        setLoading(true);
        const { user, isLoggedIn } = await isUserLoggedIn();
        if (isLoggedIn) {
            localStorage.setItem(localStorageConstant.name, user.name);
            localStorage.setItem(localStorageConstant.arrivalConfirmed, user.registration.confirm_arrival);
            localStorage.setItem(localStorageConstant.arrivalDetails, user.registration.arrival_dtls);
            localStorage.setItem(localStorageConstant.familyDetails, user.registration.family_dtls);
            localStorage.setItem(localStorageConstant.financialDetails, user.registration.financial_dtls);

            if (location.pathname === '/') {
                navigate(ROUTES.home, { replace: true });
            }
        } else {
            navigate(ROUTES.login, { replace: true });
        }
        setLoading(false);
    }, []);

    return (
        <>
            {loading ? <LoadingComponent /> : children}
            <ToastContainer
                position="bottom-center"
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

function Root({ children }) {
    return (
        <RecoilRoot>
            <App>{children}</App>
        </RecoilRoot>
    );
}

export default Root;

import { lazy } from 'react';
import HomeWrapper from '../pages/home/HomeWrapper';

const ArrivalRegistrationPage = lazy(() => import('../pages/registration/subpages/arrivalRegistrationPage'));
const Otp = lazy(() => import('../pages/Authentication/Otp'));

const Login = lazy(() => import('../pages/Authentication/Login'));
const RegisterPage = lazy(() => import('../pages/registration/registerPage'));

const routes = [
    {
        path: '/',
        layout: 'blank',
    },
    // Authentication
    {
        path: '/login',
        element: <Login />,
        layout: 'blank',
    },
    {
        path: '/verifyOtp',
        element: <Otp />,
        layout: 'blank',
    },
    {
        path: '/home',
        element: <HomeWrapper />,
        layout: 'blank',
    },
    {
        path: '/home/register',
        element: <RegisterPage />,
        layout: 'blank',
    },
    {
        path: '/home/register/arrival',
        element: <ArrivalRegistrationPage />,
        layout: 'blank',
    },
];

export { routes };

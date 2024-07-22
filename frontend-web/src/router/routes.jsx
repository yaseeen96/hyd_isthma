import { lazy } from 'react';
import HomeWrapper from '../pages/home/HomeWrapper';
import FamilyRegistrationPage from '../pages/registration/subpages/familyRegistration';

const ArrivalRegistrationPage = lazy(() => import('../pages/registration/subpages/arrivalRegistrationPage'));
const Otp = lazy(() => import('../pages/Authentication/Otp'));

const Login = lazy(() => import('../pages/Authentication/Login'));
const RegisterPage = lazy(() => import('../pages/registration/registerPage'));

export const ROUTES = {
    login: '/login',
    verifyOtp: '/verifyOtp',
    home: '/home',
    register: '/home/register',
    rsvpRegistration: '/home/register/arrival',
    familyRegistration: '/home/register/family',
};

const routes = [
    {
        path: '/',
        layout: 'blank',
    },
    // Authentication
    {
        path: ROUTES.login,
        element: <Login />,
        layout: 'blank',
    },
    {
        path: ROUTES.verifyOtp,
        element: <Otp />,
        layout: 'blank',
    },
    {
        path: ROUTES.home,
        element: <HomeWrapper />,
        layout: 'blank',
    },
    {
        path: ROUTES.register,
        element: <RegisterPage />,
        layout: 'blank',
    },
    {
        path: ROUTES.rsvpRegistration,
        element: <ArrivalRegistrationPage />,
        layout: 'blank',
    },
    {
        path: ROUTES.familyRegistration,
        element: <FamilyRegistrationPage />,
        layout: 'blank',
    },
];

export { routes };

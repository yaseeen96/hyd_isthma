import { lazy } from 'react';
import HomeWrapper from '../pages/home/HomeWrapper';

const RegisterPage = lazy(() => import('../pages/registration/registerPage'));
const Otp = lazy(() => import('../pages/Authentication/Otp'));

const Login = lazy(() => import('../pages/Authentication/Login'));
const Index = lazy(() => import('../pages/Index'));

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
];

export { routes };

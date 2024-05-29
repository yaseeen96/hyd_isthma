import { lazy } from 'react';
import Otp from '../pages/Authentication/Otp';

const Login = lazy(() => import('../pages/Authentication/Login'));
const Index = lazy(() => import('../pages/Index'));

const routes = [
    // dashboard
    {
        path: '/index',
        element: <Index />,
    },
    // Authentication
    {
        // path: '/auth/login',
        path: '/', // temp url
        element: <Login />,
        layout: 'blank',
    },
    {
        path: '/auth/verifyOtp',
        element: <Otp />,
        layout: 'blank',
    },
];

export { routes };

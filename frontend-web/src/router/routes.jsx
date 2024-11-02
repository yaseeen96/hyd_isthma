import { lazy } from 'react';
import HomeWrapper from '../pages/home/HomeWrapper';
import FamilyRegistrationPage from '../pages/registration/subpages/familyRegistration';
import FinancialRegistration from '../pages/registration/subpages/financialRegistration';
import AdditionalDetailsRegistration from '../pages/registration/subpages/additionalDetailsRegistration';
import NotificationDetailPage from '../pages/Notification/NotificationDetailPage';
import Timeline from '../pages/programs/pages/timeline';
import PDFContent from '../pages/programs/utils/pdfContent';
import MapComponent from '../pages/maps/maps';
import Otp from '../pages/Authentication/Otp';
import Login from '../pages/Authentication/Login';
import RegisterPage from '../pages/registration/registerPage';
import SupportPage from '../pages/home/subpages/Supportpage';
import NotificationList from '../pages/home/subpages/NotificationPage';
import PrayerTimes from '../pages/prayer/prayerTimes';

const ArrivalRegistrationPage = lazy(() => import('../pages/registration/subpages/arrivalRegistrationPage'));
// const Otp = lazy(() => import('../pages/authentication/Otp'));

// const Login = lazy(() => import('../pages/authentication/Login'));
// const RegisterPage = lazy(() => import('../pages/registration/registerPage'));

export const ROUTES = {
    login: '/login',
    verifyOtp: '/verifyOtp',
    home: '/home',
    register: '/home/register',
    rsvpRegistration: '/home/register/arrival',
    familyRegistration: '/home/register/family',
    financialRegistration: '/home/register/finance',
    additionalRegistration: '/home/register/additional',
    notificationDetails: '/notification',
    timeline: '/timeline',
    maps: '/map',
    notifications: '/notifications',
    support: '/support',
    prayer: '/prayer',
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

    {
        path: ROUTES.financialRegistration,
        element: <FinancialRegistration />,
        layout: 'blank',
    },
    {
        path: ROUTES.additionalRegistration,
        element: <AdditionalDetailsRegistration />,
        layout: 'blank',
    },
    {
        path: ROUTES.notificationDetails,
        element: <NotificationDetailPage />,
        layout: 'blank',
    },
    {
        path: ROUTES.timeline,
        element: <Timeline />,
        layout: 'blank',
    },
    {
        path: ROUTES.maps,
        element: <MapComponent />,
        layout: 'blank',
    },
    {
        path: ROUTES.notifications,
        element: <NotificationList />,
        layout: 'blank',
    },
    {
        path: ROUTES.support,
        element: <SupportPage />,
        layout: 'blank',
    },
    {
        path: ROUTES.prayer,
        element: <PrayerTimes />,
        layout: 'blank',
    },
];

export { routes };

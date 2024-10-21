import { lazy } from 'react';
import HomeWrapper from '../pages/home/HomeWrapper';
import FamilyRegistrationPage from '../pages/registration/subpages/familyRegistration';
import FinancialRegistration from '../pages/registration/subpages/financialRegistration';
import AdditionalDetailsRegistration from '../pages/registration/subpages/additionalDetailsRegistration';
import NotificationDetailPage from '../pages/Notification/NotificationDetailPage';
import Timeline from '../pages/programs/pages/timeline';
import PDFContent from '../pages/programs/utils/pdfContent';

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
    financialRegistration: '/home/register/finance',
    additionalRegistration: '/home/register/additional',
    notificationDetails: '/notification',
    timeline: '/timeline',
    pdf: "/pdf"
};

const routes = [
    {
        path: '/',
        layout: 'blank',
    },
    {
        path: ROUTES.pdf,
        element: <PDFContent data={
             [
                {
                    "id": 1,
                    "theme_name": "Parallel Test Session",
                    "session_convener": "Abdul Jabbar Siddiqi",
                    "theme_type": "Parallel",
                    "hall_name": "Hall 1",
                    "convener_bio": "Nazim Ijtema, Secretary JIH Markaz, Ex SIO National President",
                    "datetime": "2024-11-17 10:00 AM - 11:00 AM",
                    "status": "Yet to Start",
                    "programs": [
                        {
                            "id": 1,
                            "name": "Urdu Topic",
                            "datetime": "2024-11-17 10:00 AM - 11:00 AM",
                            "speaker_name": "Abdul Jabbar Siddiqi",
                            "speaker_bio": "Nazim Ijtema, Secretary JIH Markaz, Ex SIO National President",
                            "speaker_image": "http://admin-ijtema.jihhrd.com//storage/images/speaker_image/83ba1ca5-ca0e-47c6-8f31-c9c9340fd16d.png",
                            "enrolled": true,
                            "status": "Yet to Start",
                            "english": {
                                "topic": "English Topic",
                                "transcript": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
                                "translation": "http://admin-ijtema.jihhrd.com//storage/program_translations/english/1/b60cd855-8a81-4a10-b573-e49a876289db.mp3"
                            },
                            "malyalam": {
                                "topic": "Malyalam Topic",
                                "transcript": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
                                "translation": "http://admin-ijtema.jihhrd.com//storage/program_translations/malyalam/1/6a239172-a73a-45dd-bd4f-2bf0711a7161.mp3"
                            },
                            "bengali": {
                                "topic": "Bengali Topic",
                                "transcript": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
                                "translation": "http://admin-ijtema.jihhrd.com//storage/program_translations/bengali/1/81dc79d4-d641-47bd-9464-3c5b9e69c3ef.mp3"
                            },
                            "tamil": {
                                "topic": "Tamil topic",
                                "transcript": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
                                "translation": "http://admin-ijtema.jihhrd.com//storage/program_translations/tamil/1/8533fd95-496b-414d-84da-92cc9cfc37da.mp3"
                            },
                            "kannada": {
                                "topic": "Kannada",
                                "transcript": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
                                "translation": "http://admin-ijtema.jihhrd.com//storage/program_translations/kannada/1/9dc0dd44-9a72-4441-afc5-316b7122af21.mp3"
                            }
                        }
                    ]
                },
                {
                    "id": 2,
                    "theme_name": "Test introduction session",
                    "session_convener": "Hammad Azam",
                    "theme_type": "Fixed",
                    "hall_name": "Hall 1",
                    "convener_bio": "National President PSF, Technology Team Qayed, Leader, Speaker, Motivator, Singer",
                    "datetime": "2024-11-15 09:30 AM - 12:30 PM",
                    "status": "Yet to Start",
                    "programs": [
                        {
                            "id": 2,
                            "name": "ذاتی تقریر۔",
                            "datetime": "2024-11-15 10:47 AM - 11:47 AM",
                            "speaker_name": "Abdul Momin",
                            "speaker_bio": "Muawain Ameer, Bangalore Jamaat",
                            "speaker_image": "/assets/img/no-image.png",
                            "enrolled": false,
                            "status": "Yet to Start",
                            "english": {
                                "topic": "Personal speech",
                                "transcript": null,
                                "translation": null
                            },
                            "malyalam": {
                                "topic": null,
                                "transcript": null,
                                "translation": null
                            },
                            "bengali": {
                                "topic": null,
                                "transcript": null,
                                "translation": null
                            },
                            "tamil": {
                                "topic": null,
                                "transcript": null,
                                "translation": null
                            },
                            "kannada": {
                                "topic": null,
                                "transcript": null,
                                "translation": null
                            }
                        },
                        {
                            "id": 3,
                            "name": "اجتماع کا تعارف",
                            "datetime": "2024-11-15 09:51 AM - 10:31 AM",
                            "speaker_name": "Hammad Azam",
                            "speaker_bio": "National President PSF, Technology Team Qayed, Leader, Speaker, Motivator, Singer",
                            "speaker_image": "http://admin-ijtema.jihhrd.com//storage/images/speaker_image/65cae5ac-b06d-4dd1-abd4-155ced28c81a.png",
                            "enrolled": false,
                            "status": "Yet to Start",
                            "english": {
                                "topic": "Ijteman introductions",
                                "transcript": null,
                                "translation": null
                            },
                            "malyalam": {
                                "topic": null,
                                "transcript": null,
                                "translation": null
                            },
                            "bengali": {
                                "topic": null,
                                "transcript": null,
                                "translation": null
                            },
                            "tamil": {
                                "topic": null,
                                "transcript": null,
                                "translation": null
                            },
                            "kannada": {
                                "topic": null,
                                "transcript": null,
                                "translation": null
                            }
                        }
                    ]
                },
                {
                    "id": 3,
                    "theme_name": "Test session 1",
                    "session_convener": "Bilal Ahmad",
                    "theme_type": "Fixed",
                    "hall_name": "Hall 4",
                    "convener_bio": "Ameer-e-Muqami, West Bengal",
                    "datetime": "2024-11-15 11:00 AM - 11:10 PM",
                    "status": "Yet to Start",
                    "programs": []
                },
                {
                    "id": 4,
                    "theme_name": "Test theme1",
                    "session_convener": "Abdul Momin",
                    "theme_type": "Fixed",
                    "hall_name": "Hall 3",
                    "convener_bio": "Muawain Ameer, Bangalore Jamaat",
                    "datetime": "2024-11-15 11:00 AM - 11:59 AM",
                    "status": "Yet to Start",
                    "programs": []
                },
                {
                    "id": 5,
                    "theme_name": "Test123",
                    "session_convener": "Sibghat Hussaini",
                    "theme_type": "Fixed",
                    "hall_name": "Hall 4",
                    "convener_bio": "He is nothing",
                    "datetime": "2024-11-16 09:30 AM - 11:30 AM",
                    "status": "Yet to Start",
                    "programs": []
                },
                {
                    "id": 6,
                    "theme_name": "Test456",
                    "session_convener": "Sibghat Hussaini",
                    "theme_type": "Fixed",
                    "hall_name": "Hall 1",
                    "convener_bio": "He is nothing",
                    "datetime": "2024-11-16 09:30 AM - 11:31 AM",
                    "status": "Yet to Start",
                    "programs": []
                }
            ]
        } />,
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
];

export { routes };

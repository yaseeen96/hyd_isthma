import React from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { useQuery } from 'react-query';
import { getSingleNotification } from '../../services/notification_service';
import LoadingComponent from '../../components/common/loadingComponent';
import PDFViewer from './components/pdfViewer';

const NotificationDetailPage = () => {
    const location = useLocation();
    const navigate = useNavigate();
    const params = new URLSearchParams(location.search);
    const id = params.get('id');

    const { data, error, isLoading } = useQuery(['singleNotification', id], getSingleNotification, {
        enabled: !!id, // Only run the query if id is available
    });

    if (!id) {
        return <div className="text-red-500">Error: No notification ID provided</div>;
    }

    if (isLoading) return <LoadingComponent />;
    if (error) return <div className="text-red-500">Error loading notification</div>;
    if (!data) return <div className="h-screen flex flex-col justify-center items-center">No notification data</div>;

    const notification = data.data;
    const pdfUrl = notification.document;

    // Construct the Google Drive embed URL
    const embedUrl = `https://drive.google.com/viewerng/viewer?embedded=true&url=${encodeURIComponent(pdfUrl)}`;

    return (
        <div className="w-full flex flex-col">
            <section className="w-full">
                <img src={notification.image || '/assets/images/cover.jpg'} alt={notification.title} className="w-full" />
            </section>
            <section className="px-8 py-4">
                <button onClick={() => navigate('/home', { replace: true })} className="mb-4 px-4 py-2 bg-blue-500 text-white rounded">
                    Back to Home
                </button>
                <h1 className="text-2xl font-bold">{notification.title}</h1>
                <hr className="my-4" />
                <p className="text-gray-700">{notification.message}</p>
                {notification.document && (
                    <>
                        <h1 className="text-xl my-4 font-bold">File</h1>
                        <PDFViewer embedUrl={embedUrl} />
                    </>
                )}
            </section>
        </div>
    );
};

export default NotificationDetailPage;

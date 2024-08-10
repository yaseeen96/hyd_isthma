// NotificationDetailPage.jsx
import React from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { useQuery } from 'react-query';
import { getSingleNotification } from '../../services/notification_service';
import LoadingComponent from '../../components/common/loadingComponent';

const NotificationDetailPage = () => {
    const location = useLocation();
    const navigate = useNavigate();
    const params = new URLSearchParams(location.search);
    const id = params.get('id');

    console.log('Notification ID:', id); // Log the ID to debug

    const { data, error, isLoading } = useQuery(['singleNotification', id], getSingleNotification, {
        enabled: !!id, // Only run the query if id is available
    });

    if (!id) {
        return <div>Error: No notification ID provided</div>;
    }

    if (isLoading) return <LoadingComponent />;
    if (error) return <div>Error loading notification</div>;
    if (!data) return <div className="h-screen flex flex-col justify-center items-center">No notification data</div>;

    const notification = data.data;
    let videoId = null;
    let videoUrl = null;
    if (notification.youtube_url) {
        videoId = notification.youtube_url.split('v=')[1] || 'LfaMVlDaQ24';
        videoUrl = `https://www.youtube.com/embed/${videoId}`;
    }
    const pdfUrl = notification.document;

    return (
        <div className="w-full flex flex-col">
            {/* cover image */}
            <section className="w-full">
                <img src={notification.image || '/assets/images/cover.jpg'} alt={notification.title} className="w-full" />
            </section>
            {/* body */}
            <section className="px-8 py-4">
                {/* Back to Home button */}
                <button onClick={() => navigate('/home', { replace: true })} className="mb-4 px-4 py-2 bg-primary text-white rounded">
                    Back to Home
                </button>
                <h1 className="text-2xl">{notification.title}</h1>
                <hr className="my-4" />
                <p>{notification.message}</p>
                {/* Video iframe */}
                {notification.youtube_url && (
                    <>
                        <h1 className="text-xl my-4 font-bold">Video</h1>
                        <iframe
                            src={videoUrl}
                            width="100%"
                            height="400"
                            frameBorder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowFullScreen
                            title="Notification Video"
                            className="my-4"
                        ></iframe>
                    </>
                )}
                {/* PDF iframe */}
                {notification.document && (
                    <>
                        <h1 className="text-xl my-4 font-bold">File</h1>
                        <iframe className="my-4" src={pdfUrl} width="100%" height="600" title="Notification PDF"></iframe>
                    </>
                )}
            </section>
        </div>
    );
};

export default NotificationDetailPage;

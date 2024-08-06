import React from 'react';
import { useLocation, useNavigate } from 'react-router-dom';

const NotificationDetailPage = () => {
    const location = useLocation();
    const navigate = useNavigate();
    const params = new URLSearchParams(location.search);
    const id = params.get('id');
    const videoId = 'LfaMVlDaQ24';
    const videoUrl = `https://www.youtube.com/embed/${videoId}`;
    const pdfUrl = 'https://css4.pub/2015/icelandic/dictionary.pdf';

    return (
        <div className="w-full flex flex-col">
            {/* cover image */}
            <section className="w-full">
                <img src={'/assets/images/cover.jpg'} alt="" className="w-full" />
            </section>
            {/* body */}
            <section className="px-8 py-4">
                {/* Back to Home button */}
                <button onClick={() => navigate('/home')} className="mb-4 px-4 py-2 bg-primary text-white rounded">
                    Back to Home
                </button>
                <h1 className="text-2xl">Notification Title</h1>
                <hr className="my-4" />
                <p>
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Obcaecati, eos. Dolores libero repudiandae praesentium a maxime voluptate dolore mollitia consequuntur! Inventore natus
                    unde nobis alias dolor obcaecati consequatur vitae a?
                </p>
                {/* Video iframe */}
                <h1 className="text-xl my-4 font-bold">Video</h1>
                {videoUrl && (
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
                )}
                {/* PDF iframe */}
                <h1 className="text-xl my-4 font-bold">File</h1>
                {pdfUrl && <iframe className="my-4" src={pdfUrl} width="100%" height="600" frameBorder="0" title="Notification PDF"></iframe>}
            </section>
        </div>
    );
};

export default NotificationDetailPage;

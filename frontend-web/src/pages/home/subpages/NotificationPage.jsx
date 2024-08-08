// components/NotificationList.js
import React from 'react';
import { useQuery } from 'react-query';
import { getNotifications } from '../../../services/notification_service';
import SingleNotificationCard from '../components/singleNotificationCard';
import HomeLayout from '../layout/Homelayout';
import LoadingComponent from '../../../components/common/loadingComponent';
import BottomBar from '../components/bottomBar';
import { useNavigate } from 'react-router-dom';
import { ROUTES } from '../../../router/routes';

const NotificationList = () => {
    const { data, error, isLoading } = useQuery('notifications', getNotifications);
    const navigate = useNavigate();

    if (isLoading)
        return (
            <div className="h-screen flex flex-col">
                <LoadingComponent />;
                <BottomBar />
            </div>
        );
    if (error) return <div>Error loading notifications</div>;

    return (
        <HomeLayout>
            <h1 className="text-2xl font-bold my-2 mx-2">My Notifications</h1>

            {data.data.length === 0 ? (
                <div className=" w-full h-[80vh] flex items-center justify-center text-gray-500 my-4">No notifications</div>
            ) : (
                data.data.map((notification) => (
                    <SingleNotificationCard
                        key={notification.id}
                        title={notification.title}
                        subtitle={notification.message}
                        imageUrl={notification.image}
                        time={notification.created_at}
                        onButtonPress={() => {
                            navigate(`${ROUTES.notificationDetails}?id=${notification.id}`);
                        }}
                    />
                ))
            )}
        </HomeLayout>
    );
};

export default NotificationList;

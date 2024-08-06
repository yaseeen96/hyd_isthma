import React from 'react';
import HomeLayout from '../layout/Homelayout';
import SingleNotificationCard from '../components/singleNotificationCard';

const NotificationPage = () => {
    let notifications = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
    return (
        <HomeLayout>
            {notifications.map((value, index) => (
                <SingleNotificationCard
                    title={'Notification Title'}
                    subtitle={`Lorem ipsum dolor sit amet consectetur adipisicing elit. Consectetur ad doloribus laudantium? Enim odio vero illo magnam repellendus quaerat commodi deleniti dicta ad, cupiditate a in quasi autem, accusamus vel.`}
                    imageUrl={'/assets/images/cover.jpg'}
                    time={'2 days ago'}
                />
            ))}
        </HomeLayout>
    );
};

export default NotificationPage;

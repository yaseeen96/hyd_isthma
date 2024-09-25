// components/NotificationList.js
import React from 'react';
import HomeLayout from '../layout/Homelayout';

const PollsPage = () => {
    return (
        <HomeLayout>
            <div className="flex flex-col h-[80vh] justify-center items-center">
                <p>No polls yet</p>
            </div>
        </HomeLayout>
    );
};

export default PollsPage;

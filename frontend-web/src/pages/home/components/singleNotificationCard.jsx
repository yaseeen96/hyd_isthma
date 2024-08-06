import React from 'react';

const SingleNotificationCard = ({ title, subtitle, imageUrl, time, onClick }) => {
    return (
        <div key={12} className="w-full pl-4 border grid grid-cols-12 my-2">
            <div className="flex flex-col col-span-8 py-4 w-full">
                <h3 className="text-lg font-bold">{title}</h3>
                <p className="overflow-hidden whitespace-nowrap text-ellipsis text-md text-gray-700">{subtitle}</p>
                <p className="text-gray-500">{time}</p>
            </div>
            <img src={imageUrl} alt="" className="col-span-4 object-cover h-full" />
        </div>
    );
};

export default SingleNotificationCard;

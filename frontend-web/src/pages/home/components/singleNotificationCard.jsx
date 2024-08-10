import React from 'react';

const SingleNotificationCard = ({ title, subtitle, imageUrl, time, onButtonPress }) => {
    return (
        <button className="text-start" onClick={onButtonPress}>
            <div className="w-full pl-4 border grid grid-cols-12 my-2">
                <div className="flex flex-col col-span-8 py-4 w-full">
                    <h3 className="text-lg font-bold">{title}</h3>
                    <p className="overflow-hidden whitespace-nowrap text-ellipsis text-md text-gray-700">{subtitle}</p>
                    <p className="text-gray-500">{time}</p>
                </div>
                <div className="flex justify-center items-center col-span-4">
                    <img src={imageUrl} alt="" className=" object-cover w-full" />
                </div>
            </div>
        </button>
    );
};

export default SingleNotificationCard;

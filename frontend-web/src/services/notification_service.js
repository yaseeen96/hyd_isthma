import { axiosAuthenticatedClient } from './axios_client';
import { localStorageConstant } from '../utils/constants/localStorageConstants';
export const getNotifications = async () => {
    try {
        const response = await axiosAuthenticatedClient.get('notifications/listNotifications', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem(localStorageConstant.token)}`,
                'Content-Type': 'application/json',
                accept: 'application/json',
            },
        });
        return response.data;
    } catch (error) {
        console.error(error);
        return null;
    }
};

export const getSingleNotification = async ({ queryKey }) => {
    const [_, id] = queryKey;
    console.log('Fetching notification with ID:', id); // Log the ID to debug

    try {
        const response = await axiosAuthenticatedClient.get(`notifications/getNotification/${id}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem(localStorageConstant.token)}`,
                'Content-Type': 'application/json',
                accept: 'application/json',
            },
        });
        return response.data;
    } catch (error) {
        console.error(error);
        throw new Error('Failed to fetch notification');
    }
};

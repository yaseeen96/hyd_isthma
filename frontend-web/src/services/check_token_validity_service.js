import axios from 'axios';
import { localStorageConstant } from '../utils/constants/localStorageConstants';

export const isUserLoggedIn = async () => {
    const token = localStorage.getItem(localStorageConstant.token);
    const fcmToken = localStorage.getItem(localStorageConstant.fcmToken);
    if (!token) {
        return false;
    }
    try {
        const response = await axios.post(
            'https://admin-ijtema.jihhrd.com/api/v1/auth/verifyToken',
            { token: token, push_token: fcmToken },
            {
                headers: {
                    'Content-Type': 'application/json',
                },
            }
        );
        if (response.data.status === 'success') {
            return { user: response.data.data, isLoggedIn: true };
        } else {
            return {
                user: null,
                isLoggedIn: false,
            };
        }
    } catch (error) {
        return { user: null, isLoggedIn: false };
    }
};

import axios from 'axios';

export const isUserLoggedIn = async () => {
    const token = localStorage.getItem('token');
    const fcmToken = localStorage.getItem('fcmtoken');
    if (!token) {
        return false;
    }
    try {
        const response = await axios.post(
            'https://jihapi.kkshan.amlc.in/api/v1/auth/verifyToken',
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

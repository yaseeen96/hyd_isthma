import axios from 'axios';
import { localStorageConstant } from '../utils/constants/localStorageConstants';

export const sendOtpService = async (phone) => {
    try {
        const response = await axios.post(
            'https://admin-ijtema.jihhrd.com/api/v1/auth/login',
            {
                phone: `${phone}`,
            },
            {
                headers: {
                    'Content-Type': 'application/json',
                },
            }
        );

        return response.data;
    } catch (error) {
        console.log(error.response.data);
        console.log(error.response.data.message.phone[0]);
        throw new Error(error.response.data.message.phone[0]);
    }
};

export const verifyOtpService = async (phone, otp) => {
    try {
        const response = await axios.post(
            'https://admin-ijtema.jihhrd.com/api/v1/auth/verifyOtp',
            {
                phone: `${phone}`,
                otp: otp,
            },
            {
                headers: {
                    'Content-Type': 'application/json',
                },
            }
        );

        return response.data;
    } catch (error) {
        // check if response.data.message.otp is an array
        if (error.response.data.message.otp instanceof Array) {
            throw new Error(error.response.data.message.otp[0]);
        }
        throw new Error(error.response.data.message);
    }
};

export const logoutService = async () => {
    try {
        const response = await axios.post('https://admin-ijtema.jihhrd.com/api/v1/logout', null, {
            headers: { Authorization: `Bearer ${localStorage.getItem(localStorageConstant.token)}`, 'Content-Type': 'application/json' },
        });

        if (response.status === 204) {
            localStorage.removeItem(localStorageConstant.token);
            window.location.reload();
            return true;
        } else {
            return false;
        }
    } catch (error) {
        return false;
    }
};

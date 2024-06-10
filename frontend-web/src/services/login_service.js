import axios from 'axios';

export const sendOtpService = async (phone) => {
    try {
        const response = await axios.post('https://jihapi.kkshan.amlc.in/api/v1/auth/login', {
            phone: `${phone}`,
        });

        return response.data;
    } catch (error) {
        throw new Error(error.response.data.message.phone[0]);
    }
};

export const verifyOtpService = async (phone, otp) => {
    try {
        const response = await axios.post('https://jihapi.kkshan.amlc.in/api/v1/auth/verifyOtp', {
            phone: `${phone}`,
            otp: otp,
        });

        return response.data;
    } catch (error) {
        // check if response.data.message.otp is an array
        if (error.response.data.message.otp instanceof Array) {
            throw new Error(error.response.data.message.otp[0]);
        }
        throw new Error(error.response.data.message);
    }
};

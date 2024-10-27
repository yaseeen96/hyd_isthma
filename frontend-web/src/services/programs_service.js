import { localStorageConstant } from '../utils/constants/localStorageConstants';
import { axiosAuthenticatedClient } from './axios_client';

export const getProgramDetails = async (language) => {
    try {
        const response = await axiosAuthenticatedClient.get(`programs/listPrograms?lang=${language}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem(localStorageConstant.token)}`,
                'Content-Type': 'application/json',
                accept: 'application/json',
            },
        });
        return response.data;
    } catch (error) {
        console.error(error);
    }
};

// programs/registerProgram

export const enrollforProgram = async (sessionId) => {
    try {
        const response = await axiosAuthenticatedClient.post(
            'programs/registerSession',
            {
                session_id: sessionId,
            },
            {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem(localStorageConstant.token)}`,
                    'Content-Type': 'application/json',
                    accept: 'application/json',
                },
            }
        );
        return response.data;
    } catch (error) {
        console.error(error);
        return error.response.data;
    }
};

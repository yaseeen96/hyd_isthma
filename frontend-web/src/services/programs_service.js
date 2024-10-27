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

export const submitFeedback = async (feedbackType, title, description, programId) => {
    try {
        // Build the data payload based on conditions
        const data = {
            feedback_type: feedbackType,
            title,
            description,
            ...(feedbackType === 'program' && { program_id: programId }), // Include program_id if feedbackType is 'program'
        };

        const response = await axiosAuthenticatedClient.post('feedback/submitFeedback', data, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem(localStorageConstant.token)}`,
                'Content-Type': 'application/json',
                accept: 'application/json',
            },
        });
        return response.data;
    } catch (error) {
        console.error(error);
        return error.response.data;
    }
};

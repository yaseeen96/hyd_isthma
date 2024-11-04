import { localStorageConstant } from '../utils/constants/localStorageConstants';
import { axiosAuthenticatedClient } from './axios_client';

export const getProgramDetails = async (language) => {
    try {
        // Define the URL conditionally based on the language
        const url = language.toLowerCase() === 'urdu' ? 'programs/listPrograms' : `programs/listPrograms?lang=${language}`;

        const response = await axiosAuthenticatedClient.get(url, {
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

export const getFAQs = async () => {
    try {
        // Define the URL conditionally based on the language
        const url = `programs/listFaqs`;

        const response = await axiosAuthenticatedClient.get(url, {
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

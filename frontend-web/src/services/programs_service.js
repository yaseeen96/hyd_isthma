import { localStorageConstant } from '../utils/constants/localStorageConstants';
import { axiosAuthenticatedClient } from './axios_client';

export const getProgramDetails = async () => {
    try {
        const response = await axiosAuthenticatedClient.get('programs/listPrograms', {
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

export const enrollforProgram = async (programId) => {
    try {
        const response = await axiosAuthenticatedClient.post(
            'programs/registerProgram',
            {
                program_id: programId,
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

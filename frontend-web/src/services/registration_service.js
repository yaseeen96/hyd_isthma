import axios from 'axios';
import { axiosAuthenticatedClient } from './axios_client';
import { toast } from 'react-toastify';
export const confirmRegistrationService = async (data) => {
    try {
        console.log(data);
        console.log(typeof data.confirmArrival);

        const response = await axiosAuthenticatedClient.post('user/register', {
            confirm_arrival: data.confirmArrival,
            reason_for_not_coming: data.confirmArrival === '1' ? null : data.reason_for_not_coming,
            emergency_contact: data.emergency_contact,
            ameer_permission_taken: data.confirmArrival === '1' ? null : data.ameer_permission_taken,
            dob: data.date_of_birth.startDate,
            email: data.email,
        });
        console.log(response.data);
        return response.data.status === 'success';
    } catch (error) {
        console.error(error);
        return false;
    }
};

export const updateFamilyDetails = async (data) => {
    try {
        const response = await axiosAuthenticatedClient.post('/user/familyDetails', data);
        console.log(`success: response: ${response.data}`);
        return true;
    } catch (error) {
        console.error(error);
        return false;
    }
};

export const getUserDetails = async () => {
    try {
        const response = await axiosAuthenticatedClient.get('user/getUserDetails');
        return response.data;
    } catch (error) {
        console.error(error);
        return null;
    }
};

export const getRegistrationDetails = async () => {
    try {
        const response = await axiosAuthenticatedClient.get('user/getUserDetailsTest');
        return response.data;
    } catch (error) {
        console.error(error);
    }
};

import axios from 'axios';
import { axiosAuthenticatedClient } from './axios_client';
export const confirmRegistrationService = async (data) => {
    try {
        console.log(data);
        // log tyoe of confirmArrival
        console.log(typeof data.confirmArrival);

        const response = await axiosAuthenticatedClient.post('user/register', {
            confirm_arrival: data.confirmArrival,
            reason_for_not_coming: data.confirmArrival === '1' ? null : data.reason_for_not_coming,
            emergency_contact: data.emergency_contact,
            ameer_permission_taken: data.confirmArrival === '1' ? null : data.ameer_permission_taken,
            dob: data.date_of_birth.startDate,
            email: data.email,
        });
        return response.data.status === 'success';
    } catch (error) {
        return false;
    }
};

export const getUserDetails = async () => {
    try {
        const response = await axiosAuthenticatedClient.get('user/getUserDetails', {});
        return response.data;
    } catch (error) {
        console.error(error);
        return null;
    }
};

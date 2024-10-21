import axios, { HttpStatusCode } from 'axios';
import { axiosAuthenticatedClient } from './axios_client';
import { localStorageConstant } from '../utils/constants/localStorageConstants';
import { toast } from 'react-toastify';
export const confirmRegistrationService = async (data) => {
    try {
        console.log(data);
        console.log(typeof data.confirmArrival);

        const response = await axiosAuthenticatedClient.post(
            'user/register',
            {
                confirm_arrival: data.confirmArrival,
                reason_for_not_coming: data.confirmArrival === '1' ? null : data.reason_for_not_coming,
                emergency_contact: data.emergency_contact,
                ameer_permission_taken: data.confirmArrival === '1' ? null : data.ameer_permission_taken,
                dob: data.date_of_birth.startDate,
                email: data.email,
            },
            {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem(localStorageConstant.token)}`,
                    'Content-Type': 'application/json',
                    accept: 'application/json',
                },
            }
        );
        console.log(response.data);
        localStorage.setItem(localStorageConstant.arrivalConfirmed, 1);
        return response.data.status === 'success';
    } catch (error) {
        console.error(error);
        return false;
    }
};

export const updateFamilyDetails = async (data) => {
    try {
        const response = await axiosAuthenticatedClient.post('/user/familyDetails', data, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem(localStorageConstant.token)}`,
                'Content-Type': 'application/json',
                accept: 'application/json',
            },
        });
        console.log(`success: response: ${response.data}`);
        localStorage.setItem(localStorageConstant.familyDetails, '1');
        return true;
    } catch (error) {
        console.error(error);
        return false;
    }
};

export const getUserDetails = async () => {
    try {
        const response = await axiosAuthenticatedClient.get('user/getUserDetails', {
            headers: {
                Authorization: `Bearer ${localStorage.getItem(localStorageConstant.token)}`,
                'Content-Type': 'application/json',
                accept: 'application/json',
            },
        });
        return response.data;
    } catch (error) {
        console.error(error);
        return null;
    }
};

export const getRegistrationDetails = async () => {
    try {
        const response = await axiosAuthenticatedClient.get('user/getUserDetailsTest', {
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

export const updateFinancialDetails = async (fees) => {
    try {
        const response = axiosAuthenticatedClient.post(
            'user/financialDetails',
            {
                fees_paid_to_ameer: fees,
            },
            {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem(localStorageConstant.token)}`,
                    'Content-Type': 'application/json',
                    accept: 'application/json',
                },
            }
        );
        localStorage.setItem(localStorageConstant.financialDetails, '1');
        return response.data;
    } catch (error) {
        console.error(error);
    }
};

export const updateAdditionalDetails = async (data) => {
    try {
        const response = await axiosAuthenticatedClient.post('user/additionalDetails', data, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem(localStorageConstant.token)}`,
                'Content-Type': 'application/json',
                accept: 'application/json',
            },
        });
        if (response.status == HttpStatusCode.Ok) {
            localStorage.setItem(localStorageConstant.arrivalDetails, '1');
            return true;
        } else {
            return false;
        }
    } catch (err) {
        console.error(err.response.data);
        return false;
    }
};

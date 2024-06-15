import axios from 'axios';
export const confirmRegistrationService = async (data) => {
    try {
        console.log(data);
        // log tyoe of confirmArrival
        console.log(typeof data.confirmArrival);

        const response = await axios.post(
            'https://jihapi.kkshan.amlc.in/api/v1/user/register',
            {
                confirm_arrival: data.confirmArrival,
                reason_for_not_coming: data.reason_for_not_coming,
                emergency_contact: data.emergency_contact,
                ameer_permission_taken: data.ameer_permission_taken,
            },
            { headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } }
        );
        return response.data.status === 'success';
    } catch (error) {
        return false;
    }
};

export const getUserDetails = async () => {
    try {
        const response = await axios.get('https://jihapi.kkshan.amlc.in/api/v1/user/getUserDetails', {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` },
        });
        return response.data;
    } catch (error) {
        console.error(error);
        return null;
    }
};

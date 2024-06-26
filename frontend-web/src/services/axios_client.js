import axios from 'axios';

export const axiosAuthenticatedClient = axios.create({
    baseURL: 'https://jihapi.kkshan.amlc.in/api/v1',
    headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
        'Content-Type': 'application/json',
    },
});

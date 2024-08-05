import axios from 'axios';
import { localStorageConstant } from '../utils/constants/localStorageConstants';

export const axiosAuthenticatedClient = axios.create({
    baseURL: 'https://jihapi.kkshan.amlc.in/api/v1',
    headers: {
        Authorization: `Bearer ${localStorage.getItem(localStorageConstant.token)}`,
        'Content-Type': 'application/json',
        accept: 'application/json',
    },
});

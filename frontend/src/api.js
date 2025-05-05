import axios from 'axios';

const apiClient = axios.create({
    baseURL: 'http://localhost:8080/api',
    headers: {
        'Content-Type': 'application/json',
    },
});

export const getClients = () => apiClient.get('/clients');

export const getAppointments = () => apiClient.get('/appointments');

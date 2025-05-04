import axios from 'axios';

// Базовый URL для всех запросов
const apiClient = axios.create({
    baseURL: 'http://localhost:8080/api',
    headers: {
        'Content-Type': 'application/json',
    },
});

// Получить список клиентов
export const getClients = () => apiClient.get('/clients');

// Получить список приёмов
export const getAppointments = () => apiClient.get('/appointments');

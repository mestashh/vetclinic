import axios from 'axios';

// Загрузка клиентов
async function loadClients() {
    try {
        const res = await axios.get('/api/clients');
        const clientsList = document.getElementById('clients-list');
        clientsList.innerHTML = '';

        res.data.forEach(client => {
            const div = document.createElement('div');
            div.className = 'p-2 border-b';
            div.textContent = `${client.full_name} (${client.email})`;
            clientsList.appendChild(div);
        });
    } catch (error) {
        console.error('Ошибка загрузки клиентов:', error);
    }
}

// Загрузка записей на прием
async function loadAppointments() {
    try {
        const res = await axios.get('/api/appointments');
        const appointmentsList = document.getElementById('appointments-list');
        appointmentsList.innerHTML = '';

        res.data.forEach(appointment => {
            const div = document.createElement('div');
            div.className = 'p-2 border-b';
            div.textContent = `${appointment.scheduled_at} - ${appointment.status}`;
            appointmentsList.appendChild(div);
        });
    } catch (error) {
        console.error('Ошибка загрузки приёмов:', error);
    }
}

// Запуск нужных функций в зависимости от страницы
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('clients-list')) loadClients();
    if (document.getElementById('appointments-list')) loadAppointments();
});

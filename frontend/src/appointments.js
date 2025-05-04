import { getAppointments } from './api.js';

document.addEventListener('DOMContentLoaded', () => {
    const appointmentsList = document.querySelector('#appointments-list');

    if (appointmentsList) {
        getAppointments()
            .then(({ data }) => {
                appointmentsList.innerHTML = data.map(appointment => `
                    <div>
                        <strong>Приём #${appointment.id}</strong><br>
                        Клиент ID: ${appointment.client_id}<br>
                        Питомец ID: ${appointment.pet_id}<br>
                        Дата: ${appointment.scheduled_at}<br>
                        Статус: ${appointment.status || 'не указан'}
                    </div>
                    <hr>
                `).join('');
            })
            .catch((error) => {
                appointmentsList.innerHTML = `<p>Ошибка загрузки данных приёмов.</p>`;
                console.error(error);
            });
    }
});

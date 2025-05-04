import { getClients } from './api.js';

document.addEventListener('DOMContentLoaded', () => {
    const clientsList = document.querySelector('#clients-list');

    if (clientsList) {
        getClients()
            .then(({ data }) => {
                clientsList.innerHTML = data.map(client => `
                    <div>
                        <strong>${client.last_name} ${client.first_name} ${client.middle_name || ''}</strong><br>
                        Телефон: ${client.phone || 'не указан'}<br>
                        Email: ${client.email || 'не указан'}
                    </div>
                    <hr>
                `).join('');
            })
            .catch((error) => {
                clientsList.innerHTML = `<p>Ошибка загрузки данных клиентов.</p>`;
                console.error(error);
            });
    }
});

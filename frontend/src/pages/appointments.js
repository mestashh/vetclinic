import axios from 'axios';

export function initAppointments() {
    axios.defaults.withCredentials = true;

    const tableBody = document.querySelector('#appointmentsTable tbody');
    const addBtn = document.getElementById('addAppointmentBtn');

    let users = [];
    let veterinarians = [];

    function showError(msg) {
        alert(msg);
    }

    function makeRow(appt) {
        const user = appt?.user;
        const pet = appt?.pet;
        const vet = appt?.veterinarian;
        const date = appt?.scheduled_at?.slice(0, 10) ?? '';
        const time = appt?.scheduled_at?.slice(11, 16) ?? '';

        return `
            <tr>
                <td>${user ? `${user.first_name} ${user.last_name}` : '—'}</td>
                <td>${pet?.name ?? '—'}</td>
                <td>${vet ? `${vet.first_name} ${vet.last_name}` : '—'}</td>
                <td>${date} ${time}</td>
                <td>—</td>
            </tr>`;
    }

    function loadAppointments() {
        axios.get('/api/appointments')
            .then(({ data }) => {
                const rows = (data.data || []).map(makeRow).join('');
                tableBody.innerHTML = rows;
            })
            .catch(err => {
                console.error('[appointments.js] Ошибка загрузки приёмов:', err);
                showError('Ошибка загрузки приёмов: ' + err.message);
            });
    }

    function loadRefs() {
        return Promise.all([
            axios.get('/api/users'),
            axios.get('/api/veterinarians'),
        ])
            .then(([usersRes, vetsRes]) => {
                users = usersRes.data?.data ?? [];
                veterinarians = vetsRes.data?.data ?? [];
            })
            .catch(err => {
                console.error('[appointments.js] Ошибка справочников:', err);
                showError('Ошибка загрузки справочников');
            });
    }

    // Sanctum: сначала получаем csrf-cookie, потом загружаем всё
    axios.get('/sanctum/csrf-cookie').then(() => {
        loadRefs().then(loadAppointments);
    });
}

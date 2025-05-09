// resources/js/myappointments.js

import axios from 'axios';

export function initMyAppointments() {
    const tableBody = document.querySelector('#appointmentsTable tbody');
    const addBtn = document.getElementById('addAppointmentBtn');

    let users = [];
    let veterinarians = [];

    const SLOT_TIMES = [
        { h: 10, m: 0 }, { h: 11, m: 30 },
        { h: 13, m: 0 }, { h: 14, m: 30 },
        { h: 16, m: 0 }, { h: 17, m: 30 },
    ];

    async function loadRefs() {
        const [u, vRes] = await Promise.all([
            axios.get('/api/users'),
            axios.get('/api/veterinarians'),
        ]);
        users = u.data.data || [];
        veterinarians = (vRes.data.data || []).map(v => ({
            id: v.id,
            first_name: v.user.first_name,
            middle_name: v.user.middle_name,
            last_name: v.user.last_name,
        }));
    }

    function getNextSlot() {
        const now = new Date();
        for (let { h, m } of SLOT_TIMES) {
            const dt = new Date(now.getFullYear(), now.getMonth(), now.getDate(), h, m);
            if (dt > now) return dt;
        }
        const t = new Date(now);
        t.setDate(t.getDate() + 1);
        return new Date(t.getFullYear(), t.getMonth(), t.getDate(), SLOT_TIMES[0].h, SLOT_TIMES[0].m);
    }

    async function fetchBusySlots(veterinarian_id, date) {
        if (!veterinarian_id || !date) return [];
        try {
            const res = await axios.get(`/api/veterinarians/${veterinarian_id}/appointments/${date}`);
            return res.data.busy_slots;
        } catch (e) {
            console.error('Ошибка при получении занятых слотов:', e);
            return [];
        }
    }

    async function buildOptions(date, selectedTime, veterinarian_id) {
        const busy_slots = await fetchBusySlots(veterinarian_id, date);
        return SLOT_TIMES.map(({ h, m }) => {
            const hh = String(h).padStart(2, '0');
            const mm = String(m).padStart(2, '0');
            const tv = `${hh}:${mm}`;
            const disabled = busy_slots.includes(tv) ? ' disabled' : '';
            const sel = tv === selectedTime ? ' selected' : '';
            return `<option value="${tv}"${disabled}${sel}>${tv}</option>`;
        }).join('');
    }

    async function makeRow(appt) {
        const isNew = !appt;
        const idAttr = isNew ? '' : `data-id="${appt.id}"`;

        let slot;
        if (isNew) {
            slot = getNextSlot();
        } else {
            const [datePart, timePart] = appt.scheduled_at.split(' ');
            const [Y, M, D] = datePart.split('-').map(Number);
            const [h, m] = timePart.split(':').map(Number);
            slot = new Date(Y, M - 1, D, h, m);
        }

        const yyyy = slot.getFullYear();
        const mm = String(slot.getMonth() + 1).padStart(2, '0');
        const dd = String(slot.getDate()).padStart(2, '0');
        const hh = String(slot.getHours()).padStart(2, '0');
        const min = String(slot.getMinutes()).padStart(2, '0');

        const dateVal = `${yyyy}-${mm}-${dd}`;
        const timeVal = `${hh}:${min}`;
        const minDate = getNextSlot().toISOString().slice(0, 10);
        const veterinarian_id = appt?.veterinarian_id || '';

        const userCell = window.currentUserRole === 'client'
            ? `<input type="text" class="w-full border-none bg-gray-100" value="${window.currentUserName}" disabled>`
            : `<select class="user-select w-full border-none"${isNew ? '' : ' disabled'}>
                <option value="">— клиент —</option>
                ${users.map(u => `
                    <option value="${u.id}"${appt?.client_id === u.id ? ' selected' : ''}>
                        ${[u.last_name, u.first_name, u.middle_name].filter(Boolean).join(' ')}
                    </option>`).join('')}
            </select>`;

        const petCell = `<select class="pet-select w-full border-none"${isNew ? '' : ' disabled'}><option value="">Загрузка...</option></select>`;

        const vetCell = `<select class="vet-select w-full border-none"${isNew ? '' : ' disabled'}>
            <option value="">— ветеринар —</option>
            ${veterinarians.map(v => `
                <option value="${v.id}"${v.id == veterinarian_id ? ' selected' : ''}>
                    ${[v.last_name, v.first_name, v.middle_name].filter(Boolean).join(' ')}
                </option>`).join('')}
        </select>`;

        const timeOptions = await buildOptions(dateVal, timeVal, veterinarian_id);

        const dateCell = `
            <input type="date" class="date-input w-full border-none"${isNew ? '' : ' disabled'} value="${dateVal}" min="${minDate}">
            <select class="time-select w-full border-none"${isNew ? '' : ' disabled'}>${timeOptions}</select>`;

        const actions = isNew
            ? `<button class="save-btn btn-icon confirm">✅</button><button class="cancel-btn btn-icon cancel">❌</button>`
            : `<button class="edit-btn btn-icon edit">✏️</button><button class="delete-btn btn-icon delete">🗑️</button>`;

        return `<tr ${idAttr} class="border-b ${isNew ? 'bg-gray-100' : ''}">
            <td class="px-4 py-2">${userCell}</td>
            <td class="px-4 py-2">${petCell}</td>
            <td class="px-4 py-2">${vetCell}</td>
            <td class="px-4 py-2">${dateCell}</td>
            <td class="px-4 py-2 actions">${actions}</td>
        </tr>`;
    }

    async function loadAppointments() {
        const { data } = await axios.get('/api/appointments');
        let arr = Array.isArray(data) ? data : (data.data || []);
        if (window.currentUserRole === 'client') {
            arr = arr.filter(a => a.client_id === window.currentUserId);
        }
        tableBody.innerHTML = (await Promise.all(arr.map(makeRow))).join('');
        arr.forEach(appt => {
            const row = tableBody.querySelector(`tr[data-id="${appt.id}"]`);
            axios.get(`/api/users/${appt.client_id}/pets`)
                .then(res => {
                    row.querySelector('.pet-select').innerHTML = res.data.data.map(p =>
                        `<option value="${p.id}"${appt.pet_id === p.id ? ' selected' : ''}>${p.name}</option>`
                    ).join('');
                });
        });
    }

    tableBody.addEventListener('click', async event => {
        const row = event.target.closest('tr');
        const id = row?.dataset.id;
        const btn = event.target;

        if (btn.classList.contains('save-btn') || btn.classList.contains('update-btn')) {
            const clientId = window.currentUserRole === 'client' ? window.currentUserId : row.querySelector('.user-select').value;
            const date = row.querySelector('.date-input').value;
            const time = row.querySelector('.time-select').value;
            const payload = {
                client_id: clientId,
                pet_id: row.querySelector('.pet-select').value,
                veterinarian_id: row.querySelector('.vet-select').value,
                scheduled_at: `${date}T${time}:00`
            };
            const method = btn.classList.contains('save-btn') ? 'post' : 'put';
            const url = method === 'post' ? '/api/appointments' : `/api/appointments/${id}`;
            return axios[method](url, payload).then(loadAppointments);
        }

        if (btn.classList.contains('edit-btn')) {
            row.querySelectorAll('.pet-select, .vet-select, .date-input, .time-select').forEach(el => el.disabled = false);
            row.querySelector('.actions').innerHTML = `<button class="update-btn btn-icon confirm">✅</button><button class="cancel-btn btn-icon cancel">❌</button>`;
        }

        if (btn.classList.contains('cancel-btn')) return loadAppointments();
        if (btn.classList.contains('delete-btn')) return axios.delete(`/api/appointments/${id}`).then(loadAppointments);
    });

    tableBody.addEventListener('change', async event => {
        const row = event.target.closest('tr');
        if (!row) return;

        const dateInput = row.querySelector('.date-input');
        const timeSelect = row.querySelector('.time-select');
        const vetSelect = row.querySelector('.vet-select');

        const dateVal = dateInput?.value;
        const vetId = vetSelect?.value;

        if (dateVal && vetId) {
            const timeOptions = await buildOptions(dateVal, '', vetId);
            timeSelect.innerHTML = timeOptions;
        }
    });

    addBtn.onclick = async () => {
        const html = await makeRow(null);
        tableBody.insertAdjacentHTML('afterbegin', html);
        const row = tableBody.querySelector('tr');
        const petSelect = row.querySelector('.pet-select');
        axios.get(`/api/users/${window.currentUserId}/pets`)
            .then(res => {
                petSelect.innerHTML = res.data.data.map(p => `<option value="${p.id}">${p.name}</option>`).join('');
            });
    };

    loadRefs().then(loadAppointments);
}

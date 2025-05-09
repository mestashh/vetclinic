import axios from 'axios';

export function initAppointments() {
    const table = document.getElementById('appointmentsTable');
    const tableBody = table.querySelector('tbody');
    const addBtn = document.getElementById('addAppointmentBtn');
    const isAdmin = ['admin', 'superadmin'].includes(window.currentUserRole);

    let users = [];
    let veterinarians = [];
    let pets = [];

    const SLOT_TIMES = [
        { h: 10, m: 0 }, { h: 11, m: 30 },
        { h: 13, m: 0 }, { h: 14, m: 30 },
        { h: 16, m: 0 }, { h: 17, m: 30 },
    ];

    function showError(msg) {
        alert(msg);
        console.error(msg);
    }

    async function loadRefs() {
        try {
            const [usersRes, vetsRes, petsRes] = await Promise.all([
                axios.get('/api/users'),
                axios.get('/api/veterinarians'),
                axios.get('/api/animals')
            ]);
            users = usersRes.data.data || [];
            veterinarians = (vetsRes.data.data || []).map(v => ({
                id: v.id,
                user_id: v.user_id,
                name: [v.user?.last_name, v.user?.first_name].filter(Boolean).join(' '),
            }));
            pets = petsRes.data.data || [];
        } catch (e) {
            showError("Ошибка загрузки справочников");
        }
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
        } catch {
            return [];
        }
    }

    async function buildOptions(date, selectedTime, veterinarian_id) {
        const busy_slots = await fetchBusySlots(veterinarian_id, date);
        return SLOT_TIMES.map(({ h, m }) => {
            const hh = String(h).padStart(2, '0');
            const mm = String(m).padStart(2, '0');
            const value = `${hh}:${mm}`;
            const disabled = busy_slots.includes(value) ? ' disabled' : '';
            const selected = value === selectedTime ? ' selected' : '';
            return `<option value="${value}"${disabled}${selected}>${value}</option>`;
        }).join('');
    }

    async function makeRow(appt) {
        const isNew = !appt;
        const idAttr = isNew ? '' : `data-id="${appt.id}"`;
        let slot = isNew ? getNextSlot() : new Date(appt.scheduled_at);

        const yyyy = slot.getFullYear();
        const mm = String(slot.getMonth() + 1).padStart(2, '0');
        const dd = String(slot.getDate()).padStart(2, '0');
        const hh = String(slot.getHours()).padStart(2, '0');
        const min = String(slot.getMinutes()).padStart(2, '0');

        const dateVal = `${yyyy}-${mm}-${dd}`;
        const timeVal = `${hh}:${min}`;
        const minDate = getNextSlot().toISOString().slice(0, 10);
        const veterinarian_id = appt?.veterinarian_id || '';

        const userCell = `<select class="user-select w-full border-none"${isNew ? '' : ' disabled'}>
            <option value="">Клиент</option>
            ${users.map(u => `
                <option value="${u.id}"${appt?.client_id === u.id ? ' selected' : ''}>
                    ${[u.last_name, u.first_name, u.middle_name].filter(Boolean).join(' ')}
                </option>`).join('')}
        </select>`;

        const petCell = `<select class="pet-select w-full border-none"${isNew ? '' : ' disabled'}>
            <option value="">Питомец</option>
            ${pets.map(p => `
                <option value="${p.id}"${appt?.pet_id === p.id ? ' selected' : ''}>${p.name}</option>`).join('')}
        </select>`;

        const vetCell = `<select class="vet-select w-full border-none"${isNew ? '' : ' disabled'}>
            <option value="">Ветеринар</option>
            ${veterinarians.map(v => `
                <option value="${v.id}"${v.id == veterinarian_id ? ' selected' : ''}>${v.name}</option>`).join('')}
        </select>`;

        const timeOptions = await buildOptions(dateVal, timeVal, veterinarian_id);

        const dateCell = `
            <input type="date" class="date-input w-full border-none"${isNew ? '' : ' disabled'} value="${dateVal}" min="${minDate}">
            <select class="time-select w-full border-none"${isNew ? '' : ' disabled'}>${timeOptions}</select>`;

        const actions = isAdmin
            ? (isNew
                ? `<button class="save-btn btn-icon confirm">✅</button><button class="cancel-btn btn-icon cancel">❌</button>`
                : `<button class="edit-btn btn-icon edit">✏️</button><button class="delete-btn btn-icon delete">🗑️</button>`)
            : '';

        return `<tr ${idAttr} class="border-b ${isNew ? 'bg-gray-100' : ''}">
            <td class="px-2 py-2">${userCell}</td>
            <td class="px-2 py-2">${petCell}</td>
            <td class="px-2 py-2">${vetCell}</td>
            <td class="px-2 py-2">${dateCell}</td>
            <td class="px-2 py-2 actions">${actions}</td>
        </tr>`;
    }
    async function loadAppointments() {
        try {
            const { data } = await axios.get('/api/appointments');
            let arr = Array.isArray(data) ? data : (data.data || []);
            tableBody.innerHTML = (await Promise.all(arr.map(makeRow))).join('');
        } catch (e) {
            showError("Ошибка загрузки приёмов");
        }
    }

    tableBody.addEventListener('click', async event => {
        const row = event.target.closest('tr');
        const id = row?.dataset.id;
        const btn = event.target;

        if (btn.classList.contains('save-btn') || btn.classList.contains('update-btn')) {
            const client_id = row.querySelector('.user-select')?.value;
            const pet_id = row.querySelector('.pet-select')?.value;
            const veterinarian_id = row.querySelector('.vet-select')?.value;
            const date = row.querySelector('.date-input')?.value;
            const time = row.querySelector('.time-select')?.value;

            const payload = {
                client_id, pet_id, veterinarian_id,
                scheduled_at: `${date}T${time}:00`
            };

            const method = btn.classList.contains('save-btn') ? 'post' : 'put';
            const url = method === 'post' ? '/api/appointments' : `/api/appointments/${id}`;

            try {
                await axios[method](url, payload);
                loadAppointments();
            } catch (e) {
                showError('Ошибка сохранения');
            }
            return;
        }

        if (btn.classList.contains('edit-btn')) {
            row.querySelectorAll('select, input').forEach(i => i.disabled = false);
            row.querySelector('.actions').innerHTML = `
                <button class="update-btn btn-icon confirm">✅</button>
                <button class="cancel-btn btn-icon cancel">❌</button>
            `;
        }

        if (btn.classList.contains('cancel-btn')) return loadAppointments();

        if (btn.classList.contains('delete-btn')) {
            try {
                await axios.delete(`/api/appointments/${id}`);
                loadAppointments();
            } catch {
                showError('Ошибка при удалении');
            }
        }
    });

    tableBody.addEventListener('change', async event => {
        if (event.target.classList.contains('user-select')) {
            const row = event.target.closest('tr');
            const clientId = event.target.value;
            const petSelect = row.querySelector('.pet-select');

            if (clientId && petSelect) {
                axios.get(`/api/users/${clientId}/pets`)
                    .then(res => {
                        petSelect.innerHTML = res.data.data.map(p =>
                            `<option value="${p.id}">${p.name}</option>`
                        ).join('');
                    })
                    .catch(() => {
                        petSelect.innerHTML = '<option value="">Ошибка загрузки питомцев</option>';
                    });
            }
        }
        const row = event.target.closest('tr');
        const dateInput = row?.querySelector('.date-input');
        const timeSelect = row?.querySelector('.time-select');
        const vetSelect = row?.querySelector('.vet-select');

        const dateVal = dateInput?.value;
        const vetId = vetSelect?.value;

        if (dateVal && vetId && timeSelect) {
            const timeOptions = await buildOptions(dateVal, '', vetId);
            timeSelect.innerHTML = timeOptions;
        }
    });

    if (addBtn) {
        addBtn.onclick = async () => {
            const html = await makeRow(null);
            tableBody.insertAdjacentHTML('afterbegin', html);
        };
    }


    loadRefs().then(loadAppointments);
}

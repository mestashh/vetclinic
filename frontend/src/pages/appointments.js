import axios from 'axios';

export function initAppointments() {
    const tableBody = document.querySelector('#appointmentsTable tbody');
    const addBtn = document.getElementById('addAppointmentBtn');

    let clients = [];
    let veterinarians = [];

    const SLOT_TIMES = [
        { h: 10, m: 0 }, { h: 11, m: 30 },
        { h: 13, m: 0 }, { h: 14, m: 30 },
        { h: 16, m: 0 }, { h: 17, m: 30 },
    ];

    async function loadRefs() {
        try {
            const [clientsRes, vetsRes] = await Promise.all([
                axios.get('/api/clients'),
                axios.get('/api/veterinarians'),
            ]);
            clients = clientsRes.data?.data ?? [];
            console.log('Клиенты загружены:', clients);
            veterinarians = vetsRes.data?.data ?? [];
        } catch (err) {
            alert('Ошибка загрузки справочников: ' + err);
            clients = [];
            veterinarians = [];
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

    function buildOptions(currentDate, currentFull) {
        const now = new Date();
        const selectedDate = new Date(currentDate);
        return SLOT_TIMES.map(({ h, m }) => {
            const hh = String(h).padStart(2, '0');
            const mm = String(m).padStart(2, '0');
            const full = `${currentDate}T${hh}:${mm}`;
            let disabled = '';
            if (selectedDate.toDateString() === now.toDateString()) {
                disabled = new Date(full) <= now ? ' disabled' : '';
            }
            const sel = full === currentFull ? ' selected' : '';
            return `<option value="${full}"${disabled}${sel}>${hh}:${mm}</option>`;
        }).join('');
    }

    function makeRow(appt) {
        const isNew = appt == null;
        const idAttr = isNew ? '' : `data-id="${appt.id}"`;
        const nowSlot = isNew ? getNextSlot() : new Date(appt.scheduled_at);
        const dateVal = nowSlot.toISOString().slice(0, 10);
        const timeVal = nowSlot.toISOString().slice(0, 16);

        const clientSelect = `
    <select class="client-select w-full border-none"${isNew ? '' : ' disabled'}>
        <option value="">Выберите клиента</option>
        ${clients.map(c => `
            <option value="${c.id}"${appt?.client_id === c.id ? ' selected' : ''}>
                ${[c.last_name, c.first_name, c.middle_name].filter(Boolean).join(' ')}
            </option>`).join('')}
    </select>`;


        const vetSelect = `
            <select class="vet-select w-full border-none"${isNew ? '' : ' disabled'}>
                <option value="">Выберите ветеринара</option>
                ${veterinarians.map(v => `
                    <option value="${v.id}"${appt?.veterinarian_id === v.id ? ' selected' : ''}>
                        ${v.last_name} ${v.first_name} ${v.middle_name ?? ''}
                    </option>`).join('')}
            </select>`;

        const petSelect = `
            <select class="pet-select w-full border-none"${isNew ? '' : ' disabled'}>
                ${appt?.pet_id ? `<option value="${appt.pet_id}" selected>${appt?.pet?.name || '...'}</option>` : '<option value="">Выберите питомца</option>'}
            </select>`;


        return `
        <tr ${idAttr} class="${isNew ? 'bg-gray-100' : 'bg-white hover:bg-gray-50'} border-b">
            <td class="px-4 py-2">${clientSelect}</td>
            <td class="px-4 py-2">${petSelect}</td>
            <td class="px-4 py-2">${vetSelect}</td>
            <td class="px-4 py-2 space-y-1">
                <input type="date" class="date-input w-full border-none"
                    ${isNew ? '' : 'disabled'} min="${getNextSlot().toISOString().slice(0, 10)}"
                    value="${dateVal}">
                <select class="time-select w-full border-none"${isNew ? '' : 'disabled'}>
                    ${buildOptions(dateVal, isNew ? timeVal : appt.scheduled_at.slice(0, 16))}
                </select>
            </td>
            <td class="px-4 py-2 space-x-1">
                ${isNew
            ? `<button class="save-btn bg-green-500 text-white px-2 rounded">Сохранить</button>
                   <button class="cancel-btn bg-gray-500 text-white px-2 rounded">Отмена</button>`
            : `<button class="edit-btn bg-blue-500 text-white px-2 rounded">Изменить</button>
                   <button class="delete-btn bg-red-500 text-white px-2 rounded">Удалить</button>`}
            </td>
        </tr>`;
    }

    function loadAppointments() {
        axios.get('/api/appointments')
            .then(({ data }) => {
                tableBody.innerHTML = (data.data || []).map(makeRow).join('');
            })
            .catch(err => alert('Ошибка загрузки данных: ' + err));
    }

    addBtn.onclick = () => {
        tableBody.insertAdjacentHTML('afterbegin', makeRow(null));
    };

    tableBody.addEventListener('change', async (event) => {
        const row = event.target.closest('tr');

        if (event.target.classList.contains('client-select')) {
            const clientId = event.target.value;
            const petSelect = row.querySelector('.pet-select');

            if (!clientId) {
                petSelect.innerHTML = '<option value="">Выберите питомца</option>';
                return;
            }

            try {
                const res = await axios.get(`/api/clients/${clientId}/pets`);
                const pets = res.data.data;
                petSelect.innerHTML = pets.map(p =>
                    `<option value="${p.id}">${p.name} (${p.species})</option>`).join('');
            } catch (err) {
                alert('Ошибка загрузки питомцев: ' + err);
            }
        }

        if (event.target.classList.contains('date-input')) {
            const select = row.querySelector('.time-select');
            select.innerHTML = buildOptions(event.target.value, '');
        }
    });

    tableBody.addEventListener('click', (event) => {
        const row = event.target.closest('tr');
        const id = row.dataset.id;

        const payload = {
            client_id: row.querySelector('.client-select').value,
            pet_id: row.querySelector('.pet-select').value,
            veterinarian_id: row.querySelector('.vet-select').value,
            scheduled_at: `${row.querySelector('.date-input').value}T${row.querySelector('.time-select').value.slice(11)}:00`,
        };

        if (event.target.classList.contains('save-btn')) {
            axios.post('/api/appointments', payload)
                .then(loadAppointments)
                .catch(err => alert('Ошибка: ' + err));
        }

        if (event.target.classList.contains('delete-btn')) {
            axios.delete(`/api/appointments/${id}`).then(loadAppointments)
                .catch(err => alert('Ошибка удаления: ' + err));
        }

        if (event.target.classList.contains('edit-btn')) {
            row.querySelectorAll('input, select').forEach(el => el.disabled = false);
            event.target.textContent = 'Сохранить';
            event.target.classList.replace('edit-btn', 'update-btn');
        } else if (event.target.classList.contains('update-btn')) {
            axios.put(`/api/appointments/${id}`, payload)
                .then(loadAppointments)
                .catch(err => alert('Ошибка обновления: ' + err));
        }
    });

    loadRefs().then(loadAppointments);
}
import axios from 'axios';

export function initAppointments() {
    const tableBody = document.querySelector('#appointmentsTable tbody');
    const addBtn = document.getElementById('addAppointmentBtn');

    const SLOT_TIMES = [
        { h:10, m:0 }, { h:11, m:30 },
        { h:13, m:0 }, { h:14, m:30 },
        { h:16, m:0 }, { h:17, m:30 },
    ];

    function getNextSlot() {
        const now = new Date();
        for (let {h,m} of SLOT_TIMES) {
            const dt = new Date(now.getFullYear(), now.getMonth(), now.getDate(), h, m);
            if (dt > now) return dt;
        }
        const t = new Date(now);
        t.setDate(t.getDate()+1);
        return new Date(t.getFullYear(), t.getMonth(), t.getDate(), SLOT_TIMES[0].h, SLOT_TIMES[0].m);
    }

    function buildOptions(currentDate, currentFull) {
        const now = new Date();
        const selectedDate = new Date(currentDate);

        return SLOT_TIMES.map(({h,m}) => {
            const hh = String(h).padStart(2,'0');
            const mm = String(m).padStart(2,'0');
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
        const dateVal = nowSlot.toISOString().slice(0,10);
        const timeVal = nowSlot.toISOString().slice(0,16);

        return `
      <tr ${idAttr} class="${isNew? 'bg-gray-100':'bg-white hover:bg-gray-50'} border-b">
        <td class="px-4 py-2">
          <input ${isNew?'':'disabled'} class="client-input w-full border-none" placeholder="Клиент ID"
                 value="${appt?.client_id||''}">
        </td>
        <td class="px-4 py-2">
          <input ${isNew?'':'disabled'} class="pet-input w-full border-none" placeholder="Питомец ID"
                 value="${appt?.pet_id||''}">
        </td>
        <td class="px-4 py-2">
          <input ${isNew?'':'disabled'} class="vet-input w-full border-none" placeholder="Ветеринар ID"
                 value="${appt?.veterinarian_id||''}">
        </td>
        <td class="px-4 py-2 space-y-1">
          <input type="date" ${isNew?'':'disabled'} class="date-input w-full border-none"
                 min="${getNextSlot().toISOString().slice(0,10)}" value="${dateVal}">
          <select ${isNew?'':'disabled'} class="time-select w-full border-none">
            ${buildOptions(dateVal, isNew ? timeVal : appt.scheduled_at.slice(0,16))}
          </select>
        </td>
        <td class="px-4 py-2">
          <input ${isNew?'':'disabled'} class="note-input w-full border-none" placeholder="Примечание"
                 value="${appt?.notes||''}">
        </td>
        <td class="px-4 py-2 space-x-1">
          ${isNew
            ? `<button class="save-btn bg-green-500 text-white px-2 rounded">Сохранить</button>
               <button class="cancel-btn bg-gray-500 text-white px-2 rounded">Отмена</button>`
            : `<button class="edit-btn bg-blue-500 text-white px-2 rounded">Изменить</button>
               <button class="delete-btn bg-red-500 text-white px-2 rounded">Удалить</button>`
        }
        </td>
      </tr>
    `;
    }

    function loadAppointments() {
        axios.get('/api/appointments')
            .then(({data}) => {
                tableBody.innerHTML = (data.data || []).map(makeRow).join('');
            })
            .catch(err => alert('Ошибка загрузки данных: ' + err));
    }

    addBtn.onclick = () => {
        tableBody.insertAdjacentHTML('afterbegin', makeRow(null));
    };

    tableBody.addEventListener('click', (event) => {
        const row = event.target.closest('tr');
        const id = row.dataset.id;

        if (event.target.classList.contains('save-btn')) {
            axios.post('/api/appointments', {
                client_id: row.querySelector('.client-input').value,
                pet_id: row.querySelector('.pet-input').value,
                veterinarian_id: row.querySelector('.vet-input').value,
                scheduled_at: `${row.querySelector('.date-input').value}T${row.querySelector('.time-select').value.slice(11)}:00`,
                notes: row.querySelector('.note-input').value
            }).then(loadAppointments).catch(err => alert('Ошибка: ' + err));
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
            axios.put(`/api/appointments/${id}`, {
                client_id: row.querySelector('.client-input').value,
                pet_id: row.querySelector('.pet-input').value,
                veterinarian_id: row.querySelector('.vet-input').value,
                scheduled_at: `${row.querySelector('.date-input').value}T${row.querySelector('.time-select').value.slice(11)}:00`,
                notes: row.querySelector('.note-input').value
            }).then(loadAppointments).catch(err => alert('Ошибка обновления: ' + err));
        }
    });

    // Добавлен обработчик изменения даты
    tableBody.addEventListener('change', (event) => {
        if (event.target.classList.contains('date-input')) {
            const row = event.target.closest('tr');
            const select = row.querySelector('.time-select');
            select.innerHTML = buildOptions(event.target.value, '');
        }
    });

    loadAppointments();
}

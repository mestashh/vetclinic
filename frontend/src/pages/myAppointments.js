import axios from 'axios';

export function initMyAppointments() {
    const tableBody = document.querySelector('#appointmentsTable tbody');
    const addBtn    = document.getElementById('addAppointmentBtn');

    let users         = [];
    let veterinarians = [];

    // Возможные слоты
    const SLOT_TIMES = [
        { h: 10, m: 0  }, { h: 11, m: 30 },
        { h: 13, m: 0  }, { h: 14, m: 30 },
        { h: 16, m: 0  }, { h: 17, m: 30 },
    ];

    // Загрузить справочники пользователей и ветеринаров
    async function loadRefs() {
        const [u, vRes] = await Promise.all([
            axios.get('/api/users'),
            axios.get('/api/veterinarians'),
        ]);
        users = u.data.data || [];
        // берём из каждого v.user нужные поля
        veterinarians = (vRes.data.data || []).map(v => ({
            id:      v.id,
            first_name:  v.user.first_name,
            middle_name: v.user.middle_name,
            last_name:   v.user.last_name,
        }));
    }


    // Нахождение первого свободного слота после текущего момента
    function getNextSlot() {
        const now = new Date();
        for (let { h, m } of SLOT_TIMES) {
            const dt = new Date(now.getFullYear(), now.getMonth(), now.getDate(), h, m);
            if (dt > now) return dt;
        }
        // если сегодня все занято — возвращаем завтра в 10:00
        const t = new Date(now);
        t.setDate(t.getDate() + 1);
        return new Date(t.getFullYear(), t.getMonth(), t.getDate(), SLOT_TIMES[0].h, SLOT_TIMES[0].m);
    }

    // Построить <option> для селекта времени
    function buildOptions(date, currentFull) {
        return SLOT_TIMES.map(({ h, m }) => {
            const hh   = String(h).padStart(2, '0');
            const mm   = String(m).padStart(2, '0');
            const full = `${date}T${hh}:${mm}`;
            const disabled = new Date(full) <= new Date() ? ' disabled' : '';
            const sel      = full === currentFull ? ' selected' : '';
            return `<option value="${full}"${disabled}${sel}>${hh}:${mm}</option>`;
        }).join('');
    }

    // Рендер одной строки (новая, если appt==null, или существующая)
    function makeRow(appt) {
        const isNew = !appt;
        const idAttr = isNew ? '' : `data-id="${appt.id}"`;

        const slot = isNew ? getNextSlot() : new Date(appt.scheduled_at);
        const dateVal = slot.toISOString().slice(0, 10);
        const fullVal = slot.toISOString().slice(0, 16);
        const minDate = getNextSlot().toISOString().slice(0, 10);

        const userCell = window.currentUserRole === 'client'
            ? `<input type="text" class="w-full border-none bg-gray-100" value="${window.currentUserName}" disabled>`
            : `<select class="user-select w-full border-none"${isNew ? '' : ' disabled'}>
         <option value="">— клиент —</option>
         ${users.map(u => `
           <option value="${u.id}"${appt?.client_id === u.id ? ' selected' : ''}>
             ${[u.last_name, u.first_name, u.middle_name].filter(Boolean).join(' ')}
           </option>`).join('')}
       </select>`;

        // Питомцы будут загружены позже
        const petCell = `
    <select class="pet-select w-full border-none"${isNew ? '' : ' disabled'}>
      <option value="">Загрузка...</option>
    </select>`;

        const vetCell = `
    <select class="vet-select w-full border-none"${isNew ? '' : ' disabled'}>
      <option value="">— ветеринар —</option>
      ${veterinarians.map(v => `
        <option value="${v.id}"${appt?.veterinarian_id === v.id ? ' selected' : ''}>
          ${[v.last_name, v.first_name, v.middle_name].filter(Boolean).join(' ')}
        </option>`).join('')}
    </select>`;

        const dateCell = `
    <input type="date" class="date-input w-full border-none"${isNew ? '' : ' disabled'}
      value="${dateVal}" min="${minDate}">
    <select class="time-select w-full border-none"${isNew ? '' : ' disabled'}>
      ${buildOptions(dateVal, fullVal)}
    </select>`;

        const actions = isNew
            ? `<button class="save-btn bg-green-500 text-white px-2 rounded">Сохранить</button>
       <button class="cancel-btn bg-gray-500 text-white px-2 rounded">Отмена</button>`
            : `<button class="edit-btn bg-blue-500 text-white px-2 rounded">Изменить</button>
       <button class="delete-btn bg-red-500 text-white px-2 rounded">Удалить</button>`;

        const rowHtml = `
    <tr ${idAttr} class="border-b ${isNew ? 'bg-gray-100' : ''}">
      <td class="px-4 py-2">${userCell}</td>
      <td class="px-4 py-2">${petCell}</td>
      <td class="px-4 py-2">${vetCell}</td>
      <td class="px-4 py-2">${dateCell}</td>
      <td class="px-4 py-2 space-x-2">${actions}</td>
    </tr>`;

        return rowHtml;
    }



    // Загрузить и отрисовать все записи
    function loadAppointments() {
        axios.get('/api/appointments')
            .then(({ data }) => {
                let arr = Array.isArray(data) ? data : (data.data || []);
                if (window.currentUserRole === 'client') {
                    arr = arr.filter(a => a.client_id === window.currentUserId);
                }
                tableBody.innerHTML = arr.map(makeRow).join('');

                // После рендера записей загрузим питомцев для каждой записи
                arr.forEach(appt => {
                    const row = tableBody.querySelector(`tr[data-id="${appt.id}"]`);
                    const petSelect = row.querySelector('.pet-select');
                    axios.get(`/api/users/${appt.client_id}/pets`)
                        .then(res => {
                            petSelect.innerHTML = res.data.data.map(p => `
              <option value="${p.id}"${appt.pet_id === p.id ? ' selected' : ''}>
                ${p.name}
              </option>`).join('');
                        })
                        .catch(e => {
                            petSelect.innerHTML = '<option value="">Ошибка загрузки</option>';
                            console.error('Ошибка загрузки питомцев:', e);
                        });
                });
            })
            .catch(e => alert('Ошибка загрузки: ' + e));
    }


    // «Добавить запись»
    addBtn.onclick = () => {
        const html = makeRow(null);
        tableBody.insertAdjacentHTML('afterbegin', html);

        // если клиент — сразу подгружаем питомцев
        if (window.currentUserRole === 'client') {
            const row       = tableBody.querySelector('tr');
            const petSelect = row.querySelector('.pet-select');
            axios.get(`/api/users/${window.currentUserId}/pets`)
                .then(res => {
                    petSelect.innerHTML = res.data.data
                        .map(p => `<option value="${p.id}">${p.name}</option>`)
                        .join('');
                })
                .catch(e => alert('Ошибка питомцев: ' + e));
        }
    };

    // Обработчик всех кнопок внутри таблицы
    tableBody.addEventListener('click', event => {
        const row    = event.target.closest('tr');
        const id     = row?.dataset.id;
        const btn    = event.target;

        // сохранить новую
        if (btn.classList.contains('save-btn')) {
            const clientId = window.currentUserRole === 'client'
                ? window.currentUserId
                : row.querySelector('.user-select').value;
            const payload = {
                client_id:       clientId,
                pet_id:          row.querySelector('.pet-select').value,
                veterinarian_id: row.querySelector('.vet-select').value,
                scheduled_at:    `${row.querySelector('.date-input').value}T${row
                    .querySelector('.time-select').value.slice(11)}:00`
            };
            return axios.post('/api/appointments', payload)
                .then(loadAppointments)
                .catch(e => alert('Ошибка: ' + e));
        }

        // разблокировать поля
        if (btn.classList.contains('edit-btn')) {
            row.querySelectorAll('.pet-select, .vet-select, .date-input, .time-select')
                .forEach(el => el.disabled = false);
            btn.textContent = 'Сохранить';
            btn.classList.replace('edit-btn', 'update-btn');
            return;
        }

        // отправить обновление
        if (btn.classList.contains('update-btn')) {
            const clientId = window.currentUserRole === 'client'
                ? window.currentUserId
                : row.querySelector('.user-select').value;
            const payload = {
                client_id:       clientId,
                pet_id:          row.querySelector('.pet-select').value,
                veterinarian_id: row.querySelector('.vet-select').value,
                scheduled_at:    `${row.querySelector('.date-input').value}T${row
                    .querySelector('.time-select').value.slice(11)}:00`
            };
            return axios.put(`/api/appointments/${id}`, payload)
                .then(loadAppointments)
                .catch(e => alert('Ошибка обновления: ' + e));
        }

        // удалить
        if (btn.classList.contains('delete-btn')) {
            return axios.delete(`/api/appointments/${id}`)
                .then(loadAppointments)
                .catch(e => alert('Ошибка удаления: ' + e));
        }
    });

    // старотовая загрузка справочников и записей
    loadRefs().then(loadAppointments);
}

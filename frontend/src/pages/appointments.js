import axios from 'axios';

export function initAppointments() {
    const tableBody = document.querySelector('#appointmentsTable tbody');
    const isAdmin = ['admin', 'superadmin'].includes(window.currentUserRole);

    let users = [];
    let veterinarians = [];

    const SLOT_TIMES = [
        { h: 10, m: 0 }, { h: 11, m: 30 },
        { h: 13, m: 0 }, { h: 14, m: 30 },
        { h: 16, m: 0 }, { h: 17, m: 30 },
    ];

    async function loadRefs() {
        const [usersRes, vetsRes] = await Promise.all([
            axios.get('/api/users'),
            axios.get('/api/veterinarians'),
        ]);
        users = usersRes.data?.data ?? [];
        veterinarians = (vetsRes.data?.data ?? []).map(v => ({
            id: v.id,
            first_name: v.user?.first_name ?? '—',
            middle_name: v.user?.middle_name ?? '',
            last_name: v.user?.last_name ?? '—',
        }));
    }

    function buildOptions(currentDate, currentFull) {
        return SLOT_TIMES.map(({ h, m }) => {
            const hh = String(h).padStart(2, '0');
            const mm = String(m).padStart(2, '0');
            const full = `${currentDate}T${hh}:${mm}`;
            const disabled = new Date(full) <= new Date() ? ' disabled' : '';
            const sel = full === currentFull ? ' selected' : '';
            return `<option value="${full}"${disabled}${sel}>${hh}:${mm}</option>`;
        }).join('');
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

    function makeRow(appt) {
        const idAttr = `data-id="${appt.id}"`;
        const slot = new Date(appt.scheduled_at);
        const dateVal = slot.toISOString().slice(0, 10);
        const fullVal = slot.toISOString().slice(0, 16);

        const userName = users.find(u => u.id === appt.client_id);
        const vetName = veterinarians.find(v => v.id === appt.veterinarian_id);

        const actions = isAdmin
            ? `
                <button class="edit-btn btn-icon bg-blue-500 text-white rounded">✏️</button>
                <button class="delete-btn btn-icon bg-red-500 text-white rounded">🗑️</button>
              `
            : '';

        return `
        <tr ${idAttr} class="bg-white hover:bg-gray-50 border-b">
            <td class="px-4 py-2">${userName ? [userName.last_name, userName.first_name, userName.middle_name].filter(Boolean).join(' ') : '—'}</td>
            <td class="px-4 py-2">${appt?.pet?.name || '—'}</td>
            <td class="px-4 py-2">${vetName ? [vetName.last_name, vetName.first_name, vetName.middle_name].filter(Boolean).join(' ') : '—'}</td>
            <td class="px-4 py-2">${dateVal} ${fullVal.slice(11, 16)}</td>
            ${isAdmin ? `<td class="px-4 py-2 space-x-2">${actions}</td>` : ''}
        </tr>`;
    }

    function loadAppointments() {
        axios.get('/api/appointments')
            .then(({ data }) => {
                let arr = Array.isArray(data) ? data : (data.data || []);

                if (window.currentUserRole === 'client') {
                    arr = arr.filter(a => a.client_id === window.currentUserId);
                    tableBody.innerHTML = arr.map(makeRow).join('');
                    return;
                }

                if (window.currentUserRole === 'vet') {
                    return axios.get(`/api/veterinarians/by-user/${window.currentUserId}`)
                        .then(res => {
                            const vetId = res.data.id;
                            const filtered = arr.filter(a => a.veterinarian_id === vetId);
                            tableBody.innerHTML = filtered.map(makeRow).join('');
                        })
                        .catch(() => {
                            alert('Вы не связаны с профилем ветеринара.');
                            tableBody.innerHTML = '';
                        });
                }

                tableBody.innerHTML = arr.map(makeRow).join('');
            })
            .catch(err => alert('Ошибка загрузки данных: ' + err));
    }

    loadRefs().then(loadAppointments);
}

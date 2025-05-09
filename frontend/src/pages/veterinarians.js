export function initVets() {
    const table = document.getElementById('vetsTable');
    const addBtn = document.getElementById('addVetBtn');

    function showError(msg) {
        alert(msg);
    }

    function loadVets() {
        axios.get('/api/veterinarians')
            .then(({ data }) => {
                const html = (data.data || []).map(v => {
                    const fullName = [v.user?.last_name, v.user?.first_name, v.user?.middle_name]
                        .filter(Boolean).join(' ');
                    return `
                    <tr data-id="${v.id}" class="bg-white border-b hover:bg-gray-50">
                        <td class="px-4 py-2"><input disabled value="${fullName}" class="vet-input w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="${v.specialization || ''}" class="vet-input w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="${v.user?.phone || ''}" class="vet-input w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="${v.user?.email || ''}" class="vet-input w-full border-none"></td>
                        <td class="px-4 py-2 action-buttons">
                            <button class="edit-btn btn-icon">✏️</button>
                            <button class="delete-btn btn-icon">🗑️</button>
                        </td>
                    </tr>`;
                }).join('');

                table.querySelector('tbody').innerHTML = html;
                attachEvents();
            })
            .catch(() => showError("Не удалось загрузить ветеринаров"));
    }

    function attachEvents() {
        table.querySelectorAll('.delete-btn').forEach(btn => {
            btn.onclick = () => {
                const id = btn.closest('tr').dataset.id;
                axios.delete(`/api/veterinarians/${id}`)
                    .then(() => loadVets())
                    .catch(() => showError("Ошибка при удалении"));
            };
        });

        table.querySelectorAll('.edit-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                row.querySelectorAll('input').forEach(i => i.disabled = false);
                const cell = btn.closest('td');
                cell.innerHTML = `
                    <button class="update-btn btn-icon confirm-btn">✅</button>
                    <button class="cancel-btn btn-icon cancel-btn">❌</button>
                `;
                attachEvents();
            };
        });

        table.querySelectorAll('.update-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                const id = row.dataset.id;
                const [last_name, first_name, middle_name, specialization, phone, email] =
                    Array.from(row.querySelectorAll('input')).map(i => i.value);
                axios.put(`/api/veterinarians/${id}`, {
                    last_name, first_name, middle_name, specialization, phone, email
                }).then(() => loadVets())
                    .catch(() => showError("Ошибка при обновлении"));
            };
        });
    }

    if (addBtn) {
        addBtn.onclick = () => {
            const tr = document.createElement('tr');
            tr.className = 'bg-gray-100 border-b';
            tr.innerHTML = `
                <td class="px-4 py-2"><input placeholder="ФИО" class="new-input w-full" /></td>
                <td class="px-4 py-2"><input placeholder="Специализация" class="new-input w-full" /></td>
                <td class="px-4 py-2"><input placeholder="Телефон" class="new-input w-full" /></td>
                <td class="px-4 py-2"><input placeholder="Email" class="new-input w-full" /></td>
                <td class="px-4 py-2 action-buttons">
                    <button class="save-btn btn-icon confirm-btn">✅</button>
                    <button class="cancel-btn btn-icon cancel-btn">❌</button>
                </td>`;
            table.prepend(tr);

            tr.querySelector('.save-btn').onclick = () => {
                const [fullName, specialization, phone, email] =
                    Array.from(tr.querySelectorAll('.new-input')).map(i => i.value);

                const [last_name, first_name, middle_name] = (fullName + '  ').split(' ');
                axios.post('/api/veterinarians', {
                    last_name,
                    first_name,
                    middle_name,
                    specialization,
                    phone,
                    email
                }).then(() => loadVets())
                    .catch(() => showError("Ошибка при создании"));
            };

            tr.querySelector('.cancel-btn').onclick = () => tr.remove();
        };
    }

    loadVets();
}

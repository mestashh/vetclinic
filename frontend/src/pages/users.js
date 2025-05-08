export function initUsers() {
    const table = document.getElementById('usersTable');
    const addBtn = document.getElementById('addUserBtn');

    function showError(msg) {
        alert(msg);
    }

    // 2) функция загрузки и рендеринга
    function loadUsers() {
        axios.get('/api/users')
            .then(({ data }) => {
                const html = (data.data || []).map(u => `
                        <td class="px-4 py-2"><input disabled value="${u.last_name || ''}" class="w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="${u.first_name || ''}" class="w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="${u.middle_name || ''}" class="w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="${u.phone || ''}" class="w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="${u.email || ''}" class="w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="${u.address || ''}" class="w-full border-none"></td>
                        <td class="px-4 py-2 space-x-1">
                            <button class="edit-btn bg-blue-500 text-white px-2 rounded">Редактировать</button>
                            <button class="delete-btn bg-red-500 text-white px-2 rounded">Удалить</button>
                        </td>
                    </tr>`).join('');
                table.querySelector('tbody').innerHTML = html;
                attachEvents();
            })
            .catch(() => showError("Не удалось загрузить клиентов"));
    }

    function attachEvents() {
        table.querySelectorAll('.delete-btn').forEach(btn => {
            btn.onclick = () => {
                const id = btn.closest('tr').dataset.id;
                axios.delete(`/api/users/${id}`)
                    .then(loadUsers)
                    .catch(() => showError('Ошибка при удалении'));
            };
        });
        table.querySelectorAll('.edit-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                row.querySelectorAll('input').forEach(i => i.disabled = false);
                btn.textContent = 'Сохранить';
                btn.classList.replace('edit-btn', 'update-btn');
                attachEvents();
            };
        });
        table.querySelectorAll('.update-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                const id = row.dataset.id;
                const [last_name, first_name, middle_name, phone, email, address] =
                    Array.from(row.querySelectorAll('input')).map(i => i.value);
                axios.put(`/api/users/${id}`, { last_name, first_name, middle_name, phone, email, address })
                    .then(loadUsers)
                    .catch(() => showError('Ошибка при обновлении'));
            };
        });
    }

    if (addBtn) {
        addBtn.onclick = () => {
            const tr = document.createElement('tr');
            tr.className = 'bg-gray-100 border-b';
            tr.innerHTML = `
                <td class="px-4 py-2"><input placeholder="Фамилия" class="new-input w-full" /></td>
                <td class="px-4 py-2"><input placeholder="Имя" class="new-input w-full" /></td>
                <td class="px-4 py-2"><input placeholder="Отчество" class="new-input w-full" /></td>
                <td class="px-4 py-2"><input placeholder="Телефон" class="new-input w-full" /></td>
                <td class="px-4 py-2"><input placeholder="Email" class="new-input w-full" /></td>
                <td class="px-4 py-2"><input placeholder="Адрес" class="new-input w-full" /></td>
                <td class="px-4 py-2 space-x-1">
                    <button class="save-btn bg-green-500 text-white px-2 rounded">Сохранить</button>
                    <button class="cancel-btn bg-gray-500 text-white px-2 rounded">Отмена</button>
                </td>`;
            table.prepend(tr);
            tr.querySelector('.save-btn').onclick = () => {
                const [last_name, first_name, middle_name, phone, email, address] =
                    Array.from(tr.querySelectorAll('.new-input')).map(i => i.value);
                axios.post('/api/users', {
                    last_name,
                    first_name,
                    middle_name,
                    phone, email,
                    address
                }).then(() => loadUsers())
                    .catch(() => showError('Ошибка при создании'));
            };
            tr.querySelector('.cancel-btn').onclick = () => tr.remove();
        };
    }
    loadUsers();
}

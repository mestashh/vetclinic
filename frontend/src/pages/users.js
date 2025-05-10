export function initUsers() {
    const table = document.getElementById('usersTable');
    const addBtn = document.getElementById('addUserBtn');

    function showError(msg) {
        alert(msg);
    }

    function loadUsers() {
        axios.get('/api/users')
            .then(({ data }) => {
                const html = (data.data || []).map(u => `
                    <tr data-id="${u.id}">
                        <td><input disabled value="${u.last_name || ''}" class="w-full border-none"></td>
                        <td><input disabled value="${u.first_name || ''}" class="w-full border-none"></td>
                        <td><input disabled value="${u.middle_name || ''}" class="w-full border-none"></td>
                        <td><input disabled value="${u.phone || ''}" class="w-full border-none"></td>
                        <td><input disabled value="${u.email || ''}" class="w-full border-none"></td>
                        <td><input disabled value="${u.address || ''}" class="w-full border-none"></td>
                        <td><input disabled value="${u.passport || ''}" class="w-full border-none"></td>
                        <td class="action-buttons">
                            <button class="edit-btn btn-icon">✏️</button>
                            <button class="delete-btn btn-icon">🗑️</button>
                        </td>
                    </tr>`).join('');
                table.querySelector('tbody').innerHTML = html;
                attachEvents();
                applySearch(); // фильтрация после загрузки

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
                const id = row.dataset.id;
                row.querySelectorAll('input').forEach(i => i.disabled = false);

                const cell = btn.closest('td');
                cell.innerHTML = `
                    <button class="update-btn btn-icon confirm-btn">✅</button>
                    <button class="cancel-btn btn-icon cancel-btn">❌</button>
                `;

                cell.querySelector('.update-btn').onclick = () => {
                    const [last_name, first_name, middle_name, phone, email, address, passport] =
                        Array.from(row.querySelectorAll('input')).map(i => i.value);
                    axios.put(`/api/users/${id}`, { last_name, first_name, middle_name, phone, email, address, passport })
                        .then(loadUsers)
                        .catch(() => showError('Ошибка при обновлении'));
                };

                cell.querySelector('.cancel-btn').onclick = () => loadUsers();
            };
        });
    }

    if (addBtn) {
        addBtn.onclick = () => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><input placeholder="Фамилия" class="new-input w-full" /></td>
                <td><input placeholder="Имя" class="new-input w-full" /></td>
                <td><input placeholder="Отчество" class="new-input w-full" /></td>
                <td><input placeholder="Телефон" class="new-input w-full" /></td>
                <td><input placeholder="Email" class="new-input w-full" /></td>
                <td><input placeholder="Адрес" class="new-input w-full" /></td>
                <td><input placeholder="Паспорт" class="new-input w-full" /></td>
                <td class="action-buttons">
                    <button class="save-btn btn-icon confirm-btn">✅</button>
                    <button class="cancel-btn btn-icon cancel-btn">❌</button>
                </td>`;
            table.querySelector('tbody').prepend(tr);

            tr.querySelector('.save-btn').onclick = () => {
                const [last_name, first_name, middle_name, phone, email, address, passport] =
                    Array.from(tr.querySelectorAll('.new-input')).map(i => i.value);
                axios.post('/api/users', {
                    last_name, first_name, middle_name, phone, email, address, passport
                }).then(loadUsers)
                    .catch(() => showError('Ошибка при создании'));
            };

            tr.querySelector('.cancel-btn').onclick = () => tr.remove();
        };
    }
    function applySearch() {
        const query = document.getElementById('searchInput')?.value?.toLowerCase() || '';
        table.querySelectorAll('tbody tr').forEach(row => {
            const text = Array.from(row.querySelectorAll('input'))
                .map(i => i.value.toLowerCase())
                .join(' ');
            row.style.display = text.includes(query) ? '' : 'none';
        });
    }
    document.getElementById('searchInput')?.addEventListener('input', applySearch);


    loadUsers();
}

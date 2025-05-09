export function initChange() {
    const table = document.getElementById('rolesTable');
    if (!table) {
        console.warn('rolesTable не найден');
        return;
    }

    function showError(msg) {
        alert(msg);
    }

    function loadUsers() {
        console.log('Загружаем пользователей...');
        axios.get('/api/users')
            .then(({ data }) => {
                console.log('Полученные данные:', data);
                const users = data.data || [];

                const html = users.map(user => {
                    const fullName = [user.last_name, user.first_name, user.middle_name]
                        .filter(Boolean).join(' ');

                    return `
                        <tr data-id="${user.id}">
                            <td>${fullName}</td>
                            <td>${user.email}</td>
                            <td>${user.phone}</td>
                            <td class="action-buttons">
                                <select class="role-select">
                                    <option value="client" ${user.role === 'client' ? 'selected' : ''}>Клиент</option>
                                    <option value="vet" ${user.role === 'vet' ? 'selected' : ''}>Ветеринар</option>
                                    <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Админ</option>
                                    <option value="superadmin" ${user.role === 'superadmin' ? 'selected' : ''}>Супер-админ</option>
                                </select>
                                <button class="save-role-btn btn-icon confirm-btn">✅</button>
                            </td>
                        </tr>`;
                }).join('');

                table.querySelector('tbody').innerHTML = html;
                attachEvents();
            })
            .catch(err => {
                console.error('Ошибка при загрузке пользователей:', err);
                showError("Не удалось загрузить пользователей");
            });
    }

    function attachEvents() {
        table.querySelectorAll('.save-role-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                const id = row.dataset.id;
                const newRole = row.querySelector('.role-select').value;

                console.log(`Изменяем роль пользователя ${id} на "${newRole}"`);

                axios.put(`/api/users/${id}/role`, { role: newRole })
                    .then(() => {
                        console.log('Роль обновлена успешно');
                        alert('Роль обновлена');
                    })
                    .catch(err => {
                        console.error('Ошибка при обновлении роли:', err);
                        showError('Ошибка при обновлении роли');
                    });
            };
        });
    }
    function applySearch() {
        const query = document.getElementById('searchInput')?.value?.toLowerCase() || '';
        document.querySelectorAll('#rolesTable tbody tr').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    }

    document.getElementById('searchInput')?.addEventListener('input', applySearch);
    applySearch();
    loadUsers();
}

export function initChange() {
    const table = document.getElementById('rolesTable');
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    let allUsers = [];

    if (!table) return;

    function showError(msg) {
        alert(msg);
    }

    function renderUsers(users) {
        const html = users.map(user => {
            const fullName = [user.last_name, user.first_name, user.middle_name].filter(Boolean).join(' ');
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
    }

    function loadUsers() {
        axios.get('/api/users')
            .then(({ data }) => {
                allUsers = data.data || [];
                applyFilters();
            })
            .catch(err => {
                console.error('Ошибка при загрузке пользователей:', err);
                showError("Не удалось загрузить пользователей");
            });
    }

    function applyFilters() {
        const query = searchInput?.value?.toLowerCase() || '';
        const role = roleFilter?.value;

        const filtered = allUsers.filter(user => {
            const text = [user.first_name, user.last_name, user.middle_name, user.email, user.phone]
                .filter(Boolean)
                .join(' ')
                .toLowerCase();
            const matchesSearch = text.includes(query);
            const matchesRole = !role || user.role === role;
            return matchesSearch && matchesRole;
        });

        renderUsers(filtered);
    }

    function attachEvents() {
        table.querySelectorAll('.save-role-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                const id = row.dataset.id;
                const newRole = row.querySelector('.role-select').value;
                axios.put(`/api/users/${id}/role`, { role: newRole })
                    .then(() => alert('Роль обновлена'))
                    .catch(() => showError('Ошибка при обновлении роли'));
            };
        });
    }

    searchInput?.addEventListener('input', applyFilters);
    roleFilter?.addEventListener('change', applyFilters);
    loadUsers();
}

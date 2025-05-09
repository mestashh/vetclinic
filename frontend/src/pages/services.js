export function initServices() {
    const table = document.getElementById('servicesTable');
    const addBtn = document.getElementById('addServiceBtn');

    const isAdmin = window.currentUserRole === 'admin' || window.currentUserRole === 'superadmin';

    if (!table) return;

    function showError(msg) {
        alert(msg);
    }

    function loadServices() {
        axios.get('/api/procedures')
            .then(response => {
                const services = Array.isArray(response.data)
                    ? response.data
                    : response.data.data || [];

                const html = services.map(s => {
                    const actions = isAdmin
                        ? `
                            <button class="edit-btn btn-icon edit">✏️</button>
                            <button class="delete-btn btn-icon delete">🗑️</button>
                          `
                        : '';

                    return `
                        <tr data-id="${s.id}">
                            <td><input disabled value="${s.name}" class="service-input w-full border-none"></td>
                            <td><input disabled value="${s.description || ''}" class="service-input w-full border-none"></td>
                            <td><input disabled value="${s.price}" class="service-input w-full border-none"></td>
                            <td><input disabled value="${s.quantity}" class="service-input w-full border-none"></td>
                            ${isAdmin ? `<td class="action-buttons">${actions}</td>` : ''}
                        </tr>`;
                }).join('');

                table.querySelector('tbody').innerHTML = html;
                applySearch();
                if (isAdmin) attachEvents();
            })
            .catch(() => showError('Ошибка загрузки услуг'));
    }

    function attachEvents() {
        table.querySelectorAll('.delete-btn').forEach(btn => {
            btn.onclick = () => {
                const id = btn.closest('tr').dataset.id;
                axios.delete(`/api/procedures/${id}`)
                    .then(loadServices)
                    .catch(() => showError('Ошибка при удалении'));
            };
        });

        table.querySelectorAll('.edit-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                row.querySelectorAll('input').forEach(i => i.disabled = false);

                const cell = btn.closest('td');
                cell.innerHTML = `
                    <button class="update-btn btn-icon confirm">✅</button>
                    <button class="cancel-btn btn-icon cancel">❌</button>
                `;

                cell.querySelector('.update-btn').onclick = () => {
                    const [name, description, price, quantity] = Array.from(row.querySelectorAll('input')).map(i => i.value);
                    const id = row.dataset.id;
                    axios.put(`/api/procedures/${id}`, {
                        name,
                        description,
                        price: parseFloat(price),
                        quantity: parseInt(quantity)
                    })
                        .then(loadServices)
                        .catch(() => showError('Ошибка при обновлении'));
                };

                cell.querySelector('.cancel-btn').onclick = () => loadServices();
            };
        });
    }

    if (isAdmin && addBtn) {
        addBtn.onclick = () => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><input placeholder="Название" class="new-input w-full"></td>
                <td><input placeholder="Описание" class="new-input w-full"></td>
                <td><input placeholder="Цена" type="number" class="new-input w-full"></td>
                <td><input placeholder="Количество" type="number" class="new-input w-full"></td>
                <td class="action-buttons">
                    <button class="save-btn btn-icon confirm">✅</button>
                    <button class="cancel-btn btn-icon cancel">❌</button>
                </td>
            `;

            tr.querySelector('.save-btn').onclick = () => {
                const [name, description, price, quantity] = Array.from(tr.querySelectorAll('.new-input')).map(i => i.value);
                axios.post('/api/procedures', {
                    name,
                    description,
                    price: parseFloat(price),
                    quantity: parseInt(quantity)
                })
                    .then(loadServices)
                    .catch(() => showError('Ошибка при создании'));
            };

            tr.querySelector('.cancel-btn').onclick = () => tr.remove();
            table.querySelector('tbody').prepend(tr);
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
    loadServices();
}

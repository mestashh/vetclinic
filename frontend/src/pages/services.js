export function initServices() {
    console.log('[services.js] initServices загружен');

    const table = document.getElementById('servicesTable');
    const addBtn = document.getElementById('addServiceBtn');

    if (!table) {
        console.error('[services.js] servicesTable не найден в DOM!');
        return;
    }

    const isAdmin = window.currentUserRole === 'admin' || window.currentUserRole === 'superadmin';

    function showError(msg) {
        alert(msg);
    }

    function loadServices() {
        console.log('[services.js] загружаем список услуг...');
        axios.get('/api/procedures')
            .then(response => {
                let services;
                if (Array.isArray(response.data)) {
                    services = response.data;
                } else if (Array.isArray(response.data.data)) {
                    services = response.data.data;
                } else {
                    services = [];
                }

                const html = services.map(s => {
                    let actionButtons = '';

                    if (isAdmin) {
                        actionButtons = `
                            <button class="edit-btn bg-blue-500 text-white px-2 rounded">Редактировать</button>
                            <button class="delete-btn bg-red-500 text-white px-2 rounded">Удалить</button>
                        `;
                    }

                    return `
                    <tr data-id="${s.id}" class="bg-white border-b hover:bg-gray-50">
                        <td class="px-4 py-2">
                          <input disabled value="${s.name}" class="service-input w-full border-none">
                        </td>
                        <td class="px-4 py-2">
                          <input disabled value="${s.description || ''}" class="service-input w-full border-none">
                        </td>
                        <td class="px-4 py-2">
                          <input disabled value="${s.price}" class="service-input w-full border-none">
                        </td>
                        <td class="px-4 py-2 space-x-1">
                            ${actionButtons}
                        </td>
                    </tr>`;
                }).join('');

                table.querySelector('tbody').innerHTML = html;
                attachEvents();
            })
            .catch(err => {
                console.error('[services.js] ошибка загрузки услуг:', err);
                showError("Не удалось загрузить услуги");
            });
    }

    function attachEvents() {
        table.querySelectorAll('.delete-btn').forEach(btn => {
            btn.onclick = () => {
                const id = btn.closest('tr').dataset.id;
                axios.delete(`/api/procedures/${id}`)
                    .then(loadServices)
                    .catch(() => showError("Ошибка при удалении"));
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
                const [name, description, price] = Array.from(row.querySelectorAll('input')).map(i => i.value);

                axios.put(`/api/procedures/${id}`, {
                    name,
                    description,
                    price: parseFloat(price)
                })
                    .then(loadServices)
                    .catch(() => showError("Ошибка при обновлении"));
            };
        });
    }

    if (isAdmin && addBtn) {
        addBtn.onclick = () => {
            const tr = document.createElement('tr');
            tr.className = 'bg-gray-100 border-b';
            tr.innerHTML = `
                <td class="px-4 py-2"><input placeholder="Название" class="new-input w-full"></td>
                <td class="px-4 py-2"><input placeholder="Описание" class="new-input w-full"></td>
                <td class="px-4 py-2"><input placeholder="Цена" type="number" class="new-input w-full"></td>
                <td class="px-4 py-2 space-x-1">
                    <button class="save-btn bg-green-500 text-white px-2 rounded">Сохранить</button>
                    <button class="cancel-btn bg-gray-500 text-white px-2 rounded">Отмена</button>
                </td>
            `;

            tr.querySelector('.save-btn').onclick = () => {
                const [name, description, price] = Array.from(tr.querySelectorAll('.new-input')).map(i => i.value);
                axios.post('/api/procedures', {
                    name,
                    description,
                    price: parseFloat(price)
                })
                    .then(loadServices)
                    .catch(() => showError("Ошибка при создании"));
            };

            tr.querySelector('.cancel-btn').onclick = () => tr.remove();

            table.querySelector('tbody').prepend(tr);
        };
    }

    loadServices();
}

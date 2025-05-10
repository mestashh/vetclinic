import axios from 'axios';

export function initServices() {
    const table = document.getElementById('servicesTable');
    const addBtn = document.getElementById('addServiceBtn');
    const searchInput = document.getElementById('searchInput');
    const isSuperAdmin = window.currentUserRole === 'superadmin';
    const isClient = window.currentUserRole === 'client';
    let services = [];

    const LOW_STOCK_THRESHOLD = 10;

    function showError(msg) {
        alert(msg);
        console.error(msg);
    }

    function renderServices() {
        const query = searchInput?.value?.toLowerCase() || '';
        const filtered = services.filter(s => s.name.toLowerCase().includes(query));

        const html = filtered.map(service => `
            <tr data-id="${service.id}" class="service-row">
                <td>
                    <button class="toggle-items" style="margin-right: 5px;">▶️</button>
                    <span class="service-name">${service.name}</span>
                </td>
                <td class="service-desc">${service.description || ''}</td>
                ${isSuperAdmin ? `
                <td class="action-buttons">
                    <button class="add-variant-btn btn-icon confirm-btn">➕ Вариант</button>
                    <button class="edit-btn btn-icon edit-btn">✏️</button>
                    <button class="delete-btn btn-icon cancel-btn">🗑️</button>
                </td>` : ''}
            </tr>
        `).join('');

        table.querySelector('tbody').innerHTML = html;
        if (isSuperAdmin) attachEvents();
        setupToggleEvents(isSuperAdmin);
    }

    function setupToggleEvents(isSuperAdmin) {
        table.querySelectorAll('.toggle-items').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                const id = row.dataset.id;
                const service = services.find(s => s.id == id);

                const nextRow = row.nextElementSibling;
                if (nextRow && nextRow.classList.contains('variants-row')) {
                    nextRow.remove();
                    btn.textContent = '▶️';
                    return;
                }

                btn.textContent = '▼';
                const variantRow = document.createElement('tr');
                variantRow.classList.add('variants-row');
                variantRow.innerHTML = `
                <td colspan="4">
                    <ul style="padding-left: 1rem; margin-top: 0.5rem;">
                        ${(service.items || []).map(item => {
                    const lowStock = item.quantity < LOW_STOCK_THRESHOLD;
                    return `
                            <li data-item-id="${item.id}" style="margin-bottom: 0.5rem;">
                                <span class="item-content">
                                    <strong>${item.name}</strong> — ${item.price}₽ 
                                    ${!isClient ? `(${item.quantity} шт.)` : ''}
                                    ${lowStock ? '<span style="color:red;">🚨 Мало на складе</span>' : ''}
                                    ${item.description ? ` — ${item.description}` : ''}
                                </span>
                                ${lowStock ? `
                                    <button class="order-btn btn-icon confirm-btn" style="margin-left:1rem;">📦 Заявка</button>
                                ` : ''}
                                ${isSuperAdmin ? `
                                <span class="variant-buttons" style="margin-left: 1rem;">
                                    <button class="edit-variant btn-icon edit-btn">✏️</button>
                                    <button class="delete-variant btn-icon cancel-btn">🗑️</button>
                                </span>` : ''}
                            </li>
                        `}).join('') || '<em>Нет вариантов</em>'}
                    </ul>
                </td>
            `;
                row.after(variantRow);

                if (isSuperAdmin) {
                    variantRow.querySelectorAll('.edit-variant').forEach(editBtn => {
                        editBtn.onclick = () => {
                            const li = editBtn.closest('li');
                            const id = li.dataset.itemId;
                            const span = li.querySelector('.item-content');

                            const item = services.flatMap(s => s.items || []).find(i => i.id == id);
                            if (!item) return;

                            span.innerHTML = `
                                <input type="text" value="${item.name}" class="v-name" style="width:120px;" />
                                <input type="number" value="${item.price}" class="v-price" style="width:80px;" />
                                <input type="number" value="${item.quantity}" class="v-qty" style="width:80px;" />
                                <input type="text" value="${item.description || ''}" class="v-desc" style="width:200px;" />
                                <button class="btn-icon confirm-btn save-v">✅</button>
                                <button class="btn-icon cancel-btn cancel-v">❌</button>
                            `;

                            span.querySelector('.cancel-v').onclick = () => loadServices();

                            span.querySelector('.save-v').onclick = () => {
                                const name = span.querySelector('.v-name').value.trim();
                                const price = parseFloat(span.querySelector('.v-price').value);
                                const quantity = parseInt(span.querySelector('.v-qty').value);
                                const description = span.querySelector('.v-desc').value.trim();

                                axios.put(`/api/items/${id}`, {
                                    name, price, quantity, description
                                })
                                    .then(() => loadServices())
                                    .catch(() => showError('Ошибка при обновлении варианта'));
                            };
                        };
                    });

                    variantRow.querySelectorAll('.delete-variant').forEach(delBtn => {
                        delBtn.onclick = () => {
                            const li = delBtn.closest('li');
                            const id = li.dataset.itemId;
                            if (confirm('Удалить этот вариант?')) {
                                axios.delete(`/api/items/${id}`)
                                    .then(() => loadServices())
                                    .catch(() => showError('Ошибка при удалении варианта'));
                            }
                        };
                    });
                }

                // Заявка
                variantRow.querySelectorAll('.order-btn').forEach(btn => {
                    btn.onclick = () => {
                        const li = btn.closest('li');
                        const id = li.dataset.itemId;
                        const comment = prompt('Комментарий к заявке (необязательно):');
                        const quantity = prompt('Сколько заказать?', '10');
                        if (!quantity || isNaN(quantity) || quantity <= 0) {
                            alert('Неверное количество');
                            return;
                        }

                        axios.post('/api/orders', {
                            item_id: id,
                            quantity: quantity,
                            comment
                        }).then(() => {
                            alert('Заявка отправлена!');
                        }).catch(() => {
                            showError('Ошибка при создании заявки');
                        });
                    };
                });
            };
        });
    }

    function attachEvents() {
        table.querySelectorAll('.delete-btn').forEach(btn => {
            btn.onclick = () => {
                const id = btn.closest('tr').dataset.id;
                axios.delete(`/api/services/${id}`).then(loadServices).catch(() => showError('Ошибка при удалении'));
            };
        });

        table.querySelectorAll('.add-variant-btn').forEach(btn => {
            btn.onclick = () => {
                const parentRow = btn.closest('tr');
                const serviceId = parentRow.dataset.id;

                const existing = parentRow.nextElementSibling;
                if (existing && existing.classList.contains('variant-form-row')) {
                    existing.remove();
                    return;
                }

                const formRow = document.createElement('tr');
                formRow.classList.add('variant-form-row');
                formRow.innerHTML = `
                    <td colspan="4">
                        <div style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center;">
                            <input type="text" placeholder="Название" class="variant-name" style="flex:1;" />
                            <input type="number" placeholder="Цена" class="variant-price" style="width:120px;" />
                            <input type="number" placeholder="Кол-во" class="variant-qty" style="width:100px;" />
                            <input type="text" placeholder="Описание" class="variant-desc" style="flex:2;" />
                            <button class="btn-icon confirm-btn save-variant">Сохранить</button>
                            <button class="btn-icon cancel-btn cancel-variant">Отмена</button>
                        </div>
                    </td>
                `;
                parentRow.after(formRow);

                formRow.querySelector('.cancel-variant').onclick = () => formRow.remove();

                formRow.querySelector('.save-variant').onclick = () => {
                    const name = formRow.querySelector('.variant-name').value;
                    const price = parseFloat(formRow.querySelector('.variant-price').value);
                    const quantity = parseInt(formRow.querySelector('.variant-qty').value);
                    const description = formRow.querySelector('.variant-desc').value;

                    if (!name || isNaN(price) || isNaN(quantity)) {
                        return showError('Заполните все обязательные поля');
                    }

                    axios.post(`/api/services/${serviceId}/items`, {
                        name, price, quantity, description
                    })
                        .then(() => loadServices())
                        .catch(() => showError('Ошибка при добавлении варианта'));
                };
            };
        });

        table.querySelectorAll('.edit-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                const id = row.dataset.id;
                const nameCell = row.querySelector('.service-name');
                const descCell = row.querySelector('.service-desc');

                const originalName = nameCell.textContent.trim();
                const originalDesc = descCell.textContent.trim();

                nameCell.innerHTML = `<input type="text" value="${originalName}" class="edit-input" style="width: 100%" />`;
                descCell.innerHTML = `<textarea class="edit-input" style="width: 100%">${originalDesc}</textarea>`;

                const actionCell = row.querySelector('.action-buttons');
                actionCell.innerHTML = `
                    <button class="save-edit btn-icon confirm-btn">✅</button>
                    <button class="cancel-edit btn-icon cancel-btn">❌</button>
                `;

                actionCell.querySelector('.save-edit').onclick = () => {
                    const newName = nameCell.querySelector('input').value.trim();
                    const newDesc = descCell.querySelector('textarea').value.trim();

                    axios.put(`/api/services/${id}`, {
                        name: newName,
                        description: newDesc
                    })
                        .then(() => loadServices())
                        .catch(() => showError('Ошибка при обновлении услуги'));
                };

                actionCell.querySelector('.cancel-edit').onclick = () => {
                    loadServices();
                };
            };
        });
    }

    if (isSuperAdmin && addBtn) {
        addBtn.onclick = () => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><input type="text" placeholder="Название" class="new-input" /></td>
                <td><textarea placeholder="Описание" class="new-input"></textarea></td>
                <td colspan="2">
                    <div class="action-buttons">
                        <button class="btn-icon confirm-btn" id="saveNewService">Сохранить</button>
                        <button class="btn-icon cancel-btn" id="cancelNewService">Отмена</button>
                    </div>
                </td>
            `;
            table.querySelector('tbody').prepend(tr);

            tr.querySelector('#cancelNewService').onclick = () => tr.remove();

            tr.querySelector('#saveNewService').onclick = () => {
                const name = tr.querySelector('input').value.trim();
                const description = tr.querySelector('textarea').value.trim();
                if (!name) return showError('Название обязательно');
                axios.post('/api/services', { name, description })
                    .then(loadServices)
                    .catch(() => showError('Ошибка при создании услуги'));
            };
        };
    }

    searchInput?.addEventListener('input', () => renderServices());

    function loadServices() {
        axios.get('/api/services?include=items')
            .then(({ data }) => {
                services = data.data || [];
                renderServices();
            })
            .catch(() => showError('Ошибка загрузки услуг'));
    }

    loadServices();
}

document.addEventListener('DOMContentLoaded', initServices);

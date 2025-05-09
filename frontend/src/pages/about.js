import axios from 'axios';

export function initAbout() {
    const table = document.getElementById('petsTable');
    const addBtn = document.getElementById('addPetBtn');

    function showError(msg) {
        alert(msg);
    }

    function attachPetEvents() {
        // Удаление питомца
        table.querySelectorAll('.delete-pet-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                if (!row) return;
                const id = row.dataset.id;
                if (!confirm('Вы уверены, что хотите удалить этого питомца?')) return;
                axios.delete(`/api/animals/${id}`)
                    .then(() => loadPets())
                    .catch(() => showError('Ошибка при удалении питомца'));
            };
        });

        // Редактирование питомца
        table.querySelectorAll('.edit-pet-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                const id = row.dataset.id;
                const inputs = row.querySelectorAll('input');
                inputs.forEach(i => i.disabled = false);

                const btnContainer = row.querySelector('.pet-buttons');
                btnContainer.innerHTML = `
                    <button class="confirm-pet-btn icon-button icon-edit">✅</button>
                    <button class="cancel-pet-btn icon-button icon-delete">❌</button>
                `;

                // Подтвердить обновление
                btnContainer.querySelector('.confirm-pet-btn').onclick = () => {
                    const [name, species, breed, age] = Array.from(inputs).map(i => i.value);
                    axios.put(`/api/animals/${id}`, { name, species, breed, age })
                        .then(() => loadPets())
                        .catch(() => showError('Ошибка при обновлении питомца'));
                };

                // Отмена редактирования
                btnContainer.querySelector('.cancel-pet-btn').onclick = () => loadPets();
            };
        });
    }

    function loadPets() {
        axios.get('/api/animals', { params: { t: Date.now() } })
            .then(({ data }) => {
                const pets = data.data || [];
                const myPets = pets.filter(p => Number(p.client_id) === Number(window.currentUserId));

                const html = myPets.map(p => `
                    <tr data-id="${p.id}">
                        <td><input disabled value="${p.name}" class="pet-input w-full border-none"/></td>
                        <td><input disabled value="${p.species}" class="pet-input w-full border-none"/></td>
                        <td><input disabled value="${p.breed || ''}" class="pet-input w-full border-none"/></td>
                        <td><input disabled value="${p.age || ''}" class="pet-input w-full border-none"/></td>
                        <td>
                            <div class="pet-buttons">
                                <button class="edit-pet-btn icon-button icon-edit">✏️</button>
                                <button class="delete-pet-btn icon-button icon-delete">🗑️</button>
                            </div>
                        </td>
                    </tr>
                `).join('');

                table.querySelector('tbody').innerHTML = html;
                attachPetEvents();
            })
            .catch(() => showError('Не удалось загрузить питомцев'));
    }

    // Добавление нового питомца
    if (addBtn) {
        addBtn.onclick = () => {
            const tr = document.createElement('tr');
            tr.className = 'bg-gray-100 border-b';
            tr.innerHTML = `
                <td><input placeholder="Кличка" class="new-pet-input w-full" /></td>
                <td><input placeholder="Вид" class="new-pet-input w-full" /></td>
                <td><input placeholder="Порода" class="new-pet-input w-full" /></td>
                <td><input placeholder="Возраст" type="number" min="0" class="new-pet-input w-full" /></td>
                <td>
                    <div class="pet-buttons">
                        <button class="save-pet-btn icon-button icon-edit">✅</button>
                        <button class="cancel-pet-btn icon-button icon-delete">❌</button>
                    </div>
                </td>
            `;
            table.querySelector('tbody').prepend(tr);

            tr.querySelector('.save-pet-btn').onclick = () => {
                const [name, species, breed, age] = Array.from(tr.querySelectorAll('.new-pet-input')).map(i => i.value);
                axios.post('/api/animals', {
                    name,
                    species,
                    breed,
                    age,
                    client_id: window.currentUserId
                })
                    .then(() => loadPets())
                    .catch(() => showError('Ошибка при создании питомца'));
            };

            tr.querySelector('.cancel-pet-btn').onclick = () => tr.remove();
        };
    }

    // Редактирование профиля пользователя
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');

    if (editBtn && saveBtn) {
        editBtn.onclick = () => {
            document.querySelectorAll('#userForm input').forEach(i => i.disabled = false);
            editBtn.style.display = 'none';
            saveBtn.style.display = 'inline-block';
        };

        saveBtn.onclick = () => {
            const form = document.getElementById('userForm');
            const data = {};
            form.querySelectorAll('input[name]').forEach(input => {
                data[input.name] = input.value;
            });

            axios.post('/profile/update', data)
                .then(() => location.reload())
                .catch(() => alert('Ошибка при сохранении профиля'));
        };
    }

    loadPets();
}

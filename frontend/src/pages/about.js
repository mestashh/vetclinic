import axios from 'axios';

export function initAbout() {
    const table = document.getElementById('petsTable');
    const addBtn = document.getElementById('addPetBtn');

    function showError(msg) {
        alert(msg);
    }

    function loadPets() {
        axios.get('/api/animals')
            .then(({ data }) => {
                const pets = data.data || [];

                // Фильтрация питомцев по текущему клиенту
                const myPets = pets.filter(p => p.client_id === window.currentUserId);

                const html = myPets.map(p => `
                    <tr data-id="${p.id}">
                        <td><input disabled value="${p.name}" class="pet-input w-full border-none"></td>
                        <td><input disabled value="${p.species}" class="pet-input w-full border-none"></td>
                        <td><input disabled value="${p.breed || ''}" class="pet-input w-full border-none"></td>
                        <td><input disabled value="${p.age || ''}" class="pet-input w-full border-none"></td>
                        <td class="space-x-1">
                            <button class="edit-pet-btn btn-primary px-2 py-1 rounded">Редактировать</button>
                            <button class="delete-pet-btn btn-secondary px-2 py-1 rounded">Удалить</button>
                        </td>
                    </tr>
                `).join('');
                table.querySelector('tbody').innerHTML = html;
                attachPetEvents();
            })
            .catch(() => showError("Не удалось загрузить питомцев"));
    }

    function attachPetEvents() {
        // Удаление питомца
        table.querySelectorAll('.delete-pet-btn').forEach(btn => {
            btn.onclick = () => {
                const id = btn.closest('tr').dataset.id;
                axios.delete(`/api/animals/${id}`)
                    .then(() => loadPets())
                    .catch(() => showError("Ошибка при удалении питомца"));
            };
        });

        // Редактирование питомца
        table.querySelectorAll('.edit-pet-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                row.querySelectorAll('input').forEach(i => i.disabled = false);
                btn.textContent = 'Сохранить';
                btn.classList.replace('edit-pet-btn', 'update-pet-btn');
                attachPetEvents();
            };
        });

        // Сохранение изменений питомца
        table.querySelectorAll('.update-pet-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                const id = row.dataset.id;
                const [name, species, breed, age] = Array.from(row.querySelectorAll('input')).map(i => i.value);
                axios.put(`/api/animals/${id}`, { name, species, breed, age })
                    .then(() => loadPets())
                    .catch(() => showError("Ошибка при обновлении питомца"));
            };
        });
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
                <td><input placeholder="Возраст" class="new-pet-input w-full" type="number" min="0" /></td>
                <td class="space-x-1">
                    <button class="save-pet-btn btn-green px-2 py-1 rounded">Сохранить</button>
                    <button class="cancel-pet-btn btn-gray px-2 py-1 rounded">Отмена</button>
                </td>
            `;
            table.querySelector('tbody').prepend(tr);

            // Сохранение нового питомца
            tr.querySelector('.save-pet-btn').onclick = () => {
                const [name, species, breed, age] = Array.from(tr.querySelectorAll('.new-pet-input')).map(i => i.value);
                axios.post('/api/animals', { name, species, breed, age, client_id: window.currentUserId })
                    .then(() => loadPets())
                    .catch(() => showError("Ошибка при создании питомца"));
            };

            // Отмена добавления питомца
            tr.querySelector('.cancel-pet-btn').onclick = () => tr.remove();
        };
    }

    // Изначальная загрузка питомцев
    loadPets();
}

import axios from 'axios';

export function initAbout() {
    const table = document.getElementById('petsTable');
    const addBtn = document.getElementById('addPetBtn');

    function showError(msg) {
        alert(msg);
        console.error(msg);
    }

    function attachPetEvents() {
        table.querySelectorAll('.delete-pet-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                const id = row.dataset.id;
                if (!confirm('Вы уверены, что хотите удалить этого питомца?')) return;
                axios.delete(`/api/animals/${id}`)
                    .then(() => {
                        console.log(`Питомец с id=${id} удалён.`);
                        loadPets();
                    })
                    .catch((err) => showError('Ошибка при удалении питомца', err));
            };
        });

        table.querySelectorAll('.edit-pet-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                const id = row.dataset.id;
                console.log(`Начинаем редактирование питомца id=${id}`);

                const inputs = row.querySelectorAll('input');
                inputs.forEach(i => i.disabled = false);

                const btnContainer = row.querySelector('.pet-buttons');
                btnContainer.innerHTML = `
                    <button class="confirm-pet-btn icon-button icon-edit">✅</button>
                    <button class="cancel-pet-btn icon-button icon-delete">❌</button>
                `;

                btnContainer.querySelector('.confirm-pet-btn').onclick = () => {
                    const updatedInputs = row.querySelectorAll('input');
                    const updatedData = {
                        name: updatedInputs[0].value,
                        species: updatedInputs[1].value,
                        breed: updatedInputs[2].value,
                        age: updatedInputs[3].value,
                    };

                    console.log('Отправляем PUT-запрос с данными:', updatedData, `на /api/animals/${id}`);

                    axios.put(`/api/animals/${id}`, updatedData)
                        .then((response) => {
                            console.log('Ответ сервера:', response.data);
                            loadPets();
                        })
                        .catch((err) => showError('Ошибка при обновлении питомца', err));
                };

                btnContainer.querySelector('.cancel-pet-btn').onclick = () => {
                    console.log(`Отмена редактирования питомца id=${id}`);
                    loadPets();
                };
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
            .catch((err) => showError('Не удалось загрузить питомцев', err));
    }


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
                console.log('Добавляем нового питомца:', { name, species, breed, age });

                axios.post('/api/animals', {
                    name, species, breed, age,
                    client_id: window.currentUserId
                })
                    .then(() => loadPets())
                    .catch((err) => showError('Ошибка при создании питомца', err));
            };

            tr.querySelector('.cancel-pet-btn').onclick = () => {
                console.log('Отмена добавления питомца.');
                tr.remove();
            };
        };
    }

    loadPets();
}

import axios from 'axios';

export function initAbout() {
    const table = document.getElementById('petsTable');
    const addBtn = document.getElementById('addPetBtn');
    const form = document.getElementById('userForm');
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');

    function showError(msg, err = null) {
        alert(msg);
        console.error(msg, err);
    }

    // 👤 Личная информация
    if (editBtn && saveBtn && form) {
        editBtn.onclick = () => {
            form.querySelectorAll('input').forEach(input => input.disabled = false);
            editBtn.style.display = 'none';
            saveBtn.style.display = 'inline-block';
        };

        saveBtn.onclick = async () => {
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                await axios.put(`/api/users/${window.currentUserId}`, data);
                location.reload();
            } catch (err) {
                showError('Ошибка при сохранении профиля', err);
            }
        };
    }

    // 🐾 Питомцы
    function attachPetEvents() {
        table.querySelectorAll('.delete-pet-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                const id = row.dataset.id;
                if (!confirm('Вы уверены, что хотите удалить этого питомца?')) return;

                axios.delete(`/api/animals/${id}`)
                    .then(() => loadPets())
                    .catch(err => showError('Ошибка при удалении питомца', err));
            };
        });

        table.querySelectorAll('.edit-pet-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                const id = row.dataset.id;

                row.querySelectorAll('input').forEach(i => i.disabled = false);

                const btnContainer = row.querySelector('.pet-buttons');
                btnContainer.innerHTML = `
                    <button class="confirm-pet-btn icon-button icon-edit">✅</button>
                    <button class="cancel-pet-btn icon-button icon-delete">❌</button>
                `;

                btnContainer.querySelector('.confirm-pet-btn').onclick = () => {
                    const inputs = row.querySelectorAll('input');
                    const updated = {
                        name: inputs[0].value,
                        species: inputs[1].value,
                        breed: inputs[2].value,
                        age: inputs[3].value,
                        client_id: window.currentUserId
                    };

                    axios.put(`/api/animals/${id}`, updated)
                        .then(() => loadPets())
                        .catch(err => showError('Ошибка при обновлении питомца', err));
                };

                btnContainer.querySelector('.cancel-pet-btn').onclick = () => {
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
            .catch(err => showError('Не удалось загрузить питомцев', err));
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
                axios.post('/api/animals', {
                    name, species, breed, age,
                    client_id: window.currentUserId
                })
                    .then(() => loadPets())
                    .catch(err => showError('Ошибка при создании питомца', err));
            };

            tr.querySelector('.cancel-pet-btn').onclick = () => tr.remove();
        };
    }

    loadPets();
}

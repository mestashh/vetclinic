import axios from 'axios';

export function initPets() {
    const table = document.getElementById('petsTable');
    const addBtn = document.getElementById('addPetBtn');
    let users = [];

    function showError(m) {
        alert(m);
    }

    async function loadRefs() {
        try {
            const res = await axios.get('/api/users');
            users = res.data.data || [];
        } catch {
            showError("Не удалось загрузить клиентов");
        }
    }

    function loadPets() {
        axios.get('/api/animals')
            .then(({ data }) => {
                const pets = data.data || [];

                const html = pets.map(p => {
                    const userName = p.client
                        ? [p.client.last_name, p.client.first_name, p.client.middle_name].filter(Boolean).join(' ')
                        : '';

                    return `
                        <tr data-id="${p.id}" class="bg-white border-b hover:bg-gray-50">
                            <td class="px-4 py-2"><input disabled value="${p.name}" class="w-full border-none"></td>
                            <td class="px-4 py-2"><input disabled value="${p.species}" class="w-full border-none"></td>
                            <td class="px-4 py-2"><input disabled value="${p.breed || ''}" class="w-full border-none"></td>
                            <td class="px-4 py-2"><input disabled value="${p.age || ''}" class="w-full border-none"></td>
                            <td class="px-4 py-2"><input disabled value="${userName}" class="w-full border-none"></td>
                            <td class="px-4 py-2 action-buttons">
                            <a href="/pet-history/${p.id}" class="icon-button" title="История питомца"
           style="background-color:#0ea5e9; color:white; padding:0.3rem; border-radius:4px; width:32px; text-align:center;">🩺</a>
                                <button class="edit-btn btn-icon">✏️</button>
                                <button class="delete-btn btn-icon">🗑️</button>
                            </td>
                        </tr>`;
                }).join('');

                table.querySelector('tbody').innerHTML = html;
                attachEvents();
            })
            .catch(() => showError("Не удалось загрузить питомцев"));
    }

    function attachEvents() {
        table.querySelectorAll('.delete-btn').forEach(btn => {
            btn.onclick = () => {
                const id = btn.closest('tr').dataset.id;
                axios.delete(`/api/animals/${id}`)
                    .then(() => loadPets())
                    .catch(() => showError("Ошибка при удалении"));
            };
        });

        table.querySelectorAll('.edit-btn').forEach(btn => {
            btn.onclick = () => {
                const row = btn.closest('tr');
                const id = row.dataset.id;
                const cells = row.querySelectorAll('td');

                cells[0].innerHTML = `<input value="${cells[0].querySelector('input').value}" class="edit-input w-full">`;
                cells[1].innerHTML = `<input value="${cells[1].querySelector('input').value}" class="edit-input w-full">`;
                cells[2].innerHTML = `<input value="${cells[2].querySelector('input').value}" class="edit-input w-full">`;
                cells[3].innerHTML = `<input type="number" value="${cells[3].querySelector('input').value}" class="edit-input w-full">`;

                const currentOwner = cells[4].querySelector('input').value;
                const userOptions = users.map(u => {
                    const fullName = [u.last_name, u.first_name, u.middle_name].filter(Boolean).join(' ');
                    const selected = fullName === currentOwner ? 'selected' : '';
                    return `<option value="${u.id}" ${selected}>${fullName}</option>`;
                }).join('');

                cells[4].innerHTML = `<select class="edit-input w-full">${userOptions}</select>`;

                cells[5].innerHTML = `
                <button class="update-btn btn-icon confirm-btn">✅</button>
                <button class="cancel-btn btn-icon cancel-btn">❌</button>
            `;

                cells[5].querySelector('.update-btn').onclick = () => {
                    const name = cells[0].querySelector('input').value;
                    const species = cells[1].querySelector('input').value;
                    const breed = cells[2].querySelector('input').value;
                    const age = cells[3].querySelector('input').value;
                    const client_id = parseInt(cells[4].querySelector('select').value);  // ✅ Приводим к числу

                    axios.put(`/api/animals/${id}`, { name, species, breed, age, client_id })
                        .then(() => loadPets())
                        .catch(() => showError("Ошибка при обновлении"));
                };
                cells[5].querySelector('.cancel-btn').onclick = () => loadPets();
            };
        });
    }


    function handleAdd() {
        const tr = document.createElement('tr');
        tr.className = 'bg-gray-100 border-b';

        const userOptions = users.map(c =>
            `<option value="${c.id}">${[c.last_name, c.first_name, c.middle_name].filter(Boolean).join(' ')}</option>`
        ).join('');

        tr.innerHTML = `
        <td class="px-4 py-2"><input placeholder="Имя" class="new-input w-full" /></td>
        <td class="px-4 py-2"><input placeholder="Вид" class="new-input w-full" /></td>
        <td class="px-4 py-2"><input placeholder="Порода" class="new-input w-full" /></td>
        <td class="px-4 py-2"><input type="number" placeholder="Возраст" class="new-input w-full" /></td>
        <td class="px-4 py-2">
            <select class="new-input w-full">
                <option value="">Выберите владельца</option>
                ${userOptions}
            </select>
        </td>
        <td class="px-4 py-2 action-buttons">
            <button class="save-btn btn-icon confirm-btn">✅</button>
            <button class="cancel-btn btn-icon cancel-btn">❌</button>
        </td>`;

        table.prepend(tr);

        tr.querySelector('.save-btn').onclick = () => {
            const [name, species, breed, age, client_id] =
                Array.from(tr.querySelectorAll('.new-input')).map(i => i.value);

            axios.post('/api/animals', { name, species, breed, age, client_id })
                .then(() => {
                    loadPets();    // ✅ перезагрузка питомцев
                    tr.remove();   // ✅ удаление строки добавления после успеха
                })
                .catch(() => showError("Ошибка при создании"));
        };

        tr.querySelector('.cancel-btn').onclick = () => tr.remove();
    }
    function applySearch() {
        const query = document.getElementById('searchInput')?.value?.toLowerCase() || '';
        table.querySelectorAll('tbody tr').forEach(row => {
            const text = Array.from(row.querySelectorAll('input, select'))
                .map(i => i.value?.toLowerCase?.() || '')
                .join(' ');
            row.style.display = text.includes(query) ? '' : 'none';
        });
    }
    document.getElementById('searchInput')?.addEventListener('input', applySearch);
    applySearch();

    loadRefs().then(() => {
        addBtn.onclick = handleAdd;
        loadPets();
    });
}

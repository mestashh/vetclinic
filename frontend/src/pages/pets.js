export function initPets() {
    const table = document.getElementById('petsTable');
    const addBtn = document.getElementById('addPetBtn');

    let clients = [];

    function showError(m) {
        alert(m);
    }

    async function loadRefs() {
        try {
            const res = await axios.get('/api/clients');
            clients = res.data.data || [];
            console.log('Загруженные клиенты:', clients);
        } catch (e) {
            showError("Не удалось загрузить клиентов");
        }
    }

    function loadPets() {
        axios.get('/api/animals')
            .then(({ data }) => {
                const html = data.map(p => `
                    <tr data-id="${p.id}" class="bg-white border-b hover:bg-gray-50">
                        <td class="px-4 py-2"><input disabled value="${p.name}" class="pet-input w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="${p.species}" class="pet-input w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="${p.breed || ''}" class="pet-input w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="${p.age || ''}" class="pet-input w-full border-none"></td>
                        <td class="px-4 py-2">
                            <input disabled value="${p.client ? [p.client.last_name, p.client.first_name, p.client.middle_name].filter(Boolean).join(' ') : ''}" class="pet-input w-full border-none">
                        </td>
                        <td class="px-4 py-2 space-x-1">
                            <button class="delete-btn bg-red-500 text-white px-2 rounded">Удалить</button>
                        </td>
                    </tr>`).join('');
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
    }

    function handleAdd() {
        const tr = document.createElement('tr');
        tr.className = 'bg-gray-100 border-b';

        const clientOptions = clients.map(c =>
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
                    ${clientOptions}
                </select>
            </td>
            <td class="px-4 py-2 space-x-1">
                <button class="save-btn bg-green-500 text-white px-2 rounded">Сохранить</button>
                <button class="cancel-btn bg-gray-500 text-white px-2 rounded">Отмена</button>
            </td>`;

        table.prepend(tr);

        tr.querySelector('.save-btn').onclick = () => {
            const [name, species, breed, age, client_id] =
                Array.from(tr.querySelectorAll('.new-input')).map(i => i.value);

            axios.post('/api/animals', { name, species, breed, age, client_id })
                .then(() => loadPets())
                .catch(() => showError("Ошибка при создании"));
        };

        tr.querySelector('.cancel-btn').onclick = () => tr.remove();
    }

    loadRefs().then(() => {
        addBtn.onclick = handleAdd;
        loadPets();
    });
}

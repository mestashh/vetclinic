export function initVets() {
    const table = document.getElementById('vetsTable');
    const addBtn = document.getElementById('addVetBtn');

    function showError(msg) {
        alert(msg);
    }

    function loadVets() {
        axios.get('/api/veterinarians')
            .then(({ data }) => {
                const html = (data.data || []).map(v => `
                    <tr data-id="${v.id}" class="bg-white border-b hover:bg-gray-50">
                        <td class="px-4 py-2"><input disabled value="${v.last_name}" class="vet-input w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="${v.first_name}" class="vet-input w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="${v.middle_name || ''}" class="vet-input w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="${v.specialization || ''}" class="vet-input w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="${v.phone || ''}" class="vet-input w-full border-none"></td>
                        <td class="px-4 py-2"><input disabled value="${v.email || ''}" class="vet-input w-full border-none"></td>
                        <td class="px-4 py-2 space-x-1">
                            <button class="delete-btn bg-red-500 text-white px-2 rounded">Удалить</button>
                        </td>
                    </tr>`).join('');

                table.querySelector('tbody').innerHTML = html;
                attachEvents();
            })
            .catch(() => showError("Не удалось загрузить ветеринаров"));
    }

    function attachEvents() {
        table.querySelectorAll('.delete-btn').forEach(btn => {
            btn.onclick = () => {
                const id = btn.closest('tr').dataset.id;
                axios.delete(`/api/veterinarians/${id}`)
                    .then(() => loadVets())
                    .catch(() => showError("Ошибка при удалении"));
            };
        });
    }

    if (addBtn) {
        addBtn.onclick = () => {
            const tr = document.createElement('tr');
            tr.className = 'bg-gray-100 border-b';
            tr.innerHTML = `
                <td class="px-4 py-2"><input placeholder="Фамилия" class="new-input w-full" /></td>
                <td class="px-4 py-2"><input placeholder="Имя" class="new-input w-full" /></td>
                <td class="px-4 py-2"><input placeholder="Отчество" class="new-input w-full" /></td>
                <td class="px-4 py-2"><input placeholder="Специализация" class="new-input w-full" /></td>
                <td class="px-4 py-2"><input placeholder="Телефон" class="new-input w-full" /></td>
                <td class="px-4 py-2"><input placeholder="Email" class="new-input w-full" /></td>
                <td class="px-4 py-2 space-x-1">
                    <button class="save-btn bg-green-500 text-white px-2 rounded">Сохранить</button>
                    <button class="cancel-btn bg-gray-500 text-white px-2 rounded">Отмена</button>
                </td>`;

            table.prepend(tr);

            tr.querySelector('.save-btn').onclick = () => {
                const [last_name, first_name, middle_name, specialization, phone, email] =
                    Array.from(tr.querySelectorAll('.new-input')).map(i => i.value);

                axios.post('/api/veterinarians', {
                    last_name,
                    first_name,
                    middle_name,
                    specialization,
                    phone,
                    email
                }).then(() => loadVets())
                    .catch(() => showError("Ошибка при создании"));
            };

            tr.querySelector('.cancel-btn').onclick = () => tr.remove();
        };
    }

    loadVets();
}

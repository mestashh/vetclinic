// полный код
export function initServices() {
    const table = document.getElementById('servicesTable');         // CHANGED
    const addBtn = document.getElementById('addServiceBtn');        // CHANGED

    function showError(m) { alert(m); }

    function loadServices() {
        axios.get('/api/procedures').then(({ data }) => {
            const html = data.map(s => `
        <tr data-id="${s.id}" class="bg-white border-b hover:bg-gray-50">
          <td class="px-4 py-2"><input disabled value="${s.name}"        class="service-input w-full border-none"></td>
          <td class="px-4 py-2"><input disabled value="${s.description || ''}" class="service-input w-full border-none"></td>
          <td class="px-4 py-2"><input disabled value="${s.price}"       class="service-input w-full border-none"></td>
          <td class="px-4 py-2 space-x-1">
            <button class="delete-btn bg-red-500 text-white px-2 rounded">Удалить</button>
          </td>
        </tr>`).join('');
            table.querySelector('tbody').innerHTML = html;
            attachEvents();
        }).catch(() => showError("Не удалось загрузить услуги"));
    }

    function attachEvents() {
        table.querySelectorAll('.delete-btn').forEach(btn => {
            btn.onclick = () => {
                const id = btn.closest('tr').dataset.id;
                axios.delete(`/api/procedures/${id}`)
                    .then(() => loadServices())
                    .catch(() => showError("Ошибка при удалении"));
            };
        });
    }

    addBtn.onclick = () => {
        const tr = document.createElement('tr');
        tr.className = 'bg-gray-100 border-b';
        tr.innerHTML = `
      <td class="px-4 py-2"><input placeholder="Название" class="new-input w-full"></td>
      <td class="px-4 py-2"><input placeholder="Описание" class="new-input w-full"></td>
      <td class="px-4 py-2"><input placeholder="Цена"     type="number" class="new-input w-full"></td>
      <td class="px-4 py-2 space-x-1">
        <button class="save-btn bg-green-500 text-white px-2 rounded">Сохранить</button>
        <button class="cancel-btn bg-gray-500 text-white px-2 rounded">Отмена</button>
      </td>`;
        table.prepend(tr);

        tr.querySelector('.save-btn').onclick = () => {
            const [name, description, price] = Array.from(tr.querySelectorAll('.new-input')).map(i => i.value);
            axios.post('/api/procedures', { name, description, price })
                .then(() => loadServices())
                .catch(() => showError("Ошибка при создании"));
        };
        tr.querySelector('.cancel-btn').onclick = () => tr.remove();
    };

    loadServices();
}

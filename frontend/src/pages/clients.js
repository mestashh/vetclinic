// полный код
export function initClients() {
    const table = document.getElementById('clientsTable');           // CHANGED: селектор по id
    const addBtn = document.getElementById('addClientBtn');          // CHANGED

    function showError(msg) {
        alert(msg);
    }

    function loadClients() {
        axios.get('/api/clients').then(({ data }) => {
            const html = data.map(c => `
        <tr data-id="${c.id}" class="bg-white border-b hover:bg-gray-50">
          <td class="px-4 py-2"><input disabled value="${c.last_name}"  class="client-input w-full border-none"></td>
          <td class="px-4 py-2"><input disabled value="${c.first_name}" class="client-input w-full border-none"></td>
          <td class="px-4 py-2"><input disabled value="${c.middle_name || ''}" class="client-input w-full border-none"></td>
          <td class="px-4 py-2"><input disabled value="${c.phone}"      class="client-input w-full border-none"></td>
          <td class="px-4 py-2"><input disabled value="${c.email}"      class="client-input w-full border-none"></td>
          <td class="px-4 py-2"><input disabled value="${c.address}"    class="client-input w-full border-none"></td>
          <td class="px-4 py-2 space-x-1">
            <button class="delete-btn bg-red-500 text-white px-2 rounded">Удалить</button>
          </td>
        </tr>`).join('');

            table.querySelector('tbody').innerHTML = html;
            attachEvents();
        }).catch(() => showError("Не удалось загрузить клиентов"));
    }

    function attachEvents() {
        table.querySelectorAll('.delete-btn').forEach(btn => {
            btn.onclick = () => {
                const id = btn.closest('tr').dataset.id;
                axios.delete(`/api/clients/${id}`)
                    .then(() => loadClients())
                    .catch(() => showError("Ошибка при удалении"));
            };
        });
    }

    addBtn.onclick = () => {
        const tr = document.createElement('tr');
        tr.className = 'bg-gray-100 border-b';
        tr.innerHTML = `
      <td class="px-4 py-2"><input placeholder="Фамилия" class="new-input w-full"></td>
      <td class="px-4 py-2"><input placeholder="Имя"      class="new-input w-full"></td>
      <td class="px-4 py-2"><input placeholder="Отчество" class="new-input w-full"></td>
      <td class="px-4 py-2"><input placeholder="Телефон"  class="new-input w-full"></td>
      <td class="px-4 py-2"><input placeholder="Email"     class="new-input w-full"></td>
      <td class="px-4 py-2"><input placeholder="Адрес"     class="new-input w-full"></td>
      <td class="px-4 py-2 space-x-1">
        <button class="save-btn bg-green-500 text-white px-2 rounded">Сохранить</button>
        <button class="cancel-btn bg-gray-500 text-white px-2 rounded">Отмена</button>
      </td>`;
        table.prepend(tr);

        tr.querySelector('.save-btn').onclick = () => {
            const [last_name, first_name, middle_name, phone, email, address] =
                Array.from(tr.querySelectorAll('.new-input')).map(i => i.value);
            axios.post('/api/clients', { first_name, middle_name, last_name, phone, email, address })
                .then(() => loadClients())
                .catch(() => showError("Ошибка при создании клиента"));
        };
        tr.querySelector('.cancel-btn').onclick = () => tr.remove();
    };

    // первоначальная загрузка
    loadClients();
}

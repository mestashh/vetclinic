export function initPastAppointments() {
    const table = document.getElementById('pastAppointmentsTable');
    const tbody = table.querySelector('tbody');

    async function load() {
        try {
            const { data } = await axios.get('/api/appointments');
            const appointments = data
                .filter(a => a.client_id === window.currentUserId && a.status === 'completed')
                .sort((a, b) => new Date(b.scheduled_at) - new Date(a.scheduled_at));

            tbody.innerHTML = appointments.map(a => {
                const date = new Date(a.scheduled_at).toLocaleString();
                const pet = a.pet?.name || '—';

                const vetUser = a.veterinarian?.user;
                const vet = vetUser
                    ? `${vetUser.last_name || ''} ${vetUser.first_name || ''}`.trim()
                    : '—';

                const services = (a.services || []).map(s => s.name).join(', ') || '—';
                const comment = a.comment || '—';

                return `
                    <tr class="summary-row" data-id="${a.id}">
                        <td colspan="4">
                            <strong>Приём:</strong> ${date}
                            <button class="show-details-btn" style="margin-left: 1rem;">Подробнее</button>
                        </td>
                    </tr>
                    <tr class="details-row" style="display: none;">
                        <td colspan="4">
                            <p><strong>Питомец:</strong> ${pet}</p>
                            <p><strong>Ветеринар:</strong> ${vet}</p>
                            <p><strong>Услуги:</strong> ${services}</p>
                            <p><strong>Комментарий:</strong> ${comment}</p>
                        </td>
                    </tr>
                `;
            }).join('');

            // раскрытие подробностей
            document.querySelectorAll('.show-details-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const row = btn.closest('tr');
                    const next = row.nextElementSibling;
                    if (next && next.classList.contains('details-row')) {
                        const isOpen = next.style.display !== 'none';
                        next.style.display = isOpen ? 'none' : 'table-row';
                        btn.textContent = isOpen ? 'Подробнее' : 'Скрыть';
                    }
                });
            });

        } catch (err) {
            console.error('Ошибка загрузки истории приёмов', err);
        }
    }

    load();
}

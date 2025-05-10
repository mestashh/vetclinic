export function initStartAppointment() {
    const select = document.getElementById('appointmentSelect');
    const info = document.getElementById('appointmentInfo');

    if (!select || !info) return;

    let appointments = [];
    let allServices = [];

    async function loadAppointments() {
        console.log('[DEBUG] Загружаем приёмы...');

        try {
            const [resAppts, resServices] = await Promise.all([
                axios.get('/api/appointments'),
                axios.get('/api/services?include=items')
            ]);

            appointments = resAppts.data.filter(a => {
                const isMatch = a.veterinarian?.user_id == window.currentUserId;
                const isScheduled = a.status === 'scheduled';

                const today = new Date();
                const apptDate = new Date(a.scheduled_at);

                const isToday =
                    apptDate.getFullYear() === today.getFullYear() &&
                    apptDate.getMonth() === today.getMonth() &&
                    apptDate.getDate() === today.getDate();

                return isMatch && isScheduled && isToday;
            });

            allServices = resServices.data.data || [];

            select.innerHTML = '<option value="">— выберите приём —</option>' + appointments.map(appt => {
                const date = new Date(appt.scheduled_at).toLocaleString();
                const client = appt.user?.name || `${appt.user?.last_name || ''} ${appt.user?.first_name || ''}`;
                return `<option value="${appt.id}">${client.trim()} — ${date}</option>`;
            }).join('');
        } catch (err) {
            console.error('[DEBUG] Ошибка загрузки:', err);
            alert('Ошибка загрузки приёмов или услуг');
        }
    }

    select.addEventListener('change', () => {
        const id = select.value;
        if (!id) {
            info.style.display = 'none';
            info.innerHTML = '';
            return;
        }

        const appt = appointments.find(a => a.id == id);
        if (!appt) return;

        const date = new Date(appt.scheduled_at).toLocaleString();
        const client = appt.user ? `${appt.user.last_name} ${appt.user.first_name}` : 'неизвестно';
        const petName = appt.pet?.name || '—';
        const petLink = appt.pet
            ? `<a href="/pet-history/${appt.pet.id}" style="color:#2563eb; text-decoration:underline;" target="_blank">${petName}</a>`
            : '—';
        const selectedServiceIds = appt.services?.map(s => s.id) || [];

        const serviceCheckboxes = allServices.map(service => {
            const items = (service.items || []).map(item => {
                const isChecked = appt.services?.some(s =>
                    s.items?.some(si => si.id === item.id)
                );
                return `
            <div class="service-item" style="margin-left: 1.5rem; margin-top: 0.3rem;">
                <label>
                    <input type="checkbox" class="service-item-checkbox" value="${item.id}" ${isChecked ? 'checked' : ''}>
                    <strong>${item.name}</strong> — ${item.price}₽ ${item.description ? `— ${item.description}` : ''}
                </label>
            </div>
        `;
            }).join('');

            const blockId = `block-${service.id}`;

            return `
        <div class="service-block" style="margin-top: 1rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <button class="toggle-btn" data-target="${blockId}" aria-expanded="false">▶️</button>
                <span style="font-weight: bold;">${service.name}</span>
            </div>
            <div id="${blockId}" class="service-items" style="display:none;">
                ${items || '<em>Нет вариантов</em>'}
            </div>
        </div>
    `;
        }).join('');



        info.innerHTML = `
            <p><strong>Дата:</strong> ${date}</p>
            <p><strong>Клиент:</strong> ${client}</p>
            <p><strong>Паспорт:</strong> ${appt.user?.passport || '—'}</p>
            <p><strong>Питомец:</strong> ${petLink}</p>
            <p><strong>Статус:</strong> ${translateStatus(appt.status)}</p>

            <div style="margin-top: 1rem;">
                <strong>Выберите оказанные услуги:</strong>
                ${serviceCheckboxes}
            </div>

            <div style="margin-top: 1rem;">
                <label for="comment"><strong>Комментарий:</strong></label>
                <textarea id="comment" rows="3" style="width:100%; margin-top:0.5rem;"></textarea>
            </div>

            <p id="totalCost" style="margin-top: 1rem;"><strong>Сумма:</strong> 0₽</p>

            <button id="completeBtn" style="margin-top: 1rem; background:#10b981;">Завершить приём</button>
        `;

        info.style.display = 'block';

        calculateTotal();

        document.querySelectorAll('.service-item-checkbox').forEach(cb => {
            cb.addEventListener('change', calculateTotal);
        });


        document.getElementById('completeBtn').onclick = async () => {
            const selectedItemIds = Array.from(document.querySelectorAll('.service-item-checkbox:checked'))
                .map(cb => parseInt(cb.value));
            const comment = document.getElementById('comment').value;

            try {
                await axios.post(`/api/appointments/${appt.id}/complete`, {
                    service_item_ids: selectedItemIds,
                    comment
                });
                alert('Приём завершён!');
                loadAppointments(); // перезагрузим список
            } catch (err) {
                console.error(err);
                alert('Ошибка при завершении приёма');
            }
        };

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.service-item-checkbox:checked').forEach(cb => {
                const id = parseInt(cb.value);
                for (const service of allServices) {
                    const item = (service.items || []).find(i => i.id === id);
                    if (item) total += parseFloat(item.price);
                }
            });
            document.getElementById('totalCost').innerHTML = `<strong>Сумма:</strong> ${total.toFixed(2)}₽`;
        }
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.dataset.target;
                const block = document.getElementById(targetId);
                if (!block) return;
                const isVisible = block.style.display === 'block';
                block.style.display = isVisible ? 'none' : 'block';
                btn.textContent = isVisible ? '▶️' : '▼';
            });
        });
    });

    function translateStatus(status) {
        switch (status) {
            case 'scheduled': return 'Запланирован';
            case 'completed': return 'Проведён';
            case 'missed': return 'Не проведён';
            default: return '—';
        }
    }

    loadAppointments();
}

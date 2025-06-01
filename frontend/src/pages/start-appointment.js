export function initStartAppointment() {
    const select = document.getElementById('appointmentSelect');
    const info = document.getElementById('appointmentInfo');

    if (!select || !info) return;

    const hasInitialData =
        typeof window.initialAppointments !== 'undefined' &&
        typeof window.initialServices !== 'undefined';

    let appointments = window.initialAppointments || [];
    if (window.initialAppointments) delete window.initialAppointments;
    let allServices = window.initialServices || [];
    if (window.initialServices) delete window.initialServices;
    const preselectedId = typeof window.selectedAppointmentId !== 'undefined' ? window.selectedAppointmentId : null;
    if (typeof window.selectedAppointmentId !== 'undefined') delete window.selectedAppointmentId;

    function renderOptions() {
        select.innerHTML = '<option value="">— выберите приём —</option>' +
            appointments.map(appt => {
                const date = new Date(appt.scheduled_at).toLocaleString();
                const client = appt.user?.name || `${appt.user?.last_name || ''} ${appt.user?.first_name || ''}`;
                return `<option value="${appt.id}">${client.trim()} — ${date}</option>`;
            }).join('');

        if (!appointments.length) {
            info.style.display = 'block';
            info.innerHTML = '<em>Нет приёмов на сегодня.</em>';
        }
        if (preselectedId) {
            select.value = preselectedId;
            select.dispatchEvent(new Event('change'));
        }
    }

    async function loadAppointments() {
        try {
            const [resAppts, resServices] = await Promise.all([
                axios.get('/api/appointments'),
                axios.get('/api/services')
            ]);

            appointments = resAppts.data.filter(a => {
                const isMatch = a.veterinarian?.user_id == window.currentUserId;
                const isScheduled = a.status === 'scheduled';
                const today = new Date();
                const apptDate = new Date(a.scheduled_at);
                return isMatch && isScheduled && apptDate.toDateString() === today.toDateString();
            });

            allServices = resServices.data.data || [];

            renderOptions();
        } catch (err) {
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
        const client = `${appt.user?.last_name} ${appt.user?.first_name}`;
        const petName = appt.pet?.name || '—';
        const petLink = appt.pet ? `<a href="/pet-history/${appt.pet.id}" style="color:#2563eb;" target="_blank">${petName}</a>` : '—';

        const serviceCheckboxes = allServices.map(service => {
            const items = (service.items || []).map(item => {
                const isChecked = appt.services?.some(s => s.items?.some(si => si.id === item.id));
                return `
                    <div class="service-item" style="margin-left: 1.5rem;">
                        <label>
                            <input type="checkbox" class="service-item-checkbox" value="${item.id}" ${isChecked ? 'checked' : ''}>
                            <strong>${item.name}</strong> — ${item.price}₽ ${item.description ? `— ${item.description}` : ''}
                        </label>
                    </div>`;
            }).join('');

            return `
                <div class="service-block" style="margin-top: 1rem;">
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <button class="toggle-btn" data-target="block-${service.id}">▶️</button>
                        <span><strong>${service.name}</strong></span>
                    </div>
                    <div id="block-${service.id}" class="service-items" style="display:none;">${items || '<em>Нет вариантов</em>'}</div>
                </div>`;
        }).join('');

        info.innerHTML = `
            <p><strong>Дата:</strong> ${date}</p>
            <p><strong>Клиент:</strong> ${client}</p>
            <p><strong>Паспорт:</strong> ${appt.user?.passport || '—'}</p>
            <p><strong>Питомец:</strong> ${petLink}</p>
            <p><strong>Статус:</strong> ${translateStatus(appt.status)}</p>

            <div style="margin-top:1rem;">
                <strong>Выберите оказанные услуги:</strong>
                ${serviceCheckboxes}
            </div>

            <div style="margin-top:1rem;">
                <label for="comment"><strong>Комментарий:</strong></label>
                <textarea id="comment" rows="3" style="width:100%; margin-top:0.5rem;"></textarea>
            </div>

            <p id="totalCost" style="margin-top:1rem;"><strong>Сумма:</strong> 0₽</p>

            <button id="completeBtn" style="background:#10b981; margin-top:1rem;">Завершить приём</button>

            <div style="margin-top: 1rem; display: flex; gap: 1rem;">
                <button id="printReceiptBtn" style="background:#3b82f6;">🧾 Печать квитанции</button>
                <button id="printContractBtn" style="background:#9333ea;">📄 Печать договора</button>
            </div>
        `;

        info.style.display = 'block';
        calculateTotal();

        document.querySelectorAll('.service-item-checkbox').forEach(cb => {
            cb.addEventListener('change', calculateTotal);
        });

        document.getElementById('completeBtn').onclick = async () => {
            const selectedItemIds = Array.from(document.querySelectorAll('.service-item-checkbox:checked')).map(cb => parseInt(cb.value));
            const comment = document.getElementById('comment').value;

            try {
                await axios.post(`/api/appointments/${appt.id}/complete`, {
                    service_item_ids: selectedItemIds,
                    comment
                });
                alert('Приём завершён!');
                loadAppointments();
            } catch (err) {
                alert('Ошибка при завершении приёма');
            }
        };

        document.getElementById('printReceiptBtn').onclick = () => printReceipt(appt);
        document.getElementById('printContractBtn').onclick = () => printContract(appt);

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

        function printReceipt(appt) {
            const items = Array.from(document.querySelectorAll('.service-item-checkbox:checked')).map(cb => {
                const id = parseInt(cb.value);
                for (const service of allServices) {
                    const item = (service.items || []).find(i => i.id === id);
                    if (item) return `${item.name} — ${item.price}₽`;
                }
                return null;
            }).filter(Boolean);

            const total = document.getElementById('totalCost')?.innerText || '';

            const win = window.open('', '_blank');
            win.document.write(`
                <html>
                    <head><title>Квитанция</title></head>
                    <body>
                        <h2>Квитанция об оплате</h2>
                        <p><strong>Дата:</strong> ${new Date(appt.scheduled_at).toLocaleString()}</p>
                        <p><strong>Клиент:</strong> ${appt.user?.last_name} ${appt.user?.first_name}</p>
                        <p><strong>Питомец:</strong> ${appt.pet?.name}</p>
                        <h3>Оказанные услуги:</h3>
                        <ul>${items.map(i => `<li>${i}</li>`).join('')}</ul>
                        <p><strong>${total}</strong></p>
                        <br><br>
                        <p>Подпись: ______________________</p>
                        <script>window.print()</script>
                    </body>
                </html>
            `);
            win.document.close();
        }

        function printContract(appt) {
            const fullName = `${appt.user?.last_name} ${appt.user?.first_name}`;
            const petName = appt.pet?.name || '';
            const date = new Date(appt.scheduled_at).toLocaleDateString();
            const comment = document.getElementById('comment')?.value || '';

            const win = window.open('', '_blank');
            win.document.write(`
                <html>
                    <head><title>Договор</title></head>
                    <body>
                        <h2>Договор на оказание ветеринарных услуг</h2>
                        <p>г. Москва &nbsp;&nbsp;&nbsp; Дата: ${date}</p>
                        <p>Клиент: <strong>${fullName}</strong></p>
                        <p>Питомец: <strong>${petName}</strong></p>
                        <p>Настоящим подтверждается согласие на оказание ветеринарных услуг в рамках приёма, запланированного на ${date}.</p>
                        <p>Стороны подтверждают, что информация о питомце предоставлена клиентом добровольно и достоверно.</p>
                        <p><strong>Комментарий:</strong> ${comment}</p>
                        <br><br>
                        <p>Клиент: _______________________</p>
                        <p>Ветеринар: ____________________</p>
                        <script>window.print()</script>
                    </body>
                </html>
            `);
            win.document.close();
        }
    });

    function translateStatus(status) {
        switch (status) {
            case 'scheduled': return 'Запланирован';
            case 'completed': return 'Проведён';
            case 'missed': return 'Не проведён';
            default: return '—';
        }
    }

    if (hasInitialData) {
        renderOptions();
    } else {
        loadAppointments();
    }
}

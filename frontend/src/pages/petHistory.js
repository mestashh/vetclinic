import axios from 'axios';

export function initPetHistory() {
    const petId = window.currentPetId;
    const petInfoEl = document.getElementById('petInfo');
    const appointmentsEl = document.getElementById('appointments');

    if (!petId || !petInfoEl || !appointmentsEl) return;

    async function load() {
        try {
            const [petsRes, apptRes, usersRes] = await Promise.all([
                axios.get('/api/animals'),
                axios.get('/api/appointments'),
                axios.get('/api/users')
            ]);

            const pet = (petsRes.data.data || []).find(p => p.id == petId);
            if (!pet) return petInfoEl.innerHTML = '<p>Питомец не найден</p>';

            const owner = (usersRes.data.data || []).find(u => u.id == pet.client_id);

            // 🐾 Блок информации
            petInfoEl.innerHTML = `
                <p><strong>ФИО владельца:</strong> ${owner ? [owner.last_name, owner.first_name, owner.middle_name].filter(Boolean).join(' ') : '—'}</p>
                <p><strong>Кличка:</strong> ${pet.name}</p>
                <p><strong>Вид:</strong> ${pet.species}</p>
                <p><strong>Порода:</strong> ${pet.breed || '—'}</p>
                <p><strong>Возраст:</strong> ${pet.age || '—'}</p>
            `;

            // 📅 История завершённых приёмов
            const allAppointments = apptRes.data || [];
            const past = allAppointments.filter(a => a.pet_id == pet.id && a.status === 'completed');

            appointmentsEl.innerHTML = past.length
                ? '<h2>Завершённые приёмы</h2>'
                : '<p>Завершённых приёмов не найдено.</p>';

            for (const appt of past) {
                const date = new Date(appt.scheduled_at).toLocaleString();
                const services = (appt.services || []).flatMap(s => s.items || []);
                const serviceNames = services.map(item => item.name).join(', ') || '—';
                const comment = appt.comment || '—';
                const total = services.reduce((sum, item) => sum + (parseFloat(item.price) || 0), 0);

                appointmentsEl.innerHTML += `
                    <div class="appointment">
                        <h4>Приём: ${date}</h4>
                        <p><strong>Услуги:</strong> ${serviceNames}</p>
                        <p><strong>Комментарий:</strong> ${comment}</p>
                        <p><strong>Стоимость:</strong> ${total.toFixed(2)}₽</p>
                    </div>
                `;
            }
        } catch (err) {
            console.error('[PetHistory] Ошибка загрузки:', err);
            petInfoEl.innerHTML = '<p>Ошибка загрузки данных</p>';
        }
    }

    load();
}

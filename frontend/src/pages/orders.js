import axios from 'axios';

export function initOrdersPage() {
    const serviceSelect = document.getElementById('serviceSelect');
    const itemSelect = document.getElementById('itemSelect');
    const qtyInput = document.getElementById('qtyInput');
    const priceInput = document.getElementById('priceInput');
    const commentInput = document.getElementById('commentInput');
    const submitBtn = document.getElementById('submitBtn');
    const ordersList = document.getElementById('ordersList');
    const totalPriceEl = document.getElementById('totalPrice');

    let allServices = window.initialServices || [];
    let ordersData = window.initialOrders || [];
    function populateServices() {
        serviceSelect.innerHTML = '<option value="">— выберите услугу —</option>' + allServices.map(s => `
            <option value="${s.id}">${s.name}</option>
        `).join('');
    }

    async function loadServices() {
        const res = await axios.get('/api/services?include=items');
        allServices = res.data.data || [];
        populateServices();
    }

    serviceSelect.onchange = () => {
        const serviceId = serviceSelect.value;
        const service = allServices.find(s => s.id == serviceId);
        if (!service) {
            itemSelect.innerHTML = '<option value="">— выберите вариант —</option>';
            itemSelect.disabled = true;
            return;
        }

        itemSelect.innerHTML = '<option value="">— выберите вариант —</option>' + (service.items || []).map(i => `
            <option value="${i.id}">${i.name}</option>
        `).join('');
        itemSelect.disabled = false;
    };

    qtyInput.oninput = calculatePrice;
    priceInput.oninput = calculatePrice;

    function calculatePrice() {
        const qty = parseInt(qtyInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        totalPriceEl.textContent = `${(qty * price).toFixed(2)}₽`;
    }

    submitBtn.onclick = async () => {
        const service_item_id = itemSelect.value;
        const quantity = parseInt(qtyInput.value);
        const price = parseFloat(priceInput.value);
        const comment = commentInput.value;

        if (!service_item_id || !quantity || isNaN(price)) {
            alert('Укажите вариант, количество и цену!');
            return;
        }

        try {
            await axios.post('/api/orders', {
                service_item_id,
                quantity,
                comment,
                price  // сохраняется, если нужно (расширение БД)
            });

            serviceSelect.value = '';
            itemSelect.innerHTML = '<option value="">— выберите вариант —</option>';
            itemSelect.disabled = true;
            qtyInput.value = '';
            priceInput.value = '';
            commentInput.value = '';
            totalPriceEl.textContent = '0₽';
            loadOrders();
        } catch (err) {
            alert('Ошибка при создании заявки');
            console.error(err);
        }
    };
    function renderOrders() {
        const list = ordersData.map(o => `
            <div class="order-card">
                <p><strong>Услуга:</strong> ${o.item?.name || '—'}</p>
                <p><strong>Количество:</strong> ${o.quantity}</p>
                <p><strong>Комментарий:</strong> ${o.comment || '—'}</p>
                <p><strong>Дата:</strong> ${new Date(o.created_at).toLocaleString()}</p>
            </div>
        `).join('');

        ordersList.innerHTML = '<h2>Ранее оформленные заявки</h2>' + (list || '<p>Нет заявок.</p>');
    }

    async function loadOrders() {
        try {
            const res = await axios.get('/api/orders');
            ordersData = res.data;
            renderOrders();
        } catch (err) {
            ordersList.innerHTML = '<p>Ошибка загрузки заявок</p>';
        }
    }

    if (allServices.length) {
        populateServices();
    } else {
        loadServices();
    }

    if (ordersData.length) {
        renderOrders();
    } else {
        loadOrders();
    }
}
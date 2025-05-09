export function initNews() {
    const root = document.getElementById('newsRoot');
    const form = document.getElementById('newsForm');
    const toggleBtn = document.getElementById('toggleNewsForm');
    const titleInput = document.getElementById('newsTitle');
    const textInput = document.getElementById('newsText');
    const submitBtn = document.getElementById('newsSubmitBtn');

    function showError(msg) {
        alert(msg);
    }

    function renderNews(newsList) {
        root.innerHTML = newsList.map(n => `
            <div class="news-item">
                <div class="news-item-title">${n.title}</div>
                <div>${n.text}</div>
            </div>
        `).join('');
    }

    function loadNews() {
        axios.get('/api/news')
            .then(res => {
                const data = Array.isArray(res.data) ? res.data : res.data.data;
                renderNews(data.reverse());
            })
            .catch(() => showError("Ошибка загрузки новостей"));
    }

    if (toggleBtn) {
        toggleBtn.onclick = () => {
            const isHidden = getComputedStyle(form).display === 'none';
            form.style.display = isHidden ? 'block' : 'none';
        };
    }

    if (submitBtn && titleInput && textInput) {
        submitBtn.onclick = () => {
            const title = titleInput.value.trim();
            const text = textInput.value.trim();
            if (!title || !text) return showError('Заполните оба поля');

            axios.post('/api/news', { title, text })
                .then(() => {
                    titleInput.value = '';
                    textInput.value = '';
                    form.style.display = 'none';
                    loadNews();
                })
                .catch(() => showError("Ошибка при добавлении"));
        };
    }

    loadNews();
}

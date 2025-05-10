export function initPetHistory() {
    const petId = window.currentPetId;
    const text = document.getElementById('helloText');

    if (text) {
        text.textContent = `Загружена история болезней для питомца с ID: ${petId}`;
    }

    console.log('[initPetHistory] Страница истории загружена для питомца:', petId);
}

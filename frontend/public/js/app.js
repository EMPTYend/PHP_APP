document.addEventListener('DOMContentLoaded', function() {
    // Загрузка доступных номеров
    fetch('/api/rooms')
        .then(response => response.json())
        .then(rooms => {
            const roomsList = document.getElementById('rooms-list');
            rooms.forEach(room => {
                const roomCard = document.createElement('div');
                roomCard.className = 'room-card';
                roomCard.innerHTML = `
                    <h3>${room.type_name}</h3>
                    <p>${room.description}</p>
                    <p class="price">${room.base_price} руб./ночь</p>
                    <button class="btn btn-primary book-btn" data-id="${room.id_type}">Забронировать</button>
                `;
                roomsList.appendChild(roomCard);
            });
        });
});
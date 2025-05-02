<template>
  <div class="booking-form">
    <h2>Бронирование номера</h2>
    <form @submit.prevent="searchRooms">
      <div class="form-group">
        <label>Тип номера:</label>
        <select v-model="searchData.room_type" required>
          <option v-for="type in roomTypes" :value="type.id_type" :key="type.id_type">
            {{ type.type_name }} ({{ type.base_price }} руб./ночь)
          </option>
        </select>
      </div>
      
      <div class="form-group">
        <label>Дата заезда:</label>
        <input type="date" v-model="searchData.check_in" required>
      </div>
      
      <div class="form-group">
        <label>Дата выезда:</label>
        <input type="date" v-model="searchData.check_out" required>
      </div>
      
      <button type="submit">Найти номера</button>
    </form>

    <div v-if="availableRooms.length > 0" class="available-rooms">
      <h3>Доступные номера:</h3>
      <div v-for="room in availableRooms" :key="room.id_room" class="room-card">
        <h4>Номер {{ room.room_number }} ({{ room.type_name }})</h4>
        <p>Этаж: {{ room.floor }}, Вместимость: {{ room.capacity }} чел.</p>
        <p>Кровати: {{ room.beds }}, Цена: {{ calculateTotalPrice(room) }} руб.</p>
        <button @click="bookRoom(room.id_room)">Забронировать</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      roomTypes: [],
      searchData: {
        room_type: '',
        check_in: '',
        check_out: ''
      },
      availableRooms: []
    };
  },
  async created() {
    const response = await fetch('/api/room-types');
    this.roomTypes = await response.json();
    if (this.roomTypes.length > 0) {
      this.searchData.room_type = this.roomTypes[0].id_type;
    }
  },
  methods: {
    async searchRooms() {
      const response = await fetch('/api/search-rooms', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.searchData)
      });
      this.availableRooms = await response.json();
    },
    calculateTotalPrice(room) {
      const days = (new Date(this.searchData.check_out) - new Date(this.searchData.check_in)) / (1000 * 60 * 60 * 24);
      return room.base_price * days;
    },
    async bookRoom(roomId) {
      if (!this.$store.state.user) {
        alert('Для бронирования необходимо войти в систему');
        return;
      }
      
      const bookingData = {
        room_id: roomId,
        check_in: this.searchData.check_in,
        check_out: this.searchData.check_out,
        adults: 2,
        children: 0
      };
      
      const response = await fetch('/api/book-room', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${this.$store.state.token}`
        },
        body: JSON.stringify(bookingData)
      });
      
      if (response.ok) {
        alert('Номер успешно забронирован!');
      } else {
        alert('Ошибка при бронировании');
      }
    }
  }
};
</script>
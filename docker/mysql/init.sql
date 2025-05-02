-- Пользователи
CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    telefon VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('guest', 'client', 'manager', 'admin') DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Типы номеров
CREATE TABLE IF NOT EXISTS room_types (
    id_type INT AUTO_INCREMENT PRIMARY KEY,
    type_name ENUM('standard', 'family', 'premium', 'family_premium', 'lux') NOT NULL,
    description TEXT,
    base_price DECIMAL(10,2) NOT NULL
);

-- Номера
CREATE TABLE IF NOT EXISTS rooms (
    id_room INT AUTO_INCREMENT PRIMARY KEY,
    id_type INT NOT NULL,
    room_number VARCHAR(10) NOT NULL UNIQUE,
    floor INT NOT NULL,
    capacity INT NOT NULL,
    beds INT NOT NULL,
    FOREIGN KEY (id_type) REFERENCES room_types(id_type)
);

-- Фотографии номеров
CREATE TABLE IF NOT EXISTS room_pictures (
    id_picture INT AUTO_INCREMENT PRIMARY KEY,
    id_room INT NOT NULL,
    picture_path VARCHAR(255) NOT NULL,
    is_main BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_room) REFERENCES rooms(id_room)
);

-- Запросы на бронирование
CREATE TABLE IF NOT EXISTS bookings (
    id_booking INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_room INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    adults INT NOT NULL,
    children INT DEFAULT 0,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_room) REFERENCES rooms(id_room)
);

-- Отзывы
CREATE TABLE IF NOT EXISTS reviews (
    id_review INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_room INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_room) REFERENCES rooms(id_room)
);

-- Заполнение типов номеров
INSERT INTO room_types (type_name, description, base_price) VALUES
('standard', 'Стандартный номер с одной двуспальной кроватью', 2500.00),
('family', 'Семейный номер с двумя кроватями', 3500.00),
('premium', 'Премиум номер с улучшенным интерьером', 4500.00),
('family_premium', 'Семейный премиум номер с двумя кроватями', 5500.00),
('lux', 'Люкс номер с гостиной зоной и джакузи', 8500.00);

-- Создание тестового администратора
INSERT INTO users (name, telefon, email, password, role) 
VALUES ('Администратор', '+79991234567', 'admin@hotel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Создание базы данных (если не существует)
CREATE DATABASE IF NOT EXISTS hotel_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE hotel_db;

-- Таблица пользователей
CREATE TABLE IF NOT EXISTS user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_phone (phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица изображений
CREATE TABLE IF NOT EXISTS pictures (
    id_pictures INT AUTO_INCREMENT PRIMARY KEY,
    road VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица комнат
CREATE TABLE IF NOT EXISTS rooms (
    id_room INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    peoples INT NOT NULL,
    rooms INT NOT NULL,
    bed VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    id_pictures INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pictures) REFERENCES pictures(id_pictures) ON DELETE SET NULL,
    INDEX idx_type (type),
    INDEX idx_peoples (peoples)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица запросов/бронирований
CREATE TABLE IF NOT EXISTS query (
    id_query INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    peoples INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_dates (check_in, check_out)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Создание администратора по умолчанию (пароль: Admin123)
INSERT IGNORE INTO user (name, phone, email, password, role) 
VALUES ('Admin', '+1234567890', 'admin@hotel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
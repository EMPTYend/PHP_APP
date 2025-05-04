USE hotel_db;

-- Создание таблицы pictures
CREATE TABLE IF NOT EXISTS pictures (
    id_pictures INT AUTO_INCREMENT PRIMARY KEY,
    road VARCHAR(255) NOT NULL
);

-- Создание таблицы rooms
CREATE TABLE IF NOT EXISTS rooms (
    id_room INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    peoples INT NOT NULL,
    rooms INT NOT NULL,
    bed VARCHAR(50) NOT NULL,
    id_pictures INT,
    FOREIGN KEY (id_pictures) REFERENCES pictures(id_pictures)
);

-- Создание таблицы user
CREATE TABLE IF NOT EXISTS user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user'
);

-- Создание таблицы query
CREATE TABLE IF NOT EXISTS query (
    id_query INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    peoples INT NOT NULL,
    FOREIGN KEY (id_user) REFERENCES user(id_user)
);
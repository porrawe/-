-- สร้างฐานข้อมูล
CREATE DATABASE field_booking;

USE field_booking;

-- ตารางสำหรับผู้ใช้
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user';
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ตารางสำหรับสนาม
CREATE TABLE fields (
    field_id INT PRIMARY KEY AUTO_INCREMENT,
    field_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ตารางการจองสนาม
CREATE TABLE bookings (
    booking_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,   -- เชื่อมโยงกับผู้ใช้
    field_id INT,  -- เชื่อมโยงกับสนาม
    name VARCHAR(100),  -- คอลัมน์ชื่อผู้จอง
    sport_type VARCHAR(100) NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    duration INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),  -- เชื่อมโยงกับตารางผู้ใช้
    FOREIGN KEY (field_id) REFERENCES fields(field_id) -- เชื่อมโยงกับตารางสนาม
);

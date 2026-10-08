-- =====================================================
-- E-COMMERCE DATABASE
-- This file creates all tables needed for the website
-- =====================================================
-- Create the database
CREATE DATABASE IF NOT EXISTS shoevinir;
USE shoevinir;
-- =====================================================
-- USERS TABLE
-- Stores customer and admin accounts
-- =====================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,          -- Hashed password
    role ENUM('user', 'admin') DEFAULT 'user', -- user or admin
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- =====================================================
-- PRODUCTS TABLE
-- Stores all product information
-- =====================================================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),                      -- Image filename
    category VARCHAR(50),
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- =====================================================
-- CART TABLE
-- Stores items in user's shopping cart
-- =====================================================
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
-- =====================================================
-- ORDERS TABLE
-- Stores completed orders
-- =====================================================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered') DEFAULT 'pending',
    shipping_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
-- =====================================================
-- ORDER ITEMS TABLE
-- Stores individual items in each order
-- =====================================================
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,           -- Price at time of purchase
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
-- =====================================================
-- SAMPLE DATA
-- =====================================================
-- Sample Admin Account
-- Username: admin | Password: admin123
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@shop.com', '$2y$10$8K1p/a0dL1LXMIgoEDFrwOfMQgUWtcIxAr0x0G3qhThLqt5RZ0Pxe', 'admin');
-- Sample User Account
-- Username: user | Password: user123
INSERT INTO users (username, email, password, role) VALUES
('user', 'user@shop.com', '$2y$10$YgPIWjOdTzAZB3DlKIeZjOx0xhXvq0q4b7r0aZxWq2l0fE8m0hF6e', 'user');
-- Sample Products
INSERT INTO products (name, description, price, image, category, stock) VALUES
('P-6000', 'Stylish Nike P-6000 sneakers with comfort design and breathable material.', 40, 'p6000.jpg', 'Shoes', 50),

('Nike AirMax 97', 'Classic AirMax 97 with full-length air cushioning for maximum comfort.', 37, 'airmax97.jpg', 'Shoes', 60),

('Air Jordan 14 Ferrari', 'Premium Jordan 14 inspired by Ferrari design, luxury performance sneaker.', 215, 'jordan14.jpg', 'Shoes', 25),

('Nike SB Dunk Low', 'Skateboarding classic Dunk Low with durable leather upper.', 110, 'dunklow.jpg', 'Shoes', 80),

('Air Jordan 1 High Cut', 'Iconic Jordan 1 high-top sneaker with timeless street style.', 185, 'jordan1high.jpg', 'Shoes', 70),

('Air Jordan 1 x Travis Scott', 'Limited edition Travis Scott collaboration with reverse swoosh design.', 175, 'travisscott.jpg', 'Shoes', 20),

('Kobe 6 BHM', 'Performance basketball shoe honoring Black History Month edition.', 2424, 'kobe6.jpg', 'Shoes', 10),

('Jordan Luka 2 Space Hunter', 'Luka Doncic signature basketball shoe with lightweight support.', 105, 'luka2.jpg', 'Shoes', 40),

('Nike PG 2 Playstation', 'PlayStation collaboration sneaker inspired by gaming design.', 500, 'pg2.jpg', 'Shoes', 15),

('Nike KD 16 Slim Reaper', 'Kevin Durant signature shoe built for elite performance.', 160, 'kd16.jpg', 'Shoes', 35),

('Nike Book 1 1995 All Star', 'Devin Booker signature shoe inspired by 90s All-Star style.', 150, 'book1.jpg', 'Shoes', 30);
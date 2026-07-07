CREATE DATABASE IF NOT EXISTS ecommerce;
USE ecommerce;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    product_name VARCHAR(100),
    description TEXT,
    category VARCHAR(100),
    price DECIMAL(10,2),
    stock INT DEFAULT 0,
    image VARCHAR(255),
    created_at DATE
);

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    name VARCHAR(100),
    price DECIMAL(10,2),
    image VARCHAR(255)
);

CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    name VARCHAR(100),
    price DECIMAL(10,2),
    image VARCHAR(255)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    name VARCHAR(100),
    price DECIMAL(10,2),
    image VARCHAR(255),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
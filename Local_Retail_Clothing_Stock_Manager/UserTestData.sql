-- Sample Users
INSERT INTO user (username, password, role) VALUES
('admin', 'admin123', 'admin'),
('employee1', 'stockpass1', 'employee'),
('manager1', 'secureman', 'manager');

-- Sample Clothing Products
INSERT INTO product (name, brand, category, size, color, price, quantity, low_stock_threshold, created_at, updated_at) VALUES
('Slim Fit Jeans', 'Levi\'s', 'Pants', 'M', 'Blue', '$49.99', 10, 3, NOW(), NOW()),
('Classic Polo Shirt', 'Ralph Lauren', 'Shirts', 'L', 'White', '$69.00', 5, 2, NOW(), NOW()),
('Running Shoes', 'Nike', 'Footwear', '10', 'Black', '$89.99', 15, 5, NOW(), NOW()),
('Graphic Tee', 'H&M', 'Shirts', 'S', 'Red', '$14.99', 8, 2, NOW(), NOW()),
('Windbreaker Jacket', 'Adidas', 'Outerwear', 'XL', 'Green', '$59.50', 4, 2, NOW(), NOW()),
('Formal Trousers', 'Zara', 'Pants', 'M', 'Gray', '$39.95', 3, 2, NOW(), NOW()),
('Sneakers', 'Converse', 'Footwear', '9', 'White', '$54.95', 12, 4, NOW(), NOW());

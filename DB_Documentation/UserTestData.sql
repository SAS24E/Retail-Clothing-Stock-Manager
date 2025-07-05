-- DELETE existing data
DELETE FROM sales_log;
DELETE FROM product;
DELETE FROM user;

-- RESET auto-increment counters
ALTER TABLE product AUTO_INCREMENT = 1;
ALTER TABLE user AUTO_INCREMENT = 1;
ALTER TABLE sales_log AUTO_INCREMENT = 1;

-- INSERT Sample Users
INSERT INTO user (username, password, role, status, join_date) VALUES
('admin', 'admin123', 'admin', 'active', NOW()),
('employee', 'employee123', 'employee', 'active', NOW()),
('john_doe', 'test123', 'employee', 'inactive', NOW()),
('jane_smith', 'hello123', 'employee', 'active', NOW());

-- INSERT Sample Products (with status field)
INSERT INTO product (name, brand, category, size, color, price, quantity, low_stock_threshold, created_at, updated_at, status) VALUES
('Slim Fit Jeans', 'Levi\'s', 'Pants', 'M', 'Blue', '$49.99', 10, 3, NOW(), NOW(), 'approved'),
('Classic Polo Shirt', 'Ralph Lauren', 'Shirts', 'L', 'White', '$69.00', 5, 2, NOW(), NOW(), 'approved'),
('Running Shoes', 'Nike', 'Footwear', '10', 'Black', '$89.99', 15, 5, NOW(), NOW(), 'approved'),
('Graphic Tee', 'H&M', 'Shirts', 'S', 'Red', '$14.99', 8, 2, NOW(), NOW(), 'pending'),
('Windbreaker Jacket', 'Adidas', 'Outerwear', 'XL', 'Green', '$59.50', 4, 2, NOW(), NOW(), 'rejected'),
('Formal Trousers', 'Zara', 'Pants', 'M', 'Gray', '$39.95', 3, 2, NOW(), NOW(), 'approved'),
('Sneakers', 'Converse', 'Footwear', '9', 'White', '$54.95', 12, 4, NOW(), NOW(), 'pending');

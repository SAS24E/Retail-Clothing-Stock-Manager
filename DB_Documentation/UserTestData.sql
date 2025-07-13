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
('Graphic Tee', 'H&M', 'Shirts', 'S', 'Red', '$14.99', 8, 2, NOW(), NOW(), 'approved'),
('Windbreaker Jacket', 'Adidas', 'Outerwear', 'XL', 'Green', '$59.50', 4, 2, NOW(), NOW(), 'approved'),
('Formal Trousers', 'Zara', 'Pants', 'M', 'Gray', '$39.95', 3, 2, NOW(), NOW(), 'approved'),
('Sneakers', 'Converse', 'Footwear', '9', 'White', '$54.95', 12, 4, NOW(), NOW(), 'approved');

-- ==== New test data 07/13/25 ==== 


-- DELETE existing data 
DELETE FROM sales_log;
DELETE FROM product;
DELETE FROM user;

-- RESET auto-increment counters
ALTER TABLE product AUTO_INCREMENT = 1;
ALTER TABLE user AUTO_INCREMENT = 1;
ALTER TABLE sales_log AUTO_INCREMENT = 1;

-- INSERT Sample Users with realistic join dates
INSERT INTO user (username, password, role, status, join_date) VALUES
('admin', 'admin123', 'admin', 'active', DATE_SUB(NOW(), INTERVAL 28 DAY)),      -- ID = 1
('employee', 'employee123', 'employee', 'active', DATE_SUB(NOW(), INTERVAL 20 DAY)), -- ID = 2
('john_doe', 'test123', 'employee', 'inactive', DATE_SUB(NOW(), INTERVAL 10 DAY)),   -- ID = 3
('jane_smith', 'hello123', 'employee', 'active', DATE_SUB(NOW(), INTERVAL 5 DAY));   -- ID = 4

-- INSERT Sample Products with realistic dates and added_by users
INSERT INTO product (name, brand, category, size, color, price, quantity, low_stock_threshold, created_at, updated_at, status, added_by) VALUES
('Slim Fit Jeans', 'Levi''s', 'Pants', 'M', 'Blue', '$49.99', 10, 3, DATE_SUB(NOW(), INTERVAL 25 DAY), DATE_SUB(NOW(), INTERVAL 25 DAY), 'approved', 1),
('Classic Polo Shirt', 'Ralph Lauren', 'Shirts', 'L', 'White', '$69.00', 5, 2, DATE_SUB(NOW(), INTERVAL 18 DAY), DATE_SUB(NOW(), INTERVAL 18 DAY), 'approved', 2),
('Running Shoes', 'Nike', 'Footwear', '10', 'Black', '$89.99', 15, 5, DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 15 DAY), 'approved', 2),
('Graphic Tee', 'H&M', 'Shirts', 'S', 'Red', '$14.99', 8, 2, DATE_SUB(NOW(), INTERVAL 9 DAY), DATE_SUB(NOW(), INTERVAL 8 DAY), 'approved', 4),
('Windbreaker Jacket', 'Adidas', 'Outerwear', 'XL', 'Green', '$59.50', 4, 2, DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY), 'approved', 1),
('Formal Trousers', 'Zara', 'Pants', 'M', 'Gray', '$39.95', 3, 2, DATE_SUB(NOW(), INTERVAL 4 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY), 'approved', 3),
('Sneakers', 'Converse', 'Footwear', '9', 'White', '$54.95', 12, 4, DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY), 'approved', 1);

-- INSERT Sample Sales Log entries (linked to real users/products)
INSERT INTO sales_log (quantity, sold_at, user_iduser, product_idproduct) VALUES
(2, DATE_SUB(NOW(), INTERVAL 22 DAY), 2, 1), -- employee sold Slim Fit Jeans
(1, DATE_SUB(NOW(), INTERVAL 17 DAY), 1, 2), -- admin sold Classic Polo Shirt
(3, DATE_SUB(NOW(), INTERVAL 14 DAY), 2, 3), -- employee sold Running Shoes
(2, DATE_SUB(NOW(), INTERVAL 7 DAY), 4, 4),  -- jane_smith sold Graphic Tee
(1, DATE_SUB(NOW(), INTERVAL 5 DAY), 1, 5),  -- admin sold Windbreaker
(1, DATE_SUB(NOW(), INTERVAL 3 DAY), 3, 6),  -- john_doe (inactive) sold Formal Trousers
(4, DATE_SUB(NOW(), INTERVAL 1 DAY), 2, 7);  -- employee sold Sneakers

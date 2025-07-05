-- DROP TABLES IN DEPENDENCY ORDER
DROP TABLE IF EXISTS sales_log;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS user;

-- Create product table
CREATE TABLE IF NOT EXISTS product (
  idproduct INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(45),
  brand VARCHAR(45),
  category VARCHAR(45),
  size VARCHAR(45),
  color VARCHAR(45),
  price VARCHAR(45),
  quantity INT,
  low_stock_threshold INT,
  created_at DATETIME,
  updated_at DATETIME,
  status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending', 
  PRIMARY KEY (idproduct)
) ENGINE=InnoDB;


-- Create user table
CREATE TABLE IF NOT EXISTS user (
  iduser INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(45),
  password VARCHAR(255),
  role VARCHAR(45),
  PRIMARY KEY (iduser)
) ENGINE=InnoDB;

-- Create sales_log table
CREATE TABLE IF NOT EXISTS sales_log (
  idsales_log INT NOT NULL AUTO_INCREMENT,
  quantity INT,
  sold_at DATETIME,
  user_iduser INT NOT NULL,
  product_idproduct INT NOT NULL,
  PRIMARY KEY (idsales_log),
  FOREIGN KEY (user_iduser) REFERENCES user(iduser),
  FOREIGN KEY (product_idproduct) REFERENCES product(idproduct)
) ENGINE=InnoDB;

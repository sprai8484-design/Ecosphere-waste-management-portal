-- ================================================================
-- ECOSPHERE RESELL MODULE — DATABASE SETUP
-- Run in phpMyAdmin → SQL tab → Go
-- ================================================================

CREATE DATABASE IF NOT EXISTS ecosphere_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecosphere_db;

-- ---------------------------------------------------------------
-- TABLE: resell_users (simple session-based, no auth required)
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS resell_users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    phone      VARCHAR(20)  DEFAULT NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------
-- TABLE: resell_products
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS resell_products (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    product_uid  VARCHAR(20)  NOT NULL UNIQUE COMMENT 'ECO-RSL-XXXXX',
    user_id      INT          DEFAULT NULL,
    seller_name  VARCHAR(100) NOT NULL,
    seller_email VARCHAR(150) NOT NULL,
    seller_phone VARCHAR(20)  DEFAULT NULL,
    product_name VARCHAR(255) NOT NULL,
    category     VARCHAR(100) NOT NULL,
    description  TEXT         NOT NULL,
    `condition`  VARCHAR(50)  NOT NULL,
    quantity     INT          NOT NULL DEFAULT 1,
    price        DECIMAL(10,2) NOT NULL,
    image        VARCHAR(255) DEFAULT NULL,
    status       ENUM('Pending','Approved','Sold','Rejected') NOT NULL DEFAULT 'Pending',
    admin_note   TEXT         DEFAULT NULL,
    reviewed_by  VARCHAR(100) DEFAULT NULL,
    reviewed_at  TIMESTAMP    DEFAULT NULL,
    created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES resell_users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------
-- TABLE: resell_transactions
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS resell_transactions (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    transaction_uid  VARCHAR(25) NOT NULL UNIQUE COMMENT 'TXN-ECO-XXXXXXXXX',
    product_id       INT         NOT NULL,
    buyer_name       VARCHAR(100) NOT NULL,
    buyer_email      VARCHAR(150) NOT NULL,
    buyer_phone      VARCHAR(20)  DEFAULT NULL,
    seller_name      VARCHAR(100) NOT NULL,
    seller_email     VARCHAR(150) NOT NULL,
    product_name     VARCHAR(255) NOT NULL,
    product_uid      VARCHAR(20)  NOT NULL,
    quantity         INT          NOT NULL DEFAULT 1,
    unit_price       DECIMAL(10,2) NOT NULL,
    total_price      DECIMAL(10,2) NOT NULL,
    payment_method   VARCHAR(50)  NOT NULL DEFAULT 'Card',
    payment_status   ENUM('Success','Failed','Refunded') NOT NULL DEFAULT 'Success',
    card_last4       VARCHAR(4)   DEFAULT NULL COMMENT 'Last 4 digits (demo)',
    transaction_date TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES resell_products(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------
-- TABLE: resell_admin_users
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS resell_admin_users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(60)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL COMMENT 'SHA-256',
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT IGNORE INTO resell_admin_users (username, password)
VALUES ('admin', SHA2('admin123', 256));

-- ---------------------------------------------------------------
-- SEED DATA: Sample approved products
-- ---------------------------------------------------------------
INSERT IGNORE INTO resell_users (id, name, email, phone) VALUES
(1, 'Priya Sharma',   'priya@example.com',  '+91 98765 43210'),
(2, 'Rahul Mehta',    'rahul@example.com',  '+91 87654 32109'),
(3, 'Sneha Kulkarni', 'sneha@example.com',  '+91 76543 21098');

INSERT IGNORE INTO resell_products
  (product_uid, user_id, seller_name, seller_email, seller_phone,
   product_name, category, description, `condition`, quantity, price, status) VALUES

('ECO-RSL-00001', 1, 'Priya Sharma', 'priya@example.com', '+91 98765 43210',
 'Dell Latitude Laptop (i5, 8GB RAM)',
 'Electronics',
 'Dell Latitude E7470 in excellent working condition. Battery holds charge for 4+ hours. Comes with original charger. Minor cosmetic scratches on lid only. Windows 11 installed.',
 'Like New', 1, 18500.00, 'Approved'),

('ECO-RSL-00002', 2, 'Rahul Mehta', 'rahul@example.com', '+91 87654 32109',
 'Solid Wood Study Table',
 'Furniture',
 'Teak wood study table with 3 drawers and a cable management hole. Dimensions: 4ft x 2ft. Minor surface scratches but structurally very solid. Dismantled for easy transport.',
 'Used', 1, 4200.00, 'Approved'),

('ECO-RSL-00003', 3, 'Sneha Kulkarni', 'sneha@example.com', '+91 76543 21098',
 'Collection of 20 Classic Novels',
 'Books',
 'Mix of Penguin Classics and Oxford editions. Includes: Pride and Prejudice, Great Gatsby, 1984, To Kill a Mockingbird, and 16 more. All in good readable condition, some have pencil annotations.',
 'Used', 20, 850.00, 'Approved'),

('ECO-RSL-00004', 1, 'Priya Sharma', 'priya@example.com', '+91 98765 43210',
 'Levi''s Denim Jacket (Size M)',
 'Clothes',
 'Genuine Levi''s trucker jacket in classic blue wash. Worn twice, essentially new. Size M (fits 38-40 chest). No stains, no damage.',
 'Like New', 1, 1800.00, 'Approved'),

('ECO-RSL-00005', 2, 'Rahul Mehta', 'rahul@example.com', '+91 87654 32109',
 'Sony WH-1000XM3 Headphones',
 'Electronics',
 'Sony premium noise-cancelling headphones. Works perfectly, ANC is excellent. Comes with case, cables and original box. One ear pad has slight wear from heavy use.',
 'Used', 1, 7500.00, 'Approved');

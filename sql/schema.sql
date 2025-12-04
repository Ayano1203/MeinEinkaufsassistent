DROP DATABASE IF EXISTS mein_einkaufsassistent;

-- Neue Datenbank erstellen
CREATE DATABASE mein_einkaufsassistent CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE mein_einkaufsassistent;

CREATE TABLE IF NOT EXISTS category(
    category_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS storage(
    storage_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    name VARCHAR(255)
);
CREATE TABLE IF NOT EXISTS product(
      product_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(255),
      category_ID INT ,
      minimum_stock INT,
      unit VARCHAR(255),
      FOREIGN KEY (category_ID)
          REFERENCES category(category_id)
          ON DELETE RESTRICT
    );
CREATE TABLE IF NOT EXISTS shoppinglist(
    shoppinglist_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    product_id INT NOT NULL,
    quantity INT,
    unit VARCHAR(255),
    added_date DATE,
    status ENUM('offen','erledigt'),
    FOREIGN KEY (product_id)
        REFERENCES product(product_id)
        ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS inventory(
    inventory_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    quantity INT,
    unit VARCHAR(255),
    expiry_date DATE,
    storage_id INT,
    FOREIGN KEY (product_id)
        REFERENCES product(product_id)
        ON DELETE RESTRICT,
    FOREIGN KEY (storage_id)
        REFERENCES storage(storage_id)
        ON DELETE RESTRICT
);
CREATE TABLE IF NOT EXISTS notification(
    notification_id INT PRIMARY KEY NOT NULL,
    inventory_id INT,
    sent_date DATE,
    FOREIGN KEY (inventory_id)
        REFERENCES inventory(inventory_id)
        ON DELETE RESTRICT
);

INSERT INTO category(name) VALUES
                              ('Milchprodukte'),
                              ('Getreide & Backen'),
                              ('Obst'),
                              ('Gemüse');

INSERT INTO storage(name) VALUES
                              ('Kühlschlank'),
                              ('Vorratskammer'),
                              ('Gefrierschrank');

INSERT INTO product(name, minimum_stock, unit) VALUES
                           ('Milch', 1, 'Packung'),
                           ('Mehl', 2, 'kg'),
                           ('Äpfel', 3,'Stück');

INSERT INTO inventory(product_id, quantity, unit, expiry_date, storage_id) VALUES
                      (1,0,'Packung', '2025-11-30',1),
                      (2,3,'Kg', '2026-05-05',2),
                      (3, 8,'Stück', '2025-12-03', 1);

# produkte suchen, die minimum_stock unterstreicht
SELECT P.name, I.quantity, P.minimum_stock
FROM inventory I
INNER JOIN product P on I.product_id = p.product_id
WHERE I.quantity < P.minimum_stock;

# produkte von inventory suchen, die innerhalb von 7 Tage expiry_date haben
SELECT P.name, I.expiry_date
       FROM inventory I
INNER JOIN product P on I.inventory_id = P.product_id
WHERE DATEDIFF(I.expiry_date, CURRENT_DATE()) <=7;

# Produkte suchen, die minimum-stock unterstreicht ODER innerhalb von 7 Tagen expiry_date haben
SELECT P.name, I.quantity, I.expiry_date
FROM inventory I
INNER JOIN product p on I.product_id = p.product_id
WHERE I.quantity < P.minimum_stock OR DATEDIFF(I.expiry_date, CURRENT_DATE()) <=7;

INSERT INTO shoppinglist (product_id, quantity, unit, added_date, status)
VALUES (1,2, 'Packung', current_date(),'offen');


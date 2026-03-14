CREATE DATABASE IF NOT EXISTS swiftship_db;
USE swiftship_db;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE shipments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tracking_number VARCHAR(20) NOT NULL UNIQUE,
    sender_name VARCHAR(100) NOT NULL,
    sender_city VARCHAR(100) NOT NULL,
    receiver_name VARCHAR(100) NOT NULL,
    receiver_city VARCHAR(100) NOT NULL,
    parcel_weight DECIMAL(10,2) NOT NULL,
    delivery_type VARCHAR(50) NOT NULL,
    current_status VARCHAR(50) DEFAULT 'Booked',
    shipment_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tracking_updates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shipment_id INT NOT NULL,
    status_message VARCHAR(255) NOT NULL,
    location VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (shipment_id) REFERENCES shipments(id) ON DELETE CASCADE
);

-- Insert default admin: admin / password123
INSERT INTO admins (username, password) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

CREATE DATABASE IF NOT EXISTS tienda_cocina;
USE tienda_cocina;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    imagen VARCHAR(255)
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE detalle_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    producto_id INT,
    cantidad INT,
    precio DECIMAL(10, 2),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- Datos de prueba
INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES
('Sartén de Hierro', 'Ideal para sellar carnes.', 45.99, 'https://images.unsplash.com/photo-1590794056226-79ef3a8147e1?q=80&w=200'),
('Set de Cuchillos', 'Acero inoxidable profesional.', 89.50, 'https://images.unsplash.com/photo-1593618998160-e34014e67546?q=80&w=200'),
('Espátula de Silicona', 'Resistente a altas temperaturas.', 12.00, 'https://images.unsplash.com/photo-1594385208974-2e75f9d8ad48?q=80&w=200'),
('Olla a Presión', 'Cocción rápida y segura.', 65.00, 'https://images.unsplash.com/photo-1584990344111-a20c223c6046?q=80&w=200');
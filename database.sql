-- Base de datos para el blog
CREATE DATABASE IF NOT EXISTS blog_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE blog_db;

-- Tabla para los posts del blog
CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla para usuarios del sistema
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor', 'user') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME NULL,
    is_active BOOLEAN DEFAULT TRUE
);

-- Insertar usuarios del sistema
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@tecblog.com', 'SecurePass2024', 'admin'),
('carlos_editor', 'carlos.martinez@tecblog.com', 'Editorial456', 'editor'),
('ana_garcia', 'ana.garcia@gmail.com', 'MyPassword789', 'user'),
('luis_rodriguez', 'luis.rod@outlook.com', 'LuisPass2024', 'user'),
('sofia_lopez', 'sofia.lopez@yahoo.com', 'Sofia123Safe', 'user');

-- Insertar algunos posts de ejemplo
INSERT INTO posts (title, content) VALUES 
('Bienvenido a mi blog', 'Este es mi primer post en el blog. Aquí compartiré mis pensamientos, experiencias y conocimientos sobre diversos temas interesantes.\n\nEspero que disfrutes leyendo mis artículos y no dudes en dejar tus comentarios y sugerencias.'),

('Consejos para programar mejor', 'La programación es un arte que requiere práctica constante. Aquí algunos consejos que me han ayudado a mejorar:\n\n1. Escribe código limpio y comentado\n2. Usa nombres descriptivos para variables y funciones\n3. Divide problemas complejos en partes más pequeñas\n4. Practica regularmente con diferentes proyectos\n5. Lee código de otros programadores\n\n¡Nunca dejes de aprender!'),

('Mi experiencia con PHP', 'PHP ha sido mi compañero de desarrollo web durante varios años. Es un lenguaje versátil que permite crear desde sitios web simples hasta aplicaciones complejas.\n\nLo que más me gusta de PHP es su simplicidad para empezar y la gran comunidad que lo respalda. Además, la integración con bases de datos como MySQL es muy directa.\n\n¿Cuál ha sido tu experiencia con PHP?');
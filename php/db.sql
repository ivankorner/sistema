-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS nu CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Usar la base de datos
USE nu;

-- Crear tabla para almacenar los datos principales
CREATE TABLE IF NOT EXISTS datos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    instrumento VARCHAR(255) NOT NULL,
    year YEAR NOT NULL,
    descripcion VARCHAR(140) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear tabla para almacenar los anexos
CREATE TABLE IF NOT EXISTS anexos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expediente_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (expediente_id) REFERENCES datos(id) ON DELETE CASCADE
);
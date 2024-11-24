CREATE DATABASE IF NOT EXISTS denuncia;

USE denuncia;

CREATE TABLE IF NOT EXISTS categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(60) NOT NULL
);

CREATE TABLE IF NOT EXISTS provincia (
    id_provincia INT AUTO_INCREMENT PRIMARY KEY,
    nombre_provincia VARCHAR(40) NOT NULL
);

CREATE TABLE IF NOT EXISTS ciudadano (
    id_ciudadano VARCHAR(30) PRIMARY KEY,
    nombre_ciudadano VARCHAR(80) NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    correoelectronico VARCHAR(80)
);

CREATE TABLE IF NOT EXISTS denuncia (
    id_denuncia INT AUTO_INCREMENT PRIMARY KEY,
    descripcion_denuncia VARCHAR(150) NOT NULL,
    id_ciudadano VARCHAR(30) NOT NULL,
    id_categoria INT NOT NULL,
    id_provincia INT NOT NULL,
    fecha_denuncia DATE NOT NULL,
    estatus_denuncia CHAR(1) NOT NULL,
    FOREIGN KEY (id_ciudadano) REFERENCES ciudadano(id_ciudadano),
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria),
    FOREIGN KEY (id_provincia) REFERENCES provincia(id_provincia)
);

CREATE TABLE IF NOT EXISTS usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(40) NOT NULL,
    apellido_usuario VARCHAR(60) NOT NULL,
    correo_usuario VARCHAR(100),
    password VARCHAR(30) NOT NULL
);

INSERT INTO provincia (nombre_provincia) VALUES 
('Bocas del Toro'), ('Coclé'), ('Colón'), ('Chiriquí'),
('Darién'), ('Herrera'), ('Los Santos'), ('Panamá'),
('Panamá Oeste'), ('Veraguas');

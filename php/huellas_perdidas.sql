CREATE DATABASE IF NOT EXISTS HuellasPerdidas;
USE HuellasPerdidas;

CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    rol ENUM('usuario','admin') DEFAULT 'usuario',
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    nombre_usuario VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    clave VARCHAR(255) NOT NULL,
    foto_perfil VARCHAR(255),
    telefono VARCHAR(20),
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
) ENGINE=InnoDB;

CREATE TABLE mascota (
    id_mascota INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    edad INT,
    color VARCHAR(50),
    tamaño ENUM('chico','mediano','grande'),
    especie VARCHAR(50) NOT NULL,
    raza VARCHAR(50),
    id_usuario INT,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE publicacion (
    id_publicacion INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    imagen VARCHAR(255),
    estado ENUM('perdido','encontrado','resuelto') DEFAULT 'perdido',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_evento DATE,
    zona VARCHAR(100) NOT NULL,
    descripcion TEXT,
    id_usuario INT,
    id_mascota INT,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_mascota) REFERENCES mascota(id_mascota)
) ENGINE=InnoDB;

CREATE TABLE comentario (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    contenido TEXT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_usuario INT,
    id_publicacion INT,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_publicacion) REFERENCES publicacion(id_publicacion)
) ENGINE=InnoDB;

CREATE TABLE avistamiento (
    id_avistamiento INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    direccion VARCHAR(150),
    latitud DECIMAL(10,7),
    longitud DECIMAL(10,7),
    id_usuario INT,
    id_publicacion INT,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_publicacion) REFERENCES publicacion(id_publicacion)
) ENGINE=InnoDB;


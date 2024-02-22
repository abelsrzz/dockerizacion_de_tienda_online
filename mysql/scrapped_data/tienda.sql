CREATE USER IF NOT EXISTS 'myadmin'@'%' IDENTIFIED BY "abc123.";
GRANT ALL PRIVILEGES ON *.* TO 'myadmin'@'%';
DROP DATABASE IF EXISTS tienda;
CREATE DATABASE tienda;
USE tienda;

CREATE TABLE categoria(
	id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL
);
CREATE TABLE producto(
	id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(500) NOT NULL,
    imagen VARCHAR(500) NOT NULL,
    precio FLOAT NOT NULL,
    especificaciones TEXT,
    marca VARCHAR(200) NOT NULL,
    id_categoria INT NOT NULL
);
CREATE TABLE categoria_producto(
	id_categoria INT,
    id_producto INT,
    PRIMARY KEY (id_categoria, id_producto)
);

CREATE TABLE usuario(
	id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(200) NOT NULL,
    nombre VARCHAR(50),
    apellido1 VARCHAR(50),
    apellido2 VARCHAR(50),
    mail VARCHAR(200) NOT NULL
);

CREATE TABLE productos_en_cesta_usuario(
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL
);



LOAD DATA INFILE '/data/product_data.csv' 
INTO TABLE producto 
FIELDS TERMINATED BY ';' 
ENCLOSED BY "'" 
LINES TERMINATED BY '\n' 
IGNORE 1 LINES
(id, nombre, imagen, precio, especificaciones, marca);

LOAD DATA INFILE '/data/category_data.csv' 
INTO TABLE categoria 
FIELDS TERMINATED BY ';' 
ENCLOSED BY "'" 
LINES TERMINATED BY '\n'  
(id, nombre);

LOAD DATA INFILE '/data/product_category_data.csv' 
INTO TABLE categoria_producto 
FIELDS TERMINATED BY ';' 
ENCLOSED BY "'" 
LINES TERMINATED BY '\n' 
(id_categoria, id_producto);

ALTER TABLE categoria_producto ADD CONSTRAINT FOREIGN KEY (id_producto) REFERENCES producto(id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE categoria_producto ADD CONSTRAINT FOREIGN KEY (id_categoria) REFERENCES categoria(id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE productos_en_cesta_usuario ADD CONSTRAINT FOREIGN KEY (id_usuario) REFERENCES usuario(id) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE productos_en_cesta_usuario ADD CONSTRAINT FOREIGN KEY (id_producto) REFERENCES producto(id) ON DELETE CASCADE ON UPDATE CASCADE;

-- 1.- Database creation

CREATE DATABASE IF NOT EXISTS estacionamiento;
USE estacionamiento;

-- 2.- Table definitions

DROP TABLE IF EXISTS coches;
DROP TABLE IF EXISTS mantenimientos;
DROP TABLE IF EXISTS reparaciones;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE coches(
	id_coche int NOT NULL PRIMARY KEY AUTO_INCREMENT,
	placa varchar(20) UNIQUE NOT NULL,
	marca_modelo varchar(100) NOT NULL,
	nombre_vehiculo varchar(100) DEFAULT NULL,
	combustible enum('gasolina','diesel','hibrido','electrico') DEFAULT 'gasolina',
	ejes int DEFAULT 2,
	anio int DEFAULT NULL,
	tipo_vehiculo varchar(50) DEFAULT NULL,
	foto_ruta varchar(255) DEFAULT NULL,
	estado enum('en_parking','entregado') NOT NULL DEFAULT 'en_parking'
);

CREATE TABLE mantenimientos(
	id_mantenimiento int NOT NULL PRIMARY KEY AUTO_INCREMENT,
	id_coche int NOT NULL,
	id_usuario int NOT NULL,
	tipo enum('aceite','llantas','preventivo','otro') NOT NULL,
	descripcion text DEFAULT NULL,
	fecha datetime NOT NULL DEFAULT current_timestamp(),
	FOREIGN KEY (id_coche) REFERENCES coches (id_coche) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario) ON UPDATE CASCADE
);

CREATE TABLE reparaciones(
	id_reparacion int NOT NULL PRIMARY KEY AUTO_INCREMENT,
	id_coche int NOT NULL,
	id_usuario int NOT NULL,
	falla varchar(100) NOT NULL,
	detalles text DEFAULT NULL,
	fecha datetime NOT NULL DEFAULT current_timestamp(),
	FOREIGN KEY (id_coche) REFERENCES  coches (id_coche) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario) ON UPDATE CASCADE
);

CREATE TABLE usuarios(
	id_usuario int NOT NULL PRIMARY KEY AUTO_INCREMENT,
	username varchar(50) UNIQUE NOT NULL,
	password varchar(255) NOT NULL,
	rol enum('admin', 'mecanico_a','mecanico_b') NOT NULL,
	);

-- 1. Insert Users (Parent table)
-- Roles are defined based on the ENUM ('admin', 'mecanico_a', 'mecanico_b')
INSERT INTO usuarios (username, password, rol) VALUES 
('igonzalez', 'hash_seguro_aqui_123', 'admin'),
('jperez', 'hash_seguro_aqui_456', 'mecanico_a');

-- 2. Insert Cars (Parent table)
-- id_coche is omitted because it is AUTO_INCREMENT. 
-- 'estado' is omitted to allow it to take the DEFAULT value 'en_parking'
INSERT INTO coches (placa, marca_modelo, nombre_vehiculo, combustible, anio, tipo_vehiculo) VALUES 
('ABC-123', 'Toyota Hilux', 'Unidad de Rescate', 'diesel', 2019, 'Pickup'),
('DEF-456', 'Nissan Tsuru B-13', 'Vehículo Personal', 'gasolina', 2005, 'Sedán');

-- 3. Insert Maintenances (Child table)
-- Requires valid IDs that already exist in the coches and usuarios tables.
-- 'fecha' is omitted so the DEFAULT current_timestamp() takes effect.
INSERT INTO mantenimientos (id_coche, id_usuario, tipo, descripcion) VALUES 
(1, 2, 'preventivo', 'Revisión general de frenos y niveles de suspensión.'),
(2, 1, 'aceite', 'Cambio de aceite 15W-40 y revisión del nivel con bayoneta nueva.');

-- 4. Insert Repairs (Child table)
-- Requires valid IDs that already exist in the coches and usuarios tables.
INSERT INTO reparaciones (id_coche, id_usuario, falla, detalles) VALUES 
(2, 1, 'Calentamiento del motor', 'Reemplazo de la junta de cabeza del motor y limpieza profunda de los plásticos interiores de las puertas.');

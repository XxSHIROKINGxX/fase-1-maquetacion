-- Roséa Boutique, Fase 4: SOLO DATOS FICTICIOS.
-- Importar desde phpMyAdmin de XAMPP. No contiene datos ni claves de usuarios reales.
CREATE DATABASE IF NOT EXISTS `rosea_boutique_fase4` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `rosea_boutique_fase4`;
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `apellido` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `rol` ENUM('cliente','admin') NOT NULL DEFAULT 'cliente',
  `fecha_registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`), UNIQUE KEY `uk_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `productos` (
  `id_producto` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(150) NOT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `precio` DECIMAL(10,2) NOT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `categoria` VARCHAR(100) DEFAULT NULL,
  `imagen` VARCHAR(255) DEFAULT NULL,
  `disponible` TINYINT(1) NOT NULL DEFAULT 1,
  `fecha_creacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `pedidos` (
  `id_pedido` INT NOT NULL AUTO_INCREMENT,
  `id_usuario` INT NOT NULL,
  `fecha_pedido` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total` DECIMAL(10,2) NOT NULL,
  `estado` ENUM('pendiente','procesando','enviado','entregado','cancelado') NOT NULL DEFAULT 'pendiente',
  `telefono` VARCHAR(20) DEFAULT NULL,
  `provincia` VARCHAR(100) DEFAULT NULL,
  `ciudad` VARCHAR(100) DEFAULT NULL,
  `direccion` VARCHAR(255) DEFAULT NULL,
  `referencia` VARCHAR(255) DEFAULT NULL,
  `metodo_entrega` ENUM('delivery','retiro') DEFAULT NULL,
  `metodo_pago` ENUM('efectivo','transferencia') DEFAULT NULL,
  `empresa_envio` VARCHAR(100) DEFAULT NULL,
  `comprobante_pago` VARCHAR(255) DEFAULT NULL,
  `estado_pago` ENUM('pendiente','verificado','rechazado') NOT NULL DEFAULT 'pendiente',
  `fecha_comprobante` DATETIME DEFAULT NULL,
  `fecha_envio` DATETIME DEFAULT NULL,
  `codigo_seguimiento` VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (`id_pedido`), KEY `idx_usuario` (`id_usuario`),
  CONSTRAINT `fk_pedidos_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `detalle_pedido` (
  `id_detalle` INT NOT NULL AUTO_INCREMENT,
  `id_pedido` INT NOT NULL,
  `id_producto` INT NOT NULL,
  `cantidad` INT NOT NULL,
  `precio_unitario` DECIMAL(10,2) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id_detalle`), KEY `idx_pedido` (`id_pedido`), KEY `idx_producto` (`id_producto`),
  CONSTRAINT `fk_detalle_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE,
  CONSTRAINT `fk_detalle_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Demo administrador: admin.demo@rosea.test / RoseaDemo2026!
-- Demo cliente: cliente.demo@rosea.test / ClienteDemo2026!
-- Las contraseñas se almacenan como hashes bcrypt (PHP password_hash).
INSERT INTO `usuarios` (`nombre`,`apellido`,`email`,`password`,`rol`) VALUES
('Admin','Demostración','admin.demo@rosea.test','$2y$12$vhN9UTnj/PvjIsd9orMRbOeQFegZcV5tLWCsKYyj1nMqUiTAPbmWS','admin'),
('Cliente','Demostración','cliente.demo@rosea.test','$2y$12$qlMuN2qcgJW14peQ9i3MpeJLxxx0mHNzGNSL7LdnGhCZiHH9mbMRm','cliente');

INSERT INTO `productos` (`nombre`,`descripcion`,`precio`,`stock`,`categoria`,`imagen`,`disponible`) VALUES
('Vestido Rosa Elegance','Vestido elegante de estilo coquette en tono rosa.',1900.00,10,'Vestidos','vestido-rosa.jpg',1),
('Blusa Rosé','Blusa delicada para combinar con tus looks favoritos.',850.00,12,'Blusas','producto_20260930_014438_4c900604.png',1),
('Falda Romance','Falda de inspiración romántica para ocasiones especiales.',1200.00,8,'Faldas',NULL,1),
('Lazo Clásico','Accesorio de estilo coquette para el cabello.',250.00,20,'Accesorios',NULL,1);

-- FASE 4: Actualizacion del catalogo Rosea Boutique

UPDATE productos
SET imagen = 'vestido-rosa.jpg'
WHERE id_producto = 1;

UPDATE productos
SET imagen = 'blusa-fleur.PNG'
WHERE id_producto = 2;

UPDATE productos
SET imagen = 'falda-amour.PNG'
WHERE id_producto = 3;

UPDATE productos
SET imagen = 'Lazo-rosa.png'
WHERE id_producto = 4;

-- Quinto producto: Top Rosalie

INSERT INTO productos
(nombre, descripcion, precio, stock, categoria, imagen, disponible)
SELECT
'Top Rosalie',
'Top romantico en tono blanco, con delicados volantes y lazo frontal, ideal para un estilo coquette.',
800.00,
5,
'Blusas',
'top-rosalie.PNG',
1
WHERE NOT EXISTS (
    SELECT 1 FROM productos
    WHERE nombre = 'Top Rosalie'
);
-- Base de datos: pb_maps_db

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- 1. Tabla de Usuarios
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password_hash` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL DEFAULT 'usuario_regular',
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabla de Lugares Turísticos
CREATE TABLE `lugarturistico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `url_imagen` varchar(255) DEFAULT NULL,
  `fk_admin_id` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`fk_admin_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Usuario Administrador por Defecto (Clave: admin123)
-- Nota: Se inserta un admin base para pruebas
INSERT INTO `usuario` (`nombre_usuario`, `email`, `password_hash`, `rol`) VALUES
('Admin Principal', 'admin@pb.com', '$2y$10$eZ.vQ.f/g.h/i.j.k.l.m.n.o.p.q.r.s.t.u.v.w.x.y.z', 'administrador');

COMMIT;
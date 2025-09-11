-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 11, 2025 at 08:54 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `roots`
--

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL COMMENT 'Nombre del rol (ej. administrador, cliente)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla para almacenar los roles de los usuarios del sistema.';

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `nombre_rol`) VALUES
(1, 'administrador'),
(5, 'cliente'),
(4, 'deliver'),
(2, 'manager'),
(3, 'vendedor');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL COMMENT 'Llave foránea que apunta al id de la tabla roles.',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre completo del usuario.',
  `email` varchar(255) NOT NULL COMMENT 'Correo electrónico del usuario, debe ser único.',
  `password` varchar(255) DEFAULT NULL COMMENT 'Contraseña hasheada. Es NULL si se registra con un proveedor externo como Google.',
  `google_id` varchar(255) DEFAULT NULL COMMENT 'ID único proporcionado por Google para el inicio de sesión social.',
  `foto_perfil` varchar(255) DEFAULT NULL COMMENT 'URL de la imagen de perfil del usuario.',
  `estatus` enum('activo','inactivo','suspendido') NOT NULL DEFAULT 'activo' COMMENT 'Estado de la cuenta del usuario.',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha y hora de creación de la cuenta.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla principal para almacenar la información de los usuarios.';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

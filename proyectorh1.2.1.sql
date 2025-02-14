-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-02-2025 a las 09:10:13
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyectorh`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `Numero_Empleado` int(20) NOT NULL,
  `Nombre_Empleado` varchar(30) NOT NULL,
  `Contraseña_Empleado` varchar(20) DEFAULT '',
  `Area` varchar(30) NOT NULL,
  `Edad` int(3) NOT NULL,
  `Genero` varchar(12) NOT NULL,
  `Titulo` varchar(50) NOT NULL,
  `Fecha_Ingreso` date NOT NULL DEFAULT current_timestamp(),
  `Direccion` varchar(200) NOT NULL,
  `Telefono` varchar(12) NOT NULL,
  `Discapacidad` varchar(50) NOT NULL DEFAULT 'N/A',
  `Estado` varchar(30) NOT NULL DEFAULT 'Activo',
  `Dias_Extras` int(2) NOT NULL DEFAULT 0,
  `Dias` int(2) NOT NULL DEFAULT 24
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`Numero_Empleado`, `Nombre_Empleado`, `Contraseña_Empleado`, `Area`, `Edad`, `Genero`, `Titulo`, `Fecha_Ingreso`, `Direccion`, `Telefono`, `Discapacidad`, `Estado`, `Dias_Extras`, `Dias`) VALUES
(1, 'Admin', '1', 'Administrador de sistema', 1, 'fem', 'admin', '2025-01-31', 'w', '1', 'N/A', 'Activo', 0, 24),
(223, 'Vaqui', 'vaqui', 'RH', 4, 'fem', 'Todologa', '2025-01-29', 'Romanza', '233444222', 'N/A', 'Activo', 0, 24),
(444, 'Curli', '', 'Profesor', 3, 'masc', 'Todologo', '2015-01-01', 'Romanza', '333333333', 'N/A', 'Activo', 8, 24),
(445, 'Cubry', 'vaqui', 'Area de administracion', 7, 'masc', 'Pendejo', '2015-02-18', 'Aqui', '222333222', 'N/A', 'Activo', 5, 15),
(566, 'Prueba1', 'vaqui', 'Profesor', 20, 'masc', 'Si', '2014-11-20', 'Aqui', '123123123', 'N/A', 'Activo', 8, 23);

--
-- Disparadores `empleado`
--
DELIMITER $$
CREATE TRIGGER `trg_AjustarDiasExtras` BEFORE INSERT ON `empleado` FOR EACH ROW BEGIN
    DECLARE años_transcurridos INT;

    -- Calcular años desde la fecha de ingreso hasta hoy
    SET años_transcurridos = TIMESTAMPDIFF(YEAR, NEW.Fecha_Ingreso, CURDATE());

    -- Si han pasado más de 6 años, calcular los días extra
    IF años_transcurridos > 6 THEN
        SET NEW.Dias_Extras = NEW.Dias_Extras + ((años_transcurridos - 6) * 2);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado_familiar`
--

CREATE TABLE `empleado_familiar` (
  `Numero_Empleado` int(11) NOT NULL,
  `Id_Familiar` int(11) NOT NULL,
  `Relacion` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado_familiar`
--

INSERT INTO `empleado_familiar` (`Numero_Empleado`, `Id_Familiar`, `Relacion`) VALUES
(445, 1, 'Esposo'),
(566, 2, 'Esposo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado_prestacion`
--

CREATE TABLE `empleado_prestacion` (
  `Numero_Empleado` int(11) NOT NULL,
  `Id_Prestacion` int(11) NOT NULL,
  `Tipo` varchar(40) NOT NULL,
  `Fecha_Solicitada` date NOT NULL DEFAULT current_timestamp(),
  `Fecha_Otorgada` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado_prestacion`
--

INSERT INTO `empleado_prestacion` (`Numero_Empleado`, `Id_Prestacion`, `Tipo`, `Fecha_Solicitada`, `Fecha_Otorgada`) VALUES
(445, 2, 'Academico', '2025-02-02', NULL),
(445, 3, 'Financiera', '2025-02-03', '2025-02-03'),
(445, 4, 'Academico', '2025-02-03', NULL),
(445, 14, 'Academico', '2025-02-04', NULL),
(445, 15, 'Academico', '2025-02-10', '2025-02-10'),
(445, 16, 'Día', '2025-02-10', '2025-02-10'),
(445, 17, 'Academico', '2025-02-12', '2025-02-12'),
(445, 24, 'Plazo', '2025-02-13', NULL),
(445, 25, 'Plazo', '2025-02-13', NULL),
(566, 26, 'Día', '2025-02-14', '2025-02-14'),
(566, 27, 'Plazo', '2025-02-14', '2025-02-14'),
(566, 28, 'Financiera', '2025-02-14', '2025-02-14'),
(566, 29, 'Academico', '2025-02-14', '2025-02-14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familiar_empleado`
--

CREATE TABLE `familiar_empleado` (
  `Id_Familiar` int(11) NOT NULL,
  `Nombre_Familiar` varchar(50) NOT NULL,
  `Nivel_academico` varchar(25) NOT NULL,
  `Edad` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `familiar_empleado`
--

INSERT INTO `familiar_empleado` (`Id_Familiar`, `Nombre_Familiar`, `Nivel_academico`, `Edad`) VALUES
(1, 'Primo de vaqui', 'Secundaria', 3),
(2, 'Angel Gutierrez', 'Universidad', 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familiar_prestacion`
--

CREATE TABLE `familiar_prestacion` (
  `Id_Familiar` int(11) DEFAULT NULL,
  `Id_Prestacion` int(11) NOT NULL,
  `Tipo` varchar(35) NOT NULL,
  `Fecha_Otorgada` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `familiar_prestacion`
--

INSERT INTO `familiar_prestacion` (`Id_Familiar`, `Id_Prestacion`, `Tipo`, `Fecha_Otorgada`) VALUES
(1, 2, 'Academico', '2025-02-03'),
(1, 3, 'Financiera', '2025-02-03'),
(1, 4, 'Academico', NULL),
(1, 14, 'Academico', NULL),
(1, 15, 'Academico', '2025-02-10'),
(1, 17, 'Academico', '2025-02-12'),
(2, 28, 'Financiera', '2025-02-14'),
(2, 29, 'Academico', '2025-02-14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestacion`
--

CREATE TABLE `prestacion` (
  `Id_Prestacion` int(11) NOT NULL,
  `Fecha_Solicitada` date NOT NULL DEFAULT current_timestamp(),
  `Fecha_Otorgada` date DEFAULT NULL,
  `Tipo` varchar(40) NOT NULL,
  `Estado` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestacion`
--

INSERT INTO `prestacion` (`Id_Prestacion`, `Fecha_Solicitada`, `Fecha_Otorgada`, `Tipo`, `Estado`) VALUES
(2, '2025-02-02', '2025-02-03', 'Academico', 'Otorgada'),
(3, '2025-02-03', '2025-02-03', 'Financiera', 'Otorgada'),
(4, '2025-02-03', NULL, 'Academico', 'Pendiente'),
(14, '2025-02-04', NULL, 'Academico', 'Pendiente'),
(15, '2025-02-10', '2025-02-10', 'Academico', 'Otorgada'),
(16, '2025-02-10', '2025-02-10', 'Día', 'Otorgada'),
(17, '2025-02-12', '2025-02-12', 'Academico', 'Otorgada'),
(24, '2025-02-13', NULL, 'Plazo', 'Pendiente'),
(25, '2025-02-13', NULL, 'Plazo', 'Pendiente'),
(26, '2025-02-14', '2025-02-14', 'Día', 'Otorgada'),
(27, '2025-02-14', '2025-02-14', 'Plazo', 'Otorgada'),
(28, '2025-02-14', '2025-02-14', 'Financiera', 'Otorgada'),
(29, '2025-02-14', '2025-02-14', 'Academico', 'Otorgada');

--
-- Disparadores `prestacion`
--
DELIMITER $$
CREATE TRIGGER `before_prestaciones_insert` BEFORE INSERT ON `prestacion` FOR EACH ROW BEGIN
    IF NEW.Fecha_Otorgada IS NULL THEN
        SET NEW.Estado = 'Pendiente';
    ELSE
        IF NEW.Estado != 'Denegada' THEN
            SET NEW.Estado = 'Otorgada';
        END IF;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_prestaciones_update` BEFORE UPDATE ON `prestacion` FOR EACH ROW BEGIN
    IF NEW.Fecha_Otorgada IS NULL THEN
        SET NEW.Estado = 'Pendiente';
    ELSE
        IF NEW.Estado != 'Denegada' THEN
            SET NEW.Estado = 'Otorgada';
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestacion_apoyoacademico`
--

CREATE TABLE `prestacion_apoyoacademico` (
  `Id_Prestacion` int(11) NOT NULL,
  `Numero_Empleado` int(11) NOT NULL,
  `Id_Familiar` int(11) NOT NULL,
  `Nivel_Academico` varchar(20) NOT NULL,
  `Nombre_Institucion` varchar(35) NOT NULL,
  `Tipo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestacion_apoyoacademico`
--

INSERT INTO `prestacion_apoyoacademico` (`Id_Prestacion`, `Numero_Empleado`, `Id_Familiar`, `Nivel_Academico`, `Nombre_Institucion`, `Tipo`) VALUES
(2, 445, 1, 'Secundaria', 'Secundaria uno', 'Utiles'),
(4, 445, 1, 'Secundaria', 'UTN', 'Exencion de inscripc'),
(14, 445, 1, 'Secundaria', 'UTN', 'Exencion de inscripc'),
(15, 445, 1, 'Secundaria', 'UTN', 'Exencion de inscripc'),
(17, 445, 1, 'Secundaria', 'Secundaria uno', 'Utiles'),
(29, 566, 2, 'Universidad', 'UTN', 'Exencion de inscripc');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestacion_apoyofinanciero`
--

CREATE TABLE `prestacion_apoyofinanciero` (
  `Id_Prestacion` int(11) NOT NULL,
  `Numero_Empleado` int(11) NOT NULL,
  `Id_Familiar` int(11) NOT NULL DEFAULT 0,
  `Deposito` tinyint(1) NOT NULL,
  `Reembolso` tinyint(1) NOT NULL,
  `Tipo` varchar(35) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestacion_apoyofinanciero`
--

INSERT INTO `prestacion_apoyofinanciero` (`Id_Prestacion`, `Numero_Empleado`, `Id_Familiar`, `Deposito`, `Reembolso`, `Tipo`) VALUES
(3, 445, 1, 1, 0, 'Gastos funerarios'),
(28, 566, 2, 1, 0, 'Lentes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestacion_dias`
--

CREATE TABLE `prestacion_dias` (
  `Id_Prestacion` int(11) NOT NULL,
  `Numero_Empleado` int(11) NOT NULL,
  `Fecha_Solicitada` date NOT NULL,
  `Dia_extra` tinyint(1) NOT NULL,
  `Motivo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestacion_dias`
--

INSERT INTO `prestacion_dias` (`Id_Prestacion`, `Numero_Empleado`, `Fecha_Solicitada`, `Dia_extra`, `Motivo`) VALUES
(12, 445, '2025-02-12', 0, 'Permiso sindical'),
(13, 445, '2025-02-13', 0, 'Permiso sindical'),
(16, 445, '2025-02-10', 1, 'Permiso sindical'),
(26, 566, '2025-02-17', 0, 'Permiso sindical');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestacion_plazos`
--

CREATE TABLE `prestacion_plazos` (
  `Id_Prestacion` int(11) NOT NULL,
  `Numero_Empleado` int(11) NOT NULL,
  `Fecha_Inicio` date NOT NULL,
  `Fecha_Final` date NOT NULL,
  `Tipo` varchar(34) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestacion_plazos`
--

INSERT INTO `prestacion_plazos` (`Id_Prestacion`, `Numero_Empleado`, `Fecha_Inicio`, `Fecha_Final`, `Tipo`) VALUES
(24, 445, '2025-02-14', '2025-02-19', ''),
(25, 445, '2025-02-24', '2025-03-04', 'Boda de una hermana'),
(27, 566, '2025-02-24', '2025-02-28', '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`Numero_Empleado`);

--
-- Indices de la tabla `empleado_familiar`
--
ALTER TABLE `empleado_familiar`
  ADD KEY `Numero_Empleado` (`Numero_Empleado`),
  ADD KEY `Id_Familiar` (`Id_Familiar`);

--
-- Indices de la tabla `empleado_prestacion`
--
ALTER TABLE `empleado_prestacion`
  ADD KEY `Numero_Empleado` (`Numero_Empleado`),
  ADD KEY `Id_Prestacion` (`Id_Prestacion`);

--
-- Indices de la tabla `familiar_empleado`
--
ALTER TABLE `familiar_empleado`
  ADD PRIMARY KEY (`Id_Familiar`) USING BTREE;

--
-- Indices de la tabla `familiar_prestacion`
--
ALTER TABLE `familiar_prestacion`
  ADD KEY `Id_Familiar` (`Id_Familiar`),
  ADD KEY `Id_Prestacion` (`Id_Prestacion`);

--
-- Indices de la tabla `prestacion`
--
ALTER TABLE `prestacion`
  ADD PRIMARY KEY (`Id_Prestacion`);

--
-- Indices de la tabla `prestacion_apoyoacademico`
--
ALTER TABLE `prestacion_apoyoacademico`
  ADD KEY `Id_Prestacion` (`Id_Prestacion`),
  ADD KEY `Numero_Empleado` (`Numero_Empleado`),
  ADD KEY `Id_Familiar` (`Id_Familiar`);

--
-- Indices de la tabla `prestacion_apoyofinanciero`
--
ALTER TABLE `prestacion_apoyofinanciero`
  ADD KEY `Id_Prestacion` (`Id_Prestacion`),
  ADD KEY `Numero_Empleado` (`Numero_Empleado`),
  ADD KEY `Id_Familiar` (`Id_Familiar`);

--
-- Indices de la tabla `prestacion_dias`
--
ALTER TABLE `prestacion_dias`
  ADD KEY `Id_Prestacion` (`Id_Prestacion`),
  ADD KEY `Numero_Empleado` (`Numero_Empleado`);

--
-- Indices de la tabla `prestacion_plazos`
--
ALTER TABLE `prestacion_plazos`
  ADD KEY `Id_Prestacion` (`Id_Prestacion`),
  ADD KEY `Numero_Empleado` (`Numero_Empleado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `Numero_Empleado` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=567;

--
-- AUTO_INCREMENT de la tabla `familiar_empleado`
--
ALTER TABLE `familiar_empleado`
  MODIFY `Id_Familiar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `prestacion`
--
ALTER TABLE `prestacion`
  MODIFY `Id_Prestacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `empleado_familiar`
--
ALTER TABLE `empleado_familiar`
  ADD CONSTRAINT `empleado_familiar_ibfk_1` FOREIGN KEY (`Id_Familiar`) REFERENCES `familiar_empleado` (`Id_Familiar`),
  ADD CONSTRAINT `empleado_familiar_ibfk_2` FOREIGN KEY (`Numero_Empleado`) REFERENCES `empleado` (`Numero_Empleado`);

--
-- Filtros para la tabla `empleado_prestacion`
--
ALTER TABLE `empleado_prestacion`
  ADD CONSTRAINT `empleado_prestacion_ibfk_1` FOREIGN KEY (`Numero_Empleado`) REFERENCES `empleado` (`Numero_Empleado`),
  ADD CONSTRAINT `empleado_prestacion_ibfk_2` FOREIGN KEY (`Id_Prestacion`) REFERENCES `prestacion` (`Id_Prestacion`);

--
-- Filtros para la tabla `familiar_prestacion`
--
ALTER TABLE `familiar_prestacion`
  ADD CONSTRAINT `familiar_prestacion_ibfk_1` FOREIGN KEY (`Id_Familiar`) REFERENCES `empleado_familiar` (`Id_Familiar`),
  ADD CONSTRAINT `familiar_prestacion_ibfk_2` FOREIGN KEY (`Id_Prestacion`) REFERENCES `prestacion` (`Id_Prestacion`);

--
-- Filtros para la tabla `prestacion_apoyoacademico`
--
ALTER TABLE `prestacion_apoyoacademico`
  ADD CONSTRAINT `prestacion_apoyoacademico_ibfk_1` FOREIGN KEY (`Numero_Empleado`) REFERENCES `empleado_prestacion` (`Numero_Empleado`),
  ADD CONSTRAINT `prestacion_apoyoacademico_ibfk_2` FOREIGN KEY (`Id_Prestacion`) REFERENCES `prestacion` (`Id_Prestacion`),
  ADD CONSTRAINT `prestacion_apoyoacademico_ibfk_3` FOREIGN KEY (`Id_Familiar`) REFERENCES `familiar_prestacion` (`Id_Familiar`);

--
-- Filtros para la tabla `prestacion_apoyofinanciero`
--
ALTER TABLE `prestacion_apoyofinanciero`
  ADD CONSTRAINT `prestacion_apoyofinanciero_ibfk_1` FOREIGN KEY (`Id_Prestacion`) REFERENCES `prestacion` (`Id_Prestacion`),
  ADD CONSTRAINT `prestacion_apoyofinanciero_ibfk_2` FOREIGN KEY (`Numero_Empleado`) REFERENCES `empleado_prestacion` (`Numero_Empleado`),
  ADD CONSTRAINT `prestacion_apoyofinanciero_ibfk_3` FOREIGN KEY (`Id_Familiar`) REFERENCES `familiar_prestacion` (`Id_Familiar`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `prestacion_dias`
--
ALTER TABLE `prestacion_dias`
  ADD CONSTRAINT `prestacion_dias_ibfk_1` FOREIGN KEY (`Id_Prestacion`) REFERENCES `empleado_prestacion` (`Id_Prestacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `prestacion_dias_ibfk_2` FOREIGN KEY (`Numero_Empleado`) REFERENCES `empleado_prestacion` (`Numero_Empleado`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `prestacion_plazos`
--
ALTER TABLE `prestacion_plazos`
  ADD CONSTRAINT `prestacion_plazos_ibfk_1` FOREIGN KEY (`Numero_Empleado`) REFERENCES `empleado_prestacion` (`Numero_Empleado`),
  ADD CONSTRAINT `prestacion_plazos_ibfk_2` FOREIGN KEY (`Id_Prestacion`) REFERENCES `empleado_prestacion` (`Id_Prestacion`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

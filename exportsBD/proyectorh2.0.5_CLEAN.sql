-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-05-2025 a las 03:17:17
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
(20, 'Prueba20', 'vaqui', 'RH', 10, 'fem', 'todologo', '2014-08-06', 'aqui', '12312312', 'N/A', 'Activo', 8, 24),
(21, 'Prueba21', 'vaqui', 'Profesor', 21, 'masc', 'si', '2014-03-03', 'aqui', '123123123', 'N/A', 'Activo', 10, 24),
(223, 'Vaqui', 'vaqui', 'RH', 4, 'fem', 'Todologa', '2025-01-29', 'Romanza', '233444222', 'N/A', 'Activo', 0, 20),
(334, 'Prueba2', 'vaqui', 'Profesor', 21, 'masc', 'a', '2006-02-21', 'a', '1231312131', 'N/A', 'Activo', 26, 23),
(444, 'Curli', '', 'Profesor', 3, 'masc', 'Todologo', '2015-01-01', 'Romanza', '333333333', 'N/A', 'Activo', 8, 24),
(445, 'Cubry', 'vaqui', 'Area de administracion', 7, 'masc', 'Inteligente', '2015-02-18', 'Aqui', '222333222', 'N/A', 'Activo', 5, 14),
(566, 'Prueba1', 'vaqui', 'Profesor', 20, 'masc', 'Si', '2014-11-20', 'Aqui', '123123123', 'N/A', 'Activo', 8, 21),
(777, 'Prueba3', 'vaqui', 'Profesor', 21, 'masc', 'a', '2010-03-17', 'a', '12313131', 'N/A', 'Activo', 16, 23),
(888, 'si', 'vaqui', 'Profesor', 2, 'masc', 'si', '2015-02-04', 'si', '1', 'N/A', 'Activo', 8, 24),
(1999, 'Test', 'vaqui', 'Profesor', 20, 'masc', 'Test', '1999-02-02', 'Test', '111', 'N/A', 'Activo', 40, 23),
(6384, 'Maria Antonieta', '', 'Area de administracion', 28, 'fem', 'Licenciada', '2020-02-11', 'Heroes, 81021', '6313571318', 'N/A', 'Activo', 0, 24),
(8236, 'Jose Quiroz', 'vaqui', 'Profesor', 32, 'masc', 'Fisico Matematico', '2010-05-20', 'Rosaritos, 8406', '6311206338', 'N/A', 'Activo', 15, 24),
(2147483647, '321312312', '', 'Profesor', 1, 'masc', '2222', '2025-03-05', '321321', '123123', 'N/A', 'Activo', 0, 24);

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
(566, 2, 'Esposo'),
(566, 3, 'Hijo'),
(21, 4, 'Hijo'),
(566, 0, 'Esposa'),
(445, 0, 'Esposa'),
(777, 0, 'Esposa'),
(777, 6, 'Hijo'),
(888, 0, 'Esposa'),
(888, 7, 'Hijo'),
(8236, 0, 'Esposa'),
(8236, 8, 'Hijo'),
(8236, 9, 'Hijo'),
(1999, 0, 'Esposa'),
(1999, 10, 'Hijo'),
(1999, 11, 'Hijo');

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
(0, 'N/A', 'Universidad', 99),
(1, 'Primo de vaqui', 'Secundaria', 3),
(2, 'Angel Gutierrez', 'Universidad', 19),
(3, 'MiHijo', 'Primaria', 8),
(4, 'Adriana', 'Secundaria', 13),
(6, 'Popo', 'Preparatoria', 16),
(7, 'siHijo', 'Universidad', 9),
(8, 'Mi Hijo', 'Preparatoria', 19),
(9, 'Hijo2', 'Universidad', 19),
(10, 'TestHijo', 'Universidad', 18),
(11, 'TestHijo2', 'Universidad', 19);

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
  `Tipo` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposprestacion`
--

CREATE TABLE `tiposprestacion` (
  `id` int(11) NOT NULL,
  `tipoMayor` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `precio` int(11) NOT NULL DEFAULT 0,
  `PeriodoMeses` int(2) NOT NULL DEFAULT 0,
  `documentos` text NOT NULL DEFAULT 'Carta de solicitud FIRMADA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tiposprestacion`
--

INSERT INTO `tiposprestacion` (`id`, `tipoMayor`, `nombre`, `precio`, `PeriodoMeses`, `documentos`) VALUES
(1, 'Financiera', 'Guarderia', 2000, 12, 'Carta de solicitud FIRMADA'),
(2, 'Financiera', 'Lentes', 2000, 12, 'Carta de solicitud FIRMADA'),
(7, 'Academico', 'Exencion de inscripcion', 2000, 12, 'Carta de solicitud FIRMADA'),
(8, 'Academico', 'Utiles', 1200, 12, 'Carta de solicitud FIRMADA'),
(9, 'Academico', 'Tesis', 2000, 12, 'Carta de solicitud FIRMADA'),
(10, 'Financiera', 'Gasto Funerario', 5000, 12, 'Carta de solicitud FIRMADA'),
(11, 'Financiera', 'Aparato Ortopedico', 3000, 12, 'Carta de solicitud FIRMADA'),
(12, 'Financiera', 'Canastilla mama', 1000, 12, 'Carta de solicitud FIRMADA'),
(13, 'Financiera', 'Titulacion', 2000, 12, 'Carta de solicitud FIRMADA'),
(14, 'Dia', 'Permiso sindical', 0, 0, 'Carta de solicitud FIRMADA'),
(15, 'Dia', 'Nacimiento hijo', 0, 0, 'Carta de solicitud FIRMADA'),
(16, 'Dia', 'Otro', 0, 0, 'Carta de solicitud FIRMADA'),
(17, 'Plazo', 'Incapacidad', 0, 0, 'Carta de solicitud FIRMADA'),
(18, 'Plazo', 'Embarazo', 0, 0, 'Carta de solicitud FIRMADA'),
(19, 'Plazo', 'Permiso por duelo', 0, 0, 'Carta de solicitud FIRMADA');

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
-- Indices de la tabla `tiposprestacion`
--
ALTER TABLE `tiposprestacion`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `Numero_Empleado` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483648;

--
-- AUTO_INCREMENT de la tabla `familiar_empleado`
--
ALTER TABLE `familiar_empleado`
  MODIFY `Id_Familiar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `prestacion`
--
ALTER TABLE `prestacion`
  MODIFY `Id_Prestacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tiposprestacion`
--
ALTER TABLE `tiposprestacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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

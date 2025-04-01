-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2025 at 03:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `proyectorh`
--

-- --------------------------------------------------------

--
-- Table structure for table `empleado`
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
-- Dumping data for table `empleado`
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
(6384, 'Maria Antonieta', '', 'Area de administracion', 28, 'fem', 'Licenciada', '2020-02-11', 'Heroes, 81021', '6313571318', 'N/A', 'Activo', 0, 24),
(8236, 'Jose Quiroz', 'vaqui', 'Profesor', 32, 'masc', 'Fisico Matematico', '2010-05-20', 'Rosaritos, 8406', '6311206338', 'N/A', 'Activo', 15, 24),
(2147483647, '321312312', '', 'Profesor', 1, 'masc', '2222', '2025-03-05', '321321', '123123', 'N/A', 'Activo', 0, 24);

--
-- Triggers `empleado`
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
-- Table structure for table `empleado_familiar`
--

CREATE TABLE `empleado_familiar` (
  `Numero_Empleado` int(11) NOT NULL,
  `Id_Familiar` int(11) NOT NULL,
  `Relacion` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `empleado_familiar`
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
(8236, 9, 'Hijo');

-- --------------------------------------------------------

--
-- Table structure for table `empleado_prestacion`
--

CREATE TABLE `empleado_prestacion` (
  `Numero_Empleado` int(11) NOT NULL,
  `Id_Prestacion` int(11) NOT NULL,
  `Tipo` varchar(40) NOT NULL,
  `Fecha_Solicitada` date NOT NULL DEFAULT current_timestamp(),
  `Fecha_Otorgada` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `empleado_prestacion`
--

INSERT INTO `empleado_prestacion` (`Numero_Empleado`, `Id_Prestacion`, `Tipo`, `Fecha_Solicitada`, `Fecha_Otorgada`) VALUES
(445, 2, 'Academico', '2025-02-02', NULL),
(445, 3, 'Financiera', '2025-02-03', '2025-02-03'),
(445, 4, 'Academico', '2025-02-03', '2025-03-07'),
(445, 14, 'Academico', '2025-02-04', '2025-03-07'),
(445, 15, 'Academico', '2025-02-10', '2025-02-10'),
(445, 16, 'Día', '2025-02-10', '2025-02-10'),
(445, 17, 'Academico', '2025-02-12', '2025-02-12'),
(445, 24, 'Plazo', '2025-02-13', '2025-03-07'),
(445, 25, 'Plazo', '2025-02-13', '2025-03-07'),
(566, 26, 'Día', '2025-02-14', '2025-02-14'),
(566, 27, 'Plazo', '2025-02-14', '2025-02-14'),
(566, 28, 'Financiera', '2025-02-14', '2025-02-14'),
(566, 29, 'Academico', '2025-02-14', '2025-02-14'),
(445, 30, 'Día', '2025-02-21', '2025-02-21'),
(566, 31, 'Día', '2025-02-21', '2025-02-21'),
(334, 32, 'Día', '2025-02-21', '2025-02-21'),
(566, 33, 'Academico', '2025-02-26', '2025-03-07'),
(566, 34, 'Día', '2025-02-27', '2025-03-07'),
(777, 35, 'Día', '2025-02-27', '2025-03-07'),
(777, 36, 'Plazo', '2025-02-27', '2025-03-07'),
(334, 37, 'Plazo', '2025-03-07', '2025-03-07'),
(777, 38, 'Plazo', '2025-03-07', '2025-03-07'),
(445, 39, 'Plazo', '2025-03-07', '2025-03-07'),
(21, 40, 'Academico', '2025-03-07', '2025-03-07'),
(445, 46, 'Financiera', '2025-03-12', '2025-03-12'),
(445, 47, 'Financiera', '2025-03-12', '2025-03-12'),
(777, 48, 'Financiera', '2025-03-12', '2025-03-12'),
(777, 49, 'Plazo', '2025-03-14', '2025-03-14'),
(888, 50, 'Financiera', '2025-03-19', '2025-03-19'),
(888, 51, 'Financiera', '2025-03-19', '2025-03-19'),
(888, 52, 'Academico', '2025-03-19', '2025-03-19'),
(8236, 53, 'Financiera', '2025-03-20', '2025-03-20'),
(8236, 54, 'Día', '2025-03-20', NULL),
(8236, 55, 'Financiera', '2025-03-20', '2025-03-20'),
(8236, 56, 'Academico', '2025-03-20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `familiar_empleado`
--

CREATE TABLE `familiar_empleado` (
  `Id_Familiar` int(11) NOT NULL,
  `Nombre_Familiar` varchar(50) NOT NULL,
  `Nivel_academico` varchar(25) NOT NULL,
  `Edad` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `familiar_empleado`
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
(9, 'Hijo2', 'Universidad', 19);

-- --------------------------------------------------------

--
-- Table structure for table `familiar_prestacion`
--

CREATE TABLE `familiar_prestacion` (
  `Id_Familiar` int(11) DEFAULT NULL,
  `Id_Prestacion` int(11) NOT NULL,
  `Tipo` varchar(35) NOT NULL,
  `Fecha_Otorgada` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `familiar_prestacion`
--

INSERT INTO `familiar_prestacion` (`Id_Familiar`, `Id_Prestacion`, `Tipo`, `Fecha_Otorgada`) VALUES
(1, 2, 'Academico', '2025-02-03'),
(1, 3, 'Financiera', '2025-02-03'),
(1, 4, 'Academico', '2025-03-07'),
(1, 14, 'Academico', '2025-03-07'),
(1, 15, 'Academico', '2025-02-10'),
(1, 17, 'Academico', '2025-02-12'),
(2, 28, 'Financiera', '2025-02-14'),
(2, 29, 'Academico', '2025-02-14'),
(3, 33, 'Academico', '2025-03-07'),
(4, 40, 'Academico', '2025-03-07'),
(0, 46, 'Financiera', '2025-03-12'),
(0, 47, 'Financiera', '2025-03-12'),
(6, 48, 'Financiera', '2025-03-12'),
(0, 50, 'Financiera', '2025-03-19'),
(0, 51, 'Financiera', '2025-03-19'),
(7, 52, 'Academico', '2025-03-19'),
(0, 53, 'Financiera', '2025-03-20'),
(0, 55, 'Financiera', '2025-03-20'),
(9, 56, 'Academico', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prestacion`
--

CREATE TABLE `prestacion` (
  `Id_Prestacion` int(11) NOT NULL,
  `Fecha_Solicitada` date NOT NULL DEFAULT current_timestamp(),
  `Fecha_Otorgada` date DEFAULT NULL,
  `Tipo` varchar(40) NOT NULL,
  `Estado` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prestacion`
--

INSERT INTO `prestacion` (`Id_Prestacion`, `Fecha_Solicitada`, `Fecha_Otorgada`, `Tipo`, `Estado`) VALUES
(2, '2025-02-02', '2025-02-03', 'Academico', 'Otorgada'),
(3, '2025-02-03', '2025-02-03', 'Financiera', 'Otorgada'),
(4, '2025-02-03', '2025-03-07', 'Academico', 'Otorgada'),
(14, '2025-02-04', '2025-03-07', 'Academico', 'Otorgada'),
(15, '2025-02-10', '2025-02-10', 'Academico', 'Otorgada'),
(16, '2025-02-10', '2025-02-10', 'Día', 'Otorgada'),
(17, '2025-02-12', '2025-02-12', 'Academico', 'Otorgada'),
(24, '2025-02-13', '2025-03-07', 'Plazo', 'Otorgada'),
(25, '2025-02-13', '2025-03-07', 'Plazo', 'Otorgada'),
(26, '2025-02-14', '2025-02-14', 'Día', 'Otorgada'),
(27, '2025-02-14', '2025-02-14', 'Plazo', 'Otorgada'),
(28, '2025-02-14', '2025-02-14', 'Financiera', 'Otorgada'),
(29, '2025-02-14', '2025-02-14', 'Academico', 'Otorgada'),
(30, '2025-02-21', '2025-02-21', 'Día', 'Otorgada'),
(31, '2025-02-21', '2025-02-21', 'Día', 'Otorgada'),
(32, '2025-02-21', '2025-02-21', 'Día', 'Otorgada'),
(33, '2025-02-26', '2025-03-07', 'Academico', 'Otorgada'),
(34, '2025-02-27', '2025-03-07', 'Día', 'Otorgada'),
(35, '2025-02-27', '2025-03-07', 'Día', 'Otorgada'),
(36, '2025-02-27', '2025-03-07', 'Plazo', 'Otorgada'),
(37, '2025-03-07', '2025-03-07', 'Plazo', 'Otorgada'),
(38, '2025-03-07', '2025-03-07', 'Plazo', 'Otorgada'),
(39, '2025-03-07', '2025-03-07', 'Plazo', 'Otorgada'),
(40, '2025-03-07', '2025-03-07', 'Academico', 'Otorgada'),
(46, '2025-03-12', '2025-03-12', 'Financiera', 'Otorgada'),
(47, '2025-03-12', '2025-03-12', 'Financiera', 'Otorgada'),
(48, '2025-03-12', '2025-03-12', 'Financiera', 'Otorgada'),
(49, '2025-03-14', '2025-03-14', 'Plazo', 'Otorgada'),
(50, '2025-03-19', '2025-03-19', 'Financiera', 'Otorgada'),
(51, '2025-03-19', '2025-03-19', 'Financiera', 'Otorgada'),
(52, '2025-03-19', '2025-03-19', 'Academico', 'Otorgada'),
(53, '2025-03-20', '2025-03-20', 'Financiera', 'Otorgada'),
(54, '2025-03-20', NULL, 'Día', 'Pendiente'),
(55, '2025-03-20', '2025-03-20', 'Financiera', 'Otorgada'),
(56, '2025-03-20', NULL, 'Academico', 'Pendiente');

--
-- Triggers `prestacion`
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
-- Table structure for table `prestacion_apoyoacademico`
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
-- Dumping data for table `prestacion_apoyoacademico`
--

INSERT INTO `prestacion_apoyoacademico` (`Id_Prestacion`, `Numero_Empleado`, `Id_Familiar`, `Nivel_Academico`, `Nombre_Institucion`, `Tipo`) VALUES
(2, 445, 1, 'Secundaria', 'Secundaria uno', 'Utiles'),
(4, 445, 1, 'Secundaria', 'UTN', 'Exencion de inscripc'),
(14, 445, 1, 'Secundaria', 'UTN', 'Exencion de inscripc'),
(15, 445, 1, 'Secundaria', 'UTN', 'Exencion de inscripc'),
(17, 445, 1, 'Secundaria', 'Secundaria uno', 'Utiles'),
(29, 566, 2, 'Universidad', 'UTN', 'Exencion de inscripc'),
(33, 566, 3, 'Primaria', 'Diego Rivera', 'Utiles'),
(40, 21, 4, 'Secundaria', 'Dos', 'Utiles'),
(52, 888, 7, 'Universidad', 'utn', 'Utiles'),
(56, 8236, 9, 'Universidad', 'UTN', 'Utiles');

-- --------------------------------------------------------

--
-- Table structure for table `prestacion_apoyofinanciero`
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
-- Dumping data for table `prestacion_apoyofinanciero`
--

INSERT INTO `prestacion_apoyofinanciero` (`Id_Prestacion`, `Numero_Empleado`, `Id_Familiar`, `Deposito`, `Reembolso`, `Tipo`) VALUES
(3, 445, 1, 1, 0, 'Gastos funerarios'),
(28, 566, 2, 1, 0, 'Lentes'),
(46, 445, 0, 1, 0, 'Guarderia'),
(47, 445, 0, 1, 0, 'Lentes'),
(48, 777, 6, 1, 0, 'Lentes'),
(50, 888, 0, 1, 0, 'Guarderia'),
(51, 888, 0, 1, 0, 'Lentes'),
(53, 8236, 0, 1, 0, 'Lentes'),
(55, 8236, 0, 1, 0, 'Guarderia');

-- --------------------------------------------------------

--
-- Table structure for table `prestacion_dias`
--

CREATE TABLE `prestacion_dias` (
  `Id_Prestacion` int(11) NOT NULL,
  `Numero_Empleado` int(11) NOT NULL,
  `Fecha_Solicitada` date NOT NULL,
  `Dia_extra` tinyint(1) NOT NULL,
  `Motivo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prestacion_dias`
--

INSERT INTO `prestacion_dias` (`Id_Prestacion`, `Numero_Empleado`, `Fecha_Solicitada`, `Dia_extra`, `Motivo`) VALUES
(12, 445, '2025-02-12', 0, 'Permiso sindical'),
(13, 445, '2025-02-13', 0, 'Permiso sindical'),
(16, 445, '2025-02-10', 1, 'Permiso sindical'),
(26, 566, '2025-02-17', 0, 'Permiso sindical'),
(30, 445, '2025-02-24', 0, 'Permiso sindical'),
(31, 566, '2025-02-24', 0, 'Permiso sindical'),
(32, 334, '2025-02-24', 0, 'Permiso sindical'),
(34, 566, '2026-03-02', 0, 'Permiso sindical'),
(54, 8236, '2025-03-21', 1, 'Permiso sindical');

-- --------------------------------------------------------

--
-- Table structure for table `prestacion_plazos`
--

CREATE TABLE `prestacion_plazos` (
  `Id_Prestacion` int(11) NOT NULL,
  `Numero_Empleado` int(11) NOT NULL,
  `Fecha_Inicio` date NOT NULL,
  `Fecha_Final` date NOT NULL,
  `Tipo` varchar(34) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prestacion_plazos`
--

INSERT INTO `prestacion_plazos` (`Id_Prestacion`, `Numero_Empleado`, `Fecha_Inicio`, `Fecha_Final`, `Tipo`) VALUES
(24, 445, '2025-02-14', '2025-02-19', ''),
(25, 445, '2025-02-24', '2025-03-04', 'Boda de una hermana'),
(27, 566, '2025-02-24', '2025-02-28', ''),
(36, 777, '2025-03-03', '2025-03-06', 'Incapacidad'),
(37, 334, '2025-03-10', '2025-03-21', 'Boda hermana'),
(38, 777, '2025-03-17', '2025-03-28', 'Boda hermana'),
(39, 445, '2025-03-25', '2025-03-27', 'Rape'),
(49, 777, '2025-03-13', '2025-03-17', 'Me comi una salchipapa');

-- --------------------------------------------------------

--
-- Table structure for table `tiposprestacion`
--

CREATE TABLE `tiposprestacion` (
  `id` int(11) NOT NULL,
  `tipoMayor` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `precio` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tiposprestacion`
--

INSERT INTO `tiposprestacion` (`id`, `tipoMayor`, `nombre`, `precio`) VALUES
(1, 'Financiera', 'Guarderia', 1600),
(2, 'Financiera', 'Lentes', 2000),
(3, 'Financiera', 'Popo', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`Numero_Empleado`);

--
-- Indexes for table `empleado_familiar`
--
ALTER TABLE `empleado_familiar`
  ADD KEY `Numero_Empleado` (`Numero_Empleado`),
  ADD KEY `Id_Familiar` (`Id_Familiar`);

--
-- Indexes for table `empleado_prestacion`
--
ALTER TABLE `empleado_prestacion`
  ADD KEY `Numero_Empleado` (`Numero_Empleado`),
  ADD KEY `Id_Prestacion` (`Id_Prestacion`);

--
-- Indexes for table `familiar_empleado`
--
ALTER TABLE `familiar_empleado`
  ADD PRIMARY KEY (`Id_Familiar`) USING BTREE;

--
-- Indexes for table `familiar_prestacion`
--
ALTER TABLE `familiar_prestacion`
  ADD KEY `Id_Familiar` (`Id_Familiar`),
  ADD KEY `Id_Prestacion` (`Id_Prestacion`);

--
-- Indexes for table `prestacion`
--
ALTER TABLE `prestacion`
  ADD PRIMARY KEY (`Id_Prestacion`);

--
-- Indexes for table `prestacion_apoyoacademico`
--
ALTER TABLE `prestacion_apoyoacademico`
  ADD KEY `Id_Prestacion` (`Id_Prestacion`),
  ADD KEY `Numero_Empleado` (`Numero_Empleado`),
  ADD KEY `Id_Familiar` (`Id_Familiar`);

--
-- Indexes for table `prestacion_apoyofinanciero`
--
ALTER TABLE `prestacion_apoyofinanciero`
  ADD KEY `Id_Prestacion` (`Id_Prestacion`),
  ADD KEY `Numero_Empleado` (`Numero_Empleado`),
  ADD KEY `Id_Familiar` (`Id_Familiar`);

--
-- Indexes for table `prestacion_dias`
--
ALTER TABLE `prestacion_dias`
  ADD KEY `Id_Prestacion` (`Id_Prestacion`),
  ADD KEY `Numero_Empleado` (`Numero_Empleado`);

--
-- Indexes for table `prestacion_plazos`
--
ALTER TABLE `prestacion_plazos`
  ADD KEY `Id_Prestacion` (`Id_Prestacion`),
  ADD KEY `Numero_Empleado` (`Numero_Empleado`);

--
-- Indexes for table `tiposprestacion`
--
ALTER TABLE `tiposprestacion`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `empleado`
--
ALTER TABLE `empleado`
  MODIFY `Numero_Empleado` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483648;

--
-- AUTO_INCREMENT for table `familiar_empleado`
--
ALTER TABLE `familiar_empleado`
  MODIFY `Id_Familiar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `prestacion`
--
ALTER TABLE `prestacion`
  MODIFY `Id_Prestacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `tiposprestacion`
--
ALTER TABLE `tiposprestacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `empleado_familiar`
--
ALTER TABLE `empleado_familiar`
  ADD CONSTRAINT `empleado_familiar_ibfk_1` FOREIGN KEY (`Id_Familiar`) REFERENCES `familiar_empleado` (`Id_Familiar`),
  ADD CONSTRAINT `empleado_familiar_ibfk_2` FOREIGN KEY (`Numero_Empleado`) REFERENCES `empleado` (`Numero_Empleado`);

--
-- Constraints for table `empleado_prestacion`
--
ALTER TABLE `empleado_prestacion`
  ADD CONSTRAINT `empleado_prestacion_ibfk_1` FOREIGN KEY (`Numero_Empleado`) REFERENCES `empleado` (`Numero_Empleado`),
  ADD CONSTRAINT `empleado_prestacion_ibfk_2` FOREIGN KEY (`Id_Prestacion`) REFERENCES `prestacion` (`Id_Prestacion`);

--
-- Constraints for table `familiar_prestacion`
--
ALTER TABLE `familiar_prestacion`
  ADD CONSTRAINT `familiar_prestacion_ibfk_1` FOREIGN KEY (`Id_Familiar`) REFERENCES `empleado_familiar` (`Id_Familiar`),
  ADD CONSTRAINT `familiar_prestacion_ibfk_2` FOREIGN KEY (`Id_Prestacion`) REFERENCES `prestacion` (`Id_Prestacion`);

--
-- Constraints for table `prestacion_apoyoacademico`
--
ALTER TABLE `prestacion_apoyoacademico`
  ADD CONSTRAINT `prestacion_apoyoacademico_ibfk_1` FOREIGN KEY (`Numero_Empleado`) REFERENCES `empleado_prestacion` (`Numero_Empleado`),
  ADD CONSTRAINT `prestacion_apoyoacademico_ibfk_2` FOREIGN KEY (`Id_Prestacion`) REFERENCES `prestacion` (`Id_Prestacion`),
  ADD CONSTRAINT `prestacion_apoyoacademico_ibfk_3` FOREIGN KEY (`Id_Familiar`) REFERENCES `familiar_prestacion` (`Id_Familiar`);

--
-- Constraints for table `prestacion_apoyofinanciero`
--
ALTER TABLE `prestacion_apoyofinanciero`
  ADD CONSTRAINT `prestacion_apoyofinanciero_ibfk_1` FOREIGN KEY (`Id_Prestacion`) REFERENCES `prestacion` (`Id_Prestacion`),
  ADD CONSTRAINT `prestacion_apoyofinanciero_ibfk_2` FOREIGN KEY (`Numero_Empleado`) REFERENCES `empleado_prestacion` (`Numero_Empleado`),
  ADD CONSTRAINT `prestacion_apoyofinanciero_ibfk_3` FOREIGN KEY (`Id_Familiar`) REFERENCES `familiar_prestacion` (`Id_Familiar`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `prestacion_dias`
--
ALTER TABLE `prestacion_dias`
  ADD CONSTRAINT `prestacion_dias_ibfk_1` FOREIGN KEY (`Id_Prestacion`) REFERENCES `empleado_prestacion` (`Id_Prestacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `prestacion_dias_ibfk_2` FOREIGN KEY (`Numero_Empleado`) REFERENCES `empleado_prestacion` (`Numero_Empleado`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `prestacion_plazos`
--
ALTER TABLE `prestacion_plazos`
  ADD CONSTRAINT `prestacion_plazos_ibfk_1` FOREIGN KEY (`Numero_Empleado`) REFERENCES `empleado_prestacion` (`Numero_Empleado`),
  ADD CONSTRAINT `prestacion_plazos_ibfk_2` FOREIGN KEY (`Id_Prestacion`) REFERENCES `empleado_prestacion` (`Id_Prestacion`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

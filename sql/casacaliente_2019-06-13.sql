-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-06-2019 a las 23:47:09
-- Versión del servidor: 5.6.21
-- Versión de PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `casacaliente`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbauditoria`
--

CREATE TABLE IF NOT EXISTS `dbauditoria` (
`idauditoria` int(11) NOT NULL,
  `tabla` varchar(80) COLLATE utf8_spanish_ci DEFAULT NULL,
  `operacion` varchar(1) COLLATE utf8_spanish_ci DEFAULT NULL,
  `campo` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  `valornuevo` mediumtext COLLATE utf8_spanish_ci,
  `valorviejo` mediumtext COLLATE utf8_spanish_ci,
  `id` int(11) DEFAULT NULL,
  `usuario` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbclientes`
--

CREATE TABLE IF NOT EXISTS `dbclientes` (
`idcliente` int(11) NOT NULL,
  `cognom` varchar(120) COLLATE utf8_spanish_ci NOT NULL,
  `nom` varchar(120) COLLATE utf8_spanish_ci NOT NULL,
  `nif` varchar(11) COLLATE utf8_spanish_ci NOT NULL,
  `carrer` varchar(300) COLLATE utf8_spanish_ci DEFAULT NULL,
  `codipostal` varchar(14) COLLATE utf8_spanish_ci DEFAULT NULL,
  `ciutat` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  `pais` varchar(60) COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefon` varchar(22) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(120) COLLATE utf8_spanish_ci DEFAULT NULL,
  `comentaris` varchar(500) COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefon2` varchar(22) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email2` varchar(120) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `dbclientes`
--

INSERT INTO `dbclientes` (`idcliente`, `cognom`, `nom`, `nif`, `carrer`, `codipostal`, `ciutat`, `pais`, `telefon`, `email`, `comentaris`, `telefon2`, `email2`) VALUES
(1, 'asdasd', 'aasd', '34534534', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbperiodos`
--

CREATE TABLE IF NOT EXISTS `dbperiodos` (
`idperiodo` int(11) NOT NULL,
  `periodo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `any` int(11) NOT NULL,
  `desdeperiode` date NOT NULL,
  `finsaperiode` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbtarifas`
--

CREATE TABLE IF NOT EXISTS `dbtarifas` (
`idtarifa` int(11) NOT NULL,
  `reftipoubicacion` int(11) NOT NULL,
  `refperiodos` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `dbtarifas`
--

INSERT INTO `dbtarifas` (`idtarifa`, `reftipoubicacion`, `refperiodos`) VALUES
(1, 2, 365),
(2, 4, 395),
(3, 5, 425),
(4, 6, 445),
(5, 7, 425),
(6, 8, 445),
(7, 9, 445);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbubicaciones`
--

CREATE TABLE IF NOT EXISTS `dbubicaciones` (
`idubicacion` int(11) NOT NULL,
  `dormitorio` int(11) NOT NULL,
  `color` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `reftipoubicacion` int(11) NOT NULL,
  `codapartament` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `hutg` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `dbubicaciones`
--

INSERT INTO `dbubicaciones` (`idubicacion`, `dormitorio`, `color`, `reftipoubicacion`, `codapartament`, `hutg`) VALUES
(1, 2, 'VERMELL', 5, '01', 'HUTG-010389'),
(2, 2, 'VERMELL', 5, '02', 'HUTG-010390'),
(3, 2, 'VERMELL', 5, '03', 'HUTG-010391'),
(4, 2, 'VERMELL', 5, '04', 'HUTG-010392'),
(5, 2, 'VERMELL', 5, '05', 'HUTG-010394'),
(6, 2, 'VERMELL', 5, '06', 'HUTG-010395'),
(7, 2, 'VERMELL', 5, '07', 'HUTG-010396'),
(8, 3, 'VERMELL', 8, '08', 'HUTG-010397'),
(9, 2, 'GROC', 4, '09', 'HUTG-010399'),
(10, 1, 'GROC', 2, '10', 'HUTG-010400'),
(11, 2, 'GROC', 4, '11', 'HUTG-010401'),
(12, 1, 'GROC', 2, '12', 'HUTG-010402'),
(13, 3, 'GROC', 7, '13', 'HUTG-010403'),
(14, 2, 'GROC', 4, '14', 'HUTG-010404'),
(15, 2, 'GROC', 4, '15', 'HUTG-010405'),
(16, 1, 'GROC', 2, '16', 'HUTG-010406'),
(17, 3, 'GROC', 7, '17', 'HUTG-010407'),
(18, 2, 'GROC', 4, '18', 'HUTG-010408'),
(19, 2, 'GROC', 4, '19', 'HUTG-010409'),
(20, 2, 'GROC', 4, '20', 'HUTG-010410'),
(21, 2, 'GROC', 4, '21', 'HUTG-010411'),
(22, 2, 'GROC', 4, '22', 'HUTG-010412'),
(23, 1, 'GROC', 2, '23', 'HUTG-010413'),
(24, 1, 'GROC', 2, '24', 'HUTG-010414'),
(25, 2, 'GROC', 4, '27', 'HUTG-010415'),
(26, 2, 'GROC', 4, '3A', 'HUTG-010398'),
(27, 2, 'GROC', 4, 'A1', 'HUTG-010393'),
(28, 2, 'GROC', 4, 'A2', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbusuarios`
--

CREATE TABLE IF NOT EXISTS `dbusuarios` (
`idusuario` int(11) NOT NULL,
  `usuario` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `refroles` int(11) NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nombrecompleto` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `activo` bit(1) DEFAULT b'0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `dbusuarios`
--

INSERT INTO `dbusuarios` (`idusuario`, `usuario`, `password`, `refroles`, `email`, `nombrecompleto`, `activo`) VALUES
(1, 'Marcos', 'marcos', 1, 'msredhotero@msn.com', 'Marcos Saupurein', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `predio_menu`
--

CREATE TABLE IF NOT EXISTS `predio_menu` (
`idmenu` int(11) NOT NULL,
  `url` varchar(65) COLLATE utf8_spanish_ci NOT NULL,
  `icono` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `Orden` smallint(6) DEFAULT NULL,
  `hover` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `permiso` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `administracion` bit(1) DEFAULT NULL,
  `torneo` bit(1) DEFAULT NULL,
  `reportes` bit(1) DEFAULT NULL,
  `grupo` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `predio_menu`
--

INSERT INTO `predio_menu` (`idmenu`, `url`, `icono`, `nombre`, `Orden`, `hover`, `permiso`, `administracion`, `torneo`, `reportes`, `grupo`) VALUES
(13, '../index.php', 'dashboard', 'Dashboard', 1, NULL, 'Administrador, Cliente, Empleado', b'1', b'1', b'0', 0),
(19, '../clientes/', 'people_outline', 'Clientes', 2, NULL, 'Administrador, Empleado', b'1', b'0', b'0', 0),
(20, '../usuarios/', 'account_circle', 'Usuarios', 12, NULL, 'Administrador, Empleado', b'1', b'0', b'0', 0),
(21, '../tipoubicacion/', 'explore', 'Tipo Ubicacion', 9, NULL, 'Administrador, Empleado', b'1', b'0', b'0', 0),
(23, '../tarifes/', 'euro_symbol', 'Tarifes', 4, NULL, 'Administrador, Empleado', b'1', b'0', b'0', 0),
(24, '../ubicaciones/', 'location_on', 'Ubicaciones', 6, NULL, 'Administrador, Empleado', b'1', b'0', b'0', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbconfiguracion`
--

CREATE TABLE IF NOT EXISTS `tbconfiguracion` (
`idconfiguracion` int(11) NOT NULL,
  `razonsocial` varchar(80) COLLATE utf8_spanish_ci DEFAULT NULL,
  `empresa` varchar(80) COLLATE utf8_spanish_ci DEFAULT NULL,
  `sistema` varchar(80) COLLATE utf8_spanish_ci DEFAULT NULL,
  `direccion` varchar(80) COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(120) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tbconfiguracion`
--

INSERT INTO `tbconfiguracion` (`idconfiguracion`, `razonsocial`, `empresa`, `sistema`, `direccion`, `telefono`, `email`) VALUES
(1, 'Casa Caliente', 'Casa Caliente', 'Casa Caliente', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbestados`
--

CREATE TABLE IF NOT EXISTS `tbestados` (
`idestado` int(11) NOT NULL,
  `estado` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `color` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `icono` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  `valor` int(11) DEFAULT NULL,
  `refformularios` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tbestados`
--

INSERT INTO `tbestados` (`idestado`, `estado`, `color`, `icono`, `orden`, `valor`, `refformularios`) VALUES
(1, 'Cargado', 'blue', NULL, 1, 1, 1),
(2, 'Bueno', 'green', NULL, 1, 1, 2),
(3, 'Regular', 'orange', NULL, 2, 1, 2),
(4, 'Malo', 'red', NULL, 3, 1, 2),
(5, 'Finalizado', 'orange', NULL, 2, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbformaspagos`
--

CREATE TABLE IF NOT EXISTS `tbformaspagos` (
`idformapago` int(11) NOT NULL,
  `formapago` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `abreviatura` varchar(5) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbformularios`
--

CREATE TABLE IF NOT EXISTS `tbformularios` (
`idformulario` int(11) NOT NULL,
  `formulario` varchar(60) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbmeses`
--

CREATE TABLE IF NOT EXISTS `tbmeses` (
`idmes` int(11) NOT NULL,
  `meses` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `desde` int(11) NOT NULL,
  `hasta` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tbmeses`
--

INSERT INTO `tbmeses` (`idmes`, `meses`, `desde`, `hasta`) VALUES
(1, 'Enero - Marzo', 1, 3),
(2, 'Abril - Junio', 4, 6),
(3, 'Julio - Septiembre', 7, 9),
(4, 'Octubre - Noviembre', 10, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbroles`
--

CREATE TABLE IF NOT EXISTS `tbroles` (
`idrol` int(11) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `activo` bit(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tbroles`
--

INSERT INTO `tbroles` (`idrol`, `descripcion`, `activo`) VALUES
(1, 'Administrador', b'1'),
(2, 'Empleado', b'1'),
(3, 'Cliente', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbtipoubicacion`
--

CREATE TABLE IF NOT EXISTS `tbtipoubicacion` (
`idtipoubicacion` int(11) NOT NULL,
  `tipoubicacion` varchar(50) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tbtipoubicacion`
--

INSERT INTO `tbtipoubicacion` (`idtipoubicacion`, `tipoubicacion`) VALUES
(2, '1 DORM GROC'),
(4, '2 DORM GROC'),
(5, '2 DORM VERM'),
(6, '2 DORM WW'),
(7, '3 DORM GROC'),
(8, '3 DORM VERM'),
(9, '3 DORM WW'),
(10, '3 Dorm Luisa');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `dbauditoria`
--
ALTER TABLE `dbauditoria`
 ADD PRIMARY KEY (`idauditoria`);

--
-- Indices de la tabla `dbclientes`
--
ALTER TABLE `dbclientes`
 ADD PRIMARY KEY (`idcliente`);

--
-- Indices de la tabla `dbperiodos`
--
ALTER TABLE `dbperiodos`
 ADD PRIMARY KEY (`idperiodo`);

--
-- Indices de la tabla `dbtarifas`
--
ALTER TABLE `dbtarifas`
 ADD PRIMARY KEY (`idtarifa`), ADD KEY `fk_tarifa_tipoubicacion_idx` (`reftipoubicacion`);

--
-- Indices de la tabla `dbubicaciones`
--
ALTER TABLE `dbubicaciones`
 ADD PRIMARY KEY (`idubicacion`), ADD KEY `fk_ubicacion_tipoubicacion_idx` (`reftipoubicacion`);

--
-- Indices de la tabla `dbusuarios`
--
ALTER TABLE `dbusuarios`
 ADD PRIMARY KEY (`idusuario`), ADD KEY `fk_dbusuarios_tbroles1_idx` (`refroles`);

--
-- Indices de la tabla `predio_menu`
--
ALTER TABLE `predio_menu`
 ADD PRIMARY KEY (`idmenu`);

--
-- Indices de la tabla `tbconfiguracion`
--
ALTER TABLE `tbconfiguracion`
 ADD PRIMARY KEY (`idconfiguracion`);

--
-- Indices de la tabla `tbestados`
--
ALTER TABLE `tbestados`
 ADD PRIMARY KEY (`idestado`);

--
-- Indices de la tabla `tbformaspagos`
--
ALTER TABLE `tbformaspagos`
 ADD PRIMARY KEY (`idformapago`);

--
-- Indices de la tabla `tbformularios`
--
ALTER TABLE `tbformularios`
 ADD PRIMARY KEY (`idformulario`);

--
-- Indices de la tabla `tbmeses`
--
ALTER TABLE `tbmeses`
 ADD PRIMARY KEY (`idmes`);

--
-- Indices de la tabla `tbroles`
--
ALTER TABLE `tbroles`
 ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `tbtipoubicacion`
--
ALTER TABLE `tbtipoubicacion`
 ADD PRIMARY KEY (`idtipoubicacion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `dbauditoria`
--
ALTER TABLE `dbauditoria`
MODIFY `idauditoria` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `dbclientes`
--
ALTER TABLE `dbclientes`
MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `dbperiodos`
--
ALTER TABLE `dbperiodos`
MODIFY `idperiodo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `dbtarifas`
--
ALTER TABLE `dbtarifas`
MODIFY `idtarifa` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `dbubicaciones`
--
ALTER TABLE `dbubicaciones`
MODIFY `idubicacion` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT de la tabla `dbusuarios`
--
ALTER TABLE `dbusuarios`
MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `predio_menu`
--
ALTER TABLE `predio_menu`
MODIFY `idmenu` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT de la tabla `tbconfiguracion`
--
ALTER TABLE `tbconfiguracion`
MODIFY `idconfiguracion` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `tbestados`
--
ALTER TABLE `tbestados`
MODIFY `idestado` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `tbformaspagos`
--
ALTER TABLE `tbformaspagos`
MODIFY `idformapago` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tbformularios`
--
ALTER TABLE `tbformularios`
MODIFY `idformulario` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tbmeses`
--
ALTER TABLE `tbmeses`
MODIFY `idmes` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `tbroles`
--
ALTER TABLE `tbroles`
MODIFY `idrol` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `tbtipoubicacion`
--
ALTER TABLE `tbtipoubicacion`
MODIFY `idtipoubicacion` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `dbtarifas`
--
ALTER TABLE `dbtarifas`
ADD CONSTRAINT `fk_tarifa_tipoubicacion` FOREIGN KEY (`reftipoubicacion`) REFERENCES `tbtipoubicacion` (`idtipoubicacion`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `dbubicaciones`
--
ALTER TABLE `dbubicaciones`
ADD CONSTRAINT `fk_ubicacion_tipoubicacion` FOREIGN KEY (`reftipoubicacion`) REFERENCES `tbtipoubicacion` (`idtipoubicacion`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

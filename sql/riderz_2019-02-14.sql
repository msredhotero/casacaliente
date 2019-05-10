-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-02-2019 a las 15:34:43
-- Versión del servidor: 5.6.21
-- Versión de PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `riderz`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbactivacionusuarios`
--

CREATE TABLE IF NOT EXISTS `dbactivacionusuarios` (
`idactivacionusuario` int(11) NOT NULL,
  `refusuarios` int(11) NOT NULL,
  `token` varchar(36) COLLATE utf8_spanish2_ci NOT NULL,
  `vigenciadesde` date NOT NULL,
  `vigenciahasta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbarchivos`
--

CREATE TABLE IF NOT EXISTS `dbarchivos` (
`idarchivo` int(11) NOT NULL,
  `refclientes` int(11) NOT NULL,
  `refcategorias` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `mes` int(11) NOT NULL,
  `token` varchar(36) NOT NULL,
  `type` varchar(45) DEFAULT NULL,
  `observacion` varchar(150) DEFAULT NULL,
  `imagen` varchar(149) DEFAULT NULL,
  `fechacreacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `dbarchivos`
--

INSERT INTO `dbarchivos` (`idarchivo`, `refclientes`, `refcategorias`, `anio`, `mes`, `token`, `type`, `observacion`, `imagen`, `fechacreacion`) VALUES
(14, 10, 1, 2019, 2, '24444B42-6686-4598-AC53-D72FD6DBAEAE', 'application/pdf', '', 'DI-2018-342-GDEBA-DGAMEGPAMTAE.pdf', '2019-02-14 14:05:50'),
(15, 11, 1, 2019, 2, 'E0D12501-C7BD-4DE6-84DE-A277AC07B27A', 'application/pdf', '', 'PVSA-PortaldeLicitaciones-Workana.pdf', '2019-02-14 14:29:40'),
(16, 12, 1, 2019, 2, '2C17680C-0350-4FF1-935B-77B5D4393248', 'application/pdf', '', 'DI-2018-342-GDEBA-DGAMEGPAMTAE.pdf', '2019-02-14 14:31:20'),
(17, 13, 1, 2019, 2, '5ADEE799-F0CE-4C34-8001-4402024C51DB', 'application/pdf', '', 'Nota_DEPOSITARENCUENTAEXISTENTE_20140429.pdf', '2019-02-14 14:33:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbclientes`
--

CREATE TABLE IF NOT EXISTS `dbclientes` (
`idcliente` int(11) NOT NULL,
  `reftipodocumentos` int(11) NOT NULL,
  `apellido` varchar(120) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(120) COLLATE utf8_spanish2_ci NOT NULL,
  `nrodocumento` varchar(10) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `celular` varchar(25) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `email` varchar(60) COLLATE utf8_spanish2_ci NOT NULL,
  `aceptaterminos` bit(1) NOT NULL DEFAULT b'0',
  `subscripcion` bit(1) NOT NULL DEFAULT b'0',
  `activo` bit(1) DEFAULT b'0'
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `dbclientes`
--

INSERT INTO `dbclientes` (`idcliente`, `reftipodocumentos`, `apellido`, `nombre`, `nrodocumento`, `telefono`, `celular`, `email`, `aceptaterminos`, `subscripcion`, `activo`) VALUES
(24, 1, 'SAFAR', 'Marcos Saupurein', '31553466', '1698564654', '', 'campochico@aif.org.ar', b'1', b'0', b'1'),
(26, 1, 'ropaldo', 'daniela', '984986', '', '', 'ropaldo@gmail.com', b'1', b'1', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dbfacturas`
--

CREATE TABLE IF NOT EXISTS `dbfacturas` (
`idfactura` int(11) NOT NULL,
  `refclientes` int(11) NOT NULL,
  `reftipofacturas` int(11) NOT NULL,
  `refestados` int(11) NOT NULL,
  `refmeses` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `concepto` varchar(180) COLLATE utf8_spanish_ci DEFAULT NULL,
  `total` decimal(18,2) DEFAULT '0.00',
  `iva` decimal(6,2) DEFAULT '0.00',
  `irff` decimal(8,2) DEFAULT '0.00',
  `fechaingreso` date NOT NULL,
  `fechasubido` date NOT NULL,
  `imagen` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `dbfacturas`
--

INSERT INTO `dbfacturas` (`idfactura`, `refclientes`, `reftipofacturas`, `refestados`, `refmeses`, `anio`, `concepto`, `total`, `iva`, `irff`, `fechaingreso`, `fechasubido`, `imagen`) VALUES
(10, 26, 1, 1, 1, 2019, 'tarjetaVisa', '2500.00', '25.00', '100.00', '2019-01-15', '2019-01-20', 'asduhas'),
(11, 26, 2, 1, 1, 2019, '', '2500.00', '25.00', '100.00', '2019-01-12', '2019-01-15', 'asduhas'),
(12, 26, 2, 1, 1, 2019, '', NULL, '25.00', '100.00', '2019-01-12', '2019-01-15', 'asduhas'),
(13, 26, 1, 1, 1, 2019, '', NULL, NULL, NULL, '2019-01-12', '2019-01-15', 'asduhas');

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
  `activo` bit(1) DEFAULT b'0',
  `refclientes` int(11) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `dbusuarios`
--

INSERT INTO `dbusuarios` (`idusuario`, `usuario`, `password`, `refroles`, `email`, `nombrecompleto`, `activo`, `refclientes`) VALUES
(1, 'msredhortero', 'marcos', 1, 'msredhotero@msn.com', 'Saupurein Marcos', b'1', NULL),
(19, 'SAFAR Marcos Saupurein', 'marcos', 3, 'campochico@aif.org.ar', 'SAFAR Marcos Saupurein', b'1', 24),
(20, 'ropaldo daniela', '123456', 3, 'ropaldo@gmail.com', 'ropaldo daniela', b'1', 26);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `images`
--

CREATE TABLE IF NOT EXISTS `images` (
`idfoto` int(11) NOT NULL,
  `refproyecto` int(11) NOT NULL,
  `refuser` int(11) NOT NULL,
  `imagen` varchar(149) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `principal` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `predio_menu`
--

INSERT INTO `predio_menu` (`idmenu`, `url`, `icono`, `nombre`, `Orden`, `hover`, `permiso`, `administracion`, `torneo`, `reportes`, `grupo`) VALUES
(11, '../documentos/', 'description', 'Mis Documentos', 6, NULL, 'Cliente', b'1', b'1', b'1', 0),
(13, '../index.php', 'dashboard', 'Dashboard', 1, NULL, 'Administrador, Cliente, Empleado', b'1', b'1', b'0', 0),
(14, '../perfil/', 'account_circle', 'Mi Perfil', 3, '', 'Cliente', b'1', b'1', b'1', 0),
(15, '../facturas/', 'list', 'Mis Facturas', 4, NULL, 'Cliente', b'1', b'1', b'1', 0),
(16, '../impuestos/', 'gavel', 'Mis Impuestos', 5, NULL, 'Cliente', b'1', b'1', b'1', 0),
(19, '../clientes/', 'people_outline', 'Clientes', 2, NULL, 'Administrador, Empleado', b'1', b'0', b'0', 0),
(20, '../usuarios/', 'account_circle', 'Usuarios', 12, NULL, 'Administrador, Empleado', b'1', b'0', b'0', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbcategorias`
--

CREATE TABLE IF NOT EXISTS `tbcategorias` (
`idcategoria` int(11) NOT NULL,
  `categoria` varchar(50) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `tbcategorias`
--

INSERT INTO `tbcategorias` (`idcategoria`, `categoria`) VALUES
(1, 'Impuestos'),
(2, 'Consultoria');

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
(1, 'RIDERZ', 'RIDERZ', 'RIDERZ', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbestados`
--

CREATE TABLE IF NOT EXISTS `tbestados` (
`idestado` int(11) NOT NULL,
  `estado` varchar(80) COLLATE utf8_spanish_ci NOT NULL,
  `color` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `icono` varchar(80) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tbestados`
--

INSERT INTO `tbestados` (`idestado`, `estado`, `color`, `icono`) VALUES
(1, 'Iniciado', 'blue', NULL),
(2, 'Aceptado', 'green', NULL),
(3, 'Rechazado', 'red', NULL);

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
-- Estructura de tabla para la tabla `tbtipodocumentos`
--

CREATE TABLE IF NOT EXISTS `tbtipodocumentos` (
`idtipodocumento` int(11) NOT NULL,
  `tipodocumento` varchar(60) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tbtipodocumentos`
--

INSERT INTO `tbtipodocumentos` (`idtipodocumento`, `tipodocumento`) VALUES
(1, 'DNI'),
(2, 'NIE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbtipofacturas`
--

CREATE TABLE IF NOT EXISTS `tbtipofacturas` (
`idtipofactura` int(11) NOT NULL,
  `tipofactura` varchar(60) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tbtipofacturas`
--

INSERT INTO `tbtipofacturas` (`idtipofactura`, `tipofactura`) VALUES
(1, 'Ingresos'),
(2, 'Gastos');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `dbactivacionusuarios`
--
ALTER TABLE `dbactivacionusuarios`
 ADD PRIMARY KEY (`idactivacionusuario`);

--
-- Indices de la tabla `dbarchivos`
--
ALTER TABLE `dbarchivos`
 ADD PRIMARY KEY (`idarchivo`);

--
-- Indices de la tabla `dbclientes`
--
ALTER TABLE `dbclientes`
 ADD PRIMARY KEY (`idcliente`), ADD KEY `fk_c_td_idx` (`reftipodocumentos`);

--
-- Indices de la tabla `dbfacturas`
--
ALTER TABLE `dbfacturas`
 ADD PRIMARY KEY (`idfactura`), ADD KEY `fk_f_e_idx` (`refestados`), ADD KEY `fk_f_tf_idx` (`reftipofacturas`), ADD KEY `fk_f_m_idx` (`refmeses`);

--
-- Indices de la tabla `dbusuarios`
--
ALTER TABLE `dbusuarios`
 ADD PRIMARY KEY (`idusuario`), ADD KEY `fk_dbusuarios_tbroles1_idx` (`refroles`);

--
-- Indices de la tabla `images`
--
ALTER TABLE `images`
 ADD PRIMARY KEY (`idfoto`);

--
-- Indices de la tabla `predio_menu`
--
ALTER TABLE `predio_menu`
 ADD PRIMARY KEY (`idmenu`);

--
-- Indices de la tabla `tbcategorias`
--
ALTER TABLE `tbcategorias`
 ADD PRIMARY KEY (`idcategoria`);

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
-- Indices de la tabla `tbtipodocumentos`
--
ALTER TABLE `tbtipodocumentos`
 ADD PRIMARY KEY (`idtipodocumento`);

--
-- Indices de la tabla `tbtipofacturas`
--
ALTER TABLE `tbtipofacturas`
 ADD PRIMARY KEY (`idtipofactura`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `dbactivacionusuarios`
--
ALTER TABLE `dbactivacionusuarios`
MODIFY `idactivacionusuario` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `dbarchivos`
--
ALTER TABLE `dbarchivos`
MODIFY `idarchivo` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT de la tabla `dbclientes`
--
ALTER TABLE `dbclientes`
MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT de la tabla `dbfacturas`
--
ALTER TABLE `dbfacturas`
MODIFY `idfactura` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `dbusuarios`
--
ALTER TABLE `dbusuarios`
MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT de la tabla `images`
--
ALTER TABLE `images`
MODIFY `idfoto` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tbcategorias`
--
ALTER TABLE `tbcategorias`
MODIFY `idcategoria` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tbconfiguracion`
--
ALTER TABLE `tbconfiguracion`
MODIFY `idconfiguracion` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `tbestados`
--
ALTER TABLE `tbestados`
MODIFY `idestado` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
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
-- AUTO_INCREMENT de la tabla `tbtipodocumentos`
--
ALTER TABLE `tbtipodocumentos`
MODIFY `idtipodocumento` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tbtipofacturas`
--
ALTER TABLE `tbtipofacturas`
MODIFY `idtipofactura` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `dbclientes`
--
ALTER TABLE `dbclientes`
ADD CONSTRAINT `fk_c_td` FOREIGN KEY (`reftipodocumentos`) REFERENCES `tbtipodocumentos` (`idtipodocumento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `dbfacturas`
--
ALTER TABLE `dbfacturas`
ADD CONSTRAINT `fk_f_e` FOREIGN KEY (`refestados`) REFERENCES `tbestados` (`idestado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_f_m` FOREIGN KEY (`refmeses`) REFERENCES `tbmeses` (`idmes`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_f_tf` FOREIGN KEY (`reftipofacturas`) REFERENCES `tbtipofacturas` (`idtipofactura`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

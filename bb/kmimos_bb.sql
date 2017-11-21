-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 29-09-2017 a las 20:39:14
-- Versión del servidor: 5.7.19-0ubuntu0.16.04.1
-- Versión de PHP: 7.0.22-2+ubuntu16.04.1+deb.sury.org+4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `kmimos_bb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formulario`
--

CREATE TABLE `formulario` (
  `id_formulario` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `apellido` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `correo` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `estado` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `municipio` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `desarrollo` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `raza` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tamano` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `peso` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `norecuerdo` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `brucelosis` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `ehrlichiosis` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `hemobartonelosis` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `leishmaniasis` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `babesiosis` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `filariasis` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `toxoplasmosis` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `anaplasma` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `ninguna` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `moquillo` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `hepatitis` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `parvovirus` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `parainfluenza` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `rabia` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `leptospirosis` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `desparasitado` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `nombremascota` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `formulario`
--

INSERT INTO `formulario` (`id_formulario`, `fecha`, `nombre`, `apellido`, `telefono`, `correo`, `estado`, `municipio`, `desarrollo`, `raza`, `tamano`, `peso`, `norecuerdo`, `brucelosis`, `ehrlichiosis`, `hemobartonelosis`, `leishmaniasis`, `babesiosis`, `filariasis`, `toxoplasmosis`, `anaplasma`, `ninguna`, `moquillo`, `hepatitis`, `parvovirus`, `parainfluenza`, `rabia`, `leptospirosis`, `desparasitado`, `nombremascota`) VALUES
(1, '2017-09-29', 'Yrcel', 'Chaudary', '04123685950', 'chaudaryy@gmail.com', 'Aguascalientes', 'Asientos', 'Cachorro', 'Alaskan malamute', 'PequeÃ±o', 'Menos 20Kg', '', '', '', '', '', '', '', '', '', 'Ninguna', '', '', '', '', 'Rabia', '', 'Desparasitado', 'Apolo'),
(2, '2017-09-29', 'Antonio', 'Hevia', '04123685950', 'chaudaryy@gmail.com', 'Nayarit', 'Santiago Ixcuintla', 'Cachorro', 'Beagle', 'Mediano', 'Mas 20Kg', 'No Recuerdo', '', '', '', '', '', '', '', '', '', 'Moquillo', '', '', '', '', '', 'Desparasitado', 'Pepe');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `identificacion` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `estatus` tinyint(1) NOT NULL,
  `cargo` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `permisologia` tinyint(4) NOT NULL,
  `usuario` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `clave` char(64) COLLATE utf8_unicode_ci NOT NULL,
  `clave_1` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `identificacion`, `estatus`, `cargo`, `permisologia`, `usuario`, `clave`, `clave_1`) VALUES
(1, 'MASTER', '---', 1, 'MASTER', 1, 'kmimosbb', '5d46ebd552bdca72064555463d01334cdaa220db32b32fdbcb6d02d10b811166', 'kmimosbb*');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `formulario`
--
ALTER TABLE `formulario`
  ADD PRIMARY KEY (`id_formulario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `formulario`
--
ALTER TABLE `formulario`
  MODIFY `id_formulario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

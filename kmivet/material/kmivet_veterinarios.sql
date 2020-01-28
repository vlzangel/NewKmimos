-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-01-2020 a las 21:33:01
-- Versión del servidor: 10.4.8-MariaDB
-- Versión de PHP: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `kmivet`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_kmivet_veterinarios`
--

CREATE TABLE `wp_kmivet_veterinarios` (
  `id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `data` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `wp_kmivet_veterinarios`
--

INSERT INTO `wp_kmivet_veterinarios` (`id`, `medico_id`, `data`) VALUES
(1, 12, '{\"kv_nombre\":\"Mariela Garcia\",\"kv_email\":\"marielacgg@hotmail.com\",\"kv_email_no_usado\":\"on\",\"kv_fecha\":\"1979-08-02\",\"kv_genero\":\"Mujer\",\"kv_dni\":\"123456\",\"kv_rfc\":\"223344\",\"kv_referencia\":\"123321\",\"kv_referido\":\"Twitter\",\"kv_calle\":\"5 de mayo\",\"kv_interior\":\"lnterior\",\"kv_estado\":\"3\",\"kv_delegacion\":\"27\",\"kv_colonia\":\"43312\",\"kv_postal\":\"1234\",\"kv_telf_fijo\":\"523456789\",\"kv_telf_movil\":\"522346543\",\"kv_titulo\":\"Veterinario\",\"kv_cedula\":\"112345\",\"kv_universidad\":\"UDM\",\"kv_internado\":\"internado\",\"kv_servicio_social\":\"servicio social\",\"kv_cursos_realizados\":\"cursos\",\"kv_otros_estudios\":\"otros estudios\",\"kv_trabajos\":\"trabajos\",\"kv_idiomas\":\"idiomas\",\"kv_red_seguro\":\"No\",\"kv_red_seguros\":\"\",\"kv_tiene_otra_especialidad\":\"Si\",\"kv_red_otra_especialidad\":\"Cardiologia\",\"kv_red_otra_cedula\":\"123454333\",\"kv_red_otra_universidad\":\"UDM\",\"kv_tiene_auto\":\"Si\",\"kv_tiene_licencia\":\"Si\",\"kv_tiene_disponibilidad\":\"Si\",\"kv_seguro_responsabilidad\":\"No\",\"kv_seguro_empresa\":\"\",\"kv_no_poliza\":\"\",\"kv_primera_ref_nombre\":\"Jose Perez\",\"kv_primera_ref_telefono\":\"5674323\",\"kv_primera_ref_email\":\"jose,perez122@gmail.com\",\"kv_segunda_ref_nombre\":\"\",\"kv_segunda_ref_telefono\":\"\",\"kv_segunda_ref_email\":\"\",\"kv_tercera_ref_nombre\":\"\",\"kv_tercera_ref_telefono\":\"\",\"kv_tercera_ref_email\":\"\",\"kv_terminos\":\"on\"}'),
(4, 15, '{\"kv_nombre\":\"pedro perez\",\"kv_email\":\"mary.garciag@gmail.com\",\"kv_email_no_usado\":\"on\",\"kv_fecha\":\"1983-02-20\",\"kv_genero\":\"0\",\"kv_dni\":\"12345\",\"kv_rfc\":\"12354\",\"kv_referencia\":\"885454\",\"kv_referido\":\"Página de MediQo\",\"kv_calle\":\"5 de mayo \",\"kv_interior\":\"mmmmm\",\"kv_estado\":\"3\",\"kv_delegacion\":\"27\",\"kv_colonia\":\"42867\",\"kv_postal\":\"522214\",\"kv_telf_fijo\":\"123456789\",\"kv_telf_movil\":\"32145687\",\"kv_titulo\":\"hgfghfgh\",\"kv_cedula\":\"122222\",\"kv_universidad\":\"ghgfhfhg\",\"kv_internado\":\"ghfghjfg\",\"kv_servicio_social\":\"fghfghf\",\"kv_cursos_realizados\":\"fhfgh\",\"kv_otros_estudios\":\"fhfhf\",\"kv_trabajos\":\"fhfhf\",\"kv_idiomas\":\"hfhfh\",\"kv_red_seguro\":\"No\",\"kv_red_seguros\":\"\",\"kv_tiene_otra_especialidad\":\"Si\",\"kv_red_otra_especialidad\":\"cardiologia\",\"kv_red_otra_cedula\":\"31313\",\"kv_red_otra_universidad\":\"iouiouo\",\"kv_tiene_auto\":\"Si\",\"kv_tiene_licencia\":\"Si\",\"kv_tiene_disponibilidad\":\"Si\",\"kv_seguro_responsabilidad\":\"No\",\"kv_seguro_empresa\":\"\",\"kv_no_poliza\":\"\",\"kv_primera_ref_nombre\":\"hfhr\",\"kv_primera_ref_telefono\":\"hghfghg\",\"kv_primera_ref_email\":\"fhgfhgf@gmail.com\",\"kv_segunda_ref_nombre\":\"\",\"kv_segunda_ref_telefono\":\"\",\"kv_segunda_ref_email\":\"\",\"kv_tercera_ref_nombre\":\"\",\"kv_tercera_ref_telefono\":\"\",\"kv_tercera_ref_email\":\"\",\"kv_terminos\":\"on\"}'),
(22, 33, '{\"kv_nombre\":\"Mariela gallardo\",\"kv_email\":\"Marielacgg@hotmail.com\",\"kv_email_no_usado\":\"on\",\"kv_fecha\":\"1999-06-22\",\"kv_genero\":\"1\",\"kv_dni\":\"8523658\",\"kv_rfc\":\"Tdhdh\",\"kv_referencia\":\"183736\",\"kv_referido\":\"Twitter\",\"kv_calle\":\"5 de mayo\",\"kv_interior\":\"Gdgdgd\",\"kv_estado\":\"3\",\"kv_delegacion\":\"27\",\"kv_colonia\":\"42867\",\"kv_postal\":\"359890\",\"kv_telf_fijo\":\"63736453828\",\"kv_telf_movil\":\"7373737374\",\"kv_titulo\":\"Veterinario\",\"kv_cedula\":\"636373\",\"kv_universidad\":\"Universidad de México\",\"kv_internado\":\"Hdjdjd\",\"kv_servicio_social\":\"Jdjdjd\",\"kv_cursos_realizados\":\"Primeros auxilios\",\"kv_otros_estudios\":\"Estudios\",\"kv_trabajos\":\"Trabajos\",\"kv_idiomas\":\"Ingles\",\"kv_red_seguro\":\"No\",\"kv_red_seguros\":\"\",\"kv_tiene_otra_especialidad\":\"No\",\"kv_red_otra_especialidad\":\"\",\"kv_red_otra_cedula\":\"\",\"kv_red_otra_universidad\":\"\",\"kv_tiene_auto\":\"Si\",\"kv_tiene_licencia\":\"Si\",\"kv_tiene_disponibilidad\":\"Si\",\"kv_seguro_responsabilidad\":\"No\",\"kv_seguro_empresa\":\"\",\"kv_no_poliza\":\"\",\"kv_primera_ref_nombre\":\"Mónica Velázquez\",\"kv_primera_ref_telefono\":\"73747373\",\"kv_primera_ref_email\":\"Monicavelasquez1@gmail.com\",\"kv_segunda_ref_nombre\":\"\",\"kv_segunda_ref_telefono\":\"\",\"kv_segunda_ref_email\":\"\",\"kv_tercera_ref_nombre\":\"\",\"kv_tercera_ref_telefono\":\"\",\"kv_tercera_ref_email\":\"\",\"kv_terminos\":\"on\"}'),
(23, 34, '{\"kv_nombre\":\"Mariela gallardo\",\"kv_email\":\"Marielacgg@hotmail.com\",\"kv_email_no_usado\":\"on\",\"kv_fecha\":\"1999-06-22\",\"kv_genero\":\"1\",\"kv_dni\":\"8523658\",\"kv_rfc\":\"Tdhdh\",\"kv_referencia\":\"183736\",\"kv_referido\":\"Twitter\",\"kv_calle\":\"5 de mayo\",\"kv_interior\":\"Gdgdgd\",\"kv_estado\":\"3\",\"kv_delegacion\":\"27\",\"kv_colonia\":\"42867\",\"kv_postal\":\"359890\",\"kv_telf_fijo\":\"63736453828\",\"kv_telf_movil\":\"7373737374\",\"kv_titulo\":\"Veterinario\",\"kv_cedula\":\"636373\",\"kv_universidad\":\"Universidad de México\",\"kv_internado\":\"Hdjdjd\",\"kv_servicio_social\":\"Jdjdjd\",\"kv_cursos_realizados\":\"Primeros auxilios\",\"kv_otros_estudios\":\"Estudios\",\"kv_trabajos\":\"Trabajos\",\"kv_idiomas\":\"Ingles\",\"kv_red_seguro\":\"No\",\"kv_red_seguros\":\"\",\"kv_tiene_otra_especialidad\":\"No\",\"kv_red_otra_especialidad\":\"\",\"kv_red_otra_cedula\":\"\",\"kv_red_otra_universidad\":\"\",\"kv_tiene_auto\":\"Si\",\"kv_tiene_licencia\":\"Si\",\"kv_tiene_disponibilidad\":\"Si\",\"kv_seguro_responsabilidad\":\"No\",\"kv_seguro_empresa\":\"\",\"kv_no_poliza\":\"\",\"kv_primera_ref_nombre\":\"Mónica Velázquez\",\"kv_primera_ref_telefono\":\"73747373\",\"kv_primera_ref_email\":\"Monicavelasquez1@gmail.com\",\"kv_segunda_ref_nombre\":\"\",\"kv_segunda_ref_telefono\":\"\",\"kv_segunda_ref_email\":\"\",\"kv_tercera_ref_nombre\":\"\",\"kv_tercera_ref_telefono\":\"\",\"kv_tercera_ref_email\":\"\",\"kv_terminos\":\"on\"}');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `wp_kmivet_veterinarios`
--
ALTER TABLE `wp_kmivet_veterinarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `wp_kmivet_veterinarios`
--
ALTER TABLE `wp_kmivet_veterinarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

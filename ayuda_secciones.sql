/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : kmimos_mx_new

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-01-12 19:16:06
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ayuda_secciones`
-- ----------------------------
DROP TABLE IF EXISTS `ayuda_secciones`;
CREATE TABLE `ayuda_secciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(300) DEFAULT NULL,
  `descripcion` text,
  `temas` text,
  `temas_relacionados` text,
  `activo` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of ayuda_secciones
-- ----------------------------

-- ----------------------------
-- Table structure for `ayuda_temas`
-- ----------------------------
DROP TABLE IF EXISTS `ayuda_temas`;
CREATE TABLE `ayuda_temas` (
  `id` int(11) NOT NULL,
  `titulo` text,
  `contenido` text,
  `usuario_tipo` varchar(10) DEFAULT NULL,
  `destacada` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of ayuda_temas
-- ----------------------------

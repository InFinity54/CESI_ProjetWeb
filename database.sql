/*
Navicat MySQL Data Transfer

Source Server         : Laragon
Source Server Version : 50724
Source Host           : localhost:3306
Source Database       : cesiprojetweb

Target Server Type    : MYSQL
Target Server Version : 50724
File Encoding         : 65001

Date: 2020-08-18 12:41:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for agents
-- ----------------------------
DROP TABLE IF EXISTS `agents`;
CREATE TABLE `agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of agents
-- ----------------------------
INSERT INTO `agents` VALUES ('1', 'ckreicher', '$2y$13$KX7pFWtDDALItsk5.IlF8OPFzStg0ssTX/X54dmb20ZrAAKEu3sNy', 'ROLE_ADMIN', 'KREICHER', 'Corentin');
INSERT INTO `agents` VALUES ('2', 'mdidelot', '$2y$13$q/bW8BeL8M2AokH9lsHjF.4aYFzTBbsTv7lJvDas0aJGBZuJj5GEm', 'ROLE_ADMIN', 'DIDELOT', 'Morgane');
INSERT INTO `agents` VALUES ('3', 'aschlosser', '$2y$13$FxQN52esye.fMFgn4wT1QOsTRnXALPMF1HIPtEtBpk0zPbwWJ8MHO', 'ROLE_ADMIN', 'SCHLOSSER', 'Axel');
INSERT INTO `agents` VALUES ('4', 'adebeukelaer', '$2y$13$tjYIR7hy15SDj1/pkXkZyOYeTsBoNtwBljEMfD36gmBVmrVVtmXS6', 'ROLE_ADMIN', 'DE BEUKELAER', 'Alexandre');

-- ----------------------------
-- Table structure for doctrine_migration_versions
-- ----------------------------
DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of doctrine_migration_versions
-- ----------------------------
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20200818085514', '2020-08-18 08:55:43', '257');
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20200818092832', '2020-08-18 09:28:37', '1752');
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20200818103440', '2020-08-18 10:34:48', '1954');
SET FOREIGN_KEY_CHECKS=1;

CREATE DATABASE `recycle_batteries`;
USE `recycle_batteries`;
CREATE TABLE `battery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `count` int(11) NOT NULL,
  `name` varchar(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
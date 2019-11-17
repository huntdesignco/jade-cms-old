CREATE TABLE `users` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` BOOLEAN DEFAULT FALSE,
  `is_suspended` BOOLEAN DEFAULT FALSE,
  `activation_token` VARCHAR(255),
  `reset_token` VARCHAR(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `t_article` (
  `id_art` int NOT NULL AUTO_INCREMENT,
  `ident_art` int DEFAULT NULL,
  `date_art` date DEFAULT NULL,
  `readtime_art` int DEFAULT NULL,
  `title_art` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hook_art` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `url_art` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fk_category_art` int DEFAULT NULL,
  `content_art` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `image_art` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_art`),
  KEY `IDX_5816B605F82A7917` (`fk_category_art`),
  CONSTRAINT `t_article_t_category_id_cat_fk` FOREIGN KEY (`fk_category_art`) REFERENCES `t_category` (`id_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=5594 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci




CREATE TABLE `favorites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `article_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E46960F5A76ED395` (`user_id`),
  KEY `IDX_E46960F57294869C` (`article_id`),
  CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_E46960F57294869C` FOREIGN KEY (`article_id`) REFERENCES `t_article` (`id_art`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci





CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `profile_image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci



CREATE TABLE `t_category` (
  `id_cat` int NOT NULL AUTO_INCREMENT,
  `name_cat` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=256 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
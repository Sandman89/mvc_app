-- mvc_app.task definition

CREATE TABLE `task` (
                        `id` int NOT NULL AUTO_INCREMENT,
                        `text` text,
                        `username` varchar(255) DEFAULT NULL,
                        `email` varchar(255) DEFAULT NULL,
                        `status` tinyint(1) DEFAULT '0',
                        PRIMARY KEY (`id`)
) ENGINE=InnoDB
    DEFAULT CHARSET=utf8mb4
    COLLATE=utf8mb4_0900_ai_ci;
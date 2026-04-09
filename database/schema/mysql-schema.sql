

CREATE TABLE `books` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publisher` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `publication_year` year NOT NULL,
  `stock` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);
CREATE TABLE `borrowings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `borrowed_date` date NOT NULL,
  `due_date` date NOT NULL,
  `charge` decimal(10,2) DEFAULT NULL,
  `returned_date` date DEFAULT NULL,
  `status` enum('borrowed','returned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'borrowed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `borrowings_user_id_foreign` (`user_id`),
  CONSTRAINT `borrowings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
CREATE TABLE `borrowings_detail` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `borrowing_id` bigint unsigned NOT NULL,
  `book_id` bigint unsigned NOT NULL,
  `qty` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `borrowings_detail_borrowing_id_foreign` (`borrowing_id`),
  KEY `borrowings_detail_book_id_foreign` (`book_id`),
  CONSTRAINT `borrowings_detail_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  CONSTRAINT `borrowings_detail_borrowing_id_foreign` FOREIGN KEY (`borrowing_id`) REFERENCES `borrowings` (`id`) ON DELETE CASCADE
);
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('siswa','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'siswa',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
);
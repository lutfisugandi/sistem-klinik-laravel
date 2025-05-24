/*
 Navicat Premium Data Transfer

 Source Server         : MYSQL LOCAL
 Source Server Type    : MySQL
 Source Server Version : 100427
 Source Host           : localhost:3306
 Source Schema         : klinik_db

 Target Server Type    : MySQL
 Target Server Version : 100427
 File Encoding         : 65001

 Date: 24/05/2025 19:26:36
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for medical_records
-- ----------------------------
DROP TABLE IF EXISTS `medical_records`;
CREATE TABLE `medical_records`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `patient_id` bigint UNSIGNED NOT NULL,
  `visit_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('registered','vitals_checked','diagnosed','prescribed','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'registered',
  `weight` decimal(5, 2) NULL DEFAULT NULL,
  `blood_pressure` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `temperature` decimal(4, 2) NULL DEFAULT NULL,
  `heart_rate` int NULL DEFAULT NULL,
  `complaints` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `diagnosis` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `registered_at` timestamp NULL DEFAULT NULL,
  `vitals_at` timestamp NULL DEFAULT NULL,
  `diagnosed_at` timestamp NULL DEFAULT NULL,
  `prescribed_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `medical_records_visit_number_unique`(`visit_number` ASC) USING BTREE,
  INDEX `medical_records_patient_id_index`(`patient_id` ASC) USING BTREE,
  INDEX `medical_records_status_index`(`status` ASC) USING BTREE,
  INDEX `medical_records_visit_number_index`(`visit_number` ASC) USING BTREE,
  INDEX `medical_records_created_at_index`(`created_at` ASC) USING BTREE,
  CONSTRAINT `medical_records_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of medical_records
-- ----------------------------
INSERT INTO `medical_records` VALUES (1, 1, 'V202505240001', 'completed', 70.50, '130/85', 38.20, 88, 'Demam tinggi sejak 2 hari, sakit kepala, badan lemas, tidak nafsu makan', 'Demam Berdarah Dengue (DBD) Grade I', 'Pasien disarankan banyak minum air putih, istirahat cukup, kontrol rutin', '2025-05-24 10:42:04', '2025-05-22 10:57:04', '2025-05-22 11:12:04', '2025-05-22 11:27:04', '2025-05-22 11:42:04', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `medical_records` VALUES (2, 2, 'V202505240002', 'completed', 58.00, '110/70', 36.80, 72, 'Batuk berdahak sudah 1 minggu, dahak berwarna kuning, kadang sesak napas', 'Infeksi Saluran Pernapasan Atas (ISPA)', 'Hindari makanan dingin, perbanyak minum air hangat', '2025-05-24 10:42:04', '2025-05-23 10:52:04', '2025-05-23 11:07:04', '2025-05-23 11:22:04', '2025-05-23 11:37:04', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `medical_records` VALUES (3, 3, 'V202505240003', 'completed', 75.20, '140/90', 36.50, 85, 'Sakit maag kambuh, perut perih, mual, muntah setelah makan pedas', 'Gastritis Akut', 'Hindari makanan pedas, asam, dan berlemak. Makan teratur', '2025-05-24 10:42:04', '2025-05-24 07:54:04', '2025-05-24 08:10:04', '2025-05-24 08:27:04', '2025-05-24 08:42:04', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `medical_records` VALUES (4, 4, 'V202505240004', 'diagnosed', 52.50, '100/70', 37.10, 78, 'Gatal-gatal di tangan dan kaki, bentol-bentol merah, muncul setelah makan seafood', 'Dermatitis Alergi (Alergi Makanan)', 'Hindari seafood, catat makanan pemicu alergi', '2025-05-24 10:42:04', '2025-05-24 10:12:04', '2025-05-24 10:27:04', NULL, NULL, '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `medical_records` VALUES (5, 5, 'V202505240005', 'completed', 82.00, '150/95', 36.70, 92, 'Pusing, tengkuk kaku, mata berkunang-kunang, sering terjadi pagi hari', 'tes', 'tes', '2025-05-24 10:42:04', '2025-05-24 10:32:04', '2025-05-24 10:56:23', '2025-05-24 11:03:17', '2025-05-24 11:03:17', '2025-05-24 10:42:04', '2025-05-24 11:03:17');
INSERT INTO `medical_records` VALUES (6, 1, 'V202505240006', 'diagnosed', 65.50, '120/80', 36.50, 70, 'Demam tinggi 3 hari, sakit kepala, mual muntah, badan lemas', 'Demam Berdarah Dengue (DBD) Grade I', 'Monitor trombosit, banyak minum air putih', '2025-05-24 10:45:19', '2025-05-24 10:47:16', '2025-05-24 10:50:35', NULL, NULL, '2025-05-24 10:45:19', '2025-05-24 10:50:35');
INSERT INTO `medical_records` VALUES (7, 1, 'V202505240007', 'completed', 70.00, '140/90', 37.50, 85, 'Test keluhan', 'Test diagnosis', NULL, '2025-05-24 11:08:04', '2025-05-24 11:08:04', '2025-05-24 11:08:04', '2025-05-24 11:08:34', '2025-05-24 11:08:34', '2025-05-24 11:08:04', '2025-05-24 11:08:34');

-- ----------------------------
-- Table structure for medicines
-- ----------------------------
DROP TABLE IF EXISTS `medicines`;
CREATE TABLE `medicines`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `stock` int NOT NULL DEFAULT 0,
  `price` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pcs',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `medicines_name_index`(`name` ASC) USING BTREE,
  INDEX `medicines_type_index`(`type` ASC) USING BTREE,
  INDEX `medicines_stock_index`(`stock` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of medicines
-- ----------------------------
INSERT INTO `medicines` VALUES (1, 'Paracetamol 500mg (Sanmol)', 'tablet', 'Obat penurun demam dan pereda nyeri', 141, 500.00, 'tablet', '2025-05-24 10:42:04', '2025-05-24 11:03:17');
INSERT INTO `medicines` VALUES (2, 'Amoxicillin 500mg (Yusimox)', 'kapsul', 'Antibiotik untuk infeksi bakteri', 80, 1200.00, 'kapsul', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `medicines` VALUES (3, 'ORS (Oralit) Pharos', 'serbuk', 'Garam rehidrasi oral untuk diare', 50, 2500.00, 'sachet', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `medicines` VALUES (4, 'Betadine Solution 15ml', 'antiseptik', 'Antiseptik untuk luka luar', 25, 18000.00, 'botol', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `medicines` VALUES (5, 'Vitamin C 100mg (Redoxon)', 'tablet', 'Suplemen vitamin C', 120, 350.00, 'tablet', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `medicines` VALUES (6, 'Antasida DOEN', 'tablet', 'Obat maag dan dispepsia', 66, 750.00, 'tablet', '2025-05-24 10:42:04', '2025-05-24 11:08:34');
INSERT INTO `medicines` VALUES (7, 'CTM 4mg (Chlorpheniramine)', 'tablet', 'Antihistamin untuk alergi', 100, 400.00, 'tablet', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `medicines` VALUES (8, 'Ibuprofen 400mg (Proris)', 'tablet', 'Anti inflamasi dan pereda nyeri', 60, 1000.00, 'tablet', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `medicines` VALUES (9, 'Bisolvon Syrup 60ml', 'sirup', 'Obat batuk berdahak', 30, 25000.00, 'botol', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `medicines` VALUES (10, 'Promag Tablet', 'tablet', 'Obat sakit maag', 84, 800.00, 'tablet', '2025-05-24 10:42:04', '2025-05-24 11:08:34');
INSERT INTO `medicines` VALUES (11, 'Tolak Angin Cair', 'herbal', 'Obat herbal masuk angin', 40, 3500.00, 'sachet', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `medicines` VALUES (12, 'Mylanta Suspension', 'suspensi', 'Obat maag dan kembung', 35, 15000.00, 'botol', '2025-05-24 10:42:04', '2025-05-24 10:42:04');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (5, '2025_05_24_054746_add_role_to_users_table', 1);
INSERT INTO `migrations` VALUES (6, '2025_05_24_054938_create_patients_table', 1);
INSERT INTO `migrations` VALUES (7, '2025_05_24_054954_create_medicines_table', 1);
INSERT INTO `migrations` VALUES (8, '2025_05_24_055012_create_medical_records_table', 1);
INSERT INTO `migrations` VALUES (9, '2025_05_24_055022_create_prescriptions_table', 1);

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for patients
-- ----------------------------
DROP TABLE IF EXISTS `patients`;
CREATE TABLE `patients`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `patient_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_date` date NOT NULL,
  `gender` enum('L','P') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `patients_patient_number_unique`(`patient_number` ASC) USING BTREE,
  INDEX `patients_name_index`(`name` ASC) USING BTREE,
  INDEX `patients_patient_number_index`(`patient_number` ASC) USING BTREE,
  INDEX `patients_phone_index`(`phone` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of patients
-- ----------------------------
INSERT INTO `patients` VALUES (1, 'P20250524001', 'Bambang Sutrisno', '1985-03-15', 'L', '08123456001', 'Jl. Sudirman No. 123, Jakarta Pusat', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `patients` VALUES (2, 'P20250524002', 'Siti Nurhaliza', '1990-07-22', 'P', '08123456002', 'Jl. Gatot Subroto No. 45, Jakarta Selatan', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `patients` VALUES (3, 'P20250524003', 'Ahmad Fauzi', '1978-11-08', 'L', '08123456003', 'Jl. Thamrin No. 67, Jakarta Pusat', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `patients` VALUES (4, 'P20250524004', 'Rina Wati', '1995-02-14', 'P', '08123456004', 'Jl. Kemang Raya No. 89, Jakarta Selatan', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `patients` VALUES (5, 'P20250524005', 'Dedi Kurniawan', '1982-09-30', 'L', '08123456005', 'Jl. Pancoran No. 12, Jakarta Selatan', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `patients` VALUES (6, 'P20250524006', 'Maya Sari', '1988-12-05', 'P', '08123456006', 'Jl. Cikini Raya No. 34, Jakarta Pusat', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `patients` VALUES (7, 'P20250524007', 'Hendra Wijaya', '1975-06-18', 'L', '08123456007', 'Jl. Senayan No. 56, Jakarta Pusat', '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `patients` VALUES (8, 'P20250524008', 'Indah Permata', '1993-04-25', 'P', '08123456008', 'Jl. Kuningan No. 78, Jakarta Selatan', '2025-05-24 10:42:04', '2025-05-24 10:42:04');

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token` ASC) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type` ASC, `tokenable_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for prescriptions
-- ----------------------------
DROP TABLE IF EXISTS `prescriptions`;
CREATE TABLE `prescriptions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `medical_record_id` bigint UNSIGNED NOT NULL,
  `medicine_id` bigint UNSIGNED NOT NULL,
  `dosage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `prescriptions_medical_record_id_medicine_id_unique`(`medical_record_id` ASC, `medicine_id` ASC) USING BTREE,
  INDEX `prescriptions_medical_record_id_index`(`medical_record_id` ASC) USING BTREE,
  INDEX `prescriptions_medicine_id_index`(`medicine_id` ASC) USING BTREE,
  CONSTRAINT `prescriptions_medical_record_id_foreign` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `prescriptions_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of prescriptions
-- ----------------------------
INSERT INTO `prescriptions` VALUES (1, 1, 1, '3x1', 'Sesudah makan', 9, '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `prescriptions` VALUES (2, 1, 5, '2x1', 'Pagi dan sore', 6, '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `prescriptions` VALUES (3, 2, 2, '3x1', 'Sesudah makan', 15, '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `prescriptions` VALUES (4, 2, 9, '3x1 cth', 'Sesudah makan', 1, '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `prescriptions` VALUES (5, 3, 6, '3x1', 'Sebelum makan', 9, '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `prescriptions` VALUES (6, 3, 12, '3x1 cth', 'Saat perut perih', 1, '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `prescriptions` VALUES (7, 4, 7, '3x1', 'Sesudah makan', 9, '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `prescriptions` VALUES (8, 4, 1, '3x1', 'Jika demam', 6, '2025-05-24 10:42:04', '2025-05-24 10:42:04');
INSERT INTO `prescriptions` VALUES (12, 5, 1, '3x1', 'Sesudah makan bila demam', 9, '2025-05-24 11:03:17', '2025-05-24 11:03:17');
INSERT INTO `prescriptions` VALUES (16, 7, 6, '3x1', 'Sebelum makan', 9, '2025-05-24 11:08:34', '2025-05-24 11:08:34');
INSERT INTO `prescriptions` VALUES (17, 7, 10, '3x1', 'Saat perut perih', 6, '2025-05-24 11:08:34', '2025-05-24 11:08:34');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('pendaftaran','dokter','perawat','apoteker') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pendaftaran',
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE,
  INDEX `users_role_index`(`role` ASC) USING BTREE,
  INDEX `users_is_active_index`(`is_active` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Dewi Sartika, A.Md.Keb', 'pendaftaran@klinik.com', NULL, '$2y$10$WMJ6orllyTkjp3UefMg5WuR0F51LpXmm5BYixxpJ.8Attte74IGlm', NULL, '2025-05-24 10:42:04', '2025-05-24 10:42:04', 'pendaftaran', '08123456789', 1);
INSERT INTO `users` VALUES (2, 'dr. Budi Rahardjo, Sp.PD', 'dokter@klinik.com', NULL, '$2y$10$oVR9AHYD0dZkVhHQbtR/vurs59zb/XdK3EZ9Z04/RnvTOkmUKrDBe', NULL, '2025-05-24 10:42:04', '2025-05-24 10:42:04', 'dokter', '08234567890', 1);
INSERT INTO `users` VALUES (3, 'Ns. Siti Aminah, S.Kep', 'perawat@klinik.com', NULL, '$2y$10$0ZkWGPIcewGx.Sj1UOl1jeNEoI/K7rFC2DOFw0KoWHuX5dCiydpw6', NULL, '2025-05-24 10:42:04', '2025-05-24 10:42:04', 'perawat', '08345678901', 1);
INSERT INTO `users` VALUES (4, 'Apt. Agus Salim, S.Farm', 'apoteker@klinik.com', NULL, '$2y$10$i/z3ZkJfBlmXSrio5cf.5.Y49k5vlGDhURNtK7b/2ZvIr1ta4BPJW', NULL, '2025-05-24 10:42:04', '2025-05-24 10:42:04', 'apoteker', '08456789012', 1);

SET FOREIGN_KEY_CHECKS = 1;

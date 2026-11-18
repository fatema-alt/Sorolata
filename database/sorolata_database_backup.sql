-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: sorolata
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  KEY `activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  KEY `activity_logs_action_index` (`action`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,1,'system.initialized','Sorolata Studio Enterprise CMS initialized with full artisan ledger.',NULL,NULL,NULL,NULL,'2026-11-15 01:37:33'),(2,NULL,'page_created','Created page #5 (Mud Handcraft)',NULL,NULL,'[]','127.0.0.1','2026-11-15 04:38:13'),(3,1,'admin.login.success','Staff member [Kazi Farhan (Master Curator)] logged into Admin Studio from IP [127.0.0.1].',NULL,NULL,NULL,NULL,'2026-11-15 23:18:31'),(4,1,'admin.profile.update','Staff member [Momo] updated account credentials/profile.',NULL,NULL,NULL,NULL,'2026-11-15 23:19:15'),(5,1,'admin.login.success','Staff member [Momo] logged into Admin Studio from IP [127.0.0.1].',NULL,NULL,NULL,NULL,'2026-11-16 00:18:48'),(6,1,'admin.login.success','Staff member [Momo] logged into Admin Studio from IP [127.0.0.1].',NULL,NULL,NULL,NULL,'2026-11-16 00:18:57'),(7,1,'settings.updated','Updated store configuration parameters',NULL,NULL,'[]','127.0.0.1','2026-11-16 00:22:02'),(8,1,'order.payment_verified','Curator verified payment for Order #SRL-QKWURKPY (TrxID: ef43fs)','App\\Models\\Order',7,'[]','127.0.0.1','2026-11-16 00:35:52'),(9,NULL,'database.backup_created','Generated database backup sorolata_backup_2026-11-16_10-00-02.sql (203.91 KB)',NULL,NULL,'{\"filename\":\"sorolata_backup_2026-11-16_10-00-02.sql\",\"size_bytes\":208806,\"size\":\"203.91 KB\"}','127.0.0.1','2026-11-16 04:00:03'),(10,NULL,'database.backup_created','Generated database backup sorolata_backup_production_ready_2026-11-17_03-53-38.sql (205.29 KB)',NULL,NULL,'{\"filename\":\"sorolata_backup_production_ready_2026-11-17_03-53-38.sql\",\"size_bytes\":210220,\"size\":\"205.29 KB\"}','127.0.0.1','2026-11-16 21:53:41');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_notifications`
--

DROP TABLE IF EXISTS `admin_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'order',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `action_url` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_notifications_uuid_unique` (`uuid`),
  KEY `admin_notifications_type_index` (`type`),
  KEY `admin_notifications_is_read_index` (`is_read`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_notifications`
--

LOCK TABLES `admin_notifications` WRITE;
/*!40000 ALTER TABLE `admin_notifications` DISABLE KEYS */;
INSERT INTO `admin_notifications` VALUES (1,'49acb058-bdc5-42a2-b365-1354f6594def','order','Imperial Sonargaon Muslin Batch Dispatched','Insured courier dispatched for Order #SRL-BENGAL-01 to London, UK.','/admin/orders',0,NULL,'2026-11-15 01:37:33','2026-11-15 01:37:33');
/*!40000 ALTER TABLE `admin_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_settings`
--

DROP TABLE IF EXISTS `admin_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_settings_key_unique` (`key`),
  KEY `admin_settings_group_index` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_settings`
--

LOCK TABLES `admin_settings` WRITE;
/*!40000 ALTER TABLE `admin_settings` DISABLE KEYS */;
INSERT INTO `admin_settings` VALUES (1,'store_name','Sorolata Commerce House','general','2026-11-15 01:37:33','2026-11-15 01:37:33'),(2,'support_email','curator@sorolata.com','general','2026-11-15 01:37:33','2026-11-15 01:37:33'),(3,'support_phone','+880 1700 000 000','general','2026-11-15 01:37:33','2026-11-15 01:37:33'),(4,'currency','BDT','general','2026-11-15 01:37:33','2026-11-15 01:37:33'),(5,'shipping_free_threshold','5000','shipping','2026-11-15 01:37:33','2026-11-15 01:37:33'),(6,'shipping_default_fee','80','shipping','2026-11-15 01:37:33','2026-11-15 01:37:33'),(7,'payment_bkash_enabled','1','payment','2026-11-15 01:37:33','2026-11-15 01:37:33'),(8,'payment_nagad_enabled','1','payment','2026-11-15 01:37:33','2026-11-15 01:37:33'),(9,'payment_rocket_enabled','1','payment','2026-11-15 01:37:33','2026-11-15 01:37:33'),(10,'payment_sslcommerz_enabled','1','payment','2026-11-15 01:37:33','2026-11-15 01:37:33'),(11,'payment_stripe_enabled','1','payment','2026-11-15 01:37:33','2026-11-15 01:37:33'),(12,'payment_paypal_enabled','1','payment','2026-11-15 01:37:33','2026-11-15 01:37:33'),(13,'payment_cod_enabled','1','payment','2026-11-15 01:37:33','2026-11-15 01:37:33'),(14,'manual_bkash_enabled','1','general','2026-11-15 23:50:17','2026-11-15 23:50:17'),(15,'manual_bkash_number','01782989118','general','2026-11-15 23:50:17','2026-11-16 00:22:02'),(16,'manual_bkash_type','Personal (Send Money)','general','2026-11-15 23:50:17','2026-11-15 23:50:17'),(17,'manual_bkash_instructions','বিকাশ অ্যাপ থেকে উপরোক্ত নাম্বারে \"Send Money\" করুন। রেফারেন্সের ঘরে আপনার অর্ডার নাম্বারটি লিখুন।','general','2026-11-15 23:50:17','2026-11-15 23:50:17'),(18,'manual_nagad_enabled','1','general','2026-11-15 23:50:17','2026-11-15 23:50:17'),(19,'manual_nagad_number','01782989118','general','2026-11-15 23:50:17','2026-11-16 00:22:02'),(20,'manual_nagad_type','Personal (Send Money)','general','2026-11-15 23:50:17','2026-11-15 23:50:17'),(21,'manual_nagad_instructions','নগদ অ্যাপ থেকে \"Send Money\" করুন এবং রেফারেন্সে আপনার অর্ডার নাম্বারটি লিখুন।','general','2026-11-15 23:50:17','2026-11-15 23:50:17'),(22,'manual_brac_enabled','1','general','2026-11-15 23:50:17','2026-11-15 23:50:17'),(23,'manual_brac_account_name','MD. ABDUR RAHIM SHAKIL','general','2026-11-15 23:50:17','2026-11-16 00:22:02'),(24,'manual_brac_account_number','1080069810001','general','2026-11-15 23:50:17','2026-11-16 00:22:02'),(25,'manual_brac_branch','BRAC Bank PLC','general','2026-11-15 23:50:17','2026-11-16 00:22:02'),(26,'manual_brac_routing_number','060671187','general','2026-11-15 23:50:17','2026-11-16 00:22:02'),(27,'manual_brac_instructions','ব্র্যাক ব্যাংক (Astha App / ব্রাঞ্চ ডিপোজিট) করে ডিপোজিট স্লিপ বা ট্রানজেকশন রেফারেন্স প্রদান করুন।','general','2026-11-15 23:50:17','2026-11-15 23:50:17'),(28,'manual_dbbl_enabled','1','general','2026-11-15 23:50:17','2026-11-15 23:50:17'),(29,'manual_dbbl_account_name','MD. ABDUR RAHIM SHAKIL','general','2026-11-15 23:50:17','2026-11-16 00:22:02'),(30,'manual_dbbl_account_number','2581580040055','general','2026-11-15 23:50:17','2026-11-16 00:22:02'),(31,'manual_dbbl_branch','Mohammadpur Branch','general','2026-11-15 23:50:17','2026-11-16 00:22:02'),(32,'manual_dbbl_routing_number','090263286','general','2026-11-15 23:50:17','2026-11-16 00:22:02'),(33,'manual_dbbl_instructions','ডাচ-বাংলা ব্যাংক (NexusPay App / রকেট / ব্যাংক ট্রান্সফার) করে ট্রানজেকশন রেফারেন্স প্রদান করুন।','general','2026-11-15 23:50:17','2026-11-15 23:50:17'),(34,'cod_advance_charge_dhaka','80','general','2026-11-15 23:50:17','2026-11-15 23:50:17'),(35,'cod_advance_charge_outside','150','general','2026-11-15 23:50:17','2026-11-15 23:50:17'),(36,'cod_instructions','ফেক অর্ডার ও পার্সেল রিটার্ন রোধে শুধুমাত্র কুরিয়ার চার্জ অগ্রিম বিকাশ, নগদ বা ব্যাংকে পরিশোধ করে TrxID সাবমিট করুন। অবশিষ্ট মূল্য পণ্য হাতে পাওয়ার পর প্রদান করবেন।','general','2026-11-15 23:50:17','2026-11-15 23:50:17');
/*!40000 ALTER TABLE `admin_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `analytics_events`
--

DROP TABLE IF EXISTS `analytics_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `analytics_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_category` varchar(255) NOT NULL DEFAULT 'commerce',
  `event_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`event_properties`)),
  `url` varchar(255) DEFAULT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `analytics_events_user_id_foreign` (`user_id`),
  KEY `analytics_events_session_id_index` (`session_id`),
  KEY `analytics_events_event_name_index` (`event_name`),
  KEY `analytics_events_event_category_index` (`event_category`),
  KEY `analytics_events_created_at_index` (`created_at`),
  CONSTRAINT `analytics_events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `analytics_events`
--

LOCK TABLES `analytics_events` WRITE;
/*!40000 ALTER TABLE `analytics_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `analytics_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(255) NOT NULL,
  `bn_text` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `bg_color` varchar(255) NOT NULL DEFAULT '#214E34',
  `text_color` varchar(255) NOT NULL DEFAULT '#F8F6F2',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `start_at` timestamp NULL DEFAULT NULL,
  `end_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcements_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
INSERT INTO `announcements` VALUES (1,'Complimentary White-Glove Insured Delivery to USA, UK, Canada & Bangladesh on Orders Above ৳6,000','৳৬,০০০ এর অধিক অর্ডারে বাংলাদেশ, যুক্তরাষ্ট্র, যুক্তরাজ্য ও কানাডায় বিনামূল্যে ইনশিওরড হোম ডেলিভারি','/shop','#214E34','#F8F6F2',1,'2026-11-14 01:37:34','2027-05-15 01:37:34','2026-11-15 01:37:34','2026-11-15 01:37:34');
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `artisans`
--

DROP TABLE IF EXISTS `artisans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `artisans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `district_id` bigint(20) unsigned NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `full_name_bn` varchar(255) DEFAULT NULL,
  `atelier_name` varchar(255) DEFAULT NULL,
  `atelier_name_bn` varchar(255) DEFAULT NULL,
  `craft_type` varchar(255) NOT NULL,
  `years_of_mastery` int(10) unsigned NOT NULL DEFAULT 1,
  `biography` text DEFAULT NULL,
  `biography_bn` text DEFAULT NULL,
  `quote` text DEFAULT NULL,
  `quote_bn` text DEFAULT NULL,
  `portrait_url` varchar(255) DEFAULT NULL,
  `awards` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`awards`)),
  `is_verified` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `artisans_uuid_unique` (`uuid`),
  KEY `artisans_district_id_foreign` (`district_id`),
  KEY `artisans_full_name_index` (`full_name`),
  KEY `artisans_craft_type_index` (`craft_type`),
  CONSTRAINT `artisans_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `artisans`
--

LOCK TABLES `artisans` WRITE;
/*!40000 ALTER TABLE `artisans` DISABLE KEYS */;
INSERT INTO `artisans` VALUES (1,'6e791f9d-8d07-4002-9f14-fa78a6037b6b',1,'Master Weaver Alimuddin','মাস্টার কাজী ফারহান','Shitalakshya Muslin Guild','সোনারগাঁ হেরিটেজ লুম গিল্ড','muslin_handspun',42,'Born into the fifth generation of Dhakai weavers, Alimuddin dedicated four decades to resurrecting the lost technique of Phuti Karpas hand-spinning.','শীতলক্ষ্যা নদীর তীরে চার পুরুষ ধরে ঐতিহ্যবাহী ঢাকাই মসলিন ও জামদানির বুনন কৌশলকে বাঁচিয়ে রেখেছেন তিনি।','When dawn mist rises off the Shitalakshya, the cotton thread becomes as supple as river water.','প্রতিটি সুতোর ফাঁকে আমরা শুধু কাপড় বুনি না, বাংলার গৌরবময় ইতিহাসকে ধরে রাখি।','https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=1000&auto=format&fit=crop','[\"National Crafts Council Master Award 2021\",\"UNESCO Heritage Living Legend Recognition\"]',1,'2026-11-15 01:37:31','2026-11-15 03:27:22',NULL),(2,'d1f3405b-d320-4bde-bfec-57257b19ec14',2,'Rabeya Begum',NULL,'Louhajang Handloom Atelier',NULL,'jamdani_weaving',38,'Rabeya works purely from memory without paper stencils, counting each motif thread in rhythmic cadence.',NULL,'A true Jamdani has no backside; both surfaces are woven identically with the patience of prayer.',NULL,'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=1000&auto=format&fit=crop','[\"Shilpacharya Lifetime Merit Award\"]',1,'2026-11-15 01:37:31','2026-11-15 01:37:31',NULL),(3,'726f3f29-e1d0-494b-8e5a-2292831b5774',3,'Gourango Karmakar',NULL,'Karatoya Bell-Metal Foundry',NULL,'brass_bell_metal',46,'Gourango hand-hammers molten bell metal using coal embers and hand bellows, creating acoustic resonance found nowhere else.',NULL,'Strike the bell metal true, and it rings with the sound of pure silver.',NULL,'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=1000&auto=format&fit=crop','[\"National Folk Art Council Gold Medal\"]',1,'2026-11-15 01:37:31','2026-11-15 01:37:31',NULL),(4,'3bfa4691-0585-4798-a705-c0557659666c',4,'Mofizul Islam',NULL,'Nisbetganj Royal Shatranji Guild',NULL,'shatranji_flatweave',32,'Leading 24 master weavers in Nisbetganj, Mofizul uses handspun jute and indigenous vegetable indigo dyes.',NULL,'The geometric diamonds in Shatranji represent the rising sun across the Teesta plains.',NULL,'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=1000&auto=format&fit=crop','[\"President Award for Traditional Handicrafts\"]',1,'2026-11-15 01:37:31','2026-11-15 01:37:31',NULL);
/*!40000 ALTER TABLE `artisans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attribute_values`
--

DROP TABLE IF EXISTS `attribute_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attribute_values` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_id` bigint(20) unsigned NOT NULL,
  `label` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `color_hex` varchar(7) DEFAULT NULL,
  `swatch_image_url` varchar(255) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_values_attribute_id_sort_order_index` (`attribute_id`,`sort_order`),
  CONSTRAINT `attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attribute_values`
--

LOCK TABLES `attribute_values` WRITE;
/*!40000 ALTER TABLE `attribute_values` DISABLE KEYS */;
INSERT INTO `attribute_values` VALUES (1,1,'Bengal Neel / Indigo','neel-indigo','#1F3A52',NULL,1,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(2,1,'Rupganj Terracotta Red','terracotta-red','#C96A3D',NULL,2,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(3,1,'Unbleached Muslin Ivory','raw-ivory','#F8F6F2',NULL,3,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(4,2,'84 Count Heirloom Weave','84-count',NULL,NULL,1,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(5,2,'100 Count Imperial Royal Weave','100-count-royal',NULL,NULL,2,'2026-11-15 01:37:33','2026-11-15 01:37:33');
/*!40000 ALTER TABLE `attribute_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attributes`
--

DROP TABLE IF EXISTS `attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attributes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'select',
  `is_filterable` tinyint(1) NOT NULL DEFAULT 1,
  `is_searchable` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attributes_uuid_unique` (`uuid`),
  UNIQUE KEY `attributes_name_unique` (`name`),
  UNIQUE KEY `attributes_slug_unique` (`slug`),
  KEY `attributes_is_filterable_index` (`is_filterable`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attributes`
--

LOCK TABLES `attributes` WRITE;
/*!40000 ALTER TABLE `attributes` DISABLE KEYS */;
INSERT INTO `attributes` VALUES (1,'9c19f019-4af5-4166-83d3-8d2225cfe2f9','Heritage Color Palettes','color','color',1,1,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(2,'3f899594-9b37-4054-816a-8c4fdeeeda4b','Weave Fineness & Thread Count','thread-count','select',1,1,'2026-11-15 01:37:33','2026-11-15 01:37:33');
/*!40000 ALTER TABLE `attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `builder_global_components`
--

DROP TABLE IF EXISTS `builder_global_components`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `builder_global_components` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'general',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`content`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `builder_global_components_uuid_unique` (`uuid`),
  KEY `builder_global_components_name_index` (`name`),
  KEY `builder_global_components_category_index` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `builder_global_components`
--

LOCK TABLES `builder_global_components` WRITE;
/*!40000 ALTER TABLE `builder_global_components` DISABLE KEYS */;
INSERT INTO `builder_global_components` VALUES (1,'fe67cdbe-6d6a-4743-9d1f-ea123aa4aeea','Artisan Provenance Seal','story','{\"type\":\"provenance_seal\",\"title\":\"Authenticated by National Crafts Council\",\"verified_badge\":true,\"signature_line\":\"Handcrafted on heritage pit looms in Narayanganj basin.\"}','2026-11-15 01:37:34','2026-11-15 01:37:34');
/*!40000 ALTER TABLE `builder_global_components` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('sorolata-commerce-platform-cache-5c785c036466adea360111aa28563bfd556b5fba','i:1;',1794742532),('sorolata-commerce-platform-cache-5c785c036466adea360111aa28563bfd556b5fba:timer','i:1794742532;',1794742532),('sorolata-commerce-platform-cache-sorolata_admin_settings_all','a:36:{s:10:\"store_name\";s:23:\"Sorolata Commerce House\";s:13:\"support_email\";s:20:\"curator@sorolata.com\";s:13:\"support_phone\";s:17:\"+880 1700 000 000\";s:8:\"currency\";s:3:\"BDT\";s:23:\"shipping_free_threshold\";i:5000;s:20:\"shipping_default_fee\";i:80;s:21:\"payment_bkash_enabled\";i:1;s:21:\"payment_nagad_enabled\";i:1;s:22:\"payment_rocket_enabled\";i:1;s:26:\"payment_sslcommerz_enabled\";i:1;s:22:\"payment_stripe_enabled\";i:1;s:22:\"payment_paypal_enabled\";i:1;s:19:\"payment_cod_enabled\";i:1;s:20:\"manual_bkash_enabled\";i:1;s:19:\"manual_bkash_number\";s:11:\"01782989118\";s:17:\"manual_bkash_type\";s:21:\"Personal (Send Money)\";s:25:\"manual_bkash_instructions\";s:246:\"বিকাশ অ্যাপ থেকে উপরোক্ত নাম্বারে \"Send Money\" করুন। রেফারেন্সের ঘরে আপনার অর্ডার নাম্বারটি লিখুন।\";s:20:\"manual_nagad_enabled\";i:1;s:19:\"manual_nagad_number\";s:11:\"01782989118\";s:17:\"manual_nagad_type\";s:21:\"Personal (Send Money)\";s:25:\"manual_nagad_instructions\";s:187:\"নগদ অ্যাপ থেকে \"Send Money\" করুন এবং রেফারেন্সে আপনার অর্ডার নাম্বারটি লিখুন।\";s:19:\"manual_brac_enabled\";i:1;s:24:\"manual_brac_account_name\";s:22:\"MD. ABDUR RAHIM SHAKIL\";s:26:\"manual_brac_account_number\";i:1080069810001;s:18:\"manual_brac_branch\";s:13:\"BRAC Bank PLC\";s:26:\"manual_brac_routing_number\";s:9:\"060671187\";s:24:\"manual_brac_instructions\";s:247:\"ব্র্যাক ব্যাংক (Astha App / ব্রাঞ্চ ডিপোজিট) করে ডিপোজিট স্লিপ বা ট্রানজেকশন রেফারেন্স প্রদান করুন।\";s:19:\"manual_dbbl_enabled\";i:1;s:24:\"manual_dbbl_account_name\";s:22:\"MD. ABDUR RAHIM SHAKIL\";s:26:\"manual_dbbl_account_number\";i:2581580040055;s:18:\"manual_dbbl_branch\";s:18:\"Mohammadpur Branch\";s:26:\"manual_dbbl_routing_number\";s:9:\"090263286\";s:24:\"manual_dbbl_instructions\";s:230:\"ডাচ-বাংলা ব্যাংক (NexusPay App / রকেট / ব্যাংক ট্রান্সফার) করে ট্রানজেকশন রেফারেন্স প্রদান করুন।\";s:24:\"cod_advance_charge_dhaka\";i:80;s:26:\"cod_advance_charge_outside\";i:150;s:16:\"cod_instructions\";s:428:\"ফেক অর্ডার ও পার্সেল রিটার্ন রোধে শুধুমাত্র কুরিয়ার চার্জ অগ্রিম বিকাশ, নগদ বা ব্যাংকে পরিশোধ করে TrxID সাবমিট করুন। অবশিষ্ট মূল্য পণ্য হাতে পাওয়ার পর প্রদান করবেন।\";}',1794832373);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaigns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `banner_url` varchar(255) DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `discount_type` varchar(255) DEFAULT NULL,
  `discount_value` decimal(12,2) DEFAULT NULL,
  `terms` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `campaigns_uuid_unique` (`uuid`),
  UNIQUE KEY `campaigns_slug_unique` (`slug`),
  KEY `campaigns_title_index` (`title`),
  KEY `campaigns_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns`
--

LOCK TABLES `campaigns` WRITE;
/*!40000 ALTER TABLE `campaigns` DISABLE KEYS */;
/*!40000 ALTER TABLE `campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `variant_id` bigint(20) unsigned DEFAULT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL,
  `custom_options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_options`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_items_product_id_foreign` (`product_id`),
  KEY `cart_items_variant_id_foreign` (`variant_id`),
  KEY `cart_items_cart_id_product_id_index` (`cart_id`,`product_id`),
  CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'BDT',
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `coupon_code` varchar(255) DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carts_uuid_unique` (`uuid`),
  KEY `carts_user_id_foreign` (`user_id`),
  KEY `carts_session_id_index` (`session_id`),
  KEY `carts_expires_at_index` (`expires_at`),
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_bn` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `description_bn` text DEFAULT NULL,
  `hero_image` varchar(255) DEFAULT NULL,
  `icon_url` varchar(255) DEFAULT NULL,
  `seo_meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`seo_meta`)),
  `layout_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`layout_json`)),
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_uuid_unique` (`uuid`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  KEY `categories_name_index` (`name`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'6342b2f5-c10e-4c64-91bf-cafd051eeb0c',NULL,'heritage-handloom-textiles','Heritage Handloom Textiles',NULL,'Rare handloom, muslin, silk, and jamdani textiles woven on traditional wooden pit-looms.',NULL,NULL,NULL,NULL,NULL,1,'2026-11-15 01:37:31','2026-11-15 01:37:31',NULL),(2,'1e93a3f4-1dd2-4e62-92c7-164ddbe35b51',NULL,'bell-metal-brassware','Bell Metal & Brassware',NULL,'Hand-forged brass, bronze, and bell-metal tableware and heirlooms from ancestral ateliers.',NULL,NULL,NULL,NULL,NULL,2,'2026-11-15 01:37:31','2026-11-15 01:37:31',NULL),(3,'e642174d-1b67-4fa3-b536-873190ab8a54',NULL,'traditional-rugs-carpets','Traditional Rugs & Shatranji',NULL,'Centuries-old flatweave floor art woven with natural fibers in Rangpur.',NULL,NULL,NULL,NULL,NULL,3,'2026-11-15 01:37:31','2026-11-15 01:37:31',NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collection_product`
--

DROP TABLE IF EXISTS `collection_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collection_product` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `collection_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `collection_product_collection_id_product_id_unique` (`collection_id`,`product_id`),
  KEY `collection_product_product_id_foreign` (`product_id`),
  CONSTRAINT `collection_product_collection_id_foreign` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE,
  CONSTRAINT `collection_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collection_product`
--

LOCK TABLES `collection_product` WRITE;
/*!40000 ALTER TABLE `collection_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `collection_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collections`
--

DROP TABLE IF EXISTS `collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'manual',
  `rules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`rules`)),
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `banner_url` varchar(255) DEFAULT NULL,
  `icon_url` varchar(255) DEFAULT NULL,
  `seo_meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`seo_meta`)),
  `layout_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`layout_json`)),
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `collections_uuid_unique` (`uuid`),
  UNIQUE KEY `collections_slug_unique` (`slug`),
  KEY `collections_title_index` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collections`
--

LOCK TABLES `collections` WRITE;
/*!40000 ALTER TABLE `collections` DISABLE KEYS */;
INSERT INTO `collections` VALUES (1,'e8a11b2d-bc07-4f71-b78c-4277a4a705ac','royal-muslin-jamdani-drop','The Royal Muslin & Jamdani Drop','manual',NULL,'300-count gossamer threads spun along the misty morning banks of the Shitalakshya River.','https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1200&auto=format&fit=crop',NULL,NULL,NULL,NULL,1,'2026-11-15 01:37:31','2026-11-15 01:37:31',NULL),(2,'9f8946ea-b4e3-46b3-806f-70c38850ad2a','ancestral-bell-metal-kansa','Ancestral Bell-Metal & Kansa Tableware','manual',NULL,'Hand-chiseled bronze alloys cast with ancient Mahasthangarh methods in Bogura.','https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?q=80&w=1200&auto=format&fit=crop',NULL,NULL,NULL,NULL,1,'2026-11-15 01:37:31','2026-11-15 01:37:31',NULL);
/*!40000 ALTER TABLE `collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `craft_types`
--

DROP TABLE IF EXISTS `craft_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `craft_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `bn_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `origin_district_id` bigint(20) unsigned DEFAULT NULL,
  `is_gi_certified` tinyint(1) NOT NULL DEFAULT 0,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `craft_types_uuid_unique` (`uuid`),
  UNIQUE KEY `craft_types_slug_unique` (`slug`),
  KEY `craft_types_origin_district_id_foreign` (`origin_district_id`),
  KEY `craft_types_name_index` (`name`),
  KEY `craft_types_is_gi_certified_index` (`is_gi_certified`),
  CONSTRAINT `craft_types_origin_district_id_foreign` FOREIGN KEY (`origin_district_id`) REFERENCES `districts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `craft_types`
--

LOCK TABLES `craft_types` WRITE;
/*!40000 ALTER TABLE `craft_types` DISABLE KEYS */;
INSERT INTO `craft_types` VALUES (1,'e790eb56-e3b3-4818-880f-eccc66cf97ae','Jamdani Fine Discontinuous Weft Handloom','ঐতিহ্যবাহী জামদানি বুননশিল্প','jamdani-handloom','UNESCO Intangible Cultural Heritage of Humanity and Bangladesh GI certified muslin handloom weave.',1,1,'spark-loom','2026-11-15 01:37:33','2026-11-15 01:37:33',NULL),(2,'a26ae357-2cb0-4d7a-ab3b-17d022a60970','Rajshahi Pure Mulberry Silk (Reshom)','রাজশাহী তুত রেশম রেশমী বুনন','rajshahi-mulberry-silk','Geographical Indication certified pure mulberry sericulture weave with natural thermal balance.',NULL,1,'silk-thread','2026-11-15 01:37:33','2026-11-15 01:37:33',NULL);
/*!40000 ALTER TABLE `craft_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_notes`
--

DROP TABLE IF EXISTS `customer_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_notes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned NOT NULL,
  `staff_id` bigint(20) unsigned DEFAULT NULL,
  `note` text NOT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_notes_customer_id_foreign` (`customer_id`),
  KEY `customer_notes_staff_id_foreign` (`staff_id`),
  KEY `customer_notes_is_pinned_index` (`is_pinned`),
  CONSTRAINT `customer_notes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `customer_notes_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_notes`
--

LOCK TABLES `customer_notes` WRITE;
/*!40000 ALTER TABLE `customer_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_profiles`
--

DROP TABLE IF EXISTS `customer_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `preferred_currency` varchar(3) NOT NULL DEFAULT 'BDT',
  `preferred_locale` varchar(5) NOT NULL DEFAULT 'en',
  `loyalty_points` int(10) unsigned NOT NULL DEFAULT 0,
  `membership_tier` varchar(255) NOT NULL DEFAULT 'bronze',
  `total_spent` decimal(12,2) NOT NULL DEFAULT 0.00,
  `orders_count` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_profiles_user_id_unique` (`user_id`),
  KEY `customer_profiles_membership_tier_index` (`membership_tier`),
  CONSTRAINT `customer_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_profiles`
--

LOCK TABLES `customer_profiles` WRITE;
/*!40000 ALTER TABLE `customer_profiles` DISABLE KEYS */;
INSERT INTO `customer_profiles` VALUES (1,1,NULL,'2026-11-15 01:37:34',NULL,NULL,'BDT','en',1250,'patron',45000.00,3,'2026-11-15 01:37:34','2026-11-15 01:37:34');
/*!40000 ALTER TABLE `customer_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `design_tokens`
--

DROP TABLE IF EXISTS `design_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `design_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `token_key` varchar(255) NOT NULL,
  `token_group` varchar(255) NOT NULL DEFAULT 'theme',
  `token_value` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `design_tokens_token_key_unique` (`token_key`),
  KEY `design_tokens_token_group_index` (`token_group`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `design_tokens`
--

LOCK TABLES `design_tokens` WRITE;
/*!40000 ALTER TABLE `design_tokens` DISABLE KEYS */;
INSERT INTO `design_tokens` VALUES (1,'color_primary_forest','color','#214E34','Primary Bengal Deep Forest Green','2026-11-15 01:37:33','2026-11-15 01:37:33'),(2,'color_secondary_terracotta','color','#C96A3D','Secondary Riverbed Terracotta','2026-11-15 01:37:33','2026-11-15 01:37:33'),(3,'color_background_cream','color','#F8F6F2','Warm Cream Archival Canvas','2026-11-15 01:37:33','2026-11-15 01:37:33'),(4,'font_heading_editorial','typography','Cormorant Garamond','Editorial Serif Display Font','2026-11-15 01:37:33','2026-11-15 01:37:33'),(5,'font_body_manrope','typography','Manrope','Modern Sans Body Typography','2026-11-15 01:37:33','2026-11-15 01:37:33');
/*!40000 ALTER TABLE `design_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discounts`
--

DROP TABLE IF EXISTS `discounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'percentage',
  `value` decimal(12,2) NOT NULL,
  `min_subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `max_discount_amount` decimal(12,2) DEFAULT NULL,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `usage_limit` int(10) unsigned DEFAULT NULL,
  `used_count` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `conditions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`conditions`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `discounts_uuid_unique` (`uuid`),
  UNIQUE KEY `discounts_code_unique` (`code`),
  KEY `discounts_type_index` (`type`),
  KEY `discounts_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discounts`
--

LOCK TABLES `discounts` WRITE;
/*!40000 ALTER TABLE `discounts` DISABLE KEYS */;
INSERT INTO `discounts` VALUES (1,'a38fd0e0-9387-47f5-8aba-705e0e4ec08f','BENGAL10','First Collector Privilege (10% Off)','percentage',10.00,5000.00,2000.00,NULL,NULL,500,0,1,NULL,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(2,'3e5f08e5-d2a7-4e4f-9af4-75847286b451','HERITAGE2000','Generational Guild Reward (৳2,000 Off)','fixed_amount',2000.00,15000.00,NULL,NULL,NULL,200,0,1,NULL,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(3,'fc1f601c-a0ce-4304-be4b-8c4c423c3c8c','FREESHIP','Complimentary Shipping Voucher','free_shipping',0.00,3000.00,NULL,NULL,NULL,NULL,0,1,NULL,'2026-11-15 01:37:31','2026-11-15 01:37:31');
/*!40000 ALTER TABLE `discounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `districts`
--

DROP TABLE IF EXISTS `districts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `districts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `division_id` bigint(20) unsigned DEFAULT NULL,
  `uuid` char(36) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `name_bn` varchar(255) NOT NULL,
  `division` varchar(255) NOT NULL,
  `gi_specialty` varchar(255) DEFAULT NULL,
  `historical_narrative` text DEFAULT NULL,
  `river_basin` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `coordinates` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`coordinates`)),
  PRIMARY KEY (`id`),
  UNIQUE KEY `districts_uuid_unique` (`uuid`),
  KEY `districts_name_en_index` (`name_en`),
  KEY `districts_division_index` (`division`),
  KEY `districts_division_id_foreign` (`division_id`),
  CONSTRAINT `districts_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `districts`
--

LOCK TABLES `districts` WRITE;
/*!40000 ALTER TABLE `districts` DISABLE KEYS */;
INSERT INTO `districts` VALUES (1,NULL,'c1055f18-f240-4274-a39b-040a7b8b84f6','Narayanganj (Sonargaon)','নারায়ণগঞ্জ (সোনারগাঁ)','Dhaka Division','Dhakai Muslin & Traditional Jamdani Weaving','Sonargaon was the historic golden capital of Bengal. The Shitalakshya River provided the unique atmospheric humidity required to spin fine 300+ count muslin threads without breakage.','Shitalakshya & Meghna River Basin',23.6495000,90.5985000,NULL,'2026-11-15 01:37:31','2026-11-15 01:37:31',NULL,NULL),(2,1,'84d17470-6246-44f3-9554-c904f314feb0','Tangail','টাঙ্গাইল','Dhaka Division','Tangail Saree & Pure Silk Weaving','The Basak artisan community migrated to the fertile banks of the Louhajang River during the British period, perfecting fine jacquard and hand-knotted borders.','Jamuna & Louhajang River Basin',24.2513000,89.9167000,NULL,'2026-11-15 01:37:31','2026-11-15 01:37:33',NULL,NULL),(3,NULL,'1e922b0f-a34e-4a1e-89ed-f03e6f469ece','Bogura','বগুড়া','Rajshahi Division','Hand-Chiseled Brass & Bell-Metal Craft','Dating back to the Mahasthangarh era (3rd century BC), Bogura metalworkers cast brassware using ancient lost-wax and hand-hammering techniques.','Karatoya River Basin',24.8465000,89.3777000,NULL,'2026-11-15 01:37:31','2026-11-15 01:37:31',NULL,NULL),(4,NULL,'e377e14b-0b72-4397-be81-8cd98251fe15','Rangpur (Nisbetganj)','রংপুর (নিসবেতগঞ্জ)','Rangpur Division','Shatranji Flatweave Tapestries & Carpets','The Mughals commissioned Shatranji carpets for imperial courts. In 1830, British collector Mr. Nisbet revitalized the weaver village now honored as Nisbetganj.','Ghaghat & Teesta River Basin',25.7439000,89.2752000,NULL,'2026-11-15 01:37:31','2026-11-15 01:37:31',NULL,NULL);
/*!40000 ALTER TABLE `districts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `divisions`
--

DROP TABLE IF EXISTS `divisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `divisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `bn_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `coordinates` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`coordinates`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `divisions_name_unique` (`name`),
  UNIQUE KEY `divisions_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `divisions`
--

LOCK TABLES `divisions` WRITE;
/*!40000 ALTER TABLE `divisions` DISABLE KEYS */;
INSERT INTO `divisions` VALUES (1,'Dhaka','ঢাকা','dhaka','{\"lat\":23.8103,\"lng\":90.4125}','2026-11-15 01:37:33','2026-11-15 01:37:33'),(2,'Chittagong','চট্টগ্রাম','chittagong','{\"lat\":22.3569,\"lng\":91.7832}','2026-11-15 01:37:33','2026-11-15 01:37:33'),(3,'Rajshahi','রাজশাহী','rajshahi','{\"lat\":24.3745,\"lng\":88.6042}','2026-11-15 01:37:33','2026-11-15 01:37:33'),(4,'Khulna','খুলনা','khulna','{\"lat\":22.8456,\"lng\":89.5403}','2026-11-15 01:37:33','2026-11-15 01:37:33'),(5,'Sylhet','সিলেট','sylhet','{\"lat\":24.8949,\"lng\":91.8687}','2026-11-15 01:37:33','2026-11-15 01:37:33'),(6,'Barisal','বরিশাল','barisal','{\"lat\":22.701,\"lng\":90.3535}','2026-11-15 01:37:33','2026-11-15 01:37:33'),(7,'Rangpur','রংপুর','rangpur','{\"lat\":25.7439,\"lng\":89.2752}','2026-11-15 01:37:33','2026-11-15 01:37:33'),(8,'Mymensingh','ময়মনসিংহ','mymensingh','{\"lat\":24.7471,\"lng\":90.4203}','2026-11-15 01:37:33','2026-11-15 01:37:33');
/*!40000 ALTER TABLE `divisions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_templates`
--

DROP TABLE IF EXISTS `email_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body_html` longtext NOT NULL,
  `variables` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variables`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_templates_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_templates`
--

LOCK TABLES `email_templates` WRITE;
/*!40000 ALTER TABLE `email_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_logs`
--

DROP TABLE IF EXISTS `inventory_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `product_variant_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `quantity_delta` int(11) NOT NULL,
  `previous_quantity` int(11) NOT NULL,
  `new_quantity` int(11) NOT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) unsigned DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_logs_uuid_unique` (`uuid`),
  KEY `inventory_logs_product_id_foreign` (`product_id`),
  KEY `inventory_logs_product_variant_id_foreign` (`product_variant_id`),
  KEY `inventory_logs_reference_type_reference_id_index` (`reference_type`,`reference_id`),
  KEY `inventory_logs_user_id_foreign` (`user_id`),
  KEY `inventory_logs_type_index` (`type`),
  CONSTRAINT `inventory_logs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_logs_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_logs`
--

LOCK TABLES `inventory_logs` WRITE;
/*!40000 ALTER TABLE `inventory_logs` DISABLE KEYS */;
INSERT INTO `inventory_logs` VALUES (1,'cbd7efa4-0f0a-466c-980b-ed1bb91fc39f',1,1,'purchase',8,0,8,NULL,NULL,'Initial Guild Batch Intake',NULL,'2026-11-15 01:37:31','2026-11-15 01:37:31');
/*!40000 ALTER TABLE `inventory_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marketing_popups`
--

DROP TABLE IF EXISTS `marketing_popups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marketing_popups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `trigger_type` varchar(255) NOT NULL DEFAULT 'delay',
  `delay_seconds` smallint(5) unsigned NOT NULL DEFAULT 5,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`content`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `marketing_popups_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marketing_popups`
--

LOCK TABLES `marketing_popups` WRITE;
/*!40000 ALTER TABLE `marketing_popups` DISABLE KEYS */;
/*!40000 ALTER TABLE `marketing_popups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `bn_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `is_sustainable` tinyint(1) NOT NULL DEFAULT 1,
  `certification` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `materials_uuid_unique` (`uuid`),
  UNIQUE KEY `materials_slug_unique` (`slug`),
  KEY `materials_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materials`
--

LOCK TABLES `materials` WRITE;
/*!40000 ALTER TABLE `materials` DISABLE KEYS */;
INSERT INTO `materials` VALUES (1,'d75ba669-a2ca-44ae-8eae-6baa123821d3','Indigenous Comilla Hill Organic Cotton','দেশি পাহাড়ি অর্গানিক কার্পাস তুলা','deshi-organic-cotton','Chittagong Hill Tracts & Comilla Basin',1,'GOTS Organic Certified Grade-A','2026-11-15 01:37:33','2026-11-15 01:37:33'),(2,'a6564c88-298f-4c92-a3ab-26b36d3d4b3c','Padma Basin Mulberry Silk Filament','পদ্মা অববাহিকার তুঁত রেশম আঁশ','mulberry-silk-filament','Bholahat, Chapai Nawabganj',1,'Bangladesh Silk Development Board Certified','2026-11-15 01:37:33','2026-11-15 01:37:33');
/*!40000 ALTER TABLE `materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_assets`
--

DROP TABLE IF EXISTS `media_assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_assets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `attachable_type` varchar(255) NOT NULL,
  `attachable_id` bigint(20) unsigned NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `disk` varchar(255) NOT NULL DEFAULT 'public',
  `mime_type` varchar(255) NOT NULL,
  `file_size` bigint(20) unsigned NOT NULL,
  `width` int(10) unsigned DEFAULT NULL,
  `height` int(10) unsigned DEFAULT NULL,
  `aspect_ratio` varchar(10) NOT NULL DEFAULT '1:1',
  `blur_placeholder` text DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `variants` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variants`)),
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_assets_uuid_unique` (`uuid`),
  KEY `media_assets_attachable_type_attachable_id_index` (`attachable_type`,`attachable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_assets`
--

LOCK TABLES `media_assets` WRITE;
/*!40000 ALTER TABLE `media_assets` DISABLE KEYS */;
INSERT INTO `media_assets` VALUES (1,'a222b1a4-c6d5-495d-8e5f-d6e92497ec5c','App\\Models\\Product',1,'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1600&auto=format&fit=crop','public','image/jpeg',420000,1600,1100,'16/11',NULL,'The Imperial Sonargaon Muslin Scarf on Natural Linen',NULL,1,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(2,'876da422-ec0d-4764-a175-5d007e994370','App\\Models\\Product',2,'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1600&auto=format&fit=crop','public','image/jpeg',380000,1600,1200,'4/3',NULL,'Heirloom Tangail Mulberry Silk Jamdani Drape',NULL,1,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(3,'ade1c8ea-1521-4e69-817a-46ecb526de4d','App\\Models\\Product',3,'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?q=80&w=1600&auto=format&fit=crop','public','image/jpeg',410000,1600,1100,'16/11',NULL,'Hand-Chiseled Royal Bogura Brass Kettle',NULL,1,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(4,'797569cb-d0e7-4a87-bbac-1eb0cf21b031','App\\Models\\Product',4,'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1600&auto=format&fit=crop','public','image/jpeg',490000,1600,1200,'4/3',NULL,'Rangpur Royal Shatranji Flatweave Carpet in Minimal Living Room',NULL,1,'2026-11-15 01:37:31','2026-11-15 01:37:31');
/*!40000 ALTER TABLE `media_assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_attachments`
--

DROP TABLE IF EXISTS `media_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_attachments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `media_asset_id` bigint(20) unsigned NOT NULL,
  `mediable_type` varchar(255) NOT NULL,
  `mediable_id` bigint(20) unsigned NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'gallery',
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_attachments_media_asset_id_foreign` (`media_asset_id`),
  KEY `media_attachments_mediable_type_mediable_id_index` (`mediable_type`,`mediable_id`),
  KEY `media_attachments_mediable_type_mediable_id_role_index` (`mediable_type`,`mediable_id`,`role`),
  KEY `media_attachments_role_index` (`role`),
  CONSTRAINT `media_attachments_media_asset_id_foreign` FOREIGN KEY (`media_asset_id`) REFERENCES `media_assets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_attachments`
--

LOCK TABLES `media_attachments` WRITE;
/*!40000 ALTER TABLE `media_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `bn_title` varchar(255) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `target` varchar(10) NOT NULL DEFAULT '_self',
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_items_parent_id_foreign` (`parent_id`),
  KEY `menu_items_menu_id_parent_id_sort_order_index` (`menu_id`,`parent_id`,`sort_order`),
  CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menu_items_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (1,1,NULL,'Curated Atelier','হেরিটেজ শপ','/shop',NULL,'_self',1,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(2,1,NULL,'Master Artisans','কারিগর গিল্ড','/artisans',NULL,'_self',2,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(3,1,NULL,'River Basins & Districts','৬৪ জেলা ঐতিহ্য','/districts',NULL,'_self',3,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(4,1,NULL,'Heritage Gazette','গ্যাজেট স্টোরিজ','/stories',NULL,'_self',4,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(5,1,NULL,'Collections','কিউরেটেড কালেকশন','/collections',NULL,'_self',5,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(6,1,NULL,'About Sorolata','আমাদের দর্শন','/about',NULL,'_self',6,'2026-11-15 01:37:33','2026-11-15 01:37:33');
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menus_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,'header_main','Main Editorial Header Navigation','header','2026-11-15 01:37:33','2026-11-15 01:37:33');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_03_14_000001_create_districts_table',1),(5,'2026_03_14_000002_create_artisans_table',1),(6,'2026_03_14_000003_create_categories_and_collections_tables',1),(7,'2026_03_14_000004_create_products_and_layouts_tables',1),(8,'2026_03_14_000005_create_media_and_orders_tables',1),(9,'2026_03_14_000006_create_stories_and_reviews_tables',1),(10,'2026_03_14_000007_create_pages_and_builder_tables',1),(11,'2026_03_14_000008_create_commerce_engine_tables',1),(12,'2026_03_14_000009_create_admin_and_cms_tables',1),(13,'2026_03_14_000010_create_normalized_enterprise_schema_tables',1),(14,'2026_11_12_053641_create_personal_access_tokens_table',1),(15,'2026_11_15_000001_create_bilingual_and_story_commerce_tables',2),(16,'2026_11_16_041739_add_advance_payment_and_due_to_orders_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `product_variant_id` bigint(20) unsigned DEFAULT NULL,
  `artisan_id` bigint(20) unsigned DEFAULT NULL,
  `product_title` varchar(255) NOT NULL,
  `variant_title` varchar(255) DEFAULT NULL,
  `artisan_name` varchar(255) DEFAULT NULL,
  `sku` varchar(255) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `subtotal` decimal(12,2) NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_artisan_id_foreign` (`artisan_id`),
  KEY `order_items_product_variant_id_foreign` (`product_variant_id`),
  CONSTRAINT `order_items_artisan_id_foreign` FOREIGN KEY (`artisan_id`) REFERENCES `artisans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,1,NULL,NULL,'The Imperial Sonargaon Muslin Scarf',NULL,NULL,'SRL-MSL-001',18500.00,1,18500.00,NULL,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(2,2,4,NULL,4,'Rangpur Royal Shatranji Flatweave Carpet',NULL,'Mofizul Islam','SRL-SHT-004',15600.00,1,15600.00,NULL,'2026-11-15 02:12:05','2026-11-15 02:12:05'),(3,3,1,NULL,1,'The Imperial Sonargaon Muslin Scarf',NULL,'Master Weaver Alimuddin','SRL-MSL-001',18500.00,1,18500.00,NULL,'2026-11-15 22:51:34','2026-11-15 22:51:34'),(4,4,4,NULL,4,'Rangpur Royal Shatranji Flatweave Carpet',NULL,'Mofizul Islam','SRL-SHT-004',15600.00,1,15600.00,NULL,'2026-11-15 22:54:29','2026-11-15 22:54:29'),(5,5,2,NULL,2,'Heirloom Tangail Mulberry Silk Jamdani',NULL,'Rabeya Begum','SRL-JMD-002',24500.00,1,24500.00,NULL,'2026-11-16 00:23:05','2026-11-16 00:23:05'),(6,6,1,NULL,1,'The Imperial Sonargaon Muslin Scarf',NULL,'Master Weaver Alimuddin','SRL-MSL-001',18500.00,1,18500.00,NULL,'2026-11-16 00:23:51','2026-11-16 00:23:51'),(7,7,1,NULL,1,'The Imperial Sonargaon Muslin Scarf',NULL,'Master Weaver Alimuddin','SRL-MSL-001',18500.00,1,18500.00,NULL,'2026-11-16 00:29:57','2026-11-16 00:29:57');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_timelines`
--

DROP TABLE IF EXISTS `order_timelines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_timelines` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `status` varchar(255) NOT NULL,
  `actor_type` varchar(255) NOT NULL DEFAULT 'system',
  `actor_id` bigint(20) unsigned DEFAULT NULL,
  `note` text DEFAULT NULL,
  `customer_notified` tinyint(1) NOT NULL DEFAULT 0,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_timelines_order_id_foreign` (`order_id`),
  CONSTRAINT `order_timelines_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_timelines`
--

LOCK TABLES `order_timelines` WRITE;
/*!40000 ALTER TABLE `order_timelines` DISABLE KEYS */;
INSERT INTO `order_timelines` VALUES (1,1,'confirmed','curator',NULL,'Order verified by master curator. Ready for insured dispatch.',1,NULL,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(2,2,'confirmed','customer',NULL,'Order placed through storefront one-page checkout.',1,NULL,'2026-11-15 02:12:05','2026-11-15 02:12:05'),(3,3,'pending','customer',NULL,'Order placed with Cash on Delivery. Advance delivery charge of ৳150 required to confirm parcel dispatch.',1,NULL,'2026-11-15 22:51:34','2026-11-15 22:51:34'),(4,3,'confirmed','payment_gateway',NULL,'Advance delivery fee of ৳150 paid via bKash (TrxID: TRX4927253306). Parcel queued for packaging. Remaining ৳18500 due on delivery.',1,NULL,'2026-11-15 22:51:47','2026-11-15 22:51:47'),(5,4,'pending','customer',NULL,'Order placed through storefront one-page checkout.',1,NULL,'2026-11-15 22:54:29','2026-11-15 22:54:29'),(6,4,'confirmed','payment_gateway',NULL,'Full order payment of ৳15750 cleared via bKash (TrxID: TRX1695201841).',1,NULL,'2026-11-15 22:54:35','2026-11-15 22:54:35'),(7,5,'pending','customer',NULL,'Order placed through storefront one-page checkout.',1,NULL,'2026-11-16 00:23:05','2026-11-16 00:23:05'),(8,5,'pending','customer',NULL,'Customer submitted payment details via bKash Mobile Banking. Sender: 01711000000, TrxID: 4sfsf. Awaiting verification by accounts team.',1,NULL,'2026-11-16 00:23:28','2026-11-16 00:23:28'),(9,6,'pending','customer',NULL,'Order placed through storefront one-page checkout.',1,NULL,'2026-11-16 00:23:51','2026-11-16 00:23:51'),(10,6,'pending','customer',NULL,'Customer submitted payment details via Brac. Sender: 01711000000, TrxID: 4sfsf. Awaiting verification by accounts team.',1,NULL,'2026-11-16 00:24:18','2026-11-16 00:24:18'),(11,7,'pending','customer',NULL,'Order placed with Cash on Delivery. Advance delivery charge of ৳80 required to confirm parcel dispatch.',1,NULL,'2026-11-16 00:29:57','2026-11-16 00:29:57'),(12,7,'pending','customer',NULL,'Customer submitted payment details via bKash Mobile Banking. Sender: 01711000000, TrxID: ef43fs. Awaiting verification by accounts team.',1,NULL,'2026-11-16 00:34:22','2026-11-16 00:34:22'),(13,7,'confirmed','staff',NULL,'Advance delivery fee of ৳80 verified by Curator [Momo] (TrxID: ef43fs). Order confirmed! Remaining ৳18500 due on parcel delivery.',1,NULL,'2026-11-16 00:35:52','2026-11-16 00:35:52');
/*!40000 ALTER TABLE `order_timelines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `advance_delivery_charge` decimal(12,2) NOT NULL DEFAULT 0.00,
  `subtotal_amount` decimal(12,2) NOT NULL,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'BDT',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `payment_gateway` varchar(255) NOT NULL DEFAULT 'bkash',
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payment_details`)),
  `shipping_rate_id` bigint(20) unsigned DEFAULT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `courier_name` varchar(255) DEFAULT NULL,
  `shipping_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`shipping_address`)),
  `billing_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`billing_address`)),
  `notes` text DEFAULT NULL,
  `is_gift` tinyint(1) NOT NULL DEFAULT 0,
  `gift_wrap` tinyint(1) NOT NULL DEFAULT 0,
  `gift_wrap_fee` decimal(12,2) NOT NULL DEFAULT 0.00,
  `gift_recipient_name` varchar(255) DEFAULT NULL,
  `gift_message` text DEFAULT NULL,
  `gift_receipt` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_uuid_unique` (`uuid`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_status_index` (`status`),
  KEY `orders_payment_status_index` (`payment_status`),
  KEY `orders_shipping_rate_id_foreign` (`shipping_rate_id`),
  CONSTRAINT `orders_shipping_rate_id_foreign` FOREIGN KEY (`shipping_rate_id`) REFERENCES `shipping_rates` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ef470371-c6b8-455a-a354-7e52075bf0be','SRL-BENGAL-01',1,'Prof. Anisuzzaman Al-Hadi','collector@bengalarchive.org','+8801711223344',18500.00,0.00,0.00,0.00,18500.00,0.00,0.00,0.00,'BDT','confirmed','paid','sslcommerz',NULL,NULL,NULL,NULL,NULL,'{\"line_1\":\"Road 79, Gulshan-2\",\"city\":\"Dhaka\",\"country\":\"Bangladesh\"}',NULL,NULL,0,0,0.00,NULL,NULL,0,'2026-11-15 01:37:33','2026-11-15 01:37:33',NULL),(2,'d9cfe9fe-4070-461b-88f5-a5c6d77eae3c','SRL-WCOIDSHP',NULL,'Dr. Eleanor Vance','collector@example.com','+880 1711 000000',15600.00,0.00,0.00,0.00,15600.00,0.00,0.00,0.00,'BDT','confirmed','paid','bkash',NULL,NULL,NULL,NULL,NULL,'{\"address\":\"House 42, Road 11, Gulshan-2\",\"district\":\"Dhaka\",\"gift_note\":null}',NULL,NULL,0,0,0.00,NULL,NULL,0,'2026-11-15 02:12:05','2026-11-15 02:12:05',NULL),(3,'6845e901-f6f0-4394-aaa2-283bea788eca','SRL-UJF5D39E',NULL,'আহমেদ সাদমান','collector@example.com','01711000000',18650.00,150.00,18500.00,150.00,18500.00,0.00,150.00,0.00,'BDT','confirmed','partially_paid','cash_on_delivery','TRX4927253306','{\"gateway\":\"bkash\",\"trx_id\":\"TRX4927253306\",\"paid_amount\":150,\"paid_at\":\"2026-11-16T04:51:47+00:00\",\"type\":\"advance_delivery_charge\",\"raw\":{\"_token\":\"LxUqDPikHfhfGg8XV8BJqLUUpTB9spvDysV1Bem6\",\"paymentID\":\"BKS_SIM_6AFA8BD69A90D\",\"trxID\":\"TRX4927253306\",\"amount\":\"150\",\"status\":\"success\",\"customer_phone\":\"01711000000\"}}',NULL,NULL,NULL,'{\"address\":\"\\u09ac\\u09be\\u09dc\\u09bf #\\u09e7\\u09e8, \\u09b0\\u09cb\\u09a1 #\\u09eb, \\u09a7\\u09be\\u09a8\\u09ae\\u09a8\\u09cd\\u09a1\\u09bf\",\"district\":\"Chittagong\",\"is_dhaka\":false,\"gift_note\":null}',NULL,NULL,0,0,0.00,NULL,NULL,0,'2026-11-15 22:51:34','2026-11-15 22:51:47',NULL),(4,'673bbafe-f069-4e57-80ed-3d69f94ccbbd','SRL-OOLUAHQW',NULL,'test','collector@example.com','01711000000',15750.00,15750.00,0.00,0.00,15600.00,0.00,150.00,0.00,'BDT','confirmed','paid','bkash','TRX1695201841','{\"gateway\":\"bkash\",\"trx_id\":\"TRX1695201841\",\"paid_amount\":15750,\"paid_at\":\"2026-11-16T04:54:35+00:00\",\"type\":\"full_payment\",\"raw\":{\"_token\":\"uS3cJ32sen1lb4ISougt0SLNDbaxH9uhV61io8J7\",\"paymentID\":\"BKS_SIM_6AFA8C860D914\",\"trxID\":\"TRX1695201841\",\"amount\":\"15750\",\"status\":\"success\",\"customer_phone\":\"01711000000\"}}',NULL,NULL,NULL,'{\"address\":\"\\u09ac\\u09be\\u09dc\\u09bf #\\u09e7\\u09e8, \\u09b0\\u09cb\\u09a1 #\\u09eb, \\u09a7\\u09be\\u09a8\\u09ae\\u09a8\\u09cd\\u09a1\\u09bf\",\"district\":\"Chittagong\",\"is_dhaka\":false,\"gift_note\":null}',NULL,NULL,0,0,0.00,NULL,NULL,0,'2026-11-15 22:54:29','2026-11-15 22:54:35',NULL),(5,'0cda218b-efbd-4bb9-a5d0-06dcc629d930','SRL-QUK4VAMJ',NULL,'আহমেদ সাদমান','collector@example.com','01711000000',24580.00,0.00,24580.00,0.00,24500.00,0.00,80.00,0.00,'BDT','pending','unpaid','bkash','4sfsf','{\"sender_number\":\"01711000000\",\"submitted_trx_id\":\"4sfsf\",\"method_used\":\"bkash\",\"customer_notes\":null,\"submitted_at\":\"2026-11-16T06:23:28+00:00\"}',NULL,NULL,NULL,'{\"address\":\"\\u09ac\\u09be\\u09dc\\u09bf #\\u09e7\\u09e8, \\u09b0\\u09cb\\u09a1 #\\u09eb, \\u09a7\\u09be\\u09a8\\u09ae\\u09a8\\u09cd\\u09a1\\u09bf\",\"district\":\"Dhaka\",\"is_dhaka\":true,\"gift_note\":null}',NULL,NULL,0,0,0.00,NULL,NULL,0,'2026-11-16 00:23:05','2026-11-16 00:23:28',NULL),(6,'9f20d443-c9e9-4792-8024-265ec712e983','SRL-HNVC3XGJ',NULL,'আহমেদ সাদমান','collector@example.com','01711000000',18580.00,0.00,18580.00,0.00,18500.00,0.00,80.00,0.00,'BDT','pending','unpaid','bkash','4sfsf','{\"sender_number\":\"01711000000\",\"submitted_trx_id\":\"4sfsf\",\"method_used\":\"brac\",\"customer_notes\":null,\"submitted_at\":\"2026-11-16T06:24:18+00:00\"}',NULL,NULL,NULL,'{\"address\":\"\\u09ac\\u09be\\u09dc\\u09bf #\\u09e7\\u09e8, \\u09b0\\u09cb\\u09a1 #\\u09eb, \\u09a7\\u09be\\u09a8\\u09ae\\u09a8\\u09cd\\u09a1\\u09bf\",\"district\":\"Dhaka\",\"is_dhaka\":true,\"gift_note\":null}',NULL,NULL,0,0,0.00,NULL,NULL,0,'2026-11-16 00:23:51','2026-11-16 00:24:18',NULL),(7,'22d76cff-ac0b-4fbb-8154-7ee5fb05f67b','SRL-QKWURKPY',NULL,'আহমেদ সাদমান','collector@example.com','01711000000',18580.00,80.00,18500.00,80.00,18500.00,0.00,80.00,0.00,'BDT','confirmed','partially_paid','cash_on_delivery','ef43fs','{\"sender_number\":\"01711000000\",\"submitted_trx_id\":\"ef43fs\",\"method_used\":\"bkash\",\"customer_notes\":null,\"submitted_at\":\"2026-11-16T06:34:22+00:00\",\"verified_by\":\"Momo\",\"verified_at\":\"2026-11-16T06:35:52+00:00\",\"verified_trx_id\":\"ef43fs\"}',NULL,NULL,NULL,'{\"address\":\"\\u09ac\\u09be\\u09dc\\u09bf #\\u09e7\\u09e8, \\u09b0\\u09cb\\u09a1 #\\u09eb, \\u09a7\\u09be\\u09a8\\u09ae\\u09a8\\u09cd\\u09a1\\u09bf\",\"district\":\"Dhaka\",\"is_dhaka\":true,\"gift_note\":null}',NULL,NULL,0,0,0.00,NULL,NULL,0,'2026-11-16 00:29:57','2026-11-16 00:35:52',NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_revisions`
--

DROP TABLE IF EXISTS `page_revisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_revisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `version` int(10) unsigned NOT NULL DEFAULT 1,
  `layout_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`layout_json`)),
  `change_summary` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `is_autosave` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_revisions_created_by_foreign` (`created_by`),
  KEY `page_revisions_page_id_version_index` (`page_id`,`version`),
  KEY `page_revisions_product_id_version_index` (`product_id`,`version`),
  CONSTRAINT `page_revisions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `page_revisions_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `page_revisions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_revisions`
--

LOCK TABLES `page_revisions` WRITE;
/*!40000 ALTER TABLE `page_revisions` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_revisions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_templates`
--

DROP TABLE IF EXISTS `page_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'landing',
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `layout_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`layout_json`)),
  `is_global` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_templates_uuid_unique` (`uuid`),
  KEY `page_templates_name_index` (`name`),
  KEY `page_templates_category_index` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_templates`
--

LOCK TABLES `page_templates` WRITE;
/*!40000 ALTER TABLE `page_templates` DISABLE KEYS */;
INSERT INTO `page_templates` VALUES (1,'5cdd0d74-efe2-4382-ac79-1aa869701107','Imperial Handloom Product Landing','product_landing','https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=600','{\"version\":1,\"page_type\":\"product\",\"sections\":[{\"id\":\"sec_hero_6afa7ae2bffa6\",\"type\":\"hero_cinematic\",\"is_active\":true,\"settings\":{\"badge_text\":\"GI Certified Heritage Artifact \\u2022 100% Handspun\",\"headline\":\"{{ product.title }}\",\"subheadline\":\"{{ product.subtitle }}\",\"primary_cta_text\":\"Acquire Piece \\u2022 {{ product.price }}\",\"primary_cta_url\":\"#purchase-box\",\"alignment\":\"center\",\"text_color\":\"#1D1D1D\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_buy_6afa7ae2bffaa\",\"type\":\"sticky_purchase_box\",\"is_active\":true,\"settings\":{\"headline\":\"Collector Acquisition & Authenticity Seal\",\"alignment\":\"center\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"48px\",\"padding_bottom\":\"48px\"}},{\"id\":\"sec_artisan_6afa7ae2bffab\",\"type\":\"artisan_spotlight\",\"is_active\":true,\"settings\":{\"headline\":\"Crafted by {{ artisan.name }}\",\"subheadline\":\"Generational master of the {{ artisan.craft }} tradition.\",\"alignment\":\"left\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_specs_6afa7ae2bffac\",\"type\":\"specs_anatomy\",\"is_active\":true,\"settings\":{\"headline\":\"Anatomy of Craft & Material Composition\",\"alignment\":\"center\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_gallery_6afa7ae2bffad\",\"type\":\"interactive_gallery\",\"is_active\":true,\"settings\":{\"headline\":\"Macro Details & Archival Motifs\",\"alignment\":\"center\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_provenance_6afa7ae2bffae\",\"type\":\"heritage_provenance\",\"is_active\":true,\"settings\":{\"headline\":\"Geographical Origin: {{ district.name }}\",\"subheadline\":\"Nourished by the {{ district.river_basin }} currents.\",\"alignment\":\"center\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_narrative_6afa7ae2bffaf\",\"type\":\"editorial_narrative\",\"is_active\":true,\"settings\":{\"headline\":\"The Loom Saga & Cultural Lineage\",\"alignment\":\"left\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}}]}',1,'2026-11-15 01:37:31','2026-11-15 21:39:14'),(2,'c85e9ac0-71c6-48b6-9882-bb7f94d42ef0','Artisan Atelier Showcase','story','https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=600','{\"version\":1,\"page_type\":\"artisan\",\"sections\":[{\"id\":\"sec_artisan_6af9613ba65d4\",\"type\":\"artisan_spotlight\",\"is_active\":true,\"settings\":{\"layout_style\":\"centered_portrait\"}},{\"id\":\"sec_gallery_6af9613ba65d8\",\"type\":\"interactive_gallery\",\"is_active\":true,\"settings\":[]},{\"id\":\"sec_narrative_6af9613ba65d9\",\"type\":\"editorial_narrative\",\"is_active\":true,\"settings\":{\"headline\":\"Generations of Handloom Cadence\"}}]}',1,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(3,'3413c562-3f24-4b7f-9c21-9819f6ef7c27','Festival Seasonal Drop Campaign','festival','https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600','{\"version\":1,\"page_type\":\"campaign\",\"sections\":[{\"id\":\"sec_hero_6af9613ba6f4e\",\"type\":\"hero_cinematic\",\"is_active\":true,\"settings\":{\"badge_text\":\"Seasonal Festival Drop \\u2022 Limited Batch\",\"headline\":\"The Autumn Jamdani & Muslin Release\",\"subheadline\":\"Exclusively reserved for private patrons and international expat collectors.\"}},{\"id\":\"sec_gallery_6af9613ba6f52\",\"type\":\"interactive_gallery\",\"is_active\":true,\"settings\":[]},{\"id\":\"sec_testimonial_6af9613ba6f53\",\"type\":\"testimonial_editorial\",\"is_active\":true,\"settings\":[]}]}',1,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(4,'907bc9fc-ab28-457b-a401-f5a3ea238ee8','Heritage Sanctuary Homepage Master','home','https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=600','{\"version\":1,\"page_type\":\"home\",\"sections\":[{\"id\":\"sec_home_hero\",\"type\":\"hero_cinematic\",\"is_active\":true,\"settings\":{\"badge_text\":\"Bespoke Heirlooms of Bengal \\u2022 GI Certified\",\"headline\":\"Living Traditions of Master Bengali Artisans\",\"subheadline\":\"Preserving ancestral loom lineages, river basin metallurgy, and living master craftsmanship.\",\"primary_cta_text\":\"Acquire Masterpieces\",\"primary_cta_url\":\"\\/shop\",\"secondary_cta_text\":\"Artisan Gazette\",\"secondary_cta_url\":\"\\/stories\",\"media_type\":\"video\",\"video_url\":\"https:\\/\\/www.youtube.com\\/watch?v=dQw4w9WgXcQ\",\"alignment\":\"center\",\"text_color\":\"#1D1D1D\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_home_artisan\",\"type\":\"artisan_spotlight\",\"is_active\":true,\"settings\":{\"headline\":\"Meet Our Generational Master Artisans\",\"subheadline\":\"Behind every thread and chisel lies a master whose family has perfected their craft over centuries.\",\"badge_text\":\"Generational Custodians\",\"media_type\":\"image\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1544005313-94ddf0286df2?q=80&w=800\",\"alignment\":\"left\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_home_provenance\",\"type\":\"heritage_provenance\",\"is_active\":true,\"settings\":{\"headline\":\"The Geography of Bengal Craftsmanship\",\"subheadline\":\"From the humid mists of Shitalakshya to the rich sands of the Jamuna river basins.\",\"badge_text\":\"Geographical Indication\",\"alignment\":\"center\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_home_gallery\",\"type\":\"interactive_gallery\",\"is_active\":true,\"settings\":{\"headline\":\"Living Archive Visual Gallery\",\"subheadline\":\"Macro details of Jamdani motifs, 300-count cotton threads, and hand-chiseled bell metal.\",\"badge_text\":\"Curated Details\",\"alignment\":\"center\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_home_testimonials\",\"type\":\"testimonial_editorial\",\"is_active\":true,\"settings\":{\"headline\":\"Appraisals from Global Connoisseurs\",\"subheadline\":\"Trusted by collectors across Dhaka, London, Toronto, and New York.\",\"badge_text\":\"Verified Collector Seal\",\"alignment\":\"center\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}}]}',1,'2026-11-15 04:51:38','2026-11-15 21:39:14'),(5,'8e157c15-69f4-4203-a1c9-e4ef9e219655','Living Manifesto (About Us)','about','https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=600','{\"version\":1,\"page_type\":\"about\",\"sections\":[{\"id\":\"sec_about_hero\",\"type\":\"hero_cinematic\",\"is_active\":true,\"settings\":{\"badge_text\":\"The Living Manifesto of Bengal\",\"headline\":\"Reviving Ancient Looms, Elevating Living Masters\",\"subheadline\":\"Sorolata is not merely an e-commerce platform; it is an archival sanctuary dedicated to protecting the indigenous crafts of South Asia.\",\"primary_cta_text\":\"Meet Our Artisans\",\"primary_cta_url\":\"\\/artisans\",\"alignment\":\"center\",\"text_color\":\"#FFFFFF\",\"bg_color\":\"#214E34\",\"padding_top\":\"96px\",\"padding_bottom\":\"96px\"}},{\"id\":\"sec_about_narrative\",\"type\":\"editorial_narrative\",\"is_active\":true,\"settings\":{\"headline\":\"Why Traditional Bengali Craft Demands Safeguarding\",\"subheadline\":\"In an era of disposable fashion and industrial machines, our generational weavers still count threads by eye and mold alloys over clay furnaces.\",\"alignment\":\"left\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_about_provenance\",\"type\":\"heritage_provenance\",\"is_active\":true,\"settings\":{\"headline\":\"Our Geographic Footprint & River Lineages\",\"subheadline\":\"Working across Tangail, Narayanganj, Rajshahi, and Dhamrai artisan clusters.\",\"alignment\":\"center\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_about_gallery\",\"type\":\"interactive_gallery\",\"is_active\":true,\"settings\":{\"headline\":\"Inside the Living Ateliers\",\"subheadline\":\"Witness the patience, tools, and generational techniques documented in our archives.\",\"alignment\":\"center\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}}]}',1,'2026-11-15 04:51:38','2026-11-15 21:39:14'),(6,'4bcf7acd-d065-4597-abf7-f7a62d25b377','Curator Concierge & Inquiries (Contact)','contact','https://images.unsplash.com/photo-1577495508048-b635879837f1?q=80&w=600','{\"version\":1,\"page_type\":\"contact\",\"sections\":[{\"id\":\"sec_contact_hero\",\"type\":\"hero_cinematic\",\"is_active\":true,\"settings\":{\"badge_text\":\"Private Patron Concierge \\u2022 Dhaka Atelier\",\"headline\":\"Consult with Our Master Curators\",\"subheadline\":\"Inquire about bespoke commissions, international insured courier dispatch, or private viewing appointments.\",\"primary_cta_text\":\"Message on WhatsApp\",\"primary_cta_url\":\"https:\\/\\/wa.me\\/8801700000000\",\"secondary_cta_text\":\"Direct Curator Email\",\"secondary_cta_url\":\"mailto:curator@sorolata.com\",\"alignment\":\"center\",\"text_color\":\"#1D1D1D\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"80px\",\"padding_bottom\":\"80px\"}},{\"id\":\"sec_contact_provenance\",\"type\":\"heritage_provenance\",\"is_active\":true,\"settings\":{\"headline\":\"Global Logistics & Insured White-Glove Courier\",\"subheadline\":\"Doorstep delivery across Bangladesh, USA, UK, Canada, and Europe with authentication seals.\",\"alignment\":\"center\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_contact_specs\",\"type\":\"specs_anatomy\",\"is_active\":true,\"settings\":{\"headline\":\"Atelier Viewing Hours & Authentication\",\"alignment\":\"center\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}}]}',1,'2026-11-15 04:51:38','2026-11-15 21:39:14'),(7,'c639b0bb-e5a6-4bc5-8c02-a1d7701eac6f','Seasonal Festival Drop (Campaign)','campaign','https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600','{\"version\":1,\"page_type\":\"campaign\",\"sections\":[{\"id\":\"sec_campaign_hero\",\"type\":\"hero_cinematic\",\"is_active\":true,\"settings\":{\"badge_text\":\"Seasonal Festival Drop \\u2022 Limited Batch\",\"headline\":\"The Autumn Jamdani & Royal Brass Release\",\"subheadline\":\"Exclusively reserved for private patrons and international expat collectors.\",\"primary_cta_text\":\"Explore Drop Pieces\",\"primary_cta_url\":\"\\/shop\",\"alignment\":\"center\",\"text_color\":\"#FFFFFF\",\"bg_color\":\"#C96A3D\",\"padding_top\":\"96px\",\"padding_bottom\":\"96px\"}},{\"id\":\"sec_campaign_gallery\",\"type\":\"interactive_gallery\",\"is_active\":true,\"settings\":{\"headline\":\"Limited Edition Showcase\",\"subheadline\":\"Each piece is hand-numbered and certified with archival seal.\",\"alignment\":\"center\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_campaign_artisan\",\"type\":\"artisan_spotlight\",\"is_active\":true,\"settings\":{\"headline\":\"Featured Master Weavers of the Drop\",\"subheadline\":\"Crafting heirloom saris on 200-year-old pit looms.\",\"alignment\":\"left\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_campaign_testimonial\",\"type\":\"testimonial_editorial\",\"is_active\":true,\"settings\":{\"headline\":\"Patron Testimonials\",\"alignment\":\"center\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}}]}',1,'2026-11-15 04:51:38','2026-11-15 21:39:14'),(8,'0390d5b8-1a44-413c-b504-2739a4c0a8e8','Shajgoj Content-to-Commerce Magazine Homepage','home','https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=800','{\"version\":1,\"page_type\":\"home\",\"sections\":[{\"id\":\"sec_mag_hero\",\"type\":\"magazine_hero_grid\",\"is_active\":true,\"settings\":{\"badge_text\":\"HERITAGE DISCOVERY \\u2022 5 MIN READ\",\"headline\":\"Are you a Green or Red Flag? Have You Ever Discovered Your True Craft Soul Amidst Heritage?\",\"subheadline\":\"Traveling across the historic river basins of Bengal where master weavers and metalsmiths breathe life into centuries of living traditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1610030469983-98e550d6193c?q=80&w=1200\",\"primary_cta_text\":\"Read Full Gazette Story\",\"primary_cta_url\":\"\\/stories\",\"alignment\":\"left\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"40px\",\"padding_bottom\":\"32px\"}},{\"id\":\"sec_mag_bubbles\",\"type\":\"topic_bubbles\",\"is_active\":true,\"settings\":{\"headline\":\"Explore Stories by Heritage Craft & Guild\",\"badge_text\":\"TOPIC ARCHIVES\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"32px\",\"padding_bottom\":\"32px\"}},{\"id\":\"sec_mag_feed\",\"type\":\"editorial_feed_sidebar\",\"is_active\":true,\"settings\":{\"headline\":\"Heritage Gazette Stories & In-Depth Chronicles\",\"badge_text\":\"CURATED JOURNAL\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"48px\",\"padding_bottom\":\"48px\"}},{\"id\":\"sec_mag_shelf\",\"type\":\"shoppable_product_shelf\",\"is_active\":true,\"settings\":{\"headline\":\"From Story to Hand: Acquire the Pieces Featured Above\",\"subheadline\":\"Handcrafted in limited archival batches by the master guilds profiled in this edition.\",\"badge_text\":\"AS FEATURED IN STORIES\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"56px\",\"padding_bottom\":\"56px\"}},{\"id\":\"sec_mag_artisan\",\"type\":\"artisan_spotlight\",\"is_active\":true,\"settings\":{\"headline\":\"Crafted by Ustad Alimullah Dewan\",\"subheadline\":\"Third-generation master weaver preserving the royal Jamdani pit-loom on the Shitalakshya river bank.\",\"badge_text\":\"Royal Master Loom Guild \\u2022 Sonargaon\",\"alignment\":\"left\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"56px\",\"padding_bottom\":\"56px\"}},{\"id\":\"sec_mag_reels\",\"type\":\"video_reels_showcase\",\"is_active\":true,\"settings\":{\"headline\":\"Living Chronicles: Watch the Hands Behind the Craft\",\"badge_text\":\"LIVING CINEMATICS\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"48px\",\"padding_bottom\":\"48px\"}},{\"id\":\"sec_mag_provenance\",\"type\":\"heritage_provenance\",\"is_active\":true,\"settings\":{\"headline\":\"Geographical Indication & Regional Heritage\",\"subheadline\":\"Every piece certified for genuine river basin origin and fair-trade honorarium.\",\"badge_text\":\"GI Certified Bengal Craftsmanship\",\"alignment\":\"center\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"56px\",\"padding_bottom\":\"56px\"}},{\"id\":\"sec_mag_newsletter\",\"type\":\"heritage_club_newsletter\",\"is_active\":true,\"settings\":{\"headline\":\"Join the Heritage Gazette & Collector Circle\",\"badge_text\":\"SOROLATA HERITAGE GUILD\",\"subheadline\":\"Receive our monthly investigative craft essays, private notifications for limited loom harvests, and invitations to exhibitions.\",\"primary_cta_text\":\"Subscribe to Gazette\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"48px\",\"padding_bottom\":\"48px\"}}]}',1,'2026-11-15 21:39:14','2026-11-15 21:39:14');
/*!40000 ALTER TABLE `page_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `page_type` varchar(255) NOT NULL DEFAULT 'landing',
  `layout_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`layout_json`)),
  `seo_meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`seo_meta`)),
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `version` int(10) unsigned NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_uuid_unique` (`uuid`),
  UNIQUE KEY `pages_slug_unique` (`slug`),
  KEY `pages_title_index` (`title`),
  KEY `pages_page_type_index` (`page_type`),
  KEY `pages_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'80fb31cb-fddb-422b-a7d0-47206d167164','heritage-craft-exhibition','Heritage Craft Exhibition','campaign','{\"version\":1,\"page_type\":\"campaign\",\"sections\":[{\"id\":\"sec_exhibit_1\",\"type\":\"hero_cinematic\",\"is_active\":true,\"settings\":{\"badge_text\":\"GI Certified Heritage Artifact\",\"headline\":\"Living Traditions of Sonargaon\",\"subheadline\":\"An archival voyage into centuries of Bengali master artisans.\",\"primary_cta_text\":\"Explore Exhibition\"}},{\"id\":\"sec_nsF1bZJW\",\"type\":\"editorial_narrative\",\"is_active\":true,\"settings\":{\"headline\":\"Editorial Story & Cultural Context\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_cGBnbZUe\",\"type\":\"testimonial_editorial\",\"is_active\":true,\"settings\":{\"headline\":\"Collector Impressions & Authenticity\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_VC14RWK7\",\"type\":\"specs_anatomy\",\"is_active\":true,\"settings\":{\"headline\":\"Craft Anatomy & Material Composition\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_hrzdSt6I\",\"type\":\"specs_anatomy\",\"is_active\":true,\"settings\":{\"headline\":\"Craft Anatomy & Material Composition\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_o7QqJDjO\",\"type\":\"heritage_provenance\",\"is_active\":true,\"settings\":{\"headline\":\"Geographical Indication & Regional Heritage\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_rppNqLks\",\"type\":\"artisan_spotlight\",\"is_active\":true,\"settings\":{\"headline\":\"Meet the Master Artisan\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_2UH52sRm\",\"type\":\"sticky_purchase_box\",\"is_active\":true,\"settings\":{\"headline\":\"Editorial Purchase & Collector Box\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_hnOa7Tea\",\"type\":\"interactive_gallery\",\"is_active\":true,\"settings\":{\"headline\":\"Curated Visual Gallery & Macro Details\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_PKgAVnW8\",\"type\":\"editorial_narrative\",\"is_active\":true,\"settings\":{\"headline\":\"Editorial Story & Cultural Context\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_bX6DZrTq\",\"type\":\"testimonial_editorial\",\"is_active\":true,\"settings\":{\"headline\":\"Collector Impressions & Authenticity\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_kdCuEI9r\",\"type\":\"artisan_spotlight\",\"is_active\":true,\"settings\":{\"headline\":\"Meet the Master Artisan\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_cdbzlU6I\",\"type\":\"heritage_provenance\",\"is_active\":true,\"settings\":{\"headline\":\"Geographical Indication & Regional Heritage\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_ndBn7dyu\",\"type\":\"heritage_provenance\",\"is_active\":true,\"settings\":{\"headline\":\"Geographical Indication & Regional Heritage\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_iQIqpsuS\",\"type\":\"specs_anatomy\",\"is_active\":true,\"settings\":{\"headline\":\"Craft Anatomy & Material Composition\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_7Gezl1hr\",\"type\":\"artisan_spotlight\",\"is_active\":true,\"settings\":{\"headline\":\"Meet the Master Artisan\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]}]}','{\"title\":\"Heritage Craft Exhibition\",\"description\":\"Bengal Craft Exhibition\"}','published',NULL,1,'2026-11-15 01:53:19','2026-11-15 03:13:12',NULL),(2,'c0efe2ea-8584-4877-81d2-9d33c9ed7392','home','Heritage Living Sanctuary (Homepage)','home','{\"version\":1,\"page_type\":\"home\",\"sections\":[{\"id\":\"sec_hero_6af9613ba6f4e\",\"type\":\"hero_cinematic\",\"is_active\":true,\"settings\":{\"badge_text\":\"Seasonal Festival Drop \\u2022 Limited Batch\",\"headline\":\"The Autumn Jamdani & Muslin Release\",\"subheadline\":\"Exclusively reserved for private patrons and international expat collectors.\"}},{\"id\":\"sec_gallery_6af9613ba6f52\",\"type\":\"interactive_gallery\",\"is_active\":true,\"settings\":[]},{\"id\":\"sec_testimonial_6af9613ba6f53\",\"type\":\"testimonial_editorial\",\"is_active\":true,\"settings\":[]}]}','{\"title\":\"Sorolata \\u2014 Handcrafted Bengali Heirlooms & Master Artisans\",\"description\":\"Ethereal Jamdani, Sonargaon Muslin, Bogura Brassware and generational craft.\"}','published',NULL,1,'2026-11-15 04:02:45','2026-11-15 04:28:17',NULL),(3,'0cb1a55a-70ef-41a9-99c9-8dcaf7b91181','about','Our Living Manifesto (About Us)','standard','{\"version\":1,\"page_type\":\"standard\",\"sections\":[{\"id\":\"sec_about_hero\",\"type\":\"hero_cinematic\",\"is_active\":true,\"settings\":{\"badge_text\":\"Our Living Philosophy \\u2022 Established 2026\",\"headline\":\"The Preservation of Living Heritage\",\"subheadline\":\"Sorolata was born from a singular mission: to safeguard endangered artisan traditions and bridge master weavers with global patrons.\",\"primary_cta_text\":\"Read Our Manifesto\"}},{\"id\":\"sec_about_narrative\",\"type\":\"editorial_narrative\",\"is_active\":true,\"settings\":{\"headline\":\"Slow Commerce & Sovereign Craftsmanship\",\"subheadline\":\"We reject fast fashion and industrialized assembly lines in favor of ethical patronage and fair artisan honoraria.\"}},{\"id\":\"sec_about_provenance\",\"type\":\"heritage_provenance\",\"is_active\":true,\"settings\":{\"headline\":\"Certified Geographical Indication Guilds\"}},{\"id\":\"sec_nskofC4z\",\"type\":\"artisan_spotlight\",\"is_active\":true,\"settings\":{\"headline\":\"Meet the Master Artisan\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_lrOROGdk\",\"type\":\"artisan_spotlight\",\"is_active\":true,\"settings\":{\"headline\":\"Meet the Master Artisan\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_5hUTBfxl\",\"type\":\"heritage_provenance\",\"is_active\":true,\"settings\":{\"headline\":\"Geographical Indication & Regional Heritage\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_cFWMPtpu\",\"type\":\"interactive_gallery\",\"is_active\":true,\"settings\":{\"headline\":\"Curated Visual Gallery & Macro Details\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_zCcQel3m\",\"type\":\"editorial_narrative\",\"is_active\":true,\"settings\":{\"headline\":\"Editorial Story & Cultural Context\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_8HILsSmy\",\"type\":\"testimonial_editorial\",\"is_active\":true,\"settings\":{\"headline\":\"Collector Impressions & Authenticity\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]},{\"id\":\"sec_NPXUYUOm\",\"type\":\"testimonial_editorial\",\"is_active\":true,\"settings\":{\"headline\":\"Collector Impressions & Authenticity\",\"badge_text\":\"GI Certified Heritage Artifact\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\"},\"blocks\":[]}]}','{\"title\":\"About Sorolata \\u2014 The Living Manifesto\",\"description\":\"Our dedication to traditional South Asian craft, ethics, and master artisan preservation.\"}','published',NULL,1,'2026-11-15 04:02:45','2026-11-15 04:35:14',NULL),(4,'16bff7a6-e4b6-460e-875e-dfe81209dff2','contact','Curator Concierge (Contact Us)','standard','{\"version\":1,\"page_type\":\"standard\",\"sections\":[{\"id\":\"sec_contact_hero\",\"type\":\"hero_cinematic\",\"is_active\":true,\"settings\":{\"badge_text\":\"Private Patron Concierge \\u2022 Dhaka Atelier\",\"headline\":\"Consult with Our Master Curators\",\"subheadline\":\"Inquire about bespoke commissions, international insured courier dispatch, or private viewing appointments.\",\"primary_cta_text\":\"Contact via WhatsApp\"}},{\"id\":\"sec_contact_provenance\",\"type\":\"heritage_provenance\",\"is_active\":true,\"settings\":{\"headline\":\"Global Logistics & Insured White-Glove Courier\",\"subheadline\":\"Doorstep delivery across Bangladesh, USA, UK, Canada, and Europe with authentication seals.\"}}]}','{\"title\":\"Curator Concierge \\u2014 Contact Sorolata\",\"description\":\"Connect with our master curation desk for bespoke commissions and inquiries.\"}','published',NULL,1,'2026-11-15 04:02:45','2026-11-15 04:02:45',NULL),(5,'f1958841-2497-4d9a-898e-b03a1c9ae549','mud','Mud Handcraft','landing','{\"version\":1,\"page_type\":\"landing\",\"sections\":[{\"id\":\"sec_home_hero\",\"type\":\"hero_cinematic\",\"is_active\":true,\"settings\":{\"badge_text\":\"Bespoke Heirlooms of Bengal \\u2022 GI Certified\",\"headline\":\"Living Traditions of Master Bengali Artisans\",\"subheadline\":\"Preserving ancestral loom lineages, river basin metallurgy, and living master craftsmanship.\",\"primary_cta_text\":\"Acquire Masterpieces\",\"primary_cta_url\":\"\\/shop\",\"secondary_cta_text\":\"Artisan Gazette\",\"secondary_cta_url\":\"\\/stories\",\"media_type\":\"video\",\"video_url\":\"https:\\/\\/www.youtube.com\\/watch?v=dQw4w9WgXcQ\",\"alignment\":\"center\",\"text_color\":\"#1D1D1D\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_home_artisan\",\"type\":\"artisan_spotlight\",\"is_active\":true,\"settings\":{\"headline\":\"Meet Our Generational Master Artisans\",\"subheadline\":\"Behind every thread and chisel lies a master whose family has perfected their craft over centuries.\",\"badge_text\":\"Generational Custodians\",\"media_type\":\"image\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1544005313-94ddf0286df2?q=80&w=800\",\"alignment\":\"left\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"el_Yx0DVTK0\",\"type\":\"element_heading\",\"is_active\":true,\"settings\":{\"headline\":\"New Editorial Heading\",\"subheadline\":\"\",\"badge_text\":\"Heritage\",\"price\":\"\\u09f3 18,500\",\"primary_cta_text\":\"Acquire Piece\",\"primary_cta_url\":\"\\/shop\",\"secondary_cta_text\":\"\",\"secondary_cta_url\":\"\\/stories\",\"media_type\":\"none\",\"image_url\":\"\",\"video_url\":\"\",\"alignment\":\"center\",\"text_color\":\"#1D1D1D\",\"headline_size\":\"text-4xl sm:text-5xl\",\"padding_top\":\"24px\",\"padding_bottom\":\"24px\",\"bg_color\":\"#FFFFFF\",\"container_width\":\"boxed\"},\"blocks\":[]},{\"id\":\"el_TlKJaCfr\",\"type\":\"artisan_spotlight\",\"is_active\":true,\"settings\":{\"headline\":\"Meet the Master Artisan\",\"subheadline\":\"\",\"badge_text\":\"Heritage\",\"price\":\"\\u09f3 18,500\",\"primary_cta_text\":\"Acquire Piece\",\"primary_cta_url\":\"\\/shop\",\"secondary_cta_text\":\"Explore Heritage\",\"secondary_cta_url\":\"\\/stories\",\"media_type\":\"none\",\"image_url\":\"\",\"video_url\":\"\",\"alignment\":\"center\",\"text_color\":\"#1D1D1D\",\"headline_size\":\"text-2xl sm:text-3xl\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\",\"bg_color\":\"#FFFFFF\",\"container_width\":\"boxed\"},\"blocks\":[]},{\"id\":\"sec_home_provenance\",\"type\":\"heritage_provenance\",\"is_active\":true,\"settings\":{\"headline\":\"The Geography of Bengal Craftsmanship\",\"subheadline\":\"From the humid mists of Shitalakshya to the rich sands of the Jamuna river basins.\",\"badge_text\":\"Geographical Indication\",\"alignment\":\"center\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_home_gallery\",\"type\":\"interactive_gallery\",\"is_active\":true,\"settings\":{\"headline\":\"Living Archive Visual Gallery\",\"subheadline\":\"Macro details of Jamdani motifs, 300-count cotton threads, and hand-chiseled bell metal.\",\"badge_text\":\"Curated Details\",\"alignment\":\"center\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}},{\"id\":\"sec_home_testimonials\",\"type\":\"testimonial_editorial\",\"is_active\":true,\"settings\":{\"headline\":\"Appraisals from Global Connoisseurs\",\"subheadline\":\"Trusted by collectors across Dhaka, London, Toronto, and New York.\",\"badge_text\":\"Verified Collector Seal\",\"alignment\":\"center\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"64px\",\"padding_bottom\":\"64px\"}}]}','{\"title\":\"Mud Handcraft \\u2014 Sorolata\",\"description\":\"Curated Bengali heritage handcrafted collection.\"}','draft',NULL,1,'2026-11-15 04:38:13','2026-11-15 21:26:25',NULL),(6,'6b604267-3443-40c9-a381-0ac489227797','shajgoj-magazine','Shajgoj Style Content-to-Commerce Magazine','home','{\"version\":1,\"page_type\":\"home\",\"sections\":[{\"id\":\"sec_mag_hero\",\"type\":\"magazine_hero_grid\",\"is_active\":true,\"settings\":{\"badge_text\":\"HERITAGE DISCOVERY \\u2022 5 MIN READ\",\"headline\":\"Are you a Green or Red Flag? Have You Ever Discovered Your True Craft Soul Amidst Heritage?\",\"subheadline\":\"Traveling across the historic river basins of Bengal where master weavers and metalsmiths breathe life into centuries of living traditions.\",\"image_url\":\"https:\\/\\/images.unsplash.com\\/photo-1610030469983-98e550d6193c?q=80&w=1200\",\"primary_cta_text\":\"Read Full Gazette Story\",\"primary_cta_url\":\"\\/stories\",\"alignment\":\"left\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"40px\",\"padding_bottom\":\"32px\"}},{\"id\":\"sec_mag_bubbles\",\"type\":\"topic_bubbles\",\"is_active\":true,\"settings\":{\"headline\":\"Explore Stories by Heritage Craft & Guild\",\"badge_text\":\"TOPIC ARCHIVES\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"32px\",\"padding_bottom\":\"32px\"}},{\"id\":\"sec_mag_feed\",\"type\":\"editorial_feed_sidebar\",\"is_active\":true,\"settings\":{\"headline\":\"Heritage Gazette Stories & In-Depth Chronicles\",\"badge_text\":\"CURATED JOURNAL\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"48px\",\"padding_bottom\":\"48px\"}},{\"id\":\"sec_mag_shelf\",\"type\":\"shoppable_product_shelf\",\"is_active\":true,\"settings\":{\"headline\":\"From Story to Hand: Acquire the Pieces Featured Above\",\"subheadline\":\"Handcrafted in limited archival batches by the master guilds profiled in this edition.\",\"badge_text\":\"AS FEATURED IN STORIES\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"56px\",\"padding_bottom\":\"56px\"}},{\"id\":\"sec_mag_artisan\",\"type\":\"artisan_spotlight\",\"is_active\":true,\"settings\":{\"headline\":\"Crafted by Ustad Alimullah Dewan\",\"subheadline\":\"Third-generation master weaver preserving the royal Jamdani pit-loom on the Shitalakshya river bank.\",\"badge_text\":\"Royal Master Loom Guild \\u2022 Sonargaon\",\"alignment\":\"left\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"56px\",\"padding_bottom\":\"56px\"}},{\"id\":\"sec_mag_reels\",\"type\":\"video_reels_showcase\",\"is_active\":true,\"settings\":{\"headline\":\"Living Chronicles: Watch the Hands Behind the Craft\",\"badge_text\":\"LIVING CINEMATICS\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"48px\",\"padding_bottom\":\"48px\"}},{\"id\":\"sec_mag_provenance\",\"type\":\"heritage_provenance\",\"is_active\":true,\"settings\":{\"headline\":\"Geographical Indication & Regional Heritage\",\"subheadline\":\"Every piece certified for genuine river basin origin and fair-trade honorarium.\",\"badge_text\":\"GI Certified Bengal Craftsmanship\",\"alignment\":\"center\",\"bg_color\":\"#F8F6F2\",\"padding_top\":\"56px\",\"padding_bottom\":\"56px\"}},{\"id\":\"sec_mag_newsletter\",\"type\":\"heritage_club_newsletter\",\"is_active\":true,\"settings\":{\"headline\":\"Join the Heritage Gazette & Collector Circle\",\"badge_text\":\"SOROLATA HERITAGE GUILD\",\"subheadline\":\"Receive our monthly investigative craft essays, private notifications for limited loom harvests, and invitations to exhibitions.\",\"primary_cta_text\":\"Subscribe to Gazette\",\"bg_color\":\"#FFFFFF\",\"padding_top\":\"48px\",\"padding_bottom\":\"48px\"}}]}','{\"title\":\"Sorolata \\u2014 Shajgoj Content-to-Commerce Gazette\",\"description\":\"Where authentic artisan stories meet bespoke handcrafted commerce.\"}','published',NULL,1,'2026-11-15 21:39:14','2026-11-15 21:39:14',NULL);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `gateway` varchar(255) NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'BDT',
  `amount` decimal(12,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_uuid_unique` (`uuid`),
  UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  KEY `payments_order_id_foreign` (`order_id`),
  KEY `payments_gateway_index` (`gateway`),
  KEY `payments_status_index` (`status`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_attribute_values`
--

DROP TABLE IF EXISTS `product_attribute_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_attribute_values` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `attribute_value_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prod_attr_val_unique` (`product_id`,`attribute_value_id`),
  KEY `product_attribute_values_attribute_value_id_foreign` (`attribute_value_id`),
  CONSTRAINT `product_attribute_values_attribute_value_id_foreign` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_attribute_values_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_attribute_values`
--

LOCK TABLES `product_attribute_values` WRITE;
/*!40000 ALTER TABLE `product_attribute_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_attribute_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_compares`
--

DROP TABLE IF EXISTS `product_compares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_compares` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_compares_user_id_foreign` (`user_id`),
  KEY `product_compares_product_id_foreign` (`product_id`),
  KEY `product_compares_session_id_product_id_index` (`session_id`,`product_id`),
  KEY `product_compares_session_id_index` (`session_id`),
  CONSTRAINT `product_compares_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_compares_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_compares`
--

LOCK TABLES `product_compares` WRITE;
/*!40000 ALTER TABLE `product_compares` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_compares` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_contents`
--

DROP TABLE IF EXISTS `product_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_contents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `story_title` varchar(255) DEFAULT NULL,
  `story_narrative` longtext DEFAULT NULL,
  `craft_technique` text DEFAULT NULL,
  `heritage_significance` text DEFAULT NULL,
  `care_guide` text DEFAULT NULL,
  `video_embed_url` varchar(255) DEFAULT NULL,
  `builder_layout_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_contents_product_id_unique` (`product_id`),
  KEY `product_contents_builder_layout_id_foreign` (`builder_layout_id`),
  CONSTRAINT `product_contents_builder_layout_id_foreign` FOREIGN KEY (`builder_layout_id`) REFERENCES `product_layouts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `product_contents_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_contents`
--

LOCK TABLES `product_contents` WRITE;
/*!40000 ALTER TABLE `product_contents` DISABLE KEYS */;
INSERT INTO `product_contents` VALUES (1,1,'Born of Shitalakshya River Moisture & Moonlit Looms','Woven exclusively during early morning mist when ambient humidity preserves the delicate 100-count cotton fibers from snapping under tension. Each floral karpash motif is laid without preliminary tracing, relying on hereditary muscle memory passed through five generations.','Discontinuous supplementary weft technique on two-pedal pit looms with tamarind-wood shuttles.','Certified under Geographical Indication Act (GI Registration BD-01) protecting authentic Bengal handloom origin.','Dry clean only using museum-grade hydrocarbon or gentle steam. Store wrapped in unbleached mulmul cloth; avoid plastic sealing to allow natural fiber breathability.','https://vimeo.com/76979871',NULL,'2026-11-15 01:37:33','2026-11-15 01:37:33');
/*!40000 ALTER TABLE `product_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_downloads`
--

DROP TABLE IF EXISTS `product_downloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_downloads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `file_size` bigint(20) unsigned NOT NULL DEFAULT 0,
  `download_limit` int(10) unsigned DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_downloads_product_id_foreign` (`product_id`),
  CONSTRAINT `product_downloads_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_downloads`
--

LOCK TABLES `product_downloads` WRITE;
/*!40000 ALTER TABLE `product_downloads` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_downloads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_faqs`
--

DROP TABLE IF EXISTS `product_faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_faqs_product_id_is_active_sort_order_index` (`product_id`,`is_active`,`sort_order`),
  KEY `product_faqs_is_active_index` (`is_active`),
  CONSTRAINT `product_faqs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_faqs`
--

LOCK TABLES `product_faqs` WRITE;
/*!40000 ALTER TABLE `product_faqs` DISABLE KEYS */;
INSERT INTO `product_faqs` VALUES (1,1,'How can I verify the authentic Geographical Indication (GI) of this Jamdani?','Each Sorolata masterpiece arrives with a numbered Archival Certificate of Provenance containing the exact master artisan signature, loom GPS coordinates, and a verifiable serial registered with the Bangladesh Small & Cottage Industries Corporation.',1,1,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(2,1,'What is the recommended method to preserve this heirloom saree over decades?','Wrap the saree in a clean, breathable unbleached mulmul or muslin cloth. Refold the garment every 3-4 months along different crease lines to prevent fiber stress. Never use chemical mothballs directly on natural dyes.',2,1,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(3,1,'Do you provide international insured shipping for collector pieces?','Yes. We provide temperature-stabilized, insured express transit via DHL Express to USA, UK, Canada, Australia and Europe with complete customs duty documentation included.',3,1,'2026-11-15 01:37:33','2026-11-15 01:37:33');
/*!40000 ALTER TABLE `product_faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_layouts`
--

DROP TABLE IF EXISTS `product_layouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_layouts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `version` int(10) unsigned NOT NULL DEFAULT 1,
  `block_payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`block_payload`)),
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `change_summary` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_layouts_created_by_foreign` (`created_by`),
  KEY `product_layouts_product_id_version_index` (`product_id`,`version`),
  CONSTRAINT `product_layouts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `product_layouts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_layouts`
--

LOCK TABLES `product_layouts` WRITE;
/*!40000 ALTER TABLE `product_layouts` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_layouts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_materials`
--

DROP TABLE IF EXISTS `product_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_materials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `material_id` bigint(20) unsigned NOT NULL,
  `percentage` tinyint(3) unsigned NOT NULL DEFAULT 100,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_materials_product_id_material_id_unique` (`product_id`,`material_id`),
  KEY `product_materials_material_id_foreign` (`material_id`),
  CONSTRAINT `product_materials_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_materials_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_materials`
--

LOCK TABLES `product_materials` WRITE;
/*!40000 ALTER TABLE `product_materials` DISABLE KEYS */;
INSERT INTO `product_materials` VALUES (1,1,1,85),(2,1,2,15);
/*!40000 ALTER TABLE `product_materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_origins`
--

DROP TABLE IF EXISTS `product_origins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_origins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `district_id` bigint(20) unsigned DEFAULT NULL,
  `artisan_id` bigint(20) unsigned DEFAULT NULL,
  `workshop_id` bigint(20) unsigned DEFAULT NULL,
  `craft_type_id` bigint(20) unsigned DEFAULT NULL,
  `gi_certificate_number` varchar(255) DEFAULT NULL,
  `harvest_season` varchar(255) DEFAULT NULL,
  `provenance_story` text DEFAULT NULL,
  `geo_coordinates` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`geo_coordinates`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_origins_product_id_unique` (`product_id`),
  KEY `product_origins_district_id_foreign` (`district_id`),
  KEY `product_origins_artisan_id_foreign` (`artisan_id`),
  KEY `product_origins_workshop_id_foreign` (`workshop_id`),
  KEY `product_origins_craft_type_id_foreign` (`craft_type_id`),
  KEY `product_origins_gi_certificate_number_index` (`gi_certificate_number`),
  CONSTRAINT `product_origins_artisan_id_foreign` FOREIGN KEY (`artisan_id`) REFERENCES `artisans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `product_origins_craft_type_id_foreign` FOREIGN KEY (`craft_type_id`) REFERENCES `craft_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `product_origins_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `product_origins_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_origins_workshop_id_foreign` FOREIGN KEY (`workshop_id`) REFERENCES `workshops` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_origins`
--

LOCK TABLES `product_origins` WRITE;
/*!40000 ALTER TABLE `product_origins` DISABLE KEYS */;
INSERT INTO `product_origins` VALUES (1,1,1,1,1,1,'GI-BD-2016-01-SRL','Winter Post-Monsoon Sericulture','Direct from the pit-looms of Mograpara, Sonargaon basin.','{\"lat\":23.65,\"lng\":90.6}','2026-11-15 01:37:33','2026-11-15 01:37:33');
/*!40000 ALTER TABLE `product_origins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_relationships`
--

DROP TABLE IF EXISTS `product_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_relationships` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `related_product_id` bigint(20) unsigned NOT NULL,
  `relation_type` varchar(255) NOT NULL DEFAULT 'related',
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `prod_rel_unique` (`product_id`,`related_product_id`,`relation_type`),
  KEY `product_relationships_related_product_id_foreign` (`related_product_id`),
  KEY `product_relationships_relation_type_index` (`relation_type`),
  CONSTRAINT `product_relationships_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_relationships_related_product_id_foreign` FOREIGN KEY (`related_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_relationships`
--

LOCK TABLES `product_relationships` WRITE;
/*!40000 ALTER TABLE `product_relationships` DISABLE KEYS */;
INSERT INTO `product_relationships` VALUES (1,2,1,'frequently_bought_together',1,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(2,1,2,'related',1,'2026-11-15 01:37:31','2026-11-15 01:37:31');
/*!40000 ALTER TABLE `product_relationships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_specifications`
--

DROP TABLE IF EXISTS `product_specifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_specifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `group_name` varchar(255) NOT NULL DEFAULT 'General',
  `attribute_name` varchar(255) NOT NULL,
  `attribute_value` text NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_specifications_product_id_sort_order_index` (`product_id`,`sort_order`),
  KEY `product_specifications_group_name_index` (`group_name`),
  CONSTRAINT `product_specifications_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_specifications`
--

LOCK TABLES `product_specifications` WRITE;
/*!40000 ALTER TABLE `product_specifications` DISABLE KEYS */;
INSERT INTO `product_specifications` VALUES (1,1,'Textile Anatomy','Warp Material','Fine combed organic cotton (84 count)',1,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(2,1,'Textile Anatomy','Weft Supplementary','Mulberry silk and golden zari inlay',2,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(3,1,'Textile Anatomy','Length & Width','5.5 Meters (12 Haat) with 0.8M matching blouse piece',3,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(4,1,'Heritage Provenance','Loom Type','Traditional Two-Treadle Pit Loom',4,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(5,1,'Heritage Provenance','Creation Duration','84 Days of dedicated continuous craftsmanship',5,'2026-11-15 01:37:33','2026-11-15 01:37:33');
/*!40000 ALTER TABLE `product_specifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_variants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(255) NOT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `compare_at_price` decimal(12,2) DEFAULT NULL,
  `cost_price` decimal(12,2) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `reserved_stock` int(11) NOT NULL DEFAULT 0,
  `weight` decimal(8,3) DEFAULT NULL,
  `attributes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`attributes`)),
  `image_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_variants_uuid_unique` (`uuid`),
  UNIQUE KEY `product_variants_sku_unique` (`sku`),
  KEY `product_variants_product_id_foreign` (`product_id`),
  KEY `product_variants_barcode_index` (`barcode`),
  KEY `product_variants_is_active_index` (`is_active`),
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (1,'d163ed87-1a31-40fb-ae51-888c3d2671bd',1,'SRL-MSL-IVO-25',NULL,'Ivory White • 2.5-Yard Handspun',18500.00,22000.00,NULL,8,0,0.250,'{\"color\":\"Ivory White\",\"length\":\"2.5 Yards\"}',NULL,1,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(2,'736813af-4f1d-45b0-9cdf-2d6f77949376',1,'SRL-MSL-SAF-30',NULL,'Golden Saffron • 3.0-Yard Archival',22000.00,26000.00,NULL,5,0,0.300,'{\"color\":\"Golden Saffron\",\"length\":\"3.0 Yards\"}',NULL,1,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(3,'9274b67a-dae3-49c2-9746-1bdf802e50b2',2,'SRL-JAM-IND-06',NULL,'Midnight Indigo • 6-Yard Saree',32000.00,38000.00,NULL,4,0,0.650,'{\"color\":\"Midnight Indigo\",\"length\":\"6 Yards\"}',NULL,1,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(4,'b68cf277-dba5-49c7-b0b7-33e9502869eb',2,'SRL-JAM-MAR-06',NULL,'Imperial Maroon • 6-Yard Saree',34500.00,40000.00,NULL,3,0,0.650,'{\"color\":\"Imperial Maroon\",\"length\":\"6 Yards\"}',NULL,1,'2026-11-15 01:37:31','2026-11-15 01:37:31');
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_bn` varchar(255) DEFAULT NULL,
  `short_title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `subtitle_bn` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `short_description_bn` text DEFAULT NULL,
  `long_description` longtext DEFAULT NULL,
  `long_description_bn` longtext DEFAULT NULL,
  `sku` varchar(255) NOT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `internal_code` varchar(255) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `sale_price` decimal(12,2) DEFAULT NULL,
  `compare_at_price` decimal(12,2) DEFAULT NULL,
  `cost_price` decimal(12,2) DEFAULT NULL,
  `wholesale_price` decimal(12,2) DEFAULT NULL,
  `tax_class` varchar(255) NOT NULL DEFAULT 'standard',
  `stock_quantity` int(11) NOT NULL DEFAULT 1,
  `track_inventory` tinyint(1) NOT NULL DEFAULT 1,
  `reserved_stock` int(11) NOT NULL DEFAULT 0,
  `low_stock_threshold` int(11) NOT NULL DEFAULT 5,
  `out_of_stock_behavior` varchar(255) NOT NULL DEFAULT 'deny',
  `is_unlimited_stock` tinyint(1) NOT NULL DEFAULT 0,
  `weight` decimal(8,3) DEFAULT NULL,
  `dimensions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dimensions`)),
  `packaging_weight` decimal(8,3) DEFAULT NULL,
  `shipping_class` varchar(255) NOT NULL DEFAULT 'standard',
  `delivery_estimate` varchar(255) DEFAULT NULL,
  `free_shipping_eligible` tinyint(1) NOT NULL DEFAULT 0,
  `currency` varchar(3) NOT NULL DEFAULT 'BDT',
  `status` varchar(255) NOT NULL DEFAULT 'published',
  `product_type` varchar(255) NOT NULL DEFAULT 'physical',
  `visibility` varchar(255) NOT NULL DEFAULT 'public',
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `artisan_id` bigint(20) unsigned DEFAULT NULL,
  `district_id` bigint(20) unsigned DEFAULT NULL,
  `handmade_duration` varchar(255) DEFAULT NULL,
  `traditional_origin` varchar(255) DEFAULT NULL,
  `workshop_name` varchar(255) DEFAULT NULL,
  `care_instructions` text DEFAULT NULL,
  `layout_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`layout_config`)),
  `theme_tokens` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`theme_tokens`)),
  `heritage_specs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`heritage_specs`)),
  `story_meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`story_meta`)),
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_uuid_unique` (`uuid`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_artisan_id_foreign` (`artisan_id`),
  KEY `products_district_id_foreign` (`district_id`),
  KEY `products_title_index` (`title`),
  KEY `products_status_index` (`status`),
  KEY `products_is_featured_index` (`is_featured`),
  KEY `products_barcode_index` (`barcode`),
  KEY `products_internal_code_index` (`internal_code`),
  KEY `products_product_type_index` (`product_type`),
  KEY `products_visibility_index` (`visibility`),
  CONSTRAINT `products_artisan_id_foreign` FOREIGN KEY (`artisan_id`) REFERENCES `artisans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'6a9af93e-df68-47d4-b820-7e3a5dc57dac','the-imperial-sonargaon-muslin-scarf','The Imperial Sonargaon Muslin Scarf','সোনারগাঁ ইম্পেরিয়াল মসলিন স্কার্ফ ও ওড়না',NULL,'Handspun 300-count Phuti Karpas cotton woven on a wooden pit-loom over 220 hours.','৩০০ কাউন্টের হাতে বোনা ফুটি কার্পাস তুলা • শীতলক্ষ্যা অববাহিকার মাস্টারপিস',NULL,'লুপ্ত ঢাকাই মসলিনের ঐতিহ্য ফিরিয়ে আনতে সোনারগাঁয়ের প্রবীণ কারিগরদের শতবর্ষের সাধনায় বোনা এক অনন্য সংগ্রহ।',NULL,NULL,'SRL-MSL-001',NULL,NULL,18500.00,NULL,22000.00,12000.00,NULL,'standard',4,1,0,5,'deny',0,NULL,NULL,NULL,'standard',NULL,0,'BDT','published','physical','public',1,1,1,NULL,NULL,NULL,NULL,'{\"version\":\"1.0\",\"blocks\":[{\"id\":\"hero_01\",\"type\":\"hero_cinematic\",\"order\":1,\"is_active\":true,\"settings\":{\"badge_text\":\"GI Certified Heritage Muslin \\u2022 100% Handspun\"}},{\"id\":\"sticky_buy_02\",\"type\":\"sticky_purchase_box\",\"order\":2,\"is_active\":true,\"settings\":[]},{\"id\":\"artisan_03\",\"type\":\"artisan_spotlight\",\"order\":3,\"is_active\":true,\"settings\":[]},{\"id\":\"gallery_04\",\"type\":\"interactive_gallery\",\"order\":4,\"is_active\":true,\"settings\":[]},{\"id\":\"provenance_05\",\"type\":\"heritage_provenance\",\"order\":5,\"is_active\":true,\"settings\":[]},{\"id\":\"specs_06\",\"type\":\"specs_anatomy\",\"order\":6,\"is_active\":true,\"settings\":[]},{\"id\":\"narrative_07\",\"type\":\"editorial_narrative\",\"order\":7,\"is_active\":true,\"settings\":[]},{\"id\":\"testimonial_08\",\"type\":\"testimonial_editorial\",\"order\":8,\"is_active\":true,\"settings\":[]}]}','{\"mode\":\"warm_heritage\",\"accent_color\":\"#9E4732\",\"bg_color\":\"#FBF9F5\"}','{\"Fiber\":\"100% Handspun Phuti Karpas (Fine Gossamer Cotton)\",\"Thread Count\":\"300s Count pit-loom warp & weft\",\"Weaving Time\":\"220 hours of single-artisan labor\",\"Dye Technique\":\"Unbleached natural ecru, washed in Shitalakshya river water\",\"Weight\":\"28 grams (so light it passes through an heirloom ring)\"}','{\"origin_era\":\"Resurrected 17th Century Mughal Court Weave\",\"preservation_tier\":\"National Living Heritage\"}',1,'2026-11-15 01:37:31','2026-11-15 01:37:31','2026-11-15 03:27:22',NULL),(2,'9af7f7c4-2d57-4752-83d9-dead427be402','heirloom-tangail-mulberry-silk-jamdani','Heirloom Tangail Mulberry Silk Jamdani','ঐতিহ্যবাহী টাঙ্গাইল মালবেরি সিল্ক জামদানি শাড়ি',NULL,'Hand-knotted supplementary weft geometric motifs on pure organic mulberry silk.','খাঁটি রেশম ও জরি সুতায় বোনা নকশিকাঁথা মোটিফ • জিআই সনদপ্রাপ্ত হস্তশিল্প',NULL,'টাঙ্গাইলের তাঁতপল্লীর বংশপরম্পরা কারিগরদের বোনা রাজকীয় জামদানি, যা যুগ যুগ ধরে আভিজাত্যের প্রতীক।',NULL,NULL,'SRL-JMD-002',NULL,NULL,24500.00,NULL,29000.00,16000.00,NULL,'standard',2,1,0,5,'deny',0,NULL,NULL,NULL,'standard',NULL,0,'BDT','published','physical','public',1,2,2,NULL,NULL,NULL,NULL,NULL,'{\"mode\":\"natural_linen\"}','{\"Fabric\":\"Pure Mulberry Silk with Zari supplementary weft\",\"Motif Tradition\":\"Terchi & Panna Hajar (Thousand Emeralds geometry)\",\"Weaving Duration\":\"6 Weeks of dual-weaver synchronization\",\"Loom Style\":\"Generational Tangail Jacquard Pit Loom\"}',NULL,1,'2026-11-15 01:37:31','2026-11-15 01:37:31','2026-11-15 03:27:22',NULL),(3,'d49f4d1d-64f9-4cfc-8b4e-a4a13ab5b37c','hand-chiseled-royal-bogura-brass-kettle','Hand-Chiseled Royal Bogura Brass Kettle','হাতে খোদাইকৃত বগুড়ার রাজকীয় পিতলের কেটলি',NULL,'Sand-cast bell metal, hand-chiseled with floral relief motifs and hand-polished with tamarind.','বগুড়ার প্রাচীন কাঁসারী পাড়ার পিটানো কাঁসা ও পিতলশিল্প',NULL,'করতোয়া অববাহিকার শতবর্ষের পুরোনো কর্মশালায় খাঁটি পিতল পিটিয়ে ও খোদাই করে তৈরি ঐতিহাসিক শৈল্পিক কেটলি।',NULL,NULL,'SRL-BRS-003',NULL,NULL,12800.00,NULL,NULL,7500.00,NULL,'standard',6,1,0,5,'deny',0,NULL,NULL,NULL,'standard',NULL,0,'BDT','published','physical','public',2,3,3,NULL,NULL,NULL,NULL,NULL,NULL,'{\"Metal Alloy\":\"80% Copper, 20% Tin (Pure Heritage Kansa Bell-Metal)\",\"Weight\":\"2.4 kg of solid hand-hammered metal\",\"Thermal Properties\":\"Keeps beverages piping hot for 90 minutes naturally\",\"Finish\":\"Natural mirror polish using tamarind pulp and river silt\"}',NULL,1,'2026-11-15 01:37:31','2026-11-15 01:37:31','2026-11-15 03:27:22',NULL),(4,'d48f16b0-f67f-4bbc-aac6-01bedb0cf5c2','rangpur-royal-shatranji-flatweave-carpet','Rangpur Royal Shatranji Flatweave Carpet',NULL,NULL,'Woven without knotting on a horizontal bamboo frame with natural indigo and madder dyed jute.',NULL,NULL,NULL,NULL,NULL,'SRL-SHT-004',NULL,NULL,15600.00,NULL,NULL,9000.00,NULL,'standard',3,1,0,5,'deny',0,NULL,NULL,NULL,'standard',NULL,0,'BDT','published','physical','public',3,4,4,NULL,NULL,NULL,NULL,NULL,NULL,'{\"Yarn Material\":\"Natural Golden Jute & Unrefined Handspun Cotton\",\"Colorants\":\"Indigofera Tinctoria (Blue), Madder Root (Red), Turmeric (Gold)\",\"Technique\":\"Hand-pressed bamboo pin flatweave (reversible)\",\"Dimensions\":\"180 cm \\u00d7 120 cm (Living Room Statement Size)\"}',NULL,1,'2026-11-15 01:37:31','2026-11-15 01:37:31','2026-11-15 01:37:31',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `return_requests`
--

DROP TABLE IF EXISTS `return_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `return_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `reason` varchar(255) NOT NULL,
  `customer_note` text DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `refund_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `refund_type` varchar(255) NOT NULL DEFAULT 'full',
  `evidence_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`evidence_images`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `return_requests_uuid_unique` (`uuid`),
  KEY `return_requests_order_id_foreign` (`order_id`),
  KEY `return_requests_user_id_foreign` (`user_id`),
  KEY `return_requests_status_index` (`status`),
  CONSTRAINT `return_requests_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `return_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `return_requests`
--

LOCK TABLES `return_requests` WRITE;
/*!40000 ALTER TABLE `return_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `return_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `artisan_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `author_name` varchar(255) NOT NULL,
  `author_location` varchar(255) DEFAULT NULL,
  `rating` tinyint(3) unsigned NOT NULL DEFAULT 5,
  `title` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `admin_reply` text DEFAULT NULL,
  `admin_replied_at` timestamp NULL DEFAULT NULL,
  `photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`photos`)),
  `is_verified_purchase` tinyint(1) NOT NULL DEFAULT 1,
  `likes_count` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reviews_uuid_unique` (`uuid`),
  KEY `reviews_product_id_foreign` (`product_id`),
  KEY `reviews_artisan_id_foreign` (`artisan_id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  CONSTRAINT `reviews_artisan_id_foreign` FOREIGN KEY (`artisan_id`) REFERENCES `artisans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,'64e2f238-addf-4152-b57d-e29f83a2386b',1,1,NULL,'Dr. Eleanor Vance','Kensington, London',5,'A transcendent museum-grade artifact','Unboxing this piece in London was a transcendent experience. The tactile weight, the aroma of natural river washed cotton, and the signed provenance letter from Master Weaver Alimuddin makes it the most prized textile in my entire collection.',NULL,NULL,NULL,1,24,'2026-11-15 01:37:31','2026-11-15 01:37:31',NULL),(2,'e66f53fe-1759-45c7-b5cf-3ffecd182b32',3,3,NULL,'Tariqul Rahman','Toronto, Canada',5,'Acoustic resonance and breathtaking metalwork','The hand-chiseled Bogura brass kettle serves as the crown jewel of our dining room. It holds heat with incredible acoustic resonance.',NULL,NULL,NULL,1,19,'2026-11-15 01:37:31','2026-11-15 01:37:31',NULL);
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `search_logs`
--

DROP TABLE IF EXISTS `search_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `search_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `query` varchar(255) NOT NULL,
  `results_count` int(10) unsigned NOT NULL DEFAULT 0,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_hash` varchar(64) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `clicked_product_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `search_logs_user_id_foreign` (`user_id`),
  KEY `search_logs_clicked_product_id_foreign` (`clicked_product_id`),
  KEY `search_logs_query_index` (`query`),
  KEY `search_logs_created_at_index` (`created_at`),
  CONSTRAINT `search_logs_clicked_product_id_foreign` FOREIGN KEY (`clicked_product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `search_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `search_logs`
--

LOCK TABLES `search_logs` WRITE;
/*!40000 ALTER TABLE `search_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `search_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_metadata`
--

DROP TABLE IF EXISTS `seo_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seo_metadata` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `seoable_type` varchar(255) NOT NULL,
  `seoable_id` bigint(20) unsigned NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `canonical_url` varchar(255) DEFAULT NULL,
  `og_image_url` varchar(255) DEFAULT NULL,
  `schema_type` varchar(255) NOT NULL DEFAULT 'WebPage',
  `robots` varchar(255) NOT NULL DEFAULT 'index,follow',
  `focus_keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`focus_keywords`)),
  `is_indexable` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seo_metadata_seoable_type_seoable_id_unique` (`seoable_type`,`seoable_id`),
  KEY `seo_metadata_seoable_type_seoable_id_index` (`seoable_type`,`seoable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_metadata`
--

LOCK TABLES `seo_metadata` WRITE;
/*!40000 ALTER TABLE `seo_metadata` DISABLE KEYS */;
INSERT INTO `seo_metadata` VALUES (1,'App\\Models\\Product',1,'The Imperial Sonargaon Muslin Scarf — Genuine GI Certified Handloom | Sorolata','Rare handcrafted Bengal Jamdani saree by master artisan. 100% authentic handloom, unbleached organic cotton, museum-grade textile provenance.','http://localhost:8000/products/the-imperial-sonargaon-muslin-scarf',NULL,'Product','index,follow','[\"jamdani saree\",\"bangladesh handloom\",\"sonargaon heritage\",\"gi certified\"]',1,'2026-11-15 01:37:33','2026-11-15 01:37:33');
/*!40000 ALTER TABLE `seo_metadata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('4WqLqybQhgvoiI8Qp4sBFJWZBzGJaSvLxKw8jSNw',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.4391','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRVpqRkhuT05EYzhoUVl2dmVIcklNVFZiYzdjelZjNjhtRXNNMWZUYyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluIjt9czo1OiJlcnJvciI7czoxNjE6IuCmheCnjeCmr+CmvuCmoeCmruCmv+CmqCDgpqHgp43gpq/gpr7gprbgpqzgp4vgprDgp43gpqHgp4cg4Kaq4KeN4Kaw4Kas4KeH4Ka2IOCmleCmsOCmpOCnhyDgpoXgpqjgp4Hgppfgp43gprDgprkg4KaV4Kaw4KeHIOCmhuCml+CnhyDgprLgppfgpofgpqgg4KaV4Kaw4KeB4Kao4KWkIjtzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MTp7aTowO3M6NToiZXJyb3IiO319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9fQ==',1794806135),('6c5nnVKpOo1rwHsEDjJD2sgVEc5jQVAAXyafTv8G',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.4391','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVkdNMFJvbEt1eVRnQUlkODF3YlBuTXdEMkZjeGx6Tll6NTViUmRYWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9idWlsZGVyL3BhZ2UvNiI7czo1OiJyb3V0ZSI7czoxMjoiYnVpbGRlci5wYWdlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1794800388),('bqljN470zcBHKFxSZV3PJRKOCJDrKQax32gBks8w',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.4391','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWkZaTjVmdkl3cEFYVjd6ZHV0ZWppSEJJcHE3bnNVbDRMS21TSEhoTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1794805767),('hefIn7bZFQptLVjWk7W8DNfkNiKdr7zDPVvf919M',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.4391','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWlBvcXZCTEFPNFYzTWxnS2JQT05pdmk4cEd3emRtVnVTMXNFNWRHeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1794806126),('IYM5YE1V2ZnV2OKrBo0IPzJgAWdXotCGjymSlbc3',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.4391','YTozOntzOjY6Il90b2tlbiI7czo0MDoianE1OHNBa2ZZRzNIVVFTdnJQR1MzOG13MWNTeUlJZnp3SlFEZzl6UCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jaGVja291dCI7czo1OiJyb3V0ZSI7czoxNDoiY2hlY2tvdXQuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1794808596),('L65eI5P03mh1rJsnU1LtmIPLHGaEMNjARLDsMz7a',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.4391','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN3NNM2dFc3BGcElGSVBOSlZ3bVdsaTdiVmFybnVjSHpBR1lIZnpMTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wYWdlL3NoYWpnb2otbWFnYXppbmUiO3M6NToicm91dGUiO3M6MTc6ImJ1aWxkZXIucGFnZS5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1794800370),('ML55fXCkm4j8S3Hw0LOkEc3aqt8CfNZkYgr3gQR7',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.4391','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRFkxRU5xR1RjQ0FIVmJIVklKdDBoWnk3TVR5OTB1YU1IZHVoUzJWdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90cmFjay1vcmRlciI7czo1OiJyb3V0ZSI7czoxMToidHJhY2sub3JkZXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1794810777),('OtatJHbXObkqIbE4MN1botaeWTiiF35eIHXiH7Ib',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.4391','YTozOntzOjY6Il90b2tlbiI7czo0MDoiY2cyNllHVkJXRUdKc2hOZGpUM2p6MHdXMlduRFNzaWZkb0VKSGZzZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jaGVja291dCI7czo1OiJyb3V0ZSI7czoxNDoiY2hlY2tvdXQuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1794803330),('PJLetepTn1MNU76gmoraiqbItm16W0Pvcy53S1nx',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.4391','YTozOntzOjY6Il90b2tlbiI7czo0MDoibXVaemVaaHdMZ3lXNUR0NmVBa3djVWNTUTFFb1lhNjNlUEN1Y1NtaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wYWdlL3NoYWpnb2otbWFnYXppbmUiO3M6NToicm91dGUiO3M6MTc6ImJ1aWxkZXIucGFnZS5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1794800457),('r41PGNTgcoGajsfcEbbvxO5fni1T0DtgIVFegwwQ',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiQlpZTDdtbWVoeTY1UVZYSHlQc2k4MW9NMFhGdExmMHp0Z09ISVdaSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kaXNjb3VudHMiO3M6NToicm91dGUiO3M6MjE6ImFkbWluLmRpc2NvdW50cy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6IjRlN2UxYWYxNmM1MTZjZmI5NDE4NWIyMjg2MWI4YmE3MTk0MjlmYTEyNjU1NmI1ODljNzY0ZWM1NTZmZjI3YTMiO3M6NjoibG9jYWxlIjtzOjI6ImVuIjt9',1794828821),('Rl9zgFFJ6VRFlju0jfMojiFsnevLyGkpq75BrPmb',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.7977.167 Safari/537.36 Antigravity/2.32.1','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRWFwSXhhUGMxQW5neEtHREdocktJMUd0MjV5eFR2ZzBzVlZJSndISSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zZXR0aW5ncyI7czo1OiJyb3V0ZSI7czoyMDoiYWRtaW4uc2V0dGluZ3MuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiIzZDAxODE2MDI1NDRmMThlOGUzOWRmMzdiODEzYzQ2NjAwZDgxZmRkYzViMDlmNjYzOGQyYmIzZjQwMmQ5ZGY1Ijt9',1794808725),('sN3Tbett04Ghjcv1cLRgylvbpGXRslOTJHQNdzNR',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.7977.167 Safari/537.36 Antigravity/2.32.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMkZLN3pRbkVXeTM5bjN4T1kxUE1OZ1o0VnRtN1pBV3JBUkRvZmhsYiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3NldHRpbmdzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO319',1794885574),('TAx6rtZ1lJWrUGrzjh3NcSWEEbONj4Oz2fptiLQP',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36','YTo4OntzOjY6Il90b2tlbiI7czo0MDoiQUhYRWhIZDE1TmhtWk43bzZWZGh4bjdVSWZlOTdsek5McWtZWGVnNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YToxOntpOjA7czo3OiJzdWNjZXNzIjt9czozOiJuZXciO2E6MDp7fX1zOjY6ImxvY2FsZSI7czoyOiJlbiI7czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6IjRlN2UxYWYxNmM1MTZjZmI5NDE4NWIyMjg2MWI4YmE3MTk0MjlmYTEyNjU1NmI1ODljNzY0ZWM1NTZmZjI3YTMiO3M6Nzoic3VjY2VzcyI7czoxNzA6IuCmuOCnjeCmrOCmvuCml+CmpOCmriwgTW9tbyEg4Ka44Kaw4Kay4Kak4Ka+IOCmuOCnjeCmn+CngeCmoeCmv+CmkyDgpoXgp43gpq/gpr7gpqHgpq7gpr/gpqgg4Kaq4KeN4Kav4Ka+4Kao4KeH4Kay4KeHIOCmuOCmq+CmsuCmreCmvuCmrOCnhyDgprLgppfgpofgpqgg4Ka54Kef4KeH4Kab4KeH4KWkIjt9',1794809929),('vLhTFb3oMTpULuWMF4XldVal9fZOHXAiZfmDLIC6',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.22621.4391','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaDNMbnhEZGtQaTUySDczc29sNWdTdHlxVWpuSUc2TFpJRDRCcTBPYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9idWlsZGVyL3BhZ2UvNiI7czo1OiJyb3V0ZSI7czoxMjoiYnVpbGRlci5wYWdlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1794800464),('ZFK5Ui9ek0SPJQREohVIqSn0gPfbHcW4DPwhYIOj',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.7977.167 Safari/537.36 Antigravity/2.32.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ3FUMDNQYTJHVjRpUGgzRnFOaFNRQWlkV29IWkRsdFlSRGNuWWxCQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1794885945);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipping_rates`
--

DROP TABLE IF EXISTS `shipping_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shipping_rates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shipping_zone_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `rate_type` varchar(255) NOT NULL DEFAULT 'flat',
  `base_rate` decimal(12,2) NOT NULL,
  `per_kg_rate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `min_weight` decimal(8,3) DEFAULT NULL,
  `max_weight` decimal(8,3) DEFAULT NULL,
  `free_shipping_min_amount` decimal(12,2) DEFAULT NULL,
  `estimated_days` varchar(255) NOT NULL DEFAULT '2-4 Business Days',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shipping_rates_shipping_zone_id_foreign` (`shipping_zone_id`),
  CONSTRAINT `shipping_rates_shipping_zone_id_foreign` FOREIGN KEY (`shipping_zone_id`) REFERENCES `shipping_zones` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipping_rates`
--

LOCK TABLES `shipping_rates` WRITE;
/*!40000 ALTER TABLE `shipping_rates` DISABLE KEYS */;
INSERT INTO `shipping_rates` VALUES (1,1,'Express White-Glove (Next Day)','flat',80.00,0.00,NULL,NULL,3000.00,'1-2 Days',1,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(2,2,'Insured Ground Courier','weight_based',130.00,30.00,NULL,NULL,5000.00,'2-4 Days',1,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(3,3,'DHL Express Air Heritage','weight_based',3500.00,800.00,NULL,NULL,35000.00,'3-5 Business Days',1,'2026-11-15 01:37:31','2026-11-15 01:37:31');
/*!40000 ALTER TABLE `shipping_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipping_zones`
--

DROP TABLE IF EXISTS `shipping_zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shipping_zones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `country_code` varchar(3) NOT NULL DEFAULT 'BD',
  `regions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`regions`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipping_zones`
--

LOCK TABLES `shipping_zones` WRITE;
/*!40000 ALTER TABLE `shipping_zones` DISABLE KEYS */;
INSERT INTO `shipping_zones` VALUES (1,'Dhaka Metropolitan','BD','[\"Dhaka\",\"Gazipur\",\"Narayanganj\"]',1,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(2,'Rest of Bangladesh','BD','[\"Chittagong\",\"Sylhet\",\"Rajshahi\",\"Khulna\",\"Barisal\",\"Rangpur\",\"Mymensingh\",\"Comilla\",\"Bogra\",\"Tangail\"]',1,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(3,'Global Expat Courier (USA, UK, Canada, EU)','INT','[\"USA\",\"UK\",\"Canada\",\"Australia\",\"Germany\",\"France\"]',1,'2026-11-15 01:37:31','2026-11-15 01:37:31');
/*!40000 ALTER TABLE `shipping_zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_visitors`
--

DROP TABLE IF EXISTS `site_visitors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_visitors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip_hash` varchar(64) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `device_type` varchar(20) NOT NULL DEFAULT 'desktop',
  `country_code` varchar(3) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `first_visit_at` timestamp NULL DEFAULT NULL,
  `last_visit_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_visitors_ip_hash_index` (`ip_hash`),
  KEY `site_visitors_device_type_index` (`device_type`),
  KEY `site_visitors_country_code_index` (`country_code`),
  KEY `site_visitors_last_visit_at_index` (`last_visit_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_visitors`
--

LOCK TABLES `site_visitors` WRITE;
/*!40000 ALTER TABLE `site_visitors` DISABLE KEYS */;
/*!40000 ALTER TABLE `site_visitors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_adjustments`
--

DROP TABLE IF EXISTS `stock_adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_adjustments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `variant_id` bigint(20) unsigned DEFAULT NULL,
  `delta` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_adjustments_warehouse_id_foreign` (`warehouse_id`),
  KEY `stock_adjustments_product_id_foreign` (`product_id`),
  KEY `stock_adjustments_variant_id_foreign` (`variant_id`),
  KEY `stock_adjustments_reference_type_reference_id_index` (`reference_type`,`reference_id`),
  KEY `stock_adjustments_user_id_foreign` (`user_id`),
  KEY `stock_adjustments_reason_index` (`reason`),
  CONSTRAINT `stock_adjustments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_adjustments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_adjustments_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_adjustments_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_adjustments`
--

LOCK TABLES `stock_adjustments` WRITE;
/*!40000 ALTER TABLE `stock_adjustments` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_adjustments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stories`
--

DROP TABLE IF EXISTS `stories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_bn` varchar(255) DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `excerpt_bn` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `content_bn` longtext DEFAULT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'Heritage & Craft',
  `category_bn` varchar(255) DEFAULT NULL,
  `author_name` varchar(255) NOT NULL DEFAULT 'Curator Desk',
  `author_name_bn` varchar(255) DEFAULT NULL,
  `hero_image` varchar(255) DEFAULT NULL,
  `reading_time` int(10) unsigned NOT NULL DEFAULT 5,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stories_uuid_unique` (`uuid`),
  UNIQUE KEY `stories_slug_unique` (`slug`),
  KEY `stories_title_index` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stories`
--

LOCK TABLES `stories` WRITE;
/*!40000 ALTER TABLE `stories` DISABLE KEYS */;
INSERT INTO `stories` VALUES (1,'05733021-b30f-4524-9145-484ec1f08f90','resurrecting-phuti-karpas-the-gossamer-muslin-of-bengal','Resurrecting Phuti Karpas: The Gossamer Muslin of Bengal',NULL,'How a dedicated collective of researchers and generational pit-loom weavers brought the 300-count imperial textile back from extinction.',NULL,'For nearly two centuries, imperial Dhakai Muslin was mourned as a lost wonder of civilization. Spun from the elusive Phuti Karpas cotton plant that flourished exclusively along the misty morning banks of the Shitalakshya River, its threads were so fine that an entire fifty-meter sari could pass through an ancestral ring...',NULL,'Loom & Heritage',NULL,'Dr. Eleanor Vance & Chief Curator',NULL,'https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1600&auto=format&fit=crop',7,1,'2026-11-15 01:37:31','2026-11-15 01:37:31','2026-11-15 01:37:31',NULL),(2,'abc79cec-bfac-435c-92bd-441151670278','the-sacred-geometry-of-jamdani-motifs','The Sacred Geometry of Jamdani Motifs: Memory Over Stencils',NULL,'Master weavers in Delduar and Sonargaon memorize centuries of floral and diamond symmetries without ever sketching them on paper.',NULL,'Jamdani is not simply textile decoration; it is spoken mathematics translated into thread tension. The master weaver sings rhythmic cadences called boles to the apprentice seated beside the wooden loom...',NULL,'Weaving Philosophy',NULL,'Curator Desk',NULL,'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=1600&auto=format&fit=crop',5,1,'2026-11-15 01:37:31','2026-11-15 01:37:31','2026-11-15 01:37:31',NULL),(3,'e985c357-76e2-4e7f-8728-0eabbd56c207','revival-of-sonargaon-muslin-and-jamdani','The Revival of Ethereal Jamdani & Sonargaon Muslin','সোনারগাঁয়ের লুপ্ত মসলিন ও জামদানির পুনর্জন্ম','An archival voyage into the humid river basins of Bengal, exploring how master artisans are reviving 300-count handspun muslin and heirloom silk Jamdanis.','শীতলক্ষ্যা ও যমুনা নদীর অববাহিকায় এক কালজয়ী অভিযাত্রা—কীভাবে বাংলার কারিগররা ফিরিয়ে আনছেন ৩০০ কাউন্টের বোনা বাতাস মসলিন ও ঐতিহ্যবাহী রেশম জামদানি।','The morning mist over the Shitalakshya River has long been the silent partner of Bengal’s finest weavers. Here in the historical capital of Sonargaon, spinning fine cotton requires exact atmospheric humidity—a secret preserved across centuries by artisanal guilds.\n\nFor generations, travelers from Constantinople to Paris marvelled at fabric so light it was christened *Baft-Hawa*—woven air. Each scarf represents more than three months of patient craftsmanship on traditional pit looms.\n\n[product:the-imperial-sonargaon-muslin-scarf]\n\nMoving further northwest toward the fertile banks of the Jamuna, the rhythm of Tangail’s handloom villages shifts to Mulberry silk. Unlike machine-stamped imitations, authentic Jamdani motifs are woven by hand using bamboo needles directly into the warp and weft.\n\nThe floral jaal and paisley borders are never drawn on paper first; they are preserved in the living memory of the weaver, passed down from father to son like an unwritten folk symphony.\n\n[product:heirloom-tangail-mulberry-silk-jamdani]\n\nWhen you drape one of these heirloom creations, you become the custodian of a living heritage—ensuring that the fires of Bengal\'s traditional looms burn bright for generations yet unborn.','শীতলক্ষ্যা নদীর সকালের কুয়াশা আর মৃদু বাষ্পীয় আর্দ্রতা শতাব্দীর পর শতাব্দী ধরে বাংলার সূক্ষ্ম তাঁতশিল্পীদের প্রধান সহায়। মেঘনা ও শীতলক্ষ্যা অববাহিকার সোনারগাঁয়ে মিহি সুতা কাটার জন্য প্রয়োজন হয় নিখুঁত প্রাকৃতিক আর্দ্রতা—যা বাংলার তাঁতিদের বংশপরম্পরার এক গোপন বিজ্ঞান।\n\nরোমান সম্রাটদের দরবার থেকে প্যারিসের ফ্যাশন হাউস—সারা বিশ্ব ঢাকাই মসলিনকে একবাক্যে আখ্যা দিয়েছিল *\'বাফত-হাওয়া\'* বা বোনা বাতাস। ৩০০ কাউন্টের ফুটি কার্পাস তুলা থেকে একেকটি মসলিন ওড়না তৈরি করতে নিবেদিতপ্রাণ কারিগরের সময় লাগে প্রায় তিন থেকে চার মাস।\n\n[product:the-imperial-sonargaon-muslin-scarf]\n\nউত্তর-পশ্চিমের যমুনা নদীর অববাহিকায় গেলে পাওয়া যায় টাঙ্গাইলের রেশমি জামদানির মোহনীয় ছন্দ। বর্তমানের সিন্থেটিক কিংবা পাওয়ারলুমের কৃত্রিমতার ভিড়ে খাঁটি মালবেরি রেশম ও জরি সুতায় বোনা জামদানির রূপ সম্পূর্ণ আলাদা। এখানে বাঁশের সূক্ষ্ম কাঠি দিয়ে প্রতিটি মোটিফ সরাসরি কাপড়ের বুননে ফুটিয়ে তোলা হয়।\n\nতাঁতিরা কোনো কাগজে নকশা আঁকেন না; প্রতিটি কলকা, ময়ূরপঙ্খী এবং জলতরঙ্গ নকশা গেঁথে থাকে তাদের মনের ভেতর, যা প্রজন্ম থেকে প্রজন্মে ছড়িয়ে যায় এক অমর বাউল গানের মতো।\n\n[product:heirloom-tangail-mulberry-silk-jamdani]\n\nযখন আপনি একটি খাঁটি জামদানি বা মসলিন সংগ্রহ করেন, আপনি কেবল একটি পোশাক পরছেন না—আপনি বাঁচিয়ে রাখছেন একটি প্রাচীন ঐতিহ্য, একজন কারিগরের আত্মসম্মান এবং বাংলার চিরন্তন শিল্পধারাকে।','Heritage & Textile Craft','বস্ত্রশিল্প ও ঐতিহ্য','Kazi Farhan (Master Curator)','কাজী ফারহান (প্রধান কিউরেটর)','https://images.unsplash.com/photo-1606744888344-498238f01761?q=80&w=1200',4,1,'2026-11-15 03:27:22','2026-11-15 03:27:22','2026-11-15 03:27:22',NULL);
/*!40000 ALTER TABLE `stories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_product`
--

DROP TABLE IF EXISTS `story_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `story_product` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `story_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `context_note_en` varchar(255) DEFAULT NULL,
  `context_note_bn` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `story_product_story_id_product_id_unique` (`story_id`,`product_id`),
  KEY `story_product_product_id_foreign` (`product_id`),
  CONSTRAINT `story_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `story_product_story_id_foreign` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `story_product`
--

LOCK TABLES `story_product` WRITE;
/*!40000 ALTER TABLE `story_product` DISABLE KEYS */;
INSERT INTO `story_product` VALUES (1,3,1,1,'Featured in Muslin Weaving chapter','সোনারগাঁ মসলিন পরিচ্ছেদে উল্লেখিত','2026-11-15 03:27:22','2026-11-15 03:27:22'),(2,3,2,2,'Featured in Jamdani Loom chapter','টাঙ্গাইল জামদানি পরিচ্ছেদে উল্লেখিত','2026-11-15 03:27:22','2026-11-15 03:27:22');
/*!40000 ALTER TABLE `story_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taggables`
--

DROP TABLE IF EXISTS `taggables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taggables` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` bigint(20) unsigned NOT NULL,
  `taggable_type` varchar(255) NOT NULL,
  `taggable_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `taggables_unique` (`tag_id`,`taggable_id`,`taggable_type`),
  KEY `taggables_taggable_type_taggable_id_index` (`taggable_type`,`taggable_id`),
  CONSTRAINT `taggables_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taggables`
--

LOCK TABLES `taggables` WRITE;
/*!40000 ALTER TABLE `taggables` DISABLE KEYS */;
/*!40000 ALTER TABLE `taggables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'product',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_name_unique` (`name`),
  UNIQUE KEY `tags_slug_unique` (`slug`),
  KEY `tags_type_index` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_classes`
--

DROP TABLE IF EXISTS `tax_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tax_classes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_compound` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_classes`
--

LOCK TABLES `tax_classes` WRITE;
/*!40000 ALTER TABLE `tax_classes` DISABLE KEYS */;
INSERT INTO `tax_classes` VALUES (1,'Standard VAT',5.00,0,1,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(2,'GI Certified Heritage Exemption',0.00,0,1,'2026-11-15 01:37:31','2026-11-15 01:37:31'),(3,'Export Zero-Rated',0.00,0,1,'2026-11-15 01:37:31','2026-11-15 01:37:31');
/*!40000 ALTER TABLE `tax_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `url_redirects`
--

DROP TABLE IF EXISTS `url_redirects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `url_redirects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `source_url` varchar(255) NOT NULL,
  `destination_url` varchar(255) NOT NULL,
  `status_code` smallint(5) unsigned NOT NULL DEFAULT 301,
  `hits_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_redirects_source_url_unique` (`source_url`),
  KEY `url_redirects_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `url_redirects`
--

LOCK TABLES `url_redirects` WRITE;
/*!40000 ALTER TABLE `url_redirects` DISABLE KEYS */;
/*!40000 ALTER TABLE `url_redirects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_addresses`
--

DROP TABLE IF EXISTS `user_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT 'Home',
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address_line_1` varchar(255) NOT NULL,
  `address_line_2` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `district_id` bigint(20) unsigned DEFAULT NULL,
  `division` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `country_code` varchar(3) NOT NULL DEFAULT 'BD',
  `is_default_shipping` tinyint(1) NOT NULL DEFAULT 0,
  `is_default_billing` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_addresses_user_id_foreign` (`user_id`),
  KEY `user_addresses_district_id_foreign` (`district_id`),
  CONSTRAINT `user_addresses_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `user_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_addresses`
--

LOCK TABLES `user_addresses` WRITE;
/*!40000 ALTER TABLE `user_addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `avatar_url` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Momo','admin@sorolata.com','super_admin',NULL,'+8801700000001',NULL,'$2y$12$vCWh9GtkgvWWuytKKpKRXOpzrObi4pJ.oHj2/Zfd5QRIM8bj/eLee','x1wpl6zoFTOIL7RwyWeSnOndeE7bFjhYQnGrFsOsAhDDOAzZpBPOx16nXOIS','2026-11-15 01:37:32','2026-11-15 23:19:15'),(2,'Tasnima Rahman (Guild Director)','manager@sorolata.com','manager',NULL,'+8801700000002',NULL,'$2y$12$.pOIJb1wRYCGR4RH2Jci9OerugdAeaiskd89Ug7Zs1/w60D1MHi2S',NULL,'2026-11-15 01:37:33','2026-11-15 01:37:33');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouse_stocks`
--

DROP TABLE IF EXISTS `warehouse_stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warehouse_stocks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `variant_id` bigint(20) unsigned DEFAULT NULL,
  `quantity_on_hand` int(11) NOT NULL DEFAULT 0,
  `quantity_reserved` int(11) NOT NULL DEFAULT 0,
  `safety_stock` int(11) NOT NULL DEFAULT 5,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wh_prod_var_unique` (`warehouse_id`,`product_id`,`variant_id`),
  KEY `warehouse_stocks_product_id_foreign` (`product_id`),
  KEY `warehouse_stocks_variant_id_foreign` (`variant_id`),
  CONSTRAINT `warehouse_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `warehouse_stocks_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `warehouse_stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouse_stocks`
--

LOCK TABLES `warehouse_stocks` WRITE;
/*!40000 ALTER TABLE `warehouse_stocks` DISABLE KEYS */;
INSERT INTO `warehouse_stocks` VALUES (1,1,1,NULL,5,1,2,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(2,2,1,NULL,3,0,1,'2026-11-15 01:37:33','2026-11-15 01:37:33');
/*!40000 ALTER TABLE `warehouse_stocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouses`
--

DROP TABLE IF EXISTS `warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warehouses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `district_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `warehouses_uuid_unique` (`uuid`),
  UNIQUE KEY `warehouses_code_unique` (`code`),
  KEY `warehouses_district_id_foreign` (`district_id`),
  KEY `warehouses_name_index` (`name`),
  KEY `warehouses_is_active_index` (`is_active`),
  CONSTRAINT `warehouses_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouses`
--

LOCK TABLES `warehouses` WRITE;
/*!40000 ALTER TABLE `warehouses` DISABLE KEYS */;
INSERT INTO `warehouses` VALUES (1,'514e0404-a242-4113-bf80-6acf74f6c7b8','Dhaka Central Heritage Sanctuary Depot','WH-DHK-01','Mohiuddin Ahmed','+8801711000001','logistics@sorolata.com','Road 11, House 42, Block D, Banani, Dhaka 1213',1,1,1,'2026-11-15 01:37:33','2026-11-15 01:37:33'),(2,'8d9168c0-80fe-48ed-807a-30c544d9bf3e','Shitalakshya River Loom Clearing Depot','WH-NRG-02','Zakir Hossain','+8801711000002','sonargaon-depot@sorolata.com','Near Folk Art Museum, Mograpara, Sonargaon, Narayanganj',1,1,0,'2026-11-15 01:37:33','2026-11-15 01:37:33');
/*!40000 ALTER TABLE `warehouses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wishlists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlists_user_id_product_id_unique` (`user_id`,`product_id`),
  KEY `wishlists_product_id_foreign` (`product_id`),
  KEY `wishlists_session_id_index` (`session_id`),
  CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlists`
--

LOCK TABLES `wishlists` WRITE;
/*!40000 ALTER TABLE `wishlists` DISABLE KEYS */;
/*!40000 ALTER TABLE `wishlists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workshops`
--

DROP TABLE IF EXISTS `workshops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workshops` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `artisan_id` bigint(20) unsigned DEFAULT NULL,
  `district_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `craft_specialty` varchar(255) DEFAULT NULL,
  `founded_year` smallint(5) unsigned DEFAULT NULL,
  `workers_count` int(10) unsigned NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `workshops_uuid_unique` (`uuid`),
  KEY `workshops_artisan_id_foreign` (`artisan_id`),
  KEY `workshops_district_id_foreign` (`district_id`),
  KEY `workshops_name_index` (`name`),
  KEY `workshops_is_active_index` (`is_active`),
  CONSTRAINT `workshops_artisan_id_foreign` FOREIGN KEY (`artisan_id`) REFERENCES `artisans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `workshops_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workshops`
--

LOCK TABLES `workshops` WRITE;
/*!40000 ALTER TABLE `workshops` DISABLE KEYS */;
INSERT INTO `workshops` VALUES (1,'4daf41fa-a290-4997-abbe-d740109e545d',1,1,'Sonargaon Heritage Loom Collective','Mograpara, Sonargaon, Narayanganj','100+ Count Royal Jamdani Weaving',1978,18,'Three generations of master weavers preserving geometric river motifs alongside the Shitalakshya River.','[\"\\/images\\/workshops\\/sonargaon-atelier-01.jpg\"]',1,'2026-11-15 01:37:33','2026-11-15 01:37:33',NULL);
/*!40000 ALTER TABLE `workshops` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-17 10:36:42

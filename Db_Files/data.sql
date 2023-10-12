-- MySQL dump 10.13  Distrib 8.0.34, for Linux (x86_64)
--
-- Host: localhost    Database: shipmentApp
-- ------------------------------------------------------
-- Server version	8.0.34-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `carrier_info`
--

DROP TABLE IF EXISTS `carrier_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carrier_info` (
  `carrier_id` varchar(25) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `verified` int NOT NULL,
  `add_date` date NOT NULL,
  PRIMARY KEY (`carrier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrier_info`
--

LOCK TABLES `carrier_info` WRITE;
/*!40000 ALTER TABLE `carrier_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `carrier_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipment`
--

DROP TABLE IF EXISTS `shipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipment` (
  `shipment_id` varchar(25) NOT NULL,
  `tracking_id` varchar(25) DEFAULT NULL,
  `shipment_status` varchar(10) NOT NULL,
  `shipment_type` varchar(10) NOT NULL,
  `shipment_weight` decimal(6,2) NOT NULL,
  `shipment_delivery_method` varchar(10) NOT NULL,
  `shipment_cost` int NOT NULL,
  `content_type` varchar(10) NOT NULL,
  `payment_type` varchar(8) NOT NULL,
  `additional_information` varchar(250) NOT NULL,
  `event_id` int DEFAULT NULL,
  `sender_name` varchar(255) NOT NULL,
  `receiver_name` varchar(255) NOT NULL,
  `sender_phone` varchar(10) NOT NULL,
  `receiver_phone` varchar(10) NOT NULL,
  `sender_email` varchar(10) DEFAULT NULL,
  `receiver_email` varchar(10) DEFAULT NULL,
  `sender_address` varchar(255) DEFAULT NULL,
  `receiver_address` varchar(255) DEFAULT NULL,
  `sender_city` varchar(100) DEFAULT NULL,
  `receiver_city` varchar(100) DEFAULT NULL,
  `sender_country` varchar(100) DEFAULT NULL,
  `receiver_country` varchar(100) DEFAULT NULL,
  `sender_pincode` varchar(6) DEFAULT NULL,
  `receiver_pincode` varchar(6) DEFAULT NULL,
  `receiver_add_info_landmark` varchar(100) DEFAULT NULL,
  `sender_state` varchar(100) DEFAULT NULL,
  `receiver_state` varchar(100) DEFAULT NULL,
  `booking_date` date NOT NULL,
  `carrier_id` int NOT NULL,
  `create_by_admin_id` int NOT NULL,
  PRIMARY KEY (`shipment_id`),
  UNIQUE KEY `tracking_id` (`tracking_id`),
  UNIQUE KEY `event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipment`
--

LOCK TABLES `shipment` WRITE;
/*!40000 ALTER TABLE `shipment` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipment_events`
--

DROP TABLE IF EXISTS `shipment_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipment_events` (
  `event_id` int NOT NULL,
  `date` date NOT NULL,
  `time` timestamp NOT NULL,
  `location` varchar(100) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  `facility_id` int NOT NULL,
  `event_by_user_id` int NOT NULL,
  `activity` varchar(250) NOT NULL,
  `shipment_id` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  KEY `shipment_id` (`shipment_id`),
  CONSTRAINT `shipment_events_ibfk_1` FOREIGN KEY (`shipment_id`) REFERENCES `shipment` (`shipment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipment_events`
--

LOCK TABLES `shipment_events` WRITE;
/*!40000 ALTER TABLE `shipment_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipment_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipment_facility`
--

DROP TABLE IF EXISTS `shipment_facility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipment_facility` (
  `facility_id` varchar(25) NOT NULL,
  `facility_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `entry_create_date` date NOT NULL,
  PRIMARY KEY (`facility_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipment_facility`
--

LOCK TABLES `shipment_facility` WRITE;
/*!40000 ALTER TABLE `shipment_facility` DISABLE KEYS */;
/*!40000 ALTER TABLE `shipment_facility` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-09-27  1:39:27

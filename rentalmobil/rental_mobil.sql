-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: rental_mobil
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `no_plat` varchar(20) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `lama_hari` int(11) NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `no_plat` (`no_plat`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`no_plat`) REFERENCES `cars` (`no_plat`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (4,17,'1202122810','2025-10-06',3,15000000.00,'rejected','2025-10-06 01:03:39'),(5,17,'1202122810','2025-10-06',4,20000000.00,'approved','2025-10-06 01:05:02'),(7,20,'AG-2GA2-BCF','2025-10-10',1,1000000.00,'rejected','2025-10-10 01:28:58'),(8,21,'AG-2GA2-BCF','2025-10-11',2,2000000.00,'approved','2025-10-11 04:05:02'),(9,21,'AG-2GA2-BCF','2025-10-11',1,1000000.00,'approved','2025-10-11 04:16:37'),(10,21,'AG-2GA2-BCF','2025-10-11',1,1000000.00,'pending','2025-10-11 04:19:52'),(11,21,'AG-2GA2-BCF','2025-10-11',12,12000000.00,'approved','2025-10-11 04:21:13'),(12,21,'AG-2GA2-BCF','2025-10-11',3,3000000.00,'rejected','2025-10-11 04:26:46'),(13,21,'AG-2GA2-BCF','2025-10-11',2,2000000.00,'approved','2025-10-11 05:30:28'),(14,21,'AG-2B5A-RCF','2025-10-11',3,1500000.00,'approved','2025-10-11 13:28:22'),(15,21,'1202122810','2025-10-11',1,500000.00,'rejected','2025-10-11 13:29:44'),(16,3,'AG-2GA2-BCF','2025-10-12',1,1000000.00,'rejected','2025-10-12 03:55:25'),(17,22,'AG-2GA2-BCF','2025-10-12',3,3000000.00,'rejected','2025-10-12 06:39:36'),(18,23,'AG-2GA2-BCF','2025-10-12',14,14000000.00,'rejected','2025-10-12 12:43:01');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cars`
--

DROP TABLE IF EXISTS `cars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cars` (
  `no_plat` varchar(20) NOT NULL,
  `merk` varchar(100) NOT NULL,
  `tipe` varchar(100) NOT NULL,
  `tahun` int(11) NOT NULL,
  `harga_per_hari` decimal(10,2) NOT NULL,
  `status` enum('available','booked') DEFAULT 'available',
  `last_service` date DEFAULT NULL,
  `pajak_status` date DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  PRIMARY KEY (`no_plat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cars`
--

LOCK TABLES `cars` WRITE;
/*!40000 ALTER TABLE `cars` DISABLE KEYS */;
INSERT INTO `cars` VALUES ('1202122810','BRIO','MATIC',2024,500000.00,'available','2025-10-11','2025-10-11','1759713209_e2b6e305fc.jpeg','EMONNN'),('AG-2B5A-RCF','ERTIGA','MANUAL',2021,500000.00,'booked','2025-10-11','2025-10-11','1759713132_a718a1bc2c.jpg','WARNA KUNING MUAT 5 ORANG'),('AG-2GA2-BCF','BMW','MANUAL',2024,1000000.00,'available','2025-10-11','2025-10-11','1759713292_28685b8f96.jpeg','WARNA KUNING MUAT 4 ORANG');
/*!40000 ALTER TABLE `cars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `ktp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin',NULL,NULL,NULL,'$2y$10$r/w5WPb3Rf9EIUdt.3fpcOt6BzGjFv51IjlX4qIBtVKfdg6MqEeVG','user',NULL),(2,'q',NULL,NULL,NULL,'$2y$10$p76ilBeUDUd8qDY5rCLaC.yFJyn5VaSSYpoBUozSJ0g7vDmky1QRK','admin',NULL),(3,'2',NULL,NULL,NULL,'$2y$10$zPzx2VM4Wf9bjWtx8.a75esukFMMP44K8waB.BZ6oM1U8HDge8GZG','user',NULL),(4,'emon',NULL,NULL,NULL,'$2y$10$OrNWha8jJy/t9SbBQ.JYOOtnQWaP86Xav9q6IAwPX0q3LGfXHm8wm','admin',NULL),(5,'mon',NULL,NULL,NULL,'$2y$10$9uN5sNEaogTem4gHT6JkkeJao6mz4JfHvCuy.MV3rgJsziYu86nmq','user',NULL),(6,'brian',NULL,NULL,NULL,'$2y$10$tZexjP8dtNfxJv479PYL8.9xfB9Tsh2uURRPCO3Lbp9b4mYXkWEVe','user',NULL),(7,'1',NULL,NULL,NULL,'$2y$10$rtyA000/v4hMzP/k0D9OcOjtXk8yGD44ne88T1evylSkmtXL6MEGq','user',NULL),(8,'3',NULL,NULL,NULL,'$2y$10$X6eaawX9Xjn47f9U/iD9guXtxBLDFVzeXbxat4yx5.vU5a4E.0gx.','user',NULL),(9,'4',NULL,NULL,NULL,'$2y$10$uSnmDroergHtPbT6BXOdluvAj9ZuM9KWsH.9uuVIJ.ij9DCoV0Egq','user',NULL),(10,'5',NULL,NULL,NULL,'$2y$10$SjSOQ5CH3C1YUsBcQy8BDe5Ab69RoVJw2cxVog0FwIYx4qYiwZ44q','user',NULL),(11,'6',NULL,NULL,NULL,'$2y$10$8gn4sjSlnIvZY3V9N.ojAeSHPbYXrYhRfhpN6NBDEygDslxKW9ndu','user',NULL),(12,'7',NULL,NULL,NULL,'$2y$10$wGLnTCCxcc.Cnlv7d0CHH.WSVr9XPa4m.cfyFeE4o5G27JFlIN8F.','user',NULL),(13,'8',NULL,NULL,NULL,'$2y$10$iS4QCh4Tt1ZfPTPjeSVqX.ZLUutLbm69nA4I0tS4t8cVFybHQuGRu','user',NULL),(14,'hama',NULL,NULL,NULL,'$2y$10$27rYpTwL8kjbzNDIsKxZFOVz6HFxb5hYt6UmCZNgmryVx5EsvHU7C','user',NULL),(15,'attakondom',NULL,NULL,NULL,'$2y$10$mAXc69rxD/A6JimBcuWkcuPE5azr025/tuXgFIS9X3v2g5j.SZDwm','user',NULL),(16,'i',NULL,NULL,NULL,'$2y$10$Jrqx5FjoAoBppG4pR.0eiuFexxBOMTZTEA6P7piFXWbjzYvaK4xt2','user',NULL),(17,'10','mon123@gmail.com','121213331','31313','$2y$10$kOd7ZGNALExxzzW7OTVRuemmEytHA8itAhYR1SbfqmtNoU/at6QfO','user','1759061491_ertiga-4-d75d-55f4.jpg'),(18,'11','iu@gmail.com','1212121221','addad','$2y$10$0IEWxYccNQsfcnJv/b484ecgFz3Ec27TZT5tSV6K1BLGu5tQvVpHe','user','1759063683_1010019.jpg'),(19,'Ahmad Abi Aji','emon4@gmail.com','08123875812','pakel sambitan tulungagung','$2y$10$cHZofOj7uzzWAHjJ1NlkkeVKpJAFY8dY4sdHkFy1Y9.9KZOZmTi62','user','1759108469_1351402.png'),(20,'emon123','emonz123@gmail.com','085855273945','Sambitan, Pakel','$2y$10$Uo5TloRk4gGZJlz1z56Jc.1j9/pOukaP15dMKo8DeS0d2KEeONGym','user','1759713515_1010019.jpg'),(21,'fattas','fatta@gmail.com','08123875812','pelem','$2y$10$ixo9bmC1JkP4TS6exgsxXeE3tFLatvxmjY0N6CohD.pm/Tq8DFiEO','user','1760152439_WIN_20250908_08_56_09_Pro.jpg'),(22,'fatahilla','attasiregar58162@gmail.com','08123875812','pelem','$2y$10$yqqk99id5Bh30tHwY2HpY.AN5QsYwq4GUIFzMzsilCAVCQeaRQeD6','user','1760250595_8242860806_115121826943417_1752941693921.png'),(23,'attai','attai@gmail.com','085855273945','jl.pelem campur','$2y$10$MD1d7oVkY9KofWx2trePJe8kTje5P8ZPfornDUDJx0pbaFjJ0Ve5.','user','1760272947_8242860806_115121826943417_1752941693921.png');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-12 20:35:08

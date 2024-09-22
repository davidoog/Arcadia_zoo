-- MySQL dump 10.13  Distrib 8.0.38, for macos14 (x86_64)
--
-- Host: localhost    Database: arcadia_db
-- ------------------------------------------------------
-- Server version	9.0.1

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
-- Table structure for table `animals`
--

DROP TABLE IF EXISTS `animals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `animals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `race` varchar(255) NOT NULL,
  `habitat` varchar(255) NOT NULL,
  `health` varchar(255) NOT NULL,
  `food` varchar(255) NOT NULL,
  `food_quantity` float NOT NULL,
  `last_checkup` date NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `food_unit` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `animals`
--

LOCK TABLES `animals` WRITE;
/*!40000 ALTER TABLE `animals` DISABLE KEYS */;
INSERT INTO `animals` VALUES (12,'Simba','Lion d\'Afrique','savane','En pleine forme','Viande crue',5,'2024-09-16','image/lion-simba.png','kg'),(13,'Mara','Lion d\'Afrique','savane','En pleine forme, blessure légère à la patte ','Viande crue',4,'2024-09-16','image/lion-mara.png','kg'),(15,'Savannah','Girafe du Kenya','savane','Légèrement fatigué ','Feuilles et fruits',9,'2024-09-16','image/girafe-savannah.png','kg'),(16,'Zuri','Guépard','savane','Légère blessure à la cuisse gauche','Viande crue',3,'2024-09-16','image/guepard-zuri.png','kg'),(17,'Amara','Éléphant d\'Afrique','savane','En pleine forme','Herbe, fruits et légumes',50,'2024-09-16','image/elephant-amara.png','kg'),(18,'Kibo','Éléphant d\'Afrique','savane','Corne gauche à surveiller','Herbe, fruits et légumes',53,'2024-09-16','image/elephant-kibo.png','kg'),(19,'Tembo','Éléphant d\'Afrique','savane','En pleine forme','Herbe, fruits et légumes',10,'2024-09-16','image/elephant-tembo.png','kg'),(20,'Kifaru','Rhinocéros blanc','savane','En pleine forme','Herbe et foin',30,'2024-09-16','image/rhinoceros-kifaru.png','kg'),(21,'Thabo','Rhinocéros blanc','savane','En pleine forme','Herbe et foin',28,'2024-09-16','image/rhinoceros-thabo.png','kg'),(22,'Rio','Perroquet Ara rouge','jungle','En pleine forme','Graines et fruits',200,'2024-09-16','image/perroquet-rio.png','g'),(23,'Coco','Perroquet Ara vert','jungle','En forme','Graines et fruits',250,'2024-09-16','image/perroquet-coco.png','kg'),(24,'Rafiki','Mandrill','jungle','En pleine forme','Fruits, feuilles, insectes',2.5,'2024-09-16','image/singe-rafiki.png','kg'),(25,'Ban','Mandrill','jungle','En pleine forme','Fruits, feuilles, insectes',2.7,'2024-09-16','image/singe-ban.png','kg'),(26,'Khali','Tigre du Bengale','jungle','Fatigué','Viande crue',8,'2024-09-16','image/tigre-khali.png','kg'),(27,'Boranne','Tigre du Bengale','jungle','En pleine forme','Viande crue',7.5,'2024-09-16','image/tigre-boranne.png','kg'),(28,'Bayou','Alligator d\'Amérique','marais','légère blessure patte droite','Poissons, petits mammifères',5,'2024-09-16','image/alligator-bayou-marais.png','kg'),(29,'Delta','Alligator d\'Amérique','marais','En pleine forme','Poissons, petits mammifères',7,'2024-09-16','image/alligator-delta-marais.png','kg'),(30,'Ruby','Tortue de Floride','marais','En pleine forme','Plantes aquatiques, petits poissons',150,'2024-09-16','image/tortue-ruby-marais.png','g'),(31,'Hérald','Tortue de Floride','marais','Légèrement fatigué ','Plantes aquatiques, petits poissons',300,'2024-09-16','image/tortue-herald-marais.png','g'),(33,'test 1','Alligator','savane','En pleine forme','zddzdqd',2,'2024-09-22','uploads/alligator-bayou-marais.png','kg'),(34,'test2','Éléphant d\'Afrique','savane','Fatigué','feuille',2,'2024-09-22','uploads/elephant-amara.png','kg'),(35,'test3','elephant','savane','En pleine forme','feuille',2,'2024-09-22','uploads/elephant-kibo.png','kg');
/*!40000 ALTER TABLE `animals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `habitats`
--

DROP TABLE IF EXISTS `habitats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `habitats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_habitat_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `habitats`
--

LOCK TABLES `habitats` WRITE;
/*!40000 ALTER TABLE `habitats` DISABLE KEYS */;
INSERT INTO `habitats` VALUES (6,'La savane ','zfzfazf','uploads/savane-card.png'),(8,'La jungle tropicale','Jungle dense',NULL),(9,'Les marais','Zone humide',NULL);
/*!40000 ALTER TABLE `habitats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description1` text,
  `subtitle2` varchar(255) DEFAULT NULL,
  `description2` text,
  `subtitle3` varchar(255) DEFAULT NULL,
  `description3` text,
  `subtitle1` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (3,'Restauration','Profitez d’une cuisine raffinée avec vue panoramique sur le lac. Ce restaurant vous propose une sélection de plats gastronomiques préparés avec des ingrédients frais et locaux.','La Table des Explorateurs',' Idéal pour les familles, ce buffet varié offre des options pour tous les goûts, y compris des plats végétariens et sans gluten. Parfait pour une pause-déjeuner équilibrée et savoureuse.','Le Bistrot Sauvage','Dans une ambiance décontractée, savourez des plats locaux et des snacks rapides. Une halte idéale pour recharger vos batteries avant de poursuivre votre visite du zoo.','Le Pavillion des Saveurs'),(4,'Visite des Habitats','Accompagné d’un guide expert, explorez les différents habitats du zoo et apprenez-en plus sur les espèces qui y vivent.','Rencontres Éducatives','Nos guides passionnés partageront des anecdotes fascinantes et des informations sur la conservation des animaux, rendant votre visite à la fois éducative et divertissante.','Moments Inoubliables','Participez à des moments privilégiés, comme l’observation des repas des animaux et des démonstrations interactives. Une expérience enrichissante pour petits et grands.','Découvrez les Secrets des Habitats'),(5,'Visite du Zoo','Montez à bord de notre petit train et profitez d’un tour complet du zoo sans effort. Une manière relaxante et amusante de découvrir le parc.','Commentaires en Direct','Tout au long du trajet, notre guide vous fournira des commentaires en direct sur les différents habitats et les animaux que vous verrez, enrichissant votre expérience.','Idéal pour Toute la Famille','Le petit train est parfait pour les visiteurs de tous âges, offrant une vue panoramique et des arrêts stratégiques pour observer de près les animaux et prendre des photos.','Partez à l’Aventure en Petit Train');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin_user','motdepasseclair','admin','2024-09-15 17:14:01'),(4,'employee_user','dd80e1549790cddeef43504fc5555781d42b90ecc5af68ba9195c84a63233211','employee','2024-09-15 17:28:05'),(14,'vétérinaire_user','4776fff2daf2af77f0353d2ca1815f07f7e5759d68baf9749ded946f6e8450b7','vet','2024-09-18 12:15:43');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zoo_hours`
--

DROP TABLE IF EXISTS `zoo_hours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zoo_hours` (
  `id` int NOT NULL AUTO_INCREMENT,
  `opening_time` time NOT NULL,
  `closing_time` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zoo_hours`
--

LOCK TABLES `zoo_hours` WRITE;
/*!40000 ALTER TABLE `zoo_hours` DISABLE KEYS */;
INSERT INTO `zoo_hours` VALUES (1,'09:00:00','19:00:00');
/*!40000 ALTER TABLE `zoo_hours` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-22 19:03:00

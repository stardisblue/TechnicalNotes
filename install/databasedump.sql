-- MySQL dump 10.15  Distrib 10.0.23-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: technotes
-- ------------------------------------------------------
-- Server version	10.0.23-MariaDB-2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE = @@TIME_ZONE */;
/*!40103 SET TIME_ZONE = '+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */;

--
-- Table structure for table `anwser_comments`
--

DROP TABLE IF EXISTS `anwser_comments`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anwser_comments` (
  `id`            INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `anwser_id`     INT(10) UNSIGNED NOT NULL,
  `user_id`       INT(10) UNSIGNED NOT NULL,
  `parent_id`     INT(10) UNSIGNED          DEFAULT NULL,
  `content`       TEXT             NOT NULL,
  `creation_date` DATETIME                  DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `anwser_id` (`anwser_id`),
  KEY `anwser_comments_ibfk_3` (`parent_id`),
  CONSTRAINT `anwser_comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `anwser_comments_ibfk_2` FOREIGN KEY (`anwser_id`) REFERENCES answers (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `anwser_comments_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES answer_comments (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `anwsers`
--

DROP TABLE IF EXISTS `anwsers`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anwsers` (
  `id`            INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id`   INT(10) UNSIGNED NOT NULL,
  `user_id`       INT(10) UNSIGNED NOT NULL,
  `content`       TEXT             NOT NULL,
  `creation_date` DATETIME                  DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `anwsers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `anwsers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`)
    ON DELETE CASCADE
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `proposed_tags`
--

DROP TABLE IF EXISTS `proposed_tags`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposed_tags` (
  `id`             INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `word`           VARCHAR(250)     NOT NULL,
  `positive_votes` INT(10) UNSIGNED NOT NULL,
  `total_votes`    INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `id`            INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`       INT(10) UNSIGNED NOT NULL,
  `title`         VARCHAR(250)     NOT NULL,
  `content`       TEXT             NOT NULL,
  `creation_date` DATETIME                  DEFAULT CURRENT_TIMESTAMP,
  `statut`        TINYINT(1)                DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `questions_tags`
--

DROP TABLE IF EXISTS `questions_tags`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions_tags` (
  `question_id` INT(10) UNSIGNED NOT NULL,
  `tag_id`      INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`question_id`, `tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `questions_tags_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `questions_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`)
    ON DELETE CASCADE
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `refused_tags`
--

DROP TABLE IF EXISTS `refused_tags`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `refused_tags` (
  `id`   INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `word` VARCHAR(250)     NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id`   INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `word` VARCHAR(250)     NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tags_technotes`
--

DROP TABLE IF EXISTS `tags_technotes`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags_technotes` (
  `technote_id` INT(10) UNSIGNED NOT NULL,
  `tag_id`      INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`technote_id`, `tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `tags_technotes_ibfk_1` FOREIGN KEY (`technote_id`) REFERENCES `technotes` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `tags_technotes_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`)
    ON DELETE CASCADE
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `technote_comments`
--

DROP TABLE IF EXISTS `technote_comments`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technote_comments` (
  `id`            INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tec_id`        INT(10) UNSIGNED NOT NULL,
  `user_id`       INT(10) UNSIGNED NOT NULL,
  `parent_id`     INT(10) UNSIGNED          DEFAULT NULL,
  `content`       TEXT             NOT NULL,
  `creation_date` DATETIME                  DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `tec_id` (`tec_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `technote_comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `technote_comments_ibfk_2` FOREIGN KEY (`tec_id`) REFERENCES `technotes` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `technote_comments_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `technote_comments` (`id`)
    ON DELETE CASCADE
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `technotes`
--

DROP TABLE IF EXISTS `technotes`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technotes` (
  `id`            INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`       INT(10) UNSIGNED NOT NULL,
  `title`         VARCHAR(250)     NOT NULL,
  `content`       TEXT             NOT NULL,
  `creation_date` DATETIME                  DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `technotes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technotes`
--

LOCK TABLES `technotes` WRITE;
/*!40000 ALTER TABLE `technotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `technotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id`           INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`         VARCHAR(50)      NOT NULL,
  `firstname`    VARCHAR(50)      NOT NULL,
  `password`     CHAR(50)         NOT NULL,
  `mail`         VARCHAR(50)      NOT NULL UNIQUE,
  `verification` VARCHAR(32)      NOT NULL,
  `isadmin`      TINYINT(1)                DEFAULT '0',
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE = @OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE = @OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES = @OLD_SQL_NOTES */;

-- Dump completed on 2016-03-18  1:16:59

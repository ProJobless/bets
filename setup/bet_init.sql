CREATE DATABASE  IF NOT EXISTS `bet` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `bet`;
-- MySQL dump 10.13  Distrib 5.5.24, for osx10.5 (i386)
--
-- Host: 127.0.0.1    Database: bet
-- ------------------------------------------------------
-- Server version	5.5.25

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bets`
--

DROP TABLE IF EXISTS `bets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bets` (
  `betid` int(11) NOT NULL AUTO_INCREMENT,
  `challenger` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` text NOT NULL,
  `conditions` text NOT NULL,
  `user1_wager` decimal(10,0) NOT NULL,
  `user2_wager` decimal(10,0) NOT NULL,
  `winner` int(11) DEFAULT NULL,
  `challenger_vote` int(11) DEFAULT NULL,
  `challenger_vote_date` int(11) DEFAULT NULL,
  `user2_vote` int(11) DEFAULT NULL,
  `user2_vote_date` int(11) DEFAULT NULL,
  `accepted` int(11) NOT NULL DEFAULT '0',
  `updated` datetime NOT NULL,
  `last_action` varchar(512) NOT NULL,
  `status` varchar(45) NOT NULL DEFAULT 'Open' COMMENT 'Open\\nAccepted\\nPending\\nClosed\\nPaid\\nTrashed',
  `winner_received_payment` bit(1) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `challenger_message` varchar(255) DEFAULT 'No Message',
  `user2_message` varchar(255) DEFAULT 'No Message',
  PRIMARY KEY (`betid`),
  KEY `challenger` (`challenger`,`user2`,`winner`,`challenger_vote`,`user2_vote`),
  KEY `user2` (`user2`),
  KEY `challenger_2` (`challenger`),
  KEY `user2_2` (`user2`),
  KEY `challenger_vote` (`challenger_vote`),
  KEY `user2_vote` (`user2_vote`),
  KEY `winner` (`winner`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payouts`
--

DROP TABLE IF EXISTS `payouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payouts` (
  `payoutid` int(11) NOT NULL AUTO_INCREMENT,
  `betid` int(11) NOT NULL,
  `loser` int(11) NOT NULL,
  `winner` int(11) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `paid` bit(1) NOT NULL DEFAULT b'0',
  `paid_date` datetime DEFAULT NULL,
  PRIMARY KEY (`payoutid`),
  KEY `betid` (`betid`),
  KEY `loser` (`loser`),
  KEY `winner` (`winner`),
  CONSTRAINT `payouts_ibfk_1` FOREIGN KEY (`betid`) REFERENCES `bets` (`betid`) ON UPDATE CASCADE,
  CONSTRAINT `payouts_ibfk_2` FOREIGN KEY (`loser`) REFERENCES `users` (`userid`) ON UPDATE CASCADE,
  CONSTRAINT `payouts_ibfk_3` FOREIGN KEY (`winner`) REFERENCES `users` (`userid`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(200) NOT NULL,
  `username` varchar(45) NOT NULL,
  `win` int(11) DEFAULT '0',
  `loss` int(11) DEFAULT '0',
  `winnings` float DEFAULT '0',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-02-21 21:03:16

/*
SQLyog Community v12.03 (64 bit)
MySQL - 5.6.20 : Database - mtguru
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`mtguru` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `mtguru`;

/*Table structure for table `gamesession` */

DROP TABLE IF EXISTS `gamesession`;

CREATE TABLE `gamesession` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `accessTime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1D6329D464B64DCC` (`userId`),
  CONSTRAINT `FK_1D6329D464B64DCC` FOREIGN KEY (`userId`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `gamesession` */

insert  into `gamesession`(`id`,`userId`,`points`,`accessTime`) values (1,3,273,'2015-02-26 22:32:07'),(2,3,273,'2015-02-26 22:33:52'),(3,3,273,'2015-02-26 22:39:24'),(4,3,273,'2015-02-26 22:40:04'),(5,3,273,'2015-02-26 23:10:42'),(6,3,0,'2015-03-13 20:57:02'),(7,3,0,'2015-03-14 01:13:07'),(8,3,512,'2015-03-14 01:25:38');

/*Table structure for table `logintable` */

DROP TABLE IF EXISTS `logintable`;

CREATE TABLE `logintable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `logintable` */

insert  into `logintable`(`id`,`userName`,`password`) values (1,'javi','4a9c7bc7049492b1dd402265045948e0');

/*Table structure for table `questiontype` */

DROP TABLE IF EXISTS `questiontype`;

CREATE TABLE `questiontype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `questionIdent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `questiontype` */

insert  into `questiontype`(`id`,`questionIdent`,`level`) values (1,'notesOfInterval',0),(2,'intervalOfNotes',1),(3,'notesOfChord',2),(4,'chordOfNotes',3),(5,'notesOfScale',4),(6,'scaleOfNotes',5),(7,'degreeOfChord',6),(8,'chordOfDegree',7),(9,'chordBelongsToScale',9);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `userId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `points` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `pointsThisWeek` int(11) NOT NULL,
  `lastAccess` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `user` */

insert  into `user`(`id`,`fullName`,`userId`,`points`,`level`,`pointsThisWeek`,`lastAccess`) values (3,'Javi Donado','javi',16312,5,1877,'2015-03-14 01:25:38'),(5,'Paco','paco',0,1,234,'2015-02-26 22:40:04'),(6,'Lolo','lolo',432,1,34,NULL),(7,'Moloco','moloco',1143,1,543,NULL),(8,'Krokako','krokako',334,1,213,'2015-02-26 22:40:04'),(9,'Moneino','moneino',665,1,443,NULL);

/*Table structure for table `userskill` */

DROP TABLE IF EXISTS `userskill`;

CREATE TABLE `userskill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currentSkill` int(11) NOT NULL,
  `numberOfAnswers` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `questionTypeId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3AA9750264B64DCC` (`userId`),
  KEY `IDX_3AA97502268B6C3E` (`questionTypeId`),
  CONSTRAINT `FK_3AA97502268B6C3E` FOREIGN KEY (`questionTypeId`) REFERENCES `questiontype` (`id`),
  CONSTRAINT `FK_3AA9750264B64DCC` FOREIGN KEY (`userId`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `userskill` */

insert  into `userskill`(`id`,`currentSkill`,`numberOfAnswers`,`userId`,`questionTypeId`) values (1,1,27,3,1),(2,0,0,3,2),(3,0,1,3,3),(4,1,24,3,4),(5,0,1,3,5),(6,1,25,3,6);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

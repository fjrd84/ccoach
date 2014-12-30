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

/*Table structure for table `answer` */

DROP TABLE IF EXISTS `answer`;

CREATE TABLE `answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `skill` int(11) NOT NULL,
  `right` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `questionTypeId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DD714F1364B64DCC` (`userId`),
  KEY `IDX_DD714F13268B6C3E` (`questionTypeId`),
  CONSTRAINT `FK_DD714F13268B6C3E` FOREIGN KEY (`questionTypeId`) REFERENCES `questiontype` (`id`),
  CONSTRAINT `FK_DD714F1364B64DCC` FOREIGN KEY (`userId`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `answer` */

/*Table structure for table `logintable` */

DROP TABLE IF EXISTS `logintable`;

CREATE TABLE `logintable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `logintable` */

insert  into `logintable`(`id`,`userName`,`password`) values (1,'paco','4a9c7bc7049492b1dd402265045948e0');

/*Table structure for table `questiontype` */

DROP TABLE IF EXISTS `questiontype`;

CREATE TABLE `questiontype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `questionIdent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `questiontype` */

insert  into `questiontype`(`id`,`questionIdent`,`level`) values (1,'notesOfInterval',0),(2,'intervalOfNotes',0),(3,'notesOfChord',1),(4,'chordOfNotes',1),(5,'notesOfScale',2),(6,'scaleOfNotes',2),(7,'degreeOfChord',3),(8,'chordOfDegree',3),(9,'chordBelongsToScale',4);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `userId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `points` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `user` */

insert  into `user`(`id`,`fullName`,`userId`,`points`,`level`) values (3,'Paco Porras','paco',1089,1);

/*Table structure for table `userskill` */

DROP TABLE IF EXISTS `userskill`;

CREATE TABLE `userskill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currentSkill` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `questionTypeId` int(11) NOT NULL,
  `numberOfAnswers` int(11) NOT NULL,
  `numberRight` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3AA9750264B64DCC` (`userId`),
  KEY `IDX_3AA97502268B6C3E` (`questionTypeId`),
  CONSTRAINT `FK_3AA97502268B6C3E` FOREIGN KEY (`questionTypeId`) REFERENCES `questiontype` (`id`),
  CONSTRAINT `FK_3AA9750264B64DCC` FOREIGN KEY (`userId`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `userskill` */

insert  into `userskill`(`id`,`currentSkill`,`userId`,`questionTypeId`,`numberOfAnswers`,`numberRight`) values (3,1,3,1,27,15),(4,1,3,2,16,11),(5,0,3,3,0,0),(6,0,3,4,0,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

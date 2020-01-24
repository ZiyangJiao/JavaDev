mysql> SHOW CREATE TABLE Comments;
+----------+-----------------------------------------------------------------------------+
| Table    | Create Table                                                                |
+----------+-----------------------------------------------------------------------------+
| Comments | CREATE TABLE `Comments` (
  `CommentID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` bigint(20) unsigned NOT NULL,
  `StoryID` bigint(20) unsigned NOT NULL,
  `CommentContent` text,
  `InsertTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`CommentID`),
  KEY `UserID` (`UserID`),
  KEY `StoryID` (`StoryID`),
  CONSTRAINT `Comments_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`),
  CONSTRAINT `Comments_ibfk_2` FOREIGN KEY (`StoryID`) REFERENCES `Stories` (`StoryID`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 |
+----------+------------------------------------------------------------------------------+


mysql> SHOW CREATE TABLE ReadingList;
+-------------+---------------------------------------------------------------------------+
| Table       | Create Table                                                              |
+-------------+---------------------------------------------------------------------------+
| ReadingList | CREATE TABLE `ReadingList` (
  `ReadingListID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` bigint(20) unsigned NOT NULL,
  `StoryID` bigint(20) unsigned NOT NULL,
  `InsertTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ReadingListID`),
  KEY `UserID` (`UserID`),
  KEY `StoryID` (`StoryID`),
  CONSTRAINT `ReadingList_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`),
  CONSTRAINT `ReadingList_ibfk_2` FOREIGN KEY (`StoryID`) REFERENCES `Stories` (`StoryID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 |
+-------------+--------------------------------------------------------------------------+


mysql> SHOW CREATE TABLE Sources;
+---------+-------------------------------------------------------------------------------+
| Table   | Create Table                                                                  |
+---------+-------------------------------------------------------------------------------+
| Sources | CREATE TABLE `Sources` (
  `StoryID` bigint(20) unsigned NOT NULL,
  `SourceLink` text,
  `InsertTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`StoryID`),
  CONSTRAINT `Sources_ibfk_1` FOREIGN KEY (`StoryID`) REFERENCES `Stories` (`StoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 |
+---------+-------------------------------------------------------------------------------+


mysql> SHOW CREATE TABLE Stories;
+---------+-------------------------------------------------------------------------------+
| Table   | Create Table                                                                  |
+---------+-------------------------------------------------------------------------------+
| Stories | CREATE TABLE `Stories` (
  `StoryID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` bigint(20) unsigned NOT NULL,
  `StoryTitle` varchar(255) NOT NULL,
  `StoryContent` text NOT NULL,
  `InsertTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`StoryID`),
  KEY `UserID` (`UserID`),
  CONSTRAINT `Stories_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 |
+---------+-------------------------------------------------------------------------------+


mysql> SHOW CREATE TABLE Users;
+-------+---------------------------------------------------------------------------------+
| Table | Create Table                                                                    |
+-------+---------------------------------------------------------------------------------+
| Users | CREATE TABLE `Users` (
  `UserID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `InsertTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 |
+-------+---------------------------------------------------------------------------------+
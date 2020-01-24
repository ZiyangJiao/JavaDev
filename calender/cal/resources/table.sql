==========================
== User info            ==

show CREATE TABLE Users
Users CREATE TABLE `Users` (
    `UserID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `Username` varchar(50) NOT NULL,
    `PasswordHash` varchar(255) NOT NULL,
    `RegisterTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ShareUsernames` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

==========================
== Event info           ==

show CREATE TABLE Events;
Events CREATE TABLE `Events` (
    `EventID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `UserID` bigint(20) unsigned NOT NULL,
    `EventTitle` varchar(255) NOT NULL,
    `EventContent` text DEFAULT NULL,
    `EventStartTime` datetime NOT NULL,
    `EventEndTime` datetime DEFAULT NULL,
    `Tag` varchar(50) DEFAULT NULL,
    `GroupUsernames` varchar(100) DEFAULT NULL,
    `InsertTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`EventID`),
    KEY `UserID` (`UserID`),
    CONSTRAINT `Events_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8


==========================
== Groups               ==

show CREATE TABLE `Groups`
Groups CREATE TABLE `Groups` (
    `UserID` bigint(20) unsigned NOT NULL,
    `EventID` bigint(20) unsigned NOT NULL,
    `GroupID` bigint(20) unsigned NOT NULL,
    `InsertTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`UserID`, `EventID`),
    KEY `EventID` (`EventID`),
    KEY `UserID` (`UserID`),
    CONSTRAINT `Groups_ibfk_1` FOREIGN KEY (`EventID`) REFERENCES `Events` (`EventID`),
    CONSTRAINT `Groups_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8


------------------------------------------------


-- ==========================
-- == Tags for events      ==

-- show CREATE TABLE Tags
-- Tags CREATE TABLE `Tags` (
--     `EventID` bigint(20) unsigned NOT NULL,
--     `UserID` bigint(20) unsigned NOT NULL,
--     `Tag` varchar(50) NOT NULL,
--     `InsertTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
--     `UpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
--     PRIMARY KEY (`EventID`,`Tag`),
--     KEY `UserID` (`UserID`),
--     CONSTRAINT `Tags_ibfk_1` FOREIGN KEY (`EventID`) REFERENCES `Events` (`EventID`),
--     CONSTRAINT `Tags_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8	

-- ==========================
-- == Info for sharing     ==

-- show CREATE TABLE Share
-- Share CREATE TABLE `Share` (
--     `UserID` bigint(20) unsigned NOT NULL,
--     `ShareUserID` bigint(20) unsigned NOT NULL,
--     `InsertTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
--     `UpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
--     PRIMARY KEY (`UserID`,`ShareUserID`),
--     KEY `ShareUserID` (`ShareUserID`),
--     CONSTRAINT `Share_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`),
--     CONSTRAINT `Share_ibfk_2` FOREIGN KEY (`ShareUserID`) REFERENCES `Users` (`UserID`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8	










===============================================================================
== Below are the original CREATE TABLE commands for `Tags`, `Share`, `Group` ==
===============================================================================

-- CREATE TABLE `Tags` (
--     `EventID` bigint(20) unsigned NOT NULL,
--     `UserID` bigint(20) unsigned NOT NULL,
--     `Tag` varchar(50) NOT NULL,
--     `InsertTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
--     `UpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
--     PRIMARY KEY (`EventID`, `Tag`), foreign key (`EventID`) references `Events` (`EventID`), foreign key (`UserID`) references `Users` (`UserID`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8

-- ==========================

-- CREATE TABLE `Share` (
--     `UserID` bigint(20) unsigned NOT NULL,
--     `ShareUserID` bigint(20) unsigned NOT NULL,
--     `InsertTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
--     `UpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
--     PRIMARY KEY (`UserID`, `ShareUserID`),  foreign key (`UserID`) references `Users` (`UserID`), foreign key (`ShareUserID`) references `Users` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

==========================

CREATE TABLE `Groups` (
    `UserID` bigint(20) unsigned NOT NULL,
    `EventID` bigint(20) unsigned NOT NULL,
    `GroupID` bigint(20) unsigned NOT NULL,
    `InsertTime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`UserID`, `EventID`),
    foreign key (`EventID`) references `Events` (`EventID`),
    foreign key (`UserID`) references `Users` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

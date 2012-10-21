SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
--create users
INSERT INTO `users` (`uId`, `uName`, `uPw`, `uType`, `uLastLogin`, `uSettings`, `uPhone`, `uMobile`, `uRealname`, `uWage`, `uAuthToken`) VALUES
(1, 'root', 'a9de63af5eb1e4196aba47de6639138a5a7552ac', 'admin', NULL, NULL, '079 123 12 12', '314 156 92 53', 'Hanspeter Müller', 9000, NULL),
(2, 'helper', 'ce066ede3bdd8bb54816b47f5bdb47497f379965', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL);


--other data
INSERT INTO `clients` (`cId`, `cName`, `cType`, `cGender`, `cPhone`, `cMobile`, `cStreet`, `cCity`) VALUES
(1, 'Max Muster', 'retail', 'm', '314 159 26 53', '123 123 12 12', 'Hansmusterstrasse 12', 'Hölstein'),
(2, 'Muster AG', 'business', 'b', '900 900 90 90', '700 700 70 70', 'Musterstrasse 12', 'FOOOOBAR');

INSERT INTO `jobs` (`jId`, `jName`, `jDesc`, `jStage`, `jResp`, `clients_cId`, `Creator users_uId`, `jCreationDate`) VALUES
(3, 'Fenster putzen', 'nur die fenster putzen', 'billing', 1, 1, 1, '2012-10-03'),
(4, 'P = NP lösen', 'nur kleiner Auftrag', 'processing', 2, 2, 1, '2012-10-30');

INSERT INTO `comAttach` (`coAtId`, `coTitle`, `coDesc`, `coDate`, `coChange`, `coResource`, `users_uId`, `jobs_jId`, `jobs_clients_cId`, `jobs_Creator users_uId`) VALUES
(2, 'lösung', 'im anhang', '2012-10-10 00:00:00', '2012-10-24 00:00:00', '/hahaha/', 2, 3, 1, 1);

INSERT INTO `comText` (`coTextId`, `coTitle`, `coText`, `coDate`, `coChange`, `jobs_jId`, `users_uId`) VALUES
(2, 'I am root', 'if you see me laughing you better have backup', '2012-10-05 00:00:00', NULL, 3, 2);

INSERT INTO `comWork` (`coWorkId`, `coTitle`, `coDesc`, `coTime`, `coDate`, `coChange`, `users_uId`, `jobs_jId`, `jobs_clients_cId`, `jobs_Creator users_uId`) VALUES
(11, 'ostseite geputzt', 'ostseite ist geputzt', 23, '2012-10-11 00:00:00', '2012-10-17 00:00:00', 1, 3, 1, 1);

INSERT INTO `materials` (`mId`, `mName`, `mDesc`, `mState`, `mDelDate`, `mPrice`, `mQuantity`, `jobs_jId`) VALUES
(3, 'geodreieck', 'wird benötigt an punkt 3.2.3', 'order', '2012-10-11', 9000, 2, 3),
(4, 'Schlauch', NULL, 'used', NULL, 3333, 2, 4);

INSERT INTO `shedule` (`sId`, `sStart`, `sStop`, `jobs_jId`, `sComment`, `users_uId`) VALUES
(2, '2012-10-17 00:00:00', '2012-10-18 00:00:00', 3, 'blablabla machen', 2);



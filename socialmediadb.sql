-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2019 at 01:46 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `socialmediadb`
--

-- --------------------------------------------------------

--
-- Table structure for table `catagorytable`
--

CREATE TABLE IF NOT EXISTS `catagorytable` (
  `categoryId` int(11) NOT NULL AUTO_INCREMENT,
  `category` text NOT NULL,
  `categoryAddDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`categoryId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `catagorytable`
--

INSERT INTO `catagorytable` (`categoryId`, `category`, `categoryAddDate`) VALUES
(1, 'General', '2018-12-17 10:01:26'),
(2, 'Sports', '2018-12-17 10:01:26'),
(3, 'Film/TV', '2018-12-17 10:02:06'),
(4, 'Funny', '2018-12-17 10:02:06'),
(5, 'Music', '2018-12-17 10:02:37'),
(6, 'Games', '2018-12-17 10:02:37');

-- --------------------------------------------------------

--
-- Table structure for table `commenttable`
--

CREATE TABLE IF NOT EXISTS `commenttable` (
  `commentID` int(11) NOT NULL AUTO_INCREMENT,
  `parentID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `replyToPost` tinyint(1) NOT NULL DEFAULT '1',
  `commentDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `commentText` text NOT NULL,
  PRIMARY KEY (`commentID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `commenttable`
--

INSERT INTO `commenttable` (`commentID`, `parentID`, `userID`, `replyToPost`, `commentDate`, `commentText`) VALUES
(1, 1, 6, 1, '2018-12-28 19:15:39', 'Nice post Jimmy.'),
(2, 21, 2, 1, '2018-12-28 20:45:52', 'I hate you.'),
(3, 21, 17, 1, '2018-12-28 20:46:25', 'I have no feelings about this.'),
(4, 18, 22, 1, '2018-12-28 20:48:31', 'I dont really play video games, but i like to read.'),
(9, 21, 2, 1, '2018-12-28 23:23:14', 'Why are you like this?'),
(14, 7, 1, 1, '2018-12-28 23:29:19', 'Me too man'),
(15, 5, 3, 1, '2018-12-28 23:29:49', 'But american footballs are egg shaped!'),
(16, 19, 6, 1, '2018-12-28 23:30:16', 'Yeah, i also enjoyed it :)'),
(17, 8, 7, 1, '2018-12-28 23:30:24', 'lol'),
(18, 3, 18, 1, '2018-12-28 23:30:33', 'Go to hell!'),
(19, 21, 19, 1, '2019-01-01 11:38:59', 'Kill yourself'),
(20, 11, 25, 1, '2019-01-04 17:10:19', 'Me too'),
(21, 21, 1, 1, '2019-01-07 06:54:15', 'Why1!!!'),
(24, 21, 1, 1, '2019-01-09 22:27:54', 'I have no emotional attachment to this post.'),
(25, 21, 1, 1, '2019-01-10 09:17:15', 'Comment'),
(26, 25, 1, 1, '2019-01-13 11:49:52', 'Hell'),
(27, 12, 1, 1, '2019-01-14 08:39:58', 'I am Testing Form validation'),
(28, 25, 1, 1, '2019-01-14 14:18:31', 'Fun for everyone'),
(29, 21, 1, 1, '2019-01-14 14:21:05', 'I dont know'),
(30, 28, 1, 1, '2019-01-17 10:16:36', 'Yeah im commenting!'),
(31, 55, 28, 1, '2019-01-18 18:43:51', 'WTF is that picture'),
(32, 55, 28, 1, '2019-01-18 18:45:35', 'I hate it'),
(33, 69, 1, 1, '2019-01-21 11:44:27', 'This is good'),
(34, 8, 1, 1, '2019-01-21 11:48:26', 'Yup'),
(35, 27, 1, 1, '2019-01-24 06:30:35', 'Yeah that feature is available now!'),
(36, 75, 1, 1, '2019-01-29 11:42:54', 'Thats pretty strange Mr Joe'),
(37, 57, 1, 1, '2019-01-29 12:54:42', 'Lol thats pretty good man )'),
(38, 74, 33, 1, '2019-02-01 18:50:53', 'lol busses'),
(39, 82, 1, 1, '2019-02-03 14:04:00', 'I hate people who are antisemetic');

-- --------------------------------------------------------

--
-- Table structure for table `emotitable`
--

CREATE TABLE IF NOT EXISTS `emotitable` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `likes` tinyint(1) NOT NULL,
  `hates` tinyint(1) NOT NULL,
  `angers` tinyint(1) NOT NULL,
  `deads` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `userId` (`userId`,`postId`),
  KEY `postId` (`postId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

--
-- Dumping data for table `emotitable`
--

INSERT INTO `emotitable` (`ID`, `userId`, `postId`, `likes`, `hates`, `angers`, `deads`) VALUES
(2, 2, 6, 0, 1, 0, 1),
(5, 2, 5, 0, 0, 0, 1),
(6, 3, 5, 0, 0, 0, 1),
(7, 1, 21, 1, 0, 1, 0),
(8, 1, 7, 0, 1, 0, 1),
(9, 1, 11, 1, 1, 0, 0),
(10, 1, 23, 1, 0, 0, 0),
(11, 1, 19, 0, 1, 0, 0),
(12, 1, 8, 1, 0, 0, 0),
(13, 1, 3, 0, 0, 0, 1),
(14, 1, 4, 1, 0, 0, 0),
(15, 1, 20, 0, 1, 0, 0),
(16, 1, 9, 1, 0, 0, 0),
(17, 1, 6, 1, 0, 0, 0),
(18, 1, 24, 1, 0, 0, 1),
(19, 1, 22, 0, 0, 1, 0),
(20, 1, 18, 1, 0, 0, 0),
(21, 1, 12, 1, 0, 0, 0),
(22, 27, 20, 1, 0, 0, 0),
(23, 27, 9, 1, 0, 0, 0),
(24, 27, 3, 0, 1, 0, 0),
(26, 27, 6, 1, 0, 0, 0),
(27, 27, 4, 1, 0, 0, 0),
(28, 27, 24, 1, 0, 0, 0),
(29, 1, 28, 1, 0, 0, 1),
(30, 1, 27, 1, 0, 0, 0),
(31, 1, 26, 0, 1, 0, 0),
(32, 1, 25, 1, 0, 0, 0),
(33, 1, 29, 0, 0, 1, 0),
(36, 1, 68, 1, 1, 0, 0),
(38, 1, 53, 0, 1, 0, 0),
(39, 1, 55, 1, 0, 0, 0),
(40, 1, 57, 1, 0, 0, 0),
(41, 1, 75, 1, 1, 0, 1),
(42, 29, 75, 1, 0, 0, 0),
(43, 32, 75, 1, 0, 0, 0),
(44, 1, 74, 1, 0, 0, 0),
(45, 1, 76, 1, 0, 0, 0),
(46, 1, 81, 1, 0, 0, 0),
(47, 33, 81, 1, 0, 0, 0),
(48, 33, 74, 1, 0, 0, 0),
(49, 33, 82, 1, 0, 0, 0),
(50, 1, 83, 1, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `posttable`
--

CREATE TABLE IF NOT EXISTS `posttable` (
  `postId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `postType` int(11) NOT NULL DEFAULT '1',
  `text` text NOT NULL,
  `imageURL` text NOT NULL,
  `category` int(11) NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `hates` int(11) NOT NULL DEFAULT '0',
  `angers` int(11) NOT NULL DEFAULT '0',
  `deads` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`postId`),
  UNIQUE KEY `postId` (`postId`),
  KEY `userId` (`userId`),
  KEY `category` (`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=84 ;

--
-- Dumping data for table `posttable`
--

INSERT INTO `posttable` (`postId`, `userId`, `date`, `postType`, `text`, `imageURL`, `category`, `likes`, `hates`, `angers`, `deads`) VALUES
(3, 2, '2018-12-10 03:34:12', 1, 'unpopular opinion time: the godfather is over rated!', 'NONE', 3, 0, 24, 4, 2),
(4, 17, '2018-12-09 14:45:13', 1, 'You need to see a phsycotherapist.', 'NONE', 4, 23, 0, 0, 0),
(5, 6, '2018-12-01 18:05:44', 1, 'I really like footballs. theyre so round.', 'NONE', 2, 123, 1, 0, 0),
(6, 2, '2018-12-17 11:22:32', 1, 'I can only play the piano!', 'NONE', 5, 4, 3, 2, 1),
(7, 3, '2018-12-17 11:25:33', 1, 'I really hate cooked onions.', 'NONE', 1, 1, 1, 2, -2),
(8, 20, '2018-12-17 12:39:17', 1, 'I love watching Anime', 'NONE', 3, 4, 0, 0, 0),
(9, 3, '2018-12-17 15:47:33', 1, 'Why am i here, Just to play music?', 'NONE', 5, 3, 0, 0, 0),
(11, 1, '2018-12-17 15:52:40', 1, 'I really like video games!', 'NONE', 1, 7, 12, 9, 4),
(12, 17, '2018-12-18 10:09:52', 1, 'I really like skyrim :)', 'NONE', 6, 1, 0, 0, 0),
(18, 20, '2018-12-18 11:26:30', 1, 'I really like video games.', 'NONE', 6, 4, 0, 0, 0),
(19, 23, '2018-12-19 04:05:06', 1, 'I really liked Baby Driver its a really fun movie!', 'NONE', 3, 1, 1, 0, 0),
(20, 24, '2018-12-19 12:13:00', 1, 'I also really like listening to music!', 'NONE', 5, 1, 1, 0, 0),
(21, 6, '2018-12-19 12:36:51', 1, 'I really like you guys', 'NONE', 1, 18, 12, 8, 4),
(22, 25, '2019-01-04 17:11:19', 1, 'I really like Resident Evil 2', 'NONE', 6, 4, 0, 1, 0),
(23, 1, '2019-01-07 13:16:32', 1, 'I really feel like killing aomeone', 'NONE', 3, 2, 0, 0, 1),
(24, 1, '2019-01-10 10:09:29', 1, 'I love video games!', 'NONE', 6, 2, 0, 0, 1),
(25, 1, '2019-01-14 08:34:53', 1, 'Im really not feeling hockey right now(((', 'NONE', 2, 1, 0, 0, 0),
(26, 27, '2019-01-14 12:38:53', 1, 'Wow this website sucks', 'NONE', 1, 0, 1, 0, 0),
(27, 27, '2019-01-14 12:39:21', 1, 'All these people discussing games but how about posting a picture or somethin?', 'NONE', 6, 1, 0, 0, 0),
(28, 27, '2019-01-14 12:39:58', 1, 'Would i lie to youWould i lie to youWould i lie to youWould i lie to youWould i lie to youWould i lie to you', 'NONE', 5, 1, 0, 0, 1),
(29, 1, '2019-01-17 10:16:20', 1, 'Make a post like this one!', 'NONE', 1, 0, 0, 1, 0),
(53, 1, '2019-01-17 12:45:30', 1, 'This is fun!', 'NONE', 1, 0, 1, 0, 0),
(55, 1, '2019-01-17 13:31:56', 3, 'I hate juice', 'postImages/user_AdminMahmoud/1547731916.png', 1, 1, 0, 0, 0),
(57, 28, '2019-01-18 18:47:49', 3, 'Look at this funny comic i found', 'postImages/user_Yasser_Bader/1547837269.jpg', 4, 1, 0, 0, 0),
(68, 1, '2019-01-21 08:14:00', 2, 'NONE', 'postImages/user_AdminMahmoud/1548058440.jpg', 6, 1, 1, 0, 0),
(74, 1, '2019-01-23 18:54:47', 3, 'Why is this like this?', 'postImages/user_AdminMahmoud/1548269686.jpg', 1, 2, 0, 0, 0),
(75, 29, '2019-01-26 14:32:41', 1, 'Hey my name is crazy old joe i like soccer and exclimation marks!!!!', 'NONE', 1, 3, 1, 0, 1),
(76, 1, '2019-01-29 12:56:00', 1, 'I just saw Blade Runner (1982) what a  weird movie but i think i liked it )', 'NONE', 3, 1, 0, 0, 0),
(77, 1, '2019-01-30 10:10:09', 3, 'Im just such a big fan of Hopsin', 'postImages/user_AdminMahmoud/1548843008.png', 5, 0, 0, 0, 0),
(81, 1, '2019-01-30 10:55:37', 3, 'Lol check this dog out bro', 'postImages/user_AdminMahmoud/1548845737.jpg', 4, 2, 0, 0, 0),
(82, 33, '2019-02-01 18:52:15', 1, 'I really like the new eminem album whos the feat on Lucky You?', 'NONE', 5, 1, 0, 0, 0),
(83, 1, '2019-02-03 14:04:34', 3, 'Nice', 'postImages/user_AdminMahmoud/1549202674.jpg', 1, 1, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `userfollowtable`
--

CREATE TABLE IF NOT EXISTS `userfollowtable` (
  `relationshipId` int(11) NOT NULL AUTO_INCREMENT,
  `followerId` int(11) NOT NULL,
  `followedId` int(11) NOT NULL,
  `relationshipDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`relationshipId`),
  KEY `followerId` (`followerId`),
  KEY `followedId` (`followedId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `userfollowtable`
--

INSERT INTO `userfollowtable` (`relationshipId`, `followerId`, `followedId`, `relationshipDate`) VALUES
(7, 1, 25, '2019-01-27 11:33:24'),
(10, 32, 29, '2019-01-28 10:37:38'),
(11, 32, 1, '2019-01-28 10:37:49'),
(12, 32, 3, '2019-01-28 10:38:02'),
(13, 32, 20, '2019-01-28 10:38:14'),
(14, 32, 6, '2019-01-28 10:38:25'),
(15, 32, 17, '2019-01-28 10:38:33'),
(16, 1, 32, '2019-01-28 11:47:27'),
(17, 1, 29, '2019-01-31 08:28:43'),
(18, 1, 28, '2019-01-31 14:28:08'),
(19, 1, 27, '2019-01-31 14:28:17'),
(20, 33, 1, '2019-02-01 18:52:50');

-- --------------------------------------------------------

--
-- Table structure for table `userprofiletable`
--

CREATE TABLE IF NOT EXISTS `userprofiletable` (
  `userId` int(11) NOT NULL,
  `profileText` text NOT NULL,
  `themeA` int(11) NOT NULL DEFAULT '0',
  `themeB` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userprofiletable`
--

INSERT INTO `userprofiletable` (`userId`, `profileText`, `themeA`, `themeB`) VALUES
(1, 'I actually just hate jews', 0, 0),
(2, 'Welcome to my profile :)', 0, 0),
(3, 'Welcome to my profile :)', 0, 0),
(6, 'Welcome to my profile :)', 0, 0),
(17, 'Welcome to my profile :)', 0, 0),
(20, 'Welcome to my profile :)', 0, 0),
(25, 'Welcome to my profile :)', 0, 0),
(27, 'Welcome to my profile :)', 0, 0),
(28, 'Welcome to my profile :)', 0, 0),
(29, 'Welcome to my profile :)', 0, 0),
(31, 'Welcome to my profile!', 0, 0),
(32, 'Welcome to my profile!', 0, 0),
(33, 'Welcome to my profile!', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `usertable`
--

CREATE TABLE IF NOT EXISTS `usertable` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` text NOT NULL,
  `Email` text NOT NULL,
  `Password` text NOT NULL,
  `joinDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userImage` text NOT NULL,
  `Premissions` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `usertable`
--

INSERT INTO `usertable` (`ID`, `Username`, `Email`, `Password`, `joinDate`, `userImage`, `Premissions`) VALUES
(1, 'AdminMahmoud', 'mahmoudaburascea@gmail.com', '$2y$10$dJFCXf1a0f7CPEa8e.z76OFrCk8hAmOAhqJI57U/uflyTTn6i12Wm', '2018-12-17 10:19:20', 'userImages/userAdminMahmoud1549202833.jpg', 1),
(2, 'AdminAla', 'ala@gmail.com', '$2y$10$TpFqWrMxxBnUXXDZ4sK9p.Bb7yQ568nlKVo0Mq0vMGItTU30L64La', '2018-12-17 10:19:20', 'userImages/userDefault.png', 1),
(3, 'MarkBlack', 'MarkBlack@Flum.com', '$2y$10$NdwTG6HGU5kKZSWHcZbzuOqZir0b8OdVFxM3fhRIvQJwhknC99SiG', '2018-12-17 10:19:20', 'userImages/user0.gif', 0),
(6, 'John Black', 'JohnBlack1990@gmail.com', '$2y$10$k5s5ZqpI6AAQRHYUtJW8Eu63POfq6eTkJ7WgXgOAIw7ouEjnlZcju', '2018-12-17 10:19:20', 'userImages/userDefault.png', 0),
(7, 'ala', 'alawan.7@gmail.com', '$2y$10$8kA7dftA5iK0YQkLlTn46epuxO7wUzv9oMNxECcvNydAm84u9SCkK', '2018-12-17 10:19:20', 'userImages/userDefault.png', 0),
(17, 'suhayb', 'suhaybas@gmail.com', '$2y$10$/55YNJu1IRVYrgFuF1j5SOFzwE3z0kFc99bsmuy1ZJgOIIJXhvXXq', '2018-12-17 10:19:20', 'userImages/userDefault.png', 0),
(18, 'Mohammed', 'mohammed20@gmail.com', '$2y$10$soF0CRs9Qr.s5RhXGif8Du/VP6zqQh0M.aDJQKN57AH1R1RrRIs3e', '2018-12-17 10:20:16', 'userImages/userDefault.png', 0),
(19, 'Anas Alkhazali', 'AnasAlkhazali@gmail.com', '$2y$10$DU3TkrGXXfNufvEOFRQyeOuhXVgnbBBHQjULoBOeJDovrT6vzYh7W', '2018-12-17 10:50:10', 'userImages/userDefault.png', 0),
(20, 'Mumin Naas', 'Mumin.Naas@gmail.com', '$2y$10$r1DKZIY7R4g7KDvSBF/EjuCpIW12qI0ynyjBw08bNqjORRdqK/Q4O', '2018-12-18 11:02:46', 'userImages/userMumin.jpg', 0),
(22, 'Alaa Barakat', 'Alaa_Barakat@gmail.com', '$2y$10$TAeo9Y3wPbA/YBgKEKgwYO/gQKxtdfey5411tFOJJSO./qS5g2y4.', '2018-12-18 12:55:35', 'userImages/userDefault.png', 0),
(23, 'John Smith', 'JohnSmith@gmail.com', '$2y$10$4yI2.l1vqC7kXAouaNuUo.ttL8I2lMw2DPR5BqFJqO1ecqxVCpfWu', '2018-12-19 12:04:15', 'userImages/userJohn Smith1545221133.jpg', 0),
(24, 'M Aburas', 'MahmoudAburas@gmail.com', '$2y$10$gtKF64FQ/klIWKbiBeZr7uXlfeg4pj3GMvllY9rqUnfpBLpmYOZQ6', '2018-12-19 12:12:16', 'userImages/userM Aburas1545221602.png', 0),
(25, 'Mona', 'MonaBuras@Gmail.com', '$2y$10$oS3LSsQN.rEQnm9kHzMD7OxwTf8ecBu7dQy8bwjhnOTz3ugckRERe', '2019-01-04 17:07:57', 'userImages/userMona1546621900.jpg', 0),
(26, 'AlexBalex', 'AlexBalexEmail@email.com', '$2y$10$E3lQW8h7V/7i3retty34ruLC4dVtidj4vEq5TnKyBBh/HwL67PKJ6', '2019-01-10 10:15:58', 'userImages/userDefault.png', 0),
(27, 'FlimsyShmuck10', 'FlimsyShmuck10@yahoo.com', '$2y$10$VbfiIHlNU0TxtL4Jd4fsFesEz.kICCisPS49vvkXKRhY4DIV5xVQ6', '2019-01-14 12:37:47', 'userImages/userFlimsyShmuck101547469627.jpg', 0),
(28, 'Yasser Bader', 'YasserBader@Fader.Kader', '$2y$10$iCaYNEukzpP3d7J6vEh5wOcyehI8zTd8KHj73z/jOWzT0QTtNZRRe', '2019-01-18 18:42:52', 'userImages/userYasser_Bader1547837339.jpg', 0),
(29, 'CrazyOldJoe', 'CrazyOldJoe@gmail.com', '$2y$10$8XQVtLd21sblhdhjv4Kq4Ov35GmD/sI130tUwpePD1hNJzJRLHY2q', '2019-01-26 14:32:11', 'userImages/userDefault.png', 0),
(31, 'Peltist_Black', 'peltist_black@gmail.com', '$2y$10$sUQmnifOn1ZmW3P2jlKoC.25PxCE9s6dh/NRjnAX/rVXouzHYwMlG', '2019-01-26 15:20:03', 'userImages/userPeltist_Black1548516443.jpg', 0),
(32, 'EvilBoy666', 'EvilBoy666@HeckMail.com', '$2y$10$qai8rMqdKmVUS6Bk/725AemqAind/s2uyiPd23usFZfSW8yZQafL2', '2019-01-28 10:37:22', 'userImages/userDefault.png', 0),
(33, 'Mohamed Adam', 'mohamedadam.gh93@gmail.com', '$2y$10$mvUh4YeKNbKc4m1Y8wMInunQAI8inPv.lB5tCzSE1tELpayHckzTy', '2019-02-01 18:49:10', 'userImages/userDefault.png', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commenttable`
--
ALTER TABLE `commenttable`
  ADD CONSTRAINT `commenttable_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `usertable` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `emotitable`
--
ALTER TABLE `emotitable`
  ADD CONSTRAINT `emotitable_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `usertable` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `emotitable_ibfk_2` FOREIGN KEY (`postId`) REFERENCES `posttable` (`postId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posttable`
--
ALTER TABLE `posttable`
  ADD CONSTRAINT `posttable_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `usertable` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posttable_ibfk_2` FOREIGN KEY (`category`) REFERENCES `catagorytable` (`categoryId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `userfollowtable`
--
ALTER TABLE `userfollowtable`
  ADD CONSTRAINT `userfollowtable_ibfk_1` FOREIGN KEY (`followerId`) REFERENCES `usertable` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userfollowtable_ibfk_2` FOREIGN KEY (`followedId`) REFERENCES `usertable` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `userprofiletable`
--
ALTER TABLE `userprofiletable`
  ADD CONSTRAINT `userprofiletable_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `usertable` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

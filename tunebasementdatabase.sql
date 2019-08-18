-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql01.service.rug.nl
-- Generation Time: Feb 02, 2017 at 06:58 PM
-- Server version: 5.6.34-79.1-56-log
-- PHP Version: 7.0.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s3253481`
--

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `album_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `year` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`album_id`, `title`, `year`) VALUES
(1, 'Paris', 2017),
(3, 'racine carrÃ©e', 2013),
(5, 'Toppers in concert', 2007),
(7, 'Cool', 2000),
(9, 'racine carrÃ©e', 2013),
(11, 'asdf', 2010);

-- --------------------------------------------------------

--
-- Table structure for table `album_song`
--

CREATE TABLE `album_song` (
  `album_id` int(11) NOT NULL,
  `song_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `album_song`
--

INSERT INTO `album_song` (`album_id`, `song_id`) VALUES
(1, 7),
(3, 11);

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `artist_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`artist_id`, `name`) VALUES
(1, 'The Chainsmokers'),
(3, 'Stromae'),
(5, 'Gerard Joling'),
(7, 'Cool song'),
(9, 'asd');

-- --------------------------------------------------------

--
-- Table structure for table `artist_album`
--

CREATE TABLE `artist_album` (
  `artist_id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `artist_album`
--

INSERT INTO `artist_album` (`artist_id`, `album_id`) VALUES
(1, 1),
(3, 3),
(5, 5),
(7, 7),
(3, 9),
(9, 11);

-- --------------------------------------------------------

--
-- Table structure for table `artist_song`
--

CREATE TABLE `artist_song` (
  `artist_id` int(11) NOT NULL,
  `song_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `artist_song`
--

INSERT INTO `artist_song` (`artist_id`, `song_id`) VALUES
(1, 7),
(3, 11);

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pub_date` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genre_id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`genre_id`, `name`) VALUES
(1, 'Dance'),
(3, 'Hiphop'),
(5, 'Pop'),
(7, 'Super'),
(9, 'asdf');

-- --------------------------------------------------------

--
-- Table structure for table `like_song`
--

CREATE TABLE `like_song` (
  `song_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `like_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `like_song`
--

INSERT INTO `like_song` (`song_id`, `user_id`, `like_status`) VALUES
(7, 53, 2),
(7, 55, 1),
(7, 57, 2),
(11, 53, 1);

-- --------------------------------------------------------

--
-- Table structure for table `playlists`
--

CREATE TABLE `playlists` (
  `playlist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(75) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `playlists`
--

INSERT INTO `playlists` (`playlist_id`, `user_id`, `name`) VALUES
(25, 55, 'Playlist'),
(27, 53, '23'),
(29, 61, 'Pompuuuh');

-- --------------------------------------------------------

--
-- Table structure for table `playlist_song`
--

CREATE TABLE `playlist_song` (
  `playlist_id` int(11) NOT NULL,
  `song_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `playlist_song`
--

INSERT INTO `playlist_song` (`playlist_id`, `song_id`) VALUES
(29, 7);

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `song_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `year` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`song_id`, `user_id`, `title`, `year`) VALUES
(7, 55, 'Paris', 2000),
(11, 53, 'Formidable\r\n', 2010);

-- --------------------------------------------------------

--
-- Table structure for table `song_genre`
--

CREATE TABLE `song_genre` (
  `song_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `song_genre`
--

INSERT INTO `song_genre` (`song_id`, `genre_id`) VALUES
(7, 7),
(11, 9);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_hash` char(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_email` char(30) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `password_hash`, `user_email`, `image`) VALUES
(53, 'Mike2', '$2y$10$/RYGz1q4VXK.GF.yUR/TeON2htQkl06SOK9f6GTuservM48rIfkmC', 'mike2@mike.nl', 'voetbal.png'),
(55, 'marloes123', '$2y$10$wptGYxLKwgjH3uywJ4w8fOFdmuspcZ9Yuxo5DU874y5Y7GlzljZEi', 'm@123.nl', ''),
(57, 'New', '$2y$10$tXTOYHuL/QB0DP9CuzrcHOiki.ojffqcCD5AWlaVV0Wml1.jbE4pS', 'new', 'index.jpeg'),
(59, 'mike', '$2y$10$g/XblnpPccfXORQedaxX1O5/AtHyAsnMRLsnguWDHsd9XfJN393zm', 'mike@mike.nl', ''),
(61, 'ricky', '$2y$10$WeFJAIGVEBdSkEpdqWzeb.JIsdTK8/lJP1BiccXlqgcsiksB3TFK.', 'sfas@.googl.nl', ''),
(63, 'marloess', '$2y$10$qpJlvjFfKEN8EEQyz2CjGO5EGQ3MhnCkk6XkBfeqcwZSqPTxmJTxO', 'marloes@s.nl', ''),
(65, 'user', '$2y$10$8d8SvdGiwJYQwvuzncepPe7rYrXEpjyRDVAqPkcpP0WLcz56wtG6m', 'user@user.nl', ''),
(67, 'Hello', '$2y$10$uzf.eAUC.lu1fgy3JDibW.BC6WzLK4naA1SHWOvqscCvziV07HS9O', 'hello', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`album_id`);

--
-- Indexes for table `album_song`
--
ALTER TABLE `album_song`
  ADD KEY `album_song_ibfk_1` (`album_id`),
  ADD KEY `album_song_ibfk_2` (`song_id`);

--
-- Indexes for table `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`artist_id`);

--
-- Indexes for table `artist_album`
--
ALTER TABLE `artist_album`
  ADD KEY `artist_id` (`artist_id`),
  ADD KEY `album_id` (`album_id`);

--
-- Indexes for table `artist_song`
--
ALTER TABLE `artist_song`
  ADD KEY `artist_id` (`artist_id`),
  ADD KEY `song_id` (`song_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genre_id`);

--
-- Indexes for table `like_song`
--
ALTER TABLE `like_song`
  ADD PRIMARY KEY (`song_id`,`user_id`),
  ADD KEY `user_id_2` (`user_id`),
  ADD KEY `song_id_2` (`song_id`) USING BTREE;

--
-- Indexes for table `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`playlist_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`song_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `song_genre`
--
ALTER TABLE `song_genre`
  ADD KEY `song_genre` (`song_id`),
  ADD KEY `song_genre_2` (`genre_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `albums`
--
ALTER TABLE `albums`
  MODIFY `album_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
  MODIFY `artist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `genre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `playlists`
--
ALTER TABLE `playlists`
  MODIFY `playlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `song_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `album_song`
--
ALTER TABLE `album_song`
  ADD CONSTRAINT `album_song_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `albums` (`album_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `album_song_ibfk_2` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`) ON UPDATE CASCADE;

--
-- Constraints for table `artist_album`
--
ALTER TABLE `artist_album`
  ADD CONSTRAINT `artist_album_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`artist_id`),
  ADD CONSTRAINT `artist_album_ibfk_2` FOREIGN KEY (`album_id`) REFERENCES `albums` (`album_id`);

--
-- Constraints for table `artist_song`
--
ALTER TABLE `artist_song`
  ADD CONSTRAINT `artist_song_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`artist_id`),
  ADD CONSTRAINT `artist_song_ibfk_2` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`);

--
-- Constraints for table `like_song`
--
ALTER TABLE `like_song`
  ADD CONSTRAINT `like_song_ibfk_1` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`),
  ADD CONSTRAINT `like_song_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `playlists`
--
ALTER TABLE `playlists`
  ADD CONSTRAINT `playlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `songs`
--
ALTER TABLE `songs`
  ADD CONSTRAINT `songs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `song_genre`
--
ALTER TABLE `song_genre`
  ADD CONSTRAINT `song_genre` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`),
  ADD CONSTRAINT `song_genre_2` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

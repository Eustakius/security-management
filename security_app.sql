-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2025 at 09:20 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `security_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `encrypted_content` text NOT NULL,
  `encrypted_aes_key` text NOT NULL,
  `encrypted_aes_key_sender` text DEFAULT NULL,
  `iv` varchar(255) NOT NULL,
  `signature` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `encrypted_content`, `encrypted_aes_key`, `encrypted_aes_key_sender`, `iv`, `signature`, `created_at`) VALUES
(1, 5, 4, 'U2FsdGVkX1+4uyx9V08RXML9dZARUIO+QZ2UWG9cjsM=', 'dGfX7iDJi9XDCVNqjXiAQX79YjmqPQKKCWOPU52ioWut+j2MERZzWbF7IiHNtZgkl5Jw2it4+JA/s3MgJRdbo0HUrPDqxV+nh8QsFXxFCMlikg4/pzVGL1y7/SkSNIcGHDTkkprVy95ieFa2z2JTajLeAtASgOllmK1QifZq7d3vFJxisVoHc2dM6WRBQoLhdsOgmmXYFWrQ9QJMYUjL/cwEbnq8VTGdPBwJjLxZa2ZdhmHsndWuu1ftmk1jWNC5T4j+/ead306JoZUFK5pG3z8YJBiYL/RbisuRHj4lYVSp2FSdcHIeXQaadsBaWuyrq1Gl7rQ2/+0nKukL7arksw==', 'iTqngdvbT+BB6cQcH86kWmwrperL3hpfG8UuTHWaX0xo7I5jEgcq5NC5yMwThFslC+EjD7z4Xpo+0xO4OKmBhgxg/xncD62uty6IXneKfPFaCkHZK5uVaH9yKcgUECnmJzY5x69T+r6bF4Uewwh7g1SGI5FTb2DxHhxkZn8TtiY0GwDuBctYP6W7L6DetzJtLXNsoITa0x52srAiDx1KFQv5BPdz8+WddZiqoFAH5VxQmPzO2easlh6tKFwYs3wINGRgEHq/MsmhY0iLArA3i7thKQAQ3vywRmt40nlb2yzuQqr+lQsvr6Q+G3nRRKqLoqL+4GEE8bDJAGAR7Ccc/w==', 'default', 'NriZ1crRPGKcfj7VCbg4xjkbhJZl0bc7/1nOBhrhfM6G6CUfILTLmyX9BYfMKQN3vJ3RKY8XNh3MVpQpkj9wOMQdNNkvfdao9QHcQr6TnBeHo1jJz52awWd0Ipip1Zgy89mGfzBjEY3WQUVQiTdxTua8WCFrzaqGWZVeatKzfF1S5ntl6SnrXZU28z5jD0aFMJgCWBOXkElK4qdLekXUEPPHC0IWJwBhBnax6m+8jSRygLp166JFOLcO7gCnnzSRxSgaeS2HTlEdITNuBGGdfb5fTPe9/VbSrckG7YuHPHWuLmel/flaukc69yeE0jUuAMt+wbGZ1VmJND5Gcdav8g==', '2025-12-09 08:08:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `public_key` text DEFAULT NULL,
  `encrypted_private_key` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `public_key`, `encrypted_private_key`, `created_at`) VALUES
(4, 'Owner', '$2y$10$F82IVY5Z4sUtBbzZUI7g8O1ca5sHmjKUrFl9BD/IAhiyEOuT0hIk2', '-----BEGIN PUBLIC KEY-----\r\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA5Ovd9E7h0mPtYp93G5io\r\n7soNnA9D8syISyU0gDbN9yrouyVf1zqA2KDdyt7YhbYgQtGnbeiXVFxgf06CYj74\r\nAmJ1LfsLid+9HQUGgOw1xsY2HrPqKFj1HhPFBkyqaCsaivM3JUVprj5siiQuIvTu\r\n4j4CCfFg/2qe/RWEMK9e+xTjf/G6SQNYVdJP4uXAi/adSneixOL8o9JPgeHYJtMQ\r\ninhGNqnmNj1u9QDE7x89Ur3o5OgXRcBoGkeT9pX7/rYO5HHfmWMKFLyWbBQmxQJQ\r\nsAEyyOJOoi0yRHu9RP87zYooHcj52YwgvC80eR+3LYlhlZzo5pZTDq4r9Ylz7AZm\r\nKwIDAQAB\r\n-----END PUBLIC KEY-----', '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', '2025-12-09 08:05:37'),
(5, 'Customer', '$2y$10$1uyrnpaMKNWXNhxTKXV2YuSK2emfMuJosNyFOng0jLzHJF54R3j0e', '-----BEGIN PUBLIC KEY-----\r\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvVCBpCGBUXn7mCmtOvpD\r\nyViejC6ZLILYh34+MLHFTZNMfaJzAHai24DlWfqKIoASh6kxg/DoGRThfAw5Gmr3\r\nKBDLVxk+c9muUIgQZC7KDCweqicCxsoxnKpYJ7vnSXlvRDydVIidd5HbTTdocDXW\r\nf+KRN71frI99TJndqNN64ffKO2b8axcmQmIY8zGQ1TktpiMc/zbgQ23pmkRwWyUe\r\n5AEZ4z8hc4qk+94tYOhyL2LXOklvRMtdxEe5XImJZ8WKQkzne4RtlKY3XrAc3gAd\r\nEys+EVVGQ5z9gPyTWOPcM7SGmrcbGjwQxU4Wc2Ck6V2KGDR09y2RSzzxT1Q/4K5z\r\nCQIDAQAB\r\n-----END PUBLIC KEY-----', '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', '2025-12-09 08:07:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

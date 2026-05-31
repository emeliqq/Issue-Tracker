-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Maj 31, 2026 at 03:11 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `issue_tracker`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `issues`
--

CREATE TABLE `issues` (
  `id` varchar(20) NOT NULL,
  `summary` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `steps_to_reproduce` text DEFAULT NULL,
  `status_id` int(11) NOT NULL,
  `severity_id` int(11) NOT NULL,
  `reporter_id` varchar(20) DEFAULT NULL,
  `assignee_id` varchar(20) DEFAULT NULL,
  `updater_id` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issues`
--

INSERT INTO `issues` (`id`, `summary`, `description`, `steps_to_reproduce`, `status_id`, `severity_id`, `reporter_id`, `assignee_id`, `updater_id`, `created_at`, `updated_at`) VALUES
('QA-00001', 'kot mi uciek', 'kot ciek mi', 'uciek mi kot', 3, 2, 'USR-00001', NULL, 'USR-00001', '2026-05-31 14:07:06', '2026-05-31 14:42:58'),
('QA-00002', 'ktos zesral sie jajecznica', 'ktos zesral sie jajecznica', 'ktos zesral sie jajecznica', 1, 3, 'USR-00001', NULL, NULL, '2026-05-31 14:48:32', NULL),
('QA-00003', 'kora je', 'kora je', 'kora je', 1, 2, 'USR-00001', NULL, NULL, '2026-05-31 14:48:43', NULL),
('QA-00004', 'missing ', 'missing ', 'missing ', 1, 1, 'USR-00001', NULL, NULL, '2026-05-31 14:49:03', NULL),
('QA-00005', 'ergergfe444', 'ergergppppppppppppppppppppppppppppppppppppppp\r\n\r\n\r\nlooos\r\n90', 'ergerger\r\nlol', 2, 2, 'USR-00001', 'USR-00001', 'USR-00001', '2026-05-31 14:49:19', '2026-05-31 15:02:03');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `severities`
--

CREATE TABLE `severities` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `severities`
--

INSERT INTO `severities` (`id`, `name`) VALUES
(1, 'Low'),
(2, 'Medium'),
(3, 'High');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `statuses`
--

CREATE TABLE `statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`id`, `name`) VALUES
(1, 'Open'),
(2, 'In Progress'),
(3, 'Resolved');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` varchar(20) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `created_at`) VALUES
('USR-00001', 'Emilia', 'Jasińska', 'emilia.jasia@gmail.com', '$2y$10$IBJY718QsgAEipMkcZGJVO32oWoUeWg83k/eBMHJdMvZ3oZdBUB8O', NULL);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `severity_id` (`severity_id`),
  ADD KEY `reporter_id` (`reporter_id`),
  ADD KEY `assignee_id` (`assignee_id`),
  ADD KEY `updater_id` (`updater_id`);

--
-- Indeksy dla tabeli `severities`
--
ALTER TABLE `severities`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `severities`
--
ALTER TABLE `severities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `issues`
--
ALTER TABLE `issues`
  ADD CONSTRAINT `issues_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`),
  ADD CONSTRAINT `issues_ibfk_2` FOREIGN KEY (`severity_id`) REFERENCES `severities` (`id`),
  ADD CONSTRAINT `issues_ibfk_3` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `issues_ibfk_4` FOREIGN KEY (`assignee_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `issues_ibfk_5` FOREIGN KEY (`updater_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

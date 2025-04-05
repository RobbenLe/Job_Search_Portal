-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Apr 05, 2025 at 08:09 PM
-- Server version: 11.5.2-MariaDB-ubu2404
-- PHP Version: 8.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `job_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `job_id` int(11) NOT NULL,
  `resume_path` varchar(255) DEFAULT NULL,
  `cover_letter` text DEFAULT NULL,
  `application_status` enum('pending','reviewed','accepted','rejected') NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `name`, `email`, `job_id`, `resume_path`, `cover_letter`, `application_status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 16, '/uploads/resumes/67e59eb14c584_Hung Ro Bben Le-CV-New.pdf', 'Hello, my name is Robben', 'pending', '2025-03-27 18:53:37', '2025-03-27 18:53:37'),
(2, 1, NULL, NULL, 16, '/uploads/resumes/67e59ed2d3065_Hung Ro Bben Le-CV-New.pdf', 'Hello', 'pending', '2025-03-27 18:54:10', '2025-03-27 18:54:10'),
(3, 1, NULL, NULL, 16, '/uploads/resumes/67e5a17e4c5db_Hung Ro Bben Le-CV-New.pdf', 'Robben', 'pending', '2025-03-27 19:05:34', '2025-03-27 19:05:34'),
(4, 1, NULL, NULL, 16, '/uploads/resumes/67e5a1a03f548_Hung Ro Bben Le-CV-New.pdf', 'Robben', 'pending', '2025-03-27 19:06:08', '2025-03-27 19:06:08'),
(5, 1, NULL, NULL, 16, '/uploads/resumes/67e5a89e571f8_Hung Ro Bben Le-CV-New.pdf', 'Hello ', 'accepted', '2025-03-27 19:35:58', '2025-03-28 09:58:52'),
(6, 1, NULL, NULL, 16, '/uploads/resumes/67e5a9582f212_Hung Ro Bben Le-CV-New.pdf', 'Nguyen Le Bao Vy ', 'accepted', '2025-03-27 19:39:04', '2025-03-28 09:59:02'),
(7, 1, NULL, NULL, 16, '/uploads/resumes/67e5b0b310d6a_Hung Ro Bben Le-CV-New.pdf', 'Hihihi', 'pending', '2025-03-27 20:10:27', '2025-03-27 20:10:27'),
(8, 6, 'Hung Robben Le', 'lehungrobben18112004@gmail.com', 12, '/uploads/resumes/67e5b4d9d82bb_Hung Ro Bben Le-CV-New.pdf', 'Heyyyy, please work ', 'pending', '2025-03-27 20:28:09', '2025-03-27 20:28:09'),
(9, 6, 'Hung Robben Le', 'lehungrobben18112004@gmail.com', 12, '/uploads/resumes/67e5b53786a83_Hung Ro bben le-Cover Letter..pdf', 'worked', 'pending', '2025-03-27 20:29:43', '2025-03-27 20:29:43'),
(10, 6, 'Hung Robben Le', 'lehungrobben18112004@gmail.com', 12, '/uploads/resumes/67e5b60bc46d2_Hung Ro bben le-Cover Letter..pdf', 'worked', 'pending', '2025-03-27 20:33:15', '2025-03-27 20:33:15'),
(11, NULL, 'Kevin', 'kevinNguyen@gmail.com', 11, '/uploads/resumes/67e5b70e82366_Hung Ro Bben Le-CV-New.pdf', 'I am kevin', 'pending', '2025-03-27 20:37:34', '2025-03-27 20:37:34'),
(12, 7, 'Nguyen Le Bao Vy ', 'nguyenlebaovy@gmail.com', 14, '/uploads/resumes/67e5b95163829_Hung Ro Bben Le-CV-New.pdf', 'I am Bao Vy', 'pending', '2025-03-27 20:47:13', '2025-03-27 20:47:13'),
(13, 7, 'Nguyen Le Bao Vy ', 'nguyenlebaovy@gmail.com', 16, '/uploads/resumes/67e5c43ee3330_Hung Ro Bben Le-CV-New.pdf', 'jhhvhvlhvi;hv;ih', 'pending', '2025-03-27 21:33:50', '2025-03-27 21:33:50'),
(14, 6, 'Hung Robben Le', 'lehungrobben18112004@gmail.com', 12, '/uploads/resumes/67e718ee50573_Hung Ro bben le-Cover Letter..pdf', 'Hwllo My anme is RObben Le', 'reviewed', '2025-03-28 21:47:26', '2025-03-28 21:48:35'),
(15, 6, 'Hung Robben Le', 'lehungrobben18112004@gmail.com', 11, '/uploads/resumes/67e71cd581b20_Hung Ro Bben Le-CV-New.pdf', 'Please Workkk', 'pending', '2025-03-28 22:04:05', '2025-03-28 22:04:05'),
(16, 6, 'Hung Robben Le', 'lehungrobben18112004@gmail.com', 14, '/uploads/resumes/67e85a5e56c6e_Hung_Ro_Bben_Le-CV.pdf', 'Good Morning', 'pending', '2025-03-29 20:38:54', '2025-03-29 20:38:54'),
(17, 6, 'Hung Robben Le', 'lehungrobben18112004@gmail.com', 16, '/uploads/resumes/67e86a79e09b0_Hung_Ro_Bben_Le-CV.docx', 'qwdqdasdadsqwdqwdqd', 'pending', '2025-03-29 21:47:37', '2025-03-29 21:47:37'),
(18, 6, 'Hung Robben Le', 'lehungrobben18112004@gmail.com', 11, '/uploads/resumes/67e86e8814162_Hung_Ro_bben_le-Cover_Letter_.pdf', 'qawsfcqfc', 'pending', '2025-03-29 22:04:56', '2025-03-29 22:04:56'),
(19, 6, 'Hung Robben Le', 'lehungrobben18112004@gmail.com', 11, '/uploads/resumes/67e8700598b06_IH_RESULTS.pdf', 'ascaecfae', 'pending', '2025-03-29 22:11:17', '2025-03-29 22:11:17'),
(20, 6, 'Hung Robben Le', 'lehungrobben18112004@gmail.com', 11, '/uploads/resumes/67e8702647864_Hung_Ro_Bben_Le-CV-New.pdf', 'aqwsfwefaefw', 'pending', '2025-03-29 22:11:50', '2025-03-29 22:11:50'),
(21, 6, 'Hung Robben Le', 'lehungrobben18112004@gmail.com', 11, '/uploads/resumes/67e871411ba19_Hung_Ro_Bben_Le-CV-New.pdf', '12345678', 'pending', '2025-03-29 22:16:33', '2025-03-29 22:16:33'),
(22, 6, 'Hung Robben Le', 'lehungrobben18112004@gmail.com', 16, '/uploads/resumes/67eda05669efa_Hung_Ro_Bben_Le-CV-New.pdf', 'Hello I am Bben,\r\n\r\nI am really aprreciate this oppoutunity ', 'pending', '2025-04-02 20:38:46', '2025-04-02 20:38:46'),
(23, 6, 'Hung Robben Le', 'lehungrobben18112004@gmail.com', 16, '/uploads/resumes/67eda2a1d734e_Hung_Ro_Bben_Le-CV-New.pdf', 'Good Morning\r\n\r\nMy Name is Robben', 'pending', '2025-04-02 20:48:33', '2025-04-02 20:48:33'),
(24, 6, 'Hung Robben Le', 'lehungrobben18112004@gmail.com', 11, '/uploads/resumes/67eda66bcffd1_Hung_Ro_Bben_Le-CV.docx', 'Hello', 'pending', '2025-04-02 21:04:43', '2025-04-02 21:04:43'),
(25, 8, 'Hung Vo', 'hungvo@gmail.com', 16, '/uploads/resumes/67edab2b61b0f_Hung_Ro_Bben_Le-CV.pdf', 'Hello, My name is Robben', 'reviewed', '2025-04-02 21:24:59', '2025-04-04 20:28:21'),
(26, 7, 'Nguyen Le Bao Vy ', 'nguyenlebaovy2003@gmail.com', 16, '/uploads/resumes/67edb0b5cd80e_Hung_Ro_Bben_Le-CV.docx', 'Hello, Thank You', 'pending', '2025-04-02 21:48:37', '2025-04-02 21:48:37'),
(27, 6, 'Hung Robben Le', 'lehungrobben18112004@gmail.com', 16, '/uploads/resumes/67f040a4a820d_Hung_Ro_Bben_Le-CV.pdf', 'hi', 'pending', '2025-04-04 20:27:16', '2025-04-04 20:27:16');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `jobType` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT 0.00,
  `status` varchar(50) DEFAULT 'open',
  `admin_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `jobType`, `description`, `requirements`, `location`, `salary`, `status`, `admin_id`, `created_at`, `updated_at`) VALUES
(11, 'Frontend Developer', 'Software Engineer', 'Responsible for building user-friendly web interfaces. Collaborates with designers and backend engineers.', 'Proficient in HTML, CSS, JavaScript. Familiarity with Vue.js or React is a plus.', 'London', 50000.00, 'open', 1, '2025-03-23 09:30:02', '2025-04-02 22:10:49'),
(12, 'Backend Engineer', 'Software Engineer', 'Develops server-side logic and APIs. Ensures high performance and responsiveness of requests.', 'Strong knowledge of Node.js or PHP. Experience with RESTful APIs and SQL databases.', 'New York, NY', 65000.00, 'open', 1, '2025-03-23 09:30:02', '2025-03-23 09:30:02'),
(13, 'Full-Stack Developer', 'Software Engineer', 'Works across front-end and back-end. Maintains the entire stack of a web application.', 'Experience with Vue.js, Node.js, MySQL, and Docker. Knowledge of CI/CD pipelines is beneficial.', 'Hybrid (2 days onsite, 3 days remote)', 75000.00, 'open', 1, '2025-03-23 09:30:02', '2025-03-23 09:30:02'),
(14, 'Data Analyst', 'Software Engineer', 'Analyzes data sets to glean insights and support decision-making. Prepares dashboards and reports.', 'Proficiency in SQL, Python (pandas, NumPy), and data visualization tools (Tableau, PowerBI).', 'Chicago, IL', 70000.00, 'open', 1, '2025-03-23 09:30:02', '2025-03-23 09:30:02'),
(16, 'AI Engineer', 'AI Developer', 'We are seeking for a highly skilled AI Specialist to lead the development and implementation of advanced machine learning models and artificial intelligence solutions. In this role, you will design, build, and deploy scalable algorithms and collaborate with cross-functional teams to integrate AI technologies into our products. You will also be responsible for researching and evaluating new approaches, optimizing existing models, and ensuring the performance and reliability of AI systems.', '• Bachelor\'s or Master\'s degree in Computer Science, Data Science, AI, or a related field. \n• At least 3 years of experience in AI development or machine learning projects. \n• Proficiency in Python with experience in frameworks such as TensorFlow, PyTorch, or Scikit-Learn. \n• Strong understanding of neural networks, deep learning, and natural language processing. \n• Experience with data preprocessing, feature engineering, and model evaluation techniques. \n• Excellent problem-solving skills and ability to work both independently and collaboratively.', 'Haarlem', 900.00, 'open', 1, '2025-03-26 07:40:42', '2025-04-04 18:07:36'),
(21, 'AI Specialist', 'Full Time', 'Develop AI model', 'Have 5 years experience in AI model', 'Haarlem', 2500.00, 'open', 8, '2025-04-05 20:04:11', '2025-04-05 20:04:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Michale', 'admin@example.com', 'admin12345', 'admin', '2025-03-23 09:27:36', '2025-03-23 09:27:36'),
(5, 'Michale', 'robben@gamil.com', 'admin12345', 'admin', '2025-03-23 09:29:39', '2025-03-23 09:29:39'),
(6, 'Hung Robben Le', 'lehungrobben18112004@gmail.com', '$2y$12$UMrdH7MMk/46g0T1B63qheSmSowWSYbu7.LyQGS9WxaaLrRJ7NtCq', 'user', '2025-03-27 07:08:26', '2025-03-27 07:08:26'),
(7, 'Nguyen Le Bao Vy ', 'nguyenlebaovy2003@gmail.com', '$2y$12$3QuZpqOj2p/pqTKbF8HB8ejahkdv91k2cQ6jNFPsUKgY977FOECqG', 'user', '2025-03-27 19:37:47', '2025-03-27 22:57:32'),
(8, 'Hung Vo', 'hungvo@gmail.com', '$2y$12$g4.Xs6k8cQ5R5rO1/qGwJOE9kzAiOFwqFoPqgZwGvcP/QAEHKpsum', 'admin', '2025-03-27 22:59:29', '2025-03-27 23:08:37'),
(9, 'RosaLe', 'rosa@gmail.com', '$2y$12$0rRPeCe3UPFZX1PSv1qpE.idkB0S9br6qRUEcxRUaZC6ABFsYCGC.', 'admin', '2025-03-27 23:02:43', '2025-03-27 23:08:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_app_user` (`user_id`),
  ADD KEY `fk_app_job` (`job_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jobs_admin` (`admin_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `fk_app_job` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_app_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `fk_jobs_admin` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2023 at 05:41 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `codetech`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(250) NOT NULL,
  `imgUrl` varchar(100) NOT NULL DEFAULT 'defaultImg.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Id`, `username`, `password`, `email`, `imgUrl`) VALUES
(1, 'admin', 'admin', 'admin@gmail.com', 'IMG-6433818af237a1.99963605.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryId` int(11) NOT NULL,
  `categoryName` varchar(100) NOT NULL,
  `categoryImg` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryId`, `categoryName`, `categoryImg`) VALUES
(21, 'Html and Css Developers', 'IMG-6426d0d96e83b9.84177057.png'),
(22, 'WordPress Developers', 'IMG-6426d1026e5924.35331759.png'),
(23, 'Python Developers', 'IMG-6426d12537f508.93904870.png'),
(24, 'Shopify Developers', 'IMG-6426d140013807.97392803.png'),
(25, 'Android Developers', 'IMG-6426d1583144a4.02502295.png'),
(26, 'JavaScript Developers', 'IMG-6426d17a3c83c3.14035667.png'),
(27, 'iOS Developers', 'IMG-6433e99dcb68d6.12550756.png'),
(28, 'Unity Developers', 'IMG-6426d1ae74cf55.91013745.png');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Id` int(11) NOT NULL,
  `username` varchar(80) NOT NULL,
  `phoneNumber` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL,
  `imgUrl` varchar(100) NOT NULL DEFAULT 'defaultImg.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Id`, `username`, `phoneNumber`, `email`, `password`, `imgUrl`) VALUES
(11, 'demo', '09759085522', 'demo@gmail.com', 'demo123', 'IMG-6464315086eea5.18622159.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `mssgId` int(11) NOT NULL,
  `mssgSubj` varchar(250) NOT NULL,
  `mssgTo` varchar(250) NOT NULL,
  `mssgFrom` varchar(250) NOT NULL,
  `mssgContent` varchar(250) NOT NULL,
  `mssgFile` varchar(300) NOT NULL,
  `isUserRead` tinyint(1) NOT NULL DEFAULT 0,
  `isAdminRead` tinyint(1) NOT NULL DEFAULT 0,
  `isAdmin` tinyint(1) NOT NULL DEFAULT 0,
  `mssgDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `serviceId` int(11) NOT NULL,
  `projectId` int(11) NOT NULL,
  `packageId` int(11) NOT NULL,
  `orderId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notificationId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `notificationText` varchar(100) NOT NULL,
  `notificationType` varchar(100) NOT NULL,
  `isRead` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `proposalId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderId` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `packageId` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `paymentMethod` int(11) NOT NULL,
  `pinCode` int(11) NOT NULL,
  `message` varchar(100) NOT NULL,
  `serviceId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `Id` int(11) NOT NULL,
  `packageType` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `deliveryWithIn` int(11) NOT NULL,
  `revisionLimit` varchar(100) NOT NULL,
  `sectionIncluded` varchar(100) NOT NULL,
  `serviceId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`Id`, `packageType`, `price`, `deliveryWithIn`, `revisionLimit`, `sectionIncluded`, `serviceId`) VALUES
(103, '1', 50, 1, '1 Revision', '1 page, Responsive design, Slider/scroller, Server upload, Browser compatibility', 31),
(104, '2', 100, 2, '1 Revision', '1 page, Responsive design, Slider/scroller, Server upload, Browser compatibility', 31),
(105, '3', 150, 2, '1 Revision', '1 page, Responsive design, Slider/scroller, Server upload, Browser compatibility', 31),
(106, '1', 50, 1, '1 Revision', '1 page, Responsive design, Slider/scroller, Server upload, Browser compatibility', 32),
(107, '2', 100, 2, '1 Revision', '1 page, Responsive design, Slider/scroller, Server upload, Browser compatibility', 32),
(108, '3', 150, 2, '1 Revision', '1 page, Responsive design, Slider/scroller, Server upload, Browser compatibility', 32),
(109, '1', 50, 1, '1 Revision', '1 page, Responsive design, Slider/scroller, Server upload, Browser compatibility', 33),
(110, '2', 100, 2, '1 Revision', '1 page, Responsive design, Slider/scroller, Server upload, Browser compatibility', 33),
(111, '3', 150, 2, '1 Revision', '1 page, Responsive design, Slider/scroller, Server upload, Browser compatibility', 33),
(112, '1', 50, 1, '1 Revision', '1 page, Responsive design, Slider/scroller, Server upload, Browser compatibility', 34),
(113, '2', 100, 2, '1 Revision', '1 page, Responsive design, Slider/scroller, Server upload, Browser compatibility', 34),
(114, '3', 150, 2, '1 Revision', '1 page, Responsive design, Slider/scroller, Server upload, Browser compatibility', 34),
(136, '1', 50, 3, '1', 'Basic Lambda Function A very simple Lambda Function', 42),
(137, '2', 200, 5, '2', 'Standard Lambda Function A Lambda function with Database and/or API Gateway integration', 42),
(138, '3', 375, 8, '3', 'Premium Lambda Function An advanced Lambda function with some integrations with AWS or 3rd-party ser', 42),
(139, '1', 5, 3, '3', 'I will do small programming tasks', 43),
(140, '2', 15, 3, '2', 'I will do complex programming tasks', 43),
(141, '3', 30, 8, '2', 'i will build desktop applications', 43),
(142, '1', 40, 1, '2', 'Basic APIs debugging Will help in finding any bugs in the flask/fastapi or Django application', 44),
(143, '2', 180, 3, '4', 'Flask, Django Rest Api Will provide the Flask/ FastApi / Django rest apis with custom requirements', 44),
(144, '3', 400, 5, '5', 'End to End application Django, Flask, FastApi web application with frontend and backend', 44),
(145, '1', 15, 2, '1', 'Only name change App logo, splash, packpage, icon', 45),
(146, '2', 45, 3, '1', 'BASIC plan + Customize', 45),
(147, '3', 150, 3, '2', 'Plan Standar + Install admin panel', 45);

-- --------------------------------------------------------

--
-- Table structure for table `project_proposal`
--

CREATE TABLE `project_proposal` (
  `proposalId` int(11) NOT NULL,
  `projectTitle` varchar(100) NOT NULL,
  `projectType` varchar(100) NOT NULL,
  `budgetOffer` varchar(100) NOT NULL,
  `projectCategory` varchar(100) NOT NULL,
  `projectSummary` varchar(100) NOT NULL,
  `projectDetails` varchar(100) NOT NULL,
  `projectImg` varchar(100) NOT NULL,
  `postedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `customerId` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `proofofpayment`
--

CREATE TABLE `proofofpayment` (
  `popId` int(11) NOT NULL,
  `gcashName` varchar(100) NOT NULL,
  `gcashNumber` int(11) NOT NULL,
  `proofImg` varchar(100) NOT NULL,
  `projectId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `serviceId` int(11) NOT NULL,
  `serviceTitle` varchar(100) NOT NULL,
  `serviceDescription` varchar(400) NOT NULL,
  `imgUrl` varchar(100) NOT NULL,
  `categoryId` int(100) NOT NULL,
  `postedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`serviceId`, `serviceTitle`, `serviceDescription`, `imgUrl`, `categoryId`, `postedAt`) VALUES
(31, 'I will do custom HTML, CSS,javascript reponsive website', 'Are you looking for a Web Developer to build a responsive, stunning, and user-friendly website from scratch or looking for someone to redesign &amp; develop your existing website, then you are in the right place! What you can think I can develop it for you!\r\n\r\nI am a full-stack Professional web developer with skills in HTML, CSS, Bootstrap, Javascript, Jquery, MySQL, and python flask. I can design', 'IMG-642e67fe213499.51468639.png', 21, '2023-04-06 23:21:07'),
(32, 'I will convert figma to html, xd to html, psd to html responsive bootstrap 5', 'Do you need to convert PSD to Html or sketch to Html or Xd to Html or Ai to Html or Zeplin to Html or Figma to Html or Invision to Html?\r\n\r\n\r\n\r\nWe are the best front-end developers team who have the best experience with 3000+ completed projects worldwide across various platforms.\r\n\r\n\r\n\r\nIf you have your design ready or if any thinking in your mind about the design then just let us know. We have 9+', 'IMG-642eaab95ec982.11472212.jpg', 21, '2023-04-06 23:21:07'),
(33, 'I will create custom wordpress website design, website development', 'Do you want a business website or an online E-commerce store?\r\n\r\nDo you want a blogging site?\r\n\r\nDo you want to redesign your current website ?\r\n\r\n\r\n\r\nThen we are there for you.\r\n\r\n\r\n\r\nSo, without wasting time let&#039;s get straight to the point,\r\n\r\n\r\n\r\nI have spent 5+ years in the development of WordPress, Woo-commerce &amp; Custom Websites to create a unique image of my work. I developed a 150+', 'IMG-642f5187dc6a80.19096920.png', 22, '2023-04-06 23:21:07'),
(34, 'I will do shopify website design, create shopify ecommerce storeeee', 'If you are a business person or thinking to start a new business then do not limit your self to small market, get your online store or shopify website and go online today.\r\n\r\nWe are a professional team of expert website developers, graphic designers and content writers who can turn your ideas/instructions into an impactful product.\r\n\r\nWe can create a professional shopify e-commerce website', 'IMG-6433a822ba7fb2.58732619.png', 24, '2023-04-09 06:14:13'),
(42, 'I will create AWS lambda function in python', 'Looking for an expert in AWS Lambda functions? You&#039;re in the right place!\r\n\r\nWhy should you hire me?\r\n\r\nI have 10+ years of experience implementing serverless architecture across organizations for various use cases with 100+ AWS Lambda functions and complex integration.\r\nI consistently deliver high-quality code.\r\nI keep myself updated with the current technology and best practices on performa', 'IMG-645ef840e9f6f7.66297923.png', 23, '2023-05-13 02:38:56'),
(43, 'I will write c, c plus plus, python, java code for you', 'I am a programmer with over 5 years of experience in C, C ++, C #, Python, Java, Go programming languages. I have experience in programming algorithms and script writing. I am sure I would be able to solve your task and deliver the exact final program you need.', 'IMG-645ef968615941.21537686.png', 23, '2023-05-13 02:43:52'),
(44, 'I will do your python projects, an application, machine deep learning, data science', 'Please Note :\r\n\r\nContact me before ordering.\r\n\r\n(I will create a customized offer for your solution after we clarified your requirements)\r\n\r\n\r\n\r\nHi! My name is Atul and I love programming in Python. I have 1 Year of experience and have programmed many projects on my own.\r\n\r\n\r\n\r\nI provide a variety of advanced python such as Web Scrapping, Image processing, data visualization and analysis, Numpy &a', 'IMG-645efbb56ed7d7.97741612.png', 23, '2023-05-13 02:53:41'),
(45, 'I will reskin rebrand any codecanyon android app, flutter app', 'I will reskin or rebrand any mobile app, Reskin experience over 1050+ mobile apps\r\n\r\nAndroid Native app\r\niOS Native app\r\nFlutter app\r\nionic cordova app\r\nreact native app\r\nunity game\r\n\r\n\r\nService Include:\r\n\r\nApp name change\r\nApp logo change\r\nApp color change\r\nSplash screen\r\nPackage name\r\nAdmob\r\nIn app purchase\r\nGoogle login\r\nFacebook login\r\nSms otp login\r\nApple login\r\nPush notification\r\nFirebase\r\nD', 'IMG-645f002c026de1.71367937.png', 25, '2023-05-13 03:12:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`mssgId`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notificationId`),
  ADD KEY `proposalId` (`proposalId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `packageId` (`packageId`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `serviceId` (`serviceId`);

--
-- Indexes for table `project_proposal`
--
ALTER TABLE `project_proposal`
  ADD PRIMARY KEY (`proposalId`),
  ADD KEY `customerId` (`customerId`);

--
-- Indexes for table `proofofpayment`
--
ALTER TABLE `proofofpayment`
  ADD PRIMARY KEY (`popId`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`serviceId`),
  ADD KEY `categoryId` (`categoryId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `mssgId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notificationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `project_proposal`
--
ALTER TABLE `project_proposal`
  MODIFY `proposalId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `proofofpayment`
--
ALTER TABLE `proofofpayment`
  MODIFY `popId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `serviceId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `proposalId` FOREIGN KEY (`proposalId`) REFERENCES `project_proposal` (`proposalId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `packageId` FOREIGN KEY (`packageId`) REFERENCES `package` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `package`
--
ALTER TABLE `package`
  ADD CONSTRAINT `serviceId` FOREIGN KEY (`serviceId`) REFERENCES `service` (`serviceId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_proposal`
--
ALTER TABLE `project_proposal`
  ADD CONSTRAINT `customerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`Id`);

--
-- Constraints for table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `categoryId` FOREIGN KEY (`categoryId`) REFERENCES `category` (`categoryId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

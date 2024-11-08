-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2024 at 12:18 PM
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
-- Database: `pet_healthcare`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$.OHVi0i/oclrfmNaOfQkROQAsDdmYvttWqpl/b6RncXdfYPvTGhtu');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointment_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `user_id` int(11) DEFAULT NULL,
  `vet_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appointment_id`, `date`, `time`, `notes`, `status`, `user_id`, `vet_id`) VALUES
(1, '2024-09-09', '12:00:00', 'Checkup', 'Cancelled', 6, 3),
(2, '2024-09-10', '12:00:00', 'Checkup', 'Cancelled', 6, 2),
(3, '2024-09-19', '11:00:00', 'vaccine', 'Cancelled', 6, 2),
(4, '2024-09-19', '12:00:00', 'Checkup', 'Cancelled', 6, 2),
(5, '2024-09-26', '19:00:00', '', 'Cancelled', 6, 2),
(6, '2024-09-24', '12:00:00', 'ad', 'Pending', 6, 2),
(7, '2024-09-26', '11:00:00', 'asd', 'Pending', 6, 2),
(8, '2024-09-27', '13:00:00', 'ad', 'Pending', 6, 2),
(9, '2024-09-28', '19:00:00', 'ad', 'Completed', 6, 2),
(10, '2024-09-28', '18:00:00', 'asd', 'Cancelled', 6, 2),
(11, '2024-11-05', '19:00:00', 'asd', 'Pending', 6, 2),
(12, '2024-09-28', '12:00:00', 'asd', 'Cancelled', 6, 2),
(13, '2024-09-25', '12:00:00', 'test', 'Pending', 6, 2),
(14, '2024-11-20', '11:00:00', '', 'Completed', 6, 2),
(15, '2024-11-01', '12:00:00', '', 'Pending', 6, 5),
(16, '2024-11-16', '12:00:00', '123', 'Pending', 6, 5),
(17, '2024-11-21', '12:00:00', '123', 'Pending', 6, 2),
(18, '2024-11-21', '13:00:00', '123', 'Pending', 6, 5),
(19, '2024-11-30', '21:00:00', '123', 'Pending', 6, 4),
(20, '2024-11-29', '18:00:00', '123', 'Pending', 6, 5),
(21, '2024-11-15', '18:00:00', '12', 'Pending', 6, 6),
(22, '2024-11-29', '17:00:00', '', 'Pending', 6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `product_id`, `name`, `price`, `quantity`, `image`, `user_id`) VALUES
(40, 8, 'Guzing 10kg Healthy & Beautiful Coat Dry Cat Food', 75, 1, 'catfood1.png', 6),
(41, 9, 'Whiskas 1.1kg Hairball Control Chicken & Tuna Dry Cat Food ', 20, 1, 'catfood2.jpg', 6);

-- --------------------------------------------------------

--
-- Table structure for table `consultation`
--

CREATE TABLE `consultation` (
  `consult_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vet_id` int(11) NOT NULL,
  `sender_type` enum('user','vet') NOT NULL,
  `message` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `attachment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `consultation`
--

INSERT INTO `consultation` (`consult_id`, `user_id`, `vet_id`, `sender_type`, `message`, `timestamp`, `attachment`) VALUES
(1, 6, 2, 'user', 'hi', '2024-09-22 21:26:08', NULL),
(2, 6, 2, 'vet', 'hi', '2024-09-22 21:29:53', NULL),
(3, 6, 2, 'vet', 'hello', '2024-09-22 21:40:09', NULL),
(4, 6, 2, 'vet', 'asd', '2024-09-22 21:42:45', NULL),
(5, 6, 2, 'vet', 'asd', '2024-09-22 21:42:46', NULL),
(6, 6, 2, 'vet', 'asd', '2024-09-22 21:42:47', NULL),
(7, 6, 2, 'vet', 'asd', '2024-09-22 21:42:47', NULL),
(8, 6, 2, 'vet', 'asd', '2024-09-22 21:42:48', NULL),
(9, 6, 2, 'vet', 'asd', '2024-09-22 21:42:49', NULL),
(10, 6, 2, 'vet', 'asd', '2024-09-22 21:42:50', NULL),
(11, 6, 2, 'user', 'yo', '2024-09-22 21:45:08', NULL),
(12, 6, 2, 'user', 'hi', '2024-09-22 21:46:35', NULL),
(13, 6, 2, 'user', 'hi', '2024-09-22 21:46:45', NULL),
(14, 6, 2, 'user', 'asdas asdasd', '2024-09-23 02:17:34', NULL),
(15, 6, 2, 'vet', 'Test test', '2024-09-23 02:17:56', NULL),
(16, 6, 2, 'user', NULL, '2024-09-29 18:56:06', 'uploads/66f9a2c604efe-INTI.png'),
(17, 6, 2, 'user', NULL, '2024-09-29 18:57:49', 'uploads/66f9a32dc9d98-LeeCheeKwong_MeetingLogW1.pdf'),
(18, 6, 2, 'user', 'test', '2024-09-29 18:59:23', NULL),
(21, 6, 2, 'vet', NULL, '2024-09-29 19:26:51', 'uploads/66f9a9fb9adb0-INTI.png'),
(23, 6, 2, 'vet', NULL, '2024-09-29 19:38:47', 'uploads/66f9acc78e370-LeeCheeKwong_MeetingLogW1.pdf'),
(26, 6, 2, 'user', '123', '2024-10-31 21:43:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hospitalization`
--

CREATE TABLE `hospitalization` (
  `hosp_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` varchar(255) NOT NULL,
  `treatment` text NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hospitalization`
--

INSERT INTO `hospitalization` (`hosp_id`, `pet_id`, `room_id`, `start_date`, `end_date`, `reason`, `treatment`, `status`) VALUES
(6, 12, 1, '2024-10-11', '2024-10-16', 'asd', 'asd', 'completed'),
(7, 12, 1, '2024-10-15', '2024-10-21', '123', '123', 'ongoing'),
(8, 12, 1, '2024-10-25', '2024-10-26', '123', '213', 'ongoing'),
(9, 12, 1, '2024-10-31', '2024-11-07', 'test', 'test', 'ongoing'),
(11, 15, 2, '2024-11-01', '2024-11-16', '123', '123', 'ongoing');

-- --------------------------------------------------------

--
-- Table structure for table `medical_record`
--

CREATE TABLE `medical_record` (
  `medical_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vet_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `diagnosis` varchar(255) DEFAULT NULL,
  `treatment` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `medical_report` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medical_record`
--

INSERT INTO `medical_record` (`medical_id`, `pet_id`, `user_id`, `vet_id`, `date`, `diagnosis`, `treatment`, `notes`, `medical_report`) VALUES
(1, 12, 6, 2, '2024-10-16', 'Test', 'Test', 'Test', 'Meeting Log_Lee Chee Kwong_APR2024.pdf'),
(2, 12, 6, 2, '2024-10-16', 'Test', 'Test', 'Test', 'Meeting Log_Lee Chee Kwong_APR2024.pdf'),
(3, 12, 6, 2, '2024-10-14', 'test', 'test', '123', 'Meeting Log_Lee Chee Kwong_APR2024.pdf'),
(5, 12, 6, 2, '2024-10-18', '123', '123', '123', 'Meeting Log_Lee Chee Kwong_APR2024.pdf'),
(6, 12, 6, 2, '2024-10-15', 'test', 'test', 'test', 'Meeting Log_Lee Chee Kwong_APR2024.pdf'),
(8, 12, 6, 2, '2024-11-01', 'asd', '123', '213', 'LeeCheeKwong_MeetingLogW1.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `hp_number` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `products` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `status` varchar(255) NOT NULL,
  `order_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `user_id`, `name`, `hp_number`, `address`, `products`, `price`, `status`, `order_date`) VALUES
(17, 6, 'Lee Chee Kwong', '0126821671', 'Jalan Lintang Slim, Georgetown, Penang, 11600, Malaysia', 'Guzing 10kg Healthy & Beautiful Coat Dry Cat Food (Qty: 1), Whiskas 1.1kg Hairball Control Chicken & Tuna Dry Cat Food  (Qty: 2), Snappy Tom 1.5kg Salmon With Chicken Dry Cat Food (Qty: 1)', 137, 'Delivered', '2024-11-08'),
(18, 6, 'Lee Chee Kwong', '0126821671', 'Jalan Lintang Slim, Georgetown, Penang, 11600, Malaysia', 'Nylabone Bone For Puppy Teething (Qty: 1), Trixie Running Belt with Leash (Qty: 1), Petdiatric Gum-max Pet Gums Supplement 30 Softgels  (Qty: 1)', 232, 'Delivering', '2024-11-08'),
(19, 6, 'Lee Chee Kwong', '0126821671', 'Jalan Lintang Slim, Georgetown, Penang, 11600, Malaysia', 'Nylabone Bone For Puppy Teething (Qty: 1), Artero Aurigel Ear Cleaner (Qty: 1), Trixie Soft Bristle Brush for Cats (Qty: 1)', 148, 'Processing', '2024-11-08'),
(20, 6, 'Lee Chee Kwong', '0126821671', 'Jalan Lintang Slim, Georgetown, Penang, 11600, Malaysia', 'Guzing 10kg Healthy & Beautiful Coat Dry Cat Food (Qty: 2)', 150, 'Processing', '2024-11-08');

-- --------------------------------------------------------

--
-- Table structure for table `pet`
--

CREATE TABLE `pet` (
  `pet_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `breed` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `preferences` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pet`
--

INSERT INTO `pet` (`pet_id`, `name`, `type`, `breed`, `age`, `image`, `preferences`, `user_id`) VALUES
(12, 'Test2', 'Cat', 'test2', 5, 'uploaded_img/cat.jpeg', 'Test', 6),
(15, 'asd', 'Dog', 'asd', 1, 'uploaded_img/OryS.jpg', '12', 6),
(16, '123', 'Dog', '123', 123, 'uploaded_img/CuteRyS.jpg', '12', 6);

-- --------------------------------------------------------

--
-- Table structure for table `pet_vaccine`
--

CREATE TABLE `pet_vaccine` (
  `vaccine_id` int(11) NOT NULL,
  `vaccine_name` varchar(255) NOT NULL,
  `vaccine_description` text DEFAULT NULL,
  `symptoms` text DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `importance_level` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pet_vaccine`
--

INSERT INTO `pet_vaccine` (`vaccine_id`, `vaccine_name`, `vaccine_description`, `symptoms`, `type`, `importance_level`) VALUES
(1, ' Canine Distemper', 'Canine distemper is a contagious and serious disease caused by the canine distemper virus. The virus attacks the respiratory, gastrointestinal, and nervous systems of dogs.\r\n\r\nAll dogs are at risk of canine distemper. Those at particular risk include puppies younger than four months and dogs that have not been vaccinated against canine distemper virus.\r\n\r\nIn addition to dogs, canine distemper virus can infect ferrets as well as a wide range of other mammals, especially carnivores. These include several wildlife species such as wild canines (e.g., foxes and wolves), raccoons, and skunks. Cats also may become infected but are unlikely to get sick.\r\n\r\nThe good news is that canine distemper can be prevented in dogs—and ferrets—through vaccination.', 'As the virus spreads to the respiratory and gastrointestinal systems, dogs typically develop the following clinical signs:\r\n\r\nDischarge from the eyes and nose\r\nFever\r\nCoughing\r\nLethargy\r\nReduced appetite\r\nVomiting\r\nDiarrhea\r\nAs the virus attacks the nervous system, dogs also may show neurologic signs:\r\n\r\nWalking in circles, unable to follow a straight path\r\nHead tilt\r\nLack of coordination\r\nMuscle twitches\r\nConvulsions with jaw-chewing movements (“chewing gum fits”) and drooling\r\nSeizures\r\nPartial or complete paralysis', 'Dog', 'Core'),
(2, 'Canine Parvovirus', 'Canine parvovirus infection is a highly contagious disease caused by canine parvovirus type 2 (CPV-2). There are several strains of CPV-2, and all produce similar signs. The disease they cause is commonly called “parvo.”\r\n\r\nThe virus attacks white blood cells and the gastrointestinal tract of dogs and other canids like coyotes, wolves, and foxes. In puppies, the virus also can damage the heart muscle.\r\n\r\nAll dogs are susceptible to canine parvovirus, although some dogs are at greater risk than others. These include puppies between 6 and 20 weeks of age, unvaccinated or incompletely vaccinated dogs, and certain breeds, such as the following:\r\n\r\nRottweilers\r\nDoberman pinschers\r\nBull terrier breeds\r\nGerman shepherds\r\nEnglish springer spaniels', 'The signs of parvovirus infection vary from dog to dog, depending on the severity of the infection. These are some important signs to watch out for:\r\n\r\nLethargy\r\nLoss of appetite\r\nVomiting\r\nSevere, often bloody, diarrhea\r\nAbdominal pain and bloating\r\nFever or low body temperature (hypothermia)', 'Dog', 'Core'),
(3, 'Canine Hepatitis', 'ICH is a highly contagious virus caused by the canine adenovirus 1 (CAV-1) that affects dogs and some wildlife including wolves, coyotes, and bears. Although hepatitis refers to liver inflammation, ICH can affect multiple organ systems including the lungs, gastrointestinal system, kidneys, and central nervous system. ICH can also cause eye problems and clotting disorders. This virus is often confused with other infectious diseases including parvovirus and infectious tracheobronchitis (i.e., kennel cough), because the organ systems affected and the clinical signs are similar. ICH is primarily spread through exposure to an infected animal’s bodily fluids, including urine, feces, or saliva. Puppies, or adult dogs with underlying medical problems, are most at risk for a severe infection, and older dogs may experience mild signs that do not require treatment. Common ways a dog may become infected with ICH include:\r\n\r\nIngestion of an infected dog’s feces or urine\r\nDirect contact with an infected dog\r\nExposure to infected wildlife\r\nContact with an infected dog’s sneeze droplets\r\nStaying at a boarding facility or shelter where infected dogs are present', 'ICH signs are similar to other common dog infectious diseases, and severity will depend on the infected dog’s immune system. Signs may occur two to five days after ICH exposure, and are dependent on the organ system that is affected. Dogs who are diagnosed with ICH may shed the virus in their urine for more than six months. ICH may be fatal in some cases. Signs may include:\r\n\r\nFever\r\nNasal congestion\r\nSneezing and coughing\r\nEye and nasal discharge\r\nRedness around the eyes\r\nCorneal clouding or a blue hue to the eyes\r\nDifficulty breathing\r\nLethargy\r\nElevated heart rate\r\nLoss of appetite\r\nVomiting and diarrhea\r\nDepression\r\nBruising\r\nAbdominal pain\r\nYellowing of the skin\r\nSeizures', 'Dog', 'Core'),
(4, 'Feline Panleukopenia', 'Feline panleukopenia (also called feline distemper) is a highly contagious, potentially fatal disease of cats caused by the feline panleukopenia virus (FPV, also called feline parvovirus). Kittens are most severely affected. Despite the names used to describe feline panleukopenia, the disease is not to be confused with canine distemper or canine parvovirus infection, which are caused by different viruses.\r\n\r\nFPV attacks cells that are rapidly growing and dividing, such as those in the lymph nodes, bone marrow, intestines, and developing fetuses. The name \"panleukopenia\" stems from the damaging effect that FPV has on white blood cells (leukocytes), which the body needs to fight off infection.\r\n\r\nFortunately, there are highly effective vaccines available to help protect cats against feline panleukopenia.', 'Most cats with FPV infection have no signs at all and appear normal. Cats that do become ill—most often those younger than a year old—may have the following:\r\n\r\nDiarrhea\r\nVomiting\r\nFever\r\nDepression/lethargy\r\nLoss of appetite\r\nDehydration (may appear as sunken eyes or dry gums)\r\nPainful belly\r\nAffected cats—especially kittens—also may suddenly die. If your cat shows any of the above signs, immediately contact your veterinarian. And, to keep the disease from spreading, keep your cat away from other cats.', 'Cat', 'Core'),
(5, 'Feline Calicivirus', 'Feline calicivirus (FCV) is a viral pathogen that causes upper respiratory tract infection in cats. Infection most commonly resembles a cold, but severe infections involving the lungs, joints, and/or other organs can occur. \r\n\r\nThe virus is most commonly found in facilities that house multiple cats, such as rescues, shelters, or breeding facilities. Yet it can spread readily in households with only a few cats. Kittens and young adult cats are usually at the highest risk for infection, but a cat of any age can be affected. ', 'Some cats become infected and do not show any clinical signs. If there are signs, they can range from mild to severe, with severe signs resulting in death. The most common signs are: \r\n\r\nSneezing \r\n\r\nNasal discharge \r\n\r\nUlceration of the surface of the nose (skin broken open) \r\n\r\nConjunctivitis (eye inflammation) \r\n\r\nEye discharge  \r\n\r\nAn affected cat may also develop the following: \r\n\r\nUlcers on the lip, tongue, or elsewhere in the mouth, often accompanied by drooling \r\n\r\nFever \r\n\r\nLethargy  \r\n\r\nDehydration \r\n\r\nPoor appetite due to severe nasal congestion and/or oral ulcerations ', 'Cat', 'Core'),
(6, 'Feline Herpesvirus 1 (FHV-1)', 'In cats, the feline herpesvirus-1 (FHV-1) is a virus affecting primarily the upper respiratory tract and the structures of the eye. Transmission occurs between cats by direct contact with infected oral, nasal, or eye secretions. Within 24 hours, a newly infected cat can transmit the feline herpes virus to other cats, so it’s important to seek veterinary care immediately.  \r\n\r\nFHV-1 is the most common viral cause of sneezing and nasal discharge in cats. Changes to the structures of the eye are also associated with feline herpes infection. \r\n\r\nYoung cats are most affected, but infection can occur at any age. Cats from multi-cat households, shelters, rescues, and catteries are at increased risk for infection. Outdoor, stray, and feral cats may become infected from contact with infected cats while outside. ', 'Clinical signs can vary in severity. Upper respiratory signs include: \r\n\r\nSneezing \r\n\r\nNasal discharge  \r\n\r\nFever \r\n\r\nLoss of appetite \r\n\r\nCoughing \r\n\r\nChanges to the eye may include: \r\n\r\nEye discharge \r\n\r\nConjunctivitis (pink eye) or chemosis (enlargement of the pink lining of the eye) \r\n\r\nChanges in the color of the eye \r\n\r\nCorneal ulcer (scratches or tears of the clear part of the eye) \r\n\r\nIn severe cases, changes to the skin around the face may include: \r\n\r\nRedness \r\n\r\nSwelling \r\n\r\nCrusting \r\n\r\nLoss of hair ', 'Cat', 'Core');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `prescription_id` int(11) NOT NULL,
  `pet_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `vet_id` int(11) DEFAULT NULL,
  `medication_name` varchar(255) DEFAULT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `frequency` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`prescription_id`, `pet_id`, `user_id`, `vet_id`, `medication_name`, `dosage`, `frequency`, `start_date`, `end_date`, `notes`) VALUES
(1, 12, 6, 2, 'Test', 'Test', 'Test', '2024-10-14', '2024-10-30', 'Test'),
(2, 12, 6, 2, 'Test', 'Test', 'Test', '2024-10-14', '2024-10-17', '123'),
(3, 12, 6, 2, '123', '123', '123', '2024-10-17', '2024-10-18', '123'),
(4, 12, 6, 2, 'test', 'test', 'test', '2024-10-15', '2024-10-23', 'test'),
(5, 12, 6, 2, '123', '123', '123', '2024-11-08', '2024-11-16', '123');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `price` double NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `name`, `description`, `type`, `image`, `price`, `quantity`) VALUES
(8, 'Guzing 10kg Healthy & Beautiful Coat Dry Cat Food', 'GUZING Cat Food \r\n\r\nCahaya Cat Food is an easy-to-digest, nutritious, and popular food for cats. \r\n\r\nBenefit: \r\nEasy to digest, nutritious & loved by the cat. \r\nA strong immuse system & can prevent the cat from disease \r\nHealthy & beautiful fur\r\nRich in Omega 3 & Omega 6\r\nPH balance reduces the risk of kidney stone disease', 'Food', 'catfood1.png', 75, 97),
(9, 'Whiskas 1.1kg Hairball Control Chicken & Tuna Dry Cat Food ', 'Whiskas® Hairball Control For Adult Cats \r\n\r\nCats spend at least five hours a day grooming themselves. Their tongues are coarse like sandpaper, and easily pick up loose hairs which are then swallowed. Although the hairs usually pass through the intestines and excreted in the stool, some cats (especially long-haired cats) are prone to developing hairballs in their stomach. Cats may regurgitate hairballs (which is unpleasant and inconvenient in itself).\r\n\r\nWHISKAS® Hairball Control with 50%^ more Sugar Beet Pulp provides natural fibre to control hairball formation and help excrete ingested hairs. Cat lovers are sure to appreciate the reduced hairball surprises!\r\n\r\nWHISKAS® Hairball Control Features:\r\n\r\n50%^ more Sugar Beet Pulp to help excrete ingested hair\r\nSugar Beet Pulp acts as a prebiotic to support healthy gut flora for a healthy digestive system\r\nOmega-6 and zinc for healthy skin and coat\r\nAntioxidants\r\nQuality protein helps maintain healthy muscles\r\nComplete and balanced nutrition for adult cats', 'Food', 'catfood2.jpg', 20, 98),
(10, 'Snappy Tom 1.5kg Salmon With Chicken Dry Cat Food', 'Dry Food | Salmon With Chicken\r\n\r\nMade with the recipe full of fibre, protein, and vitamins with a savoury gourmet flavour which your cat will love. You are encouraged to provide both dry and wet food to your cat for optimal nutrition for ideal body condition and stay healthy.\r\nNatural Ingredients\r\nNo Added Salt / Flavors.\r\nNo Added Preservative\r\nComplete & Balanced Nutrition', 'Food', 'catfood3.jpg', 22, 99),
(11, 'Royal Canin Shih Tzu Adult 1.5kg Dry Dog Food', 'Royal Canin Breed Health Nutrition Shih Tzu Adult Dry Dog Food Over 10 months old\r\n\r\nHealthy skin\r\nAdult helps support the skin’s “barrier” role (exclusive complex), maintain skin health (EPA & DHA, vitamin A) and nourish the coat. Enriched with borage oil.\r\n\r\nDental health\r\nThis formula helps reduce the risk of tartar formation thanks to calcium chelators.\r\n\r\nStool & odour reduction\r\nThis formula helps reduce faecal smell and volume.\r\n\r\nExclusive kibble design: special brachycephalic jaw\r\nA kibble exclusively designed to make it easier for the Shih Tzu to pick up and to encourage them to chew.', 'Food', 'dogfood1.png', 58, 100),
(12, 'Royal Canin Xsmall Junior / Puppy 3kg Dry Dog Food', 'Royal Canin Size Health Nutrition X-Small Puppy Dry Dog Food For very small breed puppies (adult weight up to 4 kg) - Up to 10 months old.\r\n\r\nIMMUNE SYSTEM SUPPORT\r\nGrowth is an essential stage in your dog’s life: it is the time of big changes, discoveries and new encounters. During this key period, the puppy’s immune system develops gradually. X-SMALL PUPPY helps support your puppy’s natural defences thanks particularly to a patented* complex of antioxidants including vitamin E.\r\n\r\nHEALTHY DIGESTION AND TRANSIT\r\nA balanced intake of fibres (including psyllium) to help facilitate intestinal transit and to contribute to good stool quality.\r\n\r\nINTENSE ENERGY CONTENT\r\nMeets the energy needs of very small breed puppies during the growth period, and satisfies fussy appetites.', 'Food', 'dogfood2.png', 90, 100),
(13, 'Vitalplus Dog Pork Recipe 4kg Dry Dog Food ', 'VitalPlus Premium Dog Food Pork Recipe is a complete and balanced food for dogs of all breeds. It is made with real meat of high biological value and energy dense selected quality proteins with vitamin and minerals the dogs require for a healthy life. Our recipes contains highly digestible carbohydrates to aid in digestion and help preventing intestinal disorder. In addition, it is enriched with various supplements to support the overall wellbeing of the dogs – Vitamin A, Copper and Essential Fatty Acids to promote a soft and shiny coat, Vitamin E and Selenium to strengthen the immune system. Hard and crunchy kibble also helps to strengthen the teeth and promote dental care.', 'Food', 'dogfood3.jpg', 50, 100),
(14, 'Trixie Rod with Long Plush Mouse', 'Trixie Rod with Long Plush Mouse\r\n- With a teddy mouse in elastic band.\r\n\r\n- With Catnip: with stimulating effect and promoter of the natural feline instinct of the game.\r\n\r\n- Professional Sale: 4 UDS.\r\n\r\n- Measure: 50 cm.', 'Toys', 'cattoy1.jpg', 15, 100),
(15, 'KONG Feather Mouse for Cats', 'KONG Feather Mouse for Cats\r\nThese soft, tasty plush toys have a relentable bag that keeps the grass fresh gaters and on its site.\r\n\r\nToys include a relentable flask containing a generous amount of high quality grass of high quality Kong brand.\r\n\r\nWhen the aroma of the grass Gatera begins to get lost, enough to add fresh gaters grass for another fun round.\r\n\r\nThe grass Gatera can be stored in the freezer to preserve its freshness.\r\n\r\nThe toy can be put in the washing machine if the grass is removed gaters.\r\n\r\n- Filled toy with Grass Gatera\r\n\r\n- Includes a grass jar of grass High quality Kong brand\r\n\r\n- Aroma, texture and irresistible form', 'Toys', 'cattoy2.jpg', 25, 100),
(17, 'Hagen Vesper V-Box Big Walnut', 'Hagen Vesper V-Box Big Walnut\r\nThe V-Box, is the perfect place for your cat to rest or play. The little one has a cozy cave with a comfortable cushion which you can remove to wash it. The large V-Box has multiple levels with places to relax. Both sizes have wicker balls and scraper posts with which your cat will have fun and scratch for hours.\r\n\r\nThe big V-BOX is a compact adventure world for cats. Multiple levels and resting places offer the opportunity to relax and play, while the long scratching post invites the cat to enjoy sharpening its nails.\r\n\r\n\r\nDimensions: 50 x 40 cm, height: 78 cm', 'Toys', 'cattoy3.jpg', 200, 100),
(18, 'Musqui Blue Tennis Ball for Dogs', 'The Musqui tennis ball is the perfect ally for fun afternoons in the park. \r\n\r\n \r\n\r\nThis ball is made of rubber which guarantees its durability and bite resistance, while still being lightweight, so you can throw it with ease and your dog can fetch it without any problem. \r\n\r\nIts size of 6 cm makes it easy for both small and large dogs to play with.  \r\n\r\nThanks to its material, your dog can bite it without damaging its gums or teeth and at the same time it relieves the tension accumulated in each bite. ', 'Toys', 'dogtoy1.jpg', 10, 100),
(19, 'Nylabone Bone For Puppy Teething', 'Nylabone Bone For Puppy Teething\r\nSpecially designed for teeth Cubs still have no adult teeth.\r\n\r\nsoft and flexible materials.\r\n\r\na safe product, recommended by veterinarians.\r\n\r\nhelps clean teeth and control plaque and tartar.\r\n\r\nhelps puppies develop good habits nibbling and chewing.', 'Toys', 'dogtoy2.jpg', 30, 98),
(20, 'Ebi Blue Mint Frisbee Rubber Flavoured Frisbee', 'Ebi Blue Mint Frisbee Rubber Flavoured Frisbee\r\nThis fun but also tasty dog ​​toy provides hours of entertainment. The soft, durable and flexible rubber is completely safe and retains flavor for a long period of time.     Features:Flexible TPRSturdy and durable toyMint flavor provides an extra fun factor', 'Toys', 'dogtoy3.jpg', 38, 100),
(21, 'Catit 2.0 Feeding Dish Double White 200ml X 2', 'Feeding Dish Single – White\r\nShallow, whisker stress-free design\r\nHygienic, removable stainless-steel dish\r\nDish capacity 200 ml (6.83 fl oz)\r\nDurable holder with non-skid feet\r\nDishwasher safe and easy to clean\r\nAlso available in black\r\nEasy to clean\r\nThe stainless steel dish can be removed from its holder for easy cleaning. It is dishwasher safe.\r\nDurable holder with non-skid feet\r\nWhisker stress-free design\r\nThe Feeding Dish has a shallow design so your cat can eat comfortably, without placing any stress on their whiskers.', 'Accessories', 'catacc1.jpg', 28, 100),
(22, 'Catit Stainless Steel Top Fountain White 2l ', 'New & Improved stainless steel top\r\nNew and improved high grade stainless steel\r\nHygienic and dishwasher safe top\r\nLarge water-to-air surface maximizes oxygenation\r\nCompact 2 L / 64 fl oz reservoir\r\nIncludes filter pad\r\nWith low-voltage pump\r\nEasy to disassemble and clean\r\nRe-circulating system\r\nThe new and improved high grade Stainless Steel Drinking Fountain is hygienic and dishwasher safe.\r\nThe stainless steel top is clean and shallow, so your cat can drink without putting any stress on their whiskers.\r\nThe fountain encourages your cat to drink more, which helps prevent urinary tract diseases. Its stylish design matches any home interior.\r\nLarge water-to-air surface maximizes oxygenation\r\nThe included Dual Action Fountain Filter collects large particles and debris. The activated carbon helps reduce odors and absorbs impurities.\r\nDishwasher safe top\r\nCombine with a Catit Placemat to protect your floor and countertop from food and water spills.', 'Accessories', 'catacc2.jpg', 98, 100),
(23, 'Catit Voyageur Blue/grey Medium', 'Durable, sturdy and safe cat carrier\r\nEasy to assemble thanks to side latch system\r\nMeets airline regulations\r\nSmall: 48.3 L x 32.6 W x 28 H cm (19 x 12.8 x 11 in)', 'Accessories', 'catacc3.jpg', 60, 100),
(24, 'Dogit Elevated Dish With Stainless Steal Insert Small 300ml White ', 'Elevated dog dish designed to provide more comfortable eating or drinking position for your dog\r\nExcellent for small pets with arthritis, joint pain, or digestive problem\r\nVet-recommended stainless steel dog bowl insert is removable and dishwasher-safe\r\nDesigned with anti-skid feet to keep the dog bowl from moving\r\nIdeal for small dogs, older dogs, or dogs with joint and muscle problems\r\nHeight: 9cm / 3.5\"\r\nBowl Capacity: 300ml', 'Accessories', 'dogacc1.jpg', 28, 100),
(25, 'Dogit 2in1 Durable Bowl With Ss Insert Large 1.6l Black Dia. 8 ', 'The Dogit 2-in-1 Bowl is a versatile 2-in-1 dish. The colorful outer bowl can be separated from the removable stainless steel insert allowing for the possibility of a food and water dish. The dishes come in a variety of colors and patterns. The Dogit 2-in-1 Bowl holds up to 54.1 fluid ounces and is dishwasher safe. It is the perfect way to get your dog the food and drink it needs.', 'Accessories', 'dogacc2.jpg', 65, 100),
(26, 'Trixie Running Belt with Leash', 'Trixie Running Belt with Leash\r\n\r\n Correa support with rapid release function.\r\n Suitable for walking, running, canicross, etc.\r\n Special system in the strap distributes the pressure exerted by the dog\r\n Closing that avoids tension\r\n Dog pulls are cushioned with the expansible strap\r\n Comfortable to carry thanks to ergonomic adjustment and mesh\r\n Correa completely adjustable with hand loop, also suitable for driving the dog normally\r\n With several pockets and holders\r\n impermeable zippers\r\n Integrated Dog Garbage School Dispenser\r\n Reflective parts\r\n Flat Correa', 'Accessories', 'dogacc3.jpg', 130, 99),
(27, 'Le Salon Cat Self-cleaning Slicker Brush', 'The Le Salon Self-Cleaning Slicker Brush removes dead hair and prevents matting.\r\nThe revolutionary mechanism releases hair in one easy step.\r\nBrush against the coat growth to loosen dead hair and skin, then in the direction of the growth to remove debris and polish the coat.\r\nSuitable for the general grooming of most breeds.', 'Grooming', 'catgroom1.jpg', 25, 100),
(28, 'Le Salon Essentials Cat Guillotine Nail Cutter ', 'Le Salon Essentials Cat Guillotine Nail Cutter, with charcoal handle.\r\nAll-purpose nail cutter for cats. Regular nail clipping is recommended for every pet. Always keep a cautery substance on hand while trimming (such as Hagen Antiseptic First aid Cream) to stop bleeding in case of accidents.\r\nRegular nail clipping is recommended for every pet. Le Salon Essentials Grooming Products offer a full range of grooming tools and accessories. Choose from a wide assortment of brushes, combs and grooming accessories in a variety of styles and sizes.\r\nRegular grooming is recommended for every pet to ensure a healthy and trouble-free coat.', 'Grooming', 'catgroom2.jpg', 18, 100),
(29, 'Trixie Soft Bristle Brush for Cats', 'Trixie Soft Bristle Brush for Cats\r\nSoft brush for cats with plastic bristles that acts on the coat of your pet and has protective tips to do no harm, also includes a non-slip handle.\r\n\r\nFeatures:\r\n\r\nActs on the coat\r\n.\r\nExtra soft plastic bristles with protective tip\r\nHandle with non-slip rubberized grip', 'Grooming', 'catgroom3.jpg', 38, 99),
(30, 'Artero Aurigel Ear Cleaner', 'Artero Aurigel Ear Cleaner\r\nCleaner ear canal for dogs and cats.\r\n\r\nPresentation 100ml container with dispenser.\r\n\r\neasy and accurate to apply.\r\n\r\nGel facilitating dosing and once in contact with skin liquefies causing rapid penetration and emulsificació excess wax and dirt. Thanks to an extremely mild nonionic surfactant clean respecting the epidermal equilibrium.\r\n', 'Health', 'health1.jpg', 80, 99),
(31, 'Petdiatric Gum-max Pet Gums Supplement 30 Softgels ', '496MG X 30 SOFTGELS\r\nCONTAINS COQ10\r\n\r\nEnriched Nutraceutical to Promote Periodontal and Gum Health\r\nGUM-MAX pet supplement, the only one that comes in soft-gel form, one softgel a day dosage, reduces the gums inflammation due to periodontal disease, reduces bad breath due to periodontal disease. When all others mouth freshening products doesn\'t seem to work, try GUM-MAX.', 'Health', 'health2.jpg', 72, 99),
(32, 'Furvit Cat Candy\'s Nutri Lick Beef 200g (5gx40tubes) Premium Cat Supplement ', 'CatCandy Nutri Lick – the ultimate premium supplement – uses high quality ingredients sourced from a group of well-established industry-leading suppliers globally. With their internationally recognized expertise and manufacturing facilities as well as traceable and trusted supply chain, the quality and safety of this supplement is assured. The ingredients used in this delicious yet incredibly functional supplement are sourced from countries like Norway, New Zealand, Chile, Brazil, United States of America, China, India, Japan and a little closer to home – Thailand and Malaysia.\r\n\r\nCore Benefits\r\nHealthy Heart & Liver\r\nEnhances Nervous System\r\nPromotes Bone Growth\r\nNatural Anti-Inflammatory​\r\nReduces Stress & Anxiety\r\nImproves Digestive Health\r\nBoosts Energy & Mood\r\nStrengthen Immune System\r\nHealthy Skin, Hair & Nails', 'Health', 'health3.jpg', 80, 100);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `total_space` int(11) NOT NULL,
  `space_remaining` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `room_img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `location`, `size`, `total_space`, `space_remaining`, `status`, `room_img`) VALUES
(1, '101', '144 sqft', 3, 0, 'available', 'room1.jpeg'),
(2, '102', '144 sqft', 2, 1, 'available', 'room1.jpeg'),
(3, '103', '144 sqft', 3, 3, 'available', 'room1.jpeg'),
(4, 'Cage1', '100 sqft', 5, 5, 'available', 'cage1.jpg'),
(5, 'Cage2', '100 sqft', 5, 5, 'available', 'cage1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `reset_code` varchar(10) DEFAULT NULL,
  `reset_code_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `email`, `profile_pic`, `password`, `reset_code`, `reset_code_expiry`) VALUES
(1, 'test', 'testing@gmail.com', 'uploaded_img/default_profile_pic.png', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL),
(2, 'test2', 'test2@gmail.com', NULL, 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL),
(3, 'testing123', 'testing1@gmail.com', NULL, '827ccb0eea8a706c4c34a16891f84e7b', NULL, NULL),
(4, 'LeeCK', 'ivanlee0224@gmail.com', NULL, 'ac1c8d64fd23ae5a7eac5b7f7ffee1fa', '695515', '2024-09-02 00:30:48'),
(5, 'CKLee', 'p21013018@student.newinti.edu.my', NULL, 'ac1c8d64fd23ae5a7eac5b7f7ffee1fa', '326039', '2024-09-02 15:26:51'),
(6, 'Shadow', 'shadowcat0277@gmail.com', 'uploaded_img/6723ed97c852e-OryS.jpg', 'ac1c8d64fd23ae5a7eac5b7f7ffee1fa', '764940', '2024-11-01 10:49:01');

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_record`
--

CREATE TABLE `vaccine_record` (
  `vaccine_record_id` int(11) NOT NULL,
  `vaccine_name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `pet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vaccine_record`
--

INSERT INTO `vaccine_record` (`vaccine_record_id`, `vaccine_name`, `start_date`, `end_date`, `pet_id`) VALUES
(4, 'Feline Panleukopenia', '2024-11-01', '2024-11-29', 12),
(6, 'Feline Herpesvirus 1 (FHV-1)', '2024-11-13', '2024-11-30', 12);

-- --------------------------------------------------------

--
-- Table structure for table `veterinary`
--

CREATE TABLE `veterinary` (
  `vet_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `veterinary`
--

INSERT INTO `veterinary` (`vet_id`, `name`, `email`, `password`, `profile_picture`) VALUES
(2, 'TestVet2', 'testingvet@gmail.com', '$2y$10$DnytrvswCrpqo3DQHjmOG.tL2xAJgazEACX6EARFozPpaZCCT9tWG', 'uploads/cat boy.jpg'),
(3, 'Vet3', 'vet3@gmail.com', '$2y$10$IX.jFlbsUap1yaTlgl/MpeldrFX95QfikJi2LpYtmUIave/YV6tJG', 'uploads/default_profile_pic.png'),
(4, 'New Vet', 'newvet@gmail.com', '$2y$10$GNf1Xecg0nzoPR7aRuur/.VcSNoBe2ol16WSSGKlrx6Ce694BrpNK', 'uploads/default_profile_pic.png'),
(5, 'Test', 'ali@gmail.com', '$2y$10$AJK4Iz9wMDKZdvN/GD4H7OXKhk5M0iVHH0VMfg64ZryVG8Zk9U4r.', 'uploads/default_profile_pic.png'),
(6, 'Ali', '1232@gmail.com', '$2y$10$5PTZVQu3y5pJItN4kY/hsO2/1RNa2CHpTKjvDm/hEa9jhnQ8Ke37K', 'uploads/default_profile_pic.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vet_id` (`vet_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `cart_ibfk_1` (`product_id`);

--
-- Indexes for table `consultation`
--
ALTER TABLE `consultation`
  ADD PRIMARY KEY (`consult_id`);

--
-- Indexes for table `hospitalization`
--
ALTER TABLE `hospitalization`
  ADD PRIMARY KEY (`hosp_id`),
  ADD KEY `fk_room` (`room_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `medical_record`
--
ALTER TABLE `medical_record`
  ADD PRIMARY KEY (`medical_id`),
  ADD KEY `pet_id` (`pet_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vet_id` (`vet_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pet`
--
ALTER TABLE `pet`
  ADD PRIMARY KEY (`pet_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pet_vaccine`
--
ALTER TABLE `pet_vaccine`
  ADD PRIMARY KEY (`vaccine_id`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`prescription_id`),
  ADD KEY `pet_id` (`pet_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vet_id` (`vet_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `vaccine_record`
--
ALTER TABLE `vaccine_record`
  ADD PRIMARY KEY (`vaccine_record_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `veterinary`
--
ALTER TABLE `veterinary`
  ADD PRIMARY KEY (`vet_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `consultation`
--
ALTER TABLE `consultation`
  MODIFY `consult_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `hospitalization`
--
ALTER TABLE `hospitalization`
  MODIFY `hosp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `medical_record`
--
ALTER TABLE `medical_record`
  MODIFY `medical_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pet`
--
ALTER TABLE `pet`
  MODIFY `pet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pet_vaccine`
--
ALTER TABLE `pet_vaccine`
  MODIFY `vaccine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `prescription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vaccine_record`
--
ALTER TABLE `vaccine_record`
  MODIFY `vaccine_record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `veterinary`
--
ALTER TABLE `veterinary`
  MODIFY `vet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`vet_id`) REFERENCES `veterinary` (`vet_id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `hospitalization`
--
ALTER TABLE `hospitalization`
  ADD CONSTRAINT `fk_room` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`),
  ADD CONSTRAINT `hospitalization_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pet` (`pet_id`) ON DELETE CASCADE;

--
-- Constraints for table `medical_record`
--
ALTER TABLE `medical_record`
  ADD CONSTRAINT `medical_record_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pet` (`pet_id`),
  ADD CONSTRAINT `medical_record_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `medical_record_ibfk_3` FOREIGN KEY (`vet_id`) REFERENCES `veterinary` (`vet_id`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `pet`
--
ALTER TABLE `pet`
  ADD CONSTRAINT `pet_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pet` (`pet_id`),
  ADD CONSTRAINT `prescription_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `prescription_ibfk_3` FOREIGN KEY (`vet_id`) REFERENCES `veterinary` (`vet_id`);

--
-- Constraints for table `vaccine_record`
--
ALTER TABLE `vaccine_record`
  ADD CONSTRAINT `vaccine_record_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pet` (`pet_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

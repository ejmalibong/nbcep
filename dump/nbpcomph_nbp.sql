-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2025 at 11:54 AM
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
-- Database: `nbpcomph_nbp`
--

-- --------------------------------------------------------

--
-- Table structure for table `agency`
--

CREATE TABLE `agency` (
  `agencyid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `agencycode` varchar(10) DEFAULT NULL,
  `agencyname` varchar(50) DEFAULT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bed`
--

CREATE TABLE `bed` (
  `bedid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `bedno` varchar(10) DEFAULT NULL,
  `roomid` int(11) NOT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isvacant` bit(1) NOT NULL,
  `isactive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clinic`
--

CREATE TABLE `clinic` (
  `employeeid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `employeecode` varchar(8) DEFAULT NULL,
  `employeename` varchar(50) DEFAULT NULL,
  `departmentname` varchar(20) DEFAULT NULL,
  `teamname` varchar(20) DEFAULT NULL,
  `positionname` varchar(20) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` bit(1) NOT NULL,
  `isadmin` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dailytimerecord`
--

CREATE TABLE `dailytimerecord` (
  `recordid` int(11) NOT NULL,
  `employeeid` int(11) DEFAULT NULL,
  `employeecode` varchar(50) DEFAULT NULL,
  `employeename` varchar(255) DEFAULT NULL,
  `departmentname` varchar(50) DEFAULT NULL,
  `teamname` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `attendancetype` varchar(30) DEFAULT NULL,
  `dailyschedule` varchar(100) DEFAULT NULL,
  `daytype` varchar(30) DEFAULT NULL,
  `timein` time DEFAULT NULL,
  `timeout` time DEFAULT NULL,
  `regularhours` decimal(4,2) DEFAULT NULL,
  `tardy` decimal(4,2) DEFAULT NULL,
  `undertime` decimal(4,2) DEFAULT NULL,
  `nd` decimal(4,2) DEFAULT NULL,
  `ot` decimal(4,2) DEFAULT NULL,
  `ndot` decimal(4,2) DEFAULT NULL,
  `leavetype` varchar(30) DEFAULT NULL,
  `leaveqty` decimal(4,2) DEFAULT NULL,
  `leavestatus` varchar(30) DEFAULT NULL,
  `leavehours` decimal(4,2) DEFAULT NULL,
  `ob` decimal(4,2) DEFAULT NULL,
  `remarks` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `departmentid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `departmentcode` varchar(50) DEFAULT NULL,
  `departmentname` varchar(50) DEFAULT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employeeid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `employeecode` varchar(50) DEFAULT NULL,
  `employeename` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `birthdate` datetime DEFAULT NULL,
  `nbcemailaddress` varchar(50) DEFAULT NULL,
  `emailaddress` varchar(100) DEFAULT NULL,
  `contactnumber` text DEFAULT NULL,
  `addressregistered` varchar(200) DEFAULT NULL,
  `addresslocal` varchar(200) DEFAULT NULL,
  `genderid` int(11) DEFAULT NULL,
  `maritalstatusid` int(11) DEFAULT NULL,
  `employmenttypeid` int(11) DEFAULT NULL,
  `datehired` datetime DEFAULT NULL,
  `dateseparated` datetime DEFAULT NULL,
  `dateregular` datetime DEFAULT NULL,
  `departmentid` int(11) DEFAULT NULL,
  `teamid` int(11) DEFAULT NULL,
  `positionid` int(11) DEFAULT NULL,
  `bloodtype` varchar(10) DEFAULT NULL,
  `emergencycontactname` text DEFAULT NULL,
  `emergencycontactnumber` text DEFAULT NULL,
  `emergencycontactaddress` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isapprover` bit(1) NOT NULL,
  `ishrrecords` bit(1) NOT NULL,
  `isemployee` bit(1) NOT NULL,
  `isholiday` bit(1) NOT NULL,
  `isallowedit` bit(1) NOT NULL,
  `isallowdelete` bit(1) NOT NULL,
  `isadmin` bit(1) NOT NULL,
  `isactive` bit(1) NOT NULL,
  `passwordhash` varchar(250) DEFAULT NULL,
  `isdefaultpassword` bit(1) DEFAULT b'1',
  `passwordresetcode` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employeeagency`
--

CREATE TABLE `employeeagency` (
  `employeecode` varchar(50) DEFAULT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `employeename` varchar(50) DEFAULT NULL,
  `agencyid` int(11) NOT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employeeape`
--

CREATE TABLE `employeeape` (
  `recordid` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  `employeeid` int(11) NOT NULL,
  `yearid` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `modifiedby` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employeeapeattachment`
--

CREATE TABLE `employeeapeattachment` (
  `attachmentid` int(11) NOT NULL,
  `recordid` int(11) NOT NULL,
  `filename` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employeemedicalattachment`
--

CREATE TABLE `employeemedicalattachment` (
  `attachmentid` int(11) NOT NULL,
  `recordid` int(11) NOT NULL,
  `filename` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employeemedicalrecord`
--

CREATE TABLE `employeemedicalrecord` (
  `recordid` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  `employeeid` int(11) NOT NULL,
  `employeecode` varchar(50) DEFAULT NULL,
  `lmp` date DEFAULT NULL,
  `temperature` varchar(10) DEFAULT NULL,
  `bloodpressure` varchar(10) DEFAULT NULL,
  `oxygenlevel` varchar(10) DEFAULT NULL,
  `pulserate` varchar(10) DEFAULT NULL,
  `respiratoryrate` varchar(10) DEFAULT NULL,
  `chiefcomplaint` text NOT NULL,
  `diagnosis` text NOT NULL,
  `plano` text DEFAULT NULL,
  `hpi` text DEFAULT NULL,
  `isfittowork` bit(1) NOT NULL,
  `issenthome` bit(1) NOT NULL,
  `isteleconsult` bit(1) NOT NULL,
  `isrest` bit(1) NOT NULL,
  `datetimestarted` datetime DEFAULT NULL,
  `datetimeended` datetime DEFAULT NULL,
  `totalresttime` int(11) NOT NULL,
  `roomid` int(11) DEFAULT NULL,
  `bedid` int(11) DEFAULT NULL,
  `isagency` bit(1) NOT NULL,
  `agencyid` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `modifiedby` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employmenttype`
--

CREATE TABLE `employmenttype` (
  `employmenttypeid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `employmenttypecode` varchar(30) DEFAULT NULL,
  `employmenttypename` varchar(30) DEFAULT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gender`
--

CREATE TABLE `gender` (
  `genderid` int(11) NOT NULL,
  `gendercode` varchar(20) DEFAULT NULL,
  `gendername` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hiddenrecord`
--

CREATE TABLE `hiddenrecord` (
  `employeeid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holiday`
--

CREATE TABLE `holiday` (
  `holidayid` int(11) NOT NULL,
  `holidaydate` date NOT NULL,
  `holidayname` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leavefiling`
--

CREATE TABLE `leavefiling` (
  `leavefileid` int(11) NOT NULL,
  `datecreated` datetime NOT NULL,
  `screenid` int(11) DEFAULT NULL,
  `employeeid` int(11) NOT NULL,
  `routingstatusid` int(11) NOT NULL,
  `departmentid` int(11) NOT NULL,
  `teamid` int(11) DEFAULT NULL,
  `startdate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `quantity` float NOT NULL,
  `reason` varchar(500) DEFAULT NULL,
  `leavecredits` float NOT NULL,
  `leavebalance` float NOT NULL,
  `clinicisapproved` bit(1) NOT NULL,
  `clinicid` int(11) DEFAULT NULL,
  `clinicapprovaldate` datetime DEFAULT NULL,
  `clinicremarks` varchar(500) DEFAULT NULL,
  `superiorisapproved1` bit(1) NOT NULL,
  `superiorid1` int(11) DEFAULT NULL,
  `superiorapprovaldate1` datetime DEFAULT NULL,
  `superiorremarks1` varchar(500) DEFAULT NULL,
  `superiorisapproved2` bit(1) NOT NULL,
  `superiorid2` int(11) DEFAULT NULL,
  `superiorapprovaldate2` datetime DEFAULT NULL,
  `superiorremarks2` varchar(500) DEFAULT NULL,
  `managerisapproved` bit(1) NOT NULL,
  `managerid` int(11) DEFAULT NULL,
  `managerapprovaldate` datetime DEFAULT NULL,
  `managerremarks` varchar(500) DEFAULT NULL,
  `islatefiling` bit(1) NOT NULL,
  `leavetypeid` int(11) NOT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isencoded` bit(1) NOT NULL,
  `isdone` bit(1) NOT NULL,
  `superiorisda1` bit(1) NOT NULL,
  `superiorisda2` bit(1) NOT NULL,
  `managerisda` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leavetype`
--

CREATE TABLE `leavetype` (
  `leavetypeid` int(11) NOT NULL,
  `leavetypecode` varchar(6) DEFAULT NULL,
  `leavetypename` varchar(30) DEFAULT NULL,
  `isclinic` bit(1) NOT NULL,
  `isactive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maritalstatus`
--

CREATE TABLE `maritalstatus` (
  `maritalstatusid` int(11) NOT NULL,
  `maritalstatuscode` varchar(20) DEFAULT NULL,
  `maritalstatusname` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `medicineid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `medicinename` varchar(50) DEFAULT NULL,
  `categoryid` int(11) NOT NULL,
  `unitid` int(11) NOT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicinecategory`
--

CREATE TABLE `medicinecategory` (
  `categoryid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `categorycode` varchar(10) DEFAULT NULL,
  `categoryname` varchar(30) DEFAULT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicineexpiration`
--

CREATE TABLE `medicineexpiration` (
  `expirationid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `medicineid` int(11) NOT NULL,
  `expirationdate` datetime NOT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicinestock`
--

CREATE TABLE `medicinestock` (
  `stockid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `medicineid` int(11) NOT NULL,
  `isagency` bit(1) NOT NULL,
  `agencyid` int(11) DEFAULT NULL,
  `maxstock` int(11) NOT NULL,
  `minstock` int(11) NOT NULL,
  `remarks` varchar(150) DEFAULT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` bit(1) NOT NULL,
  `orderingpoint` int(11) NOT NULL,
  `unitprice` decimal(6,2) DEFAULT NULL,
  `actualstock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicinetempavance`
--

CREATE TABLE `medicinetempavance` (
  `categoryname` varchar(50) DEFAULT NULL,
  `categoryid` int(11) DEFAULT NULL,
  `medicinename` varchar(100) DEFAULT NULL,
  `medicineid` int(11) DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `unitid` int(11) DEFAULT NULL,
  `expiration1` datetime DEFAULT NULL,
  `expiration2` datetime DEFAULT NULL,
  `expiration3` datetime DEFAULT NULL,
  `expiration4` datetime DEFAULT NULL,
  `expiration5` datetime DEFAULT NULL,
  `expiration6` datetime DEFAULT NULL,
  `expiration7` datetime DEFAULT NULL,
  `expiration8` datetime DEFAULT NULL,
  `beginningbalance` int(11) DEFAULT NULL,
  `stockout` int(11) DEFAULT NULL,
  `endingbalance` int(11) DEFAULT NULL,
  `minstock` int(11) DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  `isagency` bit(1) DEFAULT NULL,
  `agencyid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicinetempfirst`
--

CREATE TABLE `medicinetempfirst` (
  `categoryname` varchar(50) DEFAULT NULL,
  `categoryid` int(11) DEFAULT NULL,
  `medicinename` varchar(100) DEFAULT NULL,
  `medicineid` int(11) DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `unitid` int(11) DEFAULT NULL,
  `expiration1` datetime DEFAULT NULL,
  `expiration2` datetime DEFAULT NULL,
  `expiration3` datetime DEFAULT NULL,
  `expiration4` datetime DEFAULT NULL,
  `expiration5` datetime DEFAULT NULL,
  `expiration6` datetime DEFAULT NULL,
  `expiration7` datetime DEFAULT NULL,
  `expiration8` datetime DEFAULT NULL,
  `beginningbalance` int(11) DEFAULT NULL,
  `stockout` int(11) DEFAULT NULL,
  `endingbalance` int(11) DEFAULT NULL,
  `minstock` int(11) DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  `isagency` bit(1) DEFAULT NULL,
  `agencyid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicinetempnatcorp`
--

CREATE TABLE `medicinetempnatcorp` (
  `categoryname` varchar(50) DEFAULT NULL,
  `categoryid` int(11) DEFAULT NULL,
  `medicinename` varchar(100) DEFAULT NULL,
  `medicineid` int(11) DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `unitid` int(11) DEFAULT NULL,
  `expiration1` datetime DEFAULT NULL,
  `expiration2` datetime DEFAULT NULL,
  `expiration3` datetime DEFAULT NULL,
  `expiration4` datetime DEFAULT NULL,
  `expiration5` datetime DEFAULT NULL,
  `expiration6` datetime DEFAULT NULL,
  `expiration7` datetime DEFAULT NULL,
  `expiration8` datetime DEFAULT NULL,
  `beginningbalance` int(11) DEFAULT NULL,
  `stockout` int(11) DEFAULT NULL,
  `endingbalance` int(11) DEFAULT NULL,
  `minstock` int(11) DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  `isagency` bit(1) DEFAULT NULL,
  `agencyid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicinetempnbc`
--

CREATE TABLE `medicinetempnbc` (
  `categoryname` varchar(50) DEFAULT NULL,
  `categoryid` int(11) DEFAULT NULL,
  `medicinename` varchar(100) DEFAULT NULL,
  `medicineid` int(11) DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `unitid` int(11) DEFAULT NULL,
  `expiration1` datetime DEFAULT NULL,
  `expiration2` datetime DEFAULT NULL,
  `expiration3` datetime DEFAULT NULL,
  `expiration4` datetime DEFAULT NULL,
  `expiration5` datetime DEFAULT NULL,
  `expiration6` datetime DEFAULT NULL,
  `expiration7` datetime DEFAULT NULL,
  `expiration8` datetime DEFAULT NULL,
  `beginningbalance` int(11) DEFAULT NULL,
  `stockout` int(11) DEFAULT NULL,
  `endingbalance` int(11) DEFAULT NULL,
  `minstock` int(11) DEFAULT NULL,
  `orderingpoint` int(11) DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  `isagency` bit(1) DEFAULT NULL,
  `agencyid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicinetemppkimt`
--

CREATE TABLE `medicinetemppkimt` (
  `categoryname` varchar(50) DEFAULT NULL,
  `categoryid` int(11) DEFAULT NULL,
  `medicinename` varchar(100) DEFAULT NULL,
  `medicineid` int(11) DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `unitid` int(11) DEFAULT NULL,
  `expiration1` datetime DEFAULT NULL,
  `expiration2` datetime DEFAULT NULL,
  `expiration3` datetime DEFAULT NULL,
  `expiration4` datetime DEFAULT NULL,
  `expiration5` datetime DEFAULT NULL,
  `expiration6` datetime DEFAULT NULL,
  `expiration7` datetime DEFAULT NULL,
  `expiration8` datetime DEFAULT NULL,
  `beginningbalance` int(11) DEFAULT NULL,
  `stockout` int(11) DEFAULT NULL,
  `endingbalance` int(11) DEFAULT NULL,
  `minstock` int(11) DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  `isagency` bit(1) DEFAULT NULL,
  `agencyid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicinetempworkion`
--

CREATE TABLE `medicinetempworkion` (
  `categoryname` varchar(50) DEFAULT NULL,
  `categoryid` int(11) DEFAULT NULL,
  `medicinename` varchar(100) DEFAULT NULL,
  `medicineid` int(11) DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  `unitid` int(11) DEFAULT NULL,
  `expiration1` datetime DEFAULT NULL,
  `expiration2` datetime DEFAULT NULL,
  `expiration3` datetime DEFAULT NULL,
  `expiration4` datetime DEFAULT NULL,
  `expiration5` datetime DEFAULT NULL,
  `expiration6` datetime DEFAULT NULL,
  `expiration7` datetime DEFAULT NULL,
  `expiration8` datetime DEFAULT NULL,
  `beginningbalance` int(11) DEFAULT NULL,
  `stockout` int(11) DEFAULT NULL,
  `endingbalance` int(11) DEFAULT NULL,
  `minstock` int(11) DEFAULT NULL,
  `remarks` varchar(200) DEFAULT NULL,
  `isagency` bit(1) DEFAULT NULL,
  `agencyid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicinetrxdetail`
--

CREATE TABLE `medicinetrxdetail` (
  `trxdetailid` int(11) NOT NULL,
  `trxid` int(11) NOT NULL,
  `medicineid` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `stockid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicinetrxheader`
--

CREATE TABLE `medicinetrxheader` (
  `trxid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `recordid` int(11) DEFAULT NULL,
  `trxtypeid` int(11) NOT NULL,
  `employeeid` int(11) DEFAULT NULL,
  `employeecode` varchar(50) DEFAULT NULL,
  `isagency` bit(1) NOT NULL,
  `agencyid` int(11) DEFAULT NULL,
  `remarks` varchar(150) DEFAULT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicinetrxtype`
--

CREATE TABLE `medicinetrxtype` (
  `trxtypeid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `trxtypecode` varchar(10) DEFAULT NULL,
  `trxtypename` varchar(10) DEFAULT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicineunit`
--

CREATE TABLE `medicineunit` (
  `unitid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `unitcode` varchar(10) DEFAULT NULL,
  `unitname` varchar(30) DEFAULT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nurse`
--

CREATE TABLE `nurse` (
  `employeeid` int(11) NOT NULL,
  `isactive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `positionid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `positioncode` varchar(50) DEFAULT NULL,
  `positionname` varchar(50) DEFAULT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recipient`
--

CREATE TABLE `recipient` (
  `recipientid` int(11) NOT NULL,
  `departmentid` int(11) NOT NULL,
  `teamid` int(11) NOT NULL,
  `positionid` int(11) NOT NULL,
  `superiorid1` int(11) DEFAULT NULL,
  `superiorid2` int(11) DEFAULT NULL,
  `managerid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restalarm`
--

CREATE TABLE `restalarm` (
  `restalarmid` int(11) NOT NULL,
  `recordid` int(11) NOT NULL,
  `roomid` int(11) NOT NULL,
  `bedid` int(11) NOT NULL,
  `alarmtime` datetime NOT NULL,
  `isactive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `roomid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `roomname` varchar(20) DEFAULT NULL,
  `capacity` int(11) NOT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isfull` bit(1) NOT NULL,
  `isactive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `routingstatus`
--

CREATE TABLE `routingstatus` (
  `routingstatusid` int(11) NOT NULL,
  `routingstatusname` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `screening`
--

CREATE TABLE `screening` (
  `screenid` int(11) NOT NULL,
  `screendate` datetime NOT NULL,
  `screenby` int(11) NOT NULL,
  `employeeid` int(11) DEFAULT NULL,
  `employeecode` varchar(8) DEFAULT NULL,
  `employeename` varchar(50) DEFAULT NULL,
  `absentfrom` date NOT NULL,
  `absentto` date NOT NULL,
  `quantity` float NOT NULL,
  `leavetypeid` int(11) NOT NULL,
  `reason` varchar(500) DEFAULT NULL,
  `diagnosis` varchar(500) DEFAULT NULL,
  `isfittowork` bit(1) NOT NULL,
  `isused` bit(1) NOT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `medcertdate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `settingid` int(11) NOT NULL,
  `senderemail` varchar(50) DEFAULT NULL,
  `senderemailpassword` varchar(50) DEFAULT NULL,
  `devemail` varchar(50) DEFAULT NULL,
  `devemailpassword` varchar(50) DEFAULT NULL,
  `hremail` varchar(50) DEFAULT NULL,
  `hremailpassword` varchar(50) DEFAULT NULL,
  `customemail` varchar(50) DEFAULT NULL,
  `customemailpassword` varchar(50) DEFAULT NULL,
  `defaultdepartmentname` varchar(50) DEFAULT NULL,
  `defaultteamname` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `teamid` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `createddate` datetime NOT NULL,
  `teamcode` varchar(50) DEFAULT NULL,
  `teamname` varchar(50) DEFAULT NULL,
  `modifiedby` int(11) DEFAULT NULL,
  `modifieddate` datetime DEFAULT NULL,
  `isactive` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dailytimerecord`
--
ALTER TABLE `dailytimerecord`
  ADD PRIMARY KEY (`recordid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dailytimerecord`
--
ALTER TABLE `dailytimerecord`
  MODIFY `recordid` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

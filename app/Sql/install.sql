-- phpMyAdmin SQL Dump
-- version 4.0.10.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 22, 2014 at 01:26 AM
-- Server version: 5.1.73-log
-- PHP Version: 5.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `www_opensmis_demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `acos`
--

CREATE TABLE IF NOT EXISTS `acos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT '',
  `foreign_key` int(10) unsigned DEFAULT NULL,
  `alias` varchar(255) DEFAULT '',
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `aros`
--

CREATE TABLE IF NOT EXISTS `aros` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) DEFAULT '',
  `foreign_key` int(10) unsigned DEFAULT NULL,
  `alias` varchar(255) DEFAULT '',
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `aros_acos`
--

CREATE TABLE IF NOT EXISTS `aros_acos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) unsigned NOT NULL,
  `aco_id` int(10) unsigned NOT NULL,
  `_create` char(2) NOT NULL DEFAULT '0',
  `_read` char(2) NOT NULL DEFAULT '0',
  `_update` char(2) NOT NULL DEFAULT '0',
  `_delete` char(2) NOT NULL DEFAULT '0',
  `_execute` char(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `assessment_items`
--

CREATE TABLE IF NOT EXISTS `assessment_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `min` int(5) NOT NULL DEFAULT '50',
  `max` int(5) NOT NULL DEFAULT '100',
  `weighting` float(5,2) NOT NULL DEFAULT '0.00',
  `visible` int(1) NOT NULL,
  `assessment_item_type_id` int(11) NOT NULL,
  `education_grade_subject_id` int(5) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `assessment_item_type_id` (`assessment_item_type_id`),
  KEY `education_grade_subject_id` (`education_grade_subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `assessment_item_results`
--

CREATE TABLE IF NOT EXISTS `assessment_item_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marks` varchar(50) DEFAULT NULL,
  `assessment_item_id` int(11) NOT NULL,
  `assessment_result_id` int(11) DEFAULT NULL,
  `assessment_result_type_id` int(3) NOT NULL DEFAULT '0',
  `student_id` int(11) NOT NULL,
  `school_year_id` int(5) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `assessment_item_id` (`assessment_item_id`),
  KEY `assessment_result_id` (`assessment_result_id`),
  KEY `assessment_result_type_id` (`assessment_result_type_id`),
  KEY `student_id` (`student_id`),
  KEY `school_year_id` (`school_year_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `assessment_item_types`
--

CREATE TABLE IF NOT EXISTS `assessment_item_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `school_year_id` int(3) DEFAULT NULL,
  `education_grade_id` int(5) NOT NULL,
  `class_id` int(5) NOT NULL DEFAULT '0',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `education_grade_id` (`education_grade_id`),
  KEY `school_year_id` (`school_year_id`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `assessment_results`
--

CREATE TABLE IF NOT EXISTS `assessment_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marks` varchar(50) NOT NULL,
  `assessment_result_type_id` int(3) NOT NULL,
  `student_id` int(11) NOT NULL,
  `school_year_id` int(5) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `assessment_result_type_id` (`assessment_result_type_id`),
  KEY `student_id` (`student_id`),
  KEY `school_year_id` (`school_year_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `assessment_result_types`
--

CREATE TABLE IF NOT EXISTS `assessment_result_types` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `min` int(5) NOT NULL DEFAULT '50',
  `max` int(5) NOT NULL DEFAULT '100',
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `behaviour_categories`
--

CREATE TABLE IF NOT EXISTS `behaviour_categories` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `seats_total` int(5) NOT NULL DEFAULT '0',
  `school_year_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `school_year_id` (`school_year_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `class_attachments`
--

CREATE TABLE IF NOT EXISTS `class_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `description` varchar(250) NOT NULL,
  `file_name` varchar(250) NOT NULL,
  `file_content` longblob NOT NULL,
  `class_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `class_events`
--

CREATE TABLE IF NOT EXISTS `class_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `class_grades`
--

CREATE TABLE IF NOT EXISTS `class_grades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `education_grade_id` int(5) NOT NULL,
  `visible` int(1) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  KEY `education_grade_id` (`education_grade_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `class_lessons`
--

CREATE TABLE IF NOT EXISTS `class_lessons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `class_id` int(11) NOT NULL,
  `room_id` int(5) NOT NULL,
  `education_grade_subject_id` int(5) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `timetable_entry_id` int(11) NOT NULL,
  `lesson_status_id` int(3) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  KEY `room_id` (`room_id`),
  KEY `education_grade_subject_id` (`education_grade_subject_id`),
  KEY `staff_id` (`staff_id`),
  KEY `timetable_entry_id` (`timetable_entry_id`),
  KEY `lesson_status_id` (`lesson_status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `class_students`
--

CREATE TABLE IF NOT EXISTS `class_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `education_grade_id` int(5) NOT NULL,
  `student_id` int(11) NOT NULL,
  `student_category_id` int(3) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  KEY `education_grade_id` (`education_grade_id`),
  KEY `student_id` (`student_id`),
  KEY `student_category_id` (`student_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `class_subjects`
--

CREATE TABLE IF NOT EXISTS `class_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `education_grade_subject_id` int(5) NOT NULL,
  `visible` int(1) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  KEY `education_grade_subject_id` (`education_grade_subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `class_teachers`
--

CREATE TABLE IF NOT EXISTS `class_teachers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `config_items`
--

CREATE TABLE IF NOT EXISTS `config_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `value_type` varchar(20) DEFAULT NULL,
  `label` varchar(100) NOT NULL,
  `description` text,
  `value` varchar(500) NOT NULL,
  `default_value` varchar(500) DEFAULT NULL,
  `editable` int(1) NOT NULL DEFAULT '0',
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `config_item_options`
--

CREATE TABLE IF NOT EXISTS `config_item_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  `order` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '1',
  `config_item_id` int(5) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `config_item_id` (`config_item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `contact_no` varchar(50) NOT NULL,
  `main` int(1) NOT NULL DEFAULT '0',
  `contact_type_id` int(3) NOT NULL,
  `security_user_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_type_id` (`contact_type_id`),
  KEY `security_user_id` (`security_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contact_types`
--

CREATE TABLE IF NOT EXISTS `contact_types` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `international_code` varchar(10) DEFAULT NULL,
  `national_code` varchar(10) DEFAULT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `editable` int(1) NOT NULL DEFAULT '1',
  `default` int(1) NOT NULL DEFAULT '0',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `education_fees`
--

CREATE TABLE IF NOT EXISTS `education_fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(150) NOT NULL,
  `amount` float(11,2) NOT NULL,
  `fee_type_id` int(11) NOT NULL,
  `education_grade_id` int(11) NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `source` int(1) DEFAULT '0' COMMENT '0-dataentry,1-external,2-estimate',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fee_type_id` (`fee_type_id`),
  KEY `education_grade_id` (`education_grade_id`),
  KEY `school_year_id` (`school_year_id`),
  KEY `source` (`source`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `education_grades`
--

CREATE TABLE IF NOT EXISTS `education_grades` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `education_programme_id` int(5) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `education_programme_id` (`education_programme_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `education_grades_subjects`
--

CREATE TABLE IF NOT EXISTS `education_grades_subjects` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `education_grade_id` int(5) NOT NULL,
  `education_subject_id` int(5) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `education_grade_id` (`education_grade_id`),
  KEY `education_subject_id` (`education_subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `education_programmes`
--

CREATE TABLE IF NOT EXISTS `education_programmes` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `duration` int(3) DEFAULT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `education_subjects`
--

CREATE TABLE IF NOT EXISTS `education_subjects` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE IF NOT EXISTS `emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(80) NOT NULL,
  `main` int(1) NOT NULL DEFAULT '0',
  `contact_type_id` int(3) NOT NULL,
  `security_user_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contact_type_id` (`contact_type_id`),
  KEY `security_user_id` (`security_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `type` int(1) NOT NULL COMMENT '0 -> School, 1 -> Class',
  `start_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_date` date NOT NULL,
  `end_time` time NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fee_types`
--

CREATE TABLE IF NOT EXISTS `fee_types` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `field_options`
--

CREATE TABLE IF NOT EXISTS `field_options` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `parent` varchar(50) DEFAULT NULL,
  `params` text,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `field_option_values`
--

CREATE TABLE IF NOT EXISTS `field_option_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `international_code` varchar(10) DEFAULT NULL,
  `national_code` varchar(10) DEFAULT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `editable` int(1) NOT NULL DEFAULT '1',
  `default` int(1) NOT NULL DEFAULT '0',
  `field_option_id` int(5) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `field_option_id` (`field_option_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `guardian_custom_fields`
--

CREATE TABLE IF NOT EXISTS `guardian_custom_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `order` int(3) NOT NULL,
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '1 -> Text, 2 -> Text Area, 3 -> Number, 4 -> Dropdown',
  `is_mandatory` tinyint(4) NOT NULL,
  `is_unique` tinyint(4) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `guardian_custom_field_options`
--

CREATE TABLE IF NOT EXISTS `guardian_custom_field_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(250) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `guardian_custom_field_id` int(5) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `guardian_custom_field_id` (`guardian_custom_field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `guardian_custom_values`
--

CREATE TABLE IF NOT EXISTS `guardian_custom_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text_value` varchar(250) DEFAULT NULL,
  `int_value` int(5) DEFAULT NULL,
  `textarea_value` text,
  `guardian_custom_field_id` int(11) NOT NULL,
  `guardian_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `value` (`guardian_custom_field_id`,`guardian_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `guardian_identities`
--

CREATE TABLE IF NOT EXISTS `guardian_identities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identity_type_id` int(11) NOT NULL,
  `number` varchar(50) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `country_id` int(11) NOT NULL,
  `comments` text,
  `guardian_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `guardian_id` (`guardian_id`),
  KEY `identity_type_id` (`identity_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `identity_types`
--

CREATE TABLE IF NOT EXISTS `identity_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `international_code` varchar(10) DEFAULT NULL,
  `national_code` varchar(10) DEFAULT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `institution_sites`
--

CREATE TABLE IF NOT EXISTS `institution_sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `code` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country_id` int(11) NOT NULL,
  `photo_name` varchar(200) DEFAULT NULL,
  `photo_content` longblob,
  `contact_person` varchar(100) DEFAULT NULL,
  `telephone` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `date_opened` date NOT NULL,
  `date_closed` date DEFAULT NULL,
  `longitude` varchar(15) DEFAULT NULL,
  `latitude` varchar(15) DEFAULT NULL,
  `areaid` varchar(3) DEFAULT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_statuses`
--

CREATE TABLE IF NOT EXISTS `lesson_statuses` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `relationship_categories`
--

CREATE TABLE IF NOT EXISTS `relationship_categories` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `school_years`
--

CREATE TABLE IF NOT EXISTS `school_years` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `start_year` int(4) NOT NULL,
  `end_date` date DEFAULT NULL,
  `end_year` int(4) DEFAULT NULL,
  `school_days` int(5) NOT NULL DEFAULT '0',
  `order` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `security_users`
--

CREATE TABLE IF NOT EXISTS `security_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openemisid` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `identification_no` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `country_id` int(11) NOT NULL,
  `gender` char(1) NOT NULL,
  `address` text,
  `postal_code` varchar(20) DEFAULT NULL,
  `photo_name` varchar(200) DEFAULT NULL,
  `photo_content` longblob,
  `super_admin` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0 -> Inactive, 1 -> Active',
  `last_login` datetime DEFAULT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `security_user_types`
--

CREATE TABLE IF NOT EXISTS `security_user_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `security_user_id` int(11) NOT NULL,
  `type` int(2) NOT NULL COMMENT '(1->Admin), (2->Staff), (3->Student), (4->Guardian)',
  PRIMARY KEY (`id`),
  KEY `security_user_id` (`security_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` date NOT NULL,
  `start_year` int(4) NOT NULL,
  `type` int(1) NOT NULL COMMENT '0 -> Non-teaching, 1 -> Teaching',
  `staff_status_id` int(3) NOT NULL,
  `staff_category_id` int(3) NOT NULL,
  `security_user_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_status_id` (`staff_status_id`),
  KEY `staff_category_id` (`staff_category_id`),
  KEY `security_user_id` (`security_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_attachments`
--

CREATE TABLE IF NOT EXISTS `staff_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `description` varchar(250) NOT NULL,
  `file_name` varchar(250) NOT NULL,
  `file_content` longblob NOT NULL,
  `staff_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_attendance_days`
--

CREATE TABLE IF NOT EXISTS `staff_attendance_days` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `remarks` varchar(255) DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `staff_attendance_type_id` int(3) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_attendance_type_id` (`staff_attendance_type_id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_attendance_types`
--

CREATE TABLE IF NOT EXISTS `staff_attendance_types` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `short_form` varchar(30) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_behaviours`
--

CREATE TABLE IF NOT EXISTS `staff_behaviours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `action` text NOT NULL,
  `date_of_behaviour` date NOT NULL,
  `time_of_behaviour` time DEFAULT NULL,
  `staff_id` int(11) NOT NULL,
  `behaviour_category_id` int(3) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`),
  KEY `behaviour_category_id` (`behaviour_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_categories`
--

CREATE TABLE IF NOT EXISTS `staff_categories` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_custom_fields`
--

CREATE TABLE IF NOT EXISTS `staff_custom_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `order` int(3) NOT NULL,
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '1 -> Text, 2 -> Text Area, 3 -> Number, 4 -> Dropdown',
  `is_mandatory` tinyint(4) NOT NULL,
  `is_unique` tinyint(4) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_custom_field_options`
--

CREATE TABLE IF NOT EXISTS `staff_custom_field_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(250) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `staff_custom_field_id` int(5) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_custom_field_id` (`staff_custom_field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_custom_values`
--

CREATE TABLE IF NOT EXISTS `staff_custom_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text_value` varchar(250) DEFAULT NULL,
  `int_value` int(5) DEFAULT NULL,
  `textarea_value` text,
  `staff_custom_field_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `value` (`staff_custom_field_id`,`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_employments`
--

CREATE TABLE IF NOT EXISTS `staff_employments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employment_date` date NOT NULL,
  `comment` text,
  `staff_id` int(11) NOT NULL,
  `staff_employment_type_id` int(3) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`),
  KEY `staff_employment_type_id` (`staff_employment_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_employment_types`
--

CREATE TABLE IF NOT EXISTS `staff_employment_types` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `international_code` varchar(10) DEFAULT NULL,
  `national_code` varchar(10) DEFAULT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_identities`
--

CREATE TABLE IF NOT EXISTS `staff_identities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identity_type_id` int(11) NOT NULL,
  `number` varchar(50) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `country_id` int(11) NOT NULL,
  `comments` text,
  `staff_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`),
  KEY `identity_type_id` (`identity_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `staff_statuses`
--

CREATE TABLE IF NOT EXISTS `staff_statuses` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` date NOT NULL,
  `start_year` int(4) NOT NULL,
  `student_status_id` int(3) NOT NULL,
  `security_user_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_status_id` (`student_status_id`),
  KEY `security_user_id` (`security_user_id`),
  KEY `start_year` (`start_year`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_attachments`
--

CREATE TABLE IF NOT EXISTS `student_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `description` varchar(250) NOT NULL,
  `file_name` varchar(250) NOT NULL,
  `file_content` longblob NOT NULL,
  `student_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance_days`
--

CREATE TABLE IF NOT EXISTS `student_attendance_days` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `remarks` varchar(255) DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `session` int(2) NOT NULL DEFAULT '1',
  `student_attendance_type_id` int(3) NOT NULL,
  `student_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_attendance_type_id` (`student_attendance_type_id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance_lessons`
--

CREATE TABLE IF NOT EXISTS `student_attendance_lessons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `remarks` varchar(255) DEFAULT NULL,
  `student_attendance_type_id` int(3) NOT NULL,
  `class_lesson_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_attendance_type_id` (`student_attendance_type_id`),
  KEY `class_lesson_id` (`class_lesson_id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance_types`
--

CREATE TABLE IF NOT EXISTS `student_attendance_types` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `short_form` varchar(30) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_behaviours`
--

CREATE TABLE IF NOT EXISTS `student_behaviours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `action` text NOT NULL,
  `date_of_behaviour` date NOT NULL,
  `time_of_behaviour` time DEFAULT NULL,
  `student_id` int(11) NOT NULL,
  `behaviour_category_id` int(3) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `behaviour_category_id` (`behaviour_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_categories`
--

CREATE TABLE IF NOT EXISTS `student_categories` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_custom_fields`
--

CREATE TABLE IF NOT EXISTS `student_custom_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `order` int(3) NOT NULL,
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '1 -> Text, 2 -> Text Area, 3 -> Number, 4 -> Dropdown',
  `is_mandatory` tinyint(4) NOT NULL,
  `is_unique` tinyint(4) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_custom_field_options`
--

CREATE TABLE IF NOT EXISTS `student_custom_field_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(250) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `student_custom_field_id` int(5) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_custom_field_id` (`student_custom_field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_custom_values`
--

CREATE TABLE IF NOT EXISTS `student_custom_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text_value` varchar(250) DEFAULT NULL,
  `int_value` int(5) DEFAULT NULL,
  `textarea_value` text,
  `student_custom_field_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `value` (`student_custom_field_id`,`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_fees`
--

CREATE TABLE IF NOT EXISTS `student_fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` varchar(100) NOT NULL,
  `paid` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `school_year_id` int(11) NOT NULL,
  `education_grade_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `school_year_id` (`school_year_id`),
  KEY `education_grade_id` (`education_grade_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_guardians`
--

CREATE TABLE IF NOT EXISTS `student_guardians` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `security_user_id` int(11) NOT NULL COMMENT 'Linked to the guardian record in security_users table',
  `relationship_category_id` int(3) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `security_user_id` (`security_user_id`),
  KEY `relationship_category_id` (`relationship_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_identities`
--

CREATE TABLE IF NOT EXISTS `student_identities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identity_type_id` int(11) NOT NULL,
  `number` varchar(50) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `country_id` int(11) NOT NULL,
  `comments` text,
  `student_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `identity_type_id` (`identity_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_statuses`
--

CREATE TABLE IF NOT EXISTS `student_statuses` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `order` int(3) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `timetables`
--

CREATE TABLE IF NOT EXISTS `timetables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `class_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `timetable_entries`
--

CREATE TABLE IF NOT EXISTS `timetable_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `day_of_week` int(1) NOT NULL,
  `class_id` int(11) NOT NULL,
  `room_id` int(5) NOT NULL,
  `education_grade_subject_id` int(5) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `timetable_id` int(11) NOT NULL,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`),
  KEY `class_id` (`class_id`),
  KEY `education_grade_subject_id` (`education_grade_subject_id`),
  KEY `staff_id` (`staff_id`),
  KEY `timetable_id` (`timetable_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE IF NOT EXISTS `translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `eng` text NOT NULL,
  `ara` text,
  `chi` text,
  `spa` text,
  `fre` text,
  `rus` text,
  `modified_user_id` int(11) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


TRUNCATE `acos`;
INSERT INTO `acos` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES 
(1, NULL, NULL, NULL, 'All', 1, 124), 
(2, 1, NULL, NULL, 'Events', 2, 3), 
(3, 1, NULL, NULL, 'Students', 4, 31),  
(4, 1, NULL, NULL, 'Staff', 32, 53),  
(5, 1, NULL, NULL, 'Guardians', 54, 65),  
(6, 1, NULL, NULL, 'Admin', 66, 93),  
(7, 1, NULL, NULL, 'Classes', 94, 117), 
(8, 1, NULL, NULL, 'Administrator', 118, 121),  
(9, 1, NULL, NULL, 'Dashboard', 122, 123),  
(10, 3, NULL, NULL, 'StudentProfile', 5, 6),  
(11, 3, NULL, NULL, 'StudentContact', 7, 8),  
(12, 3, NULL, NULL, 'StudentGuardian', 9, 10),  
(13, 3, NULL, NULL, 'StudentBehaviour', 11, 12),  
(14, 3, NULL, NULL, 'StudentTimetable', 13, 14),  
(15, 3, NULL, NULL, 'StudentResult', 15, 16), 
(16, 3, NULL, NULL, 'StudentAttachment', 17, 18), 
(17, 3, NULL, NULL, 'StudentAttendanceDay', 19, 20),  
(18, 3, NULL, NULL, 'StudentAttendanceLesson', 21, 22), 
(19, 3, NULL, NULL, 'StudentIdentity', 23, 24), 
(20, 3, NULL, NULL, 'StudentReportCard', 25, 26), 
(21, 3, NULL, NULL, 'StudentFee', 27, 28),  
(22, 3, NULL, NULL, 'StudentPassword', 29, 30), 
(23, 4, NULL, NULL, 'StaffProfile', 33, 34),  
(24, 4, NULL, NULL, 'StaffContact', 35, 36),  
(25, 4, NULL, NULL, 'StaffAttendanceDay', 37, 38),  
(26, 4, NULL, NULL, 'StaffAttendanceLesson', 39, 40), 
(27, 4, NULL, NULL, 'StaffTimetable', 41, 42),  
(28, 4, NULL, NULL, 'StaffBehaviour', 43, 44),  
(29, 4, NULL, NULL, 'StaffEmployment', 45, 46), 
(30, 4, NULL, NULL, 'StaffAttachment', 47, 48), 
(31, 4, NULL, NULL, 'StaffIdentity', 49, 50), 
(32, 4, NULL, NULL, 'StaffPassword', 51, 52), 
(33, 5, NULL, NULL, 'GuardianProfile', 55, 56), 
(34, 5, NULL, NULL, 'GuardianContact', 57, 58), 
(35, 5, NULL, NULL, 'GuardianIdentity', 59, 60),  
(36, 5, NULL, NULL, 'GuardianStudent', 61, 62), 
(37, 5, NULL, NULL, 'GuardianPassword', 63, 64),  
(38, 6, NULL, NULL, 'AdminProfile', 67, 68),  
(39, 6, NULL, NULL, 'Education', 69, 70), 
(40, 6, NULL, NULL, 'Assessment', 71, 72),  
(41, 6, NULL, NULL, 'CustomField', 73, 74), 
(42, 6, NULL, NULL, 'FieldOptions', 75, 76),  
(43, 6, NULL, NULL, 'Translations', 77, 78),  
(44, 6, NULL, NULL, 'ConfigItem', 79, 80),  
(45, 6, NULL, NULL, 'EducationProgramme', 81, 82),  
(46, 6, NULL, NULL, 'EducationGrade', 83, 84),  
(47, 6, NULL, NULL, 'EducationGradesSubject', 85, 86),  
(48, 6, NULL, NULL, 'EducationSubject', 87, 88),  
(49, 6, NULL, NULL, 'Finance', 89, 90), 
(50, 6, NULL, NULL, 'EducationFee', 91, 92),  
(51, 7, NULL, NULL, 'ClassProfile', 95, 96),  
(52, 7, NULL, NULL, 'ClassStudent', 97, 98),  
(53, 7, NULL, NULL, 'ClassTeacher', 99, 100), 
(54, 7, NULL, NULL, 'ClassSubject', 101, 102),  
(55, 7, NULL, NULL, 'ClassAssignment', 103, 104), 
(56, 7, NULL, NULL, 'ClassResult', 105, 106), 
(57, 7, NULL, NULL, 'ClassLesson', 107, 108), 
(58, 7, NULL, NULL, 'ClassTimetable', 109, 110),  
(59, 7, NULL, NULL, 'ClassAttendanceDay', 111, 112),  
(60, 7, NULL, NULL, 'ClassAttendanceLesson', 113, 114), 
(61, 7, NULL, NULL, 'ClassAttachment', 115, 116), 
(62, 8, NULL, NULL, 'AdministratorPassword', 119, 120); 
  
--  
-- Dumping data for table `aros`  
--  

TRUNCATE `aros`;
INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES 
(1, NULL, '', NULL, 'Everyone', 1, 12), 
(2, 1, '', NULL, 'Admin', 2, 3),  
(3, 1, '', NULL, 'Staff', 4, 7),  
(4, 1, '', NULL, 'Student', 8, 9),  
(5, 1, '', NULL, 'Guardian', 10, 11), 
(6, 3, '', NULL, 'Teacher', 5, 6);  
  
--  
-- Dumping data for table `aros_acos` 
--  

TRUNCATE `aros_acos`;
INSERT INTO `aros_acos` (`id`, `aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`, `_execute`) VALUES 
(1, 2, 1, '1', '1', '1', '1', '1'), 
(2, 4, 9, '0', '1', '0', '0', ''),  
(3, 4, 2, '0', '1', '0', '0', ''),  
(4, 4, 3, '0', '1', '0', '0', ''),  
(5, 4, 13, '-1', '-1', '-1', '-1', '-1'), 
(6, 4, 16, '-1', '-1', '-1', '-1', '-1'), 
(7, 4, 22, '1', '1', '1', '1', '1'),  
(8, 3, 9, '0', '1', '0', '0', ''),  
(9, 3, 2, '0', '1', '0', '0', ''),  
(10, 3, 3, '0', '1', '0', '0', ''), 
(11, 3, 15, '1', '1', '1', '1', '1'), 
(12, 3, 17, '1', '1', '1', '1', '1'), 
(13, 3, 18, '1', '1', '1', '1', '1'), 
(14, 3, 13, '1', '1', '1', '1', '1'), 
(15, 3, 4, '0', '1', '0', '0', ''), 
(16, 3, 24, '1', '1', '1', '1', '1'), 
(17, 3, 28, '-1', '-1', '-1', '-1', '-1'),  
(18, 3, 22, '-1', '-1', '-1', '-1', '-1'),  
(19, 3, 37, '-1', '-1', '-1', '-1', '-1'),  
(20, 3, 32, '1', '1', '1', '1', '1'), 
(21, 3, 7, '0', '1', '0', '0', ''), 
(22, 3, 55, '1', '1', '1', '1', '1'), 
(23, 3, 56, '1', '1', '1', '1', '1'), 
(24, 3, 59, '1', '1', '1', '1', '1'), 
(25, 3, 60, '1', '1', '1', '1', '1'), 
(26, 3, 61, '1', '1', '1', '1', '1'), 
(27, 5, 9, '0', '1', '0', '0', ''), 
(28, 5, 2, '0', '1', '0', '0', ''), 
(29, 5, 5, '0', '1', '0', '0', ''), 
(30, 5, 3, '0', '1', '0', '0', ''), 
(31, 5, 37, '1', '1', '1', '1', '1'), 
(32, 5, 22, '-1', '-1', '-1', '-1', '-1');  
  

TRUNCATE `config_items`;
INSERT INTO `config_items` (`id`, `name`, `type`, `value_type`, `label`, `description`, `value`, `default_value`, `editable`, `order`, `visible`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'lesson_duration', 'Timetable', NULL, 'Lesson Duration', 'Lesson Duration', '30', '30', 1, 0, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(2, 'break_interval', 'Timetable', NULL, 'Break Interval', 'Break interval between the time', '0', '0', 1, 0, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(3, 'start_time_of_day', 'Timetable', 'time', 'Start Time of Day', 'Start Time of Day', '10:00', '09:00:00', 1, 0, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(4, 'end_time_of_day', 'Timetable', 'time', 'End Time of Day', 'End Time of Day', '14:00', '14:00:00', 1, 0, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(5, 'student_attendance_session', 'Attendance', 'dropdown', 'Student Attendance Per Day', 'Number of attendance taken in a day', '1', '1', 1, 0, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(6, 'attendance_view', 'Attendance', 'dropdown', 'Attendance View', 'Displaying either day or lesson attenance view', 'Day', 'Day', 1, 0, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(7, 'name_display_format', 'Display', 'dropdown', 'Name Display Format', 'Name display format', 'SecurityUser.last_name,SecurityUser.middle_name,SecurityUser.first_name', 'First Last', 1, 0, 1, 1, '2014-07-29 16:38:52', 0, '0000-00-00 00:00:00'),
(8, 'language', 'Language', 'dropdown', 'Language', 'Language', 'en', 'English', 1, 0, 1, 1, '2014-11-22 01:21:19', 0, '0000-00-00 00:00:00'),
(9, 'db_version', 'Version', NULL, 'Database Version', NULL, '1.3.1', NULL, 0, 0, 0, 0, '2014-08-14 18:12:57', 0, '2014-08-14 18:12:57'),
(10, 'student_prefix', 'Auto Generated OpenEMIS ID', 'toggleVal', 'Student Prefix', 'Prefix for auto generated ID', '1,STU', NULL, 1, 0, 1, 1, NULL, 0, '0000-00-00 00:00:00'),
(11, 'staff_prefix', 'Auto Generated OpenEMIS ID', 'toggleVal', 'Staff Prefix', 'Prefix for auto generated ID', '1,STA', NULL, 1, 0, 1, 0, NULL, 0, '0000-00-00 00:00:00'),
(12, 'guardian_prefix', 'Auto Generated OpenEMIS ID', 'toggleVal', 'Guardian Prefix', 'Prefix for auto generated ID', '1,GUA', NULL, 1, 0, 1, 1, NULL, 0, '0000-00-00 00:00:00'),
(13, 'admin_prefix', 'Auto Generated OpenEMIS ID', 'toggleVal', 'Admin Prefix', 'Prefix for auto generated ID', '1,ADM', NULL, 1, 0, 1, 0, NULL, 0, '0000-00-00 00:00:00');


TRUNCATE `config_item_options`;
INSERT INTO `config_item_options` (`id`, `name`, `value`, `order`, `visible`, `config_item_id`, `created`) VALUES
(1, '1', '1', 1, 1, 5, '0000-00-00 00:00:00'),
(2, '2', '2', 2, 1, 5, '0000-00-00 00:00:00'),
(3, '3', '3', 3, 1, 5, '0000-00-00 00:00:00'),
(4, '4', '4', 4, 1, 5, '0000-00-00 00:00:00'),
(5, '5', '5', 5, 1, 5, '0000-00-00 00:00:00'),
(6, 'Day', 'Day', 1, 1, 6, '0000-00-00 00:00:00'),
(7, 'Lesson', 'Lesson', 2, 1, 6, '0000-00-00 00:00:00'),
(8, 'First Last', 'SecurityUser.first_name,SecurityUser.last_name', 1, 1, 7, '0000-00-00 00:00:00'),
(9, 'First Middle Last', 'SecurityUser.first_name,SecurityUser.middle_name,SecurityUser.last_name', 2, 1, 7, '0000-00-00 00:00:00'),
(10, 'Last First', 'SecurityUser.last_name,SecurityUser.first_name', 3, 1, 7, '0000-00-00 00:00:00'),
(11, 'Last Middle First', 'SecurityUser.last_name,SecurityUser.middle_name,SecurityUser.first_name', 4, 1, 7, '0000-00-00 00:00:00'),
(12, 'English', 'en', 1, 1, 8, '0000-00-00 00:00:00'),
(13, 'español', 'es', 1, 1, 8, '0000-00-00 00:00:00'),
(14, 'français', 'fr', 1, 1, 8, '0000-00-00 00:00:00'),
(15, 'русский', 'ru', 1, 1, 8, '0000-00-00 00:00:00'),
(16, '中文', 'zh', 1, 1, 8, '0000-00-00 00:00:00'),
(17, 'لعربية', 'ar', 1, 1, 8, '0000-00-00 00:00:00');

TRUNCATE `contact_types`;
INSERT INTO `contact_types` (`id`, `name`, `order`, `visible`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'Mobile', 1, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(2, 'Phone', 2, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(3, 'Fax', 3, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(4, 'Email', 4, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(5, 'Other', 5, 1, NULL, NULL, 1, '0000-00-00 00:00:00');

TRUNCATE `countries`;
INSERT INTO `countries` (`id`, `name`, `international_code`, `national_code`, `order`, `visible`, `editable`, `default`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'Afghanistan', NULL, NULL, 1, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(2, 'Aland Islands', NULL, NULL, 2, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(3, 'Albania', NULL, NULL, 3, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(4, 'Algeria', NULL, NULL, 4, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(5, 'American Samoa', NULL, NULL, 5, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(6, 'Andorra', NULL, NULL, 6, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(7, 'Angola', NULL, NULL, 7, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(8, 'Anguilla', NULL, NULL, 8, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(9, 'Antarctica', NULL, NULL, 9, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(10, 'Antigua and Barbuda', NULL, NULL, 10, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(11, 'Argentina', NULL, NULL, 11, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(12, 'Armenia', NULL, NULL, 12, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(13, 'Aruba', NULL, NULL, 13, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(14, 'Australia', NULL, NULL, 14, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(15, 'Austria', NULL, NULL, 15, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(16, 'Azerbaijan', NULL, NULL, 16, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(17, 'Bahamas', NULL, NULL, 17, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(18, 'Bahrain', NULL, NULL, 18, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(19, 'Bangladesh', NULL, NULL, 19, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(20, 'Barbados', NULL, NULL, 20, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(21, 'Belarus', NULL, NULL, 21, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(22, 'Belgium', NULL, NULL, 22, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(23, 'Belize', NULL, NULL, 23, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(24, 'Benin', NULL, NULL, 24, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(25, 'Bermuda', NULL, NULL, 25, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(26, 'Bhutan', NULL, NULL, 26, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(27, 'Bolivia', NULL, NULL, 27, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(28, 'Bonaire, Sint Eustatius and Saba', NULL, NULL, 28, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(29, 'Bosnia and Herzegovina', NULL, NULL, 29, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(30, 'Botswana', NULL, NULL, 30, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(31, 'Bouvet Island', NULL, NULL, 31, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(32, 'Brazil', NULL, NULL, 32, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(33, 'British Indian Ocean Territory', NULL, NULL, 33, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(34, 'Brunei', NULL, NULL, 34, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(35, 'Bulgaria', NULL, NULL, 35, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(36, 'Burkina Faso', NULL, NULL, 36, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(37, 'Burundi', NULL, NULL, 37, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(38, 'Cambodia', NULL, NULL, 38, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(39, 'Cameroon', NULL, NULL, 39, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(40, 'Canada', NULL, NULL, 40, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(41, 'Cape Verde', NULL, NULL, 41, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(42, 'Cayman Islands', NULL, NULL, 42, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(43, 'Central African Republic', NULL, NULL, 43, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(44, 'Chad', NULL, NULL, 44, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(45, 'Chile', NULL, NULL, 45, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(46, 'China', NULL, NULL, 46, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(47, 'Christmas Island', NULL, NULL, 47, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(48, 'Cocos (Keeling) Islands', NULL, NULL, 48, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(49, 'Colombia', NULL, NULL, 49, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(50, 'Comoros', NULL, NULL, 50, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(51, 'Congo', NULL, NULL, 51, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(52, 'Cook Islands', NULL, NULL, 52, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(53, 'Costa Rica', NULL, NULL, 53, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(54, 'Cote d''ivoire (Ivory Coast)', NULL, NULL, 54, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(55, 'Croatia', NULL, NULL, 55, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(56, 'Cuba', NULL, NULL, 56, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(57, 'Curacao', NULL, NULL, 57, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(58, 'Cyprus', NULL, NULL, 58, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(59, 'Czech Republic', NULL, NULL, 59, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(60, 'Democratic Republic of the Congo', NULL, NULL, 60, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(61, 'Denmark', NULL, NULL, 61, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(62, 'Djibouti', NULL, NULL, 62, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(63, 'Dominica', NULL, NULL, 63, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(64, 'Dominican Republic', NULL, NULL, 64, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(65, 'Ecuador', NULL, NULL, 65, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(66, 'Egypt', NULL, NULL, 66, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(67, 'El Salvador', NULL, NULL, 67, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(68, 'Equatorial Guinea', NULL, NULL, 68, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(69, 'Eritrea', NULL, NULL, 69, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(70, 'Estonia', NULL, NULL, 70, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(71, 'Ethiopia', NULL, NULL, 71, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(72, 'Falkland Islands (Malvinas)', NULL, NULL, 72, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(73, 'Faroe Islands', NULL, NULL, 73, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(74, 'Fiji', NULL, NULL, 74, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(75, 'Finland', NULL, NULL, 75, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(76, 'France', NULL, NULL, 76, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(77, 'French Guiana', NULL, NULL, 77, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(78, 'French Polynesia', NULL, NULL, 78, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(79, 'French Southern Territories', NULL, NULL, 79, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(80, 'Gabon', NULL, NULL, 80, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(81, 'Gambia', NULL, NULL, 81, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(82, 'Georgia', NULL, NULL, 82, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(83, 'Germany', NULL, NULL, 83, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(84, 'Ghana', NULL, NULL, 84, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(85, 'Gibraltar', NULL, NULL, 85, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(86, 'Greece', NULL, NULL, 86, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(87, 'Greenland', NULL, NULL, 87, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(88, 'Grenada', NULL, NULL, 88, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(89, 'Guadaloupe', NULL, NULL, 89, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(90, 'Guam', NULL, NULL, 90, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(91, 'Guatemala', NULL, NULL, 91, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(92, 'Guernsey', NULL, NULL, 92, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(93, 'Guinea', NULL, NULL, 93, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(94, 'Guinea-Bissau', NULL, NULL, 94, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(95, 'Guyana', NULL, NULL, 95, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(96, 'Haiti', NULL, NULL, 96, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(97, 'Heard Island and McDonald Islands', NULL, NULL, 97, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(98, 'Honduras', NULL, NULL, 98, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(99, 'Hong Kong', NULL, NULL, 99, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(100, 'Hungary', NULL, NULL, 100, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(101, 'Iceland', NULL, NULL, 101, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(102, 'India', NULL, NULL, 102, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(103, 'Indonesia', NULL, NULL, 103, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(104, 'Iran', NULL, NULL, 104, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(105, 'Iraq', NULL, NULL, 105, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(106, 'Ireland', NULL, NULL, 106, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(107, 'Isle of Man', NULL, NULL, 107, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(108, 'Israel', NULL, NULL, 108, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(109, 'Italy', NULL, NULL, 109, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(110, 'Jamaica', NULL, NULL, 110, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(111, 'Japan', NULL, NULL, 111, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(112, 'Jersey', NULL, NULL, 112, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(113, 'Jordan', NULL, NULL, 113, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(114, 'Kazakhstan', NULL, NULL, 114, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(115, 'Kenya', NULL, NULL, 115, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(116, 'Kiribati', NULL, NULL, 116, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(117, 'Kosovo', NULL, NULL, 117, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(118, 'Kuwait', NULL, NULL, 118, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(119, 'Kyrgyzstan', NULL, NULL, 119, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(120, 'Laos', NULL, NULL, 120, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(121, 'Latvia', NULL, NULL, 121, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(122, 'Lebanon', NULL, NULL, 122, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(123, 'Lesotho', NULL, NULL, 123, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(124, 'Liberia', NULL, NULL, 124, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(125, 'Libya', NULL, NULL, 125, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(126, 'Liechtenstein', NULL, NULL, 126, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(127, 'Lithuania', NULL, NULL, 127, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(128, 'Luxembourg', NULL, NULL, 128, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(129, 'Macao', NULL, NULL, 129, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(130, 'Macedonia', NULL, NULL, 130, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(131, 'Madagascar', NULL, NULL, 131, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(132, 'Malawi', NULL, NULL, 132, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(133, 'Malaysia', NULL, NULL, 133, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(134, 'Maldives', NULL, NULL, 134, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(135, 'Mali', NULL, NULL, 135, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(136, 'Malta', NULL, NULL, 136, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(137, 'Marshall Islands', NULL, NULL, 137, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(138, 'Martinique', NULL, NULL, 138, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(139, 'Mauritania', NULL, NULL, 139, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(140, 'Mauritius', NULL, NULL, 140, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(141, 'Mayotte', NULL, NULL, 141, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(142, 'Mexico', NULL, NULL, 142, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(143, 'Micronesia', NULL, NULL, 143, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(144, 'Moldava', NULL, NULL, 144, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(145, 'Monaco', NULL, NULL, 145, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(146, 'Mongolia', NULL, NULL, 146, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(147, 'Montenegro', NULL, NULL, 147, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(148, 'Montserrat', NULL, NULL, 148, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(149, 'Morocco', NULL, NULL, 149, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(150, 'Mozambique', NULL, NULL, 150, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(151, 'Myanmar (Burma)', NULL, NULL, 151, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(152, 'Namibia', NULL, NULL, 152, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(153, 'Nauru', NULL, NULL, 153, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(154, 'Nepal', NULL, NULL, 154, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(155, 'Netherlands', NULL, NULL, 155, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(156, 'New Caledonia', NULL, NULL, 156, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(157, 'New Zealand', NULL, NULL, 157, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(158, 'Nicaragua', NULL, NULL, 158, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(159, 'Niger', NULL, NULL, 159, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(160, 'Nigeria', NULL, NULL, 160, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(161, 'Niue', NULL, NULL, 161, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(162, 'Norfolk Island', NULL, NULL, 162, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(163, 'North Korea', NULL, NULL, 163, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(164, 'Northern Mariana Islands', NULL, NULL, 164, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(165, 'Norway', NULL, NULL, 165, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(166, 'Oman', NULL, NULL, 166, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(167, 'Pakistan', NULL, NULL, 167, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(168, 'Palau', NULL, NULL, 168, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(169, 'Palestine', NULL, NULL, 169, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(170, 'Panama', NULL, NULL, 170, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(171, 'Papua New Guinea', NULL, NULL, 171, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(172, 'Paraguay', NULL, NULL, 172, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(173, 'Peru', NULL, NULL, 173, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(174, 'Phillipines', NULL, NULL, 174, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(175, 'Pitcairn', NULL, NULL, 175, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(176, 'Poland', NULL, NULL, 176, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(177, 'Portugal', NULL, NULL, 177, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(178, 'Puerto Rico', NULL, NULL, 178, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(179, 'Qatar', NULL, NULL, 179, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(180, 'Reunion', NULL, NULL, 180, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(181, 'Romania', NULL, NULL, 181, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(182, 'Russia', NULL, NULL, 182, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(183, 'Rwanda', NULL, NULL, 183, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(184, 'Saint Barthelemy', NULL, NULL, 184, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(185, 'Saint Helena', NULL, NULL, 185, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(186, 'Saint Kitts and Nevis', NULL, NULL, 186, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(187, 'Saint Lucia', NULL, NULL, 187, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(188, 'Saint Martin', NULL, NULL, 188, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(189, 'Saint Pierre and Miquelon', NULL, NULL, 189, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(190, 'Saint Vincent and the Grenadines', NULL, NULL, 190, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(191, 'Samoa', NULL, NULL, 191, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(192, 'San Marino', NULL, NULL, 192, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(193, 'Sao Tome and Principe', NULL, NULL, 193, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(194, 'Saudi Arabia', NULL, NULL, 194, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(195, 'Senegal', NULL, NULL, 195, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(196, 'Serbia', NULL, NULL, 196, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(197, 'Seychelles', NULL, NULL, 197, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(198, 'Sierra Leone', NULL, NULL, 198, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(199, 'Singapore', NULL, NULL, 199, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(200, 'Sint Maarten', NULL, NULL, 200, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(201, 'Slovakia', NULL, NULL, 201, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(202, 'Slovenia', NULL, NULL, 202, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(203, 'Solomon Islands', NULL, NULL, 203, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(204, 'Somalia', NULL, NULL, 204, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(205, 'South Africa', NULL, NULL, 205, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(206, 'South Georgia and the South Sandwich Islands', NULL, NULL, 206, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(207, 'South Korea', NULL, NULL, 207, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(208, 'South Sudan', NULL, NULL, 208, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(209, 'Spain', NULL, NULL, 209, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(210, 'Sri Lanka', NULL, NULL, 210, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(211, 'Sudan', NULL, NULL, 211, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(212, 'Suriname', NULL, NULL, 212, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(213, 'Svalbard and Jan Mayen', NULL, NULL, 213, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(214, 'Swaziland', NULL, NULL, 214, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(215, 'Sweden', NULL, NULL, 215, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(216, 'Switzerland', NULL, NULL, 216, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(217, 'Syria', NULL, NULL, 217, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(218, 'Taiwan', NULL, NULL, 218, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(219, 'Tajikistan', NULL, NULL, 219, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(220, 'Tanzania', NULL, NULL, 220, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(221, 'Thailand', NULL, NULL, 221, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(222, 'Timor-Leste (East Timor)', NULL, NULL, 222, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(223, 'Togo', NULL, NULL, 223, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(224, 'Tokelau', NULL, NULL, 224, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(225, 'Tonga', NULL, NULL, 225, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(226, 'Trinidad and Tobago', NULL, NULL, 226, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(227, 'Tunisia', NULL, NULL, 227, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(228, 'Turkey', NULL, NULL, 228, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(229, 'Turkmenistan', NULL, NULL, 229, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(230, 'Turks and Caicos Islands', NULL, NULL, 230, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(231, 'Tuvalu', NULL, NULL, 231, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(232, 'Uganda', NULL, NULL, 232, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(233, 'Ukraine', NULL, NULL, 233, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(234, 'United Arab Emirates', NULL, NULL, 234, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(235, 'United Kingdom', NULL, NULL, 235, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(236, 'United States', NULL, NULL, 236, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(237, 'United States Minor Outlying Islands', NULL, NULL, 237, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(238, 'Uruguay', NULL, NULL, 238, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(239, 'Uzbekistan', NULL, NULL, 239, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(240, 'Vanuatu', NULL, NULL, 240, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(241, 'Vatican City', NULL, NULL, 241, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(242, 'Venezuela', NULL, NULL, 242, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(243, 'Vietnam', NULL, NULL, 243, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(244, 'Virgin Islands, British', NULL, NULL, 244, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(245, 'Virgin Islands, US', NULL, NULL, 245, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(246, 'Wallis and Futuna', NULL, NULL, 246, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(247, 'Western Sahara', NULL, NULL, 247, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(248, 'Yemen', NULL, NULL, 248, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(249, 'Zambia', NULL, NULL, 249, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52'),
(250, 'Zimbabwe', NULL, NULL, 250, 1, 1, 0, NULL, NULL, 1, '2013-11-28 13:26:52');

TRUNCATE `field_options`;
INSERT INTO `field_options` (`id`, `code`, `name`, `parent`, `params`, `order`, `visible`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'AssessmentResultType', 'Result Types', 'Assessment', '{"model":"AssessmentResultType"}', 1, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(2, 'StudentCategory', 'Categories', 'Student', '{"model":"StudentCategory"}', 2, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(3, 'StudentStatus', 'Statuses', 'Student', '{"model":"StudentStatus"}', 3, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(4, 'StudentAttendanceType', 'Attendance Types', 'Student', '{"model":"StudentAttendanceType"}', 4, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(5, 'StaffCategory', 'Categories', 'Staff', '{"model":"StaffCategory"}', 5, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(6, 'StaffStatus', 'Statuses', 'Staff', '{"model":"StaffStatus"}', 6, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(7, 'StaffAttendanceType', 'Attendance Types', 'Staff', '{"model":"StaffAttendanceType"}', 7, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(8, 'StaffEmploymentType', 'Employment Types', 'Staff', '{"model":"StaffEmploymentType"}', 8, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(9, 'RelationshipCategory', 'Relationship Categories', 'Guardian', '{"model":"RelationshipCategory"}', 9, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(10, 'ContactType', 'Types', 'Contact', '{"model":"ContactType"}', 10, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(11, 'BehaviourCategory', 'Categories', 'Behaviour', '{"model":"BehaviourCategory"}', 11, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(12, 'SchoolYear', 'School Years', 'Academic', '{"model":"SchoolYear"}', 12, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(13, 'LessonStatus', 'Statuses', 'Lesson', '{"model":"LessonStatus"}', 13, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(14, 'Room', 'Rooms', 'Infrastructure', '{"model":"Room"}', 14, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(15, 'IdentityType', 'Types', 'Identity', '{"model":"IdentityType"}', 15, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(16, 'Country', 'Nationality', 'Country', '{"model":"Country"}', 16, 1, NULL, NULL, 1, '0000-00-00 00:00:00'),
(17, 'FeeType', 'Types', 'Fee', '{"model":"FeeType"}', 17, 1, NULL, NULL, 1, '0000-00-00 00:00:00');


TRUNCATE `field_option_values`;
INSERT INTO `field_option_values` (`id`, `name`, `international_code`, `national_code`, `order`, `visible`, `editable`, `default`, `field_option_id`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'Mobile', NULL, NULL, 1, 1, 1, 0, 10, NULL, NULL, 1, '2014-08-14 18:12:38'),
(2, 'Phone', NULL, NULL, 2, 1, 1, 0, 10, NULL, NULL, 1, '2014-08-14 18:12:38'),
(3, 'Fax', NULL, NULL, 3, 1, 1, 0, 10, NULL, NULL, 1, '2014-08-14 18:12:38'),
(4, 'Email', NULL, NULL, 4, 1, 1, 0, 10, NULL, NULL, 1, '2014-08-14 18:12:38'),
(5, 'Other', NULL, NULL, 5, 1, 1, 0, 10, NULL, NULL, 1, '2014-08-14 18:12:38');


TRUNCATE TABLE `assessment_result_types`;
INSERT INTO `assessment_result_types` (`id`, `name`, `min`, `max`, `order`, `visible`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'Pass', 50, 100, 1, 1, NULL, NULL, 1, NOW()),
(2, 'Fail', 0, 49, 2, 1, NULL, NULL, 1, NOW());

TRUNCATE TABLE `student_categories`;
INSERT INTO `student_categories` (`id`, `name`, `order`, `visible`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'New Enrolment', 1, 1, NULL, NULL, 1, NOW()),
(2, 'Transferred In', 2, 1, NULL, NULL, 1, NOW()),
(3, 'Graduated', 3, 1, NULL, NULL, 1, NOW()),
(4, 'Dropout', 4, 1, NULL, NULL, 1, NOW());

TRUNCATE TABLE `student_statuses`;
INSERT INTO `student_statuses` (`id`, `name`, `order`, `visible`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'Current Student', 1, 1, NULL, NULL, 1, NOW());

TRUNCATE TABLE `staff_categories`;
INSERT INTO `staff_categories` (`id`, `name`, `order`, `visible`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'Principal', 1, 1, NULL, NULL, 1, NOW()),
(2, 'Head Teacher', 2, 1, NULL, NULL, 1, NOW()),
(3, 'Teacher', 3, 1, NULL, NULL, 1, NOW()),
(4, 'Administrative Officer', 4, 1, NULL, NULL, 1, NOW()),
(5, 'Librarian', 5, 1, NULL, NULL, 1, NOW());

TRUNCATE TABLE `staff_statuses`;
INSERT INTO `staff_statuses` (`id`, `name`, `order`, `visible`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'Current', 1, 1, NULL, NULL, 1, NOW()),
(2, 'Transferred', 2, 1, NULL, NULL, 1, NOW()),
(3, 'Resigned', 3, 1, NULL, NULL, 1, NOW()),
(4, 'Terminated', 4, 1, NULL, NULL, 1, NOW());

TRUNCATE TABLE `staff_employment_types`;
INSERT INTO `staff_employment_types` (`id`, `name`, `order`, `visible`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'Appointment', 1, 1, NULL, NULL, 1, NOW()),
(2, 'Probation', 2, 1, NULL, NULL, 1, NOW()),
(3, 'Extension', 3, 1, NULL, NULL, 1, NOW()),
(4, 'Increment', 4, 1, NULL, NULL, 1, NOW()),
(5, 'Termination', 5, 1, NULL, NULL, 1, NOW()),
(6, 'Resignation', 6, 1, NULL, NULL, 1, NOW()),
(7, 'Retirement', 7, 1, NULL, NULL, 1, NOW()),
(8, 'Contract End', 8, 1, NULL, NULL, 1, NOW());

TRUNCATE TABLE `relationship_categories`;
INSERT INTO `relationship_categories` (`id`, `name`, `order`, `visible`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'Mother', 1, 1, NULL, NULL, 1, NOW()),
(2, 'Father', 2, 1, NULL, NULL, 1, NOW()),
(3, 'Aunt', 3, 1, NULL, NULL, 1, NOW()),
(4, 'Uncle', 4, 1, NULL, NULL, 1, NOW());

TRUNCATE TABLE `lesson_statuses`;
INSERT INTO `lesson_statuses` (`id`, `name`, `order`, `visible`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'Active', 1, 1, NULL, NULL, 1, NOW()),
(2, 'Cancelled', 2, 1, NULL, NULL, 1, NOW());

TRUNCATE TABLE `rooms`;
INSERT INTO `rooms` (`id`, `name`, `location`, `order`, `visible`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'Classroom 1', 'Block 1', 1, 1, NULL, NULL, 1, NOW()),
(2, 'Classroom 2', 'Block 1', 1, 1, NULL, NULL, 1, NOW());

TRUNCATE TABLE `behaviour_categories`;
INSERT INTO `behaviour_categories` (`id`, `name`, `order`, `visible`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'Outstanding', 1, 1, NULL, NULL, 1, NOW()),
(2, 'Good', 2, 1, NULL, NULL, 1, NOW()),
(3, 'Poor', 3, 1, NULL, NULL, 1, NOW());

TRUNCATE TABLE `school_years`;
INSERT INTO `school_years` (`id`, `name`, `start_date`, `start_year`, `end_date`, `end_year`, `school_days`, `visible`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, '2014', '2014-01-01', 2014, '2014-12-31', 2014, 240, 1, NULL, NULL, 1, NOW());

TRUNCATE `identity_types`;
INSERT INTO `identity_types` (`id`, `name`, `order`, `visible`, `international_code`, `national_code`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'National', 1, 1, NULL, NULL, NULL, NULL, 1, '0000-00-00 00:00:00'),
(2, 'School', 2, 1, NULL, NULL, NULL, NULL, 1, '0000-00-00 00:00:00'),
(3, 'UNHCR', 3, 1, NULL, NULL, NULL, NULL, 1, '0000-00-00 00:00:00'),
(4, 'Passport', 4, 1, NULL, NULL, NULL, NULL, 1, '0000-00-00 00:00:00');

TRUNCATE `staff_attendance_types`;
INSERT INTO `staff_attendance_types` (`id`, `name`, `short_form`, `order`, `visible`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'Attendance type 1', 'AT1', 1, 1, 1, '2014-01-03 16:08:16', 1, '2014-01-03 16:08:08'),
(2, 'Sick', 'SK', 2, 1, NULL, NULL, 1, '2014-06-13 21:39:30'),
(3, 'Work travel', 'WT', 0, 1, NULL, NULL, 1, '2014-09-25 21:18:03');

TRUNCATE `student_attendance_types`;
INSERT INTO `student_attendance_types` (`id`, `name`, `short_form`, `order`, `visible`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, 'Late', 'L', 3, 1, NULL, NULL, 1, '2014-04-02 14:54:25'),
(2, 'Absent with excuse', 'ABE', 4, 1, NULL, NULL, 1, '2014-04-02 14:55:07'),
(3, 'Present', '✓', 1, 1, NULL, NULL, 1, '2014-04-03 11:31:34'),
(4, 'Absent', '✘', 2, 1, NULL, NULL, 1, '2014-04-03 11:32:18');

TRUNCATE `translations`;
INSERT INTO `translations` (`id`, `code`, `eng`, `ara`, `chi`, `spa`, `fre`, `rus`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(1, NULL, 'File size should not be larger than 2MB.', 'لا ينبغي أن يكون حجم ملف أكبر من 2 ميغا بايت', '文件大小应不低于2兆的', 'El tamaÃ±o del archivo no debe ser mayor que 2 megabytes', 'La taille du fichier ne doit pas Ãªtre supÃ©rieure Ã  2 mÃ©ga-octets', 'Размер файла не должен превышать 2 Мб', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(2, NULL, 'Academic', 'أكاديمي', '学者', 'acadÃ©mico', 'acadÃ©mique', 'академический', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(3, NULL, 'Academic Details', 'تفاصيل الأكاديمية', '学术详情', 'Datos AcadÃ©micos', 'DÃ©tails universitaires', 'Академические Подробнее', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(4, NULL, 'Action', 'عمل', '行动', 'acciÃ³n', 'action', 'действие', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(5, NULL, 'Active', 'نشط', '活跃', 'activo', 'actif', 'активный', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(6, NULL, 'Add', 'إضافة', '加', 'aÃ±adir', 'ajouter', 'добавлять', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(7, NULL, 'Add New Entry', 'إضافة إدخال جديد', '添加新条目', 'AÃ±adir nuevo ingreso', 'Ajouter une nouvelle entrÃ©e', 'Добавить новую запись', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(8, NULL, 'Address', 'عنوان', '地址', 'direcciÃ³n', 'adresse', 'адрес', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(9, NULL, 'All Years', 'جميع سنوات', '所有的年份', 'Todos los aÃ±os', 'toutes les annÃ©es', 'All Years', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(10, NULL, 'April', 'أبريل', '四月', 'abril', 'avril', 'апрель', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(11, NULL, 'Attachment', 'التعلق', '附件', 'accesorio', 'fixation', 'привязанность', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(12, NULL, 'Attachments', 'مرفقات', '附件', 'Adjuntos', 'Annexes', 'Вложения', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(13, NULL, 'Attendance', 'الحضور', '护理', 'asistencia', 'prÃ©sence', 'посещаемость', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(14, NULL, 'Attendance by Day', 'حضور اليوم', '应邀出席者日', 'Asistencia de DÃ­a', 'Participation par jour', 'Посещаемость за днем', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(15, NULL, 'Attendance by Lesson', 'حضور الدرس', '应邀出席者课', 'La asistencia de la lecciÃ³n', 'Participation de la leÃ§on', 'Посещение урока', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(16, NULL, 'August', 'أغسطس', '八月', 'agosto', 'aoÃ»t', 'август', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(17, NULL, 'Back', 'ظهر', '后面', 'espalda', 'arriÃ¨re', 'назад', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(18, NULL, 'Behaviour', 'السلوك', '行为', 'Comportamiento', 'Comportement', 'Поведение', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(19, NULL, 'Behaviour Category', 'سلوك الفئة', '行为类别', 'Comportamiento CategorÃ­a', 'Comportement CatÃ©gorie', 'Поведение Категория', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(20, NULL, 'Cancel', 'إلغاء', '取消', 'cancelar', 'annuler', 'отменить', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(21, NULL, 'Categories', 'الفئات', '分类', 'CategorÃ­as', 'catÃ©gories', 'Категории', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(22, NULL, 'Category', 'فئة', '类别', 'categorÃ­a', 'catÃ©gorie', 'категория', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(23, NULL, 'Change', 'تغيير', '变化', 'cambio', 'changement', 'изменение', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(24, NULL, 'Change Password', 'تغيير كلمة المرور', '更改密码', 'Cambiar contraseÃ±a', 'changer mot de passe', 'Изменить пароль', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(25, NULL, 'Chinese', 'الصينية', '中文', 'chino', 'chinois', 'китайский', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(26, NULL, 'Classes', 'الطبقات', '班', 'Clases', 'cours', 'Классы', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(27, NULL, 'Close', 'قريب', '关闭', 'cerca', 'proche', 'близко', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(28, NULL, 'Code', 'رمز', '码', 'cÃ³digo', 'code', 'код', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(29, NULL, 'Comment', 'تعليق', '评论', 'comentario', 'commentaire', 'комментарий', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(30, NULL, 'Contact', 'اتصال', '联系', 'contacto', 'Contacter', 'контакт', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(31, NULL, 'Contact Person', 'شخص الاتصال', '联系人', 'persona de Contacto', 'personne Ã  contacter', 'Контактное лицо', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(32, NULL, 'Created By', 'التي أنشأتها', '创建人', 'Creado por', 'CrÃ©e par', 'Создано', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(33, NULL, 'Created On', 'أنشئت في', '创建时间', 'Creado el', 'crÃ©Ã© le', 'Дата создания', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(34, NULL, 'Date', 'تاريخ', '日期', 'fecha', 'date', 'дата', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(35, NULL, 'Date Closed', 'تاريخ مغلق', '休息日', 'Fecha Cerrada', 'Date de fermeture', 'дата окончания', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(36, NULL, 'Date Of Behaviour', 'تاريخ السلوك', '行为的日期', 'Fecha del Comportamiento', 'Date de comportement', 'Дата поведения', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(37, NULL, 'Date Opened', 'تاريخ فتح', '开业日期', 'Fecha de Apertura', 'Date d''ouverture', 'Дата открытия', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(38, NULL, 'Date Uploaded', 'تاريخ التحميل', '上传日期', 'Fecha de Subida', 'Date d''envoi', 'Дата загрузки', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(39, NULL, 'Day', 'يوم', '日', 'dÃ­a', 'jour', 'день', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(40, NULL, 'December', 'ديسمبر', '十二月', 'diciembre', 'dÃ©cembre', 'декабрь', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(41, NULL, 'Delete', 'حذف', '删除', 'borrar', 'effacer', 'удалять', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(42, NULL, 'You are able to delete this record in the database. All related information of this record will also be deleted. Are you sure you want to do this?', 'كنت قادرا على حذف هذا السجل في قاعدة البيانات. كما سيتم حذف كافة المعلومات ذات الصلة من هذا السجل. هل أنت متأكد أنك تريد أن تفعل هذا؟', '您可以在数据库中删除这个记录。此记录的所有相关信息也将被删除。您确定要这么做吗？', 'Usted es capaz de eliminar este registro en la base de datos. Toda la informaciÃ³n relacionada de este registro tambiÃ©n serÃ¡n eliminados. Â¿EstÃ¡s seguro de que quieres hacer esto?', 'Vous Ãªtes en mesure de supprimer cet enregistrement dans la base de donnÃ©es. Toutes les informations relatives de ce dossier seront Ã©galement supprimÃ©s. Etes-vous sÃ»r de vouloir faire cela?', 'Вы можете удалить эту запись в базе данных. Все информация, связанная с этой записи также будут удалены. Вы уверены, что хотите это сделать?', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(43, NULL, 'Details', 'تفاصيل', '详细信息', 'Detalles', 'dÃ©tails', 'детали', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(44, NULL, 'Description', 'وصف', '描述', 'descripciÃ³n', 'description', 'описание', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(45, NULL, 'Duration', 'مدة', '长短', 'duraciÃ³n', 'durÃ©e', 'продолжительность', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(46, NULL, 'Edit', 'تحرير', '编辑', 'editar', 'Modifier', 'редактировать', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(47, NULL, 'Email', 'البريد الإلكتروني', '电子邮件', 'Email', 'email', 'электронная почта', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(48, NULL, 'End Date', 'تاريخ نهاية', '结束日期', 'Fecha de finalizaciÃ³n', 'date de fin', 'Дата окончания', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(49, NULL, 'End Time', 'نهاية الوقت', '结束时间', 'Hora de finalizaciÃ³n', 'Heure de fin', 'Время окончания', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(50, NULL, 'English', 'الإنجليزية', '英语', 'InglÃ©s', 'Anglais', 'английский', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(51, NULL, 'Fax', 'الفاكس', '传真', 'fax', 'fax', 'факс', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(52, NULL, 'February', 'فبراير', '二月', 'febrero', 'fÃ©vrier', 'февраль', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(53, NULL, 'Female', 'أنثى', '女', 'femenino', 'femelle', 'женский', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(54, NULL, 'File', 'ملف', '文件', 'expediente', 'dossier', 'файл', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(55, NULL, 'File Name', 'اسم الملف', '文件名', 'Nombre del archivo', 'Nom du fichier', 'Название файла', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(56, NULL, 'File Type', 'نوع الملف', '文件类型', 'Tipo de Archivo', 'Type de fichier', 'Тип файла', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(57, NULL, 'First Name', 'الاسم الأول', '名字', 'Nombre', 'PrÃ©nom', 'Имя', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(58, NULL, 'Fri', 'الجمعة', '周五', 'vie', 'vendredi', 'пятница', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(59, NULL, 'Friday', 'الجمعة', '星期五', 'viernes', 'vendredi', 'пятница', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(60, NULL, 'Gender', 'غير معروف', '性别', 'desconocido', 'inconnu', 'неизвестный', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(61, NULL, 'General', 'عام', '一般', 'general', 'gÃ©nÃ©ral', 'общий', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(62, NULL, 'Grade', 'درجة', '等级', 'grado', 'grade', 'класс', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(63, NULL, 'Grades', 'الدرجات', '等次', 'grados', 'grades', 'Оценки', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(64, NULL, 'Grading', 'الدرجات', '等级', 'clasificaciÃ³n', 'Condition', 'сортировка', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(65, NULL, 'Guardian', 'وصي', '监护人', 'tutor', 'tuteur', 'опекун', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(66, NULL, 'Guardians', 'الأوصياء', '监护人', 'Guardianes', 'gardiens', 'Стражи', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(67, NULL, 'Identification', 'تحديد', '鉴定', 'identificaciÃ³n', 'identification', 'идентификация', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(68, NULL, 'Identification No', 'لا تحديد', '身份证号码', 'No de identificaciÃ³n', 'N Â° d''identification', 'Идентификационный номер', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(69, NULL, 'Identification No or Name is not found', 'لم يتم العثور على تحديد اسم أو لا', '身份证号码或名称未找到', 'No de identificaciÃ³n o nombre no se encuentra', 'N Â° d''identification ou le nom n''est pas trouvÃ©', 'Идентификационный номер или имя не найдено', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(70, NULL, 'Identification No, First Name or Last Name', 'تحديد لا، الاسم الأول أو اسم العائلة', '身份证号码，名字或姓氏', 'No de identificaciÃ³n, Nombre o Apellido', 'N Â° d''identification, le prÃ©nom ou le nom', 'Идентификационный номер, Имя или Фамилия', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(71, NULL, 'Identification No or Name', 'تحديد اسم أو لا', '身份证号码或名称', 'No de identificaciÃ³n o nombre', 'N Â° d''identification ou nom', 'Идентификационный номер или имя', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(72, NULL, 'Inactive', 'غير فعال', '不活跃', 'inactivo', 'inactif', 'неактивный', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(73, NULL, 'Infrastructure', 'بنية التحتية', '基础设施', 'infraestructura', 'infrastructure', 'инфраструктура', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(74, NULL, 'January', 'يناير', '一月', 'enero', 'janvier', 'январь', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(75, NULL, 'July', 'يوليو', '七月', 'julio', 'juillet', 'июль', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(76, NULL, 'June', 'يونيو', '六月', 'junio', 'juin', 'июнь', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(77, NULL, 'Last Modified By', 'التعديل الأخير من قبل', '上次修改者', 'Ãšltima modificaciÃ³n realizada por', 'DerniÃ¨re modification par', 'Последний Изменено', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(78, NULL, 'Last Modified On', 'آخر تعديل ل', '最后修改于', 'Ultima modificacion el', 'DerniÃ¨re modification de', 'Последнее изменение', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(79, NULL, 'Last Name', 'اسم العائلة', '姓', 'apellido', 'Nom', 'Фамилия', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(80, NULL, 'Location', 'موقع', '位置', 'ubicaciÃ³n', 'emplacement', 'расположение', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(81, NULL, 'Logout', 'تسجيل الخروج', '注销', 'Salir', 'DÃ©connexion', 'Выход', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(82, NULL, 'Malay', 'لغة الملايو', '马来人', 'malayo', 'malais', 'малайский', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(83, NULL, 'Male', 'ذكر', '男性', 'masculino', 'mÃ¢le', 'мужской', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(84, NULL, 'March', 'مسيرة', '三月', 'marzo', 'mars', 'март', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(85, NULL, 'Marks', 'علامات', '商标', 'Marcas', 'marques', 'маркировка', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(86, NULL, 'Maths', 'رياضيات', '数学', 'matemÃ¡ticas', 'math', 'математика', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(87, NULL, 'Max', 'ماكس', '最大', 'Max', 'max', 'Макс', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(88, NULL, 'Maximum', 'أقصى', '最大', 'mÃ¡ximo', 'maximum', 'максимум', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(89, NULL, 'May', 'قد', '五月', 'mayo', 'mai', 'май', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(90, NULL, 'Min', 'في', '最小', 'en', 'sur', 'на', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(91, NULL, 'Minimum', 'الحد الأدنى', '最低限度', 'mÃ­nimo', 'minimum', 'минимум', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(92, NULL, 'Modified By', 'تم التعديل بواسطة', '修改者', 'Modificado por', 'modifiÃ© par', 'Изменено', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(93, NULL, 'Modified On', 'تعديل في', '修改于', 'Modificado el', 'modifiÃ© le', 'изменение', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(94, NULL, 'Mon', 'لي', '周一', 'mi', 'Mon', 'мой', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(95, NULL, 'Monday', 'يوم الاثنين', '星期一', 'lunes', 'lundi', 'понедельник', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(96, NULL, 'Month', 'شهر', '月', 'mes', 'mois', 'месяц', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(97, NULL, 'My Account', 'حسابي', '我的帐户', 'Mi Cuenta', 'mon compte', 'Мой аккаунт', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(98, NULL, 'Name', 'اسم', '名称', 'nombre', 'nom', 'название', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(99, NULL, 'New', 'جديد', '新', 'nuevo', 'nouveau', 'новый', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(100, NULL, 'No', 'ليس', '无', 'No', 'pas', 'не', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(101, NULL, 'No Option', 'لا خيار', '没有选项', 'Sin opciÃ³n', 'aucune option', 'Нет Опция', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(102, NULL, 'not specified', 'غير محدد', '未指定', 'no especificado', 'non spÃ©cifiÃ©', 'не указан', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(103, NULL, 'November', 'نوفمبر', '十一月', 'noviembre', 'novembre', 'ноябрь', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(104, NULL, 'October', 'أكتوبر', '十月', 'octubre', 'octobre', 'октябрь', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(105, NULL, 'Option', 'خيار', '选项', 'opciÃ³n', 'option', 'вариант', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(106, NULL, 'Option Not Found', 'الخيار غير موجود', '期权未找到', 'OpciÃ³n no encontrado', 'Option Introuvable', 'Вариант не найден', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(107, NULL, 'Order', 'النظام', '顺序', 'orden', 'ordre', 'порядок', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(108, NULL, 'Password', 'كلمة السر', '密码', 'contraseÃ±a', 'mot de passe', 'пароль', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(109, NULL, 'Period', 'فترة', '期', 'perÃ­odo', 'pÃ©riode', 'период', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(110, NULL, 'Physics', 'فيزياء', '物理', 'fÃ­sica', 'physique', 'физика', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(111, NULL, 'Postal Code', 'الرمز البريدي', '邮政编码', 'CÃ³digo Postal', 'code postal', 'Почтовый индекс', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(112, NULL, 'Print', 'طباعة', '打印', 'impresiÃ³n', 'Imprimer', 'печать', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(113, NULL, 'Profile Image', 'الصورة الشخصية', '简介的图片', 'Imagen del perfil', 'image de profil', 'Изображение профиля', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(114, NULL, 'Programme', 'برنامج', '计划', 'programa', 'Ø¨Ø±Ù†Ø§Ù…Ø¬', 'программа', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(115, NULL, 'Programmes', 'برامج', '课程', 'Programas', 'Ø¨Ø±Ø§Ù…Ø¬', 'Программы', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(116, NULL, 'Relationship', 'علاقة', '关系', 'relaciÃ³n', 'relations', 'связь', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(117, NULL, 'Relationship Categories', 'العلاقة الفئات', '关系分类', 'RelaciÃ³n CategorÃ­as', 'relation CatÃ©gories', 'Отношения Категории', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(118, NULL, 'Relationship Category', 'العلاقة الفئة', '相关类别', 'RelaciÃ³n CategorÃ­a', 'relation CatÃ©gorie', 'Отношения Категория', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(119, NULL, 'Remark', 'كلام', '备注', 'observaciÃ³n', 'remarque', 'замечание', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(120, NULL, 'Remarks', 'تصريحات', '备注', 'observaciones', 'remarques', 'Замечания', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(121, NULL, 'Remove', 'نزع', '清除', 'eliminar', 'supprimer', 'удалять', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(122, NULL, 'Reorder', 'إعادة ترتيب', '重新排序', 'Reordenar', 'rÃ©organiser', 'Изменение порядка', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(123, NULL, 'Result', 'نتيجة', '结果', 'resultado', 'rÃ©sultat', 'результат', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(124, NULL, 'Results', 'النتائج', '结果', 'Resultados', 'rÃ©sultats', 'Результаты', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(125, NULL, 'Sat', 'السبت', '星期六', 'sÃ¡b', 'Sam', 'суббота', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(126, NULL, 'Saturday', 'السبت', '星期六', 'sÃ¡bado', 'samedi', 'суббота', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(127, NULL, 'Save', 'حفظ', '保存', 'guardar', 'sauver', 'экономить', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(128, NULL, 'Save as PDF', 'حفظ ك PDF', '另存为PDF', 'Guardar como PDF', 'Enregistrer au format PDF', 'Сохранить как PDF', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(129, NULL, 'School Days', 'أيام المدرسة', '上学日', 'DÃ­as de escuela', 'Jours d''Ã©cole', 'Школьные годы', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(130, NULL, 'Search', 'بحث', '搜索', 'bÃºsqueda', 'recherche', 'поиск', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(131, NULL, 'Search Results', 'نتائج البحث', '搜索结果', 'Resultados de la bÃºsqueda', 'RÃ©sultats de la recherche', 'Результаты поиска', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(132, NULL, 'Select', 'اختر', '选择', 'seleccionar', 'sÃ©lectionner', 'выбирать', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(133, NULL, 'Select File', 'حدد ملف', '选择文件', 'Seleccione Archivo', 'SÃ©lectionnez Fichier', 'Выберите Файл', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(134, NULL, 'September', 'سبتمبر', '九月', 'septiembre', 'septembre', 'сентябрь', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(135, NULL, 'Short Form', 'نموذج قصيرة', '短表', 'Short Form', 'Forme Courte', 'Краткая форма', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(136, NULL, 'Start Date', 'بدء التسجيل', '开始日期', 'Fecha de inicio', 'date de dÃ©but', 'Дата начала', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(137, NULL, 'Start Time', 'بدء التوقيت', '开始时间', 'Hora de inicio', 'Heure de dÃ©but', 'время начала', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(138, NULL, 'Status', 'حالة', '状态', 'estado', 'statut', 'статус', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(139, NULL, 'Subject', 'موضوع', '主题', 'sujeto', 'sujet', 'тема', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(140, NULL, 'Subjects', 'المواضيع', '主题', 'sujetos', 'sujets', 'тематика', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(141, NULL, 'Sun', 'شمس', '星期天', 'sol', 'Soleil', 'Солнце', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(142, NULL, 'Sunday', 'الأحد', '星期天', 'domingo', 'dimanche', 'воскресенье', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(143, NULL, 'Teacher', 'معلم', '老师', 'profesor', 'professeur', 'учитель', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(144, NULL, 'Teachers', 'معلمون', '教师', 'profesores', 'enseignants', 'Учителя', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(145, NULL, 'Telephone', 'هاتف', '电话', 'telÃ©fono', 'tÃ©lÃ©phone', 'телефон', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(146, NULL, 'Thur', 'خميس', '周四', 'jue', 'jeu.', 'чт', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(147, NULL, 'Thursday', 'الخميس', '星期四', 'jueves', 'jeudi', 'четверг', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(148, NULL, 'Time', 'وقت', '时间', 'tiempo', 'temps', 'время', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(149, NULL, 'Time Of Behaviour', 'وقت السلوك', '行为的时间', 'Tiempo de Comportamiento', 'Temps de comportement', 'Время поведения', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(150, NULL, 'Timetable', 'جدول المواعيد', '时间表', 'calendario', 'calendrier', 'расписание', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(151, NULL, 'Title', 'لقب', '标题', 'tÃ­tulo', 'titre', 'название', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(152, NULL, 'Today', 'اليوم', '今天', 'hoy', 'aujourd''hui', 'сегодня', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(153, NULL, 'Tue', 'الثلاثاء', '星期二', 'mar', 'Mar', 'вторник', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(154, NULL, 'Tuesday', 'الثلاثاء', '星期二', 'martes', 'mardi', 'вторник', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(155, NULL, 'Type', 'نوع', '类型', 'tipo', 'type', 'тип', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(156, NULL, 'Types', 'أنواع', '类型', 'Tipos', 'types', 'Виды', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(157, NULL, 'Username', 'اسم المستخدم', '用户名', 'Nombre de usuario', 'Nom d''utilisateur', 'Имя пользователя', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(158, NULL, 'Value', 'قيمة', '值', 'valor', 'valeur', 'значение', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(159, NULL, 'View', 'رأي', '视图', 'vista', 'Voir', 'вид', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(160, NULL, 'Wed', 'تزوج', '周三', 'casarse', 'Mer', 'среда', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(161, NULL, 'Wednesday', 'الأربعاء', '星期三', 'miÃ©rcoles', 'mercredi', 'среда', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(162, NULL, 'Year', 'عام', '年', 'aÃ±o', 'annÃ©e', 'год', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(163, NULL, 'Years', 'سنوات', '岁月', 'aÃ±os', 'ans', 'Годы', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(164, NULL, 'Add Event', 'إضافة حدث', '添加事件', 'AÃ±adir Evento', 'Ajouter un Ã©vÃ©nement', 'Добавить событие', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(165, NULL, 'Class Event', 'فئة الحدث', '类事件', 'clase de evento', 'classe d''Ã©vÃ©nements', 'Класс событий', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(166, NULL, 'List of Events', 'قائمة الأحداث', '活动一览', 'Listado de eventos', 'Liste des Ã©vÃ©nements', 'Афиша событий', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(167, NULL, 'School Event', 'المدرسة حدث', '学校活动', 'Evento Escolar', 'L''Ã©vÃ©nement de l''Ã©cole', 'Школа событие', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(168, NULL, 'Event', 'حدث', '事件', 'evento', 'Ã©vÃ©nement', 'событие', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(169, NULL, 'Events', 'أحداث', '活动', 'Eventos', 'Ã©vÃ©nements', 'События', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(170, NULL, 'Add Student', 'إضافة طالب', '新增学生', 'AÃ±adir alumno', 'Ajouter aux Ã©tudiants', 'Добавить студент', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(171, NULL, 'Add Academic Progress', 'إضافة التقدم الأكاديمي', '添加学术进展', 'AÃ±adir Progreso AcadÃ©mico', 'Ajouter progrÃ¨s acadÃ©mique', 'Добавить успеваемости', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(172, NULL, 'Academic Progress', 'تقدم الأكاديمية', '学术进展', 'Progreso AcadÃ©mico', 'Les progrÃ¨s acadÃ©mique', 'академический Прогресс', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(173, NULL, 'All Students', 'جميع الطلاب', '所有学生', 'Todos los Estudiantes', 'tous les Ã©tudiants', 'Все студенты', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(174, NULL, 'Alumni', 'الخريجين', '校友', 'Alumni', 'Anciens', 'Выпускники', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(175, NULL, 'Assessment Result', 'نتائج التقييم', '评估结果', 'EvaluaciÃ³n de resultados', 'RÃ©sultat de l''Ã©valuation', 'Оценка Результат', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(176, NULL, 'Current Student', 'الطلاب الحالي', '当前学生', 'estudiante actual', 'Ã©tudiant actuel', 'Текущий студент', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(177, NULL, 'Day Attendance', 'يوم الحضور', '当天出席', 'Asistencia Day', 'FrÃ©quentation Jour', 'День Посещаемость', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(178, NULL, 'Drop Out', 'ترك', '辍学', 'abandonar', 'tomber', 'выпадать', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(179, NULL, 'Graduate', 'خريج', '毕业生', 'graduado', 'diplÃ´mÃ©', 'выпускник', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(180, NULL, 'Last Login', 'آخر تسجيل دخول', '上次登录', 'Ãºltimo ingreso', 'derniÃ¨re connexion', 'Последний Войти', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(181, NULL, 'Lesson Attendance', 'الحضور الدرس', '出席课', 'Asistencia LecciÃ³n', 'leÃ§on de prÃ©sence', 'Урок Посещаемость', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(182, NULL, 'Student No', 'عدد الطلاب', '学生人数', 'NÃºmero de Estudiantes', 'NumÃ©ro d''Ã©tudiant', 'Студент Количество', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(183, NULL, 'Pass Student', 'يمر الطالب', '通过学生', 'Pase Estudiantil', 'Passez Ã©tudiants', 'Pass Студент', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(184, NULL, 'Progress Date', 'تاريخ التقدم', '进度日期', 'Progreso Fecha', 'date de progrÃ¨s', 'Прогресс Дата', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(185, NULL, 'Result Details', 'تفاصيل نتيجة', '结果详细信息', 'Detalles de Resultados', 'DÃ©tails de rÃ©sultat', 'Результат Подробности', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(186, NULL, 'Student', 'طالب', '学生', 'estudiante', 'Ã©tudiant', 'студент', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(187, NULL, 'Student State', 'الدولة طالب', '学生国家', 'Estatal del Estudiante', 'Etat de l''Ã©tudiant', 'Студент государство', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(188, NULL, 'Student Status', 'الحالة الطالب', '学籍', 'SituaciÃ³n del Estudiante', 'Statut d''Ã©tudiant', 'Студент Статус', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(189, NULL, 'Students', 'الطلاب', '学生', 'estudiantes', 'Ã©tudiants', 'Студенты', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(190, NULL, 'Add Staff', 'إضافة موظفين', '新增员工', 'AÃ±adir Staff', 'Ajouter personnel', 'Добавить Персонал', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(191, NULL, 'All Staff', 'جميع الموظفين', '所有员工', 'Todo el Personal', 'tout le personnel', 'Все Персонал', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(192, NULL, 'Staff Category', 'موظفو الفئة', '工作人员分类', 'CategorÃ­a de personal', 'CatÃ©gorie de personnel', 'Персонал Категория', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(193, NULL, 'Employment', 'توظيف', '雇用', 'empleo', 'emploi', 'занятость', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(194, NULL, 'Non Teaching', 'غير التدريس', '非教学', 'no docente', 'non enseignant', 'Номера Преподавание', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(195, NULL, 'My Profile', 'ملفي الشخصي', '我的个人资料', 'mi perfil', 'mon profil', 'Мой Профайл', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(196, NULL, 'Staff No', 'عدد الموظفين', '员工人数', 'NÃºmero de Personal', 'Nombre de personnel', 'Персонал Количество', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(197, NULL, 'Staff Status', 'الحالة الموظفين', '工作人员状态', 'Personal Estado', 'Statut du personnel', 'Персонал Статус', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(198, NULL, 'Teaching', 'تدريس', '教学', 'enseÃ±anza', 'enseignement', 'учение', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(199, NULL, 'Staff', 'العاملين', '员工', 'personal', 'personnel', 'персонал', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(200, NULL, 'Add Guardian', 'إضافة الجارديان', '加入守护者', 'AÃ±adir GuardiÃ¡n', 'Ajouter Gardien', 'Добавить Гардиан', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(201, NULL, 'Search Guardians', 'البحث الأوصياء', '搜索守护者', 'Buscar Guardianes', 'Recherche Gardiens', 'Поиск Стражи', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(202, NULL, 'Add Class', 'إضافة فئة', '添加类', 'Agregar clase', 'Ajouter une classe', 'Добавить Класс', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(203, NULL, 'Add Class Assignment', 'إضافة فئة التعيين', '添加类分配', 'AÃ±adir AsignaciÃ³n de Clase', 'Ajouter une classe Affectation', 'Добавить Class Назначение', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(204, NULL, 'Add Lesson', 'إضافة الدرس', '新增课', 'AÃ±adir LecciÃ³n', 'Ajouter leÃ§on', 'Добавить Урок', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(205, NULL, 'Add National Assignment', 'إضافة إحالة الوطنية', '新增国家分配', 'AÃ±adir Nacional de AsignaciÃ³n', 'Ajouter Affectation nationale', 'Добавить Национальный Назначение', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(206, NULL, 'Add Timetable', 'إضافة الجدول الزمني', '添加时间表', 'AÃ±adir Horarios', 'Ajouter horaire', 'Добавить расписание', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(207, NULL, 'All Subjects', 'جميع المواد الدراسية', '所有主题', 'Todos los temas', 'tous les sujets', 'Все тематики', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(208, NULL, 'All Teachers', 'جميع المعلمين', '所有教师', 'Todos los maestros', 'tous les enseignants', 'Все Учителя', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(209, NULL, 'Class Assignments', 'تعيينات الفئة', '课堂作业', 'AsignaciÃ³n de Clases', 'Missions de classe', 'Класс Задания', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(210, NULL, 'Attendance Types', 'أنواع الحضور', '考勤类型', 'Tipos de asistencia', 'Types de prÃ©sence', 'Типы посещений', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(211, NULL, 'Class', 'فئة', '类', 'clase', 'classe', 'класс', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(212, NULL, 'Class Daily Attendance', 'فئة الحضور اليومي', '类日常考勤', 'Clase de Asistencia Diaria', 'Classe prÃ©sences quotidiennes', 'Класс ежедневная посещаемость', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(213, NULL, 'Edit Lesson', 'تحرير الدرس', '编辑课', 'Editar LecciÃ³n', 'Modifier la leÃ§on', 'Редактировать Урок', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(214, NULL, 'Edit Timetable', 'تحرير الجدول الزمني', '编辑时间表', 'Editar Horario', 'Modifier l''horaire', 'Редактировать Расписание', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(215, NULL, 'Education Grade', 'التعليم الصف', '教育等级', 'EducaciÃ³n grado', 'e annÃ©e', 'Образование класс', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(216, NULL, 'Education Grades', 'التعليم الصفوف', '教育职系', 'grados de EducaciÃ³n', 'Les qualitÃ©s de l''Ã©ducation', 'Образование сортов', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(217, NULL, 'Education Grade Code', 'التعليم كود العلمية', '教育等级代码', 'CÃ³digo de EducaciÃ³n de Grado', 'Education code grade', 'Образование класс Код', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(218, NULL, 'Education Grades - Subjects', 'درجات التعليم - الموضوعات', '教育级别 - 主题', 'Grados EducaciÃ³n - Temas', 'Ã‰ducation grades - Sujets', 'Образование сортов - Предметы', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(219, NULL, 'Education Grade Subject', 'التعليم الصف الموضوع', '教育一级学科', 'EducaciÃ³n Grado Asunto', 'Ã‰ducation annÃ©e Objet', 'Тема Образование класс', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(220, NULL, 'Education Grade Subjects', 'الموضوعات العلمية التعليم', '教育一级学科', 'Temas EducaciÃ³n Grado', 'Education catÃ©gorie Sujets', 'Субъекты Образование класс', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(221, NULL, 'Education Subject', 'موضوع التعليم', '教育主题', 'EducaciÃ³n Tema', 'Education Sujet', 'Образование Тема', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(222, NULL, 'Education Subjects', 'الموضوعات التعليم', '教育主题', 'Temas EducaciÃ³n', 'Education Sujets', 'Образование Субъекты', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(223, NULL, 'Education Subject Code', 'التعليم موضوع كود', '教育科目代码', 'EducaciÃ³n Asignatura CÃ³digo', 'Education Sujet code', 'Образование Тема код', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(224, NULL, 'Employment Types', 'أنواع العمالة', '就业类型', 'Tipos de Empleo', 'Types d''emploi', 'Виды занятости', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(225, NULL, 'Grade(s)', 'الصف (ق)', '级', 'Grado (s)', 'Grade(s)', 'Класс (ы)', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(226, NULL, 'Lesson', 'درس', '教训', 'lecciÃ³n', 'leÃ§on', 'урок', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(227, NULL, 'Lessons', 'الدروس', '教训', 'Lecciones', 'leÃ§ons', 'Уроки', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(228, NULL, 'Lesson Date', 'الدرس التسجيل', '上课日期', 'LecciÃ³n Fecha', 'leÃ§on date', 'Урок Дата', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(229, NULL, 'Lesson From', 'الدرس من', '教训', 'LecciÃ³n De', 'leÃ§on De', 'Урок из', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(230, NULL, 'Lesson Status', 'الحالة الدرس', '课程状态', 'Estado LecciÃ³n', 'leÃ§on Etat', 'Урок Статус', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(231, NULL, 'Lesson Time', 'وقت الدرس', '上课时间', 'Tiempo LecciÃ³n', 'leÃ§on Temps', 'Урок Время', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(232, NULL, 'Lesson To', 'الدرس ل', '教训', 'LecciÃ³n Para', 'leÃ§on Pour', 'Урок Чтобы', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(233, NULL, 'List of Classes', 'قائمة فئات', '类的列表', 'Lista de clases', 'Liste des classes', 'Список классов', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(234, NULL, 'Programme - Grades', 'برنامج - علامات', '计划 - 级别', 'Programa - Grados', 'Programme - Grades', 'Программа - Оценки', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(235, NULL, 'Room', 'غرفة', '房间', 'habitaciÃ³n', 'chambre', 'номер', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(236, NULL, 'School Year', 'المدرسة السنة', '学年', 'aÃ±o escolar', 'AnnÃ©e scolaire', 'учебный год', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(237, NULL, 'Please select a valid subject', 'الرجاء اختيار موضوع صالحة', '请选择一个有效的主题', 'Por favor, seleccione un tema vÃ¡lido', 'S''il vous plaÃ®t sÃ©lectionner un sujet valide', 'Пожалуйста, введите корректное тему', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(238, NULL, 'Select multiple grades by holding "Shift" key.', 'تحديد درجات متعددة من خلال عقد مفتاح "شيفت"', '通过按住“Shift”键选择多个档次。', 'Seleccione varios grados manteniendo tecla "Shift"', 'SÃ©lectionner plusieurs notes en tenant la touche "Shift"', 'Выберите несколько сортов, удерживая клавишу "Shift"', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(239, NULL, 'List of students contact information', 'قائمة معلومات الاتصال الطلاب', '学生联系信息列表', 'Lista de los estudiantes informaciÃ³n de contacto', 'Liste des Ã©tudiants des informations de contact', 'Список студентов контактную информацию', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(240, NULL, 'List of teachers contact information', 'قائمة المعلمين معلومات الاتصال', '教师联系信息列表', 'Lista de profesores informaciÃ³n de contacto', 'Liste des enseignants coordonnÃ©es', 'Список учителей контактная информация', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(241, NULL, 'Total Seats', 'مجموع مقاعد', '总座位', 'total de Asientos', 'Nombre de siÃ¨ges', 'Всего мест', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(242, NULL, 'Weighting', 'الترجيح', '加权', 'PonderaciÃ³n', 'pondÃ©ration', 'взвешивание', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(243, NULL, 'Administration', 'إدارة', '管理', 'administraciÃ³n', 'administration', 'администрация', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(244, NULL, 'Add User', 'إضافة مستخدم', '添加用户', 'AÃ±adir usuario', 'Ajouter un utilisateur', 'Добавить пользователя', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(245, NULL, 'Assessment', 'تقدير', '评定', 'valoraciÃ³n', 'Ã©valuation', 'оценка', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(246, NULL, 'Assessment Items', 'تقييم الأصناف', '评估项目', 'Ejercicios de evaluaciÃ³n', 'Ã©valuation Articles', 'Оценка товары', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(247, NULL, 'Education Programme', 'برنامج التعليم', '教育计划', 'Programa de EducaciÃ³n', 'Programme de l''Ã©ducation', 'Образование Программа', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(248, NULL, 'Education Programmes', 'برامج التعليم', '教育活动', 'Programas de EducaciÃ³n', 'Programmes d''Ã©ducation', 'Программы Образование', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(249, NULL, 'Education Structure', 'هيكل التعليم', '教育结构', 'Estructura de la EducaciÃ³n', 'Ã©ducation Structure', 'Структура образования', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(250, NULL, 'Field Options', 'خيارات الحقل', '域选项', 'Opciones de campo', 'Options terrain', 'Полевые Опции', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(251, NULL, 'List of Users', 'قائمة المستخدمين', '用户列表', 'Lista de Usuarios', 'Liste des utilisateurs', 'Список пользователей', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(252, NULL, 'National Assessment', 'تقييم وطني', '国家评估', 'EvaluaciÃ³n Nacional', 'Ã©valuation nationale', 'Национальная оценка', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(253, NULL, 'National Assessments', 'التقييمات الوطنية', '国家评估', 'evaluaciones Nacionales', 'Ã©valuations nationales', 'национальных оценок', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(254, NULL, 'Result Types', 'أنواع نتيجة', '结果类型', 'Tipos de resultado', 'Types de rÃ©sultats', 'Типы результата', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(255, NULL, 'System Configuration', 'نظام التكوين', '系统配置', 'ConfiguraciÃ³n del sistema', 'Configuration du systÃ¨me', 'Конфигурация системы', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(256, NULL, 'User Management', 'إدارة المستخدم', '用户管理', 'GestiÃ³n de usuarios', 'Gestion des utilisateurs', 'Управление пользователями', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(257, NULL, 'No Data', 'لا توجد بيانات', '无资料', 'No Data', 'aucune donnÃ©e', 'Нет данных', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(258, NULL, 'An unexpected error has been encountered. Please contact the administrator for assistance.', 'تم مصادفة خطأ غير متوقع. الرجاء الاتصال بمسؤول للحصول على المساعدة.', '一个意外的错误已经遇到。请联系管理员以获得帮助。', 'Se ha encontrado un error inesperado. Por favor, pÃ³ngase en contacto con el administrador para obtener ayuda.', 'Une erreur inattendue s''est produite. S''il vous plaÃ®t contactez l''administrateur de l''aide.', 'Непредвиденная ошибка была выявлена​​. Пожалуйста, обратитесь к администратору за помощью.', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(259, NULL, 'Record has been added successfully.', 'تمت إضافة السجل بنجاح', '记录已被成功添加', 'El registro ha sido agregado con Ã©xito', 'Enregistrement a Ã©tÃ© ajoutÃ© avec succÃ¨s', 'Запись был успешно добавлен', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(260, NULL, 'Record is not added due to errors encountered.', 'لا يتم إضافة السجل بسبب مصادفة أخطاء', '记录不会因遇到错误添加', 'El registro no se aÃ±ade debido a errores encontrados', 'L''enregistrement n''est pas ajoutÃ© en raison d''erreurs rencontrÃ©es', 'Запись не добавляется из-за ошибок, возникающих', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(261, NULL, 'Please add new record into it.', 'يرجى إضافة رقم قياسي جديد في ذلك', '请添加新的记录到它', 'Por favor, aÃ±ada nuevo rÃ©cord en Ã©l', 'S''il vous plaÃ®t ajoutez nouveau record en elle', 'Пожалуйста, добавьте новую запись в него', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(262, NULL, 'Record has been updated successfully.', 'تم تحديث السجل بنجاح', '记录已经成功更新', 'El registro ha sido actualizado correctamente', 'Enregistrement a Ã©tÃ© mis Ã  jour', 'Запись успешно обновлена', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(263, NULL, 'Record is not updated due to errors encountered.', 'لم يتم تحديث السجل بسبب مصادفة أخطاء', '记录不会因遇到错误更新', 'Registro no se actualiza debido a errores encontrados', 'L''enregistrement n''est pas mis Ã  jour en raison d''erreurs rencontrÃ©es', 'Запись не обновляется из-за ошибок, возникающих', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(264, NULL, 'The record does not exist.', 'عدم وجود سجل', '该记录不存在', 'No existe el registro', 'Le dossier n''existe pas', 'Запись не существует', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(265, NULL, 'There are no records.', 'لا توجد سجلات', '没有记录', 'No hay registros', 'Il n''y a pas de dossiers', 'Там нет записей', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37');
INSERT INTO `translations` (`id`, `code`, `eng`, `ara`, `chi`, `spa`, `fre`, `rus`, `modified_user_id`, `modified`, `created_user_id`, `created`) VALUES
(266, NULL, 'Record has been deleted successfully.', 'تم حذف السجل بنجاح', '记录已被成功删除', 'El registro ha sido borrado con Ã©xito', 'Enregistrement a Ã©tÃ© supprimÃ©', 'Запись успешно удален', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(267, NULL, 'Record is not deleted due to errors encountered.', 'لا يتم حذف السجل بسبب مصادفة أخطاء', '记录不会因遇到错误删除', 'El registro no se elimina debido a errores encontrados', 'L''enregistrement n''est pas supprimÃ© en raison d''erreurs rencontrÃ©es', 'Запись не удаляется из-за ошибок, возникающих', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(268, NULL, 'Your search returns no result.', 'البحث إرجاع أية نتيجة', '没有搜索到任何结果', 'Su bÃºsqueda no devuelve ningÃºn resultado', 'Votre recherche ne retourne aucun rÃ©sultat', 'Ваш поиск не дал никаких результатов', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(269, NULL, 'Your session has timed out. Please login again.', 'انتهت مهلة جلسة العمل الخاصة بك خارج. يرجى تسجيل الدخول مرة أخرى.', '您的会话已超时。请重新登录。', 'Su sesiÃ³n ha caducado. Por favor, acceda de nuevo.', 'Votre session a expirÃ©. S''il vous plaÃ®t vous connecter Ã  nouveau.', 'Ваш сеанс закончился. Пожалуйста, войдите снова.', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(270, NULL, 'You have entered an invalid username or password.', 'لقد ادخلت اسم مستخدم أو كلمة المرور غير صالحة', '您输入了一个无效的用户名或密码', 'Ha introducido un nombre de usuario o contraseÃ±a no vÃ¡lidos', 'Vous avez entrÃ© un nom d''utilisateur ou mot de passe incorrect', 'Вы ввели неверный логин или пароль', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(271, NULL, 'You are not an authorized user.', 'لم تكن أذن المستخدم', '您不是授权用户', 'Usted no es un usuario autorizado', 'Vous n''Ãªtes pas un utilisateur autorisÃ©', 'Вы не авторизованный пользователь', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(272, NULL, 'No result returned from the search.', 'إرجاع أية نتيجة من البحث', '从搜索没有结果返回', 'NingÃºn resultado devuelto por la bÃºsqueda', 'Aucun rÃ©sultat retournÃ© par la recherche', 'Отсутствие результата возвращается из поиска', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(273, NULL, 'The file has been uploaded.', 'تم تحميل الملف', '该文件已上传', 'El archivo ha sido cargado', 'Le fichier a Ã©tÃ© tÃ©lÃ©chargÃ©', 'Файл был загружен', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(274, NULL, 'The files have been uploaded.', 'تم تحميل الملفات', '该文件已被上传', 'Los archivos se hayan cargado', 'Les fichiers ont Ã©tÃ© tÃ©lÃ©chargÃ©s', 'Файлы были загружены', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(275, NULL, 'Please ensure that the file is smaller than the file size limit.', 'الرجاء التأكد من أن الملف هو أصغر من الحد الأقصى لحجم الملف', '请确保该文件比文件大小限制较小', 'Por favor, asegÃºrese de que el archivo es menor que el lÃ­mite de tamaÃ±o de archivo', 'S''il vous plaÃ®t assurez-vous que le fichier est plus petit que la limite de taille de fichier', 'Пожалуйста, убедитесь, что файл меньше, чем ограничение на размер файла', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(276, NULL, 'No file was uploaded.', 'تم تحميل أي ملف', '没有文件被上传', 'NingÃºn archivo fue subido', 'Pas de fichier a Ã©tÃ© tÃ©lÃ©chargÃ©', 'Файл не был загружен', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(277, NULL, 'Invalid file format.', 'تنسيق ملف غير صالح', '无效的文件格式', 'Formato de archivo no vÃ¡lido', 'Format de fichier incorrect', 'Неправильный формат файла', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(278, NULL, 'File is not uploaded due to errors encountered.', 'لا يتم تحميل الملف بسبب مصادفة أخطاء', '文件不因遇到错误上传', 'El archivo no se carga debido a errores encontrados', 'Le fichier n''est pas tÃ©lÃ©chargÃ© en raison d''erreurs rencontrÃ©es', 'Файл не загружается из-за ошибок, возникающих', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(279, NULL, 'Please enter a valid name', 'الرجاء إدخال اسم صالح', '请输入一个有效的名称', 'Por favor ingrese un nombre vÃ¡lido', 'S''il vous plaÃ®t entrer un nom valide', 'Пожалуйста, укажите Ваше имя', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(280, NULL, 'Please select a year', 'الرجاء تحديد السنة', '请选择年份', 'Por favor, seleccione un aÃ±o', 'S''il vous plaÃ®t sÃ©lectionner un an', 'Пожалуйста, выберите год', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(281, NULL, 'Please select a grade', 'يرجى تحديد الصف', '请选择一个档次', 'Por favor seleccione una calificaciÃ³n', 'S''il vous plaÃ®t sÃ©lectionner un grade', 'Пожалуйста, выберите класс', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(282, NULL, 'Please enter a valid option', 'الرجاء إدخال خيار صالح', '请输入一个有效的选项', 'Por favor introduzca una opciÃ³n vÃ¡lida', 'Veuillez entrer une option valable', 'Пожалуйста, введите действительный вариант', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(283, NULL, 'Please enter a valid minimum value', 'الرجاء إدخال قيمة الحد الأدنى صالحة', '请输入一个有效的最小值', 'Por favor, introduzca un valor mÃ­nimo vÃ¡lido', 'S''il vous plaÃ®t entrer une valeur minimale valide', 'Пожалуйста, введите допустимое значение минимальной', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(284, NULL, 'Please enter a valid maximum value', 'يرجى إدخال قيمة صالحة الأقصى', '请输入有效的最大值', 'Por favor, introduzca un valor mÃ¡ximo vÃ¡lido', 'S''il vous plaÃ®t entrer une valeur maximale valide', 'Пожалуйста, введите действительный максимальное значение', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(285, NULL, 'Please select a valid subject name', 'يرجى تحديد اسم الموضوع صالحة', '请选择一个有效的主题名称', 'Por favor seleccione un nombre de sujeto vÃ¡lido', 'S''il vous plaÃ®t sÃ©lectionner un nom d''objet valide', 'Пожалуйста, введите корректное имя субъекта', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(286, NULL, 'Please select a valid class name', 'الرجاء اختيار اسم فئة صالحة', '请选择一个有效的类名', 'Por favor seleccione un nombre de clase vÃ¡lido', 'S''il vous plaÃ®t sÃ©lectionner un nom de classe valide', 'Пожалуйста, введите корректное имя класса', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(287, NULL, 'Please enter a valid class name', 'الرجاء إدخال اسم فئة صالحة', '请输入有效的类名', 'Por favor, introduzca un nombre de clase vÃ¡lido', 'S''il vous plaÃ®t entrer un nom de classe valide', 'Пожалуйста, укажите Ваше имя класса', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(288, NULL, 'Please select a valid grade', 'يرجى تحديد الصف صالحة', '请选择一个有效的等级', 'Por favor seleccione una calificaciÃ³n vÃ¡lida', 'S''il vous plaÃ®t sÃ©lectionner une catÃ©gorie valide', 'Пожалуйста, введите корректное класс', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(289, NULL, 'Start Time cannot be later than End Time', 'بدء التوقيت لا يمكن أن يكون في وقت لاحق من نهاية الوقت', '比结束时间开始时间不得晚', 'Hora de inicio no puede ser posterior a Tiempo de tÃ©rmino', 'Heure de dÃ©part ne peut pas Ãªtre postÃ©rieure Ã  l''heure de fin', 'Время начала не может быть позднее, чем End Time', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(290, NULL, 'Please select a valid location', 'الرجاء اختيار موقع صالح', '请选择一个有效的位置', 'Por favor, seleccione una ubicaciÃ³n vÃ¡lida', 'S''il vous plaÃ®t sÃ©lectionnez un emplacement valide', 'Пожалуйста, введите корректное расположение', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(291, NULL, 'Please select a valid teacher', 'الرجاء اختيار المعلم صالحة', '请选择一个有效的老师', 'Por favor seleccione un profesor vÃ¡lido', 'S''il vous plaÃ®t sÃ©lectionner un enseignant valide', 'Пожалуйста, введите корректное учителя', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(292, NULL, 'Please select a valid status', 'يرجى تحديد حالة صالحة', '请选择一个有效的状态', 'Por favor seleccione un estado vÃ¡lido', 'S''il vous plaÃ®t sÃ©lectionner un Ã©tat â€‹â€‹valide', 'Пожалуйста, введите корректное статус', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(293, NULL, 'Please enter a valid Contact No', 'يرجى إدخال رقم الهاتف صالح', '请输入一个有效的联络电话', 'Por favor, introduzca un nÃºmero de contacto vÃ¡lida', 'S''il vous plaÃ®t entrer un numÃ©ro valide de contact', 'Пожалуйста, введите действительный контактный телефон', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(294, NULL, 'Please enter a valid code', 'يرجى إدخال رمز صالح', '请输入有效的代码', 'Por favor, introduzca un cÃ³digo vÃ¡lido', 'S''il vous plaÃ®t entrer un code valide', 'Пожалуйста, введите действительный код', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(295, NULL, 'Please select a programme', 'يرجى تحديد برنامج', '请选择一个项目', 'Por favor, seleccione un programa', 'S''il vous plaÃ®t sÃ©lectionner un programme', 'Пожалуйста, выберите программу', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(296, NULL, 'Please enter a valid duration', 'يرجى إدخال مدة صالحة', '请输入一个有效的持续时间', 'Por favor escriba una duraciÃ³n vÃ¡lida', 'S''il vous plaÃ®t entrer une durÃ©e de validitÃ©', 'Пожалуйста, введите действительный продолжительность', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(297, NULL, 'Please enter a valid email', 'الرجاء إدخال بريد إلكتروني صحيح', '请输入一个有效的电子邮件', 'Por favor, introduzca un email vÃ¡lido', 'S''il vous plaÃ®t entrer une adresse email valide', 'Пожалуйста, введите действительный адрес электронной почты', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(298, NULL, 'Please enter a valid start date', 'يرجى إدخال تاريخ بداية صالحة', '一个有效的起始日期请输入', 'Por favor, introduzca una fecha de inicio vÃ¡lida', 'S''il vous plaÃ®t entrer une date de dÃ©but de validitÃ©', 'Пожалуйста, введите действительную дату начала', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(299, NULL, 'Please enter a valid start time', 'يرجى إدخال وقت البدء صالحة', '请输入一个有效的起始时间', 'Por favor, introduzca una hora de inicio vÃ¡lida', 'S''il vous plaÃ®t entrer une heure de dÃ©but valide', 'Пожалуйста, введите действительный время начала', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(300, NULL, 'Please enter a valid end date', 'يرجى إدخال تاريخ نهاية صالحة', '一个有效的结束日期请输入', 'Por favor, introduzca una fecha de finalizaciÃ³n vÃ¡lida', 'S''il vous plaÃ®t entrer une date de fin de validitÃ©', 'Пожалуйста, введите действительный Конечная дата', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(301, NULL, 'Please enter a valid end time', 'يرجى إدخال نهاية الوقت صالحة', '请输入一个有效的结束时间', 'Por favor, introduzca una hora de finalizaciÃ³n vÃ¡lida', 'S''il vous plaÃ®t entrer un temps de fin de validitÃ©', 'Пожалуйста, введите действительный время окончания', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(302, NULL, 'The end date/time cannot be earlier than the start date', 'الوقت / التاريخ نهاية لا يمكن أن يكون أقدم من تاريخ بدء', '结束日期/时间不能早于开始日期', 'La fecha / hora de finalizaciÃ³n no puede ser anterior a la fecha de inicio', 'La date / heure de fin ne peut pas Ãªtre antÃ©rieure Ã  la date de dÃ©but', 'Даты / времени окончания не может быть установлена ​​ранее даты начала', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(303, NULL, 'Please enter a valid address', 'يرجى إدخال عنوان صالح', '请输入一个有效的地址', 'Por favor, introduce una direcciÃ³n vÃ¡lida', 'S''il vous plaÃ®t entrer une adresse valide', 'Пожалуйста, введите действительный адрес', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(304, NULL, 'Please enter a valid date opened', 'يرجى إدخال تاريخ صالح فتح', '开了一个有效的日期请输入', 'Por favor, introduzca una fecha vÃ¡lida abierto', 'S''il vous plaÃ®t entrer une date valide ouvert', 'Пожалуйста, введите действительный Дата открытия', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(305, NULL, 'Please enter a valid postal code', 'يرجى إدخال الرمز البريدي صالحة', '请输入有效的邮政编码', 'Introduce un cÃ³digo postal vÃ¡lido', 'S''il vous plaÃ®t entrer un code postal valide', 'Пожалуйста, введите действительный почтовый индекс', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(306, NULL, 'Date closed must be greater than date opened', 'يجب أن يكون تاريخ مغلق أكبر من تاريخ فتح', '关闭日期必须大于日开幕', 'Fecha de cierre debe ser mayor que la fecha se abriÃ³', 'Date de fermeture doit Ãªtre supÃ©rieure Ã  la date ouvert', 'Дата окончания должна быть больше Дата открытия', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(307, NULL, 'Please enter a valid year', 'يرجى إدخال السنة صالحة', '请输入有效的一年', 'Por favor, ingrese un aÃ±o vÃ¡lido', 'S''il vous plaÃ®t entrer une annÃ©e valide', 'Пожалуйста, введите действительный год', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(308, NULL, 'End date must be greater than start date', 'يجب أن يكون تاريخ نهاية أكبر من تاريخ بدء', '结束日期必须大于开始日期', 'Fecha de finalizaciÃ³n debe ser mayor que la fecha de inicio', 'Date de fin doit Ãªtre supÃ©rieure Ã  la date de dÃ©but', 'Дата окончания должна быть больше даты начала', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(309, NULL, 'Please enter a valid school day', 'يرجى إدخال اليوم الدراسي صالحة', '请输入有效的上课日', 'Por favor ingrese el dÃ­a escolar vÃ¡lida', 'S''il vous plaÃ®t entrer un jour d''Ã©cole valide', 'Пожалуйста, введите действительный дневная школа', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(310, NULL, 'Please select a valid school year', 'الرجاء تحديد السنة الدراسية سارية المفعول', '请选择一个有效的学年', 'Por favor, seleccione un aÃ±o escolar vÃ¡lida', 'S''il vous plaÃ®t sÃ©lectionner une annÃ©e scolaire valide', 'Пожалуйста, выберите действительный учебный год', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(311, NULL, 'Please enter a numeric value for total seats', 'الرجاء إدخال قيمة رقمية لمجموع المقاعد', '请输入一个数值总席位', 'Por favor, introduzca un valor numÃ©rico para el total de escaÃ±os', 'S''il vous plaÃ®t entrer une valeur numÃ©rique pour nombre total de siÃ¨ges', 'Пожалуйста, введите числовое значение для общего числа мест', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(312, NULL, 'Please enter a numeric value for available seats', 'الرجاء إدخال قيمة رقمية لالمقاعد المتاحة', '请输入一个数值可用座位', 'Por favor, introduzca un valor numÃ©rico para asientos disponibles', 'S''il vous plaÃ®t entrer une valeur numÃ©rique pour places libres', 'Пожалуйста, введите числовое значение для доступных мест', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(313, NULL, 'Please enter your username', 'الرجاء إدخال اسم المستخدم', '请输入您的用户名', 'Por favor, ingrese su nombre de usuario', 'S''il vous plaÃ®t, entrez votre nom d''utilisateur', 'Пожалуйста, введите свое имя пользователя', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(314, NULL, 'This username is already in use', 'هذا المستخدم هو بالفعل في استخدامها', '此用户名已被使用', 'Este nombre de usuario ya estÃ¡ en uso', 'Ce nom d''utilisateur est dÃ©jÃ  utilisÃ©', 'Это имя пользователя уже используется', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(315, NULL, 'Please enter your current password', 'الرجاء إدخال كلمة المرور الحالية', '请输入您的当前密码', 'Introduzca su contraseÃ±a actual', 'S''il vous plaÃ®t, entrez votre mot de passe actuel', 'Пожалуйста, введите ваш текущий пароль', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(316, NULL, 'Your current password is incorrect', 'كلمة المرور الحالية غير صحيحة', '您当前的密码不正确', 'Su contraseÃ±a actual es incorrecta', 'Votre mot de passe est incorrect', 'Ваше текущее пароль неверен', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(317, NULL, 'Password must be at least 6 characters', 'يجب أن تكون كلمة السر لا يقل عن 6 أحرف', '密码必须至少6个字符', 'La contraseÃ±a debe tener como mÃ­nimo 6 caracteres', 'Mot de passe doit Ãªtre d''au moins 6 caractÃ¨res', 'Пароль должен содержать не менее 6 символов', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(318, NULL, 'Please enter your new password', 'الرجاء إدخال كلمة المرور الجديدة', '请输入您的新密码', 'Introduzca su nueva contraseÃ±a', 'S''il vous plaÃ®t, entrez votre nouveau mot de passe', 'Пожалуйста, введите Ваш новый пароль', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(319, NULL, 'Please confirm your new password', 'الرجاء التأكد من كلمة المرور الجديدة', '请确认您的新密码', 'Por favor confirme su nueva contraseÃ±a', 'S''il vous plaÃ®t confirmer votre nouveau mot de passe', 'Пожалуйста, подтвердите свой новый пароль', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(320, NULL, 'Both passwords do not match', 'كلا لا تتطابق كلمات المرور', '这两个密码不匹配', 'Ambas contraseÃ±as no coinciden', 'Les deux mots de passe ne correspondent pas', 'Оба пароли не совпадают', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(321, NULL, 'Please enter a valid first name', 'الرجاء إدخال الاسم الأول صالحة', '请输入有效的名字', 'Por favor ingrese un nombre vÃ¡lido', 'S''il vous plaÃ®t entrer un prÃ©nom valide', 'Пожалуйста, введите действительный имя', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(322, NULL, 'Please enter a valid last name', 'الرجاء إدخال اسم صالح مشاركة', '请输入有效的姓氏', 'Por favor, introduzca un apellido vÃ¡lido', 'S''il vous plaÃ®t entrer un nom valide', 'Пожалуйста, введите действительный фамилию', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(323, NULL, 'Please enter a valid staff no', 'يرجى إدخال رقم صالح الموظفين', '请输入一个有效的员工人数', 'Por favor, introduzca un nÃºmero de personal vÃ¡lida', 'S''il vous plaÃ®t entrer un certain nombre de personnel valide', 'Пожалуйста, введите действительный номер персонала', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(324, NULL, 'Please enter a start date', 'يرجى إدخال تاريخ بدء', '开始日期请输入', 'Por favor, introduzca una fecha de inicio', 'S''il vous plaÃ®t entrer une date de dÃ©but', 'Пожалуйста, введите дату начала', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(325, NULL, 'Please choose a category', 'يرجى اختيار الفئة', '请选择一个类别', 'Elija una categorÃ­a', 'S''il vous plaÃ®t choisir une catÃ©gorie', 'Пожалуйста, выберите категорию', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(326, NULL, 'Please choose a status', 'يرجى اختيار الوضع', '请选择一个状态', 'Por favor, elija un estado', 'S''il vous plaÃ®t choisir un statut', 'Пожалуйста, выберите статус', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(328, NULL, 'Please enter a valid short form', 'الرجاء إدخال شكل قصيرة صالحة', '请输入一个有效的短形式', 'Por favor, introduzca un breve formulario vÃ¡lido', 'S''il vous plaÃ®t entrer un court formulaire valide', 'Пожалуйста, введите действительный короткую форму', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(329, NULL, 'Please enter a valid title', 'يرجى إدخال عنوان صالحة', '请输入一个有效的标题', 'Por favor, introduzca un tÃ­tulo vÃ¡lido', 'S''il vous plaÃ®t entrez un titre valide', 'Пожалуйста, введите действительное название', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(330, NULL, 'Please enter a valid date', 'يرجى إدخال تاريخ صالح', '有效日期请输入', 'Por favor, introduzca una fecha vÃ¡lida', 'S''il vous plaÃ®t entrer une date valide', 'Пожалуйста, введите правильную дату', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(331, NULL, 'Please enter a valid description', 'الرجاء إدخال وصف صالح', '请输入一个有效的描述', 'Por favor, introduzca una descripciÃ³n vÃ¡lida', 'S''il vous plaÃ®t entrer une description valide', 'Пожалуйста, введите действительный описание', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(332, NULL, 'Please enter a valid action', 'يرجى إدخال العمل صالحة', '请输入有效的行动', 'Por favor ingrese una acciÃ³n vÃ¡lida', 'S''il vous plaÃ®t entrer une action valide', 'Пожалуйста, введите действительный действия', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(333, NULL, 'Please enter an employment date', 'يرجى إدخال تاريخ العمل', '雇佣日期请输入', 'Por favor, introduzca una fecha de empleo', 'S''il vous plaÃ®t entrer une date d''emploi', 'Пожалуйста, введите дату занятости', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(334, NULL, 'Please enter a valid timetable name', 'الرجاء إدخال اسم جدول زمني صالحة', '请输入有效的时间表名称', 'Por favor, introduzca un nombre de calendario vÃ¡lido', 'S''il vous plaÃ®t entrer un nom de calendrier valide', 'Пожалуйста, укажите Ваше имя расписание', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(335, NULL, 'Start date cannot be later than end date', 'تاريخ بدء لا يمكن أن يكون في وقت لاحق من تاريخ انتهاء', '开始日期不能晚于结束日期', 'La fecha de inicio no puede ser posterior a la fecha de finalizaciÃ³n', 'Date de dÃ©but ne peut pas Ãªtre postÃ©rieure Ã  la date de fin', 'Дата начала не может быть позднее даты конца', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(336, NULL, 'Modified', 'تعديل', '改性', 'Modificado', 'modifiÃ©', 'модифицированный', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(337, NULL, 'Created', 'خلق', '创建', 'creado', 'Ã©tabli', 'созданный', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(338, NULL, 'Staff Status Id', 'الحالة رقم الموظفين', '工作人员身份标识', 'IdentificaciÃ³n Personal Estado', 'Statut personnel Identifiant', 'Идентификация Персонал Статус', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(339, NULL, 'Staff Category Id', 'موظفو الفئة رقمموظفو الفئة', '工作人员分类标识', 'Personal de IdentificaciÃ³n CategorÃ­a', 'Personnel de catÃ©gorie Identifiant', 'Персонал Маркировка категории', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(340, NULL, 'Student Status Id', 'الحالة طالب معرف', '学生身份标识', 'IdentificaciÃ³n del Estatuto Estudiantil', 'Statut d''Ã©tudiant Identifiant', 'Идентичность Студент Статус', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(341, NULL, 'Default Value', 'القيمة الافتراضية', '默认值', 'valor por defecto', 'valeur par dÃ©faut', 'значение по умолчанию', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(342, NULL, 'Label', 'ملصق', '标签', 'etiqueta', 'Ã©tiquette', 'этикетка', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(343, NULL, 'Desc', 'وصف', '描述', 'descripciÃ³n', 'description', 'описание', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(344, NULL, 'Language', 'لغة', '语', 'idioma', 'langue', 'язык', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(345, NULL, 'Name Display Format', 'اسم عرض الصيغة', '名称显示格式', 'Nombre Formato de pantalla', 'Nom Format d''affichage', 'Имя формата отображения', 1, '2014-08-12 07:40:36', 1, '2014-08-08 11:26:37'),
(346, 'vi', 'Vietnamese', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2014-10-27 11:30:26'),
(347, 'vn', 'Vietnamese', 'Vietnamese', 'Vietnamese', 'Vietnamese', 'Vietnamese', 'Vietnamese', NULL, NULL, 1, '2014-10-27 11:32:41');

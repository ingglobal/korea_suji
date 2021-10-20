-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- 생성 시간: 20-04-08 03:12
-- 서버 버전: 10.1.44-MariaDB
-- PHP 버전: 7.0.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `ingsystem_www`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `g5_1_data`
--

CREATE TABLE `g5_1_data` (
  `dta_idx` bigint(20) NOT NULL,
  `com_idx` bigint(20) NOT NULL DEFAULT '0' COMMENT '업체번호',
  `imp_idx` bigint(20) NOT NULL DEFAULT '0' COMMENT 'iMP번호',
  `mms_idx` int(11) NOT NULL COMMENT '설비코드',
  `shf_idx` int(11) NOT NULL,
  `cod_idx` bigint(20) NOT NULL DEFAULT '0' COMMENT '코드번호',
  `dta_shf_no` int(11) NOT NULL COMMENT '교대번호',
  `dta_shf_max` int(11) NOT NULL COMMENT '총교대수',
  `dta_code` varchar(50) NOT NULL,
  `dta_group` varchar(50) DEFAULT '' COMMENT '에러,생산,예시',
  `dta_type` varchar(50) DEFAULT '' COMMENT '전류,전압등',
  `dta_no` int(11) NOT NULL DEFAULT '0' COMMENT '측정번호',
  `dta_name` varchar(60) NOT NULL COMMENT 'ex.모터',
  `dta_dt` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '일시',
  `dta_value` double DEFAULT '0' COMMENT '정수,음수,실수',
  `dta_unit` varchar(20) NOT NULL COMMENT '단위',
  `dta_message` varchar(20) DEFAULT NULL,
  `dta_status` varchar(20) DEFAULT 'pending' COMMENT '상태',
  `dta_reg_dt` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '등록일시',
  `dta_update_dt` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '수정일시'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `g5_1_data`
--

INSERT INTO `g5_1_data` (`dta_idx`, `com_idx`, `imp_idx`, `mms_idx`, `shf_idx`, `cod_idx`, `dta_shf_no`, `dta_shf_max`, `dta_code`, `dta_group`, `dta_type`, `dta_no`, `dta_name`, `dta_dt`, `dta_value`, `dta_unit`, `dta_message`, `dta_status`, `dta_reg_dt`, `dta_update_dt`) VALUES
(1, 2, 1, 0, 0, 4, 0, 0, '5092', 'pre', '1', 2, '모터', '2020-01-06 00:00:00', 45, '°C', '메시지 1578236400', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(2, 9, 1, 0, 0, 6, 0, 0, '2496', 'pre', '7', 1, '모터', '2020-01-06 00:00:00', 56, '%', '메시지 1578236400', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(3, 6, 1, 0, 0, 11, 0, 0, '7608', 'err', '2', 0, '실린더', '2020-01-06 00:00:04', 23, '%', '메시지 1578236400', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(4, 10, 1, 0, 0, 2, 0, 0, '7418', 'product', '4', 0, '실린더', '2020-01-06 00:00:07', 67, 'V', '메시지 1578236400', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(5, 9, 1, 0, 0, 7, 0, 0, '9817', 'pre', '8', 0, '서보모터', '2020-01-06 00:00:03', 89, 'psi', '메시지 1578236400', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(6, 2, 1, 0, 0, 5, 0, 0, '2802', 'err', '9', 0, '모터', '2020-01-06 00:00:09', 2222, 'rpm', '메시지 1578236400', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(7, 7, 1, 0, 0, 5, 0, 0, '4036', 'product', '1', 0, '실린더', '2020-01-06 00:00:10', 30, '°C', '메시지 1578236410', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(8, 1, 1, 0, 0, 9, 0, 0, '6439', 'product', '7', 0, '서보모터', '2020-01-06 00:00:10', 90, '%', '메시지 1578236410', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(9, 2, 1, 0, 0, 3, 0, 0, '1451', 'pre', '2', 0, '프레스', '2020-01-06 00:00:11', 167, '%', '메시지 1578236410', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(10, 3, 1, 0, 0, 12, 0, 0, '5653', 'pre', '3', 0, '실린더', '2020-01-06 00:00:12', 25, 'Am', '메시지 1578236410', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(11, 3, 1, 0, 0, 8, 0, 0, '7246', 'err', '6', 1, '모터', '2020-01-06 00:00:12', 34, 'dB', '메시지 1578236410', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(12, 10, 1, 0, 0, 11, 0, 0, '8827', 'err', '9', 0, '모터', '2020-01-06 00:00:16', 1450, 'rpm', '메시지 1578236410', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(13, 1, 1, 0, 0, 7, 0, 0, '3544', 'err', '1', 2, '모터', '2020-01-06 00:00:20', 200, '°C', '메시지 1578236420', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(14, 4, 1, 0, 0, 5, 0, 0, '9704', 'err', '7', 0, '모터', '2020-01-06 00:00:20', 0, '%', '메시지 1578236420', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(15, 1, 1, 0, 0, 12, 0, 0, '5561', 'err', '2', 0, '모터', '2020-01-06 00:00:25', 55, '%', '메시지 1578236420', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(16, 8, 1, 0, 0, 6, 0, 0, '5710', 'pre', '3', 0, '프레스', '2020-01-06 00:00:29', 344, 'Am', '메시지 1578236420', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(17, 10, 1, 0, 0, 5, 0, 0, '1265', 'pre', '8', 0, '모터', '2020-01-06 00:00:27', 65, 'psi', '메시지 1578236420', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(18, 2, 1, 0, 0, 11, 0, 0, '2674', 'err', '9', 0, '모터', '2020-01-06 00:00:23', 356, 'rpm', '메시지 1578236420', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(19, 3, 1, 0, 0, 10, 0, 0, '1991', 'pre', '1', 0, '서보모터', '2020-01-06 00:00:30', 20, '°C', '메시지 1578236430', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(20, 2, 1, 0, 0, 9, 0, 0, '539', 'err', '7', 0, '프레스', '2020-01-06 00:00:30', 57, '%', '메시지 1578236430', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(21, 3, 1, 0, 0, 8, 0, 0, '5501', 'err', '4', 4, '프레스', '2020-01-06 00:00:35', 25, 'V', '메시지 1578236430', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(22, 1, 1, 0, 0, 5, 0, 0, '7020', 'err', '5', 3, '프레스', '2020-01-06 00:00:31', 345, 'Hz', '메시지 1578236430', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(23, 6, 1, 0, 0, 3, 0, 0, '1215', 'err', '6', 0, '모터', '2020-01-06 00:00:35', 45, 'dB', '메시지 1578236430', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(24, 6, 1, 0, 0, 10, 0, 0, '3195', 'err', '8', 0, '프레스', '2020-01-06 00:00:36', 89, 'psi', '메시지 1578236430', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00'),
(25, 10, 1, 0, 0, 5, 0, 0, '1332', 'err', '1', 0, '모터', '2020-01-06 00:00:40', 20, '°C', '메시지 1578236440', 'ok', '2020-02-08 15:35:46', '0000-00-00 00:00:00');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `g5_1_data`
--
ALTER TABLE `g5_1_data`
  ADD PRIMARY KEY (`dta_idx`),
  ADD KEY `idx_dat_dt` (`dta_dt`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `g5_1_data`
--
ALTER TABLE `g5_1_data`
  MODIFY `dta_idx` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20404810;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

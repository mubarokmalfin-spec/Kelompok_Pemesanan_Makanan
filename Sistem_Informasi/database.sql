-- ========================================================
-- Database: db_spk_mental
-- Skema Sistem Pendukung Keputusan Skrining Kesehatan Mental
-- 5 Dosen PA dan 100 Mahasiswa (1 Dosen = 20 Mahasiswa)
-- ========================================================

CREATE DATABASE IF NOT EXISTS `db_spk_mental` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_spk_mental`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `skrining`;
DROP TABLE IF EXISTS `mahasiswa`;
DROP TABLE IF EXISTS `dosen`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- 1. Tabel dosen
-- --------------------------------------------------------
CREATE TABLE `dosen` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nidn` VARCHAR(30) NOT NULL UNIQUE,
  `nama_dosen` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `password_plain` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `dosen` (`id`, `nidn`, `nama_dosen`, `username`, `password`, `password_plain`, `email`) VALUES
(1, '0012058001', 'Dr. Hendra Kusuma, M.Kom.', 'dosen_hendra', '$2y$10$Yje3ve1xtHmG3PTqeexQKOzkPWpobWopDDDxB7LD6YgH8Vgl.gwJS', 'Dosen#Hendra81', 'hendra.kusuma@univ.ac.id'),
(2, '0015088202', 'Dr. Sri Wahyuni, M.T.', 'dosen_sri', '$2y$10$rM00H/6VW853QVbY7HEjTOQxS9NzWxOo8bO.aUBYrrHcCLK/uU8X.', 'Dosen@Wahyuni92', 'sri.wahyuni@univ.ac.id'),
(3, '0021038503', 'Agus Wibowo, S.Kom., M.Cs.', 'dosen_agus', '$2y$10$9M46uIMPn6J68rTmLBoS0uXM9.RThZx.BWpNW1SvWxofcLx37ccCS', 'Dosen$Wibowo73', 'agus.wibowo@univ.ac.id'),
(4, '0008118704', 'Dewi Lestari, S.T., M.Kom.', 'dosen_dewi', '$2y$10$aIph4jm1J9FqaPoLdRC9VuLvljVV1FVxrdw9PbmgSYrxi4NyiZqFi', 'Dosen%Lestari64', 'dewi.lestari@univ.ac.id'),
(5, '0030047905', 'Bambang Nugroho, M.Sc., Ph.D.', 'dosen_bambang', '$2y$10$e0APxv4d3wYfMWrhZSV/COKyCDvIm7JXY8r4F54rRDX3306iYQW5G', 'Dosen&Nugroho55', 'bambang.nugroho@univ.ac.id');

-- --------------------------------------------------------
-- 2. Tabel mahasiswa
-- --------------------------------------------------------
CREATE TABLE `mahasiswa` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nim` VARCHAR(20) NOT NULL UNIQUE,
  `nama_mahasiswa` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `password_plain` VARCHAR(100) NOT NULL,
  `prodi` VARCHAR(100) NOT NULL DEFAULT 'Sistem Informasi',
  `angkatan` VARCHAR(10) NOT NULL DEFAULT '2023',
  `jenis_kelamin` ENUM('Laki-laki', 'Perempuan') NOT NULL,
  `usia` INT NOT NULL DEFAULT 20,
  `dosen_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`dosen_id`) REFERENCES `dosen`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `mahasiswa` (`id`, `nim`, `nama_mahasiswa`, `password`, `password_plain`, `prodi`, `angkatan`, `jenis_kelamin`, `usia`, `dosen_id`) VALUES
(1, '230101001', 'Pandu Kusumawati', '$2y$10$mCK1yyRouKqoBM65eSilEOMgOyWIDNH4OKPQvQRmnOc.AAY1GXmEm', 'Nusantara@020', 'Sistem Informasi', '2023', 'Laki-laki', 20, 1),
(2, '230101002', 'Laras Hidayat', '$2y$10$J/FDAtBPlChlVbrGy.sEpO.jjOhz.0JBzo9zFLzdJaRdp8hCCUJj2', 'Pancasila#033', 'Sistem Informasi', '2023', 'Perempuan', 21, 1),
(3, '230101003', 'Nur Safitri', '$2y$10$Vkf8/XxBUdXbInhIt1uH9OBMYtozNve0Ooee.19XGUpX0tE2Rasiq', 'Harmoni$046', 'Sistem Informasi', '2023', 'Laki-laki', 22, 1),
(4, '230101004', 'Elsa Saputra', '$2y$10$6bUYoK/zEUvgB0LQNGljH.2zfGzflSF67.B9Stoeq9SXYNBeK.d8O', 'Nusantara%059', 'Sistem Informasi', '2023', 'Perempuan', 19, 1),
(5, '230101005', 'Mahesa Kurniawan', '$2y$10$VQ9FLcd5d5yOjfN5dQCmxuDY25JkvRxRlg7qfENUqGuoVKaue7q62', 'Nusantara&072', 'Sistem Informasi', '2023', 'Laki-laki', 20, 1),
(6, '230101006', 'Putri Kurniawan', '$2y$10$SMJN5EQ0zQF0TW9fgabk..IdIHR6t29mOGhGfgFASf1vzeySmNwLi', 'Ksatria*085', 'Sistem Informasi', '2023', 'Perempuan', 21, 1),
(7, '230101007', 'Fajar Saputra', '$2y$10$JfB5B60BT6.rczbP6Qfv4.0nPK1c0ckwZLLA4WhEJy0UvWNgwTO7K', 'Nusantara!098', 'Sistem Informasi', '2023', 'Laki-laki', 22, 1),
(8, '230101008', 'Kirana Kusumawati', '$2y$10$loD8U6zW.KRkZBsXS2fvhOZv3Tq5ET/Bp0UgmhNloXlqWSJ1mWxMO', 'Pintar@111', 'Sistem Informasi', '2023', 'Perempuan', 19, 1),
(9, '230101009', 'Fajar Cahyani', '$2y$10$RB5b8bwbhkM.5obPu8bOYO6ejhpaDCp62P.D/wlK7ZplDN0D0W/wC', 'Pesona#124', 'Sistem Informasi', '2023', 'Laki-laki', 20, 1),
(10, '230101010', 'Lestari Nasution', '$2y$10$rygMo4tUDJYNmDoclFFaROqM.MX8LbKaOHEri2jYonKbJV4lSB3TW', 'Mandiri$137', 'Sistem Informasi', '2023', 'Perempuan', 21, 1),
(11, '230101011', 'Bayu Kusumawati', '$2y$10$p7I.wYw2SU/UDwzYyigjE.ThpF/bIGvWKHeizHkAhU9/S9Xx.WMBq', 'Bintang%150', 'Sistem Informasi', '2023', 'Laki-laki', 22, 1),
(12, '230101012', 'Hana Indriyani', '$2y$10$3ADs0WD6rJk1yWgm9WzhdObympEMsN5Vs0Nql5YpBtfiw3IKzK0CS', 'Pesona&163', 'Sistem Informasi', '2023', 'Perempuan', 19, 1),
(13, '230101013', 'Mahesa Lubis', '$2y$10$WeNvYjR9767CGxSCBNeGTOIBa4Vc5DjeHIcm.4RyN9JkV/kCc7AJ2', 'Gemilang*176', 'Sistem Informasi', '2023', 'Laki-laki', 20, 1),
(14, '230101014', 'Yulia Pasaribu', '$2y$10$jN.WesZNYBMMnB0uHBn8w.o6xPelFz2CxcIBSUtjXE2sWPO9f3vq2', 'Pustaka!189', 'Sistem Informasi', '2023', 'Perempuan', 21, 1),
(15, '230101015', 'Eko Cahyani', '$2y$10$6HkJ20pu0ac/qtB6tjRtgeN/sjtbtbg7IETohsh4L1bFFCKGK9Peq', 'Nusantara@202', 'Sistem Informasi', '2023', 'Laki-laki', 22, 1),
(16, '230101016', 'Laras Utami', '$2y$10$CUSBxixoFU6KZrdjfbDzPOWhs5SUrSiHTyqp5cP9WC.L8RmQBoVLi', 'Pelangi#215', 'Sistem Informasi', '2023', 'Perempuan', 19, 1),
(17, '230101017', 'Rangga Wijaya', '$2y$10$dzxjAo726hdh71KDqkPimO8hRPLNhPaeoACxi9ejTkMK04CoDTRY6', 'Cerdas$228', 'Sistem Informasi', '2023', 'Laki-laki', 20, 1),
(18, '230101018', 'Nabila Hutapea', '$2y$10$dtn.ewk8gOUV.ty2WPLoKOBux6DOjcmiYwMukSQ.DJv2GH8uFX.WG', 'Pancasila%241', 'Sistem Informasi', '2023', 'Perempuan', 21, 1),
(19, '230101019', 'Pandu Santoso', '$2y$10$C.6XUb/2OsN4Ob9ANgnMx.o4vAovCK3oKP5aNcUvdTk6EVdkO9gAS', 'Pelangi&254', 'Sistem Informasi', '2023', 'Laki-laki', 22, 1),
(20, '230101020', 'Lestari Safitri', '$2y$10$c89q0LnxHTrDzQFwCzlgseL9JavLtd3kL1.HNzCMycgh7mYyA5gQy', 'Sukses*267', 'Sistem Informasi', '2023', 'Perempuan', 19, 1),
(21, '230101021', 'Farhan Siregar', '$2y$10$3dcieVpHwj/6R0p1SCeTGOwgnvRMh2WhINlxBg.GSYBx/xerCWejy', 'Prestasi!280', 'Sistem Informasi', '2023', 'Laki-laki', 20, 2),
(22, '230101022', 'Intan Syahputra', '$2y$10$Oum1Z8YheLx2ENHnWUbWuuq1CFYVs7CYb/V/pw1SRfLg6W13PODjC', 'Garuda@293', 'Sistem Informasi', '2023', 'Perempuan', 21, 2),
(23, '230101023', 'Ilham Cahyani', '$2y$10$4j3NKiLLzyj7h0yRfE6bpeZV8rdQAnZ3tPb3tszvTtFxj4Nbs2eVe', 'Gemilang#306', 'Sistem Informasi', '2023', 'Laki-laki', 22, 2),
(24, '230101024', 'Indah Gunawan', '$2y$10$qAjzZ1AdM3Yam7mfRh41W.TsDz5g2EzTxti.xRfQvXGq09NRol77a', 'Cerdas$319', 'Sistem Informasi', '2023', 'Perempuan', 19, 2),
(25, '230101025', 'Roni Rahmawati', '$2y$10$jwl76ohV7TUq48wWq9Z6sO4sE3hiAemSzNKn8DPCz5XjqZ74CmwUu', 'Nusantara%332', 'Sistem Informasi', '2023', 'Laki-laki', 20, 2),
(26, '230101026', 'Dina Syahputra', '$2y$10$M5o/5O/mIiLkM.3o8YRdleATU5rPqUIvCCfLXTWdbD4w05bLbgyAy', 'Pintar&345', 'Sistem Informasi', '2023', 'Perempuan', 21, 2),
(27, '230101027', 'Nur Pertiwi', '$2y$10$mkgAYWtEQqqqn8wHyWLs9OZ8RN6/32Od7E8Gj12Zkr37vYYD3bHya', 'Sukses*358', 'Sistem Informasi', '2023', 'Laki-laki', 22, 2),
(28, '230101028', 'Vina Hermawan', '$2y$10$gC8fDzt8HPQzJs/W/nhiM.4YXZJzDlo2.hFr5PwwcYGBvbaQMqjde', 'Sukses!371', 'Sistem Informasi', '2023', 'Perempuan', 19, 2),
(29, '230101029', 'Rudi Setiawan', '$2y$10$bal0C5wfWi/qpJNvWEwcA.4U8dwxBRaeW6vFAltKLeb9G5M5BvK2.', 'Semangat@384', 'Sistem Informasi', '2023', 'Laki-laki', 20, 2),
(30, '230101030', 'Safira Damayanti', '$2y$10$16HoehYKEwHSwkVFG6l5AudeYmEBiH27/117hFkYLVZhc6lgyiPZu', 'Pelangi#397', 'Sistem Informasi', '2023', 'Perempuan', 21, 2),
(31, '230101031', 'Budi Utomo', '$2y$10$HZTJjByNXSZMIjYoQAXcdeS5TmFePUW/44lXslOU3.VErYLqaSIWm', 'Impian$410', 'Sistem Informasi', '2023', 'Laki-laki', 22, 2),
(32, '230101032', 'Melati Nasution', '$2y$10$5rM9RoRuuAF6t6CV2Uin5eeCyC.mjruQUmzF1L3v.hnrsuIEUjuaW', 'Gemilang%423', 'Sistem Informasi', '2023', 'Perempuan', 19, 2),
(33, '230101033', 'Candra Gunawan', '$2y$10$i2BrcE9UU04d5QYQXoEyH.913/mqKXlQZEYDt1s3cLqkcLl33tekS', 'Angkasa&436', 'Sistem Informasi', '2023', 'Laki-laki', 20, 2),
(34, '230101034', 'Jihan Maulana', '$2y$10$y9IouSz60vCTtztOcEqKUO16mQZrMLTx8MH9Hvj2I5LGhzpXTvsJe', 'Pesona*449', 'Sistem Informasi', '2023', 'Perempuan', 21, 2),
(35, '230101035', 'Erlangga Wijaya', '$2y$10$5PlfRFLjOrct7OUyoX4b0.QFucIjILHKczB0gdovISGpnZ4V2y7FS', 'Pancasila!462', 'Sistem Informasi', '2023', 'Laki-laki', 22, 2),
(36, '230101036', 'Ulfa Fitriani', '$2y$10$XrC6InF87DURmSw1AnXKg.fqtw4cB5wGwHKN4XB.QuTdvDvxDYu2C', 'Pancasila@475', 'Sistem Informasi', '2023', 'Perempuan', 19, 2),
(37, '230101037', 'Reza Pasaribu', '$2y$10$0T65A1o3YLlU4UNRRu9UuewLRBV86mmWN1U5nnaOXEbXy.t1xhXGW', 'Pustaka#488', 'Sistem Informasi', '2023', 'Laki-laki', 20, 2),
(38, '230101038', 'Cahya Simanjuntak', '$2y$10$TFZVEY8rPHt9ZYolyY0QLuodxH2hUiBVbnEUxG3/Ysq.8J/P0B0lq', 'Melati$501', 'Sistem Informasi', '2023', 'Perempuan', 21, 2),
(39, '230101039', 'Nur Kurniawan', '$2y$10$2srfoees0hOuTikU2pk5vuHZK4yQx4bIqQGtR7CMhG1BZZDf5aM3W', 'Merdeka%514', 'Sistem Informasi', '2023', 'Laki-laki', 22, 2),
(40, '230101040', 'Laras Harahap', '$2y$10$CEgzcYKXwASxBuHVEyeCKe9VGyuf0sqkG6xYFkWV342NkQV4.OdNC', 'Harapan&527', 'Sistem Informasi', '2023', 'Perempuan', 19, 2),
(41, '230101041', 'Rangga Wardani', '$2y$10$M02cFyepmWETKtyjHDBJn.VuPpJ2CJJ3HSWAaR.VT4Ucid7/jdwYm', 'Gemilang*540', 'Sistem Informasi', '2023', 'Laki-laki', 20, 3),
(42, '230101042', 'Pratiwi Utomo', '$2y$10$lZAJfNkdb0JBVv4FXmghtO.rQ3Rcv1c985KawMR6qO.CS8yrQRmga', 'Pustaka!553', 'Sistem Informasi', '2023', 'Perempuan', 21, 3),
(43, '230101043', 'Raka Handayani', '$2y$10$SHtlghc7Ct6DSbzXs8ZwSOQDJ5c.uwSd7UdJr649Nuq3N5GJ7d192', 'Harapan@566', 'Sistem Informasi', '2023', 'Laki-laki', 22, 3),
(44, '230101044', 'Vivi Novitasari', '$2y$10$YFkfZrPRngpd460Dy8Xol.YxvztDCucflpK09Ckh0aCYj960Fx3fu', 'Lentera#579', 'Sistem Informasi', '2023', 'Perempuan', 19, 3),
(45, '230101045', 'Irfan Lubis', '$2y$10$m9P/fPLL75LrBCmQBYqVF.mY97F4RmTbiHYGhd8/zYGYQBsaRmRjS', 'Samudra$592', 'Sistem Informasi', '2023', 'Laki-laki', 20, 3),
(46, '230101046', 'Salma Gunawan', '$2y$10$6Y8o6Eh2PCCAVFAu24XMXOUOhpwANmvDrYajkjJYjQ9.0dqw2N3Lq', 'Prestasi%605', 'Sistem Informasi', '2023', 'Perempuan', 21, 3),
(47, '230101047', 'Erlangga Damayanti', '$2y$10$uXb4lxTgPMDNu2oq0xWJduK0bZ499G9lF20WyYMOGO13YG6abjOIW', 'Zamrud&618', 'Sistem Informasi', '2023', 'Laki-laki', 22, 3),
(48, '230101048', 'Gita Permana', '$2y$10$ILSLw.sDzIcmTlbSS99bEevdBM/jbzULJSU2392Eu1I1akx7Q1wEq', 'Nusantara*631', 'Sistem Informasi', '2023', 'Perempuan', 19, 3),
(49, '230101049', 'Rangga Cahyani', '$2y$10$ryte5.dkZPXZo.ynG.0GaumaBe45ade/0.rW8nfzmoGLIyyGbybhm', 'Harmoni!644', 'Sistem Informasi', '2023', 'Laki-laki', 20, 3),
(50, '230101050', 'Putri Utami', '$2y$10$NwbrUzMQojae.WI0et724OT.ScJ9LYnQbtpGpV2cEEFqQpW1ZOc66', 'Cahaya@657', 'Sistem Informasi', '2023', 'Perempuan', 21, 3),
(51, '230101051', 'Candra Hidayat', '$2y$10$p0.MgWMqQB2w/rebfhE5De.X/ZaPWeA3DXOS09M5gEyfQd.Ugd9GC', 'Mentari#670', 'Sistem Informasi', '2023', 'Laki-laki', 22, 3),
(52, '230101052', 'Laras Pasaribu', '$2y$10$8qqZUlA0Xz5laRC8L4OPWuPuCGwqY5/bWKlcfz1q9Bvs4zjKUm37W', 'Laksana$683', 'Sistem Informasi', '2023', 'Perempuan', 19, 3),
(53, '230101053', 'Irfan Astuti', '$2y$10$PqXm8a1NaJTPRTudxTVfHuFDglKZLe/kSGKFIOPb6dd5AitmlL7/6', 'Pustaka%696', 'Sistem Informasi', '2023', 'Laki-laki', 20, 3),
(54, '230101054', 'Berlian Wardani', '$2y$10$sP8jclN/XHhQKb3hCgE0h.1rQOR8Lxra6eFMw.I97f9o1X2MhmxD.', 'Semangat&709', 'Sistem Informasi', '2023', 'Perempuan', 21, 3),
(55, '230101055', 'Kevin Hutapea', '$2y$10$ZppPY5.eO2.gCv/Pq.RJZ.SEVruQIZIeDB5J1dy/8/yzoBre8jTly', 'Merdeka*722', 'Sistem Informasi', '2023', 'Laki-laki', 22, 3),
(56, '230101056', 'Gisela Pradana', '$2y$10$tJGbzc7JdX.D248Dx4Lfh.Ct5A48x4l25XOJ1FRD/B0t/w3JIFiiK', 'Ksatria!735', 'Sistem Informasi', '2023', 'Perempuan', 19, 3),
(57, '230101057', 'Daffa Nugraha', '$2y$10$Oqob727wF5sWYXkh6qnmb.UIK6Z6qZMU1q3/P764Fn/2cEntGNsm2', 'Nusantara@748', 'Sistem Informasi', '2023', 'Laki-laki', 20, 3),
(58, '230101058', 'Yulia Safitri', '$2y$10$OzOEHrhGnWEH1z/bgNfQJurb1jd4d2DQ3Nd8IfCloZHqg4lk2ePOi', 'Angkasa#761', 'Sistem Informasi', '2023', 'Perempuan', 21, 3),
(59, '230101059', 'Rio Hidayat', '$2y$10$3PdwuVzqiKe9iDem3kOjnuXWa6hRc6fFJhmcw.7n93Ux1YgLfLZAq', 'Gemilang$774', 'Sistem Informasi', '2023', 'Laki-laki', 22, 3),
(60, '230101060', 'Olivia Subagyo', '$2y$10$RIpHzq2sT3jjKJQGFhDVwusbpxx79yt12PqXKyk9SrJmwAxenMkqO', 'Mentari%787', 'Sistem Informasi', '2023', 'Perempuan', 19, 3),
(61, '230101061', 'Lucky Hutapea', '$2y$10$OT4KazHfRrvjIu5YdkufPeG8sz5TeNoATgbPltbLe6gRNGq4N9g/6', 'Cahaya&800', 'Sistem Informasi', '2023', 'Laki-laki', 20, 4),
(62, '230101062', 'Shinta Utami', '$2y$10$WU58yasznKldeDpCUSg4Re708aI82hGAkRG/F57H4wQCvDFvZTHAy', 'Zamrud*813', 'Sistem Informasi', '2023', 'Perempuan', 21, 4),
(63, '230101063', 'Daffa Permana', '$2y$10$VBTRAxigjzqScPhmXf71Au1Gh0oHMaakmob2PUHPsvXSbKuhFGYDe', 'Impian!826', 'Sistem Informasi', '2023', 'Laki-laki', 22, 4),
(64, '230101064', 'Rahmatia Wijaya', '$2y$10$et2n1WRgOYHz6.EJYP9gzuj2lxUxJCy6EuZGVmKFrAzra6Nb4e7J2', 'Cerdas@839', 'Sistem Informasi', '2023', 'Perempuan', 19, 4),
(65, '230101065', 'Farhan Ginting', '$2y$10$a/EKpuVCGtmywf2SVgAK4.FJ7PR/12MeQuf3dFMBHBCUdNnKrV722', 'Bintang#852', 'Sistem Informasi', '2023', 'Laki-laki', 20, 4),
(66, '230101066', 'Dwi Handayani', '$2y$10$KNvOZGf2dlE3PwacnnfhAe6FScCRziQ5YAiEtO96XX2eCVIHwdeia', 'Sukses$865', 'Sistem Informasi', '2023', 'Perempuan', 21, 4),
(67, '230101067', 'Mahesa Ginting', '$2y$10$MOMpBasTKMB8kjBQ.vLqzu4hihoYOdIOa5jbFOKDrBYz7yi9PheFu', 'Harapan%878', 'Sistem Informasi', '2023', 'Laki-laki', 22, 4),
(68, '230101068', 'Berlian Saputra', '$2y$10$nbxu.uaqWF4S7wvrHaIGVu4jI6KRbXxF0qX9solzVEa4I9unseZvW', 'Semangat&891', 'Sistem Informasi', '2023', 'Perempuan', 19, 4),
(69, '230101069', 'Budi Sitompul', '$2y$10$WCWJKretpw3./g6nLawNVu4KEqx4oSozToXgdsY9IMQd7FGO1IZB2', 'Zamrud*904', 'Sistem Informasi', '2023', 'Laki-laki', 20, 4),
(70, '230101070', 'Hani Damayanti', '$2y$10$z0ywUtpJr7OmZj7RNKdZmuAwM1l41.3dvntAVRMV6V80r6Do.A2MW', 'Pintar!917', 'Sistem Informasi', '2023', 'Perempuan', 21, 4),
(71, '230101071', 'Daffa Utomo', '$2y$10$H9iF09mjU62.ORlhmcwnZuDhHA5bcAB4HIx59GwbSmuhwQ4XLVSsm', 'Permata@930', 'Sistem Informasi', '2023', 'Laki-laki', 22, 4),
(72, '230101072', 'Endah Wulandari', '$2y$10$rHH/b0fE2Oku1nSXlon43OeS6A8G6qkcEF/QEN9o/ntq8qeejgDiG', 'Semangat#943', 'Sistem Informasi', '2023', 'Perempuan', 19, 4),
(73, '230101073', 'Satria Safitri', '$2y$10$kem53.7VuZZM/i4.jfEdduIj4PYEmRiw2jC6IzeBso/gx.35Vry62', 'Bahagia$956', 'Sistem Informasi', '2023', 'Laki-laki', 20, 4),
(74, '230101074', 'Alya Cahyani', '$2y$10$Gnk4Ga7306wDy6tngJMN4OZVkIucTPmA3slSWnBY5Ot50enaZeqRq', 'Melati%969', 'Sistem Informasi', '2023', 'Perempuan', 21, 4),
(75, '230101075', 'Yusuf Lestari', '$2y$10$QyxQXG8RJOCy6Ao3SRd9uet/Lcilg2n4LFSYteOVECYDyQOuaZf0W', 'Melati&982', 'Sistem Informasi', '2023', 'Laki-laki', 22, 4),
(76, '230101076', 'Hana Puspita', '$2y$10$fxnZ3sEQoleNht0pMTnWk.n4DFrqBN2BL0yQEbhEGrKRtKWrLfjRe', 'Gemilang*995', 'Sistem Informasi', '2023', 'Perempuan', 19, 4),
(77, '230101077', 'Zack Sitompul', '$2y$10$c0p2LY5/ve/ouzZ0dlBQieBP/aERgHLwIy18/4Hpx8nYxSv7/DDPG', 'Mandiri!009', 'Sistem Informasi', '2023', 'Laki-laki', 20, 4),
(78, '230101078', 'Safira Nugraha', '$2y$10$MDwBF53z.L.UtqNU3uo9b.fO.xsg2vwW6iKEgPeJHQhJQgUVG/ydm', 'Angkasa@022', 'Sistem Informasi', '2023', 'Perempuan', 21, 4),
(79, '230101079', 'Erlangga Cahyani', '$2y$10$AWDi8XvLFPM1gw8pE2mGMO55xe1ZshOUqR740b1VHEAoKR7DDWqRa', 'Sukses#035', 'Sistem Informasi', '2023', 'Laki-laki', 22, 4),
(80, '230101080', 'Fitri Sembiring', '$2y$10$m3TpDzH5Th7Aa2qYQfNF.O3esq.qHEszKBbz21L6IlSbKTRBfMYTq', 'Merdeka$048', 'Sistem Informasi', '2023', 'Perempuan', 19, 4),
(81, '230101081', 'Wahyu Rahmawati', '$2y$10$uJEdbh/qybcLdhAxgShPT.TSBxgy0CUrBzLZIBaqI.wBxfC3H5fLO', 'Semangat%061', 'Sistem Informasi', '2023', 'Laki-laki', 20, 5),
(82, '230101082', 'Vivi Ramadhan', '$2y$10$i4TZ1UE5u5jf.Ry7IG5vwuwsKOkhkmrh4Nynfc56H9GmBW4Djuw6u', 'Laksana&074', 'Sistem Informasi', '2023', 'Perempuan', 21, 5),
(83, '230101083', 'Handoko Saputra', '$2y$10$.ns5pJpDihwEx4qYUGd2SuLLTuV1P9u1PED6ZCI5/bL//SPaZFAsi', 'Impian*087', 'Sistem Informasi', '2023', 'Laki-laki', 22, 5),
(84, '230101084', 'Melati Wibowo', '$2y$10$zxF5J.ddu1dUY38arfuHMOzkSjDSu02L/YWsUcrO6kFfXmdg22G9y', 'Ksatria!100', 'Sistem Informasi', '2023', 'Perempuan', 19, 5),
(85, '230101085', 'Raditya Sitompul', '$2y$10$1ewvCpaZqJEfulK7mET8DemYi88WkYOjJkA599I6smwv7vt0A28D2', 'Angkasa@113', 'Sistem Informasi', '2023', 'Laki-laki', 20, 5),
(86, '230101086', 'Qonita Nasution', '$2y$10$Hb15QXgWcdCiS2Abj3mKHOSyLBf4mV8zjZDaEZ/YgN1nUbRznc3a6', 'Wawasan#126', 'Sistem Informasi', '2023', 'Perempuan', 21, 5),
(87, '230101087', 'Kharisma Pasaribu', '$2y$10$acdEOdAOA1j8WkKkhzzhC.S3BXoC3UZBIo2wOGJTmjRCHSX.u93dG', 'Bintang$139', 'Sistem Informasi', '2023', 'Laki-laki', 22, 5),
(88, '230101088', 'Gisela Subagyo', '$2y$10$Q1j37Fn2PbU6ndSksuCYOuUy3s57d2ajDw7Q30dIxOS/6395g0HHS', 'Semangat%152', 'Sistem Informasi', '2023', 'Perempuan', 19, 5),
(89, '230101089', 'Ilham Indriyani', '$2y$10$m/7kvC3cm2br6jDr6e4aaOxjYd0DTP2p5IULgXDVSXO8cR9GWpxVC', 'Pintar&165', 'Sistem Informasi', '2023', 'Laki-laki', 20, 5),
(90, '230101090', 'Gisela Simanjuntak', '$2y$10$x1KQ9mSegp27TizlML0tOuduV9qRlBdg82WaKRWGB2/bGIYCal4ye', 'Pelangi*178', 'Sistem Informasi', '2023', 'Perempuan', 21, 5),
(91, '230101091', 'Joko Maulana', '$2y$10$zof9jo3Dfl9EtEuTQeseDOtMygJypKFZU.1dfUyeVHUEqK52d0BvW', 'Harapan!191', 'Sistem Informasi', '2023', 'Laki-laki', 22, 5),
(92, '230101092', 'Fauziah Nasution', '$2y$10$oWNe.DLpl3SksGDjHV6UEeioOe7ZGV/I81hQvLfszsZEp8uANIx7.', 'Gemilang@204', 'Sistem Informasi', '2023', 'Perempuan', 19, 5),
(93, '230101093', 'Rio Kusuma', '$2y$10$VrJwl4qEj50TK3g6.r4XGuaTXjc1OsX3vNmSBXAcpmu2p9ybT/8xq', 'Mentari#217', 'Sistem Informasi', '2023', 'Laki-laki', 20, 5),
(94, '230101094', 'Fauziah Utami', '$2y$10$xlrvRpKTvsFPdH2FWRf9Ge17bxEGC./DG.Axz3WdypQq0O1KnF7YK', 'Laksana$230', 'Sistem Informasi', '2023', 'Perempuan', 21, 5),
(95, '230101095', 'Mahesa Santoso', '$2y$10$wp80IuD2.r0jMjL47qPbmeAKZmp.G3Z0qoKl9KhXDEYxUdjiRYhhy', 'Merdeka%243', 'Sistem Informasi', '2023', 'Laki-laki', 22, 5),
(96, '230101096', 'Intan Saputra', '$2y$10$d3sl7XLuMpUif5rNBEzfdugMWnt1Pl1BjFhQ.UiQvrDWY7NJH0Tla', 'Samudra&256', 'Sistem Informasi', '2023', 'Perempuan', 19, 5),
(97, '230101097', 'Rizky Rahmawati', '$2y$10$gPMggdoLf51fR/2fP3kR4exNzKL4w.y9DvzXrEd1CDUovc9g.rgii', 'Pintar*269', 'Sistem Informasi', '2023', 'Laki-laki', 20, 5),
(98, '230101098', 'Cahya Utomo', '$2y$10$eZV0thVW3OIPBsyRQFSOPO0hu5v4zgofcKFWJ5F4LwgmZl5uzdZUu', 'Mentari!282', 'Sistem Informasi', '2023', 'Perempuan', 21, 5),
(99, '230101099', 'Rio Hermawan', '$2y$10$.N3bpH2tfai6LXyYChb33ungD.i.Aj3nlyk4fqBjtKs02HDFCDMFu', 'Bahagia@295', 'Sistem Informasi', '2023', 'Laki-laki', 22, 5),
(100, '230101100', 'Alya Puspita', '$2y$10$A.4sHClQpMI8KYpTZr4PtOJ/jALFEhR1nlkvbB24fQPtPhj/GRlNe', 'Bintang#308', 'Sistem Informasi', '2023', 'Perempuan', 19, 5);

-- --------------------------------------------------------
-- 3. Tabel skrining (Riwayat Tes & SPK ML)
-- --------------------------------------------------------
CREATE TABLE `skrining` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `mahasiswa_id` INT NOT NULL,
  `dosen_id` INT NOT NULL,
  `nim` VARCHAR(20) NOT NULL,
  `nama_mahasiswa` VARCHAR(100) NOT NULL,
  `prodi` VARCHAR(100) NOT NULL,
  `angkatan` VARCHAR(10) NOT NULL,
  `jenis_kelamin` ENUM('Laki-laki', 'Perempuan') NOT NULL,
  `usia` INT NOT NULL,
  `tekanan_akademik` INT NOT NULL,
  `tekanan_pekerjaan` INT NOT NULL,
  `ipk` DECIMAL(4,2) NOT NULL,
  `kepuasan_belajar` INT NOT NULL,
  `durasi_tidur` VARCHAR(50) NOT NULL,
  `pola_makan` VARCHAR(50) NOT NULL,
  `pikiran_menyakiti_diri` VARCHAR(10) NOT NULL,
  `jam_belajar_harian` INT NOT NULL,
  `tekanan_keuangan` INT NOT NULL,
  `riwayat_keluarga` VARCHAR(10) NOT NULL,
  `hasil_prediksi` TINYINT(1) NOT NULL,
  `kategori` VARCHAR(50) NOT NULL,
  `probabilitas_rendah` DECIMAL(5,2) NOT NULL,
  `probabilitas_tinggi` DECIMAL(5,2) NOT NULL,
  `catatan_refleksi` TEXT NOT NULL,
  `faktor_utama` TEXT NOT NULL,
  `status_konseling` ENUM('Belum Ditindaklanjuti', 'Dijadwalkan Konseling', 'Selesai Konseling') DEFAULT 'Belum Ditindaklanjuti',
  `catatan_dosen` TEXT DEFAULT NULL,
  `tanggal_konseling` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`dosen_id`) REFERENCES `dosen`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `skrining` (
  `mahasiswa_id`, `dosen_id`, `nim`, `nama_mahasiswa`, `prodi`, `angkatan`, `jenis_kelamin`, `usia`,
  `tekanan_akademik`, `tekanan_pekerjaan`, `ipk`, `kepuasan_belajar`, `durasi_tidur`, `pola_makan`,
  `pikiran_menyakiti_diri`, `jam_belajar_harian`, `tekanan_keuangan`, `riwayat_keluarga`,
  `hasil_prediksi`, `kategori`, `probabilitas_rendah`, `probabilitas_tinggi`, `catatan_refleksi`, `faktor_utama`,
  `status_konseling`, `catatan_dosen`
) VALUES
(1, 1, '230101001', 'Pandu Kusumawati', 'Sistem Informasi', '2023', 'Laki-laki', 20, 2, 0, 3.75, 4, '7-8 jam', 'Sehat', 'Tidak', 4, 2, 'Tidak', 0, 'Tingkat Stress/Risiko Rendah', 93.5, 6.5, 'Kondisi psikologis dan pola hidupmu relatif stabil dan sehat. Pertahankan manajemen waktu, pola istirahat, dan lingkungan belajar yang positif!', 'Tekanan Akademik (24.71%), Tekanan Keuangan (12.01%), Jam Belajar Harian (8.45%)', 'Belum Ditindaklanjuti', NULL),
(2, 1, '230101002', 'Laras Hidayat', 'Sistem Informasi', '2023', 'Perempuan', 21, 5, 2, 3.05, 2, 'Kurang dari 5 jam', 'Tidak Sehat', 'Ya', 7, 4, 'Ya', 1, 'Tingkat Stress/Risiko Tinggi', 17.8, 82.2, 'Hasil indikasi menunjukkan kamu sedang mengalami beban pikiran atau tekanan psikologis yang cukup berat. Disarankan untuk beristirahat sejenak dan berkonsultasi dengan Dosen PA atau konselor kampus.', 'Pikiran Menyakiti Diri (35.61%), Tekanan Akademik (24.71%), Tekanan Keuangan (12.01%)', 'Dijadwalkan Konseling', 'Diharapkan hadir konseling tatap muka pada hari Selasa pukul 10.00 di Ruang Dosen Gedung B.'),
(21, 2, '230101021', 'Farhan Siregar', 'Sistem Informasi', '2023', 'Laki-laki', 20, 4, 1, 3.2, 2, '5-6 jam', 'Cukup/Sedang', 'Tidak', 5, 3, 'Tidak', 1, 'Tingkat Stress/Risiko Tinggi', 32.1, 67.9, 'Hasil indikasi menunjukkan kamu sedang mengalami beban pikiran atau tekanan psikologis yang cukup berat. Disarankan untuk beristirahat sejenak dan berkonsultasi dengan Dosen PA atau konselor kampus.', 'Tekanan Akademik (24.71%), Tekanan Keuangan (12.01%), Durasi Tidur (10.15%)', 'Belum Ditindaklanjuti', NULL),
(41, 3, '230101041', 'Rangga Wardani', 'Sistem Informasi', '2023', 'Laki-laki', 20, 2, 0, 3.85, 5, '7-8 jam', 'Sehat', 'Tidak', 3, 1, 'Tidak', 0, 'Tingkat Stress/Risiko Rendah', 95.8, 4.2, 'Kondisi psikologis dan pola hidupmu relatif stabil dan sehat. Pertahankan manajemen waktu, pola istirahat, dan lingkungan belajar yang positif!', 'Tekanan Akademik (24.71%), Tekanan Keuangan (12.01%), Jam Belajar Harian (8.45%)', 'Belum Ditindaklanjuti', NULL);

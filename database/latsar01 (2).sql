-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Jun 2025 pada 17.50
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `latsar01`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `all_kegiatan_pencacah`
--

CREATE TABLE `all_kegiatan_pencacah` (
  `id` int(11) NOT NULL,
  `kegiatan_id` int(11) NOT NULL,
  `id_pengawas` bigint(20) NOT NULL DEFAULT 0,
  `id_mitra` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `all_kegiatan_pencacah`
--

INSERT INTO `all_kegiatan_pencacah` (`id`, `kegiatan_id`, `id_pengawas`, `id_mitra`) VALUES
(1, 1, 340060064, 1),
(2, 1, 340060064, 2),
(3, 1, 340063308, 3),
(4, 1, 340063308, 4),
(5, 2, 340059726, 11),
(6, 2, 340059726, 9),
(7, 2, 6, 7),
(8, 2, 6, 15),
(9, 3, 340018881, 1),
(10, 3, 340018881, 2),
(11, 3, 5, 3),
(12, 3, 5, 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `all_kegiatan_pengawas`
--

CREATE TABLE `all_kegiatan_pengawas` (
  `id` int(11) NOT NULL,
  `kegiatan_id` int(11) NOT NULL,
  `id_pengawas` bigint(20) NOT NULL,
  `sumber_pengawas` enum('pegawai','mitra') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `all_kegiatan_pengawas`
--

INSERT INTO `all_kegiatan_pengawas` (`id`, `kegiatan_id`, `id_pengawas`, `sumber_pengawas`) VALUES
(1, 1, 340060064, 'pegawai'),
(2, 1, 340063308, 'pegawai'),
(3, 2, 340059726, 'pegawai'),
(4, 2, 6, 'pegawai'),
(5, 3, 340018881, 'pegawai'),
(6, 3, 5, 'pegawai');

-- --------------------------------------------------------

--
-- Struktur dari tabel `all_penilaian`
--

CREATE TABLE `all_penilaian` (
  `id` int(11) NOT NULL,
  `all_kegiatan_pencacah_id` int(11) NOT NULL,
  `kriteria_id` int(11) NOT NULL,
  `nilai` float DEFAULT NULL,
  `t_bobot` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `all_penilaian`
--

INSERT INTO `all_penilaian` (`id`, `all_kegiatan_pencacah_id`, `kriteria_id`, `nilai`, `t_bobot`) VALUES
(1, 4, 1, 85, 0),
(2, 4, 2, 88, 0),
(3, 4, 3, 90, 0),
(4, 4, 4, 82, 0),
(5, 4, 10, 95, 0),
(6, 3, 1, 89, 0),
(7, 3, 2, 80, 0),
(8, 3, 3, 86, 0),
(9, 3, 4, 95, 0),
(10, 3, 10, 90, 0),
(11, 2, 1, 78, 0),
(12, 2, 2, 89, 0),
(13, 2, 3, 96, 0),
(14, 2, 4, 82, 0),
(15, 2, 10, 99, 0),
(16, 1, 1, 86, 0),
(17, 1, 2, 85, 0),
(18, 1, 3, 92, 0),
(19, 1, 4, 90, 0),
(20, 1, 10, 93, 0),
(21, 11, 1, 85, 0),
(22, 11, 2, 86, 0),
(23, 11, 3, 89, 0),
(24, 11, 4, 93, 0),
(25, 11, 10, 90, 0),
(26, 12, 1, 97, 0),
(27, 12, 2, 85, 0),
(28, 12, 3, 30, 0),
(29, 12, 4, 86, 0),
(30, 12, 10, 81, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id` int(11) NOT NULL,
  `nama` varchar(64) NOT NULL,
  `start` varchar(20) DEFAULT NULL,
  `finish` varchar(20) DEFAULT NULL,
  `k_pengawas` int(11) NOT NULL,
  `k_pencacah` int(11) NOT NULL,
  `jenis_kegiatan` int(1) NOT NULL,
  `seksi_id` int(1) NOT NULL DEFAULT 0,
  `ob` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kegiatan`
--

INSERT INTO `kegiatan` (`id`, `nama`, `start`, `finish`, `k_pengawas`, `k_pencacah`, `jenis_kegiatan`, `seksi_id`, `ob`) VALUES
(1, 'Survei 1', '1748815200', '1748901600', 2, 4, 1, 2, 1),
(2, 'Survei 2', '1748901600', '1749074400', 2, 4, 1, 4, 0),
(3, 'Survei 3', '1746655200', '1747519200', 2, 4, 1, 5, 0),
(4, 'Sensus 1', '1748815200', '1749160800', 2, 4, 2, 2, 1);

--
-- Trigger `kegiatan`
--
DELIMITER $$
CREATE TRIGGER `after_insert_kegiatan` AFTER INSERT ON `kegiatan` FOR EACH ROW BEGIN
  INSERT INTO rinciankegiatan (
    kegiatan_id,
    id_mitra,
    start,
    finish,
    seksi_id,
    ob,
    beban,
    honor,
    total_honor
  )
  VALUES (
    NEW.id,         -- ID kegiatan baru
    1,              -- id_mitra default (ubah sesuai kebutuhan)
    NEW.start,
    NEW.finish,
    NEW.seksi_id,
    NEW.ob,
    0,              -- beban default
    50000,          -- honor default
    0               -- total_honor default
  );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kode_kecamatan`
--

CREATE TABLE `kode_kecamatan` (
  `kode` char(3) NOT NULL,
  `nama` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kode_kecamatan`
--

INSERT INTO `kode_kecamatan` (`kode`, `nama`) VALUES
('010', 'MERSAM'),
('011', 'MARO SEBO ULU'),
('020', 'BATIN XXIV'),
('030', 'MUARA TEMBESI'),
('040', 'MUARA BULIAN'),
('041', 'BAJUBANG'),
('042', 'MARO SEBO ILIR'),
('050', 'PEMAYUNG');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kode_keldes`
--

CREATE TABLE `kode_keldes` (
  `kode` char(6) NOT NULL,
  `nama` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kode_keldes`
--

INSERT INTO `kode_keldes` (`kode`, `nama`) VALUES
('010009', 'SENGKATI KECIL'),
('010010', 'MERSAM'),
('010011', 'BENTENG RENDAH'),
('010012', 'KEMBANG PASEBAN'),
('010013', 'KEMBANG TANJUNG'),
('010014', 'PEMATANG GADUNG'),
('010015', 'TELUK MELINTANG'),
('010016', 'SENGKATI GEDANG'),
('010017', 'SENGKATI BARU'),
('010018', 'SUNGAI PUAR'),
('010025', 'RANTAU GEDANG'),
('010026', 'TAPAH SARI'),
('010027', 'BUKIT HARAPAN'),
('010028', 'BUKIT KEMUNING'),
('010029', 'BELANTI JAYA'),
('010030', 'SIMPANG RANTAU GEDANG'),
('010031', 'SENGKATI MUDO'),
('010032', 'TANJUNG PUTRA'),
('011001', 'BATU SAWAR'),
('011002', 'PENINJAUAN'),
('011003', 'TELUK LEBAN'),
('011004', 'KAMPUNG BARU'),
('011005', 'PADANG KELAPO'),
('011006', 'SUNGAI LINGKAR'),
('011007', 'SUNGAI RUAN ILIR'),
('011008', 'SUNGAI RUAN ULU'),
('011009', 'OLAK KEMANG'),
('011010', 'TEBING TINGGI'),
('011011', 'RENGAS IX'),
('011012', 'KEMBANG SERI'),
('011013', 'BULUH KASAB'),
('011014', 'SIMPANG SUNGAI RENGAS'),
('011015', 'RAWA MEKAR'),
('011016', 'MEKAR SARI'),
('011017', 'KEMBANG SERI BARU'),
('020001', 'JELUTIH'),
('020002', 'OLAK BESAR'),
('020003', 'DURIAN LUNCUK'),
('020004', 'AUR GADING'),
('020005', 'HAJRAN'),
('020006', 'PAKU AJI'),
('020007', 'MUARA JANGGA'),
('020008', 'MATA GUAL'),
('020009', 'KOTO BOYO'),
('020010', 'KARMEO'),
('020011', 'SIMPANG KARMEO'),
('020012', 'JANGGA'),
('020013', 'TERENTANG BARU'),
('020014', 'JANGGA BARU'),
('020015', 'BULIAN BARU'),
('020016', 'SIMPANG JELUTIH'),
('020017', 'SIMPANG AUR GADING'),
('030001', 'JEBAK'),
('030002', 'AMPELU'),
('030003', 'TANJUNG MARWO'),
('030004', 'KAMPUNG BARU'),
('030005', 'SUKARAMAI'),
('030006', 'PASAR MUARA TEMBESI'),
('030007', 'RAMBUTAN MASAM'),
('030008', 'PULAU'),
('030009', 'SUNGAI PULAI'),
('030010', 'RANTAU KAPAS MUDO'),
('030011', 'RANTAU KAPAS TUO'),
('030012', 'AMPELU MUDO'),
('030013', 'PELAYANGAN'),
('030014', 'PEMATANG LIMA SUKU'),
('040007', 'SINGKAWANG'),
('040008', 'KILANGAN'),
('040012', 'RANTAU PURI'),
('040013', 'SUNGAI BULUH'),
('040014', 'MUARA BULIAN'),
('040015', 'SRIDADI'),
('040016', 'TENAM'),
('040017', 'SIMPANG TERUSAN'),
('040018', 'PASAR TERUSAN'),
('040020', 'NAPAL SISIK'),
('040021', 'MALAPARI'),
('040023', 'OLAK'),
('040024', 'TERATAI'),
('040025', 'BAJUBANG LAUT'),
('040026', 'SUNGAI BAUNG'),
('040027', 'ARO'),
('040028', 'MUARA SINGOAN'),
('040033', 'RENGAS CONDONG'),
('040034', 'PASAR BARU'),
('040035', 'RAMBAHAN'),
('040036', 'PELAYANGAN'),
('041001', 'SUNGKAI'),
('041002', 'BUNGKU'),
('041003', 'MEKAR JAYA'),
('041004', 'POMPA AIR'),
('041005', 'LADANG PERIS'),
('041006', 'PENEROKAN'),
('041007', 'BAJUBANG'),
('041008', 'BATIN'),
('041009', 'PETAJEN'),
('041010', 'MEKAR SARI NES'),
('042001', 'TERUSAN'),
('042002', 'DANAU EMBAT'),
('042003', 'BULIAN JAYA'),
('042004', 'TIDAR KURANJI'),
('042005', 'KEHIDUPAN BARU'),
('042006', 'KARYA MUKTI'),
('042007', 'BUKIT SARI'),
('042008', 'TERUSAN'),
('050003', 'TEBING TINGGI'),
('050004', 'SIMPANG KUBU KANDANG'),
('050005', 'KUBU KANDANG'),
('050006', 'KUAP'),
('050007', 'SENANING'),
('050008', 'JEMBATAN MAS'),
('050009', 'AWIN'),
('050010', 'SERASAH'),
('050011', 'PULAU BETUNG'),
('050012', 'TURE'),
('050013', 'LUBUK RUSO'),
('050014', 'OLAK RAMBAHAN'),
('050015', 'LOPAK AUR'),
('050016', 'SELAT'),
('050017', 'TELUK'),
('050018', 'PULAU RAMAN'),
('050019', 'KAOS'),
('050020', 'TELUK KETAPANG'),
('050021', 'KAMPUNG PULAU');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id` int(11) NOT NULL,
  `prioritas` int(11) NOT NULL,
  `nama` varchar(32) NOT NULL,
  `bobot` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id`, `prioritas`, `nama`, `bobot`) VALUES
(1, 1, 'Kualitas Isian', 0.3),
(2, 2, 'Ketepatan Waktu', 0.25),
(3, 3, 'Kepatuhan SOP', 0.2),
(4, 4, 'Perilaku', 0.15),
(10, 5, 'Kecepatan', 0.1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mitra`
--

CREATE TABLE `mitra` (
  `id_mitra` int(5) NOT NULL,
  `nik` char(16) NOT NULL,
  `nama` varchar(64) NOT NULL,
  `posisi` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `kecamatan` char(3) NOT NULL,
  `desa` char(6) NOT NULL,
  `alamat` varchar(128) NOT NULL,
  `jk` int(1) NOT NULL,
  `no_hp` varchar(24) NOT NULL,
  `sobat_id` char(12) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mitra`
--

INSERT INTO `mitra` (`id_mitra`, `nik`, `nama`, `posisi`, `email`, `kecamatan`, `desa`, `alamat`, `jk`, `no_hp`, `sobat_id`, `is_active`) VALUES
(1, '1504046201010004', 'Anggita Fitri Lestari', 'Mitra Pendataan', 'anggitafitri2001@gmail.com', '020', '016', 'Rt 04 Rw 03 Simpang Jelutih', 2, '+62 812-5675-5468', '150423110003', 1),
(2, '1504011911860001', 'Ade Putra', 'Mitra (Pendataan dan Pengolahan)', 'adhewz.eyes@gmail.com', '010', '012', 'Rt.15', 1, '+62 521-3927-251', '150422010001', 1),
(3, '1504036602960002', 'Monica Hikzan', 'Mitra (Pendataan dan Pengolahan)', 'Monicahikzan2475@gmail.com', '040', '014', 'Jln Sulawesi no 2 Rt 11 Rw 04 kelurahan muara bulian, kabupaten batanghari', 2, '+62 082-2558-51348', '150422010010', 1),
(4, '1504030312710005', 'Sugiono.S', 'Mitra Pendataan', 'sugionosabar938@gmail.com', '040', '024', 'Lrg.Dulur, RT.08/RW.03', 1, '+62 813-4700-6136', '150422010006', 1),
(5, '1504053003030001', 'Radja Alfajri', 'Mitra Pendataan', 'radjaalfajri25@gmail.com', '050', '010', 'JL. JAMBI- MA. BULIAN', 1, '+62 821-5882-7063', '150422010005', 1),
(6, '1504051001840002', 'Machmud', 'Mitra Pendataan', 'ajam32161@gmail.com', '050', '014', 'Jalan Dusun Sajenjang', 1, '+62 853-3257-3680', '150422010030', 1),
(7, '1504052011870003', 'Muhammad Pekih', 'Mitra Pendataan', 'muhamadpekhi@gmail.com', '050', '019', 'Kaos Rt 01 Desa kaos', 1, '+62 082-1598-18996', '150422010008', 1),
(8, '1504071101980001', 'Ridho Asshiddiqi', 'Mitra (Pendataan dan Pengolahan)', 'Ridhoasshiddiqi11@gmail.com', '040', '024', 'SIMPANG BAJUBANG LAUT RT.08', 1, '+62 822-5142-5363', '150422010015', 1),
(9, '1504034401880001', 'Mutmainah', 'Mitra (Pendataan dan Pengolahan)', 'Mutmainah8804@gmail.com', '040', '024', 'BTN BULIAN BARU 2 RT 22 KELURAHAN TERATAI', 2, '+62 822-5108-7075', '150422010009', 1),
(10, '1504030512960002', 'Rikki kurniawan', 'Mitra (Pendataan dan Pengolahan)', 'kurniawanrikki89@gmail.com', '040', '013', 'sungai buluh, rt 10, depan sd 76', 1, '+62 085-2470-59219', '150422010021', 1),
(11, '1506041404860001', 'Muhammad Hendri', 'Mitra Pendataan', 'Ehendarif2@gmail.com', '040', '028', 'Rt 01 desa muara singoan', 1, '+62 082-2518-62636', '150422100038', 1),
(12, '1504050502840002', 'Sabar santoso', 'Mitra Pendataan', 'Sabarsantosoesa@gmail.com', '050', '006', 'Jln ex pt aro km 10', 1, '+62 082-2970-99644', '150422010017', 1),
(13, '1504033112980002', 'Iqbal hawari', 'Mitra Pendataan', 'iqbalhawari77@gmail.com', '040', '024', 'BTN ratu Daha 2', 1, '082353490385', '150422020001', 1),
(14, '1504031601820004', 'Noer Alamsyah', 'Mitra (Pendataan dan Pengolahan)', 'aal@doctor.com', '040', '014', 'Jl. Irian jaya No.37 Rt.10 Rw.04 Perumnas', 1, '081349643954', '150422010035', 1),
(15, '1504060202770001', 'Adam Malik', 'Mitra Pendataan', 'adammalik56074@gmail.com', '011', '002', 'RT.04', 1, '+62 082-2562-54125', '150422010032', 1),
(16, '1504026204850001', 'Riana Pratiwi', 'Mitra Pendataan', 'pratiwiamin85@gmail.com', '030', '005', 'Jalan lintas jambi - muara bungo, desa sukaramai', 2, '+62 813-4636-4836', '150422020008', 1),
(17, '1504021010770008', 'MUSRIZAL', 'Mitra Pendataan', 'musrizalgibok12@gmail.com', '030', '013', 'Rt 004 dusun bukit berbunga', 1, '+62 082-3490-32502', '150422020021', 1),
(18, '1504021902900001', 'Dedi irawan', 'Mitra Pendataan', 'dedi190613@gmail.com', '030', '004', 'Jln. Jambi-ma. Bungo', 1, '+62 812-5432-3637', '150422020012', 1),
(19, '1504034505000004', 'INE DAMAYANTI', 'Mitra Pendataan', 'inejearty55@gmail.com', '040', '014', 'RT 04 Desa Pasar Terusan', 2, '+62 812-5565-5787', '150422010024', 1),
(20, '1504010111800001', 'Suyatno', 'Mitra Pendataan', 'Suyatnopaing@gmail.com', '010', '030', 'Jalan muaro bungo jambi', 1, '085251195341', '150422020005', 1),
(21, '1504011212850001', 'Muhammad Isnaini', 'Mitra Pendataan', 'muhammadisnaini4455@gmail.com', '010', '031', 'Jalan Lintas Jambi - Ma. Bungo', 1, '+62 081-3467-58899', '150422010054', 1),
(22, '1504062510940001', 'M.YUSUP', 'Mitra Pendataan', 'my9465880@gmail.com', '011', '010', 'RT.003.Desa Tebing Tinggi', 1, '+62 812-5580-6908', '150422010031', 1),
(23, '1504064201940001', 'Amelia Ariska', 'Mitra Pendataan', 'ameliasd044@gmail.com', '011', '005', 'RT.02 Desa Padang Kelapo', 2, '+62 812-5371-0628', '150422010033', 1),
(24, '1504081002960001', 'Ataullah Hatadi', 'Mitra Pendataan', 'ataullahhatadi33@gmail.com', '042', '008', 'RT 008 RW 003 Kelurahan Terusan Kecamatan Maro Sebo Ilir Kabupaten Batang Hari Provinsi Jambi', 1, '+62 813-5079-0907', '150422020016', 1),
(25, '1504036410890001', 'Suryanti', 'Mitra (Pendataan dan Pengolahan)', '5u12yanti@gmail.com', '040', '033', 'Hutan lindung', 2, '082153024989', '150422010044', 1),
(26, '1504051706910004', 'ALHAJRI RAZID', 'Mitra Pendataan', 'alhajryrazid@gmail.com', '050', '012', 'Jln Desa Selat - Lubuk Ruso RT 03', 1, '+62 822-5258-4239', '150422010029', 1),
(27, '1504056012900002', 'Melisa delvia', 'Mitra Pendataan', 'melisadelvia768@gmail.com', '050', '003', 'RT 07 dusun III desa tebing tinggi pemayung', 2, '+62 081-2509-55090', '150422010027', 1),
(28, '1504056305920001', 'Intan pariwara', 'Mitra Pendataan', 'ipariwaraintan@gmail.com', '050', '013', 'Lubuk ruso rt 02', 2, '+62 085-2466-91212', '150422010028', 1),
(29, '1504072810780001', 'Supomo', 'Mitra Pendataan', 'Supomojambi5@gmail.com', '041', '006', 'RT.09 Dusun purwodadi', 1, '+62 081-2538-04151', '150422010041', 1),
(30, '1504073005780004', 'Sugiman', 'Mitra Pendataan', 'sugimannadif@gmail.com', '041', '007', 'kampung baru RT 02 RW 01 bajubang', 1, '+62 081-2506-66136', '150422010039', 1),
(31, '1504076804940003', 'Hamidah Afritasari', 'Mitra Pendataan', 'hamidahas28@gmail.com', '041', '007', 'Jl. Bajubang darat km 43 RT 20 desa penerokan', 2, '6282255679727', '150422010038', 1),
(32, '1504011808930006', 'Ridwansah', 'Mitra Pendataan', 'ridwansahuffa28@gmail.com', '010', '012', 'Rt 008 Rw 002 kelurahan kembang paseban', 1, '+62 822-8494-9498', '150422020002', 1),
(33, '1504081501920003', 'Dedek Sucandra', 'Mitra Pendataan', 'dedeksucandra035@gmail.com', '042', '005', 'RT 05 RW 02', 1, '+62 813-4787-9096', '150422010045', 1),
(34, '1504070808790006', 'Edy Suwiknyo', 'Mitra Pendataan', 'edyrara1986@gmail.com', '041', '003', 'RT 11 Dusun Pendapatan', 1, '+62 822-5135-8591', '150422010046', 1),
(35, '1504010803990001', 'Dwika Riyantara', 'Mitra Pendataan', 'dwika985@gmail.com', '011', '014', 'Simpang Sungai Rengas', 1, '+62 085-3919-39231', '150422010051', 1),
(36, '1504081207690004', 'Hanafi.K', 'Mitra Pendataan', 'hanafidn2019@gmail.com', '042', '002', 'Jalan simp telkom, Rt 02 Rw 01, desa danau embat', 1, '+62 895-3066-4666', '150422020017', 1),
(37, '1504066709960002', 'Meta Amelia', 'Mitra Pendataan', 'metaamelia01@gmail.com', '011', '007', 'Desa, Sungai Ruan Ilir, RT. 015, RW. 05', 2, '+62 812-4226-6469', '150422010048', 1),
(38, '1504066709000002', 'PARIHA', 'Mitra Pendataan', 'latihan.jambi2017@gmail.com', '011', '014', 'Jln.Jambi km.120 Rt 18 Rw 01 Kelurahan Simpang Sungai Rengas', 2, '+62 812-5527-7077', '150422010050', 1),
(39, '1504065608970001', 'Gustiana Handilawati Saputri', 'Mitra Pendataan', 'Gustianahandila931@gmail.com', '011', '017', 'Jalan. RT 01 Desa Kembang Seri Baru', 2, '+62 085-3474-45743', '150422090114', 1),
(40, '1504041611760001', 'Ributsumaryono', 'Mitra Pendataan', 'ributalek47@gmail.com', '020', '009', 'Jalan lintas desa RT 05', 1, '+62 813-3105-8646', '150422020022', 1),
(41, '1504041204860001', 'Dwi Sutanto', 'Mitra Pendataan', 'dwisutantotembesi@gmail.com', '020', '013', 'Rt 24 Dusun Sido Mulyo', 1, '+62 812-5489-3779', '150422020024', 1),
(42, '1504031707780001', 'Yudi Yusnandar', 'Mitra Pendataan', 'yudiyusnandar1504@gmail.com', '040', '014', 'Jl. Sulawesi no 35 RT 011/004', 1, '+62 821-5456-2217', '150422020009', 1),
(43, '1504041609950002', 'Putra Ade Irawan', 'Mitra Pendataan', 'putraadeirawan9@gmail.com', '020', '011', 'Jl. Lintas Tembesi-Sarolangun km 15 RT 05 RW 03', 1, '+62 821-9723-6823', '150422020025', 1),
(44, '1504061606970001', 'A. Amri', 'Mitra Pendataan', 'putranusi160697@gmail.com', '011', '012', 'Jalan kembang seri', 1, '+62 853-9101-6464', '150422020018', 1),
(45, '1504045708920004', 'Mamik Agustin', 'Mitra Pendataan', 'Mamikagustin1992@gmail.com', '020', '007', 'Jln. Lintas Ma. Tembesi - Sarolangun Rt. 05 Rw. 02', 2, '+62 813-5099-9822', '150422020014', 1),
(46, '1504061704790004', 'Mohd Amir Amrullah', 'Mitra Pendataan', 'baeamir268@gmail.com', '042', '006', 'Jl. Poros Sumatra', 1, '+62 812-5807-5537', '150422020019', 1),
(47, '1572031307840001', 'Helyulitra', 'Mitra (Pendataan dan Pengolahan)', 'helyulitralik84@gmail.com', '040', '033', 'Perum Citra Palem III RT 029/04', 1, '+62 852-4718-6575', '150422030020', 1),
(48, '1504032001980001', 'Jeffri Kurnia Ramadhan', 'Mitra Pendataan', 'Jeffrikurniar@gmail.com', '040', '012', 'Desa Rantau Puri, RT.05, Kecamatan Muara Bulian, Kabupaten Batang Hari, Provinsi Jambi', 1, '+62 134-7376-592', '150422090032', 1),
(49, '1504035310930005', 'Mirna oktaviana', 'Mitra (Pendataan dan Pengolahan)', 'oktavianamirna5@gmail.com', '040', '024', 'Jalan gajah Mada BTN ratu Daha 2', 2, '+62 813-4545-2558', '150423060003', 1),
(50, '1504035601890008', 'Rosa yanly', 'Mitra (Pendataan dan Pengolahan)', 'rosayanlysanjaya@gmail.com', '040', '025', 'Bajubang laut RT 05', 2, '+62 853-4699-9269', '150422030009', 1),
(51, '1504020401960001', 'Sukris Nopi', 'Mitra Pendataan', 'sukrisnopi@gmail.com', '030', '004', 'Jl. Sarko Lrg. Tanjung Mulyo, RT004/001', 1, '+62 812-5366-7229', '150423110194', 1),
(52, '1504042510740005', 'Zukni', 'Mitra Pendataan', 'zukni.17@gmail.com', '020', '016', 'Simpang Jelutih', 1, '+62 081-2441-87188', '150422030004', 1),
(53, '1504081002930001', 'ARDIANSYAH', 'Mitra Pendataan', 'Ardi0636@gmail.com', '042', '008', 'Jln.guru syukur Rt.007 Rw.003 Kel. Terusan', 1, '+62 083-7595-1878', '150422030005', 1),
(54, '1504032201960001', 'Faisal Fajri', 'Mitra Pendataan', 'faiisalfajrii@gmail.com', '040', '027', 'Rt 03 Desa Aro', 1, '+62 822-5018-1857', '150423110005', 1),
(55, '1503062006750008', 'Ahmad Kosasih', 'Mitra Pendataan', 'Ahmadkosasihjambi@gmail.com', '040', '024', 'Griya teratai indah RT 18', 1, '+62 852-4660-0813', '150422030011', 1),
(56, '1504050211950001', 'Iswardat', 'Mitra Pendataan', 'iswardat69@gmail.com', '050', '020', 'RT 02 desa teluk ketapang', 1, '+62 811-5387-572', '150422090104', 1),
(57, '1504052604950004', 'Riko rikardo', 'Mitra Pendataan', 'rikorikardo26@gmail.com', '050', '003', 'RT 02 Dusun I desa tebing tinggi kecamatan pemayung kabupaten Batanghari', 1, '+62 822-5510-0339', '150422030010', 1),
(58, '1504052209790001', 'Hendri boy', 'Mitra Pendataan', 'boyhendri698@gmail.com', '050', '010', 'Jl. Jambi-Ma. Bulian km 33', 1, '+62 821-5302-7689', '150422030030', 1),
(59, '1504066901000004', 'Rodiatul Fitri', 'Mitra Pendataan', 'rodiatulfitri29@gmail.com', '011', '014', 'RT 013 RW006, Simpang Sungai Rengas, Kecamatan Maro Sebo Ulu, Kabupaten Batanghari, Provinsi Jambi', 2, '081255619273', '150423050003', 1),
(60, '1504011005670005', 'Thamrin', 'Mitra Pendataan', 'okethamrin123@gmail.com', '010', '012', 'Rt 001 Kel. Kembang Paseban Kec. Mersam', 1, '+62 812-5054-1384', '150422090192', 1),
(61, '1504030709770001', 'Denny Winata', 'Mitra Pendataan', 'dennywinata91@gmail.com', '040', '024', 'Jln. Gajah Mada RT. 001 Kel. Teratai', 1, '+62 853-4716-3890', '150422030026', 1),
(62, '1504036812850001', 'Devi Ermina sari', 'Mitra Pendataan', 'devierminasari@gmail.com', '030', '005', 'Dusun Sekar Sari RT 08 Desa Sukaramai', 2, '+62 821-5709-8365', '150422100010', 1),
(63, '1504070912880004', 'Sutikno', 'Mitra Pendataan', 'sutiks947@gmail.com', '041', '005', 'JALAN PAL 50 LAMA', 1, '+62 082-3523-00521', '150422030025', 1),
(64, '1504062403840002', 'Ahmad Sukri', 'Mitra Pendataan', 'sukrimburi.camat@gmail.com', '011', '014', 'Belakang kantor camat MSU Rt02 Rw01', 1, '+62 085-7525-16771', '150422030027', 1),
(65, '1504034507920004', 'MURTI\'A', 'Mitra Pendataan', 'murtiaa45@gmail.com', '040', '020', 'Jalan. Poros Desa Napal Sisik Rt. 02', 2, '082152288459', '150422030031', 1),
(66, '1504010607940006', 'Ansori', 'Mitra Pendataan', 'ansoriscout@gmail.com', '030', '008', 'Rt. 09 Desa Pulau', 1, '082152689593', '150422030028', 1),
(67, '1504010809950003', 'Yasir muttaqin', 'Mitra Pendataan', 'yasirmuttaqin4@gmail.com', '010', '029', 'Rt 16 desa belanti jaya', 1, '082252711608', '150422090145', 1),
(68, '1504031810890001', 'AHMAT SANTOSO', 'Mitra Pendataan', 'ahmatku89@gmail.com', '040', '013', 'Jalan Jambi Muara Bulian KM.54 RT.009', 1, '085247919390', '150422030035', 1),
(69, '1504070210950003', 'Muhammad Ridho', 'Mitra Pendataan', 'mridhojbi1995@gmail.com', '041', '007', 'sumber sari RT 01 Kel. Bajubang', 1, '+62 835-0417-987', '150422030036', 1),
(70, '1504026010970003', 'Nini Sulistiani', 'Mitra Pendataan', 'ninisulistiani20@gmail.com', '030', '006', 'Kelurahan pasar muara tembesi, Rt 003 Rw 001 Kel. pasar Muara Tembesi, Kec Muara Tembesi, Kab. batang Hari', 2, '+62 822-5074-9447', '150423110198', 1),
(71, '1504027009810001', 'Yeni', 'Mitra Pendataan', 'pemdesampelu2020@gmail.com', '030', '002', 'RT 05 Desa Ampelu, Kecamatan Muara Tembesi, Kabupaten Batang hari', 2, '+62 822-5006-3314', '150422100054', 1),
(72, '1504031107900003', 'Mangaloksa Hasibuan', 'Mitra Pendataan', 'hsbmangaloksa@gmail.com', '050', '006', 'RT. 02 Desa Kuap', 1, '082251520135', '150423110203', 1),
(73, '1504034505000005', 'Nazopa Ani Pardila', 'Mitra Pendataan', 'nazopaanipardila@gmail.com', '040', '026', 'Desa Sungai Baung', 2, '+62 853-8687-1076', '150423110205', 1),
(74, '1504084907990003', 'Adinda Kirana', 'Mitra Pendataan', 'kiranaadinda68@gmail.com', '042', '005', 'RT.07/02 Desa Kehidupan Baru', 2, '+62 821-5507-1163', '150423110210', 1),
(75, '1504030909990007', 'Iwan sanusi', 'Mitra Pendataan', 'iwansanusibc@gmail.com', '040', '008', 'Jalan muara Bulian - bajubang darat', 1, '+62 822-1350-4641', '150422100021', 1),
(76, '1504080710950002', 'Al Qorni', 'Mitra Pendataan', 'alqorni.aq@gmail.com', '042', '008', 'RT 003 RW 001 Kelurahan Terusan', 1, '+62 081-3484-16303', '150423110011', 1),
(77, '1504074904720001', 'Erlinawati', 'Mitra Pendataan', 'ridho060999@gmail.com', '041', '002', 'Desa bungku', 2, '+62 082-1990-7192', '150422040002', 1),
(78, '1504071602690001', 'Irwandi', 'Mitra Pendataan', 'irwandiwadi7@gmail.com', '041', '002', 'Desa Bungku', 1, '+62 081-3556-58364', '150422040001', 1),
(79, '1504074701960005', 'Mariana', 'Mitra Pengolahan', 'marianaalya86@gmail.com', '041', '001', 'Dusun Suka Mujur RT. 10 RW. 03 Desa Sungkai', 2, '082153274100', '150422050001', 1),
(80, '1504030407040001', 'Jiran fadhil', 'Mitra (Pendataan dan Pengolahan)', 'Jiranfadhil@gmail.com', '040', '024', 'RT 14 RW 04 KEL TERATAI', 1, '081352983303', '150422050003', 1),
(81, '1504054907000006', 'DWI SEKAR SARI', 'Mitra Pendataan', 'dwisekarsaridwisekarsari@gmail.com', '050', '021', 'Jln.lintas jambi muara bulian', 2, '+62 081-2500-35311', '150422090082', 1),
(82, '1504051903000001', 'M. RAFLI', 'Mitra Pendataan', 'rafli190300@gmail.com', '050', '017', 'Desa Teluk Rt.12', 1, '082258432128', '150422090049', 1),
(83, '1504052504920002', 'Ade sasmita', 'Mitra Pendataan', 'Ade25sasmita@gmail.com', '050', '010', 'RT. 02 Dusun Renah Jaya Desa Serasah', 1, '+62 821-5417-1238', '150422090084', 1),
(84, '1504060105910001', 'Subaidi', 'Mitra Pendataan', 'shubyelasak91@gmail.com', '011', '008', 'Sungai Ruan Ulu', 1, '6282149641366', '150422090078', 1),
(85, '1504052301840001', 'Edi Yusbar', 'Mitra Pendataan', 'Andiribet3@gmail.com', '050', '020', 'Jl selat-Lubuk Ruso RT, 02', 1, '6281244745997', '150422090090', 1),
(86, '1504076708980001', 'Eka Sari Dita', 'Mitra Pendataan', 'ekasariditaa@gmail.com', '041', '005', 'RT 009 Dusun Peris Baru', 2, '+62 085-3920-64396', '150423110012', 1),
(87, '1504051708920002', 'Gusti Andika', 'Mitra Pendataan', 'gustiandika17@gmail.com', '050', '006', 'RT. 04', 1, '081320050672', '150422090074', 1),
(88, '1504054505980005', 'SUAIBATUL ASLAMIAH', 'Mitra Pendataan', 'aslamiahsuaibatul64@gmail.com', '050', '007', 'RT 03 Desa Senaning', 2, '+62 282-2516-28006', '150422090106', 1),
(89, '1504072102810001', 'ANTORI', 'Mitra Pendataan', 'goblog4all@gmail.com', '041', '007', 'RT 09/03 Karang Anyar', 1, '+62 822-2891-2876', '150422090215', 1),
(90, '1504072508840001', 'AMAT TUGIMUN', 'Mitra Pendataan', 'tokoamattugimun@gmail.com', '041', '006', 'RT.16 Dusun Sekarsari', 1, '6282237716244', '150422090216', 1),
(91, '1504051308770002', 'Eman harianto', 'Mitra Pendataan', 'ermanhariyanto77@gmail.com', '050', '009', 'Rt 03 Desa Awin', 1, '+62 082-1578-53435', '150422090099', 1),
(92, '1504035010890006', 'Amalia', 'Mitra (Pendataan dan Pengolahan)', 'Amelbri053@gmail.com', '040', '014', 'Jl Sumatra no 49 Perumnas Rt 18 Rw 05', 2, '+62 813-1185-8551', '150422090275', 1),
(93, '1504031003050004', 'Aji Bayu Sulthoni', 'Mitra (Pendataan dan Pengolahan)', 'bayusulthoni@gmail.com', '040', '024', 'Jalan Gajah Mada RT 014 RW 003', 1, '+62 085-3463-3301', '150423110201', 1),
(94, '1504030108830004', 'Mahpud', 'Mitra Pendataan', 'poejetmahpud@gmail.com', '040', '033', 'Jln. Jend Sudirman, Lorong Alisa RT 05 RW 02', 1, '082255283456', '150422100026', 1),
(95, '1504032307960007', 'Bagus prakoso', 'Mitra Pendataan', 'bagusprakoso2307@gmail.com', '040', '015', 'RT 05 RW 02 Kelurahan Sridadi', 1, '085347668658', '150422100036', 1),
(96, '1504035411970004', 'Winda Hapsari Hasibuan', 'Mitra (Pendataan dan Pengolahan)', 'windahapsarihsb@gmail.com', '040', '033', 'Jalan Kol. Pol. M. Taher, RT. 002, RW. 001', 2, '+62 082-4396-5937', '150422100028', 1),
(97, '3577015604000001', 'Dea Anissa Servilla', 'Mitra (Pendataan dan Pengolahan)', 'deaanissa04@gmail.com', '040', '014', 'Jalan sumatera no 66 RT 10 RW 04', 2, '+62 812-3628-1077', '150422110003', 1),
(98, '1504034903970001', 'Titin Anggraini', 'Mitra (Pendataan dan Pengolahan)', 'titinanngraini@gmail.com', '040', '024', 'Perumahan Graha Mitranda Asri 2, Blok M No.1, RT/RW 024/004', 2, '+62 081-2536-28144', '150422090273', 1),
(99, '1504036003990001', 'Neneng sawitri', 'Mitra Pendataan', 'Nenengsawitri17@gmail.com', '040', '018', 'Dusun talang lado RT 13 desa pasar terusan', 2, '+62 852-4768-8070', '150422090013', 1),
(100, '1504036808980004', 'Gusti kartika. Az', 'Mitra (Pendataan dan Pengolahan)', 'Gustikartika299@gmail.com', '040', '014', 'Jalan jendral ahmad yani rt.02 rw.01', 2, '085246397173', '150422090274', 1),
(101, '1504036008030001', 'Asi Anggelia Safitri', 'Mitra Pendataan', 'asiandaliaa@gmail.com', '040', '018', 'Lorong Amelia', 2, '082297619221', '150422090009', 1),
(102, '1504031605990001', 'WIRA DWY KURNIAWAN', 'Mitra (Pendataan dan Pengolahan)', 'wiradwyk@gmail.com', '040', '014', 'Jln Jend Sudirman', 1, '082251169542', '150422090008', 1),
(103, '1504034911980003', 'Henida Kurniati', 'Mitra Pendataan', 'henida.kurniati09@gmail.com', '040', '035', 'Lorong Slamet RT 6', 2, '+62 082-2528-49366', '150422090028', 1),
(104, '1504026712970001', 'Desi susilawati', 'Mitra Pendataan', 'desisusilauwati2712@gmail.com', '030', '014', 'Pematang lima suku rt 10 kec.muara tembesi', 2, '+62 896-9262-8700', '150422090063', 1),
(105, '1504086901940002', 'Putri Hawani', 'Mitra Pendataan', 'putrihawani110@gmail.com', '042', '002', 'Jl. Simpang Telkom, RT 02, RW 01, Desa Danau Embat', 2, '085347360256', '150422090052', 1),
(106, '1504080805960002', 'ikrar aji risandi', 'Mitra Pendataan', 'ikraraji123@gmail.com', '042', '006', 'rt. 03 rw. 04', 1, '082255466687', '150422100022', 1),
(107, '1504032803950005', 'Edy Rian Dono', 'Mitra (Pendataan dan Pengolahan)', 'edyriando@gmail.com', '040', '024', 'Jl. Gajah Mada RT 14 RW 03', 1, '081346296701', '150422090054', 1),
(108, '1504085909860001', 'Farida Nurdiana', 'Mitra Pendataan', 'faridanurdiana778@gmail.com', '042', '007', 'RT01/01', 2, '082250727571', '150422090062', 1),
(109, '1504086903990003', 'Puja mawadda', 'Mitra Pendataan', 'Pujamawadda44@gmail.com', '042', '001', 'Desa terusan', 2, '082243719716', '150422090061', 1),
(110, '1302105606890005', 'Ersa Fransisca', 'Mitra Pendataan', 'ersafransisca33@gmail.com', '020', '001', 'RT 04', 2, '+62 822-5504-0400', '150422090066', 1),
(111, '1505011007830003', 'Hardianto', 'Mitra Pendataan', 'Ardiiiianto777@gmail.com', '020', '007', 'Rt 08 Rw. 02 Kel. Muara Jangga', 1, '+62 822-5128-0495', '150422090067', 1),
(112, '1503065606880003', 'NURATI', 'Mitra Pendataan', 'nuratigilang026@gmail.com', '020', '009', 'Jl. Lintas Desa RT.004/02', 2, '082154773275', '150422090146', 1),
(113, '1504043108880001', 'Ali jemad', 'Mitra Pendataan', 'bang95332@gmail.com', '020', '001', 'Desa jelutih', 1, '085389734446', '150422090165', 1),
(114, '1504040906910002', 'Lukman hakim', 'Mitra Pendataan', 'Lukman.hakim123409@gmail.com', '020', '013', 'RT 14 Dusun Sido Mukti Desa Terentang baru', 1, '+62 853-8787-9668', '150422090131', 1),
(115, '1504040512980002', 'Deni kurniawan', 'Mitra Pendataan', 'denikurniawanbulian@gmail.com', '020', '017', 'Jln. Jambi-sarolangun km.21', 1, '+62 852-5078-9123', '150422090069', 1),
(116, '1504041408890005', 'M. SANUSI', 'Mitra Pendataan', 'msanusi051@gmail.com', '020', '010', 'Jl. Lintas Jambi-Sarolangun KM 18 RT 09 Dusun III', 1, '081354531960', '150422090140', 1),
(117, '1505011008940001', 'Mukminatun', 'Mitra Pendataan', 'Mukmin_atun@yahoo.com', '050', '016', 'Jalan jambi-muara bulian desa selat rt 10 kecamatan Pemayung kabupaten batanghari', 2, '081328599709', '150422090079', 1),
(118, '1504060505820003', 'Hairul Pahmi', 'Mitra Pendataan', 'hairulpahmi797@gmail.com', '011', '002', 'RT. 02', 1, '+62 852-4717-2148', '150422090080', 1),
(119, '1504054201000005', 'Ade yayang putri', 'Mitra Pendataan', 'adeyayangputri@gmail.com', '050', '013', 'RT. 06 Desa Lubuk Ruso', 2, '+62 852-4606-1830', '150422090092', 1),
(120, '1504060502880001', 'ZILMIZAN', 'Mitra Pendataan', 'zilmizan@gmail.com', '011', '002', 'Jln AMD RT.04/RW.02  Peninjauan', 1, '+62 853-4521-7991', '150422090200', 1),
(121, '1504054112000005', 'Vina Ramadhan', 'Mitra Pendataan', 'Vinar0929@gmail.com', '050', '018', 'Jl. Pulau raman kaos. RT 03,RW 02', 2, '+62 085-2989-92206', '150422090105', 1),
(122, '1504056202960001', 'Nur Aini', 'Mitra Pendataan', 'nurainimaidanadira@gmail.com', '050', '018', 'Ds Pulau Raman Rt 03 Re 02', 2, '+62 813-5020-4991', '150422090112', 1),
(123, '1504064307980001', 'Liza rafiza', 'Mitra Pendataan', 'lizarafiza98@yahoo.com', '011', '002', 'Jln AMD RT 12', 2, '+62 822-1990-7181', '150422090128', 1),
(124, '1504047112900006', 'SUTINI', 'Mitra Pendataan', 'tininabil785@gmail.com', '020', '011', 'Jalan muara tembesi-sarolangun Rt. 07 dusun 1', 2, '+62 221-4819-951', '150422090263', 1),
(125, '1504046008810002', 'ERLIN ROSYA', 'Mitra Pendataan', 'erlinrosavivo@gmail.com', '020', '011', 'RT 03 dusun 02 desa simpang karmeo', 2, '+62 081-2505-53075', '150422090264', 1),
(126, '1504065008970002', 'Dwi Agustiani', 'Mitra Pendataan', 'dwiagustiyani1997@gmail.com', '011', '014', 'RT 08 RW 04 Kelurahan Simpang Sungai Rengas', 2, '+62 852-5144-6423', '150422090138', 1),
(127, '1504060310940001', 'Heri Winata Suprianto', 'Mitra Pendataan', 'Herrywinata48@yaho.com', '011', '006', 'Rt 01 desa sungai lingkar', 1, '+62 021-5322-0844', '150422090204', 1),
(128, '1504066002960001', 'Marwiyah Fitri', 'Mitra Pendataan', 'marwiyahfitrimf@gmail.com', '011', '017', 'RT.003 Dusun 1 Tangkit Jaya Desa Kembang Seri Baru', 2, '+62 821-4885-1309', '150422090116', 1),
(129, '1504063008000001', 'ARBI SUHENDRA', 'Mitra Pendataan', 'arbisuhendraajaoke@gmail.com', '011', '004', 'Rt 01 Desa kampung baru', 1, '+62 821-5122-5973', '150422090117', 1),
(130, '1504020608820004', 'Alian Saputra', 'Mitra Pendataan', 'syahpoetra22@yahoo.co.id', '030', '004', 'jalan lintas jambi-muara tembesi rt.12 rw.02', 1, '+62 813-4714-8671', '150422100012', 1),
(131, '1504014703980004', 'Wulan Vradita', 'Mitra Pendataan', 'vraditawulan@gmail.com', '011', '014', 'RT 01 RW 01 Kelurahan Simpang Sungai Rengas', 2, '+62 081-2536-07217', '150423030029', 1),
(132, '1504021507860001', 'SABIIN', 'Mitra Pendataan', 'sabiinazza@gmail.com', '030', '003', 'RT.002 Desa Tanjung Marwo', 1, '+62 813-5222-7006', '150422090123', 1),
(133, '1504024309880001', 'Siti Khusnul Chotimah', 'Mitra Pendataan', 'khusnulchotimah806@gmail.com', '030', '009', 'Jln.rimbo Kolin RT 4 dusun 2 Sungai Pulai', 2, '+62 082-2913-10040', '150422100014', 1),
(134, '1504046206850003', 'Elni', 'Mitra Pendataan', 'zeerhen53211@gmail.com', '020', '009', 'Jl. pemda RT.07/04', 2, '+62 852-5088-9978', '150422090156', 1),
(135, '1504060202920005', 'Jamaludin', 'Mitra Pendataan', 'jamaludinmal606@gmail.com', '011', '011', 'Jln .AMD Rt.01 Desa Rengas IX', 1, '081351843934', '150422090130', 1),
(136, '1504046903030001', 'ROSMALIA AININ NAJAH', 'Mitra Pendataan', 'rosmaliaaininnajah@gmail.com', '020', '014', 'Jalan Rambutan Rt006/001', 2, '+62 852-4853-5889', '150422090160', 1),
(137, '1504020306940001', 'Dina Dianti', 'Mitra Pendataan', 'dinadianti03061994@gmail.com', '030', '008', 'Rt 002 dusun buluran', 2, '085393556307', '150422100006', 1),
(138, '1504010604890001', 'Lukman', 'Mitra Pendataan', 'IrengLukman@gmail.com', '010', '012', 'Rt20 rw 05 kel.kembang pasebna', 1, '+62 081-3454-05321', '150422090163', 1),
(139, '1504014907940003', 'Ricca Juli yanti', 'Mitra Pendataan', 'riccajulianti97@gmail.com', '010', '012', 'RT 02 Kelurahan Kembang Paseban', 2, '+62 082-2565-38721', '150422090142', 1),
(140, '1504010605820001', 'INDRA', 'Mitra Pendataan', 'indramanap5@gmail.com', '010', '010', 'Mersam RT 016', 1, '+62 812-5667-0249', '150422090182', 1),
(141, '1504062610900001', 'MUKHLIS', 'Mitra Pendataan', 'Mukhlisdaud90@gmail.com', '011', '010', 'Jln.Tanah galian RT.005 Desa Tebing Tinggi Kecamatan Mari Sebo Ulu', 1, '+62 895-3076-4040', '150422090195', 1),
(142, '1504012006850003', 'Purbani Fitra Sulistiyanto', 'Mitra Pendataan', 'purbani.fitrah@gmail.com', '010', '012', 'Jln. Tanah Begali RT. 05/01', 1, '+62 853-4971-8725_', '150422090144', 1),
(143, '1504016203040002', 'NURUL ANNISA', 'Mitra Pendataan', 'nrlanisa5@gmail.com', '010', '028', 'Bukit kemuning Rt 07 Rw 02', 2, '+62 822-5157-2865', '150422090162', 1),
(144, '1504010202870003', 'HENDRI', 'Mitra Pendataan', 'hendriajaoke1987@gmail.com', '010', '013', 'Jln babat Rt.02 Desa Kembang Tanjung', 1, '+62 813-5051-7271', '150422090149', 1),
(145, '1504015312940001', 'Pitri yani', 'Mitra Pendataan', 'Fitriyanii94@icloud.com', '010', '013', 'Rt 04 desa kembang tanjung kec mersam', 2, '+62 822-5346-0919', '150422090147', 1),
(146, '1504016506020001', 'Nabila', 'Mitra Pendataan', 'Nbae38878@gmail.com', '010', '013', 'Kembang tanjung rt. 04', 2, '+62 822-5514-9306', '150422090150', 1),
(147, '1504030904830002', 'Awin kurniawan', 'Mitra Pendataan', 'awinkurniawan18@gmail.com', '010', '029', 'Desa belanti jaya Rt. 06', 1, '+62 852-4633-6067', '150422090153', 1),
(148, '1504010807960001', 'Muhamad Mustaqim', 'Mitra Pendataan', 'taqim1767@gmail.com', '010', '029', 'Rt 14, Belanti Jaya', 1, '+62 853-4814-6469', '150422090152', 1),
(149, '1504015307930002', 'Riyanti Pranciska', 'Mitra Pendataan', 'abelsyafira8@gmail.com', '010', '032', 'Jalan Jambi Muaro Bungo', 2, '+62 082-1545-37209', '150422090164', 1),
(150, '1504012209960001', 'ADYTIA PRATAMA', 'Mitra Pendataan', 'aditbae010203@gmail.com', '010', '011', 'RT.003 Desa Benteng Rendah', 1, '+62 812-5478-9475', '150422090154', 1),
(151, '1504010206920003', 'Muhamad Junaldi', 'Mitra Pendataan', 'Joenaldialdi@gmail.com', '010', '031', 'Jalan Lintas jambi ma.bungo', 1, '+62 813-1905-5739', '150422090158', 1),
(152, '1504011007840006', 'HERMANTO', 'Mitra Pendataan', 'hermantobae561@gmail.com', '010', '017', 'RT 09 RW 00 DESA SENGKATI BARU', 1, '+62 823-5017-6634', '150422090166', 1),
(153, '1504011809930001', 'Denni Rizky kurniawan', 'Mitra Pendataan', 'dennirizky18282011@yahoo.com', '010', '030', 'RT 005 desa simpang rantau gedang kecamatan mersam kabupaten batang hari', 1, '+62 822-5255-4910', '150422090184', 1),
(154, '1504010502700004', 'ILMAN', 'Mitra Pendataan', 'ilmanputra207@gmail.com', '010', '025', 'RT 03 Desa Rantau Gedang', 1, '081220843147', '150422090177', 1),
(155, '1504016408950001', 'Idawanti', 'Mitra Pendataan', 'ida463648@gmail.com', '010', '030', 'RT 014,simpang rantau gedang', 2, '081296008650', '150422090183', 1),
(156, '1504014703950001', 'Sari mustifah', 'Mitra Pendataan', 'sarimustifah95@gmail.com', '010', '027', 'Rt04', 2, '082158834318', '150422090170', 1),
(157, '1504010105930001', 'Mustaqim', 'Mitra Pendataan', 'zefran25oke@gmail.com', '010', '016', 'Sengkati Gedang', 1, '+62 822-5640-2436', '150422090174', 1),
(158, '1504014403780003', 'Ema yusefa', 'Mitra Pendataan', 'emayusefa68@gmail.com', '010', '030', 'Rt 010,simpang rantau gedang', 2, '+62 081-2507-30730', '150422090180', 1),
(159, '1504016605950001', 'Lulus Riyana', 'Mitra Pendataan', 'lulusriyana@gmail.com', '010', '027', 'RT 03', 2, '082158400252', '150422090169', 1),
(160, '1504015910020001', 'Mariha', 'Mitra Pendataan', 'marihamahmudzhudi@gmail.com', '010', '017', 'RT 05 Desa sengkati Baru', 2, '085252808233', '150422090179', 1),
(161, '1504070606910002', 'PUJO HARTONO', 'Mitra Pendataan', 'udjoptj@gmail.com', '041', '009', 'Jln. Ness Rt 11 Dusun Meranti  Desa Petajen', 1, '+62 823-4393-8097', '150422090254', 1),
(162, '1504071507900004', 'Ilham Salasa', 'Mitra Pendataan', 'Ilhamsalasa1990@gmail.com', '041', '010', 'Rt 004 dusun merbau tiga desa mekar sari nes', 1, '+62 852-5298-5830', '150422090235', 1),
(163, '1504070807750004', 'Sarijo', 'Mitra Pendataan', 'sarijoijo75@gmail.com', '041', '006', 'RT 15 DUsun sekarsari desa penerokan', 1, '082299131595', '150422090249', 1),
(164, '1504071508870001', 'Wahid Subandi', 'Mitra Pendataan', 'subandiwahid89@gmail.com', '041', '005', 'Rt 08 desa ladang peris', 1, '+62 085-2458-28228', '150422090251', 1),
(165, '1504075101010008', 'DELA ERIMA EFENDI', 'Mitra Pendataan', 'delaerimaefendi11@gmail.com', '041', '006', 'RT 10 KM 44 Desa Penerokan Kecamatan Bajubang Kabupaten Batang Hari', 2, '+62 085-2475-01773', '150422090240', 1),
(166, '1504025102800003', 'ROSMALINDA', 'Mitra Pendataan', 'lindarosma508@gmail.com', '030', '010', 'Rt 03 Dusun 02 Desa Rantau Kapas Mudo', 2, '081283295455', '150422090217', 1),
(167, '1504070512700001', 'PAWET W', 'Mitra Pendataan', 'pawetwidodo776@gmail.com', '041', '002', 'Rt19 dusun 4 kunangan jaya 1 bungku', 1, '085246305055', '150422090224', 1),
(168, '1504075004840004', 'Sukarna', 'Mitra Pendataan', 'Sukarnahkarnah104@gmail.com', '041', '002', 'Rto5 dusun1 desa bungku', 2, '+62 895-3248-83553', '150422090236', 1),
(169, '1504076005860001', 'Erta susilawati', 'Mitra Pendataan', 'ertaserta293@gmail.com', '041', '002', 'RT 02 dusun satu bungku', 2, '+62 821-5491-6146', '150422090234', 1),
(170, '1504072507890004', 'Mohtadi', 'Mitra Pendataan', 'muhtadiadi14144@gmail.com', '041', '003', 'Rt11 rw 04 desa mekar jaya', 1, '+62 082-1583-05737', '150422090257', 1),
(171, '1504022307940001', 'Deni ardiansyah', 'Mitra Pendataan', 'rosodeni98@gmail.com', '030', '014', 'Desa pematang lima suku rt 06', 1, '+62 812-5628-246', '150422090268', 1),
(172, '1504022810960001', 'OCCA RAHMAT CSH', 'Mitra Pendataan', 'occarahmat71@gmail.com', '030', '010', 'RT 07 Desa Rantau Kapas Mudo', 1, '+62 813-4245-5208', '150422090265', 1),
(173, '1504020708860005', 'AHMAD YANI', 'Mitra Pendataan', 'kojekriankojek@gmail.com', '030', '007', 'Rambutan masam rt 013', 1, '081258462663', '150422100048', 1),
(174, '1504040510780001', 'Ahmad Nawawi', 'Mitra Pendataan', 'ahmadnawawibulianbaru@gmail.com', '020', '015', 'Rt 05 rw 02 dusun maya sari', 1, '081347001467', '150422100019', 1),
(175, '1504067008040001', 'Amaliani eka gusnika', 'Mitra Pendataan', 'amaliaeka293@gmail.com', '011', '014', 'RT 18 RW 01 kel.simpang sungai rengas', 2, '085226192929', '150422100042', 1),
(176, '1504074105990002', 'Meitri Syafiqa', 'Mitra Pengolahan', 'syameitri@gmail.com', '041', '006', 'RT.11 Dusun Wonorejo', 2, '082133224619', '150423060001', 1),
(177, '1504037112900002', 'ROMI MANDASARI', 'Mitra (Pendataan dan Pengolahan)', 'romimandasari90@gmail.com', '040', '033', 'jln.jendral sudirman', 2, '082158849553', '150422110013', 1),
(178, '1504035508990001', 'Hasri Ainun', 'Mitra (Pendataan dan Pengolahan)', 'ainunhasri188@gmail.com', '040', '026', 'Desa sungai baung', 2, '+62 896-4984-5094', '150422110008', 1),
(179, '1504030202980002', 'Ikhsan Arifki', 'Mitra Pengolahan', 'ikhsanarifki@gmail.com', '040', '024', 'Komplek mayang mangurai blok L', 1, '+62 812-5680-162', '150422110010', 1),
(180, '1504034207950003', 'Tiara Dwi Julianda', 'Mitra Pengolahan', 'tiaradwijulianda00@gmail.com', '040', '033', 'Hutan Lindung Rt.17 Rw 04 Kelurahan Rengas Condong', 2, '+62 082-3940-32834', '150422110004', 1),
(181, '1504031508900001', 'Alba Syambas', 'Mitra (Pendataan dan Pengolahan)', 'syambasdesign737@gmail.com', '040', '014', 'Jln. Mahoni 1 Blok. M10 Rt.36 Perumahan PBI, Muara Bulian,', 1, '+62 812-5576-7717', '150422110005', 1),
(182, '1504056407030001', 'ARIIBA AADILAH', 'Mitra Pengolahan', 'ariiba.aadilah@gmail.com', '050', '003', 'RT. 001 RW. 000 Ds. Tebing-tinggi', 2, '082251963064', '150422110009', 1),
(183, '1504032208930001', 'AZUAN ANAS', 'Mitra (Pendataan dan Pengolahan)', 'azuananas22@gmail.com', '040', '012', 'Desa Rantau Puri, RT.04', 1, '085348792868', '150422110007', 1),
(184, '1504036006980002', 'Revira Yuninda', 'Mitra Pengolahan', 'reviraxy@gmail.com', '040', '024', 'Jalan Baru Pematang Inuman,lrg Tentram,RT 24 RW 03 kelurahan Teratai Kec Muara bulian Kab Batanghari Provinsi Jambi.', 2, '+62 822-1142-8191', '150422110012', 1),
(185, '1504035212990008', 'Rahmaya desrin', 'Mitra Pendataan', 'Desrinrahmaya@gmail.com', '040', '007', 'Desa Singkawang RT 07 dekat kantor balai desa', 2, '+62 896-1936-7666', '150423110018', 1),
(186, '1504044105000004', 'Lailatul Munawaroh', 'Mitra Pendataan', 'munawaroh1777@gmail.com', '020', '016', 'Jln muara tembesi-sarolangun, desa simpang jelutih kecamatan batin XXIV kabupaten batanghari', 2, '+62 082-2534-65992', '150423030046', 1),
(187, '1504035306030003', 'Yuyun vereti Sinta', 'Mitra (Pendataan dan Pengolahan)', 'yuyunveretisinta@gmail.com', '040', '017', 'Simpang terusan RT 05 dusun suka damai , kabupaten Batanghari', 2, '+62 822-2619-3188', '150423060043', 1),
(188, '1501114908010004', 'Satri Diana', 'Mitra Pendataan', 'dd6054186@gmail.com', '050', '003', 'Desa tebing tinggi, RT 07', 2, '+62 813-4651-6459', '150423030003', 1),
(189, '1504044607000001', 'Sinta Susanti', 'Mitra Pendataan', 'susantisinta883@gmail.com', '020', '003', 'Jl. PTPN VI RT 10 RW 000', 2, '+62 895-3500-88567', '150423110259', 1),
(190, '1504081806980003', 'Sholahudin abdillah', 'Mitra Pendataan', 'sholahudinabdillah98@gmail.com', '042', '001', 'Terusan', 1, '+62 812-5318-9915', '150423030018', 1),
(191, '1504073001020002', 'Toha Bimantara', 'Mitra Pendataan', 'tarabimantara10@gmail.com', '041', '003', 'Rt. 07 Dusun Pemerataan  Desa Mekar Jaya', 1, '+62 082-2433-39413', '150423030007', 1),
(192, '1504036704970003', 'ALFIN SAKINAH', 'Mitra Pengolahan', 'alfinsakinahhhh@gmail.com', '040', '013', 'Jalan Jambi Muara Bulian Dusun Baru RT 01 Desa Sungai Buluh', 2, '+62 858-4545-2437', '150423060028', 1),
(193, '1504064512960001', 'Komariah', 'Mitra Pendataan', 'komariahrirista05@gmail.com', '042', '004', 'RT 02 RW 01 Desa Tidar Kuranji', 2, '+62 822-5041-8866', '150423030009', 1),
(194, '1504082512940002', 'Dwi Setiawan', 'Mitra Pendataan', 'setiawandwi565@gmail.com', '042', '002', 'RT 007 RW 003', 1, '+62 812-5005-8200', '150423110263', 1),
(195, '1504052601930001', 'SALMAN ALFARISI', 'Mitra Pendataan', 'salmanalf69@gmail.com', '050', '011', 'Rt 07 Desa Pulau Betung', 1, '+62 822-5134-7505', '150423030012', 1),
(196, '1504066708980002', 'Suci Lestari', 'Mitra Pendataan', 'sucilestari270898@gmail.com', '011', '007', 'Sungai Ruan Ilir Rt. 011 Rw. 000', 2, '+62 812-2679-6217', '150423110209', 1),
(197, '1504054202990001', 'VIVI BELLA LARASATI', 'Mitra Pendataan', 'vivibellalarasati02@gmail.com', '050', '004', 'SIMPANG KUBU KANDANG RT 004', 2, '+62 813-5157-7575', '150423030017', 1),
(198, '1504066809950001', 'NURUL SEFTARIANA', 'Mitra Pendataan', 'nurulput607@gmail.com', '011', '004', 'Rt 04 desa kampung baru', 2, '+62 812-4555-2015', '150423030019', 1),
(199, '1504021404040001', 'M. ABI BURRAHMAN', 'Mitra Pendataan', 'abiburrahman2004@gmail.com', '030', '014', 'Jln.LINTAS MUARO BUNGO-JAMBI DESA PEMATANG LIMA SUKU RT 05', 1, '+62 812-5490-4732', '150423030027', 1),
(200, '1504076506800001', 'Yumharlina', 'Mitra Pendataan', 'Yumharlina@gmail.com', '041', '005', 'RT.01Simpang Jambi Ladang Peris Bajubang', 2, '+62 821-5263-6353', '150423050080', 1),
(201, '1504085902990002', 'MAYA MARINA MARPAUNG', 'Mitra Pendataan', 'mayamarina34@gmail.com', '042', '004', 'Tidar Kuranji RT 001 RW 001', 2, '+62 853-2520-1727', '150423030036', 1),
(202, '1504062601980001', 'Muhammad Nawawi', 'Mitra Pendataan', 'nawawydedeo@gmail.com', '011', '003', 'Jalan AMD desa teluk leban', 1, '+62 813-5094-2721', '150423030031', 1),
(203, '1504031707000007', 'Mario pebrian', 'Mitra Pendataan', 'mariofebrian619@gmail.com', '040', '021', 'Jl. Ladang panjang RT. 01 Desa Malapari', 1, '+62 812-5564-8206', '150423110218', 1),
(204, '1504061610970001', 'Syaipur rohim', 'Mitra Pendataan', 'saifurazzainii16@gmail.com', '011', '014', 'RT/RW 014/006 kelurahan Simpang Sungai Rengas', 1, '+62 822-3880-5940', '150423030033', 1),
(205, '1504041708940003', 'Nasrin arsadi', 'Mitra Pendataan', 'Cberkah085@gmail.com', '020', '007', 'Muara jangga Rt 02 Rw 03', 1, '+62 085-2427-57455', '150423030047', 1),
(206, '1504021209830003', 'PARYONO', 'Mitra Pendataan', 'a085269609998@gmail.com', '030', '009', 'Jl.jambi-muara bungo', 1, '+62 813-5700-5122', '150423030037', 1),
(207, '1504036511980003', 'Gitty Loviani', 'Mitra (Pendataan dan Pengolahan)', 'gittyloviani94@gmail.com', '040', '034', 'Jl orang Kayo Hitam RT 002 RW 003', 2, '+62 821-4983-9144', '150423060026', 1),
(208, '1504040512860001', 'Alimin', 'Mitra Pendataan', 'alimin051285@gmail.com', '020', '013', 'Rt12 dusun sido rukun desa terentang baru', 1, '+62 813-4544-9449', '150423030056', 1),
(209, '1504045209990002', 'Sartika Sari', 'Mitra Pendataan', 'sartika012@gmail.com', '020', '001', 'RT 09, RW 03 desa jelutih, kecamatan batin xxiv', 2, '+62 812-5494-4415', '150423030048', 1),
(210, '1504045410920004', 'Ani Rosadiana', 'Mitra Pendataan', 'anirosa343@gmail.com', '020', '008', 'MataguaL, rt 02,rw 01,kecamatan batin XXIV', 2, '+62 852-4792-5355', '150423030057', 1),
(211, '1504023012900002', 'Gigin pradana', 'Mitra (Pendataan dan Pengolahan)', 'giginpradana31@gmail.com', '030', '004', 'RT. 006 RW. 001 KELURAHAN KAMPUNG BARU KECAMATAN MUARA TEMBESI', 1, '+62 083-5300-0123', '150423040001', 1),
(212, '1504072311950001', 'Sutrisno', 'Mitra Pendataan', 'vivoc9349@gmail.com', '041', '002', 'Rt18 rw04 dusun kunangan jaya 1 desa bungku', 1, '+62 821-5489-4979', '150423050001', 1),
(213, '1504010202770007', 'Paimin', 'Mitra Pendataan', 'paiminpaimin030@gmail.com', '010', '030', 'RT 05', 1, '+62 082-3333-00021', '150423050084', 1),
(214, '1504075109010005', 'Sucitra Dwi Sanjaya', 'Mitra Pendataan', 'sucitradvc@gmail.com', '041', '007', 'RT 08 RW 03,  Karang Anyar Tengah, Kelurahan Bajubang', 2, '+62 812-5849-4527', '150423060005', 1),
(215, '1504031910990001', 'M. Heri', 'Mitra (Pendataan dan Pengolahan)', 'anakboval19@gmail.com', '040', '014', 'JL. Jendral Sudirman Pal 1 Muara Bulian, RT 030/RW 008', 1, '+62 822-5125-4378', '150423060029', 1),
(216, '1504035207990004', 'NOFRIANI S', 'Mitra Pengolahan', 'nofrianis99@gmail.com', '040', '014', 'Jln. Jend sudirman km.4 Rt.23 Rw.06 kel. muarabulian', 2, '+62 853-4033-8087', '150423110232', 1),
(217, '1504031201960004', 'Indra hidayat', 'Mitra Pengolahan', 'bleizjr.ih@gmail.com', '040', '014', 'Jalan Gajah Mada RT 021 RW 002', 1, '+62 822-5219-4616', '150423060015', 1),
(218, '1504076311960006', 'Eva Giyarti', 'Mitra Pendataan', 'evagiyarti23@gmail.com', '041', '006', 'Penerokan KM 42 RT.003', 2, '+62 822-8443-0942', '150423110247', 1),
(219, '1504030608050006', 'Dimas Ivan Angga Saputra', 'Mitra (Pendataan dan Pengolahan)', 'ivanjambi068@gmail.com', '040', '033', 'Jl. Hutan Lindung RT 15 RW 04', 1, '+62 852-4900-2422', '150423080004', 1),
(220, '1504076303990004', 'Syltiva', 'Mitra (Pendataan dan Pengolahan)', 'tivasyl@gmail.com', '041', '008', 'RT 04, Dusun Anggrek, Desa Batin, Kec. Bajubang', 2, '+62 853-4699-9884', '150423060027', 1),
(221, '1504036807980005', 'Rima melati', 'Mitra Pengolahan', 'rimamelati3310@gmail.com', '040', '014', 'Jl. Ahmad Yani', 2, '+62 822-5484-9253', '150423060014', 1),
(222, '1203046404980001', 'Nanda Lathifah Siregar', 'Mitra (Pendataan dan Pengolahan)', 'nandalathifahsiregar@gmail.com', '040', '014', 'Perumahan Pondok Berlian Indah No.17 Rt.036 Rw.008', 2, '+62 812-5097-9851', '150423100002', 1),
(223, '1504035110960004', 'RTS Nur Oktapiani', 'Mitra Pengolahan', 'Rtsnuroktapiani11@gmail.com', '040', '024', 'Kampung Tengah RT 09 RW 02', 2, '+62 857-5751-53794', '150423060024', 1),
(224, '1504021806000001', 'WAHYU DIMAS', 'Mitra (Pendataan dan Pengolahan)', 'saintswahyu@gmail.com', '030', '013', 'Rt 02 desa pelayangan kecamatan muara Tembesi', 1, '+62 822-5149-8797', '150423060009', 1),
(225, '1504052707990001', 'Andika', 'Mitra Pengolahan', 'andikala27@gmail.com', '050', '015', 'RT 001 RW 001 Lorong Delima, tepian Sungai Batang Hari', 1, '+62 813-4640-3425', '150423070001', 1),
(226, '1504033112010005', 'Ahmad Zidane', 'Mitra Pendataan', 'ahmadzidanez445@gmail.com', '040', '016', 'RT 03 Desa Tenam, Kec. Muara Bulian, Kab. Batanghari', 1, '+62 081-2535-66162', '150423110001', 1),
(227, '1504062106980003', 'Al muhajirin', 'Mitra Pendataan', 'almhj21@gmail.com', '011', '015', 'Rawa mekar Rt04/01 kec.maro sebo ulu .kab.Batanghari', 1, '+62 085-3482-03061', '150423110111', 1),
(228, '1504076804930001', 'Dina Tri Pratiwi', 'Mitra Pendataan', 'dinatripratiwi28@gmail.com', '041', '007', 'Bajubang RT. 013 RW. 004', 2, '+62 081-3511-57868', '150423110152', 1),
(229, '1504042508990002', 'Fani Arliansyah', 'Mitra Pendataan', 'Kodokzuma77@gmail.com', '020', '015', 'Bulian baru', 1, '+62 821-5015-4190', '150423110260', 1),
(230, '1504017009020001', 'Astri Nesti', 'Mitra (Pendataan dan Pengolahan)', 'Astrinesti53@gmail.com', '010', '012', 'Kembang paseban Rt.19', 2, '+62 822-5436-6811', '150423110178', 1),
(231, '1504075411940003', 'Ulin Nikmah', 'Mitra Pendataan', 'u.nikmah55@gmail.com', '041', '006', 'Jl. Bajubang Darat KM 42 RT 03 Desa Penerokan', 2, '+62 813-4757-1554', '150423110125', 1),
(232, '1504076309010001', 'DHEA ALMA DEWI', 'Mitra Pendataan', 'dheaalma66@gmail.com', '041', '001', 'RT.005, Dusun Suka Damai, Desa Sungkai, Kecamatan Bajubang, Kabupaten Batang Hari', 2, '+62 081-2549-14044', '150423110109', 1),
(233, '1504045112950002', 'Rika nopita sari', 'Mitra Pendataan', 'novitarika201@gmail.com', '020', '008', 'Jalan sarolangun-jambi', 2, '+62 822-8682-7445', '150423110187', 1),
(234, '1504032308960001', 'Aripan', 'Mitra Pendataan', 'arifan060622@gmail.com', '042', '008', 'Kelurahan terusan RT 012 RW 004', 1, '+62 085-2477-55424', '150423110171', 1),
(235, '1504010808920007', 'Muslim', 'Mitra Pendataan', 'm_muslim28@yahoo.com', '010', '011', 'Jalan Jambi Muara Bungo RT 01 Desa Benteng Rendah', 1, '085246442264', '150423110099', 1),
(236, '1504040404950001', 'Fathurrahman', 'Mitra Pendataan', 'fathurrahmanzulf.djb@gmail.com', '020', '002', 'Jalan tembesi - Sarolangun km 35', 1, '+62 822-5452-5577', '150423110113', 1),
(237, '1504035407970004', 'Indri', 'Mitra Pendataan', 'Indriin997@gmail.com', '040', '016', 'Desan tenam RT.01', 2, '+62 895-7008-10302', '150423110054', 1),
(238, '1504035211000001', 'Putri Ayuningsih M', 'Mitra Pengolahan', 'putriayy312@gmail.com', '040', '033', 'Jalan. Raden Mattaher', 2, '+62 821-4920-0047', '150423110097', 1),
(239, '1504041111820001', 'ERIWI SUSANTO', 'Mitra Pendataan', 'susantoeriwi@gmail.com', '020', '010', 'Jl.Lintas-jambi sarolangun', 1, '+62 821-5883-8547', '150423110159', 1),
(240, '1504031509020001', 'Randisaputra', 'Mitra Pendataan', 'Randi414514@gmail.com', '040', '014', 'Jalan gajah mada', 1, '+62 821-5808-0506', '150423110252', 1),
(241, '1504021907010003', 'Wahid Wahyudi', 'Mitra Pendataan', 'wahyube8@gmail.com', '030', '014', 'Jalan Jambi-Bungo RT 009', 1, '+62 081-2539-22666', '150423110146', 1),
(242, '1504034107990004', 'Ririn Anjeli', 'Mitra Pendataan', 'ririnririnnn5@gmail.com', '042', '001', 'Terusan Rt 09', 2, '+62 085-2478-97697', '150423110164', 1),
(243, '1504055708860005', 'Ida Candra', 'Mitra Pendataan', 'iccandra17.ip@gmail.com', '050', '003', 'RT 02 Desa Tebing Tinggi Kecamatan Pemayung Kabupaten Batang Hari', 2, '+62 081-3504-73725', '150423110028', 1),
(244, '1504016111950001', 'Nurhikmah', 'Mitra Pendataan', 'Nurkema21@gmail.com', '010', '012', 'Jln jambi-ma Bungo Kel kembang Paseban RT 02', 2, '+62 082-2361-16093', '150423110258', 1),
(245, '1504035808010004', 'R. Radhiah Chairunnisa', 'Mitra (Pendataan dan Pengolahan)', 'radhiahc@gmail.com', '040', '033', 'Jalan Gajah Mada RT 06 RW 02 Kelurahan Rengas Condong Kecamatan Muara Bulian', 2, '+62 813-4967-5984', '150423110022', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawai`
--

CREATE TABLE `pegawai` (
  `id_peg` int(11) NOT NULL,
  `nip` bigint(18) NOT NULL,
  `nama` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `jabatan` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pegawai`
--

INSERT INTO `pegawai` (`id_peg`, `nip`, `nama`, `email`, `jabatan`) VALUES
(340015473, 197410281997121001, 'Hartono, S.Si., M.E', 'ton@bps.go.id', 'Kepala BPS'),
(340016150, 197808292000122001, 'Rina Agustina, S.ST.', 'rinagus@bps.go.id', 'Statistisi Ahli Muda'),
(340017146, 197109062003121001, 'Johani, S.P.', 'johani@bps.go.id', 'Statistisi Penyelia'),
(340017522, 198405052005021001, 'Madik, S.E., M.E.', 'madik@bps.go.id', 'Analisis Pengelola Keuangan APBN Ahli Muda'),
(340018103, 198010202006041001, 'Angger Halim Ismail, S.P.', 'angger@bps.go.id', 'Kasubbag Umum'),
(340018881, 197303072006041018, 'Isyak', 'isyak@bps.go.id', 'Statistisi Mahir'),
(340019333, 198108122007011001, 'Doli Herdianto', 'doli@bps.go.id', 'Pelaksana'),
(340019347, 197310062007011003, 'Jupri', 'jupri@bps.go.id', 'Statistisi Penyelia'),
(340019368, 197201062007011001, 'A. Puadi, S.IP', 'puadi@bps.go.id', 'Statistisi Ahli Muda'),
(340050244, 198509252009022001, 'Septie Wulandary, SST, M.Stat', 'septie@bps.go.id', 'Statistisi Ahli Madya'),
(340052320, 198107232009012002, 'Yusmiradewi', 'yusmira@bps.go.id', 'Pranata Keuangan APBN Terampil'),
(340054651, 198510172011011009, 'Hendra Rusmanto, SE', 'hrusmanto@bps.go.id', 'Statistisi Ahli Muda'),
(340056947, 199107282014102001, 'Eka Julita Irmayanti, S.ST', 'ekajulita@bps.go.id', 'Statistisi Ahli Muda'),
(340057667, 199507292017012002, 'Linda Annisa, S.ST.', 'linda.annisa@bps.go.id', 'Statistisi Ahli Muda'),
(340059527, 199706012019122001, 'Fitri Pratiwi, S.Tr.Stat.', 'fitri.pratiwi@bps.go.id', 'Statistisi Ahli Pertama'),
(340059726, 199710262019122001, 'Rensy Hasibuan, S.Tr.Stat.', 'rensy.hasibuan@bps.go.id', 'Statistisi Ahli Pertama'),
(340060064, 199803062021041001, 'Dwi Satria Firmansyah, S.Tr.Stat.', 'satria.firmansyah@bps.go.id', 'Statistisi Ahli Pertama'),
(340060586, 200002122022012003, 'Dias Khusnul Khotimah, S.Tr.Stat.', 'diaskhusnul@bps.go.id', 'Statistisi Ahli Pertama'),
(340060595, 199902192022012001, 'Disya Pratistaning Ratriatmaja, S.Tr.Stat.', 'disya.pratista@bps.go.id', 'Pranata Komputer Ahli Pertama'),
(340061763, 200007192023022003, 'Erisa, S.Tr.Stat.', 'erisa@bps.go.id', 'Statistisi Ahli Pertama'),
(340061959, 200110082023022003, 'Nafisa Qurrotul Ayuni, S.Tr.Stat.', 'nafisa.ayuni@bps.go.id', 'Statistisi Ahli Pertama'),
(340062861, 198811172024212001, 'Lanna Sari Siregar, A.Md.Kom', 'lannasari-pppk@bps.go.id', 'Pranata SDM Aparatur Terampil'),
(340063226, 200101212024122001, 'Imelda Salsabila, S.Tr.Stat.', 'imelda.salsabila@bps.go.id', 'Pelaksana'),
(340063308, 200006242024121001, 'Maulana Pandudinata, S.Tr.Stat.', 'maulana.pandudinata@bps.go.id', 'Pelaksana');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rinciankegiatan`
--

CREATE TABLE `rinciankegiatan` (
  `id` int(11) NOT NULL,
  `id_mitra` int(11) DEFAULT NULL,
  `kegiatan_id` int(11) DEFAULT NULL,
  `start` varchar(20) DEFAULT NULL,
  `finish` varchar(20) DEFAULT NULL,
  `seksi_id` int(11) DEFAULT NULL,
  `ob` varchar(50) DEFAULT NULL,
  `beban` int(11) DEFAULT NULL,
  `honor` decimal(10,2) DEFAULT NULL,
  `total_honor` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rinciankegiatan`
--

INSERT INTO `rinciankegiatan` (`id`, `id_mitra`, `kegiatan_id`, `start`, `finish`, `seksi_id`, `ob`, `beban`, `honor`, `total_honor`) VALUES
(1, 1, 1, '1748815200', '1748901600', 2, '1', 50, '50000.00', '2500000.00'),
(2, 2, 1, '2025-06-02', '2025-06-03', 2, '1', 50, '50000.00', '2500000.00'),
(3, 3, 1, '2025-06-02', '2025-06-03', 2, '1', 50, '50000.00', '2500000.00'),
(4, 4, 1, '2025-06-02', '2025-06-03', 2, '1', 40, '50000.00', '2000000.00'),
(5, 1, 2, '1748901600', '1749074400', 4, '0', 0, '50000.00', '0.00'),
(6, 1, 3, '1746655200', '1750197600', 5, '2', 30, '50000.00', '1500000.00'),
(7, 5, 3, NULL, NULL, NULL, '2', 50, '50000.00', '2500000.00'),
(8, 2, 3, NULL, NULL, NULL, '1', 1, '1500000.00', '1500000.00'),
(9, 3, 3, NULL, NULL, NULL, '2', 20, '100000.00', '2000000.00'),
(10, 6, 2, NULL, NULL, NULL, '1', 20, '50000.00', '1000000.00'),
(11, 6, 3, NULL, NULL, NULL, '1', 40, '35000.00', '1400000.00'),
(12, 7, 2, NULL, NULL, NULL, '1', 1, '2500000.00', '2500000.00'),
(13, 9, 2, NULL, NULL, NULL, '1', 2, '1500000.00', '3000000.00'),
(14, 15, 2, NULL, NULL, NULL, '2', 20, '150000.00', '3000000.00'),
(15, 11, 2, NULL, NULL, NULL, '1', 30, '30000.00', '900000.00'),
(16, 1, 4, '1748815200', '1749074400', 1, '0', 0, '50000.00', '0.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `seksi`
--

CREATE TABLE `seksi` (
  `id` int(11) NOT NULL,
  `nama` varchar(16) NOT NULL,
  `pjk` varchar(255) DEFAULT NULL,
  `nip_pjk` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `seksi`
--

INSERT INTO `seksi` (`id`, `nama`, `pjk`, `nip_pjk`) VALUES
(1, 'Produksi', 'Hendra Rusmanto, SE', 198510172011011009),
(2, 'Sosial', 'A. Puadi, S.IP', 197201062007011001),
(3, 'Distribusi', 'Eka Julita Irmayanti, S.ST', 199107282014102001),
(4, 'Nerwilis', 'Rina Agustina, S.ST.', 197808292000122001),
(5, 'IPDS', 'Dwi Satria Firmansyah, S.Tr.Stat.', 199803062021041001);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sistempembayaran`
--

CREATE TABLE `sistempembayaran` (
  `id` int(20) NOT NULL,
  `kode` int(20) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sistempembayaran`
--

INSERT INTO `sistempembayaran` (`id`, `kode`, `nama`) VALUES
(1, 1, 'OB'),
(2, 2, 'Selain OB');

-- --------------------------------------------------------

--
-- Struktur dari tabel `subkriteria`
--

CREATE TABLE `subkriteria` (
  `id` int(11) NOT NULL,
  `nilai` int(1) NOT NULL,
  `prioritas` int(11) NOT NULL,
  `deskripsi` varchar(32) NOT NULL,
  `bobot` double NOT NULL,
  `konversi` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `subkriteria`
--

INSERT INTO `subkriteria` (`id`, `nilai`, `prioritas`, `deskripsi`, `bobot`, `konversi`) VALUES
(1, 5, 1, 'Sangat baik sekali', 0.3, 90),
(2, 4, 2, 'Sangat baik', 0.25, 80),
(3, 3, 3, 'Baik', 0.2, 70),
(4, 2, 4, 'Cukup baik', 0.15, 60),
(5, 1, 5, 'Kurang baik', 0.1, 50);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(32) NOT NULL,
  `image` varchar(32) NOT NULL DEFAULT 'default.jpg',
  `password` varchar(128) NOT NULL DEFAULT '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO',
  `role_id` int(1) NOT NULL,
  `seksi_id` int(1) NOT NULL DEFAULT 0,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `date_created` int(11) NOT NULL,
  `token` varchar(128) DEFAULT NULL,
  `date_created_token` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `email`, `image`, `password`, `role_id`, `seksi_id`, `is_active`, `date_created`, `token`, `date_created_token`) VALUES
(107, 'ipds1504@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 3, 5, 1, 1621989189, NULL, NULL),
(108, 'produksi1504@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 3, 1, 1, 1621989189, NULL, NULL),
(109, 'nerwilis1504@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 3, 4, 1, 1621989189, NULL, NULL),
(110, 'sosial1504@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 3, 2, 1, 1621989189, NULL, NULL),
(111, 'distribusi1504@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 3, 3, 1, 1621989189, NULL, NULL),
(112, 'kepala1504@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 2, 0, 1, 1621989189, NULL, NULL),
(127, 'admin1504@gmail.com', 'IMG-20210429-WA0026.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 1, 0, 1, 1621989189, NULL, NULL),
(132, 'imelda.salsabila@bps.go.id', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 4, 0, 1, 1748690072, NULL, NULL),
(133, 'anggitafitri2001@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 5, 0, 1, 1748690140, NULL, NULL),
(134, 'adhewz.eyes@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 5, 0, 1, 1748690144, NULL, NULL),
(135, 'maulana.pandudinata@bps.go.id', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 4, 0, 1, 1748776153, NULL, NULL),
(136, 'satria.firmansyah@bps.go.id', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 4, 0, 1, 1748776158, NULL, NULL),
(137, 'Monicahikzan2475@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 5, 0, 1, 1748776166, NULL, NULL),
(138, 'radjaalfajri25@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 5, 0, 1, 1748776551, NULL, NULL),
(139, 'pratiwiamin85@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 5, 0, 1, 1748776558, NULL, NULL),
(140, 'sugionosabar938@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 5, 0, 1, 1748836265, NULL, NULL),
(141, 'anggitafitri2001@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 4, 0, 1, 1748844590, NULL, NULL),
(142, 'disya.pratista@bps.go.id', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 4, 0, 1, 1749015864, NULL, NULL),
(143, 'muhamadpekhi@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 5, 0, 1, 1749015891, NULL, NULL),
(144, 'ajam32161@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 5, 0, 1, 1749086293, NULL, NULL),
(145, 'Ridhoasshiddiqi11@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 5, 0, 1, 1749086295, NULL, NULL),
(146, 'Mutmainah8804@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 5, 0, 1, 1749086296, NULL, NULL),
(147, 'kurniawanrikki89@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 5, 0, 1, 1749086297, NULL, NULL),
(148, 'rensy.hasibuan@bps.go.id', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 4, 0, 1, 1749240576, NULL, NULL),
(149, 'ajam32161@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 4, 0, 1, 1749240586, NULL, NULL),
(150, 'Ehendarif2@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 5, 0, 1, 1749240596, NULL, NULL),
(151, 'adammalik56074@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 5, 0, 1, 1749240602, NULL, NULL),
(152, 'isyak@bps.go.id', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 4, 0, 1, 1749243536, NULL, NULL),
(153, 'radjaalfajri25@gmail.com', 'default.jpg', '$2y$10$LbxrTcSA4dSZlSnoPWUUoeb7b6xBZD.tE/fsBxydlgn.q6aqV18nO', 4, 0, 1, 1749243542, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_access_menu`
--

CREATE TABLE `user_access_menu` (
  `id` int(11) NOT NULL,
  `role_id` int(1) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_access_menu`
--

INSERT INTO `user_access_menu` (`id`, `role_id`, `menu_id`) VALUES
(11, 1, 6),
(19, 4, 3),
(20, 4, 4),
(24, 5, 4),
(36, 3, 2),
(46, 3, 8),
(48, 1, 1),
(51, 1, 7),
(54, 3, 4),
(55, 3, 5),
(57, 1, 18),
(64, 3, 19),
(65, 4, 19),
(66, 5, 19),
(68, 3, 1),
(69, 2, 1),
(70, 2, 20),
(71, 1, 2),
(73, 1, 4),
(74, 1, 5),
(75, 1, 8),
(76, 1, 19),
(77, 1, 20),
(79, 5, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_menu`
--

CREATE TABLE `user_menu` (
  `id` int(11) NOT NULL,
  `menu` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_menu`
--

INSERT INTO `user_menu` (`id`, `menu`) VALUES
(1, 'Master'),
(2, 'Kegiatan'),
(3, 'Penilaian'),
(4, 'Hasil Penilaian'),
(5, 'History Penilaian'),
(6, 'Admin'),
(7, 'Menu'),
(8, 'Ranking'),
(19, 'Timeline'),
(20, 'Rekap');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `role` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_role`
--

INSERT INTO `user_role` (`id`, `role`) VALUES
(1, 'Admin'),
(2, 'Kepala BPS'),
(3, 'Operator'),
(4, 'Pengawas/Pemeriksa Organik'),
(5, 'Mitra');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_sub_menu`
--

CREATE TABLE `user_sub_menu` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `title` varchar(32) NOT NULL,
  `url` varchar(64) NOT NULL,
  `icon` varchar(32) NOT NULL,
  `is_active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_sub_menu`
--

INSERT INTO `user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon`, `is_active`) VALUES
(1, 6, 'Dashboard', 'admin', 'fas fa-fw fa-tachometer-alt', 1),
(4, 7, 'Menu Management', 'menu', 'fas fa-fw fa-folder', 1),
(5, 7, 'Submenu Management', 'menu/submenu', 'fas fa-fw fa-folder-open', 1),
(9, 6, 'Role', 'admin/role', 'fas fa-fw fa-user-tie', 1),
(12, 1, 'Data Mitra', 'master/mitra', 'fas fa-fw fa-user', 1),
(13, 2, 'Survei', 'kegiatan/survei', 'fas fa-fw fa-book', 1),
(14, 3, 'Isi Penilaian', 'penilaian', 'fas fa-fw fa-pencil-alt', 1),
(15, 4, 'Cetak Hasil Penilaian', 'penilaian/pilihkegiatan', 'fas fa-fw fa-file-pdf', 1),
(16, 5, 'Arsip', 'penilaian/arsip', 'fas fa-fw fa-archive', 1),
(20, 2, 'Sensus', 'kegiatan/sensus', 'fas fa-fw fa-book', 1),
(25, 6, 'All User', 'admin/alluser', 'fas fa-fw fa-user', 1),
(26, 1, 'Data Pegawai', 'master/pegawai', 'fas fa-fw fa-user-tie', 1),
(27, 8, 'Ranking Mitra', 'ranking/pilih_kegiatan_nilai_akhir', 'fas fa-fw fa-graduation-cap', 1),
(28, 8, 'Data Kriteria', 'ranking/kriteria', 'fas fa-fw fa-key', 1),
(29, 8, 'Penghitungan', 'ranking/pilih_kegiatan', 'fas fa-fw fa-pen', 1),
(32, 19, 'Jadwal', 'timeline/index', 'fas fa-fw fa-calendar-alt', 1),
(33, 20, 'Beban Kerja Pegawai', 'rekap/bk_pegawai', 'fas fa-fw fa-file-excel', 1),
(34, 20, 'Beban Kerja Mitra', 'rekap/bk_mitra', 'fas fa-fw fa-file-excel', 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `all_kegiatan_pencacah`
--
ALTER TABLE `all_kegiatan_pencacah`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `all_kegiatan_pengawas`
--
ALTER TABLE `all_kegiatan_pengawas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `all_penilaian`
--
ALTER TABLE `all_penilaian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kode_kecamatan`
--
ALTER TABLE `kode_kecamatan`
  ADD PRIMARY KEY (`kode`);

--
-- Indeks untuk tabel `kode_keldes`
--
ALTER TABLE `kode_keldes`
  ADD PRIMARY KEY (`kode`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mitra`
--
ALTER TABLE `mitra`
  ADD PRIMARY KEY (`id_mitra`);

--
-- Indeks untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_peg`);

--
-- Indeks untuk tabel `rinciankegiatan`
--
ALTER TABLE `rinciankegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `seksi`
--
ALTER TABLE `seksi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sistempembayaran`
--
ALTER TABLE `sistempembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `subkriteria`
--
ALTER TABLE `subkriteria`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user_access_menu`
--
ALTER TABLE `user_access_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user_menu`
--
ALTER TABLE `user_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `all_kegiatan_pencacah`
--
ALTER TABLE `all_kegiatan_pencacah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `all_kegiatan_pengawas`
--
ALTER TABLE `all_kegiatan_pengawas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `all_penilaian`
--
ALTER TABLE `all_penilaian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `mitra`
--
ALTER TABLE `mitra`
  MODIFY `id_mitra` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_peg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=340063310;

--
-- AUTO_INCREMENT untuk tabel `rinciankegiatan`
--
ALTER TABLE `rinciankegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `seksi`
--
ALTER TABLE `seksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `sistempembayaran`
--
ALTER TABLE `sistempembayaran`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `subkriteria`
--
ALTER TABLE `subkriteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT untuk tabel `user_access_menu`
--
ALTER TABLE `user_access_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT untuk tabel `user_menu`
--
ALTER TABLE `user_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

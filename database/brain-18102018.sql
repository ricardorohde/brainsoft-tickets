-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 18-Out-2018 às 18:51
-- Versão do servidor: 10.1.35-MariaDB
-- versão do PHP: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brain`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `administrative_file`
--

CREATE TABLE `administrative_file` (
  `id` int(8) NOT NULL,
  `id_registry` int(8) NOT NULL,
  `path_to_file` varchar(80) COLLATE utf8_bin NOT NULL,
  `status` varchar(7) COLLATE utf8_bin NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiration_date` date NOT NULL,
  `paid_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `administrative_file`
--

INSERT INTO `administrative_file` (`id`, `id_registry`, `path_to_file`, `status`, `registration_date`, `expiration_date`, `paid_date`) VALUES
(1, 1, 'primeirocartorioguarapuava-janeiro.pdf', 'pago', '2017-11-30 19:04:59', '2017-11-30', '2017-11-30'),
(2, 1, 'primeirocartorioguarapuava-fevereiro.pdf', 'pago', '2017-11-30 19:09:00', '2017-12-30', '2017-12-04'),
(3, 1, 'primeirocartorioguarapuava-fevereiro.pdf', 'pago', '2017-11-30 19:11:42', '2017-12-11', '2018-01-04'),
(4, 1, 'primeirocartorioguarapuava-fevereiro.pdf', 'pago', '2017-11-30 19:13:19', '2018-01-10', '2018-01-29'),
(5, 1, 'primeirocartorioguarapuava-janeiro.pdf', 'ativo', '2017-11-30 19:17:34', '0000-00-00', '0000-00-00'),
(6, 5, '1cartoriodemaringa-janeiro.pdf', 'pago', '2017-12-04 13:29:02', '2017-12-12', '2017-12-04'),
(7, 5, '1cartoriodemaringa-fevereiro.pdf', 'pago', '2017-12-04 13:29:02', '2017-11-30', '2017-12-04');

-- --------------------------------------------------------

--
-- Estrutura da tabela `authorization_user_page`
--

CREATE TABLE `authorization_user_page` (
  `id` int(8) NOT NULL,
  `id_user` int(8) NOT NULL,
  `id_page` int(8) NOT NULL,
  `access` varchar(3) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `authorization_user_page`
--

INSERT INTO `authorization_user_page` (`id`, `id_user`, `id_page`, `access`) VALUES
(1, 1, 1, 'yes'),
(2, 1, 2, 'yes'),
(3, 1, 3, 'yes'),
(4, 1, 4, 'yes'),
(5, 1, 5, 'yes'),
(6, 1, 6, 'yes'),
(7, 1, 7, 'yes'),
(8, 1, 8, 'yes'),
(9, 4, 2, 'yes'),
(10, 4, 4, 'yes'),
(11, 4, 5, 'yes'),
(12, 4, 6, 'yes'),
(13, 2, 6, 'yes'),
(14, 2, 2, 'yes'),
(15, 3, 6, 'yes'),
(16, 3, 2, 'yes'),
(17, 8, 2, 'yes'),
(18, 8, 6, 'yes'),
(19, 9, 2, 'yes'),
(20, 9, 6, 'yes'),
(21, 10, 2, 'yes'),
(22, 10, 6, 'yes'),
(23, 7, 2, 'yes'),
(24, 7, 6, 'yes'),
(25, 4, 3, 'yes'),
(26, 3, 3, 'yes'),
(27, 3, 4, 'yes'),
(28, 3, 5, 'yes'),
(29, 2, 3, 'yes'),
(30, 2, 4, 'yes'),
(31, 3, 4, 'yes'),
(32, 3, 3, 'yes'),
(33, 7, 4, 'yes'),
(34, 7, 3, 'yes'),
(35, 8, 4, 'yes'),
(36, 8, 3, 'yes'),
(37, 9, 4, 'yes'),
(38, 9, 3, 'yes'),
(39, 10, 4, 'yes'),
(40, 10, 3, 'yes');

-- --------------------------------------------------------

--
-- Estrutura da tabela `category_module`
--

CREATE TABLE `category_module` (
  `id` int(11) NOT NULL,
  `description` varchar(60) COLLATE utf8_bin NOT NULL,
  `t_group` varchar(6) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `category_module`
--

INSERT INTO `category_module` (`id`, `description`, `t_group`) VALUES
(1, 'Matrícula', 'nivel1'),
(2, 'Transcrição', 'nivel2'),
(3, 'Minuta', 'nivel1'),
(4, 'Condição da Parte', 'nivel1'),
(5, 'Natureza Formal', 'nivel1'),
(6, 'Impressão', 'nivel1'),
(7, 'Modelos de Ficha', 'nivel1'),
(8, 'Scanners', 'nivel1'),
(9, 'Digitalização', 'nivel1'),
(10, 'Impressoras', 'nivel1'),
(11, 'Rede', 'nivel1'),
(12, 'Usuário', 'nivel1'),
(13, 'Atos', 'nivel1'),
(14, 'Financeiro', 'nivel2'),
(15, 'Protocolo', 'nivel1'),
(16, 'Dúvidas', 'nivel1'),
(17, 'IMOB', 'nivel1');

-- --------------------------------------------------------

--
-- Estrutura da tabela `chat`
--

CREATE TABLE `chat` (
  `id` int(8) NOT NULL,
  `id_chat` int(8) NOT NULL,
  `opening_time` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `final_time` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `duration_in_minutes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `chat`
--

INSERT INTO `chat` (`id`, `id_chat`, `opening_time`, `final_time`, `duration_in_minutes`) VALUES
(5, 553050, '2018-10-10 12:20:21', '0000-00-00 00:00:00', 13),
(6, 553058, '2018-10-10 13:18:34', '0000-00-00 00:00:00', 7),
(7, 553098, '2018-10-10 13:44:06', '2018-10-10 14:03:09', 19),
(8, 102, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(9, 553283, '2018-10-15 12:16:42', '2018-10-15 12:35:39', 18),
(10, 553432, '2018-10-17 12:42:30', '0000-00-00 00:00:00', 5),
(11, 553438, '2018-10-17 12:45:01', '0000-00-00 00:00:00', 15),
(12, 553437, '2018-10-17 12:44:23', '2018-10-17 13:06:56', 22),
(13, 553491, '2018-10-17 13:23:24', '0000-00-00 00:00:00', 3),
(14, 553494, '2018-10-17 13:38:55', '0000-00-00 00:00:00', 14),
(15, 553600, '2018-10-17 14:04:12', '0000-00-00 00:00:00', 7),
(16, 103, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(17, 553767, '2018-10-17 16:33:30', '0000-00-00 00:00:00', 2),
(18, 553766, '2018-10-17 16:31:49', '0000-00-00 00:00:00', 7),
(19, 553787, '2018-10-17 16:49:50', '0000-00-00 00:00:00', 18),
(20, 553788, '2018-10-17 16:50:09', '2018-10-17 17:11:49', 21),
(21, 553790, '2018-10-17 16:55:42', '0000-00-00 00:00:00', 20),
(22, 104, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(24, 553868, '2018-10-17 17:13:37', '0000-00-00 00:00:00', 10),
(25, 553867, '2018-10-17 17:13:20', '0000-00-00 00:00:00', 11),
(26, 553912, '2018-10-17 17:34:52', '0000-00-00 00:00:00', 2),
(27, 553910, '2018-10-17 17:30:57', '0000-00-00 00:00:00', 10),
(28, 554085, '2018-10-17 17:54:52', '0000-00-00 00:00:00', 10),
(30, 554131, '2018-10-17 18:41:34', '0000-00-00 00:00:00', 5),
(31, 554346, '2018-10-17 18:54:41', '2018-10-17 19:06:45', 12),
(32, 55555, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(33, 554126, '2018-10-17 18:25:16', '2018-10-17 19:15:38', 50),
(34, 554725, '2018-10-17 19:15:56', '0000-00-00 00:00:00', 8),
(36, 554726, '2018-10-17 19:33:20', '0000-00-00 00:00:00', 17),
(37, 554727, '2018-10-17 19:45:22', '2018-10-17 19:59:11', 13),
(38, 554825, '2018-10-17 20:11:52', '2018-10-17 20:30:34', 18),
(39, 554127, '2018-10-17 18:26:37', '2018-10-17 18:59:45', 33),
(40, 105, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(42, 555034, '2018-10-18 11:35:23', '2018-10-18 11:47:41', 12),
(43, 106, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(44, 555041, '2018-10-18 11:46:25', '2018-10-18 12:09:39', 23),
(45, 555066, '2018-10-18 11:48:31', '2018-10-18 12:06:18', 17),
(46, 555155, '2018-10-18 12:06:47', '2018-10-18 12:25:57', 19),
(47, 555244, '2018-10-18 12:09:51', '2018-10-18 12:42:26', 32),
(48, 555246, '2018-10-18 12:11:00', '2018-10-18 12:52:35', 41),
(49, 555304, '2018-10-18 12:39:29', '0000-00-00 00:00:00', 27),
(50, 555531, '2018-10-18 13:07:43', '0000-00-00 00:00:00', 3),
(51, 555042, '2018-10-18 11:46:55', '2018-10-18 13:37:51', 110),
(52, 555704, '2018-10-18 13:36:54', '0000-00-00 00:00:00', 15),
(53, 555665, '2018-10-18 13:31:34', '2018-10-18 13:57:39', 26),
(54, 107, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1),
(55, 555852, '2018-10-18 14:14:58', '2018-10-18 14:31:28', 16),
(56, 556231, '2018-10-18 16:30:06', '0000-00-00 00:00:00', 20);

-- --------------------------------------------------------

--
-- Estrutura da tabela `city`
--

CREATE TABLE `city` (
  `id` int(8) NOT NULL,
  `description` varchar(250) NOT NULL,
  `id_state` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `city`
--

INSERT INTO `city` (`id`, `description`, `id_state`) VALUES
(1, 'Bom Jesus do Tocantins', 4),
(2, 'Botelhos', 6),
(3, 'Campina Grande do Sul', 1),
(4, 'Cândido de Abreu', 1),
(5, 'Cascavel', 1),
(6, 'Catanduvas', 1),
(7, 'Chopinzinho', 1),
(8, 'Corbélia', 1),
(9, 'Cruzeiro do Oeste', 1),
(10, 'Curitiba', 1),
(11, 'Dois Vizinhos', 1),
(12, 'Faxinal', 1),
(13, 'Francisco Beltrão', 1),
(14, 'Grandes Rios', 1),
(15, 'Rio Negro', 1),
(16, 'Guarapuava', 1),
(17, 'Barracão', 1),
(18, 'Buritis', 3),
(19, 'Colorado do Oeste', 3),
(20, 'Costa Marques', 3),
(21, 'Espigão do Oeste', 3),
(22, 'Ariquemes', 3),
(23, 'Cacoal', 3),
(24, 'Canoas', 2),
(25, 'Dom Feliciano', 2),
(26, 'Camaqã', 2),
(27, 'Cariri do Tocantins', 4),
(28, 'Salto do Lontra', 1),
(29, 'Colinas do Tocantins', 4),
(30, 'Combinado', 4),
(31, 'Cianorte', 1),
(32, 'Foz do Iguaçu', 1),
(33, 'Guaíra', 1),
(34, 'Guaraniaçu', 1),
(35, 'Ibiporã', 1),
(36, 'Icaraína', 1),
(37, 'Imbituva', 1),
(38, 'Ipiranga', 1),
(39, 'Jandaia do Sul', 1),
(40, 'Maringá', 1),
(41, 'Tomazina', 1),
(42, 'Santa Mariana', 1),
(43, 'São Mateus do Sul', 1),
(44, 'Primeiro de Maio', 1),
(45, 'Italva', 9),
(46, 'Ji-Paraná', 3),
(47, 'Cerejeiras', 3),
(48, 'Guajará Mirim', 3),
(49, 'Guaraí', 3),
(50, 'Jaru', 10),
(51, 'Machadinho do Oeste', 3),
(54, 'Farroupilha', 2),
(55, 'Igrejinha', 2),
(56, 'Ijuí', 2),
(57, 'Mostardas', 2),
(58, 'Itacajá', 4),
(59, 'Itapiratins', 4),
(60, 'Bela Vista do Paraíso', 1),
(61, 'Itacajá', 4),
(62, 'Caruaru', 8),
(63, 'Recife', 8),
(64, 'Laranjeiras do Sul', 1),
(65, 'Loanda', 1),
(66, 'Mangueirinha', 1),
(67, 'Manoel Ribas', 1),
(68, 'Marmeleiro', 1),
(69, 'Ortigueira', 1),
(70, 'Pato Branco', 1),
(71, 'Pinhão', 1),
(72, 'Marechal Cândido Rondon', 1),
(73, 'Aurora do TO', 4),
(74, 'Paranaguá', 1),
(75, 'Paranavaí', 1),
(77, 'Gurupi', 1),
(79, 'Nova Brasilândia do Oeste', 3),
(80, 'Ouro Preto do Oeste', 3),
(83, 'Jaguarão', 2),
(84, 'Nova Prata', 2),
(85, 'Palmeira das Missões', 2),
(86, 'Juarina', 4),
(87, 'Lajeado', 4),
(88, 'Palmeirante', 4),
(89, 'Peixe', 4),
(90, 'Mateiros', 4),
(91, 'Gurupi', 4),
(92, 'Palmas', 4),
(93, 'Bernardo Sayão', 4),
(94, 'Formosa', 1),
(95, 'Ponte Alta do Bom Jesus', 4),
(96, 'Formosa do Oeste', 1),
(97, 'Santa Terezinha de Goiás', 5),
(98, 'Abadiânia', 5),
(99, 'São Domingos do Araguaia', 7),
(100, 'Medianeira', 1),
(101, 'Pinhais', 1),
(102, 'Piraquara', 1),
(103, 'Pitanga', 1),
(104, 'Prudentópolis', 1),
(105, 'Reserva', 1),
(106, 'Rio Branco do Sul', 1),
(107, 'Santo Antônio da Platina', 1),
(108, 'Santo Antônio do Sudoeste', 1),
(109, 'Altônia', 1),
(110, 'Porto Velho', 3),
(113, 'Pimenta Bueno', 3),
(114, 'Presidente Medice', 10),
(115, 'Santa Luzia', 3),
(116, 'São Francisco do Guaporé', 3),
(117, 'Alta Floresta do Oeste', 3),
(118, 'Alvorada do Oeste', 3),
(119, 'Piratini', 2),
(120, 'Porto Alegre', 2),
(121, 'Santa Helena', 2),
(122, 'Bom Jesus', 2),
(123, 'Pedro Afonso', 4),
(124, 'Presisdente Kennedy', 4),
(125, 'Pau D\'arco', 4),
(126, 'Rubiataba', 5),
(127, 'Uberaba', 6),
(128, 'Cambuí', 6),
(129, 'Cantagalo', 1),
(130, 'São João', 1),
(131, 'Sengés', 1),
(132, 'Sertanópolis', 1),
(133, 'Siqueira Campos', 1),
(134, 'Telêmaco Borba', 1),
(135, 'União da Vitória', 1),
(136, 'Wenceslau Braz', 1),
(137, 'Xambrê', 1),
(138, 'Antonina', 1),
(139, 'Vilhena', 3),
(140, 'Rolim de Moura', 3),
(141, 'São Miguel do Guaporé', 3),
(142, 'Santa Rosa', 2),
(143, 'São Francisco de Paula', 2),
(144, 'São Jerônimo', 2),
(145, 'Sarandi', 2),
(146, 'Arroio do Ratos', 2),
(147, 'Taguatinga', 4),
(148, 'Tupirama', 4),
(149, 'Araguaçu', 4),
(150, 'Araguaína', 4),
(151, 'Bandeirantes do Tocantins', 4),
(152, 'Santa Fé', 4),
(153, 'Porto Velho', 10),
(154, 'Taguatinga', 11),
(155, 'Vilhena', 10),
(156, 'Cacoal', 10),
(157, 'São João de Araguaia', 7);

-- --------------------------------------------------------

--
-- Estrutura da tabela `client`
--

CREATE TABLE `client` (
  `id` int(8) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(160) NOT NULL,
  `id_credential` int(8) NOT NULL,
  `id_registry` int(8) NOT NULL,
  `id_role` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `client`
--

INSERT INTO `client` (`id`, `name`, `email`, `id_credential`, `id_registry`, `id_role`) VALUES
(1, 'Eliane', 'eliane@cartorio.com.br', 6, 2, 5),
(2, 'Irton José Diesel Junior', 'cripalmeira@gmail.com', 12, 5, 5),
(3, 'Vanessa', 'cribelavista@hotmail.com', 13, 6, 5),
(4, 'André', 'sri.wbraz@gmail.com', 14, 7, 5),
(5, 'Jamille', 'riprudentopolis@hotmail.com', 15, 8, 5),
(6, 'Vania', 'sri.wbraz@gmail.com', 16, 7, 5),
(7, 'Marivone', 'marivonegrisoli@gmail.com', 17, 9, 5),
(8, 'Murilo', 'cri1oficio@hotmail.com', 18, 1, 5),
(9, 'Adriano Lima', 'rcaaluminio@outlook.com', 19, 10, 5),
(10, 'Evanize', 'evanize@hotmail.com', 20, 11, 5),
(11, 'Lucia', 'luciahelena.almeida@hotmail.com', 21, 12, 5),
(12, 'Cinthia', 'rionegro@hotmail.com', 22, 13, 5),
(13, 'Eliane', 'ritomazina@hotmail.com', 23, 14, 5),
(14, 'Rodrigo', 'rodrigo@uberaba.com.br', 24, 15, 5),
(15, 'Alessandra', 'risantamariana@gmail.com', 25, 16, 5),
(16, 'Luciane', '3oficioregistrodeimoveis@gmail.com', 26, 2, 5),
(17, 'Caroline', 'registro.carneiro@bol.com.br', 27, 17, 5),
(18, 'Dayane Santos', 'segundorivilhena@gmail.com', 28, 18, 5),
(19, 'Adriane', 'adriane@hotmail.com', 29, 19, 5),
(20, 'Petterson', 'informatica@ri3.com.br', 30, 20, 5),
(21, 'Adriana', 'adriana@hotmail.com', 31, 21, 5),
(22, 'Marlene', 'marlene@hotmail.com', 32, 21, 5),
(23, 'Audrey', '2sri@hotmail.com.br', 33, 3, 5),
(24, 'Arielle', 'contato@segundoriportovelho.com', 34, 22, 5),
(25, 'Bruno', 'atendimento@ripinhais.com.br', 35, 23, 5),
(26, 'José Eduardo', 'joseeduardo@ripinhais.com.br', 36, 23, 4),
(27, 'Nicolas Matiazi', 'crixambre@gmail.com', 37, 24, 5),
(28, 'Jhonatan Ribeiro de Souza', 'jhonribeirodsouza@hotmail.com', 38, 25, 5),
(29, 'Bruna', 'brunadominguesnunes@gmail.com', 39, 26, 5),
(30, 'Ivana', 'ivanameurer@gmail.com', 40, 3, 5),
(31, 'Richer', ' rsdtorre@ucs.br', 41, 26, 5),
(32, 'Matilde', 'matilde.araujo@hotmail.com', 42, 27, 5),
(33, 'Josias', 'josias@hotmail.com', 43, 28, 5),
(34, 'Vania', 'vania@hotmail.com', 44, 29, 5),
(35, 'Sedineia', 'sedineia@hotmail.com', 45, 31, 5),
(36, 'Fernanda', 'cri.barracao@gmail.com', 46, 30, 5),
(37, 'Kassio', 'oficiomoraes@uol.com.br', 47, 32, 5),
(38, 'Nathalia', 'nathalia@hotmail.com', 48, 33, 5),
(39, 'Joel', 'joel@hotmail.com', 49, 34, 5),
(40, 'Taise', 'taise@hotmail.com', 50, 35, 5),
(41, 'Jakeliny', 'imoveisjaru@gmail.com', 51, 36, 5),
(42, 'Andre Henrique', 'cartorio.sja@hotmail.com', 52, 37, 5),
(43, 'Claudio', 'servregmostardas@gmail.com', 53, 38, 5),
(44, 'Valeria', 'v.lucchetti@uol.com.br', 54, 20, 5),
(45, 'Tuane', 'sri.braz@gmail.com', 55, 7, 5),
(46, 'Andriesi', 'ribomjesus@gmail.com', 56, 39, 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `credential`
--

CREATE TABLE `credential` (
  `id` int(8) NOT NULL,
  `login` varchar(120) NOT NULL,
  `password` text NOT NULL,
  `b_salt` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `credential`
--

INSERT INTO `credential` (`id`, `login`, `password`, `b_salt`) VALUES
(1, 'grcbrain', '0d1b69c834a7cb8fd222090216c1b5dea47700f0686b5003d25a9ccb0f5a0e77', 'FjzoMdOKbohJZOreRNaU'),
(2, 'fernando', 'fff188bb8f03a04944fd5c6469d67e21b43fa2ed744638a5c0ecdc8df5dfcb95', 'uZG95JyvnPfT5BmNUPeN'),
(3, 'rodrigo', 'a21be1a53fb977399428e4e2a4a562b918b23efdf2bbec667b6130c2a363d342', '7l1iq7f60kIsjsV9rKrk'),
(4, 'camila', '02453730b9b95c2e6ef3d8cf4e6c7163196bde74a18f156470c6df0884b3d841', 'UyMkXc1TWIfUZfUKTKXo'),
(5, 'teste', '0d0b162df78f783735988f6ea45f0ffc5d44c21dc69774002c9cac4b3909b97d', 'sRipILmtT2v9Ga7Z27nt'),
(6, 'eliane', 'dc295267dbb5556654c0fd9ae93376a1cea381f00ddc456aaf2fa85116aeeeaa', 'BOATior3Q7SWFaMR6nSZ'),
(7, 'alex', '84c8e4546e39fefad96cf582bc06ae299f044f8e8cb444e7bb5982b210546042', 'HpaniUPW8H0U2tyTuvvX'),
(8, 'eduardo', 'a1274da86ca3d4bcbb6a3240edb5a653105c9bb92f8df5ec690b68b008116ac6', 'ysBLkt7fo0v2OfyYehJ7'),
(9, 'filipe', 'a86d3c4b1ffb94d0eb6461e305001a9d3f15903c87f7c71c7ee7487983eb7578', 'CQjapxEocMsaJPNuZmt2'),
(10, 'rafael', '241fd2269ef9d44eba3ee8b616be203064c0c3c80a8305efa94643a2de61de7c', 'HltbeLQ5uL2a8mIaN7gi'),
(11, 'alcindo', '1aad55ef3ae70f2148a8450c827599e2d9af6ff11d0ef079dc70e7ed21d8d78d', 'tiruce6pSaddweisnoGZ'),
(12, 'Irton', '5b48a2583522b0bf157c7195edabbfd0b148a95223447f55663484f7fa72c350', 'aHNQttHWm8cUOrYwv4wr'),
(13, 'Vanessa', '32cab0c0fb9dbe124f6d811aa51befa95600905243caadfe21abb57c8c316d84', 'fS5yFb5XcQbnaDH9bsat'),
(14, 'André', '486be2a5ad2f1f8d2a181ced9e0d15a62774bd1f2ecb48c36783a089f97402bb', 'KNM5IvOiqnssfFGIpMrG'),
(15, 'Jamille', '1860d8ea91e1851bfeadbcbcca1f4fca43b3eea9e4eb796d7f086fe6f5b0fea8', 'WMimGZITLyvbOiAycdm8'),
(16, 'vania', 'b0a8f4d370bc38e5c38f819fbf35f2799ccc3cb46ef07c1480349e3728656a29', 'uGJ1JdLmmV4rys4MsYJf'),
(17, 'marivone', '02177522c5802dd0d3de05e8447f08ef071c846a2ae8657d74365638c7c52700', 'r2ifUqEAEosQy5FrfMNs'),
(18, 'murilo', '24fedc2c1578e8a753887d135044fdb59b26355d5fddc45bea46dbfa7b349858', 'coQnWWIYxQ9RC2VduUx8'),
(19, 'adriano', '6cbe9a62873f0f4a42007149bf9492394a4e5cb3515e8570a0a19608dd631c1c', 'rc3EsyIa4UxglpRtPQJu'),
(20, 'evanize', '83a130a2263de53493c1cb88251f4e66fb4e4f711ef604a37b7438cb70fc3336', 'PPufDDAtvnPk5uWJbxvi'),
(21, 'lucia', '4777ad7f071354b4f05f8a6cf1117f33a79cc09a31fcb1ef815b5e557edc0720', 'fTQ95zrYfJEsz2tCZeSv'),
(22, 'cinthia', '05f97b626f24cefdbaec9ece7335fccde755a78811d427097b0ff8a77ab16eba', 'okot72rzDlmwis7DtefJ'),
(23, 'elianeT', '410ea68c4229fa7414503293de5d790cf6d5b6e5d84749893af92e181286bc06', 'VO4w9WLQRjff0Kkp7Fmd'),
(24, 'rodrigoUberaba', '5912f2d60e611965d977659d90da7205a7afc1f8cdb196eba5aa231a15b34571', 'lKiZpQ5Tr8Tm6Xa7aqTv'),
(25, 'alessandra', '465f31bfd610424923a4c12e1b1cd6453bd5efc5bd4969b09134060412243782', 'nSeoETnIGsVraf4C8iOi'),
(26, 'luciane', 'de15da57d1e0821ce17601d53e59c8f83f554ab780cf59bec30bba27e6533da4', 'nhGbifdYpXuTYK1qFCJG'),
(27, 'caroline', 'f724ed998d508d3d7661c6044f34e57fbbff9844799377b8b05dbba4179676a5', 'tfYrYJpDlYREGJjOt2W3'),
(28, 'dayane', 'da93975649551bb0ebccfdbf45aedd44be916f03253b00dedea8f9840d322f92', 'qGsoZ2WkknrSxSil6Woa'),
(29, 'adriane', 'c631764b246396930c2f760ad6e2f3fd309f0fd981d32efe1897120242208fc3', 'rufUO2EVNtJ4b46njqkL'),
(30, 'petterson', 'e42d8ba386d1a6f67915d97c07d8288810894196cea7ca856ed89c4bad491f9d', 'tMXn1ypOKUag1s3zzhh7'),
(31, 'adriana', '4c3c3787c0f11842f4d5f2727d424b761d049abf75e561ae00632613c2e04bdd', 'ZhqniknlIBpd8OCxRID0'),
(32, 'marlene', 'b7411fb81be763a324061675454d9ef040e32e70c769731dbb6610c26b2d193e', 'vBbUIbDNEgYisbgs2Vlu'),
(33, 'audrey', 'd581b0bf340000fd4b1ce842c74955212506d1bff0799884ca8234ef6ee49065', 'iPO23GnrVmsba2virnUl'),
(34, 'arielle', '8ed818b94a5cb36df60ee203949609c32b7b7b4292d11854788c408c3bfbb2ba', 'MJTNhNWDmtnoUa06mbWK'),
(35, 'bruno', 'c9b28a52ed326cc41fe865690cf75b8390801cc97ef295237b44a8243b6e4f98', 'VriFBiAZSSHraVCyFrod'),
(36, 'joseEduardo', '50e8e9ae103c32dc1de0fe10ad67b4ce2f8b02a4a7d3454c7905040bde9b5392', 'Tl67c0tiCFvPXELi2iBX'),
(37, 'nicolas', '56bc577de60af74b0888d9fc9d8ed95c135cce9544752407af45ae979aa9e189', 'A8tryxoErhcLawrS7cMk'),
(38, 'jhonatan', 'adf84d4893e174d9d2b6fa31ca6b4c400cc2e7371230a8e8c5ef443d9e1a022a', 'Dkybesm8TluXUEnNnowb'),
(39, 'bruna', '2b9d1f9224703a9c06ed26c366fd2b30abea6773fe5f0cc386b6c17e52e8f0b0', 'SErYqTF8n5kIDguZuovs'),
(40, 'ivana', '83d046719f5528a8a5e83d9a707039ed1638b9e5fce2db7490071b0f1f3a2da3', '1XNs8pgq53sjDuro3no1'),
(41, 'richer', '72ba590e232ff048b2988cbd6ea58c09ea37416ff4ceaac6b7373b9611cdcdd5', 'VrCxlFzajyuamN5Z4Chr'),
(42, 'matilde', '9b422ddc95ea805b3fc8cac85f25e1691ce17fa0456512f74d0fa9a3816a40c4', 'Zbs1jnougTsvnzUnx8rB'),
(43, 'josias', '15f3a0c61e4331f0776fed3c4cedb05af76d656c6571f535bc3ae6275d1ac2fc', 'oWmfqrXNL1oVslg0jTYn'),
(44, 'vaniaAraguaçu', 'df914816b520431883c034e01dadaa18120d6201dce355a95cefb8842ae751ae', 'JkjX4cIn7S2Gc3Vsfd4d'),
(45, 'sedineia', '909088e5386a6138650e6fc2bdf83e3393aabac0d8cfa5bc3fd94b0f3b2ba334', 'ARwwFsxbAoyHChopWNMX'),
(46, 'fernanda', '98433f9e7e15e4ae80144ad9b71e06f74174ee4f897d007d74d97f08f3e81fe7', '6oufc0kgntoXB9tJYc4n'),
(47, 'kassio', '309af9c418b800d256f370f729a3a2aa32bc20b96db8e0c81b74808010d1b681', 'aWYOtTUUVj1hCkoynopn'),
(48, 'nathalia', 'a0eee041799b4b975658b0c65d46b2c26e89e478f222e8113b71322de231cdaa', 'G6LZD2ixA4UlorVZOuAn'),
(49, 'joel', '5d15209b2227a9da3eab42abf96eb10ea389d8ea66ce74a4c69c36986eee4635', 'mz95wal5p6wCnIZ4vjiL'),
(50, 'thaise', '0b77142c96f4baaf13ae24f78f37b90fc8b7e15c7d7144c44af37fb1ad33dcad', 'YRFAr46Cm2jOFZcbN2vl'),
(51, 'jakeliny', '042673a8827c44a825efa18d51f908eca7d429831afc803b8d4bedadb28d3695', 'Mud2sTbvo4ovf8fko68h'),
(52, 'andreHenrique', '47317060da5de9d5734c6cd5cc8423eb18dd65ed4fb09efedcbf61b5bf299fe0', 'tB6tgN10iosIxXaFqSfa'),
(53, 'rodrigomostardas', '9a76eaf1fd8af7f756e4f2eae47b81c7b033bd7cca355213585d2ec3bb9fd90f', '5r9TzEWWMLaojgatoayQ'),
(54, 'valeria', 'cdc1d2a99e6d5ef7c7b8108ee05301f02d8bf8df15e3bbda22a7fda447404037', '4ts3VMSncsvNmR3My3av'),
(55, 'Tuane', '1c187fb0943644ada95f39813cb9e1d6bbbce10bbc6db4842d1283671a96e0fd', 'a85SLqhzWeWVysc9tDdF'),
(56, 'andriesi', 'ff7877331216308c389585c24f46f4e63276a1a2e57f1bf6eaefab645fd019bb', 'STUz6ifZh8mjukRRiSNf');

-- --------------------------------------------------------

--
-- Estrutura da tabela `employee`
--

CREATE TABLE `employee` (
  `id` int(8) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(60) NOT NULL,
  `on_chat` varchar(3) DEFAULT 'no',
  `t_group` varchar(7) NOT NULL,
  `id_credential` int(8) NOT NULL,
  `id_role` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `employee`
--

INSERT INTO `employee` (`id`, `name`, `email`, `on_chat`, `t_group`, `id_credential`, `id_role`) VALUES
(1, 'Camila Bortolanza', 'camila@brainsoftsistemas.com.br', 'no', 'Selecio', 4, 3),
(2, 'Rodrigo Santi', 'rodrigo@brainsoftsistemas.com.br', 'yes', 'nivel1', 3, 2),
(3, 'Guilherme Ribas Carneiro', 'guilherme.rcarneiro@gmail.com', 'yes', 'develop', 1, 1),
(4, 'Alex Wargenhak', 'alex@brainsoftsistemas.com.br', 'yes', 'nivel1', 7, 2),
(5, 'Fernando Pontarolo', 'fernando@brainsoftsistemas.com.br', 'yes', 'nivel2', 2, 2),
(6, 'Eduardo Ishida', 'eduardo@brainsoftsistemas.com.br', 'yes', 'nivel2', 8, 2),
(7, 'Filipe Kuhn', 'filipe@brainsoftsistemas.com.br', 'yes', 'nivel2', 9, 2),
(8, 'Rafael Galego', 'rafael@brainsoftsistemas.com.br', 'no', 'nivel2', 10, 2),
(9, 'Alcindo Almeida', 'alcindo@brainsoftsistemas.com.br', 'yes', 'Selecio', 11, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `area` varchar(20) COLLATE utf8_bin NOT NULL,
  `content` varchar(60) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `who_did` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura da tabela `notification`
--

CREATE TABLE `notification` (
  `id` int(8) NOT NULL,
  `description` varchar(250) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `notification`
--

INSERT INTO `notification` (`id`, `description`, `date`) VALUES
(1, 'Boleto Atrasado', '2018-02-19 08:47:52');

-- --------------------------------------------------------

--
-- Estrutura da tabela `page`
--

CREATE TABLE `page` (
  `id` int(8) NOT NULL,
  `name` varchar(120) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `page`
--

INSERT INTO `page` (`id`, `name`) VALUES
(1, 'Boletos'),
(2, 'Tickets'),
(3, 'Usuarios'),
(4, 'Cartórios'),
(5, 'Cadastros'),
(6, 'FilaInterna'),
(7, 'Autorizações'),
(8, 'Relatorios');

-- --------------------------------------------------------

--
-- Estrutura da tabela `registry`
--

CREATE TABLE `registry` (
  `id` int(8) NOT NULL,
  `name` varchar(120) NOT NULL,
  `id_city` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `registry`
--

INSERT INTO `registry` (`id`, `name`, `id_city`) VALUES
(1, '1 de Guarapuava', 16),
(2, '3 de Guarapuava', 16),
(3, '2 de Guarapuava', 16),
(4, 'Piratini', 119),
(5, 'Palmeira das Missões', 85),
(6, 'Bela Vista do Paraíso', 60),
(7, 'Wenceslau Braz', 136),
(8, 'Prudentópolis', 104),
(9, '1 de Presidente Médici', 114),
(10, '1 de Porto Velho', 153),
(11, 'Registro de Ibiporã', 35),
(12, 'Cartório de Taguatinga', 154),
(13, 'RI de Rio Negro', 15),
(14, 'RI de Tomazina', 41),
(15, '1 de Uberaba', 127),
(16, 'RI de Santa Mariana', 42),
(17, '2 de Francisco Beltrão', 13),
(18, '2 de Vilhena', 155),
(19, 'RI de Marmeleiro', 68),
(20, 'RI de Porto Alegre', 120),
(21, 'RI de Cascavel', 5),
(22, '2 de Porto Velho', 153),
(23, 'RI de Pinhais', 101),
(24, 'RI de Xâmbre', 137),
(25, 'RI de Laranjeiras do Sul', 64),
(26, '2 de Cacoal', 156),
(27, 'RI de Jaguarão', 83),
(28, 'RI de Sengés', 131),
(29, 'RI de Araguaçu', 149),
(30, 'RI Barracão', 17),
(31, '1 de Franciso Beltrão', 13),
(32, 'RI Araguaína', 150),
(33, 'RI de Peixe', 89),
(34, 'RI de Bandeirantes do Tocantins', 151),
(35, 'RI de Dom Feliciano', 25),
(36, 'RI de Jaru', 50),
(37, 'RI de São João de Araguaia', 157),
(38, 'RI Mostardas', 57),
(39, 'RI de Bom Jesus', 122);

-- --------------------------------------------------------

--
-- Estrutura da tabela `role`
--

CREATE TABLE `role` (
  `id` int(8) NOT NULL,
  `description` varchar(160) NOT NULL,
  `type` int(1) NOT NULL,
  `status` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `role`
--

INSERT INTO `role` (`id`, `description`, `type`, `status`) VALUES
(1, 'adm', 0, 'ativo'),
(2, 'supportBrain', 0, 'ativo'),
(3, 'Atendimento', 0, 'ativo'),
(4, 'Oficial', 1, 'ativo'),
(5, 'Atendente', 1, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `state`
--

CREATE TABLE `state` (
  `id` int(8) NOT NULL,
  `description` varchar(80) NOT NULL,
  `initials` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `state`
--

INSERT INTO `state` (`id`, `description`, `initials`) VALUES
(1, 'Paraná', 'PR'),
(2, 'Rio Grande do Sul', 'RS'),
(3, 'Roraima', 'RR'),
(4, 'Tocantins', 'TO'),
(5, 'Goiás', 'GO'),
(6, 'Minas Gerais', 'MG'),
(7, 'Pará', 'PA'),
(8, 'Pernambuco', 'PE'),
(9, 'Rio de Janeiro', 'RJ'),
(10, 'Rondonia', 'RO'),
(11, 'Distrito Federal', 'DF');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ticket`
--

CREATE TABLE `ticket` (
  `id` int(11) NOT NULL,
  `id_registry` int(8) NOT NULL,
  `id_client` int(8) NOT NULL,
  `priority` varchar(40) NOT NULL,
  `t_status` varchar(40) NOT NULL,
  `source` varchar(40) NOT NULL,
  `type` varchar(40) NOT NULL,
  `t_group` varchar(40) NOT NULL,
  `id_module` int(8) DEFAULT NULL,
  `id_attendant` int(8) DEFAULT NULL,
  `resolution` text,
  `id_who_opened` int(8) DEFAULT NULL,
  `id_who_closed` int(8) DEFAULT NULL,
  `id_chat` int(8) NOT NULL,
  `is_repeated` int(1) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `finalized_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `ticket`
--

INSERT INTO `ticket` (`id`, `id_registry`, `id_client`, `priority`, `t_status`, `source`, `type`, `t_group`, `id_module`, `id_attendant`, `resolution`, `id_who_opened`, `id_who_closed`, `id_chat`, `is_repeated`, `registered_at`, `finalized_at`) VALUES
(7, 6, 3, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 13, 4, 'criado minuta e alterado variável cabeçalho de ato', 4, 7, 5, 0, '2018-10-10 12:34:14', '2018-10-10 13:48:01'),
(8, 7, 4, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 14, 2, 'Revertido Situação de Protocolo', 4, 3, 6, 0, '2018-10-10 13:26:49', '2018-10-10 13:37:11'),
(9, 8, 5, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 13, 4, 'criado minuta', 4, 7, 7, 0, '2018-10-10 13:54:44', '2018-10-10 14:04:48'),
(10, 6, 3, 'baixa', 'solucionado', 'telefone', 'solicitacao', 'nivel1', 13, 4, 'alterado variável cabeçalho', 4, 7, 8, 0, '2018-10-10 14:12:11', '2018-10-10 14:40:35'),
(11, 7, 6, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 15, 2, 'Associado', 3, 3, 9, 0, '2018-10-15 13:37:33', '2018-10-15 13:37:53'),
(12, 9, 7, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 1, 4, 'desregistrado atos', 7, 7, 10, 0, '2018-10-17 12:52:37', '2018-10-17 13:01:59'),
(13, 1, 8, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 13, 4, 'desregistrado atos', 7, 7, 11, 0, '2018-10-17 13:01:32', '2018-10-17 13:44:02'),
(14, 10, 9, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel2', 4, 7, 'Criado natureza formal e tipo de serviço.\r\n', 1, 9, 12, 0, '2018-10-17 13:12:17', '2018-10-17 13:19:15'),
(15, 11, 10, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel2', 13, 6, 'Chat iniciado em 17/10/2018 às 10:23:34\r\nIP: 201.54.117.202 em 172.68.25.33\r\nTempo de Espera: 00:00:10\r\nDepartamento solicitado Camila\r\nRodrigo (10:23:34):\r\nOlá Evanize, em que posso ajudar?\r\nEvanize (10:23:59):\r\nolá\r\nRodrigo (10:24:00):\r\nBom dia Evanize, tudo bem?\r\nEvanize (10:24:19):\r\ntudo bom\r\nEvanize (10:24:38):\r\nentão gostaria de incluir uma minuta no registro auxiliar\r\nEvanize (10:24:54):\r\nretificação de dados pessoais\r\nRodrigo (10:26:10):\r\ncerto, vou passar para o Eduardo ele já cria pra você\r\nChat transferido para o departamento [Eduardo]\r\nAtendido por [Eduardo I.]\r\nEduardo I. (10:26:54):\r\nbom dia Evanize\r\nEvanize (10:28:03):\r\nboa dia Eduardo\r\nEduardo I. (10:28:47):\r\ntem o protocolo e o texto?\r\nEvanize (10:31:16):\r\nsim\r\nEvanize (10:31:23):\r\n78740\r\nEvanize (10:31:42):\r\ntexto está aberto\r\nEduardo I. (10:32:12):\r\nme passa os dados do teamviewer por gentileza\r\nEvanize (10:32:32):\r\n309 650 090\r\nEvanize (10:32:35):\r\n8191\r\nMensagem automática (10:35:55):\r\nEsse chat está há mais de 3 minutos sem interação de ambas as partes.\r\n\r\nMensagem automática (10:38:55):\r\nEsse chat está há mais de 6 minutos sem interação de ambas as partes.\r\n\r\nEvanize (10:39:24):\r\nprontinho Evanize\r\nEvanize (10:39:35):\r\nem que mais posso ajudar?\r\nEvanize (10:39:54):\r\nsomente obrigado Eduardo I\r\nEduardo I. (10:40:02):\r\nPor nada\r\nEduardo I. (10:40:07):\r\num ótimo dia!\r\nEvanize (10:40:19):\r\npara vc tambem,\r\nEduardo I. (10:40:24):\r\nObrigado', 8, 8, 13, 0, '2018-10-17 13:27:57', '2018-10-17 13:41:28'),
(17, 12, 11, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 16, 4, 'configurado impressora', 1, 7, 14, 0, '2018-10-17 13:53:48', '2018-10-17 14:33:07'),
(18, 14, 13, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel2', 12, 5, 'Gerado novamente o arquivo CRI.', 2, 2, 15, 0, '2018-10-17 14:11:45', '2018-10-17 14:30:16'),
(19, 15, 14, 'baixa', 'solucionado', 'telefone', 'solicitacao', 'nivel2', 1, 5, 'Liberado transação no banco de dados.', 2, 2, 16, 0, '2018-10-17 14:35:05', '2018-10-17 14:35:43'),
(20, 1, 8, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 3, 2, 'Alterado caminho do IP', 1, 3, 17, 0, '2018-10-17 16:36:17', '2018-10-17 16:43:30'),
(21, 16, 15, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 1, 4, 'dúvidas sobre requisição de selos em certidão de inteiro teor de registro de matrícula', 1, 7, 18, 0, '2018-10-17 16:39:23', '2018-10-17 17:18:32'),
(22, 2, 16, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel2', 3, 5, 'Alterado norma de tipo e serviço de encerramento de matrícula.', 2, 2, 19, 0, '2018-10-17 17:09:17', '2018-10-17 17:10:15'),
(23, 17, 17, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 8, 2, 'Dado Permissão para unificar pessoa', 3, 3, 20, 0, '2018-10-17 17:12:55', '2018-10-17 17:13:05'),
(24, 12, 11, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 16, 4, 'dúvidas', 1, 7, 21, 0, '2018-10-17 17:16:40', '2018-10-17 17:35:42'),
(25, 19, 19, 'baixa', 'solucionado', 'telefone', 'solicitacao', 'nivel2', 12, 7, 'Alterado filtro de Relatório.\r\n', 9, 9, 22, 0, '2018-10-17 17:21:44', '2018-10-17 17:22:07'),
(27, 20, 20, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel2', 12, 5, 'Erro no exportador de processo, mas não teve erro depois que conferimos.', 2, 2, 24, 0, '2018-10-17 17:24:21', '2018-10-17 17:50:31'),
(28, 18, 18, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 1, 4, 'desregistrado atos', 7, 7, 25, 0, '2018-10-17 17:25:37', '2018-10-17 17:54:36'),
(29, 21, 21, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 1, 2, 'Desregistrado ato', 1, 3, 26, 0, '2018-10-17 17:38:02', '2018-10-17 17:42:01'),
(30, 11, 10, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 13, 4, 'criado tipo de serviço', 7, 7, 27, 0, '2018-10-17 17:43:22', '2018-10-17 18:00:48'),
(31, 3, 23, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel2', 15, 5, 'Criado tipo de serviço, minuta , e ligado para cliente para tirar dúvida sobre ato.', 2, 2, 28, 0, '2018-10-17 18:05:28', '2018-10-17 19:16:47'),
(33, 24, 27, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 18, 4, 'Dúvidas sobre a exportação das imagens para a central eletrônica', 7, 7, 30, 0, '2018-10-17 18:56:55', '2018-10-17 19:11:08'),
(34, 25, 28, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 17, 4, 'Excluído ato de matrícula', 7, 7, 31, 0, '2018-10-17 19:00:40', '2018-10-17 19:07:42'),
(36, 22, 24, 'baixa', 'fechado', 'chat', 'solicitacao', 'nivel2', 12, 7, 'Criado Composição de custas, norma de cálculo e tipo de serviço para cobrança de Custas Postergadas, onde o usuário insere o valor dos emolumentos e do selo para cobrar por exemplo uma custa postergada de Penhora.', 9, 9, 33, 0, '2018-10-17 19:16:56', '2018-10-17 19:17:06'),
(37, 26, 29, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel2', 2, 5, 'Alterado tipo de certidão de inteiro teor, era do tipo de inteiro teor , para tipo de ônus.', 1, 2, 34, 0, '2018-10-17 19:24:55', '2018-10-17 19:57:54'),
(39, 26, 31, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 13, 4, 'minuta', 1, 7, 36, 0, '2018-10-17 19:51:29', '2018-10-17 20:36:39'),
(40, 3, 30, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 1, 4, '', 1, NULL, 37, 0, '2018-10-17 19:51:59', '0000-00-00 00:00:00'),
(41, 19, 19, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel2', 12, 6, '', 8, 6, 38, 0, '2018-10-17 20:24:53', '2018-10-17 20:10:00'),
(42, 23, 25, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel2', 2, 6, '', 1, 6, 39, 0, '2018-10-17 20:28:35', '2018-10-17 20:11:17'),
(43, 28, 33, 'baixa', 'solucionado', 'telefone', 'solicitacao', 'nivel2', 1, 5, 'Instalado imob em terminais', 2, 2, 40, 0, '2018-10-18 11:30:00', '2018-10-18 13:10:18'),
(45, 29, 34, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 19, 4, 'reiniciado servidor postgres', 7, 7, 42, 0, '2018-10-18 11:54:29', '2018-10-18 11:55:04'),
(46, 15, 14, 'baixa', 'solucionado', 'telefone', 'solicitacao', 'nivel2', 12, 6, 'Passado para o Alcindo os dados levantados em relação ao erro nas custas de Uberaba. Precisa ver com o Norberto agora para corrigir o arredondamento que mostra na tela, somando os valores individuais não bate com o total mostrado na tela.', 8, 8, 43, 0, '2018-10-18 12:04:19', '2018-10-18 14:27:42'),
(47, 31, 35, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel2', 18, 5, 'Passado lista de Clientes do Paraná.', 2, 2, 44, 0, '2018-10-18 12:17:53', '2018-10-18 13:05:46'),
(48, 30, 36, 'baixa', 'solucionado', 'chat', 'problema', 'nivel2', 2, 7, 'Liberado transação...', 9, 9, 45, 0, '2018-10-18 12:25:51', '2018-10-18 12:26:07'),
(49, 32, 37, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel2', 2, 7, 'Atualizado XML e enviado novamente exportação.', 9, 9, 46, 0, '2018-10-18 12:28:29', '2018-10-18 12:36:22'),
(50, 33, 38, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 1, 2, 'Desregistrado', 3, 3, 47, 0, '2018-10-18 12:46:45', '2018-10-18 12:47:00'),
(51, 37, 42, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel1', 16, 4, 'não foi configurado a impressora', 7, 7, 48, 0, '2018-10-18 12:53:56', '2018-10-18 14:50:21'),
(52, 38, 43, 'baixa', 'solucionado', 'chat', 'configuracao', 'nivel2', 2, 7, 'Configurado Token de envio do indicador pessoal do CRI-RS', 9, 9, 49, 0, '2018-10-18 13:06:59', '2018-10-18 13:42:48'),
(53, 29, 34, 'baixa', 'aberto', 'chat', 'solicitacao', 'nivel2', 12, 5, '', 2, NULL, 50, 0, '2018-10-18 13:11:42', '0000-00-00 00:00:00'),
(54, 34, 39, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel2', 13, 6, '', 8, 8, 51, 0, '2018-10-18 13:45:11', '2018-10-18 14:27:19'),
(55, 20, 44, 'alta', 'solucionado', 'chat', 'solicitacao', 'nivel2', 12, 7, 'Criado pendência 3425 e 3426 para alteração de Recibo e Relatório de Caixa.', 9, 9, 52, 0, '2018-10-18 13:53:33', '2018-10-18 14:19:22'),
(56, 11, 10, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel2', 13, 6, 'explicado para o Haroldo como usar a minuta de desmembramento', 8, 8, 53, 0, '2018-10-18 14:00:51', '2018-10-18 14:27:52'),
(57, 15, 14, 'baixa', 'solucionado', 'telefone', 'solicitacao', 'nivel2', 12, 7, 'Feito nova exportação do CORI.', 9, 9, 54, 0, '2018-10-18 14:28:03', '2018-10-18 16:14:13'),
(58, 7, 45, 'baixa', 'solucionado', 'chat', 'solicitacao', 'nivel2', 1, 6, '', 8, 8, 55, 0, '2018-10-18 14:35:02', '2018-10-18 14:35:28'),
(59, 39, 46, 'baixa', 'aberto', 'chat', 'solicitacao', 'nivel2', 2, 7, '', 9, NULL, 56, 0, '2018-10-18 16:51:38', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ticket_module`
--

CREATE TABLE `ticket_module` (
  `id` int(8) NOT NULL,
  `description` varchar(60) NOT NULL,
  `id_category` int(11) NOT NULL,
  `limit_time` int(3) NOT NULL,
  `status` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `ticket_module`
--

INSERT INTO `ticket_module` (`id`, `description`, `id_category`, `limit_time`, `status`) VALUES
(1, 'Desregistrar', 1, 10, 'ativo'),
(2, 'Desregistrar', 2, 10, 'ativo'),
(3, 'Registrar', 1, 10, 'ativo'),
(4, 'Criação', 5, 10, 'ativo'),
(5, 'Associação', 5, 10, 'ativo'),
(6, 'Impressão', 7, 10, 'ativo'),
(7, 'Digitalização', 7, 10, 'ativo'),
(8, 'Mover', 13, 10, 'ativo'),
(9, 'Alterar Data', 13, 10, 'ativo'),
(10, 'Mudar Númeração', 13, 10, 'ativo'),
(11, 'Excluir', 13, 10, 'inativo'),
(12, 'Relatório ', 14, 30, 'ativo'),
(13, 'Geral', 3, 30, 'ativo'),
(14, 'Reverter Situação ', 15, 30, 'ativo'),
(15, 'Associar', 15, 20, 'ativo'),
(16, 'Geral', 10, 25, 'ativo'),
(17, 'Excluir', 13, 25, 'ativo'),
(18, 'Geral', 16, 20, 'ativo'),
(19, 'Falha ao abrir conexão', 11, 25, 'ativo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `user_notification`
--

CREATE TABLE `user_notification` (
  `id` int(8) NOT NULL,
  `id_user` int(8) NOT NULL,
  `id_notification` int(8) NOT NULL,
  `status` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrative_file`
--
ALTER TABLE `administrative_file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_adm_registry` (`id_registry`);

--
-- Indexes for table `authorization_user_page`
--
ALTER TABLE `authorization_user_page`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_authorization_credential` (`id_user`),
  ADD KEY `fk_authorization_page` (`id_page`);

--
-- Indexes for table `category_module`
--
ALTER TABLE `category_module`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_state` (`id_state`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_credential` (`id_credential`),
  ADD KEY `fk_user_registry` (`id_registry`),
  ADD KEY `fk_user_role` (`id_role`);

--
-- Indexes for table `credential`
--
ALTER TABLE `credential`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_employee_role` (`id_role`),
  ADD KEY `fk_employee_credential` (`id_credential`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_log_employee` (`who_did`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registry`
--
ALTER TABLE `registry`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_city` (`id_city`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ticket_module` (`id_module`),
  ADD KEY `fk_ticket_employee` (`id_attendant`),
  ADD KEY `fk_ticket_who_opened` (`id_who_opened`),
  ADD KEY `fk_ticket_registry` (`id_registry`),
  ADD KEY `fk_ticket_client` (`id_client`),
  ADD KEY `fk_ticket_chat` (`id_chat`),
  ADD KEY `fk_ticket_who_closed` (`id_who_closed`);

--
-- Indexes for table `ticket_module`
--
ALTER TABLE `ticket_module`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ticket_module_category` (`id_category`);

--
-- Indexes for table `user_notification`
--
ALTER TABLE `user_notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_u_n_notification` (`id_notification`),
  ADD KEY `fk_u_n_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrative_file`
--
ALTER TABLE `administrative_file`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `authorization_user_page`
--
ALTER TABLE `authorization_user_page`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `category_module`
--
ALTER TABLE `category_module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `credential`
--
ALTER TABLE `credential`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `page`
--
ALTER TABLE `page`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `registry`
--
ALTER TABLE `registry`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `ticket_module`
--
ALTER TABLE `ticket_module`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user_notification`
--
ALTER TABLE `user_notification`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `administrative_file`
--
ALTER TABLE `administrative_file`
  ADD CONSTRAINT `fk_adm_registry` FOREIGN KEY (`id_registry`) REFERENCES `registry` (`id`);

--
-- Limitadores para a tabela `authorization_user_page`
--
ALTER TABLE `authorization_user_page`
  ADD CONSTRAINT `fk_authorization_credential` FOREIGN KEY (`id_user`) REFERENCES `credential` (`id`),
  ADD CONSTRAINT `fk_authorization_page` FOREIGN KEY (`id_page`) REFERENCES `page` (`id`);

--
-- Limitadores para a tabela `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `fk_state` FOREIGN KEY (`id_state`) REFERENCES `state` (`id`);

--
-- Limitadores para a tabela `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `fk_user_credential` FOREIGN KEY (`id_credential`) REFERENCES `credential` (`id`),
  ADD CONSTRAINT `fk_user_registry` FOREIGN KEY (`id_registry`) REFERENCES `registry` (`id`),
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`);

--
-- Limitadores para a tabela `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `fk_employee_credential` FOREIGN KEY (`id_credential`) REFERENCES `credential` (`id`),
  ADD CONSTRAINT `fk_employee_role` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`);

--
-- Limitadores para a tabela `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `fk_log_employee` FOREIGN KEY (`who_did`) REFERENCES `employee` (`id`);

--
-- Limitadores para a tabela `registry`
--
ALTER TABLE `registry`
  ADD CONSTRAINT `registry_ibfk_1` FOREIGN KEY (`id_city`) REFERENCES `city` (`id`);

--
-- Limitadores para a tabela `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `fk_ticket_chat` FOREIGN KEY (`id_chat`) REFERENCES `chat` (`id`),
  ADD CONSTRAINT `fk_ticket_client` FOREIGN KEY (`id_client`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `fk_ticket_employee` FOREIGN KEY (`id_attendant`) REFERENCES `employee` (`id`),
  ADD CONSTRAINT `fk_ticket_module` FOREIGN KEY (`id_module`) REFERENCES `ticket_module` (`id`),
  ADD CONSTRAINT `fk_ticket_registry` FOREIGN KEY (`id_registry`) REFERENCES `registry` (`id`),
  ADD CONSTRAINT `fk_ticket_who_closed` FOREIGN KEY (`id_who_closed`) REFERENCES `employee` (`id`),
  ADD CONSTRAINT `fk_ticket_who_opened` FOREIGN KEY (`id_who_opened`) REFERENCES `credential` (`id`);

--
-- Limitadores para a tabela `ticket_module`
--
ALTER TABLE `ticket_module`
  ADD CONSTRAINT `fk_ticket_module_category` FOREIGN KEY (`id_category`) REFERENCES `category_module` (`id`);

--
-- Limitadores para a tabela `user_notification`
--
ALTER TABLE `user_notification`
  ADD CONSTRAINT `fk_u_n_notification` FOREIGN KEY (`id_notification`) REFERENCES `notification` (`id`),
  ADD CONSTRAINT `fk_u_n_user` FOREIGN KEY (`id_user`) REFERENCES `credential` (`id`);

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `update_status_of_on_chat` ON SCHEDULE EVERY 1 DAY STARTS '2018-10-04 19:00:00' ON COMPLETION NOT PRESERVE ENABLE COMMENT 'Every employees goes to offline' DO UPDATE employee SET on_chat = "no" WHERE on_chat = "yes"$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

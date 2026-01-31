-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 30-Jan-2026 às 21:25
-- Versão do servidor: 5.6.15-log
-- PHP Version: 5.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `medclick`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `agenda`
--

CREATE TABLE IF NOT EXISTS `agenda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `medico_id` int(11) DEFAULT NULL,
  `disponivel` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medico_id` (`medico_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2296 ;

--
-- Extraindo dados da tabela `agenda`
--

INSERT INTO `agenda` (`id`, `data`, `hora`, `medico_id`, `disponivel`) VALUES
(2295, '2025-11-28', '17:00:00', 12, 1),
(2294, '2025-11-28', '16:00:00', 12, 1),
(2293, '2025-11-28', '15:00:00', 12, 1),
(2292, '2025-11-28', '14:00:00', 12, 1),
(2291, '2025-11-28', '13:00:00', 12, 0),
(2290, '2025-11-28', '12:00:00', 12, 1),
(2289, '2025-11-27', '17:00:00', 12, 1),
(2288, '2025-11-27', '16:00:00', 12, 1),
(2287, '2025-11-27', '13:00:00', 12, 1),
(2286, '2025-11-27', '15:00:00', 12, 1),
(2285, '2025-11-27', '14:00:00', 12, 1),
(2284, '2025-11-27', '12:00:00', 12, 0),
(2283, '2025-11-27', '11:00:00', 12, 1),
(2282, '2025-11-27', '10:00:00', 12, 1),
(2281, '2025-11-27', '09:00:00', 12, 1),
(2280, '2025-11-26', '15:00:00', 12, 1),
(2279, '2025-11-26', '14:00:00', 12, 0),
(2278, '2025-11-26', '12:00:00', 12, 1),
(2277, '2025-11-26', '11:00:00', 12, 1),
(2276, '2025-11-26', '10:00:00', 12, 1),
(2275, '2025-11-26', '09:00:00', 12, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `consultas`
--

CREATE TABLE IF NOT EXISTS `consultas` (
  `id_consulta` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(20) NOT NULL DEFAULT 'Agendada',
  `paciente_id` int(11) DEFAULT NULL,
  `nome_paciente_manual` varchar(255) DEFAULT NULL,
  `medico_id` int(11) DEFAULT NULL,
  `agenda_id` int(11) DEFAULT NULL,
  `codigo_confirmacao` varchar(20) DEFAULT NULL,
  `especialidade_id` int(11) NOT NULL,
  `data` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  PRIMARY KEY (`id_consulta`),
  KEY `paciente_id` (`paciente_id`),
  KEY `medico_id` (`medico_id`),
  KEY `agenda_id` (`agenda_id`),
  KEY `fk_consulta_especialidade` (`especialidade_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Extraindo dados da tabela `consultas`
--

INSERT INTO `consultas` (`id_consulta`, `status`, `paciente_id`, `nome_paciente_manual`, `medico_id`, `agenda_id`, `codigo_confirmacao`, `especialidade_id`, `data`, `hora`) VALUES
(12, 'Realizada', 7, NULL, 12, NULL, 'ff725ce1', 3, '2025-09-21', '20:00:00'),
(11, 'Realizada', 7, NULL, 12, NULL, '77ad2d94', 3, '2025-09-20', '19:53:00'),
(10, 'Realizada', 7, NULL, 12, NULL, 'c2b7dcf7', 3, '2025-09-19', '16:00:00'),
(9, 'Realizada', 7, NULL, 12, NULL, 'ee281c08', 3, '2025-09-16', '18:00:00'),
(13, 'Agendada', 7, NULL, 12, 2081, 'CONF-8BC985', 3, '2025-10-23', '14:26:00'),
(14, 'Realizada', 7, NULL, 12, 2081, 'CONF-A1B2C3', 3, '2025-10-10', '10:00:00'),
(15, 'Agendada', 7, NULL, 13, 2093, 'CONF-D4E5F6', 2, '2025-10-25', '15:00:00'),
(16, 'Cancelada', 7, NULL, 14, 2107, 'CONF-G7H8I9', 1, '2025-09-28', '11:00:00'),
(17, 'Agendada', 7, NULL, 12, 2102, 'CONF-70B55B', 3, '2025-10-17', '15:00:00'),
(18, 'Realizada', 7, NULL, 12, 2257, 'CONF-0652EC', 3, '2025-11-06', '12:00:00'),
(19, 'Realizada', 7, NULL, 12, NULL, '314ef937', 3, '2025-11-10', '18:00:00'),
(20, 'Agendada', 7, NULL, 12, 2279, 'CONF-9228A8', 3, '2025-11-26', '14:00:00'),
(21, 'Realizada', 9, NULL, 12, 2284, 'CONF-273C8E', 3, '2025-11-27', '12:00:00'),
(22, 'Realizada', 7, NULL, 12, 2291, 'CONF-EBF2EE', 3, '2025-11-28', '13:00:00'),
(23, 'Agendada', 7, NULL, 12, NULL, 'f83e59a1', 3, '2025-11-28', '18:45:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `contatos_medicos`
--

CREATE TABLE IF NOT EXISTS `contatos_medicos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `medico_id` int(11) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `rede_social` varchar(150) DEFAULT NULL,
  `criado_por` int(11) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `medico_id` (`medico_id`),
  KEY `criado_por` (`criado_por`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `contatos_medicos`
--

INSERT INTO `contatos_medicos` (`id`, `medico_id`, `telefone`, `email`, `rede_social`, `criado_por`, `criado_em`) VALUES
(1, 12, '(18) 99876-5544', 'brasilux.medico@gmail.com', '@drbrasilux', 1, '2025-10-17 13:28:57'),
(2, 13, '(18) 99777-8899', 'eduardo.scorpioni@medclick.com', '@dreduardoscorpioni', 1, '2025-10-17 13:28:57'),
(3, 14, '(18) 99666-4455', 'souteste@medclick.com', '@drsouteste', 1, '2025-10-17 13:28:57');

-- --------------------------------------------------------

--
-- Estrutura da tabela `documentos`
--

CREATE TABLE IF NOT EXISTS `documentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paciente_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `consulta_id` int(11) DEFAULT NULL,
  `tipo` varchar(50) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `arquivo` varchar(255) NOT NULL,
  `descricao` text,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_consulta_id` (`consulta_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Extraindo dados da tabela `documentos`
--

INSERT INTO `documentos` (`id`, `paciente_id`, `medico_id`, `consulta_id`, `tipo`, `titulo`, `arquivo`, `descricao`, `criado_em`) VALUES
(1, 6, 12, NULL, 'comprovante', NULL, 'pdf_generator/pdfs/comprovante_6_1757902685.pdf', NULL, '2025-09-15 02:18:06'),
(12, 7, 12, NULL, 'receita', 'Receita Médica - Antibiótico', 'pdf_generator/pdfs/receita_7_20251017.pdf', 'Amoxicilina 500mg - Tomar 1 cápsula de 8 em 8 horas por 7 dias.', '2025-10-17 13:28:57'),
(13, 7, 13, NULL, 'exame', 'Resultado de Exame - Hemograma', 'pdf_generator/pdfs/hemograma_7_20251017.pdf', 'Exame realizado no Hospital Regional - resultados dentro da normalidade.', '2025-10-17 13:28:57'),
(14, 7, 14, NULL, 'comprovante', 'Comprovante de Consulta', 'pdf_generator/pdfs/comprovante_7_20251017.pdf', 'Consulta realizada com sucesso em 10/10/2025.', '2025-10-17 13:28:57'),
(15, 7, 12, 18, 'comprovante', NULL, 'pdf_generator/pdfs/comprovante_7_1762360409.pdf', NULL, '2025-11-05 16:33:31'),
(16, 7, 12, NULL, 'atestado', 'Atestado Médico', 'pdf_generator/pdfs/atestado_7_1762360531.pdf', 'asaaasa', '2025-11-05 16:35:32'),
(17, 9, 12, 21, 'comprovante', NULL, 'pdf_generator/pdfs/comprovante_9_1764074336.pdf', NULL, '2025-11-25 12:38:58'),
(18, 9, 12, NULL, 'receita', 'Receita Médica', 'pdf_generator/pdfs/receita_9_1764074368.pdf', 'teste para receita medica', '2025-11-25 12:39:29'),
(19, 9, 12, NULL, 'atestado', 'Atestado Médico', 'pdf_generator/pdfs/atestado_9_1764074394.pdf', 'teste do atestado medico', '2025-11-25 12:39:54'),
(20, 7, 12, 22, 'comprovante', NULL, 'pdf_generator/pdfs/comprovante_7_1764175731.pdf', NULL, '2025-11-26 16:48:52');

-- --------------------------------------------------------

--
-- Estrutura da tabela `especialidades`
--

CREATE TABLE IF NOT EXISTS `especialidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `especialidades`
--

INSERT INTO `especialidades` (`id`, `nome`) VALUES
(1, 'Clínico Geral'),
(2, 'Oftalmologista'),
(3, 'Dentista'),
(4, 'Cardiologista'),
(5, 'Dermatologista');

-- --------------------------------------------------------

--
-- Estrutura da tabela `farmacias`
--

CREATE TABLE IF NOT EXISTS `farmacias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `abertura` time NOT NULL,
  `fechamento` time NOT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `is_24h` tinyint(1) DEFAULT '0',
  `email` varchar(150) DEFAULT NULL,
  `criado_por` int(11) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `imagem` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `criado_por` (`criado_por`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Extraindo dados da tabela `farmacias`
--

INSERT INTO `farmacias` (`id`, `nome`, `endereco`, `abertura`, `fechamento`, `cidade`, `estado`, `telefone`, `is_24h`, `email`, `criado_por`, `criado_em`, `imagem`) VALUES
(1, 'Droga Raia', 'Av. Coronel Marcondes, 755 - Centro, Presidente Prudente - SP', '07:00:00', '22:00:00', ' Presidente Prudente ', 'SP', '(18) 2101-7300', 0, 'DrogaRaia@gmail.com', NULL, '2025-09-09 00:55:23', 'DrogaRaia.png\r\n'),
(2, 'Drogaria São Paulo', 'Av. Manoel Goulart, 1800 - Centro, Presidente Prudente - SP\r\n\r\n', '07:00:00', '22:00:00', 'Presidente Prudente', 'SP', '18997113887', 0, 'DROGASaoPAulo@gmail.com', NULL, '2025-09-09 00:55:23', 'DrogaSãoPaulo.png\r\n'),
(3, 'Drogaria Pacheco', 'Rua Tenente Nicolau Maffei, 800 - Centro', '07:00:00', '22:00:00', 'Presidente Prudente', 'SP', '(18) 3223-6500', 0, NULL, NULL, '2025-09-11 16:55:49', 'farmacia_68c2ff152c8d68.92863848.jpg'),
(4, 'Drogasil', 'Av. Manoel Goulart, 2106 - Vila Santa Helena, Pres. Prudente - SP, 19015-241', '00:00:00', '23:59:59', 'Presidente Prudente', 'SP', '(18) 99813-0214', 1, NULL, NULL, '2025-09-11 17:00:48', 'farmacia_68c30040e1cdd6.45329442.png'),
(5, 'Farmácia Drogacenter', 'Rua Fernão Sales n 452', '00:00:00', '23:59:59', 'Regente Feijó', 'SP', '(18)99711-3887', 1, NULL, NULL, '2025-09-11 17:02:04', 'farmacia_68c3008c026c55.75411906.jpg'),
(6, 'Drogaria Ipanema', 'Av. Brasil, 1401 - Jardim Ipanema, Presidente Prudente - SP', '08:00:00', '20:00:00', 'Presidente Prudente', 'SP', '(18) 3908-1100', 0, NULL, NULL, '2025-09-11 17:03:33', 'farmacia_68c300e531c4a9.45990467.jpg'),
(7, 'Farmácia Nossa Senhora Aparecida', 'Rua Professor Sebastião de Souza, 255 - Vila Santa Helena, Presidente Prudente - SP', '08:00:00', '18:00:00', 'Presidente Prudente', 'SP', '(18) 3223-5982', 0, NULL, NULL, '2025-09-11 17:04:22', 'farmacia_68c30116356965.30680239.jpg'),
(8, 'Drogaria Preço Popular', 'Rua Barão do Rio Branco, 1187 - Centro, Presidente Prudente - SP', '08:00:00', '20:00:00', 'Presidente Prudente', 'SP', '(18) 3223-4843', 0, NULL, NULL, '2025-09-11 17:05:10', 'farmacia_68c301469c0521.28314407.png'),
(9, 'Drogaria Santa Cruz', 'Rua Dona Ana de Oliveira, 55 - Centro, Presidente Prudente - SP', '08:00:00', '20:00:00', 'Presidente Prudente', 'SP', '(18) 3223-5020', 0, NULL, NULL, '2025-09-11 17:05:42', 'farmacia_68c301664e1265.82163592.jpg'),
(10, 'Farmácia Central', 'Av. Cel. José Soares Marcondes, 2000 - Centro', '07:00:00', '22:00:00', 'Presidente Prudente', 'SP', '(18) 3223-9090', 0, 'farmaciacentral@medclick.com', 1, '2025-10-17 13:28:57', 'farmacia_central.png'),
(11, 'Farmácia Popular 24h', 'Rua Rui Barbosa, 500 - Jardim Paulista', '00:00:00', '23:59:59', 'Presidente Prudente', 'SP', '(18) 99999-0000', 1, 'popular24h@medclick.com', 1, '2025-10-17 13:28:57', 'farmacia_popular.png');

-- --------------------------------------------------------

--
-- Estrutura da tabela `gerentes`
--

CREATE TABLE IF NOT EXISTS `gerentes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cpf` varchar(14) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `gerentes`
--

INSERT INTO `gerentes` (`id`, `nome`, `email`, `senha`, `telefone`, `imagem`, `criado_em`, `cpf`) VALUES
(1, 'Gerente A', 'a@gmail.com', '$2y$10$EAECM.O8Z48VhSZr8sZnru8utMY3m9jvt2qftcC0qtv2rLz.BJKIS', '(11) 99999-9999', 'ger.jpg', '2025-09-05 17:16:54', '301107'),
(2, 'Gerente B', 'b@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(11) 88888-8888', NULL, '2025-09-05 17:16:54', NULL),
(3, 'Gerente C', 'c@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(11) 77777-7777', NULL, '2025-09-05 17:16:54', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `horarios_funcionamento`
--

CREATE TABLE IF NOT EXISTS `horarios_funcionamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `medico_id` int(11) NOT NULL,
  `hora_abertura` time NOT NULL,
  `hora_almoco_inicio` time DEFAULT NULL,
  `hora_almoco_fim` time DEFAULT NULL,
  `hora_fechamento` time NOT NULL,
  `atende_24h` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `medico_id` (`medico_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `horarios_funcionamento`
--

INSERT INTO `horarios_funcionamento` (`id`, `medico_id`, `hora_abertura`, `hora_almoco_inicio`, `hora_almoco_fim`, `hora_fechamento`, `atende_24h`) VALUES
(1, 12, '09:00:00', '13:00:00', '14:00:00', '19:00:00', 0),
(2, 13, '08:00:00', '12:00:00', '13:00:00', '18:00:00', 0),
(3, 14, '07:00:00', '12:00:00', '13:00:00', '19:00:00', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `hospitais`
--

CREATE TABLE IF NOT EXISTS `hospitais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `criado_por` int(11) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `imagem` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `criado_por` (`criado_por`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Extraindo dados da tabela `hospitais`
--

INSERT INTO `hospitais` (`id`, `nome`, `endereco`, `cidade`, `estado`, `telefone`, `email`, `criado_por`, `criado_em`, `imagem`) VALUES
(1, 'Hospital Regional de Presidente Prudente', 'Rua José Bongiovani, 1297 - Vila Liberdade', 'Presidente Prudente', 'SP', '(18) 3229-1500', 'contato@hrpp.sp.gov.br', NULL, '2025-09-21 15:16:12', 'hop1.jpg'),
(2, 'Santa Casa de Misericórdia de Presidente Prudente', 'Rua Wenceslau Braz, 5 - Vila Euclides', 'Presidente Prudente', 'SP', '(18) 3901-8000', 'sac@santacasaprudente.org.br', NULL, '2025-09-21 15:16:12', 'hop2.jpg'),
(3, 'Hospital Iamada', 'Rua Cyro Bueno, 200 - Jardim Morumbi', 'Presidente Prudente', 'SP', '(18) 2104-5000', 'atendimento@iamada.com.br', NULL, '2025-09-21 15:16:12', 'hop3.jpg'),
(4, 'Hospital Estadual de Presidente Prudente', 'Av. Cel. José Soares Marcondes, 3758 - Parque Higienópolis', 'Presidente Prudente', 'SP', '(18) 3908-4422', 'hospitalestadual@saude.sp.gov.br', NULL, '2025-09-21 15:16:12', 'hop4.jpg'),
(5, 'Hospital Regional do Câncer de Presidente Prudente', 'Av. Cel. José Soares Marcondes, 2380 - Vila Euclides', 'Presidente Prudente', 'SP', '(18) 2104-8000', 'contato@hrcancer.org.br', NULL, '2025-09-21 15:16:12', 'hop5.jpg'),
(6, 'Hospital e Maternidade Nossa Senhora das Graças', 'Rua Dr. Gurgel, 715 - Centro', 'Presidente Prudente', 'SP', '(18) 3311-5000', 'contato@nossasenhoradasgracas.com.br', NULL, '2025-09-21 15:16:12', 'hop6.jpg'),
(7, 'Hospital Infantil Unimed Presidente Prudente', 'Av. Washington Luiz, 2305 - Jardim Paulista', 'Presidente Prudente', 'SP', '(18) 2104-8080', 'infantil@unimedpp.com.br', NULL, '2025-09-21 15:16:12', 'hop7.jpg'),
(8, 'Hospital Ortocardio', 'Av. Cel. José Soares Marcondes, 2044 - Parque Higienópolis', 'Presidente Prudente', 'SP', '(18) 3222-0355', 'contato@ortocardio.com.br', NULL, '2025-09-21 15:16:12', 'hop8.jpg'),
(9, 'Santa Casa de Misericórdia de Pirapozinho', 'Rua das Flores, 250 - Centro', 'Pirapozinho', 'SP', '(18) 3269-1234', 'sac@santacasapirapozinho.org.br', NULL, '2025-09-21 15:16:12', 'hop9.jpg'),
(10, 'Hospital São Lucas', 'Av. Washington Luiz, 1800 - Jardim Paulista', 'Presidente Prudente', 'SP', '(18) 3233-8080', 'contato@hospitalsaolucas.com', 1, '2025-10-17 13:28:57', 'hop10.jpg'),
(11, 'Hospital Vida Saudável', 'Rua XV de Novembro, 1500 - Centro', 'Presidente Prudente', 'SP', '(18) 3902-9090', 'vida@saude.com', 1, '2025-10-17 13:28:57', 'hop11.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `locais_consulta`
--

CREATE TABLE IF NOT EXISTS `locais_consulta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `locais_consulta`
--

INSERT INTO `locais_consulta` (`id`, `nome`, `endereco`, `cidade`, `estado`) VALUES
(1, 'Hospital Santa Casa', 'Rua A, 123', 'Presidente Prudente', 'SP'),
(2, 'Clínica Central', 'Av. Brasil, 456', 'Presidente Prudente', 'SP'),
(3, 'Hospital Regional', 'Rua das Flores, 789', 'Presidente Prudente', 'SP'),
(4, 'Clínica MedClick', 'Rua das Palmeiras, 350', 'Presidente Prudente', 'SP'),
(5, 'Centro de Especialidades Médicas', 'Av. Brasil, 1800', 'Presidente Prudente', 'SP');

-- --------------------------------------------------------

--
-- Estrutura da tabela `medicos`
--

CREATE TABLE IF NOT EXISTS `medicos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `crm` varchar(20) NOT NULL,
  `especialidade_id` int(11) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefone` varchar(14) DEFAULT NULL,
  `local_consulta_id` int(11) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `crm` (`crm`),
  KEY `especialidade_id` (`especialidade_id`),
  KEY `local_consulta_id` (`local_consulta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Extraindo dados da tabela `medicos`
--

INSERT INTO `medicos` (`id`, `nome`, `crm`, `especialidade_id`, `imagem`, `senha`, `email`, `telefone`, `local_consulta_id`, `criado_em`) VALUES
(1, 'Dr. João Mendes', 'CRM12345-SP', 1, 'joao.jpg', '$2y$10$KIXkUxvYxF8E8utMX8UgKe5vUcz/6gTxyhn0h2RdvGsmXQTRKPMQO', NULL, NULL, 1, '2025-06-09 17:39:11'),
(2, 'Dra. Ana Souza', 'CRM67890-RJ', 3, 'ana.jpg', '$2y$10$KIXkUxvYxF8E8utMX8UgKe5vUcz/6gTxyhn0h2RdvGsmXQTRKPMQO', NULL, NULL, 2, '2025-06-09 17:39:11'),
(3, 'Dr. Carlos Lima', 'CRM11223-MG', 2, 'carlos.jpg', '$2y$10$KIXkUxvYxF8E8utMX8UgKe5vUcz/6gTxyhn0h2RdvGsmXQTRKPMQO', NULL, NULL, 3, '2025-06-09 17:39:11'),
(4, 'Dra. Fernanda Ribeiro', 'CRM44556-SP', 4, 'fernanda.jpg', '$2y$10$KIXkUxvYxF8E8utMX8UgKe5vUcz/6gTxyhn0h2RdvGsmXQTRKPMQO', NULL, NULL, NULL, '2025-06-09 17:39:11'),
(5, 'Dr. Ricardo Alves', 'CRM99887-BA', 6, 'ricardo.jpg', '$2y$10$KIXkUxvYxF8E8utMX8UgKe5vUcz/6gTxyhn0h2RdvGsmXQTRKPMQO', NULL, NULL, NULL, '2025-06-09 17:39:11'),
(6, 'Dr. Eduardo', 'CRM54556-SP', 4, '68471cbf74e4b.jpg', '$2y$10$24/XNu9yWC8tyu8ZnZa6cOn8Rz2syQbFcAz2nn78A285F6G0S8C8.', NULL, NULL, NULL, '2025-06-09 17:41:19'),
(7, 'Dr. TesteMedico', 'AB12345', 2, '68499cf2f3b38.jpg', '$2y$10$UG2URC5g.6KYDN5zZhBWkuEgvbShMhosrXNMy8cMKIVhwj6zqHDIW', NULL, NULL, NULL, '2025-06-11 15:12:51'),
(8, 'Dr. TesteMedico2', 'AB123', 2, NULL, '$2y$10$Ckflb52xwY5K6mkCsW9v4.DYU6MhkhXaMFEtc33E9I3tcWDnNy7YC', NULL, NULL, NULL, '2025-06-11 15:14:13'),
(12, 'Dr. Bernado Silva', '123', 3, 'med.jpg', '$2y$10$152rN0ZT8bduuA0LQAQn6eoLRu0uqnnZqNE3v1nEeARa6kJwvNqeS', 'bernardoS@gmail.com', '(18) 9999-9999', 1, '2025-08-01 19:00:36'),
(13, 'Dr. Eduardo Scorpioni', '301107', 2, '68962b1ebbd25.jpg', '$2y$10$ZxW3/UULErdgY4AlCdjs7.imgQvuf7l.jP8Zkptm1wFHFodulllA2', NULL, NULL, 2, '2025-08-08 16:51:42'),
(14, 'Dr. souteste', '321', 1, '68962c805ff29.jpg', '$2y$10$NGm50cDglSeJfyPpm1I7W.VMhJq3kUBY17mv.sBcIFTlCRdfN9sce', NULL, NULL, 3, '2025-08-08 16:57:36');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pacientes`
--

CREATE TABLE IF NOT EXISTS `pacientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `email` varchar(100) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `data_nascimento` date NOT NULL,
  `sexo` varchar(10) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `possui_deficiencia` enum('Sim','Não') NOT NULL,
  `deficiencia` text,
  `senha` varchar(255) NOT NULL,
  `imagem` varchar(55) NOT NULL,
  `imagem_base6` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpf` (`cpf`),
  UNIQUE KEY `cpf_2` (`cpf`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Extraindo dados da tabela `pacientes`
--

INSERT INTO `pacientes` (`id`, `nome`, `cpf`, `email`, `endereco`, `data_nascimento`, `sexo`, `telefone`, `possui_deficiencia`, `deficiencia`, `senha`, `imagem`, `imagem_base6`) VALUES
(1, 'Eduardo Viccino Scorpioni', '495.897.738-42', 'eduscorpioni@outlook.com', 'Rua FernÃ£o Sales, 452', '2025-05-23', 'Masculino', '18997113887', '', '', '', '', ''),
(7, 'Igor Marques da Silva', '123', 'ighormarrques21@gmail.com', 'Rua Barao do Rio Branco, 11', '0007-11-30', 'Masculino', '18988224039', '', '', '$2y$10$EAECM.O8Z48VhSZr8sZnru8utMY3m9jvt2qftcC0qtv2rLz.BJKIS', '692704f4777bd.jpeg', '');

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `contatos_medicos`
--
ALTER TABLE `contatos_medicos`
  ADD CONSTRAINT `contatos_medicos_ibfk_1` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contatos_medicos_ibfk_2` FOREIGN KEY (`criado_por`) REFERENCES `gerentes` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `farmacias`
--
ALTER TABLE `farmacias`
  ADD CONSTRAINT `farmacias_ibfk_1` FOREIGN KEY (`criado_por`) REFERENCES `gerentes` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `hospitais`
--
ALTER TABLE `hospitais`
  ADD CONSTRAINT `hospitais_ibfk_1` FOREIGN KEY (`criado_por`) REFERENCES `gerentes` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `medicos`
--
ALTER TABLE `medicos`
  ADD CONSTRAINT `medicos_ibfk_1` FOREIGN KEY (`local_consulta_id`) REFERENCES `locais_consulta` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

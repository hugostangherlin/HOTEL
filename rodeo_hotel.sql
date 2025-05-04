-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04-Maio-2025 às 05:35
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `rodeo_hotel`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `avaliacao`
--

CREATE TABLE `avaliacao` (
  `ID_Avaliacao` int(11) NOT NULL,
  `Nota` varchar(45) NOT NULL,
  `Comentario` varchar(45) DEFAULT NULL,
  `Data_Avaliacao` varchar(45) DEFAULT NULL,
  `usuarios_ID` int(11) NOT NULL COMMENT 'Somente o usuário pode avaliar uma reserva',
  `Reserva_ID_Reserva` int(11) NOT NULL COMMENT 'A avaliação corresponde a uma reserva'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `ID_Categoria` int(11) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Descricao` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`ID_Categoria`, `Nome`, `Descricao`) VALUES
(1, 'Suite Master', ''),
(2, 'Luxo ', ''),
(3, 'Standard', ''),
(4, 'Econômico', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pagamentos`
--

CREATE TABLE `pagamentos` (
  `ID_Pagamento` int(11) NOT NULL,
  `ID_Reserva` int(11) DEFAULT NULL,
  `ID_Usuarios` int(11) DEFAULT NULL,
  `Valor` decimal(10,2) DEFAULT NULL,
  `Forma_Pagamento` enum('Cartão','Boleto','Pix','Dinheiro') NOT NULL,
  `Status` enum('Pendente','Pago','Cancelado') NOT NULL,
  `Data_Pagamento` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `pagamentos`
--

INSERT INTO `pagamentos` (`ID_Pagamento`, `ID_Reserva`, `ID_Usuarios`, `Valor`, `Forma_Pagamento`, `Status`, `Data_Pagamento`) VALUES
(0, 0, 0, 900.00, 'Cartão', 'Pago', '2025-05-04 00:34:33');

-- --------------------------------------------------------

--
-- Estrutura da tabela `perfil`
--

CREATE TABLE `perfil` (
  `ID_Perfil` int(11) NOT NULL COMMENT 'Gestor ou Hóspede',
  `Categoria` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `perfil`
--

INSERT INTO `perfil` (`ID_Perfil`, `Categoria`) VALUES
(1, 'Gestor'),
(2, 'Hóspede');

-- --------------------------------------------------------

--
-- Estrutura da tabela `quarto`
--

CREATE TABLE `quarto` (
  `ID_Quarto` int(11) NOT NULL,
  `Status` enum('Disponível','Ocupado','Manutenção') NOT NULL,
  `Capacidade` int(11) NOT NULL,
  `Categoria_ID_Categoria` int(11) NOT NULL COMMENT 'Cada quarto corresponde a uma categoria.',
  `Foto` varchar(45) NOT NULL,
  `Preco_diaria` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `quarto`
--

INSERT INTO `quarto` (`ID_Quarto`, `Status`, `Capacidade`, `Categoria_ID_Categoria`, `Foto`, `Preco_diaria`) VALUES
(1, 'Disponível', 1, 1, '', 150.00),
(2, 'Disponível', 2, 1, '', 150.00),
(3, 'Disponível', 4, 1, '', 150.00),
(4, 'Disponível', 6, 1, '', 150.00),
(5, 'Disponível', 1, 2, '', 200.00),
(6, 'Disponível', 2, 2, '', 200.00),
(7, 'Disponível', 4, 2, '', 200.00),
(8, 'Disponível', 6, 2, '', 200.00),
(9, 'Disponível', 1, 3, '', 300.00),
(10, 'Disponível', 2, 3, '', 300.00),
(11, 'Disponível', 4, 3, '', 300.00),
(12, 'Disponível', 6, 3, '', 300.00),
(13, 'Disponível', 1, 4, '', 400.00),
(14, 'Disponível', 2, 4, '', 400.00),
(15, 'Disponível', 4, 4, '', 400.00),
(16, 'Disponível', 6, 4, '', 400.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `relatorios`
--

CREATE TABLE `relatorios` (
  `ID_Relatorio` int(11) NOT NULL,
  `Data_relatorio` varchar(45) NOT NULL,
  `Descricao` varchar(45) DEFAULT NULL,
  `Reserva_ID_Reserva` int(11) NOT NULL COMMENT 'Um relatório pode constar várias reservas.',
  `usuarios_ID` int(11) NOT NULL COMMENT 'Um relatório pode constar vários usuários',
  `Avaliacao_ID_Avaliacao` int(11) NOT NULL COMMENT 'Um relatório pode constar várias avaliações'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `reserva`
--

CREATE TABLE `reserva` (
  `ID_Reserva` int(11) NOT NULL,
  `Checkin` date NOT NULL,
  `Checkout` date NOT NULL,
  `Quarto_ID_Quarto` int(11) NOT NULL COMMENT 'Cada reserva corresponde a um quarto.',
  `usuarios_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `reserva`
--

INSERT INTO `reserva` (`ID_Reserva`, `Checkin`, `Checkout`, `Quarto_ID_Quarto`, `usuarios_ID`) VALUES
(0, '2025-05-04', '2025-05-10', 1, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `ID` int(11) NOT NULL,
  `Nome` varchar(45) NOT NULL,
  `Email` varchar(45) NOT NULL,
  `Data_Nascimento` varchar(45) NOT NULL,
  `Telefone` varchar(45) NOT NULL,
  `Endereco` varchar(45) NOT NULL,
  `CPF` varchar(45) NOT NULL,
  `Perfil_ID_Perfil` int(11) NOT NULL COMMENT 'Cada usuário será designado um tipo de perfil',
  `Senha` varchar(255) DEFAULT NULL,
  `solicitou_exclusao` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`ID`, `Nome`, `Email`, `Data_Nascimento`, `Telefone`, `Endereco`, `CPF`, `Perfil_ID_Perfil`, `Senha`, `solicitou_exclusao`) VALUES
(0, 'hospede', 'hospede@gmail.com', '2004-05-01', '+5561983179384', 'QNR 1 Conjunto B Casa 34', '064.875.921-02', 2, '$2y$10$XQdjRL29SzaBvGuJyhu54uXznvWB4bg.HRfUVGeR4gocZoxRHBxFK', 0),
(0, 'gestor', 'gestor@gmail.com', '1980-10-09', '+5561983113397', 'QNR 1 Conjunto B Casa 34', '704.012.331-20', 1, '$2y$10$C1VbRDsi88lhlK59n1QWxOa2x9eqiRthWoCb/1lF05hCm5BkoWhre', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

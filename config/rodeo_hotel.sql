-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 02-Jun-2025 às 04:37
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

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
  `ID_Reserva` int(11) DEFAULT NULL,
  `ID_Usuario` int(11) NOT NULL,
  `Nota` int(11) NOT NULL,
  `Comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `ID_Categoria` int(11) NOT NULL,
  `Nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`ID_Categoria`, `Nome`) VALUES
(1, 'Suite Master'),
(2, 'Luxo '),
(3, 'Standard'),
(4, 'Econômico');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pagamentos`
--

CREATE TABLE `pagamentos` (
  `ID_Pagamento` int(11) NOT NULL,
  `ID_Reserva` int(11) NOT NULL,
  `ID_Usuarios` int(11) NOT NULL,
  `Valor` decimal(10,2) NOT NULL,
  `Forma_Pagamento` varchar(45) NOT NULL,
  `Status` varchar(45) NOT NULL,
  `Data_Pagamento` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `perfil`
--

CREATE TABLE `perfil` (
  `ID_Perfil` int(11) NOT NULL,
  `Nome_Perfil` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `perfil`
--

INSERT INTO `perfil` (`ID_Perfil`, `Nome_Perfil`) VALUES
(1, 'Gestor'),
(2, 'Hóspede');

-- --------------------------------------------------------

--
-- Estrutura da tabela `quarto`
--

CREATE TABLE `quarto` (
  `ID_Quarto` int(11) NOT NULL,
  `Status` varchar(45) DEFAULT NULL,
  `Capacidade` int(11) DEFAULT NULL,
  `Categoria_ID_Categoria` int(11) NOT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `Preco_diaria` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `quarto`
--

INSERT INTO `quarto` (`ID_Quarto`, `Status`, `Capacidade`, `Categoria_ID_Categoria`, `Foto`, `Preco_diaria`) VALUES
(27, 'Disponível', 1, 1, 'quarto_683cd0fe8afdd.jpg', 200.00),
(28, 'Disponivel', 2, 1, 'quarto_683cd1edd53e9.jpg', 200.00),
(29, 'Disponivel', 4, 1, 'quarto_683cd2509e626.jpg', 200.00),
(31, 'Disponivel', 1, 2, 'quarto_683cd333ad35b.jpg', 150.00),
(32, 'Disponivel', 2, 2, 'quarto_683cd3d9658ec.jpg', 150.00),
(33, 'Disponivel', 4, 2, 'quarto_683cd58c2d5e5.jpg', 150.00),
(34, 'Disponivel', 1, 3, 'quarto_683cd626ca3cc.jpg', 100.00),
(35, 'Disponivel', 2, 3, 'quarto_683cd67a06d7e.jpg', 100.00),
(36, 'Disponivel', 4, 3, 'quarto_683cd749043e5.jpg', 100.00),
(37, 'Disponível', 1, 4, 'quarto_683cd82b7fc94.jpg', 50.00),
(38, 'Disponivel', 2, 4, 'quarto_683cd83d56624.jpg', 50.00),
(39, 'Disponivel', 4, 4, 'quarto_683cd863d66b3.jpg', 50.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `relatorio`
--

CREATE TABLE `relatorio` (
  `ID_Relatorio` int(11) NOT NULL,
  `Data_Relatorio` datetime NOT NULL,
  `Tipo_Relatorio` varchar(45) NOT NULL,
  `Descricao` text NOT NULL,
  `Arquivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `relatorio`
--

INSERT INTO `relatorio` (`ID_Relatorio`, `Data_Relatorio`, `Tipo_Relatorio`, `Descricao`, `Arquivo`) VALUES
(26, '2025-06-01 04:47:26', 'Usuários', 'Relatório de usuários Filtro por perfil: Gestor.', 'relatorio_usuarios_20250601_044726.pdf'),
(27, '2025-06-01 04:47:55', 'Reservas', 'Relatório de reservas a partir de 2025-05-31 até 2025-06-02', 'relatorio_reservas_20250601_044755.pdf'),
(28, '2025-06-01 05:03:27', 'Faturamento', 'Relatório de faturamento - Filtro forma pagamento: Pix.', 'relatorio_faturamento_20250601_050327.pdf'),
(29, '2025-06-01 12:49:02', 'Usuários', 'Relatório de usuários', 'relatorio_usuarios_20250601_124902.pdf'),
(30, '2025-06-02 04:04:38', 'Faturamento', 'Relatório de faturamento', 'relatorio_faturamento_20250602_040437.pdf'),
(31, '2025-06-02 04:07:19', 'Faturamento', 'Relatório de faturamento', 'relatorio_faturamento_20250602_040719.pdf'),
(32, '2025-06-02 04:08:08', 'Faturamento', 'Relatório de faturamento', 'relatorio_faturamento_20250602_040808.pdf');

-- --------------------------------------------------------

--
-- Estrutura da tabela `reserva`
--

CREATE TABLE `reserva` (
  `ID_Reserva` int(11) NOT NULL,
  `Checkin` date NOT NULL,
  `Checkout` date NOT NULL,
  `Quarto_ID_Quarto` int(11) NOT NULL,
  `usuarios_ID` int(11) NOT NULL,
  `solicitou_exclusao` tinyint(1) DEFAULT 0,
  `Data_Solicitacao_Exclusao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `CPF` char(14) NOT NULL,
  `Perfil_ID_Perfil` int(11) NOT NULL,
  `solicitou_exclusao` varchar(45) DEFAULT NULL,
  `Senha` varchar(255) NOT NULL,
  `Data_Solicitacao_Exclusao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`ID`, `Nome`, `Email`, `Data_Nascimento`, `Telefone`, `Endereco`, `CPF`, `Perfil_ID_Perfil`, `solicitou_exclusao`, `Senha`, `Data_Solicitacao_Exclusao`) VALUES
(3, 'gestor', 'gestor@gmail.com', '', '+5561983113397', 'QNR 1 Conjunto B Casa 34', '704.012.331-20', 1, NULL, '$2y$10$VSR6xYtiFkqDIyDjrPV.ZuFDohf4FJEhgBzbELMO/b8c0GXpGbRma', '2025-05-04 14:57:17');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD PRIMARY KEY (`ID_Avaliacao`),
  ADD KEY `fk_Avaliacao_Quarto` (`ID_Reserva`),
  ADD KEY `fk_Avaliacao_Usuario` (`ID_Usuario`);

--
-- Índices para tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`ID_Categoria`);

--
-- Índices para tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD PRIMARY KEY (`ID_Pagamento`),
  ADD KEY `fk_Pagamentos_Reserva` (`ID_Reserva`),
  ADD KEY `fk_Pagamentos_Usuarios` (`ID_Usuarios`);

--
-- Índices para tabela `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`ID_Perfil`);

--
-- Índices para tabela `quarto`
--
ALTER TABLE `quarto`
  ADD PRIMARY KEY (`ID_Quarto`),
  ADD KEY `fk_Quarto_Categoria` (`Categoria_ID_Categoria`);

--
-- Índices para tabela `relatorio`
--
ALTER TABLE `relatorio`
  ADD PRIMARY KEY (`ID_Relatorio`);

--
-- Índices para tabela `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`ID_Reserva`),
  ADD KEY `fk_Reserva_Quarto1` (`Quarto_ID_Quarto`),
  ADD KEY `fk_Reserva_usuarios1` (`usuarios_ID`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_usuarios_Perfil` (`Perfil_ID_Perfil`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  MODIFY `ID_Avaliacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `ID_Categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  MODIFY `ID_Pagamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de tabela `perfil`
--
ALTER TABLE `perfil`
  MODIFY `ID_Perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `quarto`
--
ALTER TABLE `quarto`
  MODIFY `ID_Quarto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de tabela `relatorio`
--
ALTER TABLE `relatorio`
  MODIFY `ID_Relatorio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de tabela `reserva`
--
ALTER TABLE `reserva`
  MODIFY `ID_Reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD CONSTRAINT `fk_Avaliacao_Reserva` FOREIGN KEY (`ID_Reserva`) REFERENCES `reserva` (`ID_Reserva`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Avaliacao_Usuario` FOREIGN KEY (`ID_Usuario`) REFERENCES `usuarios` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD CONSTRAINT `fk_Pagamentos_Reserva` FOREIGN KEY (`ID_Reserva`) REFERENCES `reserva` (`ID_Reserva`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Pagamentos_Usuarios` FOREIGN KEY (`ID_Usuarios`) REFERENCES `usuarios` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `quarto`
--
ALTER TABLE `quarto`
  ADD CONSTRAINT `fk_Quarto_Categoria` FOREIGN KEY (`Categoria_ID_Categoria`) REFERENCES `categoria` (`ID_Categoria`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_Reserva_Quarto1` FOREIGN KEY (`Quarto_ID_Quarto`) REFERENCES `quarto` (`ID_Quarto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Reserva_usuarios1` FOREIGN KEY (`usuarios_ID`) REFERENCES `usuarios` (`ID`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_Perfil` FOREIGN KEY (`Perfil_ID_Perfil`) REFERENCES `perfil` (`ID_Perfil`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

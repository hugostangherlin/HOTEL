-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 23-Abr-2025 às 21:16
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
  `Foto` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  `Senha` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`ID`, `Nome`, `Email`, `Data_Nascimento`, `Telefone`, `Endereco`, `CPF`, `Perfil_ID_Perfil`, `Senha`) VALUES
(13, 'Hóspede', 'hospede@gmail.com', '2004-05-01', '+55(61) 98317-9384', 'Qnr 1 Conjunto B Casa 34', '111.111.111-11', 2, '$2y$10$Icht.DWS1nBcjpZZsEqsd.D.PYxwdVlWhfVJ9onvKOq9UGXNNxp6S'),
(15, 'Gestor', 'gestor@gmail.com', '2001-01-01', '+55(61) 98311-3397', 'CNR 1', '478.258.96-46', 1, '$2y$10$P49Ed5x7bHSjNZwQdEa8kutDbyAkljQ/nfqiG9lrkC9sTvHdDRHc.'),
(16, 'HUGO STANGHERLIN FEITOSA DE CARVALHO', 'hugo@gmail.com', '2004-05-01', '+5561983179384', 'QNR 1', '852.741.963-02', 1, '$2y$10$nzKr89bIL5HMUX96bbGb7.sm4MhGGz9MJVlR2ZWzibGYgTh.G5RIS');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD PRIMARY KEY (`ID_Avaliacao`),
  ADD KEY `fk_Avaliacao_usuarios1_idx` (`usuarios_ID`),
  ADD KEY `fk_Avaliacao_Reserva1_idx` (`Reserva_ID_Reserva`);

--
-- Índices para tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`ID_Categoria`);

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
  ADD KEY `fk_Quarto_Categoria1_idx` (`Categoria_ID_Categoria`);

--
-- Índices para tabela `relatorios`
--
ALTER TABLE `relatorios`
  ADD PRIMARY KEY (`ID_Relatorio`),
  ADD KEY `fk_Relatorios_Reserva1_idx` (`Reserva_ID_Reserva`),
  ADD KEY `fk_Relatorios_usuarios1_idx` (`usuarios_ID`),
  ADD KEY `fk_Relatorios_Avaliacao1_idx` (`Avaliacao_ID_Avaliacao`);

--
-- Índices para tabela `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`ID_Reserva`),
  ADD KEY `fk_Reserva_Quarto1_idx` (`Quarto_ID_Quarto`),
  ADD KEY `fk_Reserva_usuarios1_idx` (`usuarios_ID`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_usuarios_Perfil_idx` (`Perfil_ID_Perfil`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  MODIFY `ID_Avaliacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `ID_Categoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `perfil`
--
ALTER TABLE `perfil`
  MODIFY `ID_Perfil` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Gestor ou Hóspede', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `relatorios`
--
ALTER TABLE `relatorios`
  MODIFY `ID_Relatorio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD CONSTRAINT `fk_Avaliacao_Reserva1` FOREIGN KEY (`Reserva_ID_Reserva`) REFERENCES `reserva` (`ID_Reserva`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Avaliacao_usuarios1` FOREIGN KEY (`usuarios_ID`) REFERENCES `usuarios` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `quarto`
--
ALTER TABLE `quarto`
  ADD CONSTRAINT `fk_Quarto_Categoria1` FOREIGN KEY (`Categoria_ID_Categoria`) REFERENCES `categoria` (`ID_Categoria`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `relatorios`
--
ALTER TABLE `relatorios`
  ADD CONSTRAINT `fk_Relatorios_Avaliacao1` FOREIGN KEY (`Avaliacao_ID_Avaliacao`) REFERENCES `avaliacao` (`ID_Avaliacao`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Relatorios_Reserva1` FOREIGN KEY (`Reserva_ID_Reserva`) REFERENCES `reserva` (`ID_Reserva`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Relatorios_usuarios1` FOREIGN KEY (`usuarios_ID`) REFERENCES `usuarios` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_Reserva_Quarto1` FOREIGN KEY (`Quarto_ID_Quarto`) REFERENCES `quarto` (`ID_Quarto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Reserva_usuarios1` FOREIGN KEY (`usuarios_ID`) REFERENCES `usuarios` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_Perfil` FOREIGN KEY (`Perfil_ID_Perfil`) REFERENCES `perfil` (`ID_Perfil`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

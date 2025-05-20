-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 20-Maio-2025 às 20:54
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
  `ID_Quarto` int(11) NOT NULL,
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
(1, 'Ocupado', 1, 1, 'quarto_6824b7747eb00.jpg', 350.00),
(2, 'Ocupado', 1, 1, 'quarto_68240342b4a5b.jpeg', 150.00),
(3, 'Disponivel', 4, 1, 'quarto_68240660aaa19.jpg', 150.00),
(13, 'Disponivel', 1, 4, '', 400.00),
(22, 'Disponivel', 1, 2, 'quarto_68240dd57e085.jpg', 200.00),
(23, 'Disponivel', 2, 2, 'quarto_68240df308ef1.jpeg', 200.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `relatorio`
--

CREATE TABLE `relatorio` (
  `ID_Relatorio` int(11) NOT NULL,
  `Data_Relatorio` datetime NOT NULL,
  `Tipo_Relatorio` varchar(45) NOT NULL,
  `Descricao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(3, 'gestor', 'gestor@gmail.com', '1980-10-09', '+5561983113397', 'QNR 1 Conjunto B Casa 34', '704.012.331-20', 1, NULL, '$2y$10$HhKxenprFERP3hEKXO9l/eixvksWomx91Xm1xiBEziGuEyPZMYPCu', '2025-05-04 14:57:17'),
(9, 'hospede', 'hospede@gmail.com', '2004-05-01', '(55) 61983-1793', 'QNR 1', '064.875.921-02', 2, '1', '$2y$10$tZsz8wFs/mG0nsyUya8ROOdpM8iIDtQlDurclj.wv8Nrurdcrus3.', '2025-05-14 19:38:44'),
(11, 'joao', 'joao@gmail.com', '2004-08-05', '(61) 99789-1545', 'qnn 1', '555.555.555-55', 1, NULL, '$2y$10$wNswlU1UTyECdw6u3tXQm./MfSoIrZuVOXQzc0cAGhmYTH2APYMFC', '2025-05-14 20:07:44'),
(13, 'joao', 'joaogestor@gmail.com', '2004-08-05', '(61) 99238-2332', 'qnn 23', '057.382.918-33', 1, NULL, '$2y$10$bhZRvrsTYsggjhRmGCuMZeeGNjBcjnQhNRN6ax62g4/mrNwjfmgU6', '2025-05-14 21:36:56'),
(14, 'arthur', 'arthurgestor@gmail.com', '2004-08-05', '(61) 99238-2332', 'qnn 23', '057.382.918-33', 1, NULL, '$2y$10$D8r07tqCI3bFkNaubFD2x.ck6myVp30WGloT8zhahq8P5rC3u56dS', '2025-05-14 21:37:14');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD PRIMARY KEY (`ID_Avaliacao`),
  ADD KEY `fk_Avaliacao_Quarto` (`ID_Quarto`),
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
  MODIFY `ID_Avaliacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `ID_Categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  MODIFY `ID_Pagamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de tabela `perfil`
--
ALTER TABLE `perfil`
  MODIFY `ID_Perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `quarto`
--
ALTER TABLE `quarto`
  MODIFY `ID_Quarto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `relatorio`
--
ALTER TABLE `relatorio`
  MODIFY `ID_Relatorio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `reserva`
--
ALTER TABLE `reserva`
  MODIFY `ID_Reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD CONSTRAINT `fk_Avaliacao_Quarto` FOREIGN KEY (`ID_Quarto`) REFERENCES `quarto` (`ID_Quarto`) ON DELETE CASCADE ON UPDATE NO ACTION,
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

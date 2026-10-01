-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27/09/2026 às 16:21
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `convictus_data`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `ataque`
--

CREATE TABLE `ataque` (
  `id` int(11) NOT NULL COMMENT 'Chave Primaria (ID)',
  `personagem_id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `dano` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ataque`
--

INSERT INTO `ataque` (`id`, `personagem_id`, `nome`, `dano`) VALUES
(1, 1, 'Golpe de Espada', 22),
(2, 1, 'Corte Poderoso', 28),
(3, 2, 'Estocada Rápida', 16),
(4, 2, 'Corte Sombrio', 21);

-- --------------------------------------------------------

--
-- Estrutura para tabela `personagem`
--

CREATE TABLE `personagem` (
  `id` int(11) NOT NULL COMMENT 'Id do Personagem',
  `nome` varchar(50) NOT NULL,
  `sprite` varchar(255) NOT NULL,
  `hp_max` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `personagem`
--

INSERT INTO `personagem` (`id`, `nome`, `sprite`, `hp_max`) VALUES
(1, 'Convictus', 'convictus.png', 120),
(2, 'Mercenario', 'mercenario.png', 80);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `ataque`
--
ALTER TABLE `ataque`
  ADD PRIMARY KEY (`id`),
  ADD KEY `personagem_id` (`personagem_id`);

--
-- Índices de tabela `personagem`
--
ALTER TABLE `personagem`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `ataque`
--
ALTER TABLE `ataque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Chave Primaria (ID)', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `personagem`
--
ALTER TABLE `personagem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id do Personagem', AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `ataque`
--
ALTER TABLE `ataque`
  ADD CONSTRAINT `ataque_ibfk_1` FOREIGN KEY (`personagem_id`) REFERENCES `personagem` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

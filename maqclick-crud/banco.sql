-- Rode este arquivo inteiro no MySQL do LARAGON (cria o banco, as tabelas e os dados)

CREATE DATABASE IF NOT EXISTS db_maqclick;
USE db_maqclick;

CREATE TABLE IF NOT EXISTS usuario (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nome_usuario VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    perfil_usuario VARCHAR(30) NOT NULL
);

CREATE TABLE IF NOT EXISTS categoria (
    id_categoria INT PRIMARY KEY AUTO_INCREMENT,
    nome_categoria VARCHAR(255) NOT NULL,
    descricao_categoria VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS equipamento (
    id_equipamento INT PRIMARY KEY AUTO_INCREMENT,
    nome_equipamento VARCHAR(255) NOT NULL,
    descricao_equipamento VARCHAR(255),
    status_equipamento VARCHAR(30),
    id_categoria INT NOT NULL,
    FOREIGN KEY (id_categoria) REFERENCES categoria (id_categoria)
);

CREATE TABLE IF NOT EXISTS agendamento (
    id_agendamento INT PRIMARY KEY AUTO_INCREMENT,
    data_inicio DATETIME NOT NULL,
    data_fim DATETIME NOT NULL,
    status_agendamento VARCHAR(30),
    id_usuario INT NOT NULL,
    id_equipamento INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario),
    FOREIGN KEY (id_equipamento) REFERENCES equipamento (id_equipamento)
);

CREATE TABLE IF NOT EXISTS emprestimo (
    id_emprestimo INT PRIMARY KEY AUTO_INCREMENT,
    data_retirada DATETIME NOT NULL,
    data_prevista_devolucao DATETIME NOT NULL,
    data_devolucao DATETIME,
    status VARCHAR(30),
    id_usuario INT NOT NULL,
    id_equipamento INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario),
    FOREIGN KEY (id_equipamento) REFERENCES equipamento (id_equipamento)
);

CREATE TABLE IF NOT EXISTS manutencao (
    id_manutencao INT PRIMARY KEY AUTO_INCREMENT,
    data_manutencao DATETIME NOT NULL,
    descricao_manutencao VARCHAR(255),
    tipo VARCHAR(30),
    status VARCHAR(30),
    id_equipamento INT NOT NULL,
    FOREIGN KEY (id_equipamento) REFERENCES equipamento (id_equipamento)
);

INSERT INTO categoria (nome_categoria, descricao_categoria) VALUES
('Escavadeiras e Retroescavadeiras', 'Máquinas de escavação e movimentação de terra'),
('Carga Pesada', 'Pás carregadeiras e veículos de grande porte'),
('Tratores e Caminhões', 'Tratores de esteira e caminhões articulados');

INSERT INTO usuario (nome_usuario, email, senha, perfil_usuario) VALUES
('Dorival Camara', 'dorival@devisate.com', '123456', 'ADMIN'),
('Gustavo Alcântara', 'gustavo@devisate.com', '123456', 'ADMIN'),
('Felipe Mazalli', 'felipe@devisate.com', '123456', 'ADMIN'),
('Davi Neris', 'neris@devisate.com', '123456', 'USUARIO');

INSERT INTO equipamento (nome_equipamento, descricao_equipamento, status_equipamento, id_categoria) VALUES
('Escavadeira', 'Escavadeira Hidráulica', 'DISPONIVEL', 1),
('Escavadeira', 'Mini Escavadeira 35Z', 'DISPONIVEL', 1),
('Trator', 'Trator de Esteiras D6', 'MANUTENCAO', 3),
('Caminhão', 'Caminhão Articulado 745', 'DISPONIVEL', 3),
('Retroescavadeira', 'Retroescavadeira 416F2', 'DISPONIVEL', 1),
('Carregadeira', 'Pá Carregadeira 966H', 'MANUTENCAO', 2);

INSERT INTO emprestimo (data_retirada, data_prevista_devolucao, data_devolucao, status, id_usuario, id_equipamento) VALUES
('2026-09-01 08:00:00', '2026-09-05 17:00:00', '2026-09-04 16:30:00', 'DEVOLVIDO', 1, 2),
('2026-09-15 08:00:00', '2026-09-18 17:00:00', NULL, 'ATIVO', 2, 1);

INSERT INTO agendamento (data_inicio, data_fim, status_agendamento, id_usuario, id_equipamento) VALUES
('2026-09-25 08:00:00', '2026-09-25 17:00:00', 'AGENDADO', 2, 2),
('2026-09-28 08:00:00', '2026-09-28 17:00:00', 'AGENDADO', 1, 1);

INSERT INTO manutencao (data_manutencao, descricao_manutencao, tipo, status, id_equipamento) VALUES
('2026-09-10 09:00:00', 'Troca das escovas do motor', 'CORRETIVA', 'EM ANDAMENTO', 3),
('2026-08-20 14:00:00', 'Revisão geral e lubrificação', 'PREVENTIVA', 'CONCLUIDA', 1);

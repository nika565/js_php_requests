DROP DATABASE IF EXISTS request;
CREATE DATABASE request;
USE request;

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE,
    cargo ENUM('funcionario', 'adm') NOT NULL,
    senha VARCHAR(10) NOT NULL
);

INSERT INTO clientes(nome, email, cargo, senha) VALUES
('Luffy', 'luffy@email.com', 'adm', 'carne123'),
('Zoro', 'zoro@email.com', 'funcionario', 'pinga123'),
('Law', 'law@email.com', 'funcionario', 'corazon123'),
('Shanks', 'shanks@email.com', 'adm', 'luffy123'),
('Kid', 'kid@email.com', 'funcionario', 'metal123');

SELECT * FROM clientes;
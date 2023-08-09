DROP DATABASE IF EXISTS request;
CREATE DATABASE request;
USE request;

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE,
    cargo ENUM('funcionario', 'adm') NOT NULL,
    senha VARCHAR(256) NOT NULL
);

SELECT * FROM clientes;
-- Criação do banco de dados
CREATE DATABASE conecttecs_db;
USE conecttecs_db;

-- Tabela de clientes
CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL CHECK (telefone REGEXP '^[0-9]{10,11}$'),
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acesso TIMESTAMP NULL
);

-- Tabela de técnicos
CREATE TABLE tecnicos (
    id_tecnico INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL CHECK (telefone REGEXP '^[0-9]{10,11}$'),
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    certificado ENUM('sim', 'nao') NOT NULL,
    documento_certificado VARCHAR(255),
    cursando ENUM('sim', 'nao'),
    documento_matricula VARCHAR(255),
    valor_servico DECIMAL(10,2),
    descricao_tecnico TEXT NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acesso TIMESTAMP NULL
);


-- Criação do banco de dados
CREATE DATABASE conecttecs_db;

-- Seleciona o banco de dados
USE conecttecs_db;

-- Criação da tabela de clientes
CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL, -- Armazenada como hash
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criação da tabela de técnicos
CREATE TABLE tecnicos (
    id_tecnico INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL, -- Armazenada como hash
    certificado ENUM('sim', 'nao') NOT NULL, -- Indica se possui certificado
    documento_certificado VARCHAR(255), -- Caminho para o arquivo do certificado (se aplicável)
    cursando ENUM('sim', 'nao'), -- Indica se o técnico está cursando
    documento_matricula VARCHAR(255), -- Caminho para o comprovante de matrícula (se aplicável)
    especialidades TEXT NOT NULL, -- Lista de especialidades
    valor_servico DECIMAL(10,2),
    descricao_tecnico TEXT NOT NULL,
    foto VARCHAR(255), -- Caminho para a foto de perfil -- Pequena apresentação do técnico
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criação da tabela de serviços
CREATE TABLE servicos (
    id_servico INT AUTO_INCREMENT PRIMARY KEY,
    id_tecnico INT NOT NULL,
    valor DECIMAL(10, 2), -- Valor do serviço
    descricao TEXT, -- Descrição do serviço
    FOREIGN KEY (id_tecnico) REFERENCES tecnicos(id_tecnico) ON DELETE CASCADE
);

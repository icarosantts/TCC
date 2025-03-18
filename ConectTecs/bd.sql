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
    endereco VARCHAR(255),
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
    especialidades TEXT NOT NULL,
    valor_servico DECIMAL(10,2),
    descricao_tecnico TEXT NOT NULL,
    foto VARCHAR(255),
    cidade VARCHAR(100),
    estado VARCHAR(50),
    avaliacao_media DECIMAL(3,2) DEFAULT 0.00,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de agendamentos
CREATE TABLE agendamentos (
    id_agendamento INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    tecnico_id INT NOT NULL,
    data_agendamento DATETIME NOT NULL,
    descricao TEXT,
    status ENUM('pendente', 'confirmado', 'recusado') DEFAULT 'pendente',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id_cliente),
    FOREIGN KEY (tecnico_id) REFERENCES tecnicos(id_tecnico)
);

-- Tabela de mensagens
CREATE TABLE mensagens (
    id_mensagem INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    tecnico_id INT NOT NULL,
    mensagem TEXT NOT NULL,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id_cliente),
    FOREIGN KEY (tecnico_id) REFERENCES tecnicos(id_tecnico)
);

-- Tabela de avaliações
CREATE TABLE avaliacoes (
    id_avaliacao INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    tecnico_id INT NOT NULL,
    nota DECIMAL(3,2) NOT NULL,
    comentario TEXT,
    data_avaliacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id_cliente),
    FOREIGN KEY (tecnico_id) REFERENCES tecnicos(id_tecnico)
);
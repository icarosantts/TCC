-- Cria o banco de dados se não existir
CREATE DATABASE IF NOT EXISTS tecnicosDB;

-- Usa o banco de dados criado
USE tecnicosDB;

-- Cria a tabela 'tecnicos'
CREATE TABLE IF NOT EXISTS tecnicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    avaliacao DECIMAL(2, 1) CHECK (avaliacao >= 0 AND avaliacao <= 5),
    valorServico DECIMAL(10, 2) NOT NULL,
    area VARCHAR(50) NOT NULL,
    quantidadeServicos INT DEFAULT 0,
    apresentacao TEXT
); 


CREATE TABLE usuarios(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    senha VARCHAR(255) NOT NULL,
);

CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tecnico_nome VARCHAR(255) NOT NULL,
    data_hora DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE mensagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tecnico_nome VARCHAR(255) NOT NULL,
    mensagem TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



-- Insere dados de exemplo na tabela
INSERT INTO tecnicos (nome, avaliacao, valorServico, area, quantidadeServicos, apresentacao) VALUES
('João Silva', 4.5, 100.00, 'Elétrica', 50, 'Técnico elétrico com 10 anos de experiência.'),
('Maria Oliveira', 5.0, 120.00, 'Informática', 30, 'Especialista em suporte técnico e redes');

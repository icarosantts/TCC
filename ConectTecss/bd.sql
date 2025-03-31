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
    foto VARCHAR(255) DEFAULT 'img/perfil-padrao.jpg',
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
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
    especialidades TEXT NOT NULL,
    valor_servico DECIMAL(10,2),
    descricao_tecnico TEXT NOT NULL,
    foto VARCHAR(255) DEFAULT 'img/perfil-padrao.jpg',
    cidade VARCHAR(100),
    estado VARCHAR(50),
    avaliacao_media DECIMAL(3,2) DEFAULT 0.00,
    status ENUM('ativo', 'inativo') DEFAULT 'ativo',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acesso TIMESTAMP NULL
);

-- Tabela de agendamentos
CREATE TABLE agendamentos (
    id_agendamento INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    tecnico_id INT NOT NULL,
    data_agendamento DATETIME NOT NULL,
    descricao TEXT,
    status ENUM('pendente', 'confirmado', 'recusado', 'cancelado') DEFAULT 'pendente',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id_cliente),
    FOREIGN KEY (tecnico_id) REFERENCES tecnicos(id_tecnico)
);

-- Tabela de conversas (armazena metadados das conversas)
CREATE TABLE conversas (
    id_conversa INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    tecnico_id INT NOT NULL,
    ultima_mensagem TEXT,
    data_ultima_mensagem TIMESTAMP,
    mensagens_nao_lidas INT DEFAULT 0,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id_cliente),
    FOREIGN KEY (tecnico_id) REFERENCES tecnicos(id_tecnico),
    UNIQUE KEY (cliente_id, tecnico_id)
);

-- Tabela de mensagens (modificada para o chat)
CREATE TABLE mensagens (
    id_mensagem INT AUTO_INCREMENT PRIMARY KEY,
    conversa_id INT NOT NULL,
    remetente_id INT NOT NULL,
    destinatario_id INT NOT NULL,
    mensagem TEXT NOT NULL,
    lida ENUM('sim', 'nao') DEFAULT 'nao',
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (conversa_id) REFERENCES conversas(id_conversa)
);

-- Tabela de notificações de chat (para técnicos)
CREATE TABLE notificacoes_chat (
    id_notificacao INT AUTO_INCREMENT PRIMARY KEY,
    tecnico_id INT NOT NULL,
    cliente_id INT NOT NULL,
    mensagem TEXT,
    lida ENUM('sim', 'nao') DEFAULT 'nao',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tecnico_id) REFERENCES tecnicos(id_tecnico),
    FOREIGN KEY (cliente_id) REFERENCES clientes(id_cliente)
);

-- Tabela de avaliações
CREATE TABLE avaliacoes (
    id_avaliacao INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    tecnico_id INT NOT NULL,
    agendamento_id INT,
    nota DECIMAL(3,2) NOT NULL,
    comentario TEXT,
    data_avaliacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id_cliente),
    FOREIGN KEY (tecnico_id) REFERENCES tecnicos(id_tecnico),
    FOREIGN KEY (agendamento_id) REFERENCES agendamentos(id_agendamento)
);
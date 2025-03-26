const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const mysql = require('mysql2');

const app = express();
const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: "http://localhost", // Permite conexões do XAMPP
        methods: ["GET", "POST"]
    }
});

// Conexão com MySQL
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '', // Sua senha do MySQL (se tiver)
    database: 'conecttecs_db'
});

// Verifica conexão com MySQL
db.connect((err) => {
    if (err) {
        console.error('Erro ao conectar ao MySQL:', err);
        return;
    }
    console.log('Conectado ao MySQL!');
});

// Eventos do Socket.IO
io.on('connection', (socket) => {
    console.log('Usuário conectado:', socket.id);

    // Entrar em uma sala de chat
    socket.on('entrar_sala', (data) => {
        socket.join(`chat_${data.sala}`);
        console.log(`Usuário ${socket.id} entrou na sala chat_${data.sala}`);
    });

    // Enviar mensagem
    socket.on('enviar_mensagem', (data) => {
        // 1. Salva no MySQL
        db.query(
            'INSERT INTO mensagens (conversa_id, remetente_id, destinatario_id, mensagem) VALUES (?, ?, ?, ?)',
            [data.conversa_id, data.remetente_id, data.destinatario_id, data.mensagem],
            (err) => {
                if (err) return console.error('Erro ao salvar mensagem:', err);
                
                // 2. Transmite para a sala
                io.to(`chat_${data.conversa_id}`).emit('nova_mensagem', data);
                console.log(`Mensagem enviada na sala chat_${data.conversa_id}`);
            }
        );
    });

    // Desconexão
    socket.on('disconnect', () => {
        console.log('Usuário desconectado:', socket.id);
    });
});

// Inicia o servidor (APENAS UMA VEZ)
server.listen(3000, '127.0.0.1', () => {
    console.log('Servidor Socket.IO rodando em http://localhost:3000');
});
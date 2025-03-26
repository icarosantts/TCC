// Variáveis globais
const socket = io('http://localhost:3000');
let conversaAtual = null;
let usuarioId = null;
let usuarioTipo = null;
let carregandoMensagens = false;

// Inicialização do chat
document.addEventListener('DOMContentLoaded', () => {
    // Obter dados do usuário
    usuarioId = document.getElementById('usuario-id').value;
    usuarioTipo = document.getElementById('usuario-tipo').value;
    
    // Configurar eventos
    document.getElementById('enviar-mensagem').addEventListener('click', enviarMensagem);
    document.getElementById('campo-mensagem').addEventListener('input', verificarMensagem);
    document.getElementById('campo-mensagem').addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            enviarMensagem();
        }
    });
    document.getElementById('atualizar-conversas').addEventListener('click', carregarConversas);
    
    // Configurar Socket.IO
    configurarSocket();
});

function configurarSocket() {
    socket.on('connect', () => {
        console.log('Conectado ao servidor de chat');
        if (conversaAtual) {
            socket.emit('entrar_sala', { 
                sala: `chat_${conversaAtual.id}`,
                usuarioId: usuarioId
            });
        }
    });

    socket.on('nova_mensagem', (data) => {
        if (conversaAtual && data.conversa_id === conversaAtual.id) {
            adicionarMensagemNoChat(data);
            rolarParaBaixo();
        }
        carregarConversas(); // Atualiza a lista de conversas
    });

    socket.on('status_usuario', (data) => {
        if (conversaAtual && (
            data.usuario_id === conversaAtual.interlocutorId || 
            data.usuario_id === usuarioId
        )) {
            atualizarStatusUsuario(data);
        }
    });

    socket.on('disconnect', () => {
        console.log('Desconectado do servidor de chat');
    });
}

// Carrega a lista de conversas
function carregarConversas() {
    const lista = document.getElementById('lista-conversas');
    lista.innerHTML = '<div class="carregando-conversas"><i class="fas fa-spinner fa-spin"></i> Carregando conversas...</div>';
    
    fetch('chat/carregar_conversas.php')
        .then(response => response.json())
        .then(conversas => {
            lista.innerHTML = '';
            
            if (conversas.length === 0) {
                lista.innerHTML = '<div class="sem-conversas"><i class="fas fa-comment-slash"></i><p>Nenhuma conversa iniciada</p></div>';
                return;
            }
            
            conversas.forEach(conv => {
                const div = document.createElement('div');
                div.className = 'conversa' + (conv.mensagens_nao_lidas > 0 ? ' com-notificacao' : '');
                div.dataset.id = conv.id_conversa;
                div.dataset.interlocutorId = usuarioTipo === 'tecnico' ? conv.cliente_id : conv.tecnico_id;
                div.dataset.nome = conv.nome;
                
                div.innerHTML = `
                    <img src="${conv.foto || 'img/perfil-padrao.jpg'}" class="foto-perfil" alt="Foto de ${conv.nome}">
                    <div class="conversa-info">
                        <div class="conversa-cabecalho">
                            <strong>${conv.nome}</strong>
                            <span class="conversa-hora">${formatarData(conv.data_ultima_mensagem)}</span>
                        </div>
                        <p class="ultima-mensagem">${conv.ultima_mensagem || 'Nenhuma mensagem'}</p>
                        ${conv.mensagens_nao_lidas > 0 ? 
                            `<span class="notificacao">${conv.mensagens_nao_lidas}</span>` : ''}
                    </div>
                `;
                
                div.addEventListener('click', () => {
                    abrirConversa(
                        conv.id_conversa, 
                        conv.nome, 
                        usuarioTipo === 'tecnico' ? conv.cliente_id : conv.tecnico_id,
                        conv.foto
                    );
                });
                
                lista.appendChild(div);
            });
        })
        .catch(error => {
            console.error('Erro ao carregar conversas:', error);
            lista.innerHTML = '<div class="erro-carregamento"><i class="fas fa-exclamation-circle"></i><p>Erro ao carregar conversas</p></div>';
        });
}

// Abre uma conversa específica
function abrirConversa(conversaId, nomeInterlocutor, interlocutorId, fotoInterlocutor) {
    // Atualiza a conversa atual
    conversaAtual = { 
        id: conversaId, 
        nome: nomeInterlocutor, 
        interlocutorId: interlocutorId,
        foto: fotoInterlocutor
    };
    
    // Atualiza a UI
    document.getElementById('nome-interlocutor').textContent = nomeInterlocutor;
    document.getElementById('chat-input').style.display = 'flex';
    
    // Atualiza o cabeçalho com a foto
    const header = document.getElementById('chat-header');
    header.querySelector('h3').textContent = nomeInterlocutor;
    
    // Entra na sala de chat
    socket.emit('entrar_sala', { 
        sala: `chat_${conversaId}`,
        usuarioId: usuarioId
    });
    
    // Solicita status do interlocutor
    socket.emit('verificar_status', { usuarioId: interlocutorId });
    
    // Carrega as mensagens
    carregarMensagens(conversaId);
}

// Carrega mensagens de uma conversa
function carregarMensagens(conversaId) {
    if (carregandoMensagens) return;
    carregandoMensagens = true;
    
    const chatDiv = document.getElementById('mensagens-chat');
    chatDiv.innerHTML = '<div class="carregando-mensagens"><i class="fas fa-spinner fa-spin"></i> Carregando mensagens...</div>';
    
    fetch(`chat/carregar_mensagens.php?conversa_id=${conversaId}`)
        .then(response => response.json())
        .then(mensagens => {
            chatDiv.innerHTML = '';
            
            if (mensagens.length === 0) {
                chatDiv.innerHTML = '<div class="sem-mensagens"><i class="fas fa-comment-dots"></i><p>Nenhuma mensagem ainda</p></div>';
                return;
            }
            
            mensagens.forEach(msg => {
                adicionarMensagemNoChat(msg);
            });
            
            rolarParaBaixo();
        })
        .catch(error => {
            console.error('Erro ao carregar mensagens:', error);
            chatDiv.innerHTML = '<div class="erro-carregamento"><i class="fas fa-exclamation-circle"></i><p>Erro ao carregar mensagens</p></div>';
        })
        .finally(() => {
            carregandoMensagens = false;
        });
}

// Adiciona uma mensagem ao chat
function adicionarMensagemNoChat(msg) {
    const chatDiv = document.getElementById('mensagens-chat');
    
    // Remove placeholders se existirem
    const placeholders = chatDiv.querySelector('.sem-mensagens, .erro-carregamento, .carregando-mensagens');
    if (placeholders) {
        placeholders.remove();
    }
    
    const classe = msg.remetente_id == usuarioId ? 'minha-mensagem' : 'mensagem-outro';
    const hora = formatarHora(msg.data_envio);
    
    const mensagemDiv = document.createElement('div');
    mensagemDiv.className = `mensagem ${classe}`;
    mensagemDiv.innerHTML = `
        <div class="mensagem-conteudo">
            <p>${msg.mensagem}</p>
            <small>${hora}</small>
        </div>
    `;
    
    chatDiv.appendChild(mensagemDiv);
}

// Envia mensagem
function enviarMensagem() {
    const campo = document.getElementById('campo-mensagem');
    const mensagem = campo.value.trim();
    
    if (!mensagem || !conversaAtual) return;
    
    const botaoEnviar = document.getElementById('enviar-mensagem');
    botaoEnviar.disabled = true;
    botaoEnviar.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    const dados = {
        conversa_id: conversaAtual.id,
        remetente_id: usuarioId,
        destinatario_id: conversaAtual.interlocutorId,
        mensagem: mensagem
    };
    
    // Envia via Socket.IO para tempo real
    socket.emit('enviar_mensagem', dados);
    
    // Envia via AJAX para persistência
    fetch('chat/enviar_mensagem.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dados)
    })
    .then(response => {
        if (!response.ok) throw new Error('Erro ao enviar mensagem');
        campo.value = '';
        carregarConversas(); // Atualiza a lista de conversas
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao enviar mensagem. Tente novamente.');
    })
    .finally(() => {
        botaoEnviar.disabled = false;
        botaoEnviar.innerHTML = '<i class="fas fa-paper-plane"></i>';
    });
}

// Atualiza o status do interlocutor
function atualizarStatusUsuario(data) {
    const statusDiv = document.getElementById('status-interlocutor');
    if (!statusDiv) return;
    
    const bolaStatus = statusDiv.querySelector('.status-bola');
    const textoStatus = statusDiv.querySelector('small');
    
    if (data.online) {
        bolaStatus.style.backgroundColor = '#4CAF50';
        textoStatus.textContent = 'Online';
    } else {
        bolaStatus.style.backgroundColor = '#f44336';
        textoStatus.textContent = 'Offline';
    }
}

// Verifica se há mensagem para habilitar o botão
function verificarMensagem() {
    const campo = document.getElementById('campo-mensagem');
    const botao = document.getElementById('enviar-mensagem');
    botao.disabled = campo.value.trim() === '';
}

// Rola o chat para baixo
function rolarParaBaixo() {
    const chatDiv = document.getElementById('mensagens-chat');
    chatDiv.scrollTop = chatDiv.scrollHeight;
}

// Funções auxiliares de formatação
function formatarHora(dataString) {
    if (!dataString) return '';
    const data = new Date(dataString);
    return data.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function formatarData(dataString) {
    if (!dataString) return '';
    
    const hoje = new Date();
    const data = new Date(dataString);
    
    if (data.toDateString() === hoje.toDateString()) {
        return formatarHora(dataString);
    } else if (data.getFullYear() === hoje.getFullYear()) {
        return data.toLocaleDateString([], { month: 'short', day: 'numeric' });
    } else {
        return data.toLocaleDateString([], { year: 'numeric', month: 'short', day: 'numeric' });
    }
}

// Inicia o chat quando a seção é mostrada
document.addEventListener('DOMContentLoaded', () => {
    const secaoMensagens = document.getElementById('mensagens');
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'style') {
                const display = secaoMensagens.style.display;
                if (display === 'block' || display === '') {
                    iniciarChat();
                }
            }
        });
    });
    
    observer.observe(secaoMensagens, { attributes: true });
});
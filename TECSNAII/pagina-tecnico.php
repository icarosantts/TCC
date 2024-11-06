<?php
session_start();

// Verifica se o usuário está logado e se é do tipo técnico
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    // Se não estiver logado ou não for técnico, redireciona para a página de login
    header("Location: login.html");
    exit();
}

// Caso esteja logado e seja técnico, o conteúdo da página do técnico é exibido
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Técnico - TECSNAI</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1 class="logo"><a href="index.html">TECSNAI</a></h1>
            <nav class="menu">
                <ul class="nav-links">
                    <li><a href="#" onclick="mostrarSecao('painel')">Painel</a></li>
                    <li><a href="#" onclick="mostrarSecao('perfil')">Meu Perfil</a></li>
                    <li><a href="#" onclick="mostrarSecao('mensagens')">Mensagens</a></li>
                    <li><a href="#" onclick="mostrarSecao('ajuda')">Ajuda</a></li>
                    <li><a href="logout.php">Sair</a></li>
                    <li class="configuracoes-dropdown">
                        <a href="#">Configurações</a>
                        <ul class="submenu">
                            <li><a href="#" onclick="mostrarSecao('mudar-senha')">Mudar Senha</a></li>
                            <li><a href="#" onclick="mostrarSecao('alterar-email')">Alterar E-mail</a></li>
                            <li><a href="#" onclick="mostrarSecao('calendario')">Calendário</a></li>
                            <li><a href="#" onclick="mostrarSecao('excluir-conta')">Excluir Conta</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section id="painel" class="secao">
            <h2>Painel</h2>
            <div class="icone-agendamentos">
                <button onclick="mostrarSecao('agendamentos-confirmados')">Agendamentos Confirmados</button>
                <button onclick="mostrarSecao('pedidos-agendamentos')">Pedidos de Agendamentos</button>
            </div>
        </section>

        <section id="perfil" class="secao" style="display: none;">
            <h2>Meu Perfil</h2>
            <div id="perfil-info">
                <img id="foto-perfil" src="default-profile.png" alt="Foto de Perfil" />
                <p><strong>Nome:</strong> <span id="nome-tecnico"></span></p>
                <p><strong>Área Técnica:</strong> <span id="area-tecnica"></span></p>
                <p><strong>Descrição:</strong> <span id="descricao-tecnico"></span></p>
                <p><strong>Valor do Serviço:</strong> R$ <span id="valor-servico"></span></p>
                <button onclick="mostrarFormValor()">Editar Valor do Serviço</button>
            </div>
        
            <div id="form-editar-valor" style="display: none;">
                <h3>Adicionar/Editar Valor do Serviço</h3>
                <input type="number" id="novo-valor" placeholder="Novo Valor" />
                <button onclick="atualizarValor()">Salvar</button>
                <button onclick="cancelarEdicao()">Cancelar</button>
            </div>
        </section>                

        <section id="mensagens" class="secao" style="display: none;">
            <h2>Mensagens</h2>
            <div id="mensagens-list"></div>
        </section>

        <section id="ajuda" class="secao" style="display: none;">
            <h2>Ajuda</h2>
            <p>Precisa de ajuda? Consulte nossas informações de suporte ou entre em contato conosco.</p>
        </section>

        <section id="mudar-senha" class="secao" style="display: none;">
            <h2>Mudar Senha</h2>
            <!-- Formulário para mudar a senha -->
        </section>

        <section id="alterar-email" class="secao" style="display: none;">
            <h2>Alterar E-mail</h2>
            <!-- Formulário para alterar o e-mail -->
        </section>

        <section id="calendario" class="secao" style="display: none;">
            <h2>Calendário</h2>
            <p>Visualize seus agendamentos em um calendário interativo.</p>
        </section>

        <section id="excluir-conta" class="secao" style="display: none;">
            <h2>Excluir Conta</h2>
            <p>Cuidado! Esta ação é irreversível.</p>
            <!-- Botão ou formulário para confirmar exclusão da conta -->
        </section>

        <section id="agendamentos-confirmados" class="secao" style="display: none;">
            <h2>Agendamentos Confirmados</h2>
            <div id="agendamentos-confirmados-list"></div>
        </section>

        <section id="pedidos-agendamentos" class="secao" style="display: none;">
            <h2>Pedidos de Agendamentos</h2>
            <div id="pedidos-agendamentos-list"></div>
        </section>
    </main>

    <script>
        // Função para mostrar apenas a seção selecionada
        function mostrarSecao(secaoId) {
            const secoes = document.querySelectorAll('.secao');
            secoes.forEach(secao => secao.style.display = 'none');
            document.getElementById(secaoId).style.display = 'block';
        }

        // Função para carregar mensagens (exemplo de chamada de API)
        function carregarMensagens() {
            fetch('/buscar-mensagens.php')
                .then(response => response.json())
                .then(mensagens => {
                    const mensagensList = document.getElementById('mensagens-list');
                    mensagensList.innerHTML = ''; // Limpa a lista atual

                    mensagens.forEach(mensagem => {
                        const div = document.createElement('div');
                        div.innerHTML = `<p><strong>${mensagem.remetente}:</strong> ${mensagem.conteudo} (em: ${mensagem.data_envio})</p>`;
                        mensagensList.appendChild(div);
                    });
                })
                .catch(error => console.error('Erro ao carregar mensagens:', error));
        }

        // Função para carregar agendamentos (exemplo de chamada de API)
        function carregarAgendamentos() {
            fetch('/buscar-agendamentos.php')
                .then(response => response.json())
                .then(agendamentos => {
                    const confirmadosList = document.getElementById('agendamentos-confirmados-list');
                    confirmadosList.innerHTML = ''; // Limpa a lista atual

                    agendamentos.confirmados.forEach(agendamento => {
                        const div = document.createElement('div');
                        div.innerHTML = `<p><strong>Agendamento Confirmado:</strong> ${agendamento.data_hora} (em: ${agendamento.data_criacao})</p>`;
                        confirmadosList.appendChild(div);
                    });

                    const pedidosList = document.getElementById('pedidos-agendamentos-list');
                    pedidosList.innerHTML = ''; // Limpa a lista atual

                    agendamentos.pedidos.forEach(agendamento => {
                        const div = document.createElement('div');
                        div.innerHTML = `<p><strong>Pedido de Agendamento:</strong> ${agendamento.data_hora} (em: ${agendamento.data_criacao})</p>`;
                        pedidosList.appendChild(div);
                    });
                })
                .catch(error => console.error('Erro ao carregar agendamentos:', error));
        }

        // Carregar conteúdo inicial quando a página for carregada
        document.addEventListener('DOMContentLoaded', () => {
            mostrarSecao('painel'); // Exibir o painel por padrão
            carregarMensagens();
            carregarAgendamentos();
        });

        // Função para carregar o perfil do técnico
function carregarPerfil() {
    fetch('/buscar-perfil-tecnico.php') // URL do seu script PHP que retorna os dados do perfil
        .then(response => response.json())
        .then(data => {
            document.getElementById('nome-tecnico').textContent = data.nome;
            document.getElementById('area-tecnica').textContent = data.area_tecnica;
            document.getElementById('descricao-tecnico').textContent = data.descricao;
            document.getElementById('valor-servico').textContent = data.valor_servico || "Não definido";
            document.getElementById('foto-perfil').src = data.foto_perfil || 'default-profile.png'; // Default caso não tenha foto
        })
        .catch(error => console.error('Erro ao carregar perfil:', error));
}

// Função para mostrar o formulário de edição de valor
function mostrarFormValor() {
    document.getElementById('form-editar-valor').style.display = 'block';
}

// Função para atualizar o valor do serviço
function atualizarValor() {
    const novoValor = document.getElementById('novo-valor').value;

    fetch('/atualizar-valor-servico.php', { // URL do seu script PHP para atualizar o valor
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ valor_servico: novoValor }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('valor-servico').textContent = novoValor; // Atualiza o valor exibido
            cancelarEdicao();
        } else {
            alert('Erro ao atualizar o valor.');
        }
    })
    .catch(error => console.error('Erro ao atualizar valor:', error));
}

// Função para cancelar a edição do valor
function cancelarEdicao() {
    document.getElementById('form-editar-valor').style.display = 'none';
    document.getElementById('novo-valor').value = ''; // Limpa o campo
}

// Carregar perfil ao iniciar
document.addEventListener('DOMContentLoaded', carregarPerfil);

    </script>
</body>
</html>


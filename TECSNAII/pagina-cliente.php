<?php
session_start();

// Verifica se o usuário está logado e se é do tipo cliente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    // Se não estiver logado ou não for cliente, redireciona para a página de login
    header("Location: login.html");
    exit();
}

// Caso esteja logado e seja cliente, o conteúdo da página do cliente é exibido
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Usuário - TECSNAI</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1 class="logo"><a href="index.html">TECSNAI</a></h1>
            <nav class="menu">
                <ul class="nav-links">
                    <li><a href="#" onclick="mostrarSecao('buscar-tecnicos')">Buscar Técnicos</a></li>
                    <li><a href="#" onclick="mostrarSecao('agendar-servicos')">Agendar Serviços</a></li>
                    <li><a href="#" onclick="mostrarSecao('mensagens')">Mensagens</a></li>
                    <li><a href="#" onclick="mostrarSecao('meu-perfil')">Meu Perfil</a></li>
                    <li><a href="logout.php">Sair</a></li>
                    <li class="configuracoes-dropdown">
                        <a href="#">Configurações</a>
                        <ul class="submenu">
                            <li><a href="#" onclick="mostrarSecao('mudar-senha')">Mudar Senha</a></li>
                            <li><a href="#" onclick="mostrarSecao('alterar-email')">Alterar E-mail</a></li>
                            <li><a href="#" onclick="mostrarSecao('excluir-conta')">Excluir Conta</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <!-- Painel Resumo -->
        <section id="resumo" class="secao">
            <h2>Painel do Usuário</h2>
            <p>Resumo das interações, incluindo solicitações de agendamento, mensagens e avaliações.</p>
            <button onclick="mostrarSecao('historico-servicos')">Histórico de Serviços</button>
        </section>

        <!-- Buscar Técnicos -->
        <section id="buscar-tecnicos" class="secao" style="display: none;">
            <h2>Buscar Técnicos</h2>
            <p>Use a busca para encontrar técnicos qualificados.</p>
        </section>

        <!-- Agendar Serviços -->
        <section id="agendar-servicos" class="secao" style="display: none;">
            <h2>Agendar Serviços</h2>
            <p>Agende novos serviços com técnicos disponíveis.</p>
        </section>

        <!-- Mensagens -->
        <section id="mensagens" class="secao" style="display: none;">
            <h2>Mensagens</h2>
            <div id="mensagens-list"></div>
        </section>

        <!-- Meu Perfil -->
        <section id="meu-perfil" class="secao" style="display: none;">
            <h2>Meu Perfil</h2>
            <form action="atualizar_perfil.php" method="POST">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" required>

                <label for="preferencias">Preferências:</label>
                <textarea id="preferencias" name="preferencias"></textarea>

                <button type="submit">Salvar Alterações</button>
            </form>
        </section>

        <!-- Histórico de Serviços -->
        <section id="historico-servicos" class="secao" style="display: none;">
            <h2>Histórico de Serviços</h2>
            <div id="historico-servicos-list"></div>
        </section>

        <!-- Configurações -->
        <section id="mudar-senha" class="secao" style="display: none;">
            <h2>Mudar Senha</h2>
            <form action="mudar_senha.php" method="POST">
                <label for="nova-senha">Nova Senha:</label>
                <input type="password" id="nova-senha" name="nova_senha" required>
                <button type="submit">Alterar Senha</button>
            </form>
        </section>

        <section id="alterar-email" class="secao" style="display: none;">
            <h2>Alterar E-mail</h2>
            <form action="alterar_email.php" method="POST">
                <label for="novo-email">Novo E-mail:</label>
                <input type="email" id="novo-email" name="novo_email" required>
                <button type="submit">Alterar E-mail</button>
            </form>
        </section>

        <section id="excluir-conta" class="secao" style="display: none;">
            <h2>Excluir Conta</h2>
            <p>Cuidado! Esta ação é irreversível.</p>
            <form action="excluir_conta.php" method="POST">
                <button type="submit">Confirmar Exclusão</button>
            </form>
        </section>
    </main>

    <script>
        function mostrarSecao(secaoId) {
            document.querySelectorAll('.secao').forEach(secao => secao.style.display = 'none');
            document.getElementById(secaoId).style.display = 'block';
        }
        document.addEventListener('DOMContentLoaded', () => mostrarSecao('resumo'));
    </script>
</body>
</html>

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
    <title>Painel do Usuário - ConectTecs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1 class="logo"><a href="index.html">ConectTecs</a></h1>
            <nav class="menu">
                <ul class="nav-links">
                    <li><a href="#" onclick="carregarQuiz()">Buscar Técnicos</a></li>
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
        function carregarQuiz() {
            const quizHTML = `
                <h2>Estou Procurando um Técnico</h2>
                <p>Responda ao quiz abaixo para encontrar o técnico ideal para suas necessidades.</p>
                <form id="quiz-form" action="processar_quiz.php" method="post">
                    <div class="form-group">
                        <label for="tipo-tecnico">Que técnico você procura?</label>
                        <select id="tipo-tecnico" name="tipo-tecnico" required>
                            <option value="alimentos">Técnico em Alimentos</option>
                            <option value="automacao-industrial">Técnico em Automação Industrial</option>
                            <option value="mecatronica">Técnico em Mecatrônica</option>
                            <option value="manutencao-automotiva">Técnico em Manutenção Automotiva</option>
                            <option value="edificacoes">Técnico em Edificações</option>
                            <option value="design-calcados">Técnico em Design de Calçados</option>
                            <option value="eletroeletronica">Técnico em Eletroeletrônica</option>
                            <option value="eletronica">Técnico em Eletrônica</option>
                            <option value="eletrotecnica">Técnico em Eletrotécnica</option>
                            <option value="energia-renovavel">Técnico em Sistemas de Energia Renovável</option>
                            <option value="qualidade">Técnico em Qualidade</option>
                            <option value="multimidia">Técnico em Multimídia</option>
                            <option value="comunicacao-visual">Técnico em Comunicação Visual</option>
                            <option value="impressao-offset">Técnico em Impressão Offset</option>
                            <option value="impressao-rotografica-flexografica">Técnico em Impressão Rotográfica e Flexográfica</option>
                            <option value="processos-graficos">Técnico em Processos Gráficos</option>
                            <option value="portos">Técnico em Portos</option>
                            <option value="logistica">Técnico em Logística</option>
                            <option value="eletromecanica">Técnico em Eletromecânica</option>
                            <option value="fabricacao-mecanica">Técnico em Fabricação Mecânica</option>
                            <option value="manutencao-maquinas-industriais">Técnico em Manutenção de Máquinas Industriais</option>
                            <option value="mecanica">Técnico em Mecânica</option>
                            <option value="mecanica-precisao">Técnico em Mecânica de Precisão</option>
                            <option value="metalurgia">Técnico em Metalurgia</option>
                            <option value="soldagem">Técnico em Soldagem</option>
                            <option value="soldagem-papel">Técnico em Soldagem e Papel</option>
                            <option value="ceramica">Técnico em Cerâmica</option>
                            <option value="petroquimica">Técnico em Petroquímica</option>
                            <option value="plasticos">Técnico em Plásticos</option>
                            <option value="analises-quimicas">Técnico em Análises Químicas</option>
                            <option value="quimica">Técnico em Química</option>
                            <option value="refrigeracao-climatizacao">Técnico em Refrigeração e Climatização</option>
                            <option value="equipamentos-biomedicos">Técnico em Equipamentos Biomédicos</option>
                            <option value="seguranca-trabalho">Técnico em Segurança do Trabalho</option>
                            <option value="desenvolvimento-sistemas">Técnico em Desenvolvimento de Sistemas</option>
                            <option value="informatica">Técnico em Informática</option>
                            <option value="rede-computadores">Técnico em Rede de Computadores</option>
                            <option value="vestuario">Técnico em Vestuário</option>
                            <option value="textil">Técnico em Têxtil</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Filtrar valores de serviço (R$):</label>
                        <input type="number" id="valor-minimo" name="valor-minimo" placeholder="Valor Mínimo" required>
                        <input type="number" id="valor-maximo" name="valor-maximo" placeholder="Valor Máximo" required>
                    </div>

                    <button type="submit">Buscar Técnicos</button>
                </form>
            `;
            // Substitui o conteúdo da seção "Buscar Técnicos"
            document.getElementById('buscar-tecnicos').innerHTML = quizHTML;
            // Mostra a seção de "Buscar Técnicos"
            mostrarSecao('buscar-tecnicos');
        }

        function mostrarSecao(secaoId) {
            document.querySelectorAll('.secao').forEach(secao => secao.style.display = 'none');
            document.getElementById(secaoId).style.display = 'block';
        }
        document.addEventListener('DOMContentLoaded', () => mostrarSecao('resumo'));
    </script>
</body>
</html>

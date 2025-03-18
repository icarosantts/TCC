<?php
session_start();

// Verifica se o usuário está logado e se é do tipo técnico
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    // Se não estiver logado ou não for técnico, redireciona para a página de login
    header("Location: login.html");
    exit();
}

// Conexão com o banco de dados
require_once 'conexao.php';

// Obtém o ID do técnico da sessão
$tecnico_id = $_SESSION['usuario_id'];

// Busca os dados do técnico no banco de dados
$query = "SELECT * FROM tecnicos WHERE id_tecnico = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $tecnico_id);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se encontrou o técnico
if ($result->num_rows > 0) {
    $tecnico = $result->fetch_assoc();
} else {
    echo "Erro: Técnico não encontrado.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Técnico - ConectTecs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1 class="logo"><a href="index.html">ConectTecs</a></h1>
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
                            <li><a href="#" onclick="mostrarSecao('calendario')">Calendário</a></li>
                            <li><a href="#" onclick="mostrarSecao('excluir-conta')">Excluir Conta</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section id="painel" class="secao" style="display: none;">
            <h2>Painel</h2>
            <div class="icone-agendamentos">
                <button onclick="mostrarSecao('agendamentos-confirmados')">Agendamentos Confirmados</button>
                <button onclick="mostrarSecao('pedidos-agendamentos')">Pedidos de Agendamentos</button>
            </div>
        </section>

        <!-- Meu Perfil -->
        <section id="perfil" class="secao">
            <h2>Perfil do Técnico</h2>

            <!-- Exibição do perfil -->
            <div id="perfil-exibicao">
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($tecnico['nome']); ?></p>
                <p><strong>Telefone:</strong> <?php echo htmlspecialchars($tecnico['telefone']); ?></p>
                <p><strong>E-mail:</strong> <?php echo htmlspecialchars($tecnico['email']); ?></p>
                <p><strong>Área de Atuação:</strong> <?php echo htmlspecialchars($tecnico['especialidades']); ?></p>
                <p><strong>Valor de Serviços:</strong> <?php echo htmlspecialchars($tecnico['valor_servico']); ?></p>
                <p><strong>Descrição:</strong> <?php echo htmlspecialchars($tecnico['descricao_tecnico']); ?></p>
                
                <button type="button" onclick="mostrarEdicao()">Editar</button>
                <p id="mensagem-edicao" style="color: green;"></p>
            </div>

            <!-- Formulário de edição (oculto inicialmente) -->
            <form id="form-edicao" action="atualizar_perfil_tecnico.php" method="POST" style="display: none;">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($tecnico['nome']); ?>">
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($tecnico['telefone']); ?>">
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($tecnico['email']); ?>">
                </div>
                <div class="form-group">
                    <label for="especialidades">Área de Atuação:</label>
                    <input type="text" id="especialidades" name="especialidades" value="<?php echo htmlspecialchars($tecnico['especialidades']); ?>">
                </div>
                <div class="form-group">
                    <label for="valor_servico">Valor de Serviços:</label>
                    <input type="text" id="valor_servico" name="valor_servico" value="<?php echo htmlspecialchars($tecnico['valor_servico']); ?>">
                </div>
                <div class="form-group">
                    <label for="descricao_tecnico">Descrição:</label>
                    <textarea id="descricao_tecnico" name="descricao_tecnico"><?php echo htmlspecialchars($tecnico['descricao_tecnico']); ?></textarea>
                </div>
                <button type="submit">Salvar</button>
                <button type="button" onclick="cancelarEdicao()">Cancelar</button>
            </form>
        </section>


        <section id="mensagens" class="secao" style="display: none;">
            <h2>Mensagens</h2>
            <div id="mensagens-list"></div>
        </section>

        <section id="ajuda" class="secao" style="display: none;">
            <h2>Ajuda</h2>
            <p>Precisa de ajuda? Entre em contato conosco:</p>
                <li><strong>E-mail:</strong> suporte@conecttecs.com</li>
                <li><strong>Telefone:</strong> (00) 1234-5678</li>
        </section>

        <section id="mudar-senha" class="secao" style="display: none;">
        <h3>Mudar Senha</h3>
        <form action="mudar_senha_tecnico.php" method="post">
            <label for="senha_atual">Senha Atual:</label>
            <input type="password" id="senha_atual" name="senha_atual" required><br>
            <label for="nova_senha">Nova Senha:</label>
            <input type="password" id="nova_senha" name="nova_senha" required><br>
            <button type="submit">Atualizar Senha</button>
        </form>
        </section>

        <section id="alterar-email" class="secao" style="display: none;">
        <h3>Alterar E-mail</h3>
        <form action="mudar_email_tecnico.php" method="post">
            <label for="novo_email">Novo E-mail:</label>
            <input type="email" id="novo_email" name="novo_email" required><br>
            <button type="submit">Atualizar E-mail</button>
        </form>
        </section>

        <section id="calendario" class="secao" style="display: none;">
        <h3>Calendário</h3>
        <p>Aqui ficará o calendário interativo.</p>
        </section>

        <section id="excluir-conta" class="secao" style="display: none;">
            <h3>Excluir Conta</h3>
            <p>Cuidado! Esta ação é irreversível.</p>
            <form action="excluir_conta_tecnico.php" method="post">
                <label for="confirmacao">Digite "EXCLUIR" para confirmar:</label>
                <input type="text" id="confirmacao" name="confirmacao" required><br>
                <button type="submit">Excluir Conta</button>
            </form>
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
        // Função para mostrar o formulário de edição
        function mostrarEdicao() {
            document.getElementById("perfil-exibicao").style.display = "none";
            document.getElementById("form-edicao").style.display = "block";
        }

        // Função para cancelar a edição
        function cancelarEdicao() {
            document.getElementById("perfil-exibicao").style.display = "block";
            document.getElementById("form-edicao").style.display = "none";
        }

        // Função para enviar o formulário de edição via AJAX
        document.getElementById('form-edicao').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o recarregamento da página

            const formData = new FormData(this);

            fetch('atualizar_perfil_tecnico.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('mensagem-edicao').textContent = data;
                cancelarEdicao(); // Volta para a exibição normal após salvar
            })
            .catch(error => {
                console.error('Erro ao salvar:', error);
            });
        });

        // Função para mostrar a seção solicitada
        function mostrarSecao(secaoId) {
            // Oculta todas as seções
            document.querySelectorAll('.secao').forEach(secao => secao.style.display = 'none');
            
            // Mostra a seção solicitada
            document.getElementById(secaoId).style.display = 'block';
        }

        // Mostra a seção "Meu Perfil" ao carregar a página (opcional)
        document.addEventListener('DOMContentLoaded', () => mostrarSecao('perfil'));
    </script>
</body>
</html>

<?php
session_start();

// Verifica se o usuário está logado e se é do tipo técnico
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    header("Location: login.php");
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
    <style>
        .btn-ajuda {
            background: #f0f0f0;
            border: 1px solid #ddd;
            padding: 5px 10px;
            margin-top: 5px;
            cursor: pointer;
            font-size: 0.8em;
        }
        #whatsapp-help {
            background: #f8f8f8;
            padding: 10px;
            margin: 10px 0;
            border-left: 3px solid #25D366;
        }
        .whatsapp-link {
            color: #25D366;
            text-decoration: none;
            font-weight: bold;
        }
        .whatsapp-link:hover {
            text-decoration: underline;
        }
        input[type="url"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
        .secao {
            display: none;
        }
        .secao.ativa {
            display: block;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1 class="logo"><a href="index.html">ConectTecs</a></h1>
            <nav class="menu">
                <ul class="nav-links">
                    <li><a href="#" onclick="mostrarSecao('perfil')" class="ativa">Meu Perfil</a></li>
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
        <section id="perfil" class="secao ativa">
            <h2>Perfil do Técnico</h2>

            <div id="perfil-exibicao">
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($tecnico['nome']); ?></p>
                <p><strong>WhatsApp:</strong> 
                    <a href="<?php echo htmlspecialchars($tecnico['whatsapp_link']); ?>" class="whatsapp-link" target="_blank">
                        Contato via WhatsApp
                    </a>
                </p>
                <p><strong>E-mail:</strong> <?php echo htmlspecialchars($tecnico['email']); ?></p>
                <p><strong>Área de Atuação:</strong> <?php echo htmlspecialchars($tecnico['especialidades']); ?></p>
                <p><strong>Valor de Serviços:</strong> R$ <?php echo number_format($tecnico['valor_servico'], 2, ',', '.'); ?></p>
                <p><strong>Descrição:</strong> <?php echo htmlspecialchars($tecnico['descricao_tecnico']); ?></p>
                
                <button type="button" onclick="mostrarEdicao()">Editar Perfil</button>
                <p id="mensagem-edicao" style="color: green;"></p>
            </div>

            <form id="form-edicao" action="atualizar_perfil_tecnico.php" method="POST" style="display: none;">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($tecnico['nome']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="whatsapp_link">Link do WhatsApp:</label>
                    <input type="url" id="whatsapp_link" name="whatsapp_link" 
                           value="<?php echo htmlspecialchars($tecnico['whatsapp_link']); ?>"
                           placeholder="https://wa.me/5511999999999" required>
                    <small>Formato: https://wa.me/5511999999999 (incluindo código do país e DDD)</small>
                    <button type="button" class="btn-ajuda" onclick="mostrarAjudaWhatsApp()">Como obter meu link?</button>
                    <div id="whatsapp-help" style="display:none;">
                        <p>1. Abra o WhatsApp no seu celular</p>
                        <p>2. Vá em Configurações > Conta</p>
                        <p>3. Seu número aparecerá no formato: 55DDDNNNNNNNN</p>
                        <p>4. Insira no campo: https://wa.me/55DDDNNNNNNNN</p>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($tecnico['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="especialidades">Área de Atuação:</label>
                    <input type="text" id="especialidades" name="especialidades" 
                           value="<?php echo htmlspecialchars($tecnico['especialidades']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="valor_servico">Valor de Serviços (R$):</label>
                    <input type="number" id="valor_servico" name="valor_servico" 
                           value="<?php echo htmlspecialchars($tecnico['valor_servico']); ?>" 
                           step="0.01" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="descricao_tecnico">Descrição:</label>
                    <textarea id="descricao_tecnico" name="descricao_tecnico" rows="4" required><?php 
                        echo htmlspecialchars($tecnico['descricao_tecnico']); 
                    ?></textarea>
                </div>
                
                <button type="submit">Salvar Alterações</button>
                <button type="button" onclick="cancelarEdicao()">Cancelar</button>
            </form>
        </section>

        <section id="ajuda" class="secao">
            <h2>Ajuda</h2>
            <p>Precisa de ajuda? Entre em contato conosco:</p>
            <ul>
                <li><strong>E-mail:</strong> suporte@conecttecs.com</li>
                <li><strong>Telefone:</strong> (00) 1234-5678</li>
            </ul>
        </section>

        <section id="mudar-senha" class="secao">
            <h3>Mudar Senha</h3>
            <form action="mudar_senha_tecnico.php" method="post">
                <div class="form-group">
                    <label for="senha_atual">Senha Atual:</label>
                    <input type="password" id="senha_atual" name="senha_atual" required>
                </div>
                <div class="form-group">
                    <label for="nova_senha">Nova Senha:</label>
                    <input type="password" id="nova_senha" name="nova_senha" required>
                </div>
                <button type="submit">Atualizar Senha</button>
            </form>
        </section>

        <section id="calendario" class="secao">
            <h3>Calendário</h3>
            <p>Aqui ficará o calendário interativo.</p>
        </section>

        <section id="excluir-conta" class="secao">
            <h3>Excluir Conta</h3>
            <p>Cuidado! Esta ação é irreversível.</p>
            <form action="excluir_conta_tecnico.php" method="post">
                <div class="form-group">
                    <label for="confirmacao">Digite "EXCLUIR" para confirmar:</label>
                    <input type="text" id="confirmacao" name="confirmacao" required>
                </div>
                <button type="submit">Excluir Conta</button>
            </form>
        </section>

        <section id="agendamentos-confirmados" class="secao">
            <h2>Agendamentos Confirmados</h2>
            <div id="agendamentos-confirmados-list"></div>
        </section>

        <section id="pedidos-agendamentos" class="secao">
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
            document.getElementById("mensagem-edicao").textContent = "";
        }

        // Função para mostrar ajuda do WhatsApp
        function mostrarAjudaWhatsApp() {
            const helpDiv = document.getElementById('whatsapp-help');
            helpDiv.style.display = helpDiv.style.display === 'none' ? 'block' : 'none';
        }

        // Validação do WhatsApp em tempo real
        document.getElementById('whatsapp_link').addEventListener('blur', function() {
            const value = this.value.trim();
            if (value && !/^https:\/\/wa\.me\/\d{10,15}$/.test(value)) {
                alert('Formato inválido! Use: https://wa.me/5511999999999');
                this.focus();
            }
        });

        // Envio do formulário via AJAX
        document.getElementById('form-edicao').addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Validação do WhatsApp
            const whatsappLink = document.getElementById('whatsapp_link').value;
            if (!/^https:\/\/wa\.me\/\d{10,15}$/.test(whatsappLink)) {
                alert('Por favor, insira um link válido do WhatsApp no formato: https://wa.me/5511999999999');
                return false;
            }

            const formData = new FormData(this);

            fetch('atualizar_perfil_tecnico.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('mensagem-edicao').textContent = data;
                setTimeout(() => {
                    location.reload(); // Recarrega a página para atualizar os dados
                }, 1500);
            })
            .catch(error => {
                console.error('Erro ao salvar:', error);
                document.getElementById('mensagem-edicao').textContent = "Erro ao atualizar perfil.";
            });
        });

        // Função para mostrar seções
        function mostrarSecao(secaoId) {
            // Oculta todas as seções
            document.querySelectorAll('.secao').forEach(secao => {
                secao.classList.remove('ativa');
            });
            
            // Mostra a seção solicitada
            document.getElementById(secaoId).classList.add('ativa');
            
            // Atualiza o menu ativo
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.classList.remove('ativa');
            });
            event.target.classList.add('ativa');
        }

        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            // Validação do formulário
            document.getElementById('form-edicao').addEventListener('submit', function(e) {
                const whatsappLink = document.getElementById('whatsapp_link').value;
                
                if (!/^https:\/\/wa\.me\/\d{10,15}$/.test(whatsappLink)) {
                    e.preventDefault();
                    alert('Por favor, insira um link válido do WhatsApp no formato: https://wa.me/5511999999999');
                    return false;
                }
                return true;
            });
        });
    </script>
</body>
</html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
session_start();

// Verifica se o usuário está logado e se é do tipo cliente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

// Conecta ao banco de dados
require_once 'conexao.php'; // Arquivo de conexão com o banco de dados

// Recupera as informações do cliente
$cliente_id = $_SESSION['usuario_id'];
$sql = "SELECT nome, telefone, email FROM clientes WHERE id_cliente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$stmt->bind_result($nome, $telefone, $email);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Usuário - ConectTecs</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos para o modal de agendamento */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .modal-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .modal-buttons button {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
        }
        
        .modal-buttons button[type="submit"] {
            background-color: #2563eb;
            color: white;
        }
        
        .modal-buttons button[type="button"] {
            background-color: #f3f4f6;
            color: #4b5563;
        }

        /* Estilos para os botões de ação */
        .acoes {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .acoes button {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            background-color: #2563eb;
            color: white;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .acoes button:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1 class="logo"><a href="index.php">ConectTecs</a></h1>
            <nav class="menu">
                <ul class="nav-links">
                    <li><a href="#" onclick="carregarQuiz()">Buscar Técnicos</a></li>
                    <li><a href="#" onclick="mostrarSecao('meu-perfil')">Meu Perfil</a></li>
                    <li><a href="#" onclick="mostrarSecao('ajuda')">Ajuda</a></li>
                    <li><a href="logout.php">Sair</a></li>
                    <li class="configuracoes-dropdown">
                        <a href="#">Configurações</a>
                        <ul class="submenu">
                            <li><a href="#" onclick="mostrarSecao('mudar-senha')">Mudar Senha</a></li>
                            <li><a href="#" onclick="mostrarSecao('excluir-conta')">Excluir Conta</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>

        <!-- Buscar Técnicos -->
        <section id="buscar-tecnicos" class="secao" style="display: none;">
            <h2>Buscar Técnicos</h2>
            <div id="quiz-container"></div>
            <div id="resultados-busca"></div>
        </section>

        <!-- Agendar Serviços -->
        <section id="agendar-servicos" class="secao" style="display: none;">
            <h2>Agendar Serviços</h2>
            <p>Agende novos serviços com técnicos disponíveis.</p>
        </section>

        <!-- Meu Perfil -->
        <section id="meu-perfil" class="secao" style="display: none;">
            <h2>Meu Perfil</h2>

            <!-- Exibição do perfil -->
            <div id="perfil-exibicao">
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($nome); ?></p>
                <p><strong>Telefone:</strong> <?php echo htmlspecialchars($telefone); ?></p>
                <p><strong>E-mail:</strong> <?php echo htmlspecialchars($email); ?></p>
                
                <button type="button" onclick="mostrarEdicao()">Editar</button>
                <p id="mensagem-edicao" style="color: green;"></p>
            </div>

            <!-- Formulário de edição (oculto inicialmente) -->
            <form id="form-edicao" action="atualizar_perfil_cliente.php" method="POST" style="display: none;">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>">
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>">
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <button type="submit">Salvar</button>
                <button type="button" onclick="cancelarEdicao()">Cancelar</button>
            </form>
        </section>

        <section id="ajuda" class="secao" style="display: none;">
            <h2>Ajuda</h2>
            <p>Precisa de ajuda? Entre em contato conosco:</p>
                <li><strong>E-mail:</strong> suporte@conecttecs.com</li>
                <li><strong>Telefone:</strong> (00) 1234-5678</li>
        </section>

        <section id="mudar-senha" class="secao" style="display: none;">
            <h3>Mudar Senha</h3>
            <form action="mudar_senha_cliente.php" method="post">
                <label for="senha_atual">Senha Atual:</label>
                <input type="password" id="senha_atual" name="senha_atual" required><br>
                <label for="nova_senha">Nova Senha:</label>
                <input type="password" id="nova_senha" name="nova_senha" required><br>
                <button type="submit">Atualizar Senha</button>
            </form>
        </section>

        <section id="excluir-conta" class="secao" style="display: none;">
            <h3>Excluir Conta</h3>
            <p>Cuidado! Esta ação é irreversível.</p>
            <form action="excluir_conta_cliente.php" method="post">
                <label for="confirmacao">Digite "EXCLUIR" para confirmar:</label>
                <input type="text" id="confirmacao" name="confirmacao" required><br>
                <button type="submit">Excluir Conta</button>
            </form>
        </section>
    </main>

    <script>
    function mostrarSecao(secaoId) {
        // Oculta todas as seções
        document.querySelectorAll('.secao').forEach(secao => secao.style.display = 'none');
        
        // Mostra a seção solicitada
        document.getElementById(secaoId).style.display = 'block';
    } 
       
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

        fetch('atualizar_perfil_cliente.php', {
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

    document.addEventListener('DOMContentLoaded', () => mostrarSecao('meu-perfil'));

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
                    <label>Faixa de preço (R$):</label>
                    <div style="display: flex; gap: 1rem;">
                        <input type="number" id="valor-minimo" name="valor-minimo" placeholder="Mínimo" required>
                        <input type="number" id="valor-maximo" name="valor-maximo" placeholder="Máximo" required>
                    </div>
                </div>

                <div class="acoes">
                    <button type="submit">Buscar Técnicos</button>
                </div>
            </form>
        `;
        // Substitui o conteúdo da seção "Buscar Técnicos"
        document.getElementById('buscar-tecnicos').innerHTML = quizHTML;
        // Mostra a seção de "Buscar Técnicos"
        mostrarSecao('buscar-tecnicos');
    }

    // Função para iniciar agendamento
    function iniciarAgendamento(tecnicoId, tecnicoNome) {
        const modalHTML = `
            <div class="modal-overlay" id="modal-agendamento">
                <div class="modal-content">
                    <h3>Agendar com ${tecnicoNome}</h3>
                    <form id="form-agendamento">
                        <input type="hidden" name="tecnico_id" value="${tecnicoId}">
                        
                        <div class="form-group">
                            <label for="data_agendamento">Data:</label>
                            <input type="date" id="data_agendamento" name="data_agendamento" required min="${new Date().toISOString().split('T')[0]}">
                        </div>
                        
                        <div class="form-group">
                            <label for="hora_agendamento">Horário:</label>
                            <input type="time" id="hora_agendamento" name="hora_agendamento" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="descricao">Descrição do serviço:</label>
                            <textarea id="descricao" name="descricao" required></textarea>
                        </div>
                        
                        <div class="modal-buttons">
                            <button type="submit">Solicitar Agendamento</button>
                            <button type="button" onclick="fecharModal('modal-agendamento')">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        document.getElementById('form-agendamento').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            // Enviar via AJAX para o banco de dados
            fetch('processar_agendamento.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert('Agendamento solicitado com sucesso! Aguarde a confirmação do técnico.');
                document.getElementById('modal-agendamento').remove();
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Ocorreu um erro ao solicitar o agendamento.');
            });
        });
    }
    
    // Função auxiliar para fechar modal
    function fecharModal(modalId) {
        const modal = document.getElementById(modalId);
        if(modal) modal.remove();
    }

    </script>
</body>
</html>
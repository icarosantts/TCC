<?php
// Inicia a sessão
session_start();

// Verifica se o usuário está logado e se é do tipo cliente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    header("Location: login.html");
    exit();
}

// Inclui a conexão com o banco de dados
require_once 'conexao.php';

// Obtém os dados enviados pelo quiz
$tipo_tecnico = $_POST['tipo-tecnico'] ?? '';
$valor_minimo = $_POST['valor-minimo'] ?? 0;
$valor_maximo = $_POST['valor-maximo'] ?? 999999;

// Consulta ao banco de dados para buscar técnicos que atendem aos critérios
$sql = "SELECT nome, especialidades, valor_servico, descricao_tecnico 
        FROM tecnicos 
        WHERE especialidades LIKE ? 
          AND valor_servico BETWEEN ? AND ?";

$stmt = $conn->prepare($sql);
$tipo_tecnico_like = '%' . $tipo_tecnico . '%';
$stmt->bind_param("sdd", $tipo_tecnico_like, $valor_minimo, $valor_maximo);

$stmt->execute();
$result = $stmt->get_result();

// Gera a página com estilo consistente
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Busca - ConectTecs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
        <div class="container">
            <h1 class="logo"><a href="index.html">ConectTecs</a></h1>
            <nav class="menu">
                <ul class="nav-links">
                    <li><a href="#" onclick="carregarQuiz()">Buscar Técnicos</a></li>
                    <li><a href="#" onclick="mostrarSecao('meu-perfil')">Meu Perfil</a></li>
                    <li><a href="#" onclick="mostrarSecao('mensagens')">Mensagens</a></li>
                    <li><a href="#" onclick="mostrarSecao('ajuda')">Ajuda</a></li>
                    
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
    <section class="resultados">
        <h2>Técnicos Encontrados</h2>
        <?php if ($result->num_rows > 0): ?>
            <div class="tecnicos-list">
                <?php while ($tecnico = $result->fetch_assoc()): ?>
                    <div class="tecnico-card">
                        <h3><?php echo htmlspecialchars($tecnico['nome']); ?></h3>
                        <p><strong>Especialidade:</strong> <?php echo htmlspecialchars($tecnico['especialidades']); ?></p>
                        <p><strong>Valor do Serviço:</strong> R$ <?php echo number_format($tecnico['valor_servico'], 2, ',', '.'); ?></p>
                        <p><strong>Descrição:</strong> <?php echo htmlspecialchars($tecnico['descricao_tecnico']); ?></p>
                        <div class="acoes">
                            <button onclick="agendarServico('<?php echo $tecnico['nome']; ?>')">Agendar Serviço</button>
                            <button onclick="enviarMensagem('<?php echo $tecnico['nome']; ?>')">Enviar Mensagem</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Nenhum técnico encontrado para os critérios selecionados.</p>
        <?php endif; ?>
    </section>
</main>
<script>
    function agendarServico(nomeTecnico) {
        alert("Agendamento solicitado para " + nomeTecnico);
        // Implementar redirecionamento ou lógica para agendamento
    }

    function enviarMensagem(nomeTecnico) {
        alert("Mensagem solicitada para " + nomeTecnico);
        // Implementar redirecionamento ou lógica para mensagens
    }
</script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>

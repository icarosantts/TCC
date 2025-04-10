<?php
session_start();

// Verifica se o usuário está logado e se é do tipo cliente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

// Inclui a conexão com o banco de dados
require_once 'conexao.php';

// Obtém os dados enviados pelo quiz
$tipo_tecnico = $_POST['tipo-tecnico'] ?? '';
$valor_minimo = $_POST['valor-minimo'] ?? 0;
$valor_maximo = $_POST['valor-maximo'] ?? 999999;

// Consulta ao banco de dados para buscar técnicos que atendem aos critérios
$sql = "SELECT id_tecnico, nome, especialidades, valor_servico, descricao_tecnico, whatsapp_link 
        FROM tecnicos 
        WHERE especialidades LIKE ? 
          AND valor_servico BETWEEN ? AND ?
          AND status = 'ativo'";

$stmt = $conn->prepare($sql);
$tipo_tecnico_like = '%' . $tipo_tecnico . '%';
$stmt->bind_param("sdd", $tipo_tecnico_like, $valor_minimo, $valor_maximo);

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Busca - ConectTecs</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            text-decoration: none;
        }
        .logout-btn {
            background-color: #f3f4f6;
            color: #4b5563;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #e5e7eb;
        }
        .tecnico-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .whatsapp-btn {
            background-color: #25D366;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-weight: 500;
        }
        .whatsapp-btn:hover {
            background-color: #128C7E;
        }
        .acoes {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .mensagem-padrao {
            margin-top: 10px;
            font-size: 0.9em;
            color: #666;
        }
        .refazer-quiz-btn {
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        .refazer-quiz-btn:hover {
            background-color: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.html" class="logo">ConectTecs</a>
        <a href="logout.php" class="logout-btn">Sair</a>
    </div>

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
                            
                            <div class="mensagem-padrao">
                                Clique no botão abaixo para iniciar o agendamento diretamente pelo WhatsApp:
                            </div>
                            
                            <div class="acoes">
                                <a href="<?php echo gerarLinkWhatsApp($tecnico['whatsapp_link'], $tecnico['nome']); ?>" 
                                   class="whatsapp-btn" 
                                   target="_blank">
                                   <img src="whatsapp-icon.png" alt="" width="20"> Agendar via WhatsApp
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>Nenhum técnico encontrado para os critérios selecionados.</p>
            <?php endif; ?>
            
            <button onclick="refazerQuiz()" class="refazer-quiz-btn">Refazer Quiz</button>
        </section>
    </main>

    <script>
        // Função para voltar à página do quiz
        function refazerQuiz() {
            window.location.href = 'pagina-cliente.php';
        }
    </script>
</body>
</html>

<?php
// Função para gerar link do WhatsApp com mensagem pré-formatada
function gerarLinkWhatsApp($whatsapp_link, $tecnico_nome) {
    $cliente_nome = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : 'Cliente';
    $mensagem = "Olá " . urlencode($tecnico_nome) . ", sou " . urlencode($cliente_nome) . 
                " e gostaria de agendar um serviço através do ConectTecs. Podemos conversar?";
    
    // Verifica se o link já tem o prefixo https://wa.me/
    if (strpos($whatsapp_link, 'https://wa.me/') === 0) {
        return $whatsapp_link . '?text=' . $mensagem;
    }
    
    // Se for apenas um número, formata corretamente
    $numero = preg_replace('/[^0-9]/', '', $whatsapp_link);
    return 'https://wa.me/55' . $numero . '?text=' . $mensagem;
}

$stmt->close();
$conn->close();
?>
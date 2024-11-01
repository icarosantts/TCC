<?php
$servername = "localhost"; // Servidor do banco de dados
$username = "root"; // Usuário do banco de dados
$password = "";   // Senha do banco de dados
$dbname = "tecnicosDB";  // Nome do banco de dados

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Captura os dados enviados pelo formulário
$tipoTecnico = '';
$tipoServico = '';
$certificacao = '';
$valorMinimo = 0;
$valorMaximo = PHP_INT_MAX; // Um valor alto para pegar todos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipoTecnico = $_POST['tipo-tecnico'] ?? '';
    $tipoServico = $_POST['tipo-servico'] ?? '';
    $certificacao = $_POST['certificacao'] ?? '';
    $valorMinimo = $_POST['valor-minimo'] ?? 0;
    $valorMaximo = $_POST['valor-maximo'] ?? PHP_INT_MAX;

    // Prepara a consulta SQL
    $stmt = $conn->prepare("SELECT * FROM tecnicos WHERE 
        (area = ? OR ? = '') AND 
        (tipo_servico = ? OR ? = '') AND 
        (certificacao = ? OR ? = '') AND 
        (valor_servico BETWEEN ? AND ?)");

    // Vincula os parâmetros
    $stmt->bind_param("ssssdddd", $tipoTecnico, $tipoTecnico, $tipoServico, $tipoServico, $certificacao, $certificacao, $valorMinimo, $valorMaximo);
    $stmt->execute();
    $result = $stmt->get_result();

    // Começo da estrutura HTML
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Resultados da Busca - TECSNAI</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <header>
            <div class="container">
                <h1 class="logo"><a href="index.html">TECSNAI</a></h1>
                <nav class="menu">
                    <ul class="nav-links">
                        <li><a href="index.html">Página Inicial</a></li>
                        <li><a href="seja-tecnico.html">Seja Técnico</a></li>
                        <li><a href="login.html">Login</a></li>
                    </ul>
                </nav>
            </div>    
        </header>

        <main>
            <section id="tecnicos-list">
                <?php
                if ($result->num_rows > 0) {
                    echo "<h1>Resultados da Busca</h1>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='tecnico-card'>";
                        echo "<h3>" . htmlspecialchars($row['nome']) . "</h3>";
                        echo "<p>Avaliação: " . htmlspecialchars($row['avaliacao']) . " / 5</p>";
                        echo "<p>Área Técnica: " . htmlspecialchars($row['area']) . "</p>";
                        echo "<p>Serviços Realizados: " . htmlspecialchars($row['quantidade_servicos']) . "</p>";
                        echo "<p>" . htmlspecialchars($row['apresentacao']) . "</p>";
                        echo "<div class='button-container'>
                                  <button onclick=\"enviarMensagem('" . htmlspecialchars($row['nome']) . "')\">Enviar Mensagem</button>
                                  <button onclick=\"agendarServico('" . htmlspecialchars($row['nome']) . "')\">Agendar Serviço</button>
                              </div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>Nenhum técnico encontrado para os critérios selecionados.</p>";
                }
                ?>
            </section>
        </main>

        <footer>
            <p>&copy; 2024 TECSNAI. Todos os direitos reservados.</p>
        </footer>
    </body>
    </html>
    <?php
} else {
    // Redirecionar de volta para o formulário se não for uma requisição POST
    header("Location: index.html");
    exit();
}

$conn->close();
?>

<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado e é do tipo técnico
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    echo "Usuário não logado ou tipo de usuário incorreto."; // Mensagem de depuração
    header("Location: login.php"); // Redireciona para a página de login
    exit();
}

// Depuração: Verificar conteúdo da sessão
echo "<pre>";
print_r($_SESSION); // Verifique o conteúdo da variável de sessão
echo "</pre>";

include 'conexao.php'; // Conexão com o banco de dados

// Verificar se a conexão foi bem-sucedida
if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

// Obtém o ID do técnico da sessão
$tecnico_id = $_SESSION['usuario_id'];

// Depuração: Verificar se o ID do técnico está correto
echo "ID do Técnico: " . $tecnico_id . "<br>";

$query = "SELECT nome, especialidades, descricao_tecnico, valor_servico FROM tecnicos WHERE id_tecnico = ?";
$stmt = $conn->prepare($query);

// Verificar se a consulta foi preparada corretamente
if (!$stmt) {
    die("Erro ao preparar a consulta: " . $conn->error);
}

$stmt->bind_param("i", $tecnico_id);
$stmt->execute();
$result = $stmt->get_result();

// Verificar se a consulta retornou resultados
if ($result->num_rows > 0) {
    $tecnico = $result->fetch_assoc();
    
    // Exibe os dados do técnico para depuração
    echo "<pre>";
    print_r($tecnico);
    echo "</pre>";
} else {
    echo "Erro: Técnico não encontrado.";
    exit();
}

$stmt->close();
$conn->close();
?>

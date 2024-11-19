<?php
session_start();

// Verifica se o usuário está logado e é do tipo técnico
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    header("Location: login.html");
    exit();
}

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tecsnai_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obtém o ID do técnico da sessão
$tecnico_id = $_SESSION['usuario_id'];

// Busca os dados do técnico no banco de dados
$query = "SELECT nome, especialidades, descricao_tecnico, valor_servico FROM tecnicos WHERE id_tecnico = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $tecnico_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $tecnico = $result->fetch_assoc();
    
    // Verificar se a descrição e a foto estão sendo recuperadas
    echo "<pre>";
    print_r($tecnico);  // Exibe todos os dados do técnico para depuração
    echo "</pre>";
} else {
    echo "Erro: Técnico não encontrado.";
    exit();
}

$stmt->close();
$conn->close();
?>

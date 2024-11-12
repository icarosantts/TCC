<?php
session_start(); // Inicia a sessão

// Dados de conexão
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tecsnai_db";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o técnico está logado
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    header("Location: login.php");
    exit;
}

$tecnico_id = $_SESSION['usuario_id'];

// Corrige a consulta SQL usando a variável correta
$sql = "SELECT nome, foto, especialidades, valor_servico, descricao_tecnico FROM tecnicos WHERE id_tecnico = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tecnico_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Recupera os dados do técnico
    $row = $result->fetch_assoc();
    $nome = $row['nome'];
    $foto = !empty($row['foto']) ? $row['foto'] : 'default-profile.png'; // Usa uma imagem padrão se não houver foto
    $especialidades = $row['especialidades'];
    $valor_servico = $row['valor_servico'];
    $descricao_tecnico = $row['descricao_tecnico'];
} else {
    echo "Nenhum técnico encontrado!";
    exit;
}

$stmt->close();
$conn->close();
?>

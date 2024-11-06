<?php
session_start();
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

$id_tecnico = $_SESSION['id_tecnico']; // ID do técnico logado
$query = "SELECT nome, area_tecnica, descricao, valor_servico, foto_perfil FROM tecnicos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_tecnico);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode([]);
}
?>

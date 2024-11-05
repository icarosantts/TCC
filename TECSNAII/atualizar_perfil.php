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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $preferencias = $_POST['preferencias'];
    $id_usuario = $_SESSION['id_usuario'];

    $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, telefone = ?, preferencias = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nome, $telefone, $preferencias, $id_usuario);

    if ($stmt->execute()) {
        echo "Perfil atualizado com sucesso.";
    } else {
        echo "Erro ao atualizar o perfil.";
    }
}
?>

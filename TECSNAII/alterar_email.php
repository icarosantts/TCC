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
    $novo_email = $_POST['novo_email'];
    $id_usuario = $_SESSION['id_usuario'];

    $stmt = $conn->prepare("UPDATE usuarios SET email = ? WHERE id = ?");
    $stmt->bind_param("si", $novo_email, $id_usuario);

    if ($stmt->execute()) {
        echo "E-mail alterado com sucesso.";
    } else {
        echo "Erro ao alterar o e-mail.";
    }
}
?>

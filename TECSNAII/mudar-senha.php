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
    $nova_senha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);
    $id_usuario = $_SESSION['id_usuario'];

    $stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
    $stmt->bind_param("si", $nova_senha, $id_usuario);

    if ($stmt->execute()) {
        echo "Senha alterada com sucesso.";
    } else {
        echo "Erro ao alterar a senha.";
    }
}
?>

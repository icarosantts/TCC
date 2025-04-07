<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    echo "Você precisa estar logado para alterar o e-mail.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $novo_email = $_POST['novo_email'];
    $id_usuario = $_SESSION['id_usuario'];

    // Valida o e-mail
    if (!filter_var($novo_email, FILTER_VALIDATE_EMAIL)) {
        echo "Por favor, insira um e-mail válido.";
        exit();
    }

    // Prepara a consulta SQL para atualizar o e-mail
    $stmt = $conn->prepare("UPDATE usuarios SET email = ? WHERE id = ?");
    $stmt->bind_param("si", $novo_email, $id_usuario);

    // Executa a consulta e verifica o sucesso
    if ($stmt->execute()) {
        echo "E-mail alterado com sucesso.";
    } else {
        echo "Erro ao alterar o e-mail.";
    }

    // Fecha o statement
    $stmt->close();
}

// Fecha a conexão
$conn->close();
?>

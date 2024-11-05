<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.html"); // Redireciona para a página de login se não estiver logado
    exit();
}

// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tecsnai_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Captura o ID do usuário da sessão
$id_usuario = $_SESSION['id_usuario'];

// Verifica se a confirmação de exclusão foi enviada
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepara a consulta para excluir o usuário
    $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);

    if ($stmt->execute()) {
        // Se a exclusão for bem-sucedida, destrói a sessão e redireciona
        session_destroy();
        header("Location: index.html"); // Redireciona para a página inicial após exclusão
        exit();
    } else {
        echo "Erro ao excluir a conta: " . $stmt->error;
    }

    $stmt->close();
}

// Fechar conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Conta - TECSNAI</title>
</head>
<body>
    <h2>Tem certeza de que deseja excluir sua conta?</h2>
    <form method="POST">
        <button type="submit">Confirmar Exclusão</button>
    </form>
    <a href="pagina-cliente.html">Cancelar</a>
</body>
</html>

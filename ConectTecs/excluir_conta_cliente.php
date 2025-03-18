<?php
session_start();
include('conexao.php'); // Arquivo de conexão com o banco de dados

// Verifica se o usuário está logado e se é do tipo cliente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = $_SESSION['usuario_id'];
    
    // Exclui a conta do cliente
    $delete = "DELETE FROM clientes WHERE id_cliente = ?";
    $stmt = $conn->prepare($delete);
    $stmt->bind_param("i", $cliente_id);
    
    if ($stmt->execute()) {
        // Armazenando a mensagem na sessão
        $_SESSION['mensagem'] = 'Conta excluída com sucesso!';
        
        // Destruindo a sessão
        session_destroy();
        
        // Redirecionando para o index
        header("Location: index.html");
        exit();
    } else {
        echo "Erro ao excluir conta.";
    }
}
?>
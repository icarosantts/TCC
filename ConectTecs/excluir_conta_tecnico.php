<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tecnico_id = $_SESSION['usuario_id'];
    
    $delete = "DELETE FROM tecnicos WHERE id_tecnico = ?";
    $stmt = $conn->prepare($delete);
    $stmt->bind_param("i", $tecnico_id);
    
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

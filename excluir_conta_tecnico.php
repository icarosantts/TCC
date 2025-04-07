<?php
session_start();
include('conexao.php');

// Verificando se o usuário está logado e se é do tipo 'tecnico'
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tecnico_id = $_SESSION['usuario_id'];

    // Preparando a consulta para excluir o técnico
    $delete = "DELETE FROM tecnicos WHERE id_tecnico = ?";
    $stmt = $conn->prepare($delete);
    
    // Ligando o parâmetro da consulta
    $stmt->bind_param("i", $tecnico_id);
    
    // Executando a consulta
    if ($stmt->execute()) {
        // Armazenando a mensagem na sessão para exibir no redirecionamento
        $_SESSION['mensagem'] = 'Conta excluída com sucesso!';
        
        // Limpando as variáveis de sessão antes de destruir
        $_SESSION = array(); // Apaga todos os dados da sessão
        
        // Destruindo a sessão
        session_destroy();
        
        // Redirecionando para o index
        header("Location: index.html");
        exit();
    } else {
        // Caso ocorra erro ao excluir a conta
        echo "Erro ao excluir conta.";
    }
}
?>


<?php
// buscar-perfil-tecnico.php

session_start();
include 'conexao.php'; // Seu arquivo de conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    http_response_code(401); // Não autorizado
    echo json_encode(['error' => 'Usuário não autorizado']);
    exit();
}

// Recupera o ID do técnico logado
$usuario_id = $_SESSION['usuario_id'];

// Recupera os dados do perfil
$query = "SELECT nome, descricao, foto FROM tecnicos WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$usuario_id]);
$perfil = $stmt->fetch(PDO::FETCH_ASSOC);

if ($perfil) {
    echo json_encode($perfil); // Retorna os dados do perfil em formato JSON
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Perfil não encontrado']);
}
?>

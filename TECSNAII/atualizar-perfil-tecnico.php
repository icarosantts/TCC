<?php
// atualizar-perfil-tecnico.php

session_start();
include 'conexao.php'; // Seu arquivo de conexão com o banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    http_response_code(401); // Não autorizado
    echo json_encode(['error' => 'Usuário não autorizado']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Recupera os dados enviados via POST
$data = json_decode(file_get_contents('php://input'), true);

// Atualiza os dados no banco
$query = "UPDATE tecnicos SET nome = ?, descricao = ? WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$data['nome'], $data['descricao'], $usuario_id]);

if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Erro ao atualizar o perfil']);
}
?>

<?php
session_start();
require '../conexao.php';

if (!isset($_SESSION['usuario_id'])) die("Acesso negado.");

$conversa_id = $_GET['conversa_id'];
$usuario_id = $_SESSION['usuario_id'];

// Busca mensagens
$sql = "SELECT * FROM mensagens 
        WHERE conversa_id = ? 
        ORDER BY data_envio ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $conversa_id);
$stmt->execute();
$result = $stmt->get_result();

$mensagens = [];
while ($row = $result->fetch_assoc()) {
    // Marca como lida se for destinatário
    if ($row['destinatario_id'] == $usuario_id && $row['lida'] == 'nao') {
        $sql_update = "UPDATE mensagens SET lida = 'sim' WHERE id_mensagem = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $row['id_mensagem']);
        $stmt_update->execute();
    }
    
    $mensagens[] = $row;
}

// Atualiza contador de não lidas
$sql = "UPDATE conversas SET mensagens_nao_lidas = 0 
        WHERE id_conversa = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $conversa_id);
$stmt->execute();

echo json_encode($mensagens);
?>
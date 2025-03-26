<?php
session_start();
require '../conexao.php';

if (!isset($_SESSION['usuario_id']) die("Acesso negado.");

$dados = json_decode(file_get_contents('php://input'), true);
$remetente_id = $_SESSION['usuario_id'];
$destinatario_id = $dados['destinatario_id'];
$mensagem = $dados['mensagem'];

// Verifica se já existe uma conversa
$sql = "SELECT id_conversa FROM conversas 
        WHERE (cliente_id = ? AND tecnico_id = ?) 
        OR (cliente_id = ? AND tecnico_id = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $remetente_id, $destinatario_id, $destinatario_id, $remetente_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Cria nova conversa
    $sql = "INSERT INTO conversas (cliente_id, tecnico_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", min($remetente_id, $destinatario_id), max($remetente_id, $destinatario_id));
    $stmt->execute();
    $conversa_id = $conn->insert_id;
} else {
    $conversa_id = $result->fetch_assoc()['id_conversa'];
}

// Salva a mensagem
$sql = "INSERT INTO mensagens (conversa_id, remetente_id, destinatario_id, mensagem) 
        VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiis", $conversa_id, $remetente_id, $destinatario_id, $mensagem);
$stmt->execute();

// Atualiza a última mensagem na conversa
$sql = "UPDATE conversas SET 
        ultima_mensagem = ?, 
        data_ultima_mensagem = NOW(),
        mensagens_nao_lidas = mensagens_nao_lidas + 1
        WHERE id_conversa = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $mensagem, $conversa_id);
$stmt->execute();

echo json_encode(['status' => 'success']);
?>
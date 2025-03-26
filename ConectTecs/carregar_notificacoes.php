<?php
session_start();
require 'conexao.php';

if ($_SESSION['usuario_tipo'] !== 'tecnico') die("Acesso negado.");

$tecnico_id = $_SESSION['usuario_id'];
$sql = "SELECT n.*, c.nome, c.foto 
        FROM notificacoes_chat n
        JOIN clientes c ON n.cliente_id = c.id_cliente
        WHERE n.tecnico_id = ? AND n.lida = 'nao'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tecnico_id);
$stmt->execute();
$result = $stmt->get_result();

$notificacoes = [];
while ($row = $result->fetch_assoc()) {
    $notificacoes[] = $row;
}

echo json_encode($notificacoes);
?>
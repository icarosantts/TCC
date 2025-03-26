<?php
session_start();
require 'conexao.php';

$usuario_id = $_SESSION['usuario_id'];
$tipo_usuario = $_SESSION['usuario_tipo'];

if ($tipo_usuario == 'cliente') {
    $sql = "SELECT c.id_conversa, t.nome, t.foto, c.ultima_mensagem, c.data_ultima_mensagem 
            FROM conversas c 
            JOIN tecnicos t ON c.tecnico_id = t.id_tecnico 
            WHERE c.cliente_id = ? 
            ORDER BY c.data_ultima_mensagem DESC";
} else {
    $sql = "SELECT c.id_conversa, cl.nome, cl.foto, c.ultima_mensagem, c.data_ultima_mensagem 
            FROM conversas c 
            JOIN clientes cl ON c.cliente_id = cl.id_cliente 
            WHERE c.tecnico_id = ? 
            ORDER BY c.data_ultima_mensagem DESC";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$conversas = [];
while ($row = $result->fetch_assoc()) {
    $conversas[] = $row;
}

echo json_encode($conversas);
?>
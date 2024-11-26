<?php
session_start();
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $preferencias = $_POST['preferencias'];
    $id_usuario = $_SESSION['id_usuario'];

    $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, telefone = ?, preferencias = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nome, $telefone, $preferencias, $id_usuario);

    if ($stmt->execute()) {
        echo "Perfil atualizado com sucesso.";
    } else {
        echo "Erro ao atualizar o perfil.";
    }
}
?>

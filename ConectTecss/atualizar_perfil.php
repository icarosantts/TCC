<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado e se é um técnico
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    echo "Erro: Acesso não autorizado.";
    exit();
}

$tecnico_id = $_SESSION['usuario_id'];

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $nome = $_POST['nome'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $especialidades = $_POST['especialidades'] ?? '';
    $valor_servico = $_POST['valor_servico'] ?? '';
    $descricao_tecnico = $_POST['descricao_tecnico'] ?? '';

    // Prepara a query para atualizar os dados do técnico
    $query = "UPDATE tecnicos SET nome = ?, telefone = ?, especialidades = ?, valor_servico = ?, descricao_tecnico = ? WHERE id_tecnico = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $nome, $telefone, $especialidades, $valor_servico, $descricao_tecnico, $tecnico_id);

    if ($stmt->execute()) {
        echo "Perfil atualizado com sucesso! Atualize a página.";
    } else {
        echo "Erro ao atualizar o perfil.";
    }

    $stmt->close();
    $conn->close();
}
?>

<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado e se é um técnico
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    echo "Erro: Acesso não autorizado.";
    header("Location: login.html"); // Redireciona para a página de login
    exit();
}

$tecnico_id = $_SESSION['usuario_id'];

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $nome = $_POST['nome'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $email = $_POST['email'] ?? ''; // Novo campo de e-mail
    $especialidades = $_POST['especialidades'] ?? '';
    $valor_servico = $_POST['valor_servico'] ?? '';
    $descricao_tecnico = $_POST['descricao_tecnico'] ?? '';

    // Validação dos campos obrigatórios
    if (empty($nome) || empty($telefone) || empty($email) || empty($especialidades) || empty($descricao_tecnico)) {
        echo "Erro: Todos os campos são obrigatórios.";
        exit();
    }

    // Validação do formato do e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Erro: E-mail inválido.";
        exit();
    }

    // Prepara a query para atualizar os dados do técnico
    $query = "UPDATE tecnicos SET nome = ?, telefone = ?, email = ?, especialidades = ?, valor_servico = ?, descricao_tecnico = ? WHERE id_tecnico = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo "Erro ao preparar a query: " . $conn->error;
        exit();
    }

    $stmt->bind_param("ssssssi", $nome, $telefone, $email, $especialidades, $valor_servico, $descricao_tecnico, $tecnico_id);

    if ($stmt->execute()) {
        echo "Perfil atualizado com sucesso! Atualize a página.";
    } else {
        echo "Erro ao atualizar o perfil: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
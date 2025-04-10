<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado e se é um técnico
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    echo "Erro: Acesso não autorizado.";
    header("Location: login.html");
    exit();
}

$tecnico_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitização dos dados
    $nome = htmlspecialchars(trim($_POST['nome']));
    $whatsapp_link = trim($_POST['whatsapp_link']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $especialidades = htmlspecialchars(trim($_POST['especialidades']));
    $valor_servico = floatval($_POST['valor_servico']);
    $descricao_tecnico = htmlspecialchars(trim($_POST['descricao_tecnico']));

    // Validações
    if (empty($nome) || empty($whatsapp_link) || empty($email) || empty($especialidades) || empty($descricao_tecnico)) {
        echo "Erro: Todos os campos são obrigatórios.";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Erro: E-mail inválido.";
        exit();
    }

    // Validação do link do WhatsApp
    if (!preg_match('/^https:\/\/wa\.me\/\d{10,15}$/', $whatsapp_link)) {
        echo "Erro: Link do WhatsApp inválido. Use o formato: https://wa.me/5511999999999";
        exit();
    }

    // Query de atualização
    $query = "UPDATE tecnicos SET 
              nome = ?, 
              whatsapp_link = ?, 
              email = ?, 
              especialidades = ?, 
              valor_servico = ?, 
              descricao_tecnico = ? 
              WHERE id_tecnico = ?";
              
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        echo "Erro ao preparar a query: " . $conn->error;
        exit();
    }

    $stmt->bind_param("ssssdsi", $nome, $whatsapp_link, $email, $especialidades, $valor_servico, $descricao_tecnico, $tecnico_id);

    if ($stmt->execute()) {
        echo "Perfil atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar o perfil: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
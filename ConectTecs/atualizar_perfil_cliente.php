<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    echo "Acesso negado.";
    exit();
}

require_once 'conexao.php'; // Arquivo de conexão com o banco de dados

$cliente_id = $_SESSION['usuario_id'];
$nome = $_POST['nome'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];

// Atualiza as informações do cliente no banco de dados
$sql = "UPDATE clientes SET nome = ?, telefone = ?, email = ? WHERE id_cliente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nome, $telefone, $email, $cliente_id);

if ($stmt->execute()) {
    echo "Perfil atualizado com sucesso!";
} else {
    echo "Erro ao atualizar o perfil.";
}

$stmt->close();
$conn->close();
?><?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    echo "Acesso negado.";
    exit();
}

require_once 'conexao.php'; // Arquivo de conexão com o banco de dados

$cliente_id = $_SESSION['usuario_id'];
$nome = $_POST['nome'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];

// Atualiza as informações do cliente no banco de dados
$sql = "UPDATE clientes SET nome = ?, telefone = ?, email = ? WHERE id_cliente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nome, $telefone, $email, $cliente_id);

if ($stmt->execute()) {
    echo "Perfil atualizado com sucesso!";
} else {
    echo "Erro ao atualizar o perfil.";
}

$stmt->close();
$conn->close();
?>
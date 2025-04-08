<?php
session_start();
include('conexao.php'); // Arquivo de conexão com o banco de dados

// Verifica se o usuário está logado e se é do tipo cliente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = $_SESSION['usuario_id'];
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);

    // Busca a senha atual no banco de dados
    $query = "SELECT senha FROM clientes WHERE id_cliente = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();

    // Verifica se a senha atual está correta
    if (password_verify($senha_atual, $cliente['senha'])) {
        // Atualiza a senha no banco de dados
        $update = "UPDATE clientes SET senha = ? WHERE id_cliente = ?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param("si", $nova_senha, $cliente_id);
        if ($stmt->execute()) {
            echo "<script>
                alert('Senha alterada com sucesso!');
                window.location.href = 'pagina-cliente.php'; // Redireciona para a tela inicial do cliente
            </script>";
            exit;
        } else {
            echo "<script>alert('Erro ao alterar senha.');</script>";
        }
    } else {
        echo "<script>alert('Senha atual incorreta.');</script>";
    }
}
?>
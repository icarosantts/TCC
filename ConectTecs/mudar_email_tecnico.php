<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tecnico_id = $_SESSION['usuario_id'];
    $novo_email = $_POST['novo_email'];

    $check_email = "SELECT id_tecnico FROM tecnicos WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $novo_email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "E-mail já está em uso.";
    } else {
        $update = "UPDATE tecnicos SET email = ? WHERE id_tecnico = ?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param("si", $novo_email, $tecnico_id);
        if ($stmt->execute()) {
            echo "<script>
                alert('Email alterada com sucesso!');
                window.location.href = 'pagina-tecnico.php'; // Redireciona para a tela inicial do técnico
            </script>";
            exit();
        } else {
            echo "Erro ao alterar e-mail.";
        }
    }
}
?>

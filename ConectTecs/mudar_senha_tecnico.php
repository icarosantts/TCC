<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tecnico_id = $_SESSION['usuario_id'];
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);

    $query = "SELECT senha FROM tecnicos WHERE id_tecnico = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $tecnico_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tecnico = $result->fetch_assoc();

    if (password_verify($senha_atual, $tecnico['senha'])) {
        $update = "UPDATE tecnicos SET senha = ? WHERE id_tecnico = ?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param("si", $nova_senha, $tecnico_id);
        if ($stmt->execute()) {
            echo "<script>
                alert('Senha alterada com sucesso!');
                window.location.href = 'pagina-tecnico.php'; // Redireciona para a tela inicial do técnico
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

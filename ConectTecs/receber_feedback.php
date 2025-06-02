<?php
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tecnico_id = $_POST["tecnico_id"] ?? null;
    $cliente_id = $_POST["cliente_id"] ?? null;
    $comentario = $_POST["comentario"] ?? null;
    $avaliacao = $_POST["avaliacao"] ?? null;

    if ($tecnico_id && $cliente_id && $comentario && $avaliacao) {
        $stmt = $conn->prepare("INSERT INTO feedback (tecnico_id, cliente_id, comentario, avaliacao) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $tecnico_id, $cliente_id, $comentario, $avaliacao);

        if ($stmt->execute()) {
            echo "Feedback enviado com sucesso!";
        } else {
            echo "Erro ao enviar feedback: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, preencha todos os campos do formulário.";
    }

    $conn->close();
}
?>

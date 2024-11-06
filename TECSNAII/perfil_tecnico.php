<?php
session_start(); // Inicia a sessão para acessar os dados do usuário logado

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tecsnai_db";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Supondo que o e-mail do técnico está armazenado na sessão após o login
$email_tecnico = $_SESSION['email']; // Altere conforme sua lógica de autenticação

$sql = "SELECT * FROM tecnicos WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email_tecnico);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $tecnico = $resultado->fetch_assoc();
} else {
    echo "Nenhum técnico encontrado.";
}

$stmt->close();
$conn->close();
?>

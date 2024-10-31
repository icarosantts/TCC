<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Configurações do banco de dados
$servername = "localhost"; // Seu servidor
$username = "root"; // Usuário do banco de dados
$password = ""; // Senha do banco de dados
$dbname = "tecnicosDB"; // Nome do banco de dados

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"));

if (isset($data->nome) && isset($data->mensagem)) {
    $nome = $data->nome;
    $mensagem = $data->mensagem;
    $emailDestinatario = "tecnico@example.com"; // Substitua pelo e-mail do técnico

    // Envia o e-mail
    $assunto = "Nova Mensagem de $nome";
    $corpo = "Mensagem: $mensagem";
    $headers = "From: remetente@example.com"; // Substitua pelo seu e-mail

    if (mail($emailDestinatario, $assunto, $corpo, $headers)) {
        echo json_encode(["message" => "Mensagem enviada com sucesso para $nome."]);
    } else {
        echo json_encode(["message" => "Erro ao enviar e-mail."]);
    }
} else {
    echo json_encode(["message" => "Dados incompletos."]);
}

$conn->close();
?>

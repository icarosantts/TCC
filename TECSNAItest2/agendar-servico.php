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

if (isset($data->nome) && isset($data->dataHora)) {
    $nome = $conn->real_escape_string($data->nome); // Escapando a string
    $dataHora = $conn->real_escape_string($data->dataHora);

    // Prepara a consulta SQL para inserir o agendamento
    $sql = "INSERT INTO agendamentos (tecnico_nome, data_hora) VALUES ('$nome', '$dataHora')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Serviço agendado com sucesso com $nome para $dataHora."]);
    } else {
        echo json_encode(["message" => "Erro ao agendar serviço: " . $conn->error]);
    }
} else {
    echo json_encode(["message" => "Dados incompletos."]);
}

$conn->close();
?>

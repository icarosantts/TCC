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

// Nome do técnico (você pode obter isso de uma sessão ou parâmetro de URL)
$tecnico_nome = 'João Silva'; // Substitua pelo nome do técnico autenticado

$sql = "SELECT * FROM agendamentos WHERE tecnico_nome = '$tecnico_nome' ORDER BY created_at DESC";
$result = $conn->query($sql);

$agendamentos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $agendamentos[] = $row;
    }
}

echo json_encode($agendamentos);

$conn->close();
?>

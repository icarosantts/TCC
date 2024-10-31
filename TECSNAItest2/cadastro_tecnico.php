<?php

$servername = "localhost"; // Servidor do banco de dados
$username = "root"; // Usuário do banco de dados
$password = "";   // Senha do banco de dados
$dbname = "tecnicosDB";  // Nome do banco de dados

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $possuiCertificado = $_POST['certificado'];
    $especialidades = implode(", ", $_POST['especialidades']);
    $tempoExperiencia = $_POST['tempo-experiencia'];
    $apresentacao = $_POST['apresentacao'];

    // Aqui você pode adicionar a lógica para salvar os dados em um banco de dados
    // Exemplo:
    // $conn = new mysqli($servername, $username, $password, $dbname);
    // $sql = "INSERT INTO tecnicos (nome, telefone, email, senha, possui_certificado, especialidades, tempo_experiencia, apresentacao) VALUES ('$nome', '$telefone', '$email', '$senha', '$possuiCertificado', '$especialidades', '$tempoExperiencia', '$apresentacao')";

    // Mensagem de sucesso
    echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href='index.html';</script>";
}
?>

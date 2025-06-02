<?php

$servername = "sql305.infinityfree.com"; // ou o que aparecer no painel
$username = "if0_39002126";      // substitua pelo seu
$password = "BCBn1iRjGzI";     // senha definida por você
$dbname = "if0_39002126_conecttecs_db"; // nome completo do banco

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>

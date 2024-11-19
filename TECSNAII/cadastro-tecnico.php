<?php
$servername = "localhost";  // seu host
$username = "root";         // seu usuário do banco de dados
$password = "";             // sua senha do banco de dados
$dbname = "tecsnai_db";     // nome do seu banco de dados

// Cria a conexão usando MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografando a senha
    $certificado = $_POST['certificado'];

    // Verifica se especialidades é um array ou uma string
    if (is_array($_POST['especialidades'])) {
        $especialidades = implode(",", $_POST['especialidades']); // Converte especialidades em string separada por vírgulas
    } else {
        $especialidades = $_POST['especialidades']; // Atribui diretamente se for uma string
    }

    $valor_servico = $_POST['valor_servico'];
    $descricao_tecnico = $_POST['descricao_tecnico'];

    // Upload do documento do certificado (se houver)
    $documento_certificado = "";
    if ($certificado == "sim" && isset($_FILES['documento-certificado'])) {
        $documento_certificado = $_FILES['documento-certificado']['name'];
        $doc_tmp = $_FILES['documento-certificado']['tmp_name'];
        $doc_path = "uploads/" . basename($documento_certificado);
        move_uploaded_file($doc_tmp, $doc_path);
    }

    // Insere os dados no banco
    $sql = "INSERT INTO tecnicos (nome, telefone, email, senha, certificado, documento_certificado, especialidades, valor_servico, descricao_tecnico) 
            VALUES ('$nome', '$telefone', '$email', '$senha', '$certificado', '$doc_path', '$especialidades', '$valor_servico', '$descricao_tecnico')";

    if ($conn->query($sql) === TRUE) {
        // Redireciona para a página de login após o cadastro
        header("Location: login.html");
        exit();
    } else {
        echo "Erro ao realizar o cadastro: " . $conn->error; // Mensagem de erro mais clara
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro realizado com sucesso</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #003366;
            color: white;
            font-family: Arial, sans-serif;
        }

        button {
            background-color: white;
            color: #003366;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }

        /* Efeito Hover no botão */
        button:hover {
            background-color: #003366;
            color: white;
        }
    </style>
</head>
<body>
    <div>
        <h2>Cadastro realizado com sucesso!</h2>
        <button onclick="window.location.href='index.html'">Voltar ao Cadastro</button>
    </div>
</body>
</html>

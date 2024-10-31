<?php
//Conexão com o banco de dados mysql
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tecnicosDB";

// Cria a conexão
$conn = new mysqli($servername,$username,$password,$dbname);

// Verifica A CONEXÃO
if($conn->connect_error){
    die("Falha na conexão:" . $conn->connect_error);
}

// obtém os valores enviados pelo formulário
$nome = $_POST['nome'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$senha = $_POST['senha'];

// Prepara a instrução SQL para inserir os dados
$sql = "INSERT INTO usuarios (nome, telefone, email, senha) VALUES ('$nome', '$telefone', '$email', '$senha')";

// Executa a inserção e verifica se deu certo
if ($conn->query($sql) === TRUE) {
    //exibe a página de sucesso
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro realizado com sucesso</title>
    <style>
        /* Estilo para o corpo da página*/ 
        body{
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #003366;
            color: white;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* Estilo para o botão de voltar*/
        button {
            background-color: white;
            color: #003366;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }

        /* Efeito hover no botão */
        button:hover {
            background-color: #003366;
            color: white;
        }
    </style>
</head>
<body>
    <div>
        <h2>Cadastro realizado com sucesso!</h2>
        <button onclick="window.location.href='login.html'"> Voltar a tela de login</button>
    </div>
</body>
</html>
<?php
}else{
    echo "Erro ao inserir os dados: ". $conn->error;
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
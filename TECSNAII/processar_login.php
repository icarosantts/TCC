<?php
session_start();

// Configurações do banco de dados
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Primeira tentativa: verifica na tabela de técnicos
    $sql_tecnico = "SELECT * FROM tecnicos WHERE email = ?";
    $stmt_tecnico = $conn->prepare($sql_tecnico);
    $stmt_tecnico->bind_param("s", $email);
    $stmt_tecnico->execute();
    $result_tecnico = $stmt_tecnico->get_result();

    if ($result_tecnico->num_rows === 1) {
        $tecnico = $result_tecnico->fetch_assoc();
        if (password_verify($senha, $tecnico['senha'])) {
            // Autenticação bem-sucedida como técnico
            $_SESSION['usuario_id'] = $tecnico['id_tecnico'];
            $_SESSION['usuario_nome'] = $tecnico['nome'];
            $_SESSION['usuario_tipo'] = 'tecnico';
            header("Location: pagina-tecnico.php");
            exit();
        }
    } else {
        // Segunda tentativa: verifica na tabela de clientes
        $sql_cliente = "SELECT * FROM clientes WHERE email = ?";
        $stmt_cliente = $conn->prepare($sql_cliente);
        $stmt_cliente->bind_param("s", $email);
        $stmt_cliente->execute();
        $result_cliente = $stmt_cliente->get_result();

        if ($result_cliente->num_rows === 1) {
            $cliente = $result_cliente->fetch_assoc();
            if (password_verify($senha, $cliente['senha'])) {
                // Autenticação bem-sucedida como cliente
                $_SESSION['usuario_id'] = $cliente['id_cliente'];
                $_SESSION['usuario_nome'] = $cliente['nome'];
                $_SESSION['usuario_tipo'] = 'cliente';
                header("Location: pagina-cliente.php");
                exit();
            }
        }
    }

    // Se o login falhar, armazena a mensagem de erro na sessão e redireciona para a página de login
    $_SESSION['erro_login'] = "Email ou senha incorretos. Tente novamente.";
    header("Location: login.html"); // Redireciona para a tela de login
    exit();
}

$conn->close();
?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
session_start();
include 'conexao.php';

// Verifica se o formulário foi enviado
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
            $_SESSION['usuario_email'] = $tecnico['email'];
            header("Location: pagina-tecnico.php");
            exit();
        }
    }

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
            $_SESSION['usuario_email'] = $cliente['email'];
            header("Location: pagina-cliente.php");
            exit();
        }
    }

    // Se o login falhar
    $erro_login = "Email ou senha incorretos. Tente novamente.";
}

// Se não for POST ou se houver erro, mostra o formulário
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ConectTecs</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1 class="logo"><a href="index.php">ConectTecs</a></h1>
            <nav class="menu">
                <ul class="nav-links">
                    <li><a href="cadastro_tecnico.php">Sou Técnico</a></li>
                    <li><a href="cadastro_cliente.php">Estou procurando por um Técnico</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>    
    </header>

    <main>
        <div class="login-container">
            <h2>Tela de Login</h2>
            <?php if (isset($erro_login)): ?>
                <div class="error-message"><?php echo $erro_login; ?></div>
            <?php endif; ?>
            <form action="login.php" method="POST"> 
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Digite o seu email" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                </div>
                <button type="submit">Login</button>    
            </form>
            <div class="footer">
                <p><a href="esqueci_senha.php">Esqueci a senha</a></p>
                <p>Não tem uma conta? <a href="escolha_cadastro.php">Cadastre-se</a></p>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 ConectTecs. Todos os direitos reservados.</p>
    </footer>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            var email = document.getElementById('email').value;
            var senha = document.getElementById('senha').value;
        
            if(email === '' || senha === '') {
                alert('Por favor, preencha todos os campos.');
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
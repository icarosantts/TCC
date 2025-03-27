<?php
session_start();
include 'conexao.php';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografando a senha

    // Insere os dados no banco
    $sql = "INSERT INTO clientes (nome, telefone, email, senha) VALUES ('$nome', '$telefone', '$email', '$senha')";

    if ($conn->query($sql) === TRUE) {
        // Redireciona para a página de login após o cadastro
        header("Location: login.php");
        exit();
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

// Se não for POST ou se houver erro, mostra o formulário
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - ConectTecs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <h1 class="logo"><a href="index.html">ConectTecs</a></h1>
            <nav class="menu">
                <ul class="nav-links">
                    <li><a href="index.html">Página Inicial</a></li>
                    <li><a href="cadastro_tecnico.php">Sou Técnico</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="cadastro">
            <h2>Cadastre-se para encontrar o profissional que procura.</h2>
            <p>Preencha os dados abaixo para se cadastrar.</p>
            <form id="cadastro-cliente-form" action="cadastro_cliente.php" method="POST">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" placeholder="Digite o seu nome" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="Digite o seu telefone" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" placeholder="Digite o seu e-mail" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                </div>
                <button type="submit">Cadastrar</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 ConectTecs. Todos os direitos reservados.</p>
    </footer>
    
    <script>
        // Selecionar o formulário de cadastro de cliente
        const cadastroForm = document.getElementById('cadastro-cliente-form');

        // Função para validar o e-mail, senha e telefone
        const validarCadastroCliente = () => {
            const email = document.getElementById('email').value;
            const senha = document.getElementById('senha').value;
            const telefone = document.getElementById('telefone').value;

            // Validação do e-mail
            if (!email.includes('@')) {
                alert('Por favor, insira um e-mail válido.');
                return false;
            }

            // Validação do telefone (apenas números, 10 a 11 dígitos)
            const telefoneRegex = /^[0-9]{10,11}$/;
            if (!telefoneRegex.test(telefone)) {
                alert('Por favor, insira um telefone válido com 10 a 11 dígitos numéricos.');
                return false;
            }

            // Validação da senha (mínimo de 6 caracteres, letra maiúscula, minúscula, número e caractere especial)
            const senhaRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;
            if (!senhaRegex.test(senha)) {
                alert('A senha deve ter pelo menos 6 caracteres, incluindo uma letra maiúscula, uma letra minúscula, um número e um caractere especial.');
                return false;
            }

            // Retorna verdadeiro se tudo estiver ok
            return true;
        };

        // Evento de envio do formulário
        cadastroForm.addEventListener('submit', (event) => {
            // Validação antes de enviar
            if (!validarCadastroCliente()) {
                // Impede o envio do formulário se a validação falhar
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
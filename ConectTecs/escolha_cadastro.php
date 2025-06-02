<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
// escolha_cadastro.php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolha o tipo de Cadastro - ConectTecs</title>
    <link rel="stylesheet" href="styles.css">
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
        <section class="hero">
            <h2>Escolha o seu tipo de cadastro</h2>
            <button onclick="location.href='cadastro_tecnico.php'">Sou Técnico</button>
            <button onclick="location.href='cadastro_cliente.php'">Estou procurando Técnico</button>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> ConectTecs. Todos os direitos reservados.</p>
    </footer>
</body>
</html>

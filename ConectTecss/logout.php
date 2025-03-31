<?php
session_start();
session_unset(); // Remove todas as variáveis de sessão
session_destroy(); // Destrói a sessão

// Redireciona o usuário para a página de login após o logout
header("Location: login.php");
exit();
?>

<?php
// atualizar-foto-perfil.php

session_start();
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

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    http_response_code(401); // Não autorizado
    echo json_encode(['error' => 'Usuário não autorizado']);
    exit();
}

// Checa se o arquivo foi enviado
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    // Definir o diretório para salvar a imagem
    $diretorio_upload = 'uploads/perfis/';
    
    // Cria o diretório caso não exista
    if (!is_dir($diretorio_upload)) {
        mkdir($diretorio_upload, 0777, true);
    }

    // Nome do arquivo (para evitar conflito com outros arquivos)
    $nome_arquivo = uniqid('foto_') . '.' . pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $caminho_arquivo = $diretorio_upload . $nome_arquivo;

    // Validar a imagem (tamanho, tipo, etc.)
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
    $tipo_imagem = $_FILES['foto']['type'];

    if (in_array($tipo_imagem, $tipos_permitidos)) {
        // Mover a imagem para o diretório desejado
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho_arquivo)) {
            // Atualizar o caminho da foto no banco de dados
            $usuario_id = $_SESSION['usuario_id'];
            $query = "UPDATE tecnicos SET foto = ? WHERE id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$caminho_arquivo, $usuario_id]);

            if ($stmt->rowCount() > 0) {
                // Retorna a URL da foto para o frontend
                echo json_encode(['success' => true, 'foto_url' => $caminho_arquivo]);
            } else {
                echo json_encode(['error' => 'Erro ao atualizar foto no banco de dados']);
            }
        } else {
            echo json_encode(['error' => 'Erro ao mover a imagem']);
        }
    } else {
        echo json_encode(['error' => 'Tipo de imagem não permitido']);
    }
} else {
    echo json_encode(['error' => 'Nenhuma foto enviada']);
}
?>

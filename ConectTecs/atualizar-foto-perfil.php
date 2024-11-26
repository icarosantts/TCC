<?php
session_start();

// Verifica se o usuário está logado e se é do tipo técnico
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'tecnico') {
    // Se não estiver logado ou não for técnico, redireciona para a página de login
    header("Location: login.html");
    exit();
}

// Conexão com o banco de dados
require_once 'conexao.php';

// Obtém o ID do técnico da sessão
$tecnico_id = $_SESSION['usuario_id'];

// Verifica se foi enviado um arquivo para upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto'])) {
    $foto = $_FILES['foto'];

    // Verifica se a foto foi carregada sem erros
    if ($foto['error'] == UPLOAD_ERR_OK) {
        // Define o diretório de upload
        $diretorio = 'uploads/';

        // Cria o diretório caso ele não exista
        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0777, true);
        }

        // Define o caminho completo do arquivo de imagem
        $foto_nome = $diretorio . basename($foto['name']);
        
        // Move a foto para o diretório de uploads
        if (move_uploaded_file($foto['tmp_name'], $foto_nome)) {
            // Atualiza o banco de dados com o caminho da nova foto
            $query = "UPDATE tecnicos SET foto = ? WHERE id_tecnico = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $foto_nome, $tecnico_id);

            if ($stmt->execute()) {
                // Foto atualizada com sucesso
                $foto_url = $foto_nome;  // Caminho da foto que será usado para exibição
            } else {
                $erro = "Erro ao atualizar a foto no banco de dados.";
            }
            $stmt->close();
        } else {
            $erro = "Erro ao mover o arquivo para o diretório de uploads.";
        }
    } else {
        $erro = "Erro no upload da foto: " . $foto['error'];
    }
}

// Busca os dados do técnico no banco de dados
$query = "SELECT * FROM tecnicos WHERE id_tecnico = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $tecnico_id);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se encontrou o técnico
if ($result->num_rows > 0) {
    $tecnico = $result->fetch_assoc();
} else {
    echo "Erro: Técnico não encontrado.";
    exit();
}

// Verifica se a foto foi carregada corretamente, se não, usa a foto padrão
if (isset($foto_url)) {
    $foto_para_exibir = $foto_url;
} else {
    // Caso não tenha foto, exibe a foto padrão
    $foto_para_exibir = 'uploads/default-profile.png';
}

$stmt->close();
$conn->close();
?>
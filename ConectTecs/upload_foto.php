<?php
session_start();
include('conexao.php'); // Inclua seu arquivo de conexão com o banco de dados

if (isset($_POST['enviar_foto']) && isset($_FILES['foto_perfil'])) {
    // Verificar se a imagem foi enviada corretamente
    $foto = $_FILES['foto_perfil'];
    if ($foto['error'] === UPLOAD_ERR_OK) {
        $foto_temp = $foto['tmp_name'];
        $foto_nome = $foto['name'];
        $foto_ext = pathinfo($foto_nome, PATHINFO_EXTENSION);
        
        // Verificar se o arquivo é uma imagem
        $valid_extensions = ['jpg', 'jpeg', 'png'];
        if (in_array($foto_ext, $valid_extensions)) {
            $novo_nome_foto = uniqid('', true) . '.' . $foto_ext;
            $caminho_destino = 'uploads/' . $novo_nome_foto;

            // Mover a foto para a pasta de uploads
            if (move_uploaded_file($foto_temp, $caminho_destino)) {
                // Atualizar o banco de dados com o novo caminho da foto
                $usuario_id = $_SESSION['id_usuario']; // Use o ID do usuário logado
                $query = "UPDATE tecnicos SET foto = ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("si", $caminho_destino, $usuario_id);
                if ($stmt->execute()) {
                    echo "Foto de perfil atualizada com sucesso!";
                } else {
                    echo "Erro ao atualizar foto de perfil!";
                }
            } else {
                echo "Erro ao fazer o upload da foto!";
            }
        } else {
            echo "Formato de imagem inválido! Apenas JPG, JPEG ou PNG são aceitos.";
        }
    } else {
        echo "Erro ao enviar a foto!";
    }
}
?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
require_once 'conexao.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

try {
    if (empty($data['date']) || empty($data['time']) || empty($data['title'])) {
        throw new Exception("Dados incompletos");
    }
    
    if (empty($data['id'])) {
        // Inserir novo evento
        $query = "INSERT INTO eventos (tecnico_id, date, time, title, description, client, address) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issssss", 
            $data['tecnico_id'],
            $data['date'],
            $data['time'],
            $data['title'],
            $data['description'],
            $data['client'],
            $data['address']
        );
    } else {
        // Atualizar evento existente
        $query = "UPDATE eventos SET 
                  date = ?, time = ?, title = ?, description = ?, client = ?, address = ?
                  WHERE id = ? AND tecnico_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssii", 
            $data['date'],
            $data['time'],
            $data['title'],
            $data['description'],
            $data['client'],
            $data['address'],
            $data['id'],
            $data['tecnico_id']
        );
    }
    
    $stmt->execute();
    
    if ($stmt->affected_rows > 0 || empty($data['id'])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Nenhuma alteração realizada']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
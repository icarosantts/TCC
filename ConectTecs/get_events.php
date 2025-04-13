<?php
require_once 'conexao.php';

header('Content-Type: application/json');

$tecnico_id = $_GET['tecnico_id'] ?? 0;

try {
    $query = "SELECT * FROM eventos WHERE tecnico_id = ? ORDER BY date, time";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $tecnico_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    
    echo json_encode($events);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
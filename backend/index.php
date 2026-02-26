<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI']; 

try {
    $conn = getDBConnection();
    
    // GET /api/todos
    if ($method === 'GET' && strpos($requestUri, 'todos') !== false && !preg_match('/(\d+)$/', $requestUri)) {
        $stmt = $conn->query("SELECT * FROM todos ORDER BY created_at DESC");
        $todos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($todos as &$todo) { $todo['completed'] = (bool)$todo['completed']; }
        echo json_encode($todos);
    }
    
    // POST /api/todos
    elseif ($method === 'POST' && strpos($requestUri, 'todos') !== false) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['title'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Titre requis']);
            exit();
        }
        $stmt = $conn->prepare("INSERT INTO todos (title) VALUES (?)");
        $stmt->execute([$data['title']]);
        $id = $conn->lastInsertId();
        $stmt = $conn->prepare("SELECT * FROM todos WHERE id = ?");
        $stmt->execute([$id]);
        $todo = $stmt->fetch(PDO::FETCH_ASSOC);
        $todo['completed'] = (bool)$todo['completed'];
        echo json_encode($todo);
    }
    
    // PUT /api/todos/{id}
    elseif ($method === 'PUT' && preg_match('/todos\/(\d+)$/', $requestUri, $matches)) {
        $id = $matches[1];
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $conn->prepare("UPDATE todos SET completed = ? WHERE id = ?");
        $stmt->execute([$data['completed'] ? 1 : 0, $id]);
        $stmt = $conn->prepare("SELECT * FROM todos WHERE id = ?");
        $stmt->execute([$id]);
        $todo = $stmt->fetch(PDO::FETCH_ASSOC);
        $todo['completed'] = (bool)$todo['completed'];
        echo json_encode($todo);
    }
    
    // DELETE /api/todos/{id}
    elseif ($method === 'DELETE' && preg_match('/todos\/(\d+)$/', $requestUri, $matches)) {
        $id = $matches[1];
        $stmt = $conn->prepare("DELETE FROM todos WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['message' => 'Tâche supprimée']);
    }
    else {
        http_response_code(404);
        echo json_encode(['error' => 'Route non trouvée']);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur: ' . $e->getMessage()]);
}
?>
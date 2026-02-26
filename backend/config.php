<?php
function getSecret($secretName){
	$secretPath = "/run/secrets/"  . $secretName;
	if (file_exists($secretPath)){
		return trim(file_get_contents($secretPath));
	}
	return getenv($secretName) ?: 'root';
}


define('DB_HOST', getenv('DB_HOST') ?: 'database');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getSecret('datab_root_password'));
define('DB_NAME', getenv('DB_NAME') ?: 'tododb');

function getDBConnection() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
            DB_USER,
            DB_PASS
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        // En cas d'erreur, on s'assure de ne pas avoir envoyé de texte avant le JSON
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        http_response_code(500);
        echo json_encode(['error' => 'Base de données déconnectée: ' . $e->getMessage()]);
        exit();
    }
}

function initDatabase() {
    try {
        $conn = getDBConnection();
        $sql = "CREATE TABLE IF NOT EXISTS todos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            completed BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->exec($sql);
    } catch(PDOException $e) {
        error_log("Erreur table: " . $e->getMessage());
    }
}


initDatabase();
?>

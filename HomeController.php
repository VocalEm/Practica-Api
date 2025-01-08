<?php

class HomeController
{
    public function index()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        try {
            $db = Database::getInstance();
            $conn = $db->getConnection();

            $stmt = $conn->prepare("SELECT * FROM usuarios");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $utf8_users = convertToUtf8($users);
            echo json_encode($utf8_users, JSON_PRETTY_PRINT);
            http_response_code(200);
        } catch (Exception $e) {
            http_response_code(500);

            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        try {
            $db = Database::getInstance();
            $conn = $db->getConnection();

            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE user_id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $utf8_users = convertToUtf8($users);
            echo json_encode($utf8_users, JSON_PRETTY_PRINT);
            http_response_code(200);
        } catch (Exception $e) {
            http_response_code(500);

            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function store()
    {
        // Configurar los headers necesarios
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        try {
            $db = Database::getInstance();
            $conn = $db->getConnection();
            if ($conn === null) {
                throw new Exception("Failed to connect to the database.");
            }
            // Obtener datos del POST 
            $data = json_decode(file_get_contents('php://input'), true);
            $nombre = $data['nombre'] ?? null;
            if (!$nombre) {
                throw new Exception("Missing required field: nombre");
            }
            // Insertar datos en la base de datos 
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre) VALUES (:nombre)");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->execute();
            // Responder con éxito 
            echo json_encode(['success' => 'User created successfully', 'nombre' => $nombre], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

<?php

class HomeController
{
    public function index()
    {

        $db = Database::getInstance();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT * FROM usuarios");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        print_r(json_encode($users));
        http_response_code(200);
    }
}

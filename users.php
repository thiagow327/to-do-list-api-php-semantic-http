<?php

require 'database.php';

// rota para buscar todos os usuarios 
if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET)) {
    try {
        $statement = $connection->query('SELECT * FROM users');
        $tasks = $statement->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($tasks);
    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}

// rota para adicionar uma nova tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['userName'])) {
        echo json_encode(['error' => 'O nome do usuário é obrigatório']);
        exit;
    }

    $userName = $data['userName'];

    try {
        $statement = $connection->prepare('INSERT INTO users (userName) VALUES (:userName)');
        $statement->bindParam(':userName', $userName);
        $statement->execute();
        $userID = $connection->lastInsertId();
        echo json_encode(['id' => $userID, 'userName' => $userName]);
    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}

// rota para atualizar usuario
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['id'] && $data['userName'])) {
        echo json_encode(['error' => 'O ID e nome do usuário são obrigatórios']);
        exit;
    }

    $userID = $data['userID'];
    $userName = $data['userName'];


    try {
        $statement = $connection->prepare('UPDATE users SET userName = :userName WHERE userID = :userID');
        $statement->bindParam(':userID', $userID);
        $statement->bindParam(':userName', $userName);
        $statement->execute();
        echo json_encode(['success' => 'Nome do usuário alterado']);
    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}

// rota para deletar um usuario
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['userID'])) {
        echo json_encode(['error' => 'O ID do usuário é obrigatório']);
        exit;
    }

    $userID = $data['userID'];

    try {
        $statement = $connection->prepare('DELETE FROM users WHERE userID = :userID');
        $statement->bindParam(':userID', $userID);
        $statement->execute();
        echo json_encode(['success' => 'Usuário deletado']);
    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}

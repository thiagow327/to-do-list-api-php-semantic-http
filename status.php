<?php

require 'database.php';

// rota para buscar todos os status
if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET)) {
    try {
        $statement = $connection->query('SELECT * FROM status');
        $status = $statement->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($status);
    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}

// rota para adicionar um novo status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['statusName'])) {
        echo json_encode(['error' => 'O nome do usuário é obrigatório']);
        exit;
    }

    $statusName = $data['statusName'];

    try {
        $statement = $connection->prepare('INSERT INTO status (statusName) VALUES (:statusName)');
        $statement->bindParam(':statusName', $statusName);
        $statement->execute();
        $statusID = $connection->lastInsertId();
        echo json_encode(['id' => $statusID, 'statusName' => $statusName]);
    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}

// rota para atualizar status
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['id'] && $data['statusName'])) {
        echo json_encode(['error' => 'O ID e nome do usuário são obrigatórios']);
        exit;
    }

    $statusID = $data['statusID'];
    $statusName = $data['statusName'];


    try {
        $statement = $connection->prepare('UPDATE status SET statusName = :statusName WHERE statusID = :statusID');
        $statement->bindParam(':statusID', $statusID);
        $statement->bindParam(':statusName', $statusName);
        $statement->execute();
        echo json_encode(['success' => 'Nome do usuário alterado']);
    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}

// rota para deletar um status
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['statusID'])) {
        echo json_encode(['error' => 'O ID do usuário é obrigatório']);
        exit;
    }

    $statusID = $data['statusID'];

    try {
        $statement = $connection->prepare('DELETE FROM status WHERE statusID = :statusID');
        $statement->bindParam(':statusID', $statusID);
        $statement->execute();
        echo json_encode(['success' => 'Usuário deletado']);
    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}

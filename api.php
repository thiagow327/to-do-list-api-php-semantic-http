<?php

require 'database.php';

// rota para buscar todas as tarefas
if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET)) {
    try {
        $statement = $connection->query('SELECT * FROM tasks');
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

    if (empty($data['title'])) {
        echo json_encode(['error' => 'O título da tarefa é obrigatório']);
        exit;
    }

    $title = $data['title'];

    try {
        $statement = $connection->prepare('INSERT INTO tasks (title) VALUES (:title)');
        $statement->bindParam(':title', $title);
        $statement->execute();
        $taskId = $connection->lastInsertId();
        echo json_encode(['id' => $taskId, 'title' => $title, 'completed' => false]);
    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}

// rota para marcar uma tarefa como concluida
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['id'])) {
        echo json_encode(['error' => 'O ID da tarefa é obrigatório']);
        exit;
    }

    $taskId = $data['id'];

    try {
        $statement = $connection->prepare('UPDATE tasks SET completed = 1 WHERE id = :id');
        $statement->bindParam(':id', $taskId);
        $statement->execute();
        echo json_encode(['success' => true]);
    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}

// rota para deletar uma tarefa
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['id'])) {
        echo json_encode(['error' => 'O ID da tarefa é obrigatório']);
        exit;
    }

    $taskId = $data['id'];

    try {
        $statement = $connection->prepare('DELETE FROM tasks WHERE id = :id');
        $statement->bindParam(':id', $taskId);
        $statement->execute();
        echo json_encode(['success' => true]);
    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}

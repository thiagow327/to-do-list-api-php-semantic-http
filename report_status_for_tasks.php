<?php

require 'database.php';

// Busca quantidade de tarefas por status
if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET)) {
    try {
        $statement = $connection->query('SELECT COUNT(tasks.taskID) AS tasks, status.statusName FROM tasks INNER JOIN status ON tasks.statusID = status.statusID GROUP BY status.statusName');
        $tasks = $statement->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($tasks);
    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}

<?php

require 'database.php';

// Busca quantidade de tarefas por usuario
if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET)) {
    try {
        $statement = $connection->query('SELECT COUNT(tasks.taskID) AS totalDeTarefas, users.userName FROM tasks INNER JOIN users on tasks.userID = users.userID GROUP BY users.userName');
        $tasks = $statement->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($tasks);
    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}

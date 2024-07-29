<?php

require 'database.php';

# Quais usuarios cuja segundo letra do primeiro nome seja 'a'
# E que possuem mais que 2 tarefas com status concluido
# Ou pelo menos 1 tarefa em andamento

if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET)) {
    try {
        $statement = $connection->query('
        SELECT COUNT(t.taskID) as quantidadeDeTarefas, s.statusName, u.userName
        FROM tasks t
        INNER JOIN users u ON t.userID = u.userID
        INNER JOIN status s ON t.statusID = s.statusID
        WHERE u.userName LIKE "_a%"
        GROUP BY u.userName, s.statusName
        HAVING (quantidadeDeTarefas > 2 AND s.statusName = "concluido") 
	    OR (quantidadeDeTarefas >= 1 AND s.statusName = "em andamento")
        ');
        $tasks = $statement->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($tasks);
    } catch (PDOException $error) {
        echo json_encode(['error' => $error->getMessage()]);
    }
}

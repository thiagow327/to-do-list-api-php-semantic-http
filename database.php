<?php

$host = 'localhost';
$dbName = 'to-do-list';
$port = 3306;
$user = 'root';
$password = 'root';

try {
    $connection = new PDO("mysql:host=$host;port=$port;dbname=$dbName;charset=utf8", $user, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    echo 'Erro na conexão com o banco de dados: ' . $error->getMessage();
}

<?php
// config/database.php
$host = "db";
$port = "3306";
$db   = "tekopora_db";
$user = "root";
$pass = ""; 
$charset = "utf8mb4";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $conn = new PDO($dsn, $user, $pass, $options);

} catch (PDOException $e) {
    die("ERROR DE CONEXIÓN A BD: " . $e->getMessage() . " | Host: " . $host . " | DB: " . $db);
}
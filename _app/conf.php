
<?php
$host = 'localhost';
$username = "root";
$password = "";
$db_log = 'abastecimento';


/* CONEXÃO BANCO DE DADOS LOGISTICA */
$dsn_log = "mysql:host=$host;dbname=$db_log;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn_log, $username, $password, $options);
} catch (\PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}


?>
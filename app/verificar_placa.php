<?php
require_once "../_app/conf.php";
if (isset($_GET['placa'])) {
    $placa = strtoupper(trim($_GET['placa'])); // Recebe a placa
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM veiculos WHERE veiculo_placa = :placa");
    $stmt->bindParam(':placa', $placa);
    $stmt->execute();
    $existe = $stmt->fetchColumn() > 0;
    echo json_encode(['existe' => $existe]);
}
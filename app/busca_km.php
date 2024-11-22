<?php
require_once "../../../../_app/conf.php";

header('Content-Type: text/plain; charset=utf-8');

$abast_data = $_POST['abast_data'] ?? '';
$veiculo_id = $_POST['veiculo_id'] ?? '';

error_log("abast_data: $abast_data, veiculo_id: $veiculo_id");

if ($abast_data && $veiculo_id) {
    $formattedDate = DateTime::createFromFormat('Y-m-d', $abast_data);
    if ($formattedDate && $formattedDate->format('Y-m-d') === $abast_data) {
        $stmt = $frota->prepare('SELECT abast_km FROM abastecimento WHERE abast_data = :data AND veiculo_id = :veiculo_id');
        $stmt->execute(['data' => $abast_data, 'veiculo_id' => $veiculo_id]);
        $results = $stmt->fetchAll();

        if ($results) {
            $km_anteriores = array_map(function($row) {
                return htmlspecialchars($row['abast_km'], ENT_QUOTES, 'UTF-8');
            }, $results);

            $km_anterior_nao_vazio = array_filter($km_anteriores, function($km) {
                return !empty($km);
            });

            if (!empty($km_anterior_nao_vazio)) {
                echo implode(',', $km_anteriores);
            } else {
                $stmt = $frota->prepare('SELECT MAX(abast_km) as max_km FROM abastecimento WHERE veiculo_id = :veiculo_id AND abast_data <= :data');
                $stmt->execute(['veiculo_id' => $veiculo_id, 'data' => $abast_data]);
                $result = $stmt->fetch();
                if ($result && $result['max_km']) {
                    echo htmlspecialchars($result['max_km'], ENT_QUOTES, 'UTF-8');
                } else {
                    echo 'Sem Histórico';
                }
            }
        } else {
            $stmt = $frota->prepare('SELECT MAX(abast_km) as max_km FROM abastecimento WHERE veiculo_id = :veiculo_id AND abast_data <= :data');
            $stmt->execute(['veiculo_id' => $veiculo_id, 'data' => $abast_data]);
            $result = $stmt->fetch();
            if ($result && $result['max_km']) {
                echo htmlspecialchars($result['max_km'], ENT_QUOTES, 'UTF-8');
            } else {
                echo 'Sem Histórico';
            }
        }
    } else {
        echo 'Formato de data inválido';
    }
} else {
    echo 'Entrada Inválida';
}
?>

<?php
require_once "../../../../../_app/conf.php";
require_once "../../../../../_app/Config.inc.php";
// Simulação de consulta ao banco de dados
//$limiteCaracteres = 5; // Substitua pelo código real para buscar o valor do banco de dados
//echo $limiteCaracteres;
?>

<?php
header('Content-Type: application/json');

    // Valor enviado pelo usuário e o parâmetro
    $inputValue = $_POST['inputValue'];
    $param = $_POST['param'];

    // Consulta o valor permitido baseado no parâmetro
    $stmt = $pdo->prepare("SELECT valor_permitido FROM tabela WHERE id = :param");
    $stmt->bindParam(':param', $param, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbValue = $row['valor_permitido'];

        // Retorna o valor do banco de dados
        echo json_encode(['success' => true, 'dbValue' => $dbValue]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Valor não encontrado']);
    }


$pdo = null;
?>
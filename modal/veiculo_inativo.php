<?php
require_once "../_app/conf.php";

$veiculoId = $_POST['inativo_id'] ?? null;

if ($veiculoId) {
    $veiculo = "SELECT * FROM veiculos WHERE veiculo_id = :veiculo_id";
    $data = $pdo->prepare($veiculo);
    $data->bindParam(':veiculo_id', $veiculoId, PDO::PARAM_INT);
    $data->execute();
    $resultado = $data->fetch(PDO::FETCH_ASSOC);
    if ($resultado) {
?>
        O veículo <strong><?= $resultado['veiculo_modelo'] ?></strong>, não pode ser abastecido, pois o mesmo se encontra inativo no sistema.<br />
        Para alterar seu status, clica no botão abaixo.
<?php
    } else {
        echo "<p>Veículo não encontrado.</p>";
    }
} else {
    echo "<p>ID do veículo não fornecido.</p>";
}



?>
<div class="modal-footer">
    <form action="" method="POST">
        <input type="hidden" name="veiculo_id" value="<?= $veiculoId ?>">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
        <button type="submit" name="status" class="btn btn-primary">Ativar veículo</button>
    </form>

</div>
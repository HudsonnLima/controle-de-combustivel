<?php

require_once "../_app/conf.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$veiculoId = $_POST['veiculo_id'] ?? null;


$veiculo = "SELECT * FROM veiculos WHERE veiculo_id = :veiculo_id";
$data = $pdo->prepare($veiculo);
$data->bindParam(':veiculo_id', $veiculoId, PDO::PARAM_INT);
$data->execute();
$resultado = $data->fetch(PDO::FETCH_ASSOC);


$veiculo_id = $resultado['veiculo_id'];
$sql = "SELECT COUNT(*) AS total_registros FROM abastecimento WHERE veiculo_id = :veiculo_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':veiculo_id', $veiculo_id, PDO::PARAM_INT);
$stmt->execute();
$total = $stmt->fetch(PDO::FETCH_ASSOC);


?>

<div class="container">

    <?php if ($total['total_registros'] >= 1): ?>
        <div class="">
            <?php if ($resultado['veiculo_status'] == 0) { ?>
                O veículo <strong><?= $resultado['veiculo_modelo'] ?></strong>, possui <?php echo $total['total_registros']; ?> abastecimentos cadastrados, você não poderá excluir, mas poderá deixa-lo inativo.<br />
                Para reativá-lo, vá em <a class="link" href="editar_veiculo.php?placa=<?php echo $resultado['veiculo_placa']; ?>">editar veículo</a> e ative novamente!<br />
            <?php } else { ?>
                O veículo <strong><?= $resultado['veiculo_modelo'] ?></strong>, não pode ser excluído, pois possui <?php echo $total['total_registros']; ?> abastecimentos cadastrados.<br />
            <?php } ?>

        </div>
        <div class="modal-footer">
            <form action="" method="POST">
                <input type="hidden" name="veiculo_id" value="<?= $veiculoId ?>">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                <?php if ($resultado['veiculo_status'] == 0) { ?>
                    <button type="submit" name="status" class="btn btn-primary">Desativar veículo</button>
                <?php } else { ?>
                    <button type="submit" name="status" class="btn btn-primary">Ativar veículo</button>
                <?php } ?>

            </form>

        </div>


    <?php else: ?>
        Deseja realmente excluir o veículo do sistema?
        <br/><br/>
        <strong>Veículo:</strong> <?= $resultado['veiculo_modelo']; ?> <br/>
        <strong>Placa:</strong> <?= $resultado['veiculo_placa']; ?>  <br/>
        <strong>Ano/Modelo:</strong> <?= $resultado['veiculo_ano_fab'] .'/' . $resultado['veiculo_ano_modelo'] ; ?> 
        <br /><br />

        <div class="modal-footer">
            <form action="" method="POST">
                <input type="hidden" name="veiculo_id" value="<?= $veiculoId ?>">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                <button type="submit" name="delete" class="btn btn-danger">Excluir</button>
            </form>

        </div>


    <?php endif; ?>

</div>
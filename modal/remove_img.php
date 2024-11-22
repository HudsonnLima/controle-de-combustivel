<?php

require_once "../_app/conf.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$abast_id = $_POST['abast_id'] ?? null;
$img_id = $_POST['img_id'] ?? null;


// Verifique se o ID foi passado
if (!$abast_id) {
    echo "ID não fornecido.";
    exit;
}

// Consulta ao banco de dados
$read = "SELECT * FROM abastecimento_img WHERE img_id = :img_id";
$stmt = $pdo->prepare($read);
$stmt->bindParam(':img_id', $img_id, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifique se existem registros
if (count($data) === 0) {
    echo "Nenhuma imagem encontrada.";
    exit;
}
?>

<div class="container">

    <br />
    <?php foreach ($data as $img): ?>
        <div class="mb-4">
            <img src="imagens/<?= htmlspecialchars($img['img_path']) ?>"
                width="800" height="640"
                class="d-block w-100 img_abast"
                alt="Imagem do abastecimento">

            <div class="modal-footer">
                <form action="actions/excluir_img.php" method="POST">
                    <input type="hidden" name="img_path" value="<?= htmlspecialchars($img['img_path']) ?>">
                    <input type="hidden" name="img_id" value="<?= htmlspecialchars($img['img_id']) ?>">
                    <input type="hidden" name="abast_id" value="<?= htmlspecialchars($abast_id) ?>">
                    <!--<button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button>-->
                    <button type="submit" name="delete" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>
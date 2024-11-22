<?php
require_once "../../../../_app/conf.php";

$loja = $_POST['empresa_id'];

    $query = "SELECT * FROM lojas WHERE empresa_id = {$loja} ORDER BY loja_nome ASC ";
    $lojas = $pdo->prepare($query);
    $lojas->execute();
    $lojas = $pdo->prepare($query);
    $lojas->execute();
    $count = $lojas->rowCount();
?>
<option selected disabled value="">Selecione a loja</option>
<?php

foreach($lojas as $store):
if($count == 1){ ?>
<option selected value="<?php echo $store["loja_id"]; ?>"><?= $store['loja_nome']?></option>
<?php
}else{
?>
<option value="<?php echo $store["loja_id"]; ?>"><?= $store['loja_nome'].' - '.$store['loja_cidade']?></option>

<?php } endforeach; 
?>




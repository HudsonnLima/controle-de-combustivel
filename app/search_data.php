<?php
include "../../../../_app/conf.php";
if(isset($_POST['busca'])){
$produto = $_POST['busca'];
$produtos = $pdo->prepare("SELECT * FROM produtos WHERE descricao LIKE '%".$produto."%' OR produto LIKE '%".$produto."%'  ORDER BY produto ASC");
$produtos->execute();
$result = array();
while ($prod = $produtos->fetch(PDO::FETCH_OBJ)) {
   array_push($result, (object) [
      "label" => $prod->descricao,
      "produto_id" => $prod->produto_id,
      "preco" => $prod->preco,
      "medida_id" => $prod->medida_id,
      "estoque" => $prod->estoque
   ]);
}
echo json_encode($result);
}
exit;

?>

<?php
require_once "../_app/conf.php";
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

var_dump($dados);

$km_anterior = (int)$dados['abast_km_anterior'];
if (!is_int($km_anterior)) {
    $km_anterior = '';
} else {
    $km_anterior = $dados['abast_km_anterior'];
}

?>
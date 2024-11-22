<?php
session_start(); //Iniciar a sessao
ob_start();
require_once "../_app/conf.php";
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);


//verificar se o usuario clicou no botao
if (!empty($dados['CadVeiculo'])) {

    if (in_array('', $dados)) :
        $_SESSION['msg'] = '<div class="trigger alert">Preencha todos os campos!</div>';
        header("Location: ../");
    endif;


            $veiculos = "INSERT INTO veiculos (veiculo_placa, veiculo_modelo, veiculo_marca, veiculo_cor, veiculo_tipo, veiculo_ano_fab, veiculo_ano_modelo, veiculo_combustivel, veiculo_uf, veiculo_status) VALUE (:veiculo_placa, :veiculo_modelo, :veiculo_marca, :veiculo_cor, :veiculo_tipo, :veiculo_ano_fab, :veiculo_ano_modelo, :veiculo_combustivel, :veiculo_uf, :veiculo_status)";
            $data = $pdo->prepare($veiculos);
            $data->bindParam(':veiculo_placa', $dados['veiculo_placa']);
            $data->bindParam(':veiculo_modelo', $dados['veiculo_modelo']);
            $data->bindParam(':veiculo_marca', $dados['veiculo_marca']);
            $data->bindParam(':veiculo_cor', $dados['veiculo_cor']);
            $data->bindParam(':veiculo_tipo', $dados['veiculo_tipo']);
            $data->bindParam(':veiculo_ano_fab', $dados['veiculo_ano_fab']);
            $data->bindParam(':veiculo_ano_modelo', $dados['veiculo_ano_modelo']);
            $data->bindParam(':veiculo_combustivel', $dados['veiculo_combustivel']);
            $data->bindParam(':veiculo_uf', $dados['veiculo_uf']);
            $data->bindParam(':veiculo_status', $dados['veiculo_status']);
            $data->execute();
            
   
    
    $_SESSION['msg'] = '<div class="trigger accept">Veículo cadastrado com sucesso!</div>';
    header("Location: ../");
} else {
    $_SESSION['msg'] = '<div class="trigger alert">Erro, veículo não cadastrado!</div>';
    header("Location: ../");
}

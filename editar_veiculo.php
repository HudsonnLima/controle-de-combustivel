<?php
session_start(); //Iniciar a sessao
ob_start();
require_once("_app/conf.php");
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$placa = filter_input(INPUT_GET, 'placa', FILTER_VALIDATE_INT);
//$placa = $_GET['placa'];



?>

<!doctype html>
<html lang="pt-br">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gerenciamento de Combustível</title>
  <link href="css/style.css" rel="stylesheet">
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.2/css/fontawesome.min.css">
  <script src="https://kit.fontawesome.com/7305dbe23e.js" crossorigin="anonymous"></script>

</head>

<body>


  <article class="container">
    <label class="titulo">Editar cadastro do veículo:</label>
    <hr>
    <?php



    $pdos = "SELECT * FROM veiculos WHERE veiculo_placa =:placa";
    $data = $pdo->prepare($pdos);
    $data->bindParam(':placa', $_GET['placa']);
    $data->execute();
    foreach ($data as $veiculos);


    ?>

    <form method="POST" action="actions/editar_veiculo.php">

      <input type="hidden" name="veiculo_id" value="<?php if (isset($veiculos['veiculo_id'])) echo $veiculos['veiculo_id']; ?>" />

      <div class="row g-2">
        <div class="col-md-2">
          <div class="form-floating">
            <input type="text" class="form-control" name="veiculo_placa" id="placa" maxlength="8" value="<?php if (isset($veiculos['veiculo_placa'])) echo $veiculos['veiculo_placa']; ?>" />
            <label for="floatingInputGrid">Placa:</label>
          </div>
        </div>


        <div class="col-md-4">
          <div class="form-floating">
            <input type="text" class="form-control" name="veiculo_modelo" id="modelo" value="<?php if (isset($veiculos['veiculo_modelo'])) echo $veiculos['veiculo_modelo']; ?>" />
            <label for="floatingInputGrid">Modelo:</label>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-floating">
            <input type="text" class="form-control" name="veiculo_marca" id="marca" value="<?php if (isset($veiculos['veiculo_marca'])) echo $veiculos['veiculo_marca']; ?>" />
            <label for="floatingInputGrid">Marca:</label>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-floating">
            <input type="text" class="form-control" name="veiculo_cor" id="cor" value="<?php if (isset($veiculos['veiculo_cor'])) echo $veiculos['veiculo_cor']; ?>" />
            <label for="floatingInputGrid">Cor:</label>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-floating ">
            <select class="form-select" name="veiculo_tipo" id="tipo">
              <option disabled="disabled" value=""> Selecione o tipo </option>
              <option value="1" <?php if (isset($veiculos['veiculo_tipo']) && $veiculos['veiculo_tipo'] == 1) echo 'selected="selected"'; ?>> Utilitario </option>
              <option value="2" <?php if (isset($veiculos['veiculo_tipo']) && $veiculos['veiculo_tipo'] == 2) echo 'selected="selected"'; ?>> Kombi / Van </option>
              <option value="3" <?php if (isset($veiculos['veiculo_tipo']) && $veiculos['veiculo_tipo'] == 3) echo 'selected="selected"'; ?>> Caminhao </option>
            </select>
            <label for="floatingSelectGrid">Tipo:</label>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-floating ">
            <select class="form-select" name="veiculo_ano_fab" id="fabricacao">
              <option disabled="disabled" value=""> Selecione o ano </option>
              <option value="2008" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2008) echo 'selected="selected"'; ?>> 2008 </option>
              <option value="2009" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2009) echo 'selected="selected"'; ?>> 2009 </option>
              <option value="2010" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2010) echo 'selected="selected"'; ?>> 2010 </option>
              <option value="2011" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2011) echo 'selected="selected"'; ?>> 2011 </option>
              <option value="2012" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2012) echo 'selected="selected"'; ?>> 2012 </option>
              <option value="2013" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2013) echo 'selected="selected"'; ?>> 2013 </option>
              <option value="2014" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2014) echo 'selected="selected"'; ?>> 2014 </option>
              <option value="2015" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2015) echo 'selected="selected"'; ?>> 2015 </option>
              <option value="2016" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2016) echo 'selected="selected"'; ?>> 2016 </option>
              <option value="2017" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2017) echo 'selected="selected"'; ?>> 2017 </option>
              <option value="2018" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2018) echo 'selected="selected"'; ?>> 2018 </option>
              <option value="2019" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2019) echo 'selected="selected"'; ?>> 2019 </option>
              <option value="2020" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2020) echo 'selected="selected"'; ?>> 2020 </option>
              <option value="2021" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2021) echo 'selected="selected"'; ?>> 2021 </option>
              <option value="2022" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2022) echo 'selected="selected"'; ?>> 2022 </option>
              <option value="2023" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2023) echo 'selected="selected"'; ?>> 2023 </option>
              <option value="2024" <?php if (isset($veiculos['veiculo_ano_fab']) && $veiculos['veiculo_ano_fab'] == 2024) echo 'selected="selected"'; ?>> 2024 </option>


            </select>
            <label for="floatingSelectGrid">Ano fabricacao:</label>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-floating ">
            <select class="form-select" name="veiculo_ano_modelo" id="ano_modelo">
              <option disabled="disabled" value=""> Selecione o ano </option>
              <option value="2008" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2008) echo 'selected="selected"'; ?>> 2008 </option>
              <option value="2009" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2009) echo 'selected="selected"'; ?>> 2009 </option>
              <option value="2010" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2010) echo 'selected="selected"'; ?>> 2010 </option>
              <option value="2011" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2011) echo 'selected="selected"'; ?>> 2011 </option>
              <option value="2012" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2012) echo 'selected="selected"'; ?>> 2012 </option>
              <option value="2013" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2013) echo 'selected="selected"'; ?>> 2013 </option>
              <option value="2014" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2014) echo 'selected="selected"'; ?>> 2014 </option>
              <option value="2015" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2015) echo 'selected="selected"'; ?>> 2015 </option>
              <option value="2016" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2016) echo 'selected="selected"'; ?>> 2016 </option>
              <option value="2017" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2017) echo 'selected="selected"'; ?>> 2017 </option>
              <option value="2018" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2018) echo 'selected="selected"'; ?>> 2018 </option>
              <option value="2019" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2019) echo 'selected="selected"'; ?>> 2019 </option>
              <option value="2020" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2020) echo 'selected="selected"'; ?>> 2020 </option>
              <option value="2021" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2021) echo 'selected="selected"'; ?>> 2021 </option>
              <option value="2022" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2022) echo 'selected="selected"'; ?>> 2022 </option>
              <option value="2023" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2023) echo 'selected="selected"'; ?>> 2023 </option>
              <option value="2024" <?php if (isset($veiculos['veiculo_ano_modelo']) && $veiculos['veiculo_ano_modelo'] == 2024) echo 'selected="selected"'; ?>> 2024 </option>
            </select>
            <label for="floatingSelectGrid">Ano Modelo:</label>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-floating ">
            <select class="form-select" name="veiculo_combustivel" id="tipo">
              <option disabled="disabled" value=""> Selecione o combustível </option>
              <option value="1" <?php if (isset($veiculos['veiculo_combustivel']) && $veiculos['veiculo_combustivel'] == 1) echo 'selected="selected"'; ?>> Gasolina </option>
              <option value="2" <?php if (isset($veiculos['veiculo_combustivel']) && $veiculos['veiculo_combustivel'] == 2) echo 'selected="selected"'; ?>> Gasolina / Etanol </option>
              <option value="3" <?php if (isset($veiculos['veiculo_combustivel']) && $veiculos['veiculo_combustivel'] == 3) echo 'selected="selected"'; ?>> Etanol </option>
              <option value="4" <?php if (isset($veiculos['veiculo_combustivel']) && $veiculos['veiculo_combustivel'] == 4) echo 'selected="selected"'; ?>> Diesel </option>
              <option value="5" <?php if (isset($veiculos['veiculo_combustivel']) && $veiculos['veiculo_combustivel'] == 5) echo 'selected="selected"'; ?>> GNV </option>


            </select>
            <label for="floatingSelectGrid">Combustível:</label>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-floating">
            <input type="teste" class="form-control" name="veiculo_uf" id="uf" value="<?php if (isset($veiculos['veiculo_uf'])) echo $veiculos['veiculo_uf']; ?>" />
            <label for="floatingInputGrid">UF - Licenciamento:</label>
          </div>


        </div>

        <div class="col-md-2">
          <div class="form-floating ">
            <select class="form-select" name="veiculo_status" id="veiculo_status">
              <option disabled="disabled" value=""> Selecione o status </option>
              <option value="0" <?php if (isset($veiculos['veiculo_status']) && $veiculos['veiculo_status'] == 0) echo 'selected="selected"'; ?>> Ativo </option>
              <option value="1" <?php if (isset($veiculos['veiculo_status']) && $veiculos['veiculo_status'] == 1) echo 'selected="selected"'; ?>> Inativo </option>

            </select>
            <label for="floatingSelectGrid">Status:</label>
          </div>
        </div>

      </div>
      <br />
      <input type="submit" name="EditarVeiculo" value="Editar Veículo" class="btn-orange">

    </form>
  </article>
  <script src="js/placa.js"></script>
</body>

</html>
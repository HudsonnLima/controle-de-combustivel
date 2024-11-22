<?php
session_start();
ob_start();
require_once "_app/conf.php";
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);





?>
<!doctype html>
<html lang="pt-br">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gerenciamento de Combustível</title>
  <link href="css/style.css" rel="stylesheet">




  
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <!-- Inclua o jQuery UI depois -->
  <script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
  <!-- Inclua o CSS do jQuery UI, se necessário -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
  <!-- Inclua jQuery e Bootstrap JS -->


  <!--BOOTSTRAP 5.1.3-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <!--//BOOTSTRAP 5.1.3-->

  <!--ICONES-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.2/css/fontawesome.min.css">
  <script src="https://kit.fontawesome.com/7305dbe23e.js" crossorigin="anonymous"></script>

  <!--//ICONES-->


  


</head>

<body>
  <article class="container">

    <?php
    $empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
    if ($empty) :
    //WSErro("Você tentou editar uma fornecedor que não existe no sistema!", WS_INFOR);
    endif;
    ?>


    <label class="titulo">Cadastrar veículo:</label>
    <hr>

    <?php
    if (isset($_SESSION['msg'])) {
      echo $_SESSION['msg'];
      unset($_SESSION['msg']);
    }

    if (isset($dados['status'])) {
      $stmt = $pdo->prepare("SELECT * FROM veiculos WHERE veiculo_id = :veiculo_id");
      $stmt->bindParam(':veiculo_id', $dados['veiculo_id'], PDO::PARAM_INT);
      $stmt->execute();
      if ($stmt->rowCount() > 0) {
        $veiculo = $stmt->fetch(PDO::FETCH_ASSOC);
        $novo_status = $veiculo['veiculo_status'] == 0 ? 1 : 0;

        // Atualizar o status do veículo
        $update = $pdo->prepare("UPDATE veiculos SET veiculo_status = :novo_status WHERE veiculo_id = :veiculo_id");
        $update->bindParam(':novo_status', $novo_status, PDO::PARAM_INT);
        $update->bindParam(':veiculo_id', $veiculo['veiculo_id'], PDO::PARAM_INT);

        if ($update->execute()) {
          echo $novo_status . '<br/>';

          if ($veiculo['veiculo_status'] == 1) {
            $_SESSION['msg'] = "<div class='trigger accept'>O status do veículo <strong>{$veiculo['veiculo_modelo']}</strong> foi alterado para ativo!</div>";
            header("Location: abastecimento.php?placa={$veiculo['veiculo_placa']}");
            die;
          } elseif ($veiculo['veiculo_status'] == 0) {
            $_SESSION['msg'] = "<div class='trigger accept'>O status do veículo <strong>{$veiculo['veiculo_modelo']}</strong> foi alterado para inativo!</div>";
          } else {
            $_SESSION['msg'] = "<div class='trigger accept'>Status não encontrado</div>";
            header("Location: ./");
          }
          header("Location: ./");
        } else {
          $_SESSION['msg'] = "<div class='trigger accept'>Falha ao atualizar o status do veículo. <strong>{$veiculo['veiculo_modelo']}</strong>!</div>";
          header("Location: ./");
        }
      } else {
        $_SESSION['msg'] = "<div class='trigger accept'>Veículo não encontrado</div>";
        header("Location: ./");
      }
    }






    if (isset($dados['delete'])) {

      $veiculo = "SELECT * FROM veiculos WHERE veiculo_id = :veiculo_id";
      $data = $pdo->prepare($veiculo);
      $data->bindParam(':veiculo_id', $dados['veiculo_id'], PDO::PARAM_INT);
      $data->execute();
      $resultado = $data->fetch(PDO::FETCH_ASSOC);

      $_SESSION['msg'] = "<div class='trigger accept'>Veículo <strong>{$resultado['veiculo_modelo']} e placa {$resultado['veiculo_placa']}</strong> deletado com sucesso!</div>";
      header("Location: ./");

      $excluir = "DELETE FROM veiculos WHERE veiculo_id = :veiculo_id";
      $deletar = $pdo->prepare($excluir);
      $deletar->bindParam(':veiculo_id', $dados['veiculo_id'], PDO::PARAM_INT);
      $deletar->execute();
    }
    ?>



    <form method="POST" action="actions/cadastrar_veiculos.php">

      <div class="row g-2">
        <div class="col-md-2">
          <div class="form-floating">
            <input type="text" class="form-control" id="placa" name="veiculo_placa" maxlength="8" id="placa" value="<?php if (isset($dados['placa'])) echo $dados['placa']; ?>" />
            <label for="floatingInputGrid">Placa:</label>
          </div>
        </div>


        <div class="col-md-4">
          <div class="form-floating">
            <input type="text" class="form-control" name="veiculo_modelo" id="modelo" value="<?php if (isset($dados['modelo'])) echo $dados['modelo']; ?>" />
            <label for="floatingInputGrid">Modelo:</label>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-floating">
            <input type="text" class="form-control" name="veiculo_marca" id="marca" value="<?php if (isset($dados['marca'])) echo $dados['marca']; ?>" />
            <label for="floatingInputGrid">Marca:</label>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-floating">
            <input type="text" class="form-control" name="veiculo_cor" id="cor" value="<?php if (isset($dados['cor'])) echo $dados['cor']; ?>" />
            <label for="floatingInputGrid">Cor:</label>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-floating ">
            <select class="form-select" name="veiculo_tipo" id="tipo">
              <option selected disabled="disabled" value=""> Selecione o tipo </option>

              <option value="1" <?php if (isset($dados['tipo']) && $dados['tipo'] == 1) ?>> Utilitario </option>
              <option value="2" <?php if (isset($dados['tipo']) && $dados['tipo'] == 2) ?>> Kombi/Van </option>
              <option value="3" <?php if (isset($dados['tipo']) && $dados['tipo'] == 3) ?>> Caminhao </option>

            </select>
            <label for="floatingSelectGrid">Tipo:</label>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-floating ">
            <select class="form-select" name="veiculo_ano_fab" id="fabricacao">
              <option selected disabled="disabled" value=""> Selecione o ano </option>
              <option value="2010"> 2008 </option>
              <option value="2010"> 2009 </option>
              <option value="2010"> 2010 </option>
              <option value="2011"> 2011 </option>
              <option value="2012"> 2012 </option>
              <option value="2013"> 2013 </option>
              <option value="2014"> 2014 </option>
              <option value="2015"> 2015 </option>
              <option value="2016"> 2016 </option>
              <option value="2017"> 2017 </option>
              <option value="2018"> 2018 </option>
              <option value="2019"> 2019 </option>
              <option value="2020"> 2020 </option>
              <option value="2021"> 2021 </option>
              <option value="2022"> 2022 </option>
              <option value="2023"> 2023 </option>
              <option value="2024"> 2024 </option>
            </select>
            <label for="floatingSelectGrid">Ano fabricacao:</label>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-floating ">
            <select class="form-select" name="veiculo_ano_modelo" id="ano_modelo">
              <option selected disabled="disabled" value=""> Selecione o ano </option>
              <option value="2010"> 2008 </option>
              <option value="2010"> 2009 </option>
              <option value="2010"> 2010 </option>
              <option value="2011"> 2011 </option>
              <option value="2012"> 2012 </option>
              <option value="2013"> 2013 </option>
              <option value="2014"> 2014 </option>
              <option value="2015"> 2015 </option>
              <option value="2016"> 2016 </option>
              <option value="2017"> 2017 </option>
              <option value="2018"> 2018 </option>
              <option value="2019"> 2019 </option>
              <option value="2020"> 2020 </option>
              <option value="2021"> 2021 </option>
              <option value="2022"> 2022 </option>
              <option value="2023"> 2023 </option>
              <option value="2024"> 2024 </option>
            </select>
            <label for="floatingSelectGrid">Ano Modelo:</label>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-floating ">
            <select class="form-select" name="veiculo_combustivel" id="tipo">
              <option selected disabled="disabled" value=""> Selecione o combustível </option>
              <option value="0"> Gasolina </option>
              <option value="1"> Gasolina / Etanol </option>
              <option value="2"> Gasolina / Etanol / GNV </option>
              <option value="3"> Etanol </option>
              <option value="4"> Diesel </option>
              <option value="5"> GNV </option>

            </select>
            <label for="floatingSelectGrid">Combustível:</label>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-floating">
            <input type="teste" class="form-control" name="veiculo_uf" id="uf" value="" />
            <label for="floatingInputGrid">UF - Licenciamento:</label>
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-floating ">
            <select class="form-select" name="veiculo_status" id="veiculo_status">
              <option selected disabled="disabled" value=""> Selecione o status </option>
              <option selected value="0"> Ativo </option>
              <option value="1"> Inativo </option>
            </select>
            <label for="floatingSelectGrid">Status:</label>
          </div>
        </div>

      </div>
      <br />
      <input type="submit" id="submitBtn" name="CadVeiculo" value="Cadastrar" class="btn-orange">


    </form>

    <label class="titulo">Veículos Cadastrados:</label>

    <hr>
    <table id="search_table" class="table table-striped">
      <tr class="table-dark">
        <td scope="col" align="left" style="width:10%;">Placa</td>
        <td scope="col" align="left" style="width:40%;">Modelo</td>
        <td scope="col" align="left" style="width:10%;">Ano</td>
        <td scope="col" align="left" style="width:12%;">Marca</td>
        <td scope="col" align="center" style="width:10%;">KM</td>
        <td scope="col" align="center" style="width:10%;">Tipo</td>
        <!--<td scope="col" align="center" style="width:120px;">Combustível</td>
      <td scope="col" align="center" style="width:120px;">UF</td>-->
        <td scope="col" align="center" style="width:20%;" colspan="4">Ações</td>

      </tr>


      <tr class="table-light">
        <?php
        $pdos = "SELECT * FROM veiculos";
        $data = $pdo->prepare($pdos);
        $data->execute();
        foreach ($data as $veiculos) :
        ?>

          <td align="left" style=""><?= $veiculos['veiculo_placa']; ?></td>
          <td align="left" style=""><?= $veiculos['veiculo_modelo']; ?></td>

          </td>

          <td align="left" style=""><?= $veiculos['veiculo_ano_fab'] ?></td>

          <td align="left" style=""><?= $veiculos['veiculo_marca'] ?></td>
          <?php
          $veiculo_id = $veiculos['veiculo_id'];
          $sql = "SELECT * FROM abastecimento WHERE veiculo_id = {$veiculo_id} ORDER BY abast_id DESC LIMIT 1";
          // Preparar a consulta
          $stmt = $pdo->prepare($sql);
          // Executar a consulta
          $stmt->execute();
          // Obter o resultado como um array associativo
          $km = $stmt->fetch(PDO::FETCH_ASSOC);
          ?>
          <?php if (isset($km['abast_km'])) : ?>
            <td align="center"><?= $km['abast_km']; ?></td>
          <?php else : ?>
            <td align="center">0</td>
          <?php endif; ?>
          <!--<td align="center" style=""><?= $veiculos['veiculo_cor'] ?></td>-->

          <?php if (isset($veiculos['veiculo_tipo']) and $veiculos['veiculo_tipo'] == 1) : ?>
            <td align="center" style="">Utilitario</td>
          <?php elseif (isset($veiculos['veiculo_tipo']) and $veiculos['veiculo_tipo'] == 2) : ?>
            <td align="center" style="">Kombi / Van</td>
          <?php elseif (isset($veiculos['veiculo_tipo']) and $veiculos['veiculo_tipo'] == 3) : ?>
            <td align="center" style="">Caminhão</td>
          <?php endif ?>


          <!--<td align="center" style=""><?= $veiculos['veiculo_combustivel'] ?></td>
        <td align="center" style=""><?= $veiculos['veiculo_uf'] ?></td>-->


          <?php if (isset($veiculos['veiculo_status']) and $veiculos['veiculo_status'] == 0) : ?>
            <td align="center">
              <a href='editar_veiculo.php?placa=<?php echo $veiculos['veiculo_placa']; ?>' class="editar" title="Editar veículo"><i class="fas fa-edit icon"></i></a>
              <!--<a href='placa=<?php echo $veiculos['veiculo_placa']; ?>' class="mecanico" title="Mecanico"><i class="fa-solid fa-gear icon"></i></a>-->
              <a href='abastecimento.php?placa=<?php echo $veiculos['veiculo_placa']; ?>' class="abastecimento" title="Abastecimento"><i class="fa-solid fa-gas-pump icon"></i></a>
              <a data-bs-toggle="modal" data-bs-target=".myModalExclusao" data-veiculo-id="<?= $veiculos['veiculo_id'] ?>" class=""><i class="fa-solid fa-trash icon" title="Excluir"></i></a>
            </td>

          <?php elseif (isset($veiculos['veiculo_status']) and $veiculos['veiculo_status'] == 1) : ?>
            <td align="center">
              <a href='editar_veiculo.php?placa=<?php echo $veiculos['veiculo_placa']; ?>' class="editar" title="Editar veículo"><i class="fas fa-edit icon"></i></a>
              <!--<a href='placa=<?php echo $veiculos['veiculo_placa']; ?>' class="mecanico" title="Mecanico"><i class="fa-solid fa-gear icon"></i></a>-->
              <a data-bs-toggle="modal" data-bs-target=".ModalInativo" data-inativo-id="<?= $veiculos['veiculo_id'] ?>" class=""><i class="fa-solid fa-gas-pump icon" title="Abastecimento"></i></a>
              <a data-bs-toggle="modal" data-bs-target=".myModalExclusao" data-veiculo-id="<?= $veiculos['veiculo_id'] ?>" class=""><i class="fa-solid fa-trash icon" title="Excluir"></i></a>

            </td>
          <?php endif; ?>
      </tr>

    <?php
        endforeach;

    ?>
    </table>
  </article>
  <?php require_once "modal/excluir-veiculo.php"; ?>
  <?php require_once "modal/veiculo-inativo.php"; ?>

  <div class="clear"></div>
  <script src="js/placa.js"></script>
</body>

</html>